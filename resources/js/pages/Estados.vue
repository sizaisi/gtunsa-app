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
                <b-row v-for="(movimiento, index) in movimientos" :key="index">                        
                    <b-col>
                        <estado-component
                            :title="movimiento.procedimiento"
                            number="+"
                            :color="movimiento.estado"
                            :componente="movimiento.componente"                            
                            :rol="movimiento.rol"
                            :tipo_rol="movimiento.tipo_rol"
                            :desc="movimiento.descripcion"
                            :fecha="movimiento.fecha"
                            :tail="true"
                        />
                    </b-col>                        
                </b-row>                
                <b-row v-if="procedimiento_actual != null">                    
                    <b-col>
                        <estado-component
                            :idexpediente="idexpediente"
                            :title="procedimiento_actual.procedimiento"
                            :idprocedimiento_actual="procedimiento_actual.id"
                            number=""
                            color="actual"
                            :componente="procedimiento_actual.componente"                            
                            :rol="procedimiento_actual.rol"
                            :tipo_rol="procedimiento_actual.tipo_rol"
                            :desc="procedimiento_actual.descripcion"
                            :fecha="''"
                            :showDescription="true"
                            :tail="true"                            
                            @reload-parent="actualizarEstados"
                        />
                    </b-col>
                </b-row>
                <b-row v-if="resto_procedimientos.length > 0">
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
                    <b-row v-for="(proc_rest, index) in resto_procedimientos" :key="index">
                        <b-col>
                            <estado-component
                                :title="proc_rest.procedimiento"
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
            procedimiento_actual: null,            
            movimientos: [],            
            resto_procedimientos: [],            
            showRest: false              
        };
    },
    created() {
        if (this.idgrado_modalidad != null && this.idexpediente != null) {
            this.getMovimientos()
            this.getProcedimientoActual()            
        }
        else {
            this.$router.push({ name: 'inicio' }); 
        } 
           
    },
    methods: {
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
        getProcedimientoActual() {            
            axios.get(`${this.api_url}/procedimiento/actual`, {
                    params: {                        
                        idexpediente: this.idexpediente
                    }
                })
                .then(response => {
                    this.procedimiento_actual = response.data;                    
                    this.getProcedimientosRestantes();    
                })
                .catch(error => {
                    console.log(error);
                });
        },                      
        getProcedimientosRestantes() {
            axios.get(`${this.api_url}/procedimiento/resto`, {
                    params: {
                        idgrado_modalidad: this.idgrado_modalidad,
                        idprocedimiento_actual: this.procedimiento_actual.id
                    }
                })
                .then(response => {                    
                    this.resto_procedimientos = response.data;                                        
                })
                .catch(error => {
                    console.log(error);
                });
        },  
        actualizarEstados() {
            this.getMovimientos();
            this.getProcedimientoActual();                        
        },      
        displayRest() {
            this.showRest = !this.showRest;
        }
    },    
};
</script>
