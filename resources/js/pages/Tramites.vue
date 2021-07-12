<template>
    <div>
        <b-card>
            <div class="text-right">
                <b-button variant="primary" @click="nuevoTramite">Nuevo trámite</b-button>
            </div>            
            <hr />
            <b-card-title>Mis trámites</b-card-title>
            <b-card
                no-body
                v-for="(expediente, index) in expedientes"
                :key="index"                
                class="mb-3"                
            >
                <template v-slot:header>
                    <h6 class="mb-0" v-text="`${expediente.tramite.nombre}`"></h6>
                </template>                
                <b-card-body>
                    <div class="p-3 border mb-3">                                
                        <b-row>
                            <div class="form-group col-md-4 border border-light">
                                <label class="text-info lbl-nombre" v-text="'Código de expediente:'"></label>
                                <label class="lbl-data" v-text="expediente.codigo"></label>
                            </div>
                            <div class="form-group col-md-4 border border-light">
                                <label class="text-info lbl-nombre" v-text="'Fecha de creación:'"></label>
                                <label class="lbl-data">{{ expediente.created_at | fecha_hora }}</label>
                            </div>                        
                            <div class="form-group col-md-4 border border-light">
                                <label class="text-info lbl-nombre" v-text="'Estado:'"></label>
                                <label class="lbl-data" v-text="expediente.estado"></label>
                            </div>                                                
                        </b-row>  
                        <b-row>
                            <div class="form-group col-md-12 border border-light">
                                <label class="text-info lbl-nombre" v-text="'Programa de estudios:'"></label>
                                <label class="lbl-data" v-text="expediente.escuela.nesc"></label>
                            </div>                                                                     
                        </b-row>                         
                    </div>                               
                    <b-button variant="info" @click="mostrarProcedimientos(expediente)">Seguir trámite</b-button>                                   
                </b-card-body>                                
            </b-card>
        </b-card>
    </div>
</template>

<script>
import moment from 'moment'  

export default {
    name: "tramites",
    components: {
        moment
    },
    data() {
        return {
            api_url: this.$root.api_url,
            expedientes: []
        };
    },
    filters: {
        fecha: function (date) {      
            return moment(date).format("DD-MM-YYYY")      
        },
        fecha_hora: function (date) {      
            return moment(date).format("DD-MM-YYYY, hh:mm A")      
        }
    }, 
    created() {
        this.getTramites();
    },
    methods: {
        getTramites() {
            axios.get(`${this.api_url}/expediente/tramite`)
                .then(response => {                                                          
                    this.expedientes = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        nuevoTramite() {
            let graduando = this.$store.getters.getGraduando
            
            if ((!graduando.telefono || !graduando.email_personal) || !graduando.direccion) {
                this.$store.dispatch('showAlert', { vm:this, 
                    alert:{titulo:'Nuevo trámite', contenido:'Debe actualizar su información de contacto', tipo:'warning', icono: 'error'}})
            } else {
                this.$router.push({name: "nuevo-tramite", 
                    params: { graduando }});
            }           
        },        
        mostrarProcedimientos(expediente) {
            this.$store.dispatch('setIdTramite', expediente.tramite_id)  
            this.$store.dispatch('setIdExpediente', expediente.id)              
            this.$router.push( { name: expediente.tramite.componente, params: { tramite_nombre: expediente.tramite.nombre} } );                     
        },        
    }
};
</script>
<style scoped>
    .lbl-nombre {
        font-weight: 500;
    }

    .lbl-data {
        text-align: center;
        border: 0;
        padding: 0;
        display: block;
        width: 100%;
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 500;
    }
</style>