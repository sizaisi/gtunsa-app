<template>
    <div>
        <b-card>            
            <b-button variant="primary" @click="nuevoTramite">Nuevo trámite</b-button>
            <hr />
            <b-card
                v-for="(expediente, index) in expedientes"
                :key="index"
                :sub-title="`Código de Expediente: ${expediente.codigo}`"
                class="mb-3"
            >
                <template v-slot:header>
                    <h6 class="mb-0" v-text="`${expediente.tramite.nombre}`"></h6>
                </template>
                <b-card-text>
                    Trámite presentado para la obtención de <b>{{ expediente.tramite.nombre }}</b> 
                    del programa de estudios de <b>{{ expediente.escuela.nesc }}</b>
                </b-card-text>
                <b-button variant="info" @click="verEstados(expediente)">Seguir trámite</b-button>
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
