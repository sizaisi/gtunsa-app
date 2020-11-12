<template>    
    <b-form @submit.prevent="registrarProyectoTesis()">
        <b-form-group
            id="textarea-group-1"            
            label-for="textarea-1"
            description="Este título debe coincidir con el título de plan de tesis del archivo adjunto"
        >            
            <template v-slot:label>
                Título de plan de tesis <span style="color:red;">*</span>
            </template>  
            <b-form-textarea
                id="textarea-1"
                :state="titulo.length >= 30 && titulo.length <= 256"
                v-model="titulo"
                placeholder="Ingrese al menos 30 caracteres y un máximo de 256 caracteres"
                rows="2"                
                required
            >
            </b-form-textarea>
        </b-form-group>            
        <subir-archivos
            :idexpediente="idexpediente" 
            :idgrado_proc="idgrado_procedimiento_actual"
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
    name: "tp_st_registrar_proyecto_grado",
    components: {
        SubirArchivos
    },
    props: ["idexpediente", "idgrado_procedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            ruta: {},
            titulo: "",            
            etiquetas: config.etiquetas,
            array_tipo_documento: [
                { value: null, text: 'Tipo Documento', disabled: true },                
                { value: 'Plan de tesis', text: 'Plan de tesis', disabled: false},
                { value: 'DNI', text: 'DNI', disabled: false},
                { value: 'Constancia de prácticas', text: 'Constancia de prácticas', disabled: false},
                { value: 'Reporte SUNARP', text: 'Reporte SUNARP', disabled: false},           
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

            if (this.titulo.length < 30 || this.titulo.length > 256) {
                this.errors.push("El campo título debe contener entre 30 y 256 caracteres.")
            }   

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
                        idgrado_procedimiento_actual: this.idgrado_procedimiento_actual
                    }
                })
                .then(response => {                    
                    this.ruta = response.data[0];
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        registrarProyectoTesis() {                 
            if (!this.validarDocumentos()) {
                return
            }
            
            axios.post(`${this.api_url}/graduando/registrar_proyecto`, {
                    titulo: this.titulo,                                        
                    idexpediente: this.idexpediente,
                    idruta: this.ruta.id,
                    idgradproc_origen: this.ruta.idgradproc_origen,
                    idgradproc_destino: this.ruta.idgradproc_destino
                })
                .then(response => {
                    if (!response.data.error) {                        
                        this.$vs.notify({
                            title: "Registro de plan de tesis",
                            text: response.data.successMessage,
                            color: "success",
                            icon: "done",
                            position: "top-center",
                            time: 4000
                        })               
                    } 
                    else {
                        this.$vs.notify({
                            title: "Registro de plan de tesis",
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
