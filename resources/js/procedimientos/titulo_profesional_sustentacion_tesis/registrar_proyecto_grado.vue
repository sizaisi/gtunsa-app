<template>
    <div>          
        <b-card no-body>
            <b-tabs v-model="tabIndex" card active-nav-item-class="font-weight-bold text-uppercase text-danger">
                <b-tab
                    title="1. Registrar título"
                    title-item-class="disabledTab"
                    :disabled="tabIndex2 < 0"
                >
                    <registrar-titulo 
                        ref="componente_registrar_titulo"
                        :idexpediente="idexpediente"
                        @reset="resetChild"
                    />                                        
                    <mostrar-errores :errors="errors"/>
                </b-tab>
                <b-tab :title="'2. Proponer Asesor'" title-item-class="disabledTab" :disabled="tabIndex2 < 1">
                    <registrar-asesor 
                        :idexpediente="idexpediente"
                        ref="componente_registrar_asesor"
                        @reset="resetChild"
                    />                    
                    <mostrar-errores :errors="errors"/>
                </b-tab>
                <b-tab :title="'3. Adjuntar Documentos'" title-item-class="disabledTab" :disabled="tabIndex2 < 2">
                    <subir-archivos
                        :idexpediente="idexpediente" 
                        :idprocedimiento="idprocedimiento_actual"                               
                        :array_opciones="array_tipo_documento"
                        :max_docs="max_docs"
                        ref="documentos"
                        @reset="resetChild"
                    />                                              
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
import MostrarErrores from "./../../components/MostrarErrores";
import SubirArchivos from "./../../components/SubirArchivos";
import RegistrarTitulo from "./../../components/titulo_profesional_sustentacion_tesis/RegistrarTitulo";
import RegistrarAsesor from "./../../components/titulo_profesional_sustentacion_tesis/RegistrarAsesor";

export default {
    name: "registrar_proyecto_grado",
    components: {     
        MostrarErrores,   
        RegistrarTitulo,
        RegistrarAsesor,
        SubirArchivos,
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            rutas: [],                  
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
            
            if (this.$refs.componente_registrar_titulo.titulo == "") {
                this.errors.push("Debe ingresar el título de su plan de tesis.")
            }   

            if (this.$refs.componente_registrar_titulo.editar == true) {
                this.errors.push("Debe guardar el título ingresado.")
            }   
                        
            if (!this.errors.length) {
                return true
            }      

            return false
        },
        validarTab2() {        
            this.errors = []             

            if (this.$refs.componente_registrar_asesor.asesor == null) { 
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
            if (!this.validarTab3()) {
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