<template>
    <div>
         <b-card class="principal-card" title="Estado de su trámite:">
            <b-container>
                <b-row>
                    <b-col>
                        <estado-component title="Inicio" number="." color="light_gray" :hideDescription="true" :tail="true"/>
                    </b-col>
                </b-row>
                <div v-for="(grado_proc_mov, index) in mov_grado_procedimientos" :key="index">
                    <b-row>
                      <b-col v-if="grado_proc_mov.idgradproc_origen == idgrado_procedimiento_actual">
                          <estado-component :ruta="ruta" :idexpediente="idexpediente"
                                            :idgrado_procedimiento_actual="idgrado_procedimiento_actual"
                                            :showDescription="true"
                                            :tail="true"
                                            color="actual"
                                            :number="grado_proc_mov.nro_orden"
                                            :title="grado_proc_mov.nombre_procedimiento"
                                            :url_form="grado_proc_mov.url_formulario"
                                            :idrol_area="grado_proc_mov.idrol_area"
                                            :rol_area="grado_proc_mov.rol_area"
                                            :tipo_rol="grado_proc_mov.tipo_rol"
                                            :desc="grado_proc_mov.descripcion"
                                            :fecha="''"
                                            />
                      </b-col>
                      <b-col v-else-if="mostrar_actual_en_movimiento == false">
                          <estado-component :title="grado_proc_mov.nombre_procedimiento"
                                            :number="grado_proc_mov.nro_orden"
                                            :color="grado_proc_mov.estado"
                                            :url_form="grado_proc_mov.url_formulario"
                                            :idrol_area="grado_proc_mov.idrol_area"
                                            :rol_area="grado_proc_mov.rol_area"
                                            :tipo_rol="grado_proc_mov.tipo_rol"
                                            :desc="grado_proc_mov.descripcion"
                                            :fecha="grado_proc_mov.fecha"
                                            :tail="true"/>
                      </b-col>
                      <b-col v-else-if="grado_proc_mov.nro_orden < orden_actual_en_movimiento || grado_proc_mov.idgradproc_origen == movimientos[movimientos.length - 1].idgradproc_origen">
                          <estado-component :title="grado_proc_mov.nombre_procedimiento"
                                            :number="grado_proc_mov.nro_orden"
                                            :color="grado_proc_mov.estado"
                                            :url_form="grado_proc_mov.url_formulario"
                                            :idrol_area="grado_proc_mov.idrol_area"
                                            :rol_area="grado_proc_mov.rol_area"
                                            :tipo_rol="grado_proc_mov.tipo_rol"
                                            :desc="grado_proc_mov.descripcion"
                                            :fecha="grado_proc_mov.fecha"
                                            :tail="true"/>
                      </b-col>
                      <b-col v-else>
                          <estado-component :title="grado_proc_mov.nombre_procedimiento"
                                            :number="grado_proc_mov.nro_orden"
                                            color="gray"
                                            :hideDescription="true"
                                            :tail="true"/>
                      </b-col>
                    </b-row>
                </div>
                <b-row v-if="(grado_proc_actual != null && mostrar_actual_en_movimiento == false) && cont_proc_actual != null"> <!-- Procedimiento Actual -->
                    <b-col>
                        <estado-component :ruta="ruta" :idexpediente="idexpediente"
                                          :title="grado_proc_actual.nombre_procedimiento"
                                          :idgrado_procedimiento_actual="idgrado_procedimiento_actual"
                                          :number="cont_proc_actual"
                                          color="actual"
                                          :url_form="grado_proc_actual.url_formulario"
                                          :idrol_area="grado_proc_actual.idrol_area"
                                          :rol_area="grado_proc_actual.rol_area"
                                          :tipo_rol="grado_proc_actual.tipo_rol"
                                          :desc="grado_proc_actual.descripcion"
                                          :fecha="''"
                                          :showDescription="true"
                                          :tail="true"/>
                    </b-col>
                </b-row>
                <b-row v-if="resto_grado_procedimientos.length > 0">
                    <b-col>
                        <estado-component :title="'Procedimientos restantes...'" :number="'..'" color="gray" click="displayAll" @displayAll="displayRest" :tail="true"/>
                    </b-col>
                </b-row>
                <b-collapse v-model="showRest">
                    <div v-for="(grado_proc_rest, index) in resto_grado_procedimientos" :key="index">
                        <b-row>
                            <b-col>
                                <estado-component :title="grado_proc_rest.nombre_procedimiento" :number="grado_proc_rest.nro_orden" color="gray" :hideDescription="true" :tail="true"/>
                            </b-col>
                        </b-row>
                    </div>
                </b-collapse>
                <b-row>
                    <b-col>
                        <estado-component title="Fin" number="." color="light_gray" :hideDescription="true"/>
                    </b-col>
                </b-row>
                {{output}}
            </b-container>
        </b-card>
    </div>
</template>

<script>
    import EstadoComponent from '../components/EstadoComponent';

    export default {
        components:{
            EstadoComponent,
        },
        props : [
                    'ruta',
                    'idgrado_modalidad',
                    'idexpediente',
                    'idgrado_procedimiento_actual'
                ],
        data() {
            return {
                grado_proc_actual : null,
                cont_proc_actual : null,
                cont_resto_procedimientos: null,
                mostrar_actual_en_movimiento : false, //mostrando actual en casos de estados de devuelto u observado
                orden_actual_en_movimiento : null, // nro de orden del proc actual en caso hay denegaciones
                movimientos : [],
                mov_grado_procedimientos : [], //procedimientos segun movimientos
                resto_grado_procedimientos : [],
                showPast: false,
                showRest: false,
                pastStates: [],
                actualState: null,
                restStates: [],
                output: '',
            }
        },
        methods : {
            getGradoProcedimientoActual() { // obtener el procedimiento actual
                let me = this

                axios.get(`${this.ruta}/grado_procedimiento_actual`, {
                    params: {
                        'idgrado_procedimiento_actual': this.idgrado_procedimiento_actual
                    }
                })
                .then(function (response) {
                    me.grado_proc_actual = response.data
                })
                .catch(function (error) {
                    console.log(error)
                })
            },
            getMovimientos() { // los movimientos de un expediente determinado
                let me = this

                axios.get(`${this.ruta}/movimiento`, {
                    params: {
                        'idexpediente': this.idexpediente
                    }
                })
                .then(function (response) {
                    me.movimientos = response.data
                    me.calcularEstadosConMovimientos()
                })
                .catch(function (error) {
                    console.log(error)
                })
            },
            calcularEstadosConMovimientos() {
                for (let i = 0; i < this.movimientos.length; i++) {
                    var objMovimiento = this.movimientos[i];
                    //this.mov_grado_procedimientos.find(item => item.idgradproc_origen === objMovimiento.idgradproc_origen)
                    var index = this.mov_grado_procedimientos.findIndex(item => item.idgradproc_origen === objMovimiento.idgradproc_origen)

                    if (index == -1) { //si no se encuentra en procedimientos a mostrar agregarlo
                        this.mov_grado_procedimientos.push(objMovimiento)
                    }
                    else { // sobreescribir el ultimo movimiento con el procedimiento que fue devuelto
                        this.mov_grado_procedimientos[index] = objMovimiento
                    }
                }

                let cont;

                for (cont = 0; cont < this.mov_grado_procedimientos.length; cont++) {
                    var objGradMovimiento = this.mov_grado_procedimientos[cont]

                    objGradMovimiento.nro_orden = cont + 1;

                    if (objGradMovimiento.idgradproc_origen == this.idgrado_procedimiento_actual) {
                        this.mostrar_actual_en_movimiento = true
                        this.orden_actual_en_movimiento = objGradMovimiento.nro_orden
                    }
                }

                if (this.mostrar_actual_en_movimiento == false) { //actual no se encuentra en movimientos como origen
                    this.cont_proc_actual = cont + 1
                    this.cont_resto_procedimientos = cont + 2
                }
                else {
                    this.cont_resto_procedimientos = cont + 1
                }

                this.showProcedimientosRestantes()
            },
            //todos los procedimientos despues del idgradproc con el maximo nro de orden
            getProcedimientosRestantes(idGradProcMaxOrdenMostrado) {
                let me = this

                axios.get(`${this.ruta}/resto_grado_procedimiento`, {
                    params: {
                        'idgrado_modalidad': this.idgrado_modalidad,
                        'idgrado_procedimiento_actual': idGradProcMaxOrdenMostrado
                    }
                })
                .then(function (response) {
                    me.resto_grado_procedimientos = response.data

                    for (let k = 0; k < me.resto_grado_procedimientos.length; k++) {
                        var objRestoGradoProcedimiento = me.resto_grado_procedimientos[k]
                        objRestoGradoProcedimiento.nro_orden = me.cont_resto_procedimientos++
                    }
                })
                .catch(function (error) {
                    console.log(error)
                })
            },
            showProcedimientosRestantes() {
              if (this.resto_grado_procedimientos.length == 0) {
                  if (this.movimientos.length > 0) {

                      if (this.mostrar_actual_en_movimiento == true) {
                          let max_orden = 0
                          let idGradProcOrigen_Max_Orden = 0

                          for (let k = 0; k < this.movimientos.length; k++) {
                              if (this.movimientos[k].nro_orden > max_orden) { //si es mayor actualizar max orden
                                  max_orden = this.movimientos[k].nro_orden
                                  idGradProcOrigen_Max_Orden = this.movimientos[k].idgradproc_origen
                              }
                          }

                          this.getProcedimientosRestantes(idGradProcOrigen_Max_Orden)
                      }
                      else {
                          this.getProcedimientosRestantes(this.idgrado_procedimiento_actual)
                      }
                  }
                  else {
                      this.getProcedimientosRestantes(this.idgrado_procedimiento_actual)
                  }
              }
            },
            displayPast() {
                this.showPast = !this.showPast
            },

            displayRest() {
                this.showRest = !this.showRest
            }
        },
        mounted() {
            this.getGradoProcedimientoActual()
            this.getMovimientos()
        }
    }
</script>
