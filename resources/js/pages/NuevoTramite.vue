<template>
    <div>
        <b-card title="Nuevo trámite:">
            <div class="d-flex justify-content-center mb-3">
                <b-button variant="outline-warning" :to="{ name: 'inicio' }"
                    ><b-icon icon="arrow-left-circle-fill"></b-icon> Volver
                </b-button>
            </div>
            <b-card no-body>
                <b-tabs
                    v-model="tabIndex"
                    card
                    active-nav-item-class="font-weight-bold text-uppercase text-danger"
                >
                    <b-tab
                        title="1. Validar datos"
                        title-item-class="disabledTab"
                        :disabled="tabIndex2 < 0"
                    >
                        <b-row>
                            <b-col sm="12" lg="2">
                                <b-form-group
                                    id="lbl-1"
                                    label="Nro. Documento:"
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
                                    label="Nombres:"
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
                                    label="Apellidos:"
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
                                        >Confirmo que mis datos están correctos
                                        (<span style="color:red;">*</span>) y asi deben mostrarse en los
                                        documentos que se generen en trámites de
                                        grados y títulos.</b
                                    >
                                </b-form-checkbox>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <small>
                                    <b><span style="color:red;">*</span></b> 
                                    En caso su información no es correcta comunicarse con DUFA al correo dufa@unsa.edu.pe
                                </small>
                            </b-col>
                        </b-row>
                    </b-tab>
                    <b-tab :title="'2. Registrar Trámite'" title-item-class="disabledTab" :disabled="tabIndex2 < 1">
                        <b-form @submit.prevent="registrarTramite()">
                            <b-row>
                                <b-col lg="6" sm="12">
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
                                                    -- Por favor seleccione una
                                                    opción --
                                                </option>
                                            </template>
                                        </b-form-select>
                                    </b-form-group>
                                </b-col>                            
                                <b-col lg="6" sm="12">
                                    <b-form-group
                                        id="input-group-3"
                                        label="Trámite:"
                                        label-for="input-3"
                                    >
                                        <b-form-select
                                            id="input-3"
                                            v-model="idtramite"
                                            :options="tramites"
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
                                <b-button :disabled="datos_correctos == 'no_acepto'" type="submit" variant="success">Registrar</b-button>
                            </div>
                        </b-form>
                    </b-tab>
                </b-tabs>
            </b-card>
            <div class="text-center">
                <b-button-group class="mt-3">
                    <b-button
                        variant="primary"
                        class="mr-1"
                        @click="prevTab"
                        :disabled="tabIndex == 0"
                    >
                        Anterior
                    </b-button>
                    <b-button
                        variant="primary"
                        @click="nextTab"
                        :disabled="tabIndex == 1 || datos_correctos == 'no_acepto'"
                    >
                        Siguiente
                    </b-button>
                </b-button-group>
            </div>
        </b-card>
    </div>
</template>
<script>
export default {
    name: "nuevo-tramite",
    data() {
        return {
            api_url: this.$root.api_url,
            graduando: {},
            escuela: null,
            escuelas: [],
            idtramite: null,
            tramites: [],            
            datos_correctos: "no_acepto",
            tabIndex: 0,
            tabIndex2: 0,
            errors: []
        };
    },
    watch: {
        escuela: function(val) {
            this.idtramite = null;

            axios.get(`${this.api_url}/tramites`, {
                    params: {                        
                        codigo: val.nues.substr(0, 1)
                    }
                })
                .then(response => {
                    this.tramites = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },        
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
                .post(`${this.api_url}/expediente/registrar`, {
                    idtramite: this.idtramite,
                    nues: this.escuela.nues,
                    espe: this.escuela.espe
                })
                .then(response => {
                    if (!response.data.error) {
                        this.$vs.notify({
                            title: "Registro de trámite",
                            text: response.data.successMessage,
                            color: "success",
                            icon: "done",
                            position: "top-center",
                            time: 4000
                        });
                    } else {
                        this.$vs.notify({
                            title: "Registro de trámite",
                            text: response.data.errorMessage,
                            color: "warning",
                            icon: "error",
                            position: "top-center",
                            time: 4000
                        });
                    }

                    this.$router.push({ name: "inicio" });
                });
        },
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
        }
    }
};
</script>
