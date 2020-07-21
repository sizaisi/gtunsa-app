<template>
    <div>
        <b-card 
            v-for="tramite in tramites"
            :key="tramite.id"                        
        >   
            <b-card-text>                
                <b>Código de Expediente:</b> {{tramite.codExpediente}} <br/>
                <b>Grado ó Título:</b> {{tramite.nombre_grado_titulo}} <br/>
                <b>Modalidad de Obtención:</b> {{tramite.nombre_modalidad}} <br/>                    
                <b>Escuela o Programa:</b> {{tramite.nesc}} <br/>
            </b-card-text>
            <b-button variant="info" @click="verEstados(tramite)">Iniciar / Seguir proceso</b-button>           
        </b-card>          
    </div>      
</template>

<script>    
    export default {               
        name: 'mostrar-tramites-component', 
        props : ['ruta'],         
        data() {
            return {                                   
                tramites : [],    
            }
        },
       
        methods : {            
            getTramites() {
                let me = this

                axios.get(`${this.ruta}/expediente/tramite`)
                .then(function (response) {                                    
                    me.tramites = response.data                                                                                 
                })
                .catch(function (error) {                    
                    console.log(error)
                })
            },
            verEstados(tramite) {
                this.$router.push({name: 'estados', params: {
                    ruta: this.ruta,                    
                    idgrado_modalidad: tramite.idgrado_modalidad,
                    idexpediente: tramite.idexpediente,
                    idgrado_procedimiento_actual : tramite.idgrado_procedimiento_actual
                }})
            }         
        },        
        mounted() {                            
            this.getTramites()
        }
    }
</script>
