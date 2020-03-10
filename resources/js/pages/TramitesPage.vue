<template>
    <div>        
        <nuevo-tramite-component 
            :ruta="ruta" 
            v-if="flag_nuevo" 
            @cancelar="flag_nuevo = 0"
            @registrado="flag_nuevo = 0"
        >
        </nuevo-tramite-component>
        <b-card v-if="!flag_nuevo">            
            <b-button @click="nuevoTramite" variant="success">Nuevo trámite</b-button>
            <hr>
            <mostrar-tramites-component :ruta="ruta"></mostrar-tramites-component>
        </b-card>                   
    </div>    
</template>

<script>    
    import NuevoTramiteComponent from '../components/NuevoTramiteComponent'
    import MostrarTramitesComponent from '../components/MostrarTramitesComponent'

    export default {       
        components:{
            NuevoTramiteComponent,            
            MostrarTramitesComponent
        },
        props : ['ruta'],         
        data() {
            return {                       
                flag_nuevo : 0,                
                tramites : [],    
            }
        },       
        methods : {
            nuevoTramite() { 
                this.flag_nuevo = 1
            }        
        }       
    }
</script>
