<template>
    <div>                  
        <subir-archivos
            :idexpediente="idexpediente" 
            :idprocedimiento="idprocedimiento_actual"                           
            :array_opciones="array_tipo_documento"
            :max_docs="max_docs"
            ref="documentos"
            @reset="resetChild"
        ></subir-archivos>
        <mostrar-errores :errors="errors"/>  

        <div class="mt-3">                        
            <b-button
                v-for="ruta in rutas"
                :key="ruta.id"         
                @click="mover(ruta)"                   
                :variant="etiquetas[ruta.etiqueta]"  
                class="mr-3"                    
            >
                {{ ruta.etiqueta | capitalize }}
            </b-button>
        </div>               
    </div>
</template>
<script>
import config from "./../../config";
import MostrarErrores from "./../../components/MostrarErrores";
import SubirArchivos from "./../../components/SubirArchivos";

export default {
    name: "registrar-requisitos-externos",
    components: {
        MostrarErrores,
        SubirArchivos        
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            rutas: [],                  
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
        this.getRutas();
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
        getRutas() {
            axios.get(`${this.api_url}/movimiento/ruta`, {
                    params: {
                        idprocedimiento_actual: this.idprocedimiento_actual
                    }
                })
                .then(response => {                                                                
                    this.rutas = response.data;                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },        
        mover(ruta) {          
            if (!this.validarDocumentos()) {
                return
            }           

            this.$bvModal.msgBoxConfirm(
                '¿Seguro que quiere ' + ruta.etiqueta + ' este expediente?', {
                title: ruta.etiqueta.charAt(0).toUpperCase()+ruta.etiqueta.slice(1) + ' Expediente',                    
                okVariant: 'success',
                okTitle: ruta.etiqueta.charAt(0).toUpperCase()+ruta.etiqueta.slice(1),
                cancelTitle: 'Cancelar',          
                centered: true
            }).then(value => {
                if (value) {
                    axios.post(`${this.api_url}/movimiento/mover`, {
                        idexpediente: this.idexpediente,
                        idruta: ruta.id,
                        idproc_origen: ruta.idproc_origen,
                        idproc_destino: ruta.idproc_destino,
                    })
                    .then((response) => {                        
                        if (!response.data.error) { 
                            this.$store.dispatch('showAlert', { vm:this, 
                                alert:{titulo:'Registro de plan de tesis', contenido:response.data.successMessage, tipo:'success', icono:'done'}})
                        } 
                        else {
                            this.$store.dispatch('showAlert', {vm:this, 
                                alert:{titulo:'Registro de plan de tesis', contenido:response.data.errorMessage, tipo:'danger', icono:'error'}})                        
                        }   
                        this.$emit("reload-parent");
                    })
                }                   
            })    
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