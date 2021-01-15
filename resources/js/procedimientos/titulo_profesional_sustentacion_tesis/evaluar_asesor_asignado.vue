<template>
    <div>                                  
        <div class="mb-4">
            <b>Asesor asignado: </b> {{ asesor.apn }}
        </div>
        <b-button
            v-for="(ruta, index) in array_ruta"
            :key="index"
            @click="mover(ruta)"
            :variant="etiquetas[ruta.etiqueta]"  
            class="mr-3"                    
        >
            {{ ruta.etiqueta | capitalize }}
        </b-button>               
    </div>
</template>
<script>
import config from "./../../config";

export default {
    name: "evaluar-asesor-asignado",    
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            array_ruta: [],
            asesor: {},            
            etiquetas: config.etiquetas,            
        };
    },
    created() {
        this.getRutas();
        this.getAsesor();
    },
    filters: {
        capitalize: function(value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    },
    methods: {                 
        getRutas() {
            axios
                .get(`${this.api_url}/movimiento/ruta`, {
                    params: {
                        idprocedimiento_actual: this.idprocedimiento_actual
                    }
                })
                .then(response => {                                            
                    this.array_ruta = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getAsesor() {
            axios
                .get(`${this.api_url}/docente/getAsesor/${this.idexpediente}`)
                .then(response => {                                                                
                    this.asesor = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        mover(ruta) {          

          axios
            .post(`${this.api_url}/graduando/mover`, {
              idexpediente: this.idexpediente,
              idruta: ruta.id,
              idproc_origen: ruta.idproc_origen,
              idproc_destino: ruta.idproc_destino,
            })
            .then((response) => {
              this.$vs.notify({
                title: "Éxito",
                text: "Su expediente se ha enviado correctamente",
                color: "success",
                icon: "done",
                position: "top-center",
                time: 4000,
              });
              this.$emit("reload-parent");
            })
            .catch((error) => {
              console.log(error);
              if (error.response.status == 422) {
                //me.errors = error.response.data.errors;
              } else {
                this.$vs.notify({
                  title: "Error",
                  text: "No se pudo registrar su proyecto de tesis",
                  color: "danger",
                  icon: "error",
                  position: "top-left",
                  time: 4000,
                });
              }
            });
        },
        resetChild() {
            this.errors = [];
        },      
    }
};
</script>
<style scoped>
    ul {
        margin-bottom: 0px;    
    }         
</style>