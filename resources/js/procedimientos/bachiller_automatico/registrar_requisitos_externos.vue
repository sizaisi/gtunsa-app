<template>    
    <b-form @submit.prevent="registrarRequisitos()">                  
        <subir-archivos
            :idexpediente="idexpediente" 
            :idgrado_proc="idprocedimiento_actual"
            :idruta="ruta.id"                
            :array_opciones="array_tipo_documento"
            :max_docs="max_docs"
            ref="documentos"
            @reset="resetChild"
        ></subir-archivos>
        <div v-if="errors.length" class="alert alert-danger" role="alert">
            <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
        </div>    
        <b-button type="submit" :variant="etiquetas[ruta.etiqueta]">
            {{ ruta.etiqueta | capitalize }}
        </b-button>
    </b-form>              
</template>
<script>
import config from "./../../config";
import SubirArchivos from "./../../components/SubirArchivos";

export default {
    name: "b_a_registrar_requisitos_externos",
    components: {
        SubirArchivos
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            ruta: {},            
            etiquetas: config.etiquetas,
            array_tipo_documento: [
                { value: null, text: 'Tipo Documento', disabled: true },                
                { value: 'DNI Anverso', text: 'DNI Anverso', disabled: false},
                { value: 'DNI Reverso', text: 'DNI Reverso', disabled: false},
                { value: 'Certificado Antecendentes Penales', text: 'Certificado Antecendentes Penales', disabled: false},
                { value: 'Constancia Nivel Intermedio de Idiomas', text: 'Constancia Nivel Intermedio de Idiomas', disabled: false},           
            ],
            max_docs: 4,
            errors: []
        };
    },
    created() {
        this.getRuta();
    },
    methods: {
        validarDocumentos() {        
            this.errors = []             

            let totalDocs = this.$refs.documentos.cantidadDocumentos();

            if (totalDocs < this.max_docs) { //referencia al metodo del componente hijo
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
        registrarRequisitos() {                 
            if (!this.validarDocumentos()) {
                return
            }
            
            axios.post(`${this.api_url}/graduando/registrar_requisitos`, {                                                
                    idexpediente: this.idexpediente,
                    idruta: this.ruta.id,
                    idproc_origen: this.ruta.idproc_origen,
                    idproc_destino: this.ruta.idproc_destino
                })
                .then(response => {
                    if (!response.data.error) {                        
                        this.$vs.notify({
                            title: "Registro de requisitos externos",
                            text: response.data.successMessage,
                            color: "success",
                            icon: "done",
                            position: "top-center",
                            time: 4000
                        })               
                    } 
                    else {
                        this.$vs.notify({
                            title: "Registro de requisitos externos",
                            text: response.data.errorMessage,
                            color: "warning",
                            icon: "error",
                            position: "top-center",
                            time: 4000
                        });
                    }                  
                    
                    this.$emit("reload-parent");
                })
                .catch(error => {                    
                    if (error.response.status == 422) {
                        //me.errors = error.response.data.errors;
                    } 
                });            
        },
        resetChild() {
            this.errors = [];
        }     
    },
    filters: {
        capitalize: function(value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    }
};
</script>
<style scoped>
    ul {
        margin-bottom: 0px;    
    }         
</style>
