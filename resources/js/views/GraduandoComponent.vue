<template>
    <div>
        <b-card no-body>
            <div class="text-center">
                <img                    
                    class="avatar border-gray"
                    :src="`http://190.119.145.150:8023/fotos/${anio_cui}/${cui}.jpg`"
                />
            </div>
            <b-card-body>
                <b-card-text>
                    <p>
                        <label> <b>CUI:</b> {{ cui }} </label><br />                                        
                        <label> <b>E-mail:</b> {{ email }} </label>
                    </p>                   
                    
                    <h5>Información de contacto:</h5>
                    
                    <div v-if="!edit_flag">
                        <label><b>Teléfono fijo:</b> {{ contacto.telefono_fijo }}</label><br />
                        <label><b>Teléfono móvil:</b> {{ contacto.telefono_movil }}</label><br />
                        <label><b>Dirección:</b> {{ contacto.direccion }}</label><br />
                        <b-button variant="warning" @click="changeEdit">
                            <b-icon icon="pencil-square"></b-icon> Editar
                        </b-button>
                    </div>
                    <div v-else>
                        <b-form @submit.prevent="actualizarDatos">
                            <b-form-group
                                id="input-group-tel-fijo"
                                label="Teléfono fijo"
                                label-for="input-tel-fijo"                                
                            >                                
                                <b-form-input id="input-tel-fijo" v-model="contacto.telefono_fijo" autocomplete="off"></b-form-input>
                                <span v-if="errors.telefono_fijo" class="text-danger">{{ errors.telefono_fijo[0] }}</span>
                            </b-form-group>
                            <b-form-group
                                id="input-group-tel-movil"                                
                                label-for="input-tel-movil"
                            >                   
                                <template v-slot:label>
                                    Teléfono móvil <span style="color:red;">*</span>
                                </template>             
                                <b-form-input id="input-tel-movil" v-model="contacto.telefono_movil" required autocomplete="off"></b-form-input>
                                <span v-if="errors.telefono_movil" class="text-danger">{{ errors.telefono_movil[0] }}</span>
                            </b-form-group>
                            <b-form-group
                                id="input-group-direccion"                                
                                label-for="input-direccion"
                            >
                                <template v-slot:label>
                                    Dirección <span style="color:red;">*</span>
                                </template>   
                                <b-form-input id="input-direccion" v-model="contacto.direccion" required autocomplete="off"></b-form-input>
                                <span v-if="errors.direccion" class="text-danger">{{ errors.direccion[0] }}</span>
                            </b-form-group>
                            <b-button type="submit" variant="success">Actualizar</b-button>
                            <b-button @click="changeEdit" variant="danger">Cancelar</b-button>
                        </b-form>
                    </div>
                </b-card-text>
            </b-card-body>
        </b-card>
    </div>
</template>

<script>
export default {
    name: "graduando",
    props : [        
        'anio_cui', 
        'cui', 
        'email'        
    ],
    data() {
        return {
            api_url: this.$root.api_url,            
            contacto: {},
            tmp_contacto: {},
            edit_flag: false,
            errors: []                    
        };
    },
    created() {        
        this.getContacto()
    },
    methods: {
        getContacto() {
            axios.get(`${this.api_url}/graduando/contacto`)
                .then(response => {
                    this.contacto = response.data;                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        actualizarDatos() {
            this.errors = [];
            axios.put(`${this.api_url}/graduando/actualizar`,
                    {
                        telefono_fijo: this.contacto.telefono_fijo,
                        telefono_movil: this.contacto.telefono_movil,
                        direccion: this.contacto.direccion
                    }
                )
                .then(response => {
                    this.edit_flag = false;
                    if (!response.data.error) {                        
                        this.$vs.notify({
                            title: "Actualización de datos",
                            text: response.data.successMessage,
                            color: "success",
                            icon: "done",
                            position: "top-left",
                            time: 4000
                        })               
                    } else {
                        this.$vs.notify({
                            title: "Actualización de datos",
                            text: response.data.errorMessage,
                            color: "warning",
                            icon: "error",
                            position: "top-left",
                            time: 4000
                        });
                    }                      
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } 
                });
        },
        changeEdit() {
            if (!this.edit_flag) {
                this.edit_flag = true;
                this.tmp_contacto = Object.assign({}, this.contacto);
            } else {
                this.edit_flag = false;
                this.contacto = Object.assign({}, this.tmp_contacto);
            }
        }           
    }
};
</script>
<style>
    .form-group.required > label:after {
    content: " *";
    color: red;
}
</style>
