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
    name: "b_a_registrar_requisitos_externos",
    components: {
        MostrarErrores,
        SubirArchivos
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            ruta: {},
            rutas: [],            
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
        this.getRutas();        
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
        getRutas() {
            axios.get(`${this.api_url}/movimiento/ruta`, {
                    params: {
                        idprocedimiento_actual: this.idprocedimiento_actual
                    }
                })
                .then(response => {                
                    //console.log(response.data)                                                          
                    this.rutas = response.data;                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },        
        mover(ruta) {                    

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