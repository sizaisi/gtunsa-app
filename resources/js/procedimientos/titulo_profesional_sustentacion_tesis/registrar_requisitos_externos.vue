<template>
    <div>                  
        <subir-archivos
            :idexpediente="idexpediente" 
            :idprocedimiento="idprocedimiento_actual"
            :idruta="ruta.id"                
            :array_opciones="array_tipo_documento"
            :max_docs="max_docs"
            ref="documentos"
            @reset="resetChild"
        ></subir-archivos>
        <div v-if="errors.length" class="alert alert-danger" role="alert">
            <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
        </div>   

        <b-button
            @click="mover(ruta)"
            :variant="etiquetas[ruta.etiqueta]"                      
        >
            {{ ruta.etiqueta | capitalize }}
        </b-button>               
    </div>
</template>
<script>
import config from "./../../config";
import SubirArchivos from "./../../components/SubirArchivos";
import DocentesFacultad from "./../../components/DocentesFacultad";

export default {
    name: "registrar-requisitos-externos",
    components: {
        SubirArchivos        
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            ruta: {},
            titulo: "",            
            etiquetas: config.etiquetas,
            array_tipo_documento: [
                { value: null, text: 'Tipo Documento', disabled: true },                
                { value: 'Antecedentes penales', text: 'Antecedentes penales', disabled: false},                
                { value: 'Constancia de idiomas', text: 'Constancia de idiomas', disabled: false},                
            ],
            max_docs: 2,            
            errors: [],
        };
    },
    created() {
        this.getRuta();
    },
    filters: {
        capitalize: function(value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    },
    methods: {         
        validarDocumentos() {        
            let totalDocs = this.$refs.documentos.cantidadDocumentos();

            if (totalDocs < this.max_docs) {
                this.errors.push(`Debe registrar ${this.max_docs} documentos para este procedimiento.`)
            }           

            if (!this.errors.length) {
                return true
            }      

            return false
        },     
        getRuta() {// unica ruta del procedimiento            
            axios
                .get(`${this.api_url}/movimiento/ruta`, {
                    params: {
                        idprocedimiento_actual: this.idprocedimiento_actual
                    }
                })
                .then(response => {                              
                    this.ruta = response.data[0];
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        mover(ruta) {
          if (!this.validarDocumentos()) {
            return
          }

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