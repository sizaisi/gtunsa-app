<template>
  <div>
        <mostrar-errores :errors="errors"/>
        <div class="mt-3">                        
            <b-button @click="mover()" variant="success">Derivar</b-button>
        </div>
  </div>
</template>
<script>
import MostrarErrores from "./MostrarErrores";

export default {
    name: "mover-expediente",
    props: ["ruta", "idexpediente"],      
    components: {
        MostrarErrores     
    },
    data() {
        return {     
            api_url: this.$root.api_url,       
            errors: []
        };
    },
    methods: {
        mover() {                     
            /*if (this.$store.state.errors.length > 0) {
                this.errors = this.$store.state.errors
                return
            }*/    

            this.$bvModal.msgBoxConfirm(
                '¿Seguro que quiere derivar este expediente?', {
                title: 'Derivar Expediente',                    
                okVariant: 'success',
                okTitle: 'Derivar',
                cancelTitle: 'Cancelar',          
                centered: true
            }).then(value => {
                if (value) {
                    axios.post(`${this.api_url}/movimiento/mover`, {
                        idexpediente: this.idexpediente,
                        idruta: this.ruta.id,
                        idproc_origen: this.ruta.procedimiento_origen_id,
                        idproc_destino: this.ruta.procedimiento_destino_id,
                    })
                    .then((response) => {                        
                        if (!response.data.error) { 
                            this.$store.dispatch('showAlert', { vm:this, 
                                alert:{titulo:'Registro de requisitos externos', contenido:response.data.successMessage, tipo:'success', icono:'done'}})
                        } 
                        else {
                            this.$store.dispatch('showAlert', {vm:this, 
                                alert:{titulo:'Registro de requisitos externos', contenido:response.data.errorMessage, tipo:'danger', icono:'error'}})                        
                        }   

                        this.$emit("reload-parent");
                    })
                }                   
            })    
        },
    }
}
</script>