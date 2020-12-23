<template>
    <div>
        <b-card class="principal-card" title="Estado de su trámite:">
            <div class="d-flex justify-content-center mb-3">
                <b-button variant="outline-warning" :to="{ name: 'inicio' }">
                    <b-icon icon="arrow-left-circle-fill"></b-icon> Volver
                </b-button>
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
                            :idgrado_procedimiento_actual="grado_proc_actual.idgrado_procedimiento"
                            number=""
                            color="actual"
                            :url_form="grado_proc_actual.url_formulario"
                            :idrol_area="grado_proc_actual.idrol"
                            :rol_area="grado_proc_actual.rol_area"
                            :tipo_rol="grado_proc_actual.tipo_rol"
                            :desc="grado_proc_actual.descripcion"
                            :fecha="''"
                            :showDescription="true"
                            :tail="true"                            
                            @reload-parent="actualizarEstados"
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
                                :title="grado_proc_rest.nombre_procedimiento"
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
    data() {
        return {
            api_url: this.$root.api_url,
            idgrado_modalidad: this.$store.state.idgrado_modalidad,
            idexpediente: this.$store.state.idexpediente,            
            grado_proc_actual: null,            
            movimientos: [],            
            resto_grado_procedimientos: [],            
            showRest: false              
        };
    },
    created() {
        if (this.idgrado_modalidad != null && this.idexpediente != null) {
            this.getGradoProcedimientoActual()
            this.getMovimientos()
        }
        else {
            this.$router.push({ name: 'inicio' }); 
        } 
           
    },
    methods: {
        getGradoProcedimientoActual() {            
            axios.get(`${this.api_url}/grado_procedimiento/actual`, {
                    params: {                        
                        idexpediente: this.idexpediente
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
                    this.getProcedimientosRestantes();                                    
                })
                .catch(error => {
                    console.log(error);
                });
        },                
        getProcedimientosRestantes() {
            axios.get(`${this.api_url}/grado_procedimiento/resto`, {
                    params: {
                        idgrado_modalidad: this.idgrado_modalidad,
                        idgrado_procedimiento_actual: this.grado_proc_actual.idgrado_procedimiento
                    }
                })
                .then(response => {
                    this.resto_grado_procedimientos = response.data;                    
                })
                .catch(error => {
                    console.log(error);
                });
        },  
        actualizarEstados() {
            this.getGradoProcedimientoActual();
            this.getMovimientos();            
        },      
        displayRest() {
            this.showRest = !this.showRest;
        }
    },    
};
</script>
