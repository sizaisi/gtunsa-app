<template>
    <div>          
        <b-card no-body>
            <b-tabs
                v-model="tabIndex"
                card
                active-nav-item-class="font-weight-bold text-uppercase text-danger"
            >
                <b-tab
                    title="1. Registrar título"
                    title-item-class="disabledTab"
                    :disabled="tabIndex2 < 0"
                >
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
                    <div v-if="errors.length" class="alert alert-danger" role="alert">
                        <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
                    </div> 
                </b-tab>
                <b-tab :title="'2. Proponer Asesor'" title-item-class="disabledTab" :disabled="tabIndex2 < 1">
                    <docentes-facultad 
                        :idexpediente="idexpediente"
                        ref="docente"
                        @reset="resetChild"
                    >
                    </docentes-facultad>
                    <div v-if="errors.length" class="alert alert-danger" role="alert">
                        <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
                    </div> 
                </b-tab>
                <b-tab :title="'3. Adjuntar Documentos'" title-item-class="disabledTab" :disabled="tabIndex2 < 2">
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
                    <b-button @click="registrarProyectoTesis" :variant="etiquetas[ruta.etiqueta]">
                        {{ ruta.etiqueta | capitalize }}
                    </b-button>
                </b-tab>
            </b-tabs>
        </b-card>
        <div class="text-center">
            <b-button-group class="mt-3">
                <b-button
                    variant="secondary"
                    class="mr-1"
                    @click="prevTab"
                    :disabled="tabIndex == 0"
                >
                    Anterior
                </b-button>
                <b-button
                    variant="secondary"
                    @click="nextTab"
                    :disabled="tabIndex == 2"
                >
                    Siguiente
                </b-button>
            </b-button-group>
        </div>
        
    </div>
</template>
<script>
import config from "./../../config";
import SubirArchivos from "./../../components/SubirArchivos";
import DocentesFacultad from "./../../components/DocentesFacultad";

export default {
    name: "registrar_proyecto_grado",
    components: {
        SubirArchivos,
        DocentesFacultad
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
                { value: 'Plan de tesis', text: 'Plan de tesis', disabled: false},
                { value: 'DNI', text: 'DNI', disabled: false},
                { value: 'Constancia de prácticas', text: 'Constancia de prácticas', disabled: false},
                { value: 'Reporte SUNARP', text: 'Reporte SUNARP', disabled: false},           
            ],
            max_docs: 4,
            tabIndex: 0,
            tabIndex2: 0,
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
        prevTab() {
            this.errors = [];
            this.tabIndex2--;
            this.tabIndex--;
        },
        nextTab() {
            this.errors = [];
            let pasar = false;

            if (this.tabIndex == 0) {
                pasar = this.validarTab1();
            }

            if (this.tabIndex == 1) {
                pasar = this.validarTab2();
            }

            if (pasar) {
                this.tabIndex2++;
                this.$nextTick(function() {
                    this.tabIndex++;
                });
            }
        },
        validarTab1() {        
            this.errors = [] 

            if (this.titulo.length < 30 || this.titulo.length > 256) {
                this.errors.push("El campo título debe contener entre 30 y 256 caracteres.")
            }   
                        
            if (!this.errors.length) {
                return true
            }      

            return false
        },
        validarTab2() {        
            this.errors = [] 
            let asesor = this.$refs.docente.asesor

            if (asesor == null) { 
                this.errors.push(`Debe elegir un asesor para su proyecto de tesis`)
            }           

            if (!this.errors.length) {
                return true
            }      

            return false
        },     
        validarTab3() {        
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
        registrarProyectoTesis() {                          
            if (!this.validarTab3()) {
                return
            }
            
            axios.post(`${this.api_url}/graduando/registrar_proyecto`, {
                    titulo: this.titulo,                                        
                    idexpediente: this.idexpediente,
                    idruta: this.ruta.id,
                    idproc_origen: this.ruta.idproc_origen,
                    idproc_destino: this.ruta.idproc_destino
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
        },        
    }
};
</script>
<style scoped>
    ul {
        margin-bottom: 0px;    
    }         
</style>