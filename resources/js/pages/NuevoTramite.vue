<template>
    <div>
        <b-card title="Nuevo trámite:">
            <div class="d-flex justify-content-center mb-3">
                <b-button variant="outline-warning" :to="{ name: 'tramites' }"
                    ><b-icon icon="arrow-left-circle-fill"></b-icon> Volver
                </b-button>
            </div>
            <b-card no-body>
                <b-tabs v-model="tabIndex" card active-nav-item-class="font-weight-bold text-uppercase text-danger">
                    <b-tab title="1. Validar datos" title-item-class="disabledTab" :disabled="tabIndex2 < 0">
                        <b-row>
                            <b-col sm="12" lg="2">
                                <b-form-group
                                    id="lbl-1"
                                    label="DNI:"
                                    label-for="nro_documento"
                                >
                                    <b-form-input
                                        class="text-center"
                                        id="nro_documento"
                                        v-model="dni"
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
                                        v-model="nombres"
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
                                        v-model="apellidos"
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
                                    <b>
                                        Confirmo que mis datos están correctos (<span style="color:red;">*</span>)
                                        y asi deben mostrarse en los documentos que se generen en trámites de grados y títulos.
                                    </b>
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
                                        id="input-group-1"
                                        label="Escuela o Programa:"
                                        label-for="input-1"
                                    >
                                        <b-form-select
                                            id="input-1"
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
                                        id="input-group-2"
                                        label="Trámite:"
                                        label-for="input-2"
                                    >
                                        <b-form-select
                                            id="input-2"
                                            v-model="tramite"
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
                    <b-button variant="secondary" class="mr-1" @click="prevTab" :disabled="tabIndex == 0">
                        Anterior
                    </b-button>
                    <b-button variant="secondary" @click="nextTab" :disabled="tabIndex == 1 || datos_correctos == 'no_acepto'">
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
    props:['graduando'],
    data() {
        return {
            api_url: this.$root.api_url,                                    
            dni: '',
            nombres: '',
            apellidos: '',
            escuela: null,
            escuelas: [],
            tramite: null,
            tramites: [],            
            datos_correctos: "no_acepto",
            tabIndex: 0,
            tabIndex2: 0,
            errors: []
        };
    },
    watch: {
        escuela: async function(val) {           
            try {
                const response = await axios.get(`${this.api_url}/tramites`, {
                                        params: {                        
                                            prefijo_escuela: val.nues.substr(0, 1)
                                        }
                                    })
                this.tramites = response.data;                    
            } catch (error) {
                console.log(error)
            }               
        },        
    },    
    created() {
        if (this.graduando != null) {
            this.dni = this.graduando.alumno.dic.substring(1)
            this.getDataReniec()        
            this.getEscuelas()
        }
        else {
            this.$router.push({ name: 'tramites' }); 
        }           
    },
    methods: {
        prevTab() {
            this.errors = []
            this.tabIndex2--
            this.tabIndex--
        },
        nextTab() {
            this.errors = []
            let pasar = false

            if (this.tabIndex == 0) {
                pasar = true
            }

            if (pasar) {
                this.tabIndex2++
                this.$nextTick(function() {
                    this.tabIndex++
                });
            }
        },        
        async getDataReniec() {                                    
            try {
                const response = await axios.get(`${this.api_url}/api_dni/${this.dni}`)
                
                if (!response.data.hasOwnProperty('success')) {                                                    
                    this.nombres = response.data.nombres                                                
                    this.apellidos = `${response.data.apellidoPaterno} ${response.data.apellidoMaterno}`                             
                }      
                else {
                    let array_apn = this.graduando.alumno.apn.split(",").map(item => item.trim().replace('/', ' '))
                    this.nombres = array_apn[1]
                    this.apellidos = array_apn[0]                
                }
            } catch (error) {
                console.log(error)
            } 
        },        
        async getEscuelas() {
            try {
                const response = await axios.get(`${this.api_url}/escuelas`)                   
                this.escuelas = response.data
            } catch (error) {
                console.log(error)
            }                  
        },
        registrarTramite() {                        
            let url;                   
                        
            if (this.tramite.componente == 'Bachiller-Automatico') {                
                url = `${this.api_url}/bachiller_automatico`
            }
            else if (this.tramite.componente == 'Titulo-Tesis') {
                url = `${this.api_url}/titulo_tesis`
            }                    

            axios.post(url, {
                    tramite_id: this.tramite.id,
                    nues: this.escuela.nues,
                    espe: this.escuela.espe,
                    nombres: this.nombres,
                    apellidos: this.apellidos,
                })
                .then(response => {                    
                    if (!response.data.error) { 
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Registro de trámite', contenido:response.data.successMessage, tipo:'success', icono: 'done'}})
                    } else {                                                                              
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Registro de trámite', contenido:response.data.errorMessage, tipo:'danger', icono: 'error'}})
                    }
                    this.$router.push({ name: "tramites" });
                });                     
        },        
    }
};
</script>