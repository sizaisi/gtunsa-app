<template>
    <div>
        <b-card class="principal-card">
             <b-card-title><b>Trámite:</b> {{ tramite_nombre }}</b-card-title>
            <div class="d-flex justify-content-center mb-3">
                <b-button variant="outline-warning" :to="{ name: 'tramites' }">
                    <b-icon icon="arrow-left-circle-fill"></b-icon> Volver
                </b-button>
            </div>
            <b-container>
                <b-row>
                    <b-col>
                        <procedimiento
                            title="Inicio"
                            number="."
                            color="CCC"
                            :hideDescription="true"
                            :tail="true"
                        />
                    </b-col>
                </b-row>                
                <b-row v-for="(movimiento, index) in movimientos" :key="index">                        
                    <b-col>
                        <procedimiento
                            :title="movimiento.procedimiento"
                            number="+"
                            :accion="movimiento.accion"
                            :color="movimiento.color"
                            :componente="movimiento.componente"                            
                            :rol="movimiento.rol"
                            :tipo_rol="movimiento.tipo_rol"
                            :desc="movimiento.descripcion"
                            :fecha="movimiento.created_at"
                            :tail="true"
                        />
                    </b-col>                        
                </b-row>                
                <b-row v-if="procedimiento_actual != null">                    
                    <b-col>
                        <procedimiento
                            :idexpediente="idexpediente"
                            :title="procedimiento_actual.procedimiento"
                            :idprocedimiento_actual="procedimiento_actual.id"
                            number=""
                            accion="actual"
                            color="0D8ABC"
                            :componente="procedimiento_actual.componente"                            
                            :rol_id="procedimiento_actual.rol_id"
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
                        <procedimiento
                            :title="'Procedimientos restantes...'"
                            number=".."
                            color="7D7D7D"
                            click="displayAll"
                            @displayAll="displayRest"
                            :tail="true"
                        />
                    </b-col>
                </b-row>
                <b-collapse v-model="showRest">                    
                    <b-row v-for="(proc_rest, index) in resto_procedimientos" :key="index">
                        <b-col>
                            <procedimiento
                                :title="proc_rest.procedimiento"
                                number=""
                                color="7D7D7D"
                                :hideDescription="true"
                                :tail="true"
                            />
                        </b-col>
                    </b-row>                    
                </b-collapse>
                <b-row>
                    <b-col>
                        <procedimiento
                            title="Fin"
                            number="."
                            color="CCC"
                            :hideDescription="true"
                        />
                    </b-col>
                </b-row>                
            </b-container>
        </b-card>
    </div>
</template>
<script>
import Procedimiento from "./Procedimiento";

export default {
    name: 'bachiller-automatico-procedimientos',
    props: ['tramite_nombre'],
    components: {        
        Procedimiento        
    },    
    data() {
        return {
            api_url: this.$root.api_url,
            idtramite: this.$store.state.idtramite,
            idexpediente: this.$store.state.idexpediente,            
            procedimiento_actual: null,            
            movimientos: [],            
            resto_procedimientos: [],            
            showRest: false              
        };
    },
    created() {
        if (this.idtramite != null && this.idexpediente != null) {
            this.getMovimientos()
            this.getProcedimientoActual()            
        }
        else {
            this.$router.push({ name: 'tramites' }); 
        }           
    },
    methods: {
        getMovimientos() {            
            axios.get(`${this.api_url}/movimiento`, {
                    params: { idexpediente: this.idexpediente }
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
                    params: { idexpediente: this.idexpediente }
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
                        idtramite: this.idtramite,
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
