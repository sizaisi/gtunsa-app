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
                        <label> <b>CUI:</b> {{ contacto.cui }} </label><br />                                                                
                    </p>                   
                    
                    <h5>Información de contacto:</h5>
                    
                    <div v-if="!edit_flag">
                        <label><b>Teléfono móvil:</b> {{ contacto.telefono }}</label><br />
                        <label><b>E-mail personal:</b> {{ contacto.email_personal }}</label><br />
                        <label><b>Domicilio:</b> {{ contacto.direccion }}</label><br />
                        <b-button variant="warning" @click="changeEdit">
                            <b-icon icon="pencil-square"></b-icon> Editar
                        </b-button>
                    </div>
                    <div v-else>
                        <b-form @submit.prevent="actualizarDatos">
                            <b-form-group
                                id="input-group-tel-movil"
                                label="Teléfono"
                                label-for="input-tel-movil"                                
                            >                                
                                <template v-slot:label>
                                    Teléfono móvil <span class="text-danger">*</span>
                                </template>             
                                <b-form-input id="input-tel-fijo" v-model="contacto.telefono" autocomplete="off"></b-form-input>
                                <span v-if="errors.telefono" class="text-danger">{{ errors.telefono[0] }}</span>
                            </b-form-group>
                            <b-form-group
                                id="input-group-email-personal"                                
                                label-for="input-email-personal"
                            >                   
                                <template v-slot:label>
                                    E-mail personal <span class="text-danger">*</span>
                                </template>             
                                <b-form-input type="email" id="input-email-personal" v-model="contacto.email_personal" required autocomplete="off"></b-form-input>
                                <span v-if="errors.email_personal" class="text-danger">{{ errors.email_personal[0] }}</span>
                            </b-form-group>
                            <b-form-group
                                id="input-group-direccion"                                
                                label-for="input-direccion"
                            >
                                <template v-slot:label>
                                    Domicilio <span class="text-danger">*</span>
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
        async getContacto() {            
            try {
                const response = await axios.get(`${this.api_url}/graduando/contacto`)                
                this.contacto = response.data.administrado
            } catch (error) {
                console.log(error)
            }      
        },
        actualizarDatos() {
            this.errors = []
            
            axios.put(`${this.api_url}/graduando/actualizar/${this.contacto.id}`, this.contacto)
                .then(response => {
                    this.edit_flag = false      

                    if (!response.data.error) {                                                                         
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Actualización de datos', contenido:response.data.successMessage, tipo:'success', icono: 'done'}})
                    } else {                                         
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Actualización de datos', contenido:response.data.errorMessage, tipo:'danger', icono: 'error'}})
                    }                      
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Actualización de datos', contenido:'Se ha producido un error al actualizar sus datos', tipo:'danger', icono: 'error'}})
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
        },              
    }
};
</script>
