<template>
    <div>
        <b-card>
            <div class="text-right">
                <b-button variant="primary" @click="nuevoTramite">Nuevo trámite</b-button>
            </div>            
            <hr />
            <b-card-title>Trámites</b-card-title>
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
                    <b-card-sub-title class="mb-2"><b>Código de Expediente:</b> {{ expediente.codigo }}</b-card-sub-title>
                    <b-card-sub-title class="mb-2"><b>Fecha de creación:</b> {{ expediente.fecha }}</b-card-sub-title>
                    <b-card-text>
                        Trámite presentado para la obtención de <b>{{ expediente.tramite.nombre }}</b> 
                        del programa de estudios de <b>{{ expediente.escuela.nesc }}</b>
                    </b-card-text>
                    <b-button variant="info" @click="verEstados(expediente)">Seguir trámite</b-button>
                </b-card-body>                                
            </b-card>
        </b-card>
    </div>
</template>

<script>
export default {
    name: "inicio",
    data() {
        return {
            api_url: this.$root.api_url,
            expedientes: []
        };
    },
    created() {
        this.getTramites();
    },
    methods: {
        nuevoTramite() {
            axios.get(`${this.api_url}/graduando/contacto`)
                .then(response => {
                    let contacto = response.data;
                    if (!contacto.telefono_movil || !contacto.direccion) {                                                
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Nuevo trámite', contenido:'Debe actualizar su información de contacto', tipo:'warning', icono: 'error'}})                   
                    } else {
                        this.$router.push({
                            name: "nuevo-tramite"
                        });
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getTramites() {
            axios.get(`${this.api_url}/expediente/tramite`)
                .then(response => {                    
                    this.expedientes = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        verEstados(expediente) {
            this.$store.dispatch('setIdTramite', expediente.idtramite)  
            this.$store.dispatch('setIdExpediente', expediente.id)              
            this.$router.push( {name: "estados"} );            
        },        
    }
};
</script>
