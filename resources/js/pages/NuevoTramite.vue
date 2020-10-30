<template>
    <div>
        <b-card title="Nuevo trámite:">
            <div class="d-flex justify-content-center mb-3">
                <b-button variant="outline-warning" :to="{ name: 'inicio' }"
                    ><b-icon
                        icon="arrow-left-circle-fill"
                        aria-hidden="true"
                    ></b-icon>
                    Volver</b-button
                >
            </div>
        <b-card no-body>
                <b-tabs 
                    v-model="tabIndex" 
                    card        
                    active-nav-item-class="font-weight-bold text-uppercase text-danger"                                
                >            
                    <b-tab title="1. Información personal" title-item-class="disabledTab" :disabled="tabIndex2 < 0">                        
                        <b-row>                                
                            <b-col sm="12" lg="2">
                                <b-form-group
                                    id="lbl-1"
                                    label="Nro. Documento"
                                    label-for="nro_documento"                                        
                                >
                                    <b-form-input
                                    class="text-center"
                                    id="nro_documento"
                                    :value="graduando.dni"              
                                    type="text"
                                    readonly                                        
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col sm="12" lg="5">
                                <b-form-group
                                    id="lbl-2"
                                    label="Nombres"
                                    label-for="nombres"                                        
                                >
                                    <b-form-input
                                    class="text-center"
                                    id="nombres"       
                                    :value="graduando.nombres"                                       
                                    type="text"
                                    readonly                                        
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col sm="12" lg="5">
                                <b-form-group
                                    id="lbl3"                                    
                                    label="Apellidos"
                                    label-for="apellidos"                                    
                                >
                                    <b-form-input
                                    class="text-center"
                                    id="apellidos"                                
                                    :value="graduando.apellidos"              
                                    type="text"
                                    readonly                                        
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>                                        
                        <b-row>
                            <b-col>
                                <b-form-checkbox
                                    id="chk-1"
                                    v-model="datos_correctos"
                                    name="validacion"
                                    value="acepto"
                                    unchecked-value="no_acepto"                                    
                                >
                                    <b
                                        >Acepto que los datos de Nro. Documento, Nombres y Apellidos están de acuerdo a mi documento de identidad (DNI).</b
                                    >
                                </b-form-checkbox>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <small
                                    >En caso su información no es correcta comunicarse
                                    con DUFA al correo dufa@unsa.edu.pe</small
                                >
                            </b-col>
                        </b-row>                                       
                    </b-tab>    
                    <b-tab title="2. Información de contacto" title-item-class="disabledTab" :disabled="tabIndex2 < 1">
                        <b-row>                                
                            <b-col sm="12" lg="6">
                                <b-form-group
                                    id="lbl-4"
                                    label="Teléfono fijo"
                                    label-for="telefono_fijo"                                        
                                >
                                    <b-form-input
                                    class="text-center"
                                    id="telefono_fijo"  
                                    v-model="graduando.telefono_fijo"                              
                                    type="text"                                                                         
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col sm="12" lg="6">
                                <b-form-group
                                    id="lbl-5"
                                    label="Teléfono móvil"
                                    label-for="telefono_movil"                                        
                                >
                                    <b-form-input
                                    class="text-center"
                                    id="telefono_movil"
                                    v-model="graduando.telefono_movil"                                
                                    type="text"                                                                         
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>                            
                        </b-row>    
                        <b-row>
                            <b-col sm="12" lg="12">
                                <b-form-group
                                    id="lbl-6"
                                    label="Dirección"
                                    label-for="direccion"                                    
                                >
                                    <b-form-input
                                    id="direccion"                
                                    v-model="graduando.direccion"                
                                    type="text"                                                                 
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>                                                           
                    </b-tab>                              
                    <b-tab :title="'3. Registrar trámite'" 
                        title-item-class="disabledTab" :disabled="tabIndex2 < 2">
                        <b-form @submit.prevent="registrarTramite">
                            <b-row>
                                <b-col>
                                    <b-form-group
                                        id="input-group-3"
                                        label="Escuela o Programa:"
                                        label-for="input-3"
                                    >
                                        <b-form-select
                                            id="input-3"
                                            v-model="escuela"
                                            :options="escuelas"
                                            required
                                        >
                                            <template v-slot:first>
                                                <option :value="null" disabled>
                                                    -- Por favor seleccione una opción --
                                                </option>
                                            </template>
                                        </b-form-select>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                            <b-row>
                                <b-col lg="6" sm="12">
                                    <b-form-group
                                        id="input-group-3"
                                        label="Grado ó Título:"
                                        label-for="input-3"
                                    >
                                        <b-form-select
                                            id="input-3"
                                            v-model="idgrado_titulo"
                                            :options="grado_titulos"
                                            required
                                        >
                                            <template v-slot:first>
                                                <option :value="null" disabled>
                                                    -- Por favor seleccione una opción --
                                                </option>
                                            </template>
                                        </b-form-select>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="6" sm="12">
                                    <b-form-group
                                        id="input-group-3"
                                        label="Modalidad de obtención:"
                                        label-for="input-3"
                                    >
                                        <b-form-select
                                            id="input-3"
                                            v-model="idgrado_modalidad"
                                            :options="grado_modalidades"
                                            required
                                        >
                                            <template v-slot:first>
                                                <option :value="null" disabled>
                                                    -- Por favor seleccione una opción --
                                                </option>
                                            </template>
                                        </b-form-select>
                                    </b-form-group>
                                </b-col>
                            </b-row>                
                            <div class="mt-3">
                                <b-button
                                    :disabled="datos_correctos == 'no_acepto'"
                                    type="submit"
                                    variant="success"
                                    >Registrar</b-button
                                >                    
                            </div>
                        </b-form>
                    </b-tab>
                </b-tabs>
            </b-card>        
            <div class="text-center">
                <b-button-group class="mt-3">
                    <b-button variant="secondary" class="mr-1" @click="prevTab" :disabled="tabIndex == 0">Anterior</b-button>
                    <b-button variant="secondary" @click="nextTab" :disabled="tabIndex == 2 || datos_correctos == 'no_acepto'" >Siguiente</b-button>
                </b-button-group>     
            </div> 
        </b-card>
        <!--<b-card header="Nuevo Trámite" header-tag="header">
            <b-form @submit.prevent="registrarTramite">
                <b-row>
                    <b-col>
                        <b-form-group
                            id="input-group-3"
                            label="Escuela o Programa:"
                            label-for="input-3"
                        >
                            <b-form-select
                                id="input-3"
                                v-model="escuela"
                                :options="escuelas"
                                required
                            >
                                <template v-slot:first>
                                    <option :value="null" disabled>
                                        -- Por favor seleccione una opción --
                                    </option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col lg="6" sm="12">
                        <b-form-group
                            id="input-group-3"
                            label="Grado ó Título:"
                            label-for="input-3"
                        >
                            <b-form-select
                                id="input-3"
                                v-model="idgrado_titulo"
                                :options="grado_titulos"
                                required
                            >
                                <template v-slot:first>
                                    <option :value="null" disabled>
                                        -- Por favor seleccione una opción --
                                    </option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col lg="6" sm="12">
                        <b-form-group
                            id="input-group-3"
                            label="Modalidad de obtención:"
                            label-for="input-3"
                        >
                            <b-form-select
                                id="input-3"
                                v-model="idgrado_modalidad"
                                :options="grado_modalidades"
                                required
                            >
                                <template v-slot:first>
                                    <option :value="null" disabled>
                                        -- Por favor seleccione una opción --
                                    </option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <b-form-checkbox
                            id="checkbox-1"
                            v-model="datos_correctos"
                            name="checkbox-1"
                            value="acepto"
                            unchecked-value="no_acepto"
                            description="We'll never share your email with anyone else."
                        >
                            <b
                                >Estoy de acuerdo con los datos de información
                                personal proporcionados por la universidad.</b
                            >
                        </b-form-checkbox>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <small
                            >En caso su información no es correcta comunicarse
                            con DUFA al correo dufa@unsa.edu.pe</small
                        >
                    </b-col>
                </b-row>
                <div class="mt-3">
                    <b-button
                        :disabled="datos_correctos == 'no_acepto'"
                        type="submit"
                        variant="success"
                        >Registrar trámite</b-button
                    >
                    <b-button variant="danger" :to="{ name: 'inicio' }"
                        >Cancelar</b-button
                    >
                </div>
            </b-form>
        </b-card>-->
    </div>
</template>
<script>
export default {
    name: "nuevo-tramite",
    data() {
        return {
            api_url: this.$root.api_url,
            graduando: {},
            show: 3,
            escuela: null,
            escuelas: [],
            idgrado_titulo: null,
            grado_titulos: [],
            idgrado_modalidad: null,
            grado_modalidades: [],
            datos_correctos: "no_acepto",
            tabIndex: 0,         
            tabIndex2: 0,             
            errors: [], 
        };
    },
    watch: {
        escuela: function(val) {
            this.idgrado_titulo = null;

            axios
                .get(`${this.api_url}/GradoModalidad/grado_titulo`, {
                    params: {
                        nive: val.nive,
                        codigo: val.nues.substr(0, 1)
                    }
                })
                .then(response => {
                    this.grado_titulos = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        idgrado_titulo: function(val) {
            this.idgrado_modalidad = null;

            axios
                .get(`${this.api_url}/GradoModalidad/modalidad_obtencion`, {
                    params: {
                        idgrado_titulo: val
                    }
                })
                .then(response => {
                    this.grado_modalidades = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    created() {
        this.getEscuelas();
        this.getGraduando();
    },
    methods: {
        getGraduando() {
            axios
                .get(`${this.api_url}/graduando`)
                .then(response => {
                    this.graduando = response.data;
                    this.cui_anio = this.graduando.cui.substr(0, 4);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getEscuelas() {
            axios
                .get(`${this.api_url}/escuela`)
                .then(response => {
                    this.escuelas = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        registrarTramite() {
            //validar que cui nues y espe no tenga registro en proceso en gt_expediente
            //si hay por lo menos un registro no mostrar mensaje de error con respectivo mensaje
            //warning ud tiene un expediente en proceso para la escuela o programa seleccionado

            axios
                .get(`${this.api_url}/expediente/registrar/`, {
                    params: {
                        idgrado_modalidad: this.idgrado_modalidad,
                        nues: this.escuela.nues,
                        espe: this.escuela.espe,
                        telefono_fijo: this.graduando.telefono_fijo,
                        telefono_movil: this.graduando.telefono_movil,
                        direccion: this.graduando.direccion
                    }
                })
                .then(response => {
                    this.$vs.notify({
                        title: "Éxito",
                        text: "¡Su trámite fue registrado con éxito!",
                        color: "success",
                        icon: "done",
                        position: "top-center",
                        time: 4000
                    });

                    this.$router.push({ name: "inicio" });
                })
                .catch(error => {
                    console.log(error);
                    this.$vs.notify({
                        title: "Error",
                        text: "No se pudo registrar su trámite",
                        color: "danger",
                        icon: "error",
                        position: "top-center",
                        time: 4000
                    });
                });
        },
        prevTab() {
            this.errors = [] 
            this.tabIndex2--       
            this.tabIndex--        
        },  
        nextTab() {      
            this.errors = [] 
            let pasar = false    

            if (this.tabIndex == 0) {
                pasar = true;
            }                             
            
            if (this.tabIndex == 1) {
                pasar = true;
            }                             
                       
            if (pasar) {
                this.tabIndex2++
                this.$nextTick(function () {
                    this.tabIndex++        
                })  
            }              
        },  
    }
};
</script>