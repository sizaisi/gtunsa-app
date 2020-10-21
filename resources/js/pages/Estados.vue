<template>
    <div>
        <b-card class="principal-card" title="Estado de su trámite:">
            <div class="d-flex justify-content-center mb-3">
                <b-button variant="outline-warning" :to="{ name: 'inicio' }"
                    ><b-icon
                        icon="arrow-left-circle-fill"
                        aria-hidden="true"
                    ></b-icon>
                    Volver</b-button
                >
            </div>
            <b-container>
                <b-row>
                    <b-col>
                        <estado-component
                            title="Inicio"
                            number="."
                            color="light_gray"
                            :hideDescription="true"
                            :tail="true"
                        />
                    </b-col>
                </b-row>                
                <b-row v-for="(grado_proc_mov, index) in movimientos" :key="index">                        
                    <b-col>
                        <estado-component
                            :title="grado_proc_mov.nombre_procedimiento"
                            number="+"
                            :color="grado_proc_mov.estado"
                            :url_form="grado_proc_mov.url_formulario"
                            :idrol_area="grado_proc_mov.idrol_area"
                            :rol_area="grado_proc_mov.rol_area"
                            :tipo_rol="grado_proc_mov.tipo_rol"
                            :desc="grado_proc_mov.descripcion"
                            :fecha="grado_proc_mov.fecha"
                            :tail="true"
                        />
                    </b-col>                        
                </b-row>                
                <b-row v-if="grado_proc_actual != null">                    
                    <b-col>
                        <estado-component
                            :idexpediente="idexpediente"
                            :title="grado_proc_actual.nombre_procedimiento"
                            :idgrado_procedimiento_actual="
                                idgrado_procedimiento_actual
                            "
                            number=""
                            color="actual"
                            :url_form="grado_proc_actual.url_formulario"
                            :idrol_area="grado_proc_actual.idrol_area"
                            :rol_area="grado_proc_actual.rol_area"
                            :tipo_rol="grado_proc_actual.tipo_rol"
                            :desc="grado_proc_actual.descripcion"
                            :fecha="''"
                            :showDescription="true"
                            :tail="true"
                        />
                    </b-col>
                </b-row>
                <b-row v-if="resto_grado_procedimientos.length > 0">
                    <b-col>
                        <estado-component
                            :title="'Procedimientos restantes...'"
                            number=".."
                            color="gray"
                            click="displayAll"
                            @displayAll="displayRest"
                            :tail="true"
                        />
                    </b-col>
                </b-row>
                <b-collapse v-model="showRest">                    
                    <b-row v-for="(grado_proc_rest, index) in resto_grado_procedimientos" :key="index">
                        <b-col>
                            <estado-component
                                :title="
                                    grado_proc_rest.nombre_procedimiento
                                "
                                number=""
                                color="gray"
                                :hideDescription="true"
                                :tail="true"
                            />
                        </b-col>
                    </b-row>                    
                </b-collapse>
                <b-row>
                    <b-col>
                        <estado-component
                            title="Fin"
                            number="."
                            color="light_gray"
                            :hideDescription="true"
                        />
                    </b-col>
                </b-row>                
            </b-container>
        </b-card>
    </div>
</template>
<script>
import EstadoComponent from "../components/EstadoComponent";

export default {
    components: {
        EstadoComponent
    },
    props: [
        "idgrado_modalidad",
        "idexpediente",
        "idgrado_procedimiento_actual"
    ],
    data() {
        return {
            api_url: this.$root.api_url,
            grado_proc_actual: null,            
            movimientos: [],            
            resto_grado_procedimientos: [],            
            showRest: false,                    
        };
    },
    created() {
        this.getGradoProcedimientoActual();
        this.getMovimientos();
        this.getProcedimientosRestantes();
    },
    methods: {
        getGradoProcedimientoActual() {            
            axios.get(`${this.api_url}/grado_procedimiento/actual`, {
                    params: {
                        idgrado_procedimiento_actual: this.idgrado_procedimiento_actual
                    }
                })
                .then(response => {
                    this.grado_proc_actual = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getMovimientos() {            
            axios.get(`${this.api_url}/movimiento`, {
                    params: {
                        idexpediente: this.idexpediente
                    }
                })
                .then(response => {
                    this.movimientos = response.data;                                        
                })
                .catch(error => {
                    console.log(error);
                });
        },                
        getProcedimientosRestantes() {
            axios.get(`${this.api_url}/grado_procedimiento/resto`, {
                    params: {
                        idgrado_modalidad: this.idgrado_modalidad,
                        idgrado_procedimiento_actual: this.idgrado_procedimiento_actual
                    }
                })
                .then(response => {
                    this.resto_grado_procedimientos = response.data;                    
                })
                .catch(error => {
                    console.log(error);
                });
        },        
        displayRest() {
            this.showRest = !this.showRest;
        }
    },    
};
</script>
