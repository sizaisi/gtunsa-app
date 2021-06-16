<template>  
    <div class="text-center">                    
        <!--<mostrar-errores :errors="errors"/>-->
        <b-button class="btn btn-success" style="margin-top:1rem" @click="derivar()">
            Derivar Expediente
        </b-button>        
    </div>    
</template>
<script>
//import MostrarErrores from "./MostrarErrores";

export default {
    name: "mover-expediente",
    props: ["expediente_id"],      
    components: {
        //MostrarErrores     
    },
    data() {
        return {     
            api_url: this.$root.api_url,       
            errors: []
        };
    },
    methods: {
        derivar() {
            /*if (this.$store.state.errors.length > 0) {
                this.errors = this.$store.state.errors
                return
            }*/  
            const h = this.$createElement

            const messageVNode = h("div", { class: ["foobar"] }, [
                h("p", { class: ["text-info"] }, [
                    h(
                    "strong",
                    "INFORMACIÓN: Este expediente será derivado a "
                    ),
                ]),
            ]);

            this.$bvModal.msgBoxConfirm(
              [
                messageVNode,
                "\n",
                "¿Esta seguro de querer derivar este expediente?",
              ],
              {
                title: "Derivar Expediente",
                okVariant: "success",
                okTitle: "SI",
                cancelVariant: "danger",
                cancelTitle: "NO",
                centered: true,
              }
            )
            .then((value) => {
              if (value) {
                const ruta = this.$store.getters.getRuta

                axios.post(`${this.api_url}/movimiento/mover`, 
                    {
                        expediente_id: this.expediente_id,
                        ruta: ruta
                    }
                )
                .then(response => {                    
                    this.$store.dispatch('showAlert', { vm:this, 
                                alert:{titulo:'Registro de requisitos externos', contenido:'Expediente fue derivado con éxito', tipo:'success', icono:'done'}})
                    this.$emit("reload-parent");
                })
                .catch(error => {
                    console.log(error)
                });
              }
            });
        }        
    }
}
</script>