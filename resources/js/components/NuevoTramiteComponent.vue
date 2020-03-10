<template>
    <div>
        <b-card header="Nuevo Trámite" header-tag="header">
            <b-form method="POST">
                <b-form-group id="input-group-3" label="Escuela o Programa:" label-for="input-3">
                    <b-form-select
                        id="input-3"
                        v-model="escuela"
                        :options="escuelas"
                        required
                        >
                        <template v-slot:first>
                            <option :value="null" disabled>-- Por favor seleccione una opción --</option>
                        </template>
                    </b-form-select>
                </b-form-group>
                <b-form-group v-show="grado_titulos.length > 0" id="input-group-3" label="Grado ó Título:" label-for="input-3">
                    <b-form-select
                        id="input-3"
                        v-model="idgrado_titulo"
                        :options="grado_titulos"
                        required
                        >
                        <template v-slot:first>
                            <option :value="null" disabled>-- Por favor seleccione una opción --</option>
                        </template>
                    </b-form-select>
                </b-form-group>
                <b-form-group v-show="grado_modalidades.length > 0" id="input-group-3" label="Modalidad de obtención:" label-for="input-3">
                    <b-form-select
                        id="input-3"
                        v-model="idgrado_modalidad"
                        :options="grado_modalidades"
                        required
                        >
                        <template v-slot:first>
                            <option :value="null" disabled>-- Por favor seleccione una opción --</option>
                        </template>
                    </b-form-select>
                </b-form-group>
                <b-button variant="primary" @click="registrarTramite">Registrar trámite</b-button>
                <b-button variant="danger" @click="$emit('cancelar')">Cancelar</b-button>
            </b-form>
        </b-card>
    </div>
</template>
<script>
    export default {
        name: 'nuevo-tramite-component',
        props : ['ruta'],
        data() {
            return {
                show : 3,
                escuela : null,
                escuelas : [],
                idgrado_titulo : null,
                grado_titulos : [],
                idgrado_modalidad : null,
                grado_modalidades : [],
            }
        },
        watch: {
            escuela: function(val){
                let me = this
                me.idgrado_titulo  = null

                axios.get(`${this.ruta}/grado_titulo`, {
                    params: {
                        'nive': val.nive
                    }
                })
                .then(function (response) {
                    me.grado_titulos = response.data
                })
                .catch(function (error) {
                    console.log(error)
                })
            },
            idgrado_titulo: function(val){
                let me = this
                me.idgrado_modalidad  = null

                axios.get(`${this.ruta}/grado_modalidad`, {
                    params: {
                        'idgrado_titulo': val
                    }
                })
                .then(function (response) {
                    me.grado_modalidades = response.data
                    console.log(me.grado_modalidades)
                })
                .catch(function (error) {
                    console.log(error)
                })
            }
        },
        methods : {
            getEscuelas() { // escuelas de pregrado y posgrado
                let me = this

                axios.get(`${this.ruta}/escuela`)
                .then(function (response) {
                    me.escuelas = response.data
                })
                .catch(function (error) {
                    console.log(error)
                })
            },
            registrarTramite() {
                //validar que cui nues y espe no tenga registro en proceso en gt_expediente
                //si hay por lo menos un registro no mostrar mensaje de error con respectivo mensaje
                //warning ud tiene un expediente en proceso para la escuela o programa seleccionado
                let me = this


                axios.get(`${this.ruta}/expediente/registrar/`, {
                    params: {
                      'idgrado_modalidad': this.idgrado_modalidad,
                      'nues': this.escuela.nues,
                      'espe': this.escuela.espe,
                    }
                }).then(function (response) {
                    me.$vs.notify({
                        title: 'Éxito',
                        text: '¡Su trámite fue registrado con éxito!',
                        color: 'success',
                        icon: 'done',
                        position: 'top-center',
                        time: 4000
                    })

                    me.$emit('registrado')

                }).catch(function (error) {
                    console.log(error)
                    me.$vs.notify({
                        title: 'Error',
                        text: 'No se pudo registrar su trámite',
                        color: 'danger',
                        icon: 'error',
                        position: 'top-center',
                        time: 4000
                    })
                })
            }
        },
        mounted() {
            this.getEscuelas()
        }
    }
</script>
