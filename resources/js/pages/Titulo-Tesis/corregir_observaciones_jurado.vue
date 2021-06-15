<template>
    <div>          
        <b-card no-body>
            <b-tabs
                v-model="tabIndex"
                card
                active-nav-item-class="font-weight-bold text-uppercase text-danger"
            >
                <b-tab
                    title="1. Ver observaciones"
                    title-item-class="disabledTab"
                    :disabled="tabIndex2 < 0"
                >
                    <observaciones :idexpediente="idexpediente" />
                </b-tab>                
                <b-tab :title="'2. Adjuntar Documentos'" title-item-class="disabledTab" :disabled="tabIndex2 < 1">
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
                    :disabled="tabIndex == 1"
                >
                    Siguiente
                </b-button>
            </b-button-group>
        </div>
        
    </div>
</template>
<script>
import config from "./../../config";
import MostrarErrores from "./../../components/MostrarErrores";
import Observaciones from "./../../components/Observaciones";
import SubirArchivos from "./../../components/SubirArchivos";

export default {
    name: "corregir-observaciones-jurado",
    components: {
        MostrarErrores,
        SubirArchivos,
        Observaciones        
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            rutas: [], 
            titulo: "",            
            etiquetas: config.etiquetas,
            array_tipo_documento: [
                { value: null, text: 'Tipo Documento', disabled: true },                
                { value: 'Borrador tesis corregido', text: 'Borrador tesis corregido', disabled: false},                           
            ],
            max_docs: 1,
            tabIndex: 0,
            tabIndex2: 0,
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
        prevTab() {
            this.errors = [];
            this.tabIndex2--;
            this.tabIndex--;
        },
        nextTab() {
            this.errors = [];
            let pasar = false;

            if (this.tabIndex == 0) {
                pasar = true;
            }            

            if (pasar) {
                this.tabIndex2++;
                this.$nextTick(function() {
                    this.tabIndex++;
                });
            }
        },        
        validarTab2() {        
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
            if (!this.validarTab2()) {
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