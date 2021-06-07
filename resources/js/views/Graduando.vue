<template>
    <div>        
        <b-card no-body>
            <div class="text-center">
                <img                    
                    class="avatar border-gray"
                    :src="`http://190.119.145.150:8023/fotos/${cui_year}/${cui}.jpg`"
                />
            </div>
            <b-card-body>
                <b-card-text>
                    <div class="text-center"><b>CUI:</b> {{ cui }}</div>                                       
                    <hr>                          
                    <div v-if="!edit_flag">
                        <label><b>Teléfono móvil:</b> {{ graduando.telefono }}</label><br />
                        <label><b>E-mail personal:</b> {{ graduando.email_personal }}</label><br />
                        <label><b>Domicilio:</b> {{ graduando.direccion }}</label><br />
                        <b-button variant="warning" @click="edit_flag = !edit_flag">
                            <b-icon icon="pencil-square"></b-icon> Editar
                        </b-button>
                    </div>
                    <div v-else>
                        <b-form @submit.prevent="actualizar">
                            <b-form-group
                                id="input-group-tel-movil"
                                label="Teléfono"
                                label-for="input-tel-movil"                                
                            >                                
                                <template v-slot:label>
                                    Teléfono móvil <span class="text-danger">*</span>
                                </template>             
                                <b-form-input id="input-tel-fijo" v-model="graduando.telefono" autocomplete="off"></b-form-input>
                                <span v-if="errors.telefono" class="text-danger">{{ errors.telefono[0] }}</span>
                            </b-form-group>
                            <b-form-group
                                id="input-group-email-personal"                                
                                label-for="input-email-personal"
                            >                   
                                <template v-slot:label>
                                    E-mail personal <span class="text-danger">*</span>
                                </template>             
                                <b-form-input type="text" id="input-email-personal" v-model="graduando.email_personal" autocomplete="off"></b-form-input>
                                <span v-if="errors.email_personal" class="text-danger">{{ errors.email_personal[0] }}</span>
                            </b-form-group>
                            <b-form-group
                                id="input-group-direccion"                                
                                label-for="input-direccion"                            >
                                <template v-slot:label>
                                    Domicilio <span class="text-danger">*</span>
                                </template>   
                                <b-form-input id="input-direccion" v-model="graduando.direccion" autocomplete="off"></b-form-input>
                                <span v-if="errors.direccion" class="text-danger">{{ errors.direccion[0] }}</span>
                            </b-form-group>
                            <b-button type="submit" variant="success">Actualizar</b-button>
                            <b-button @click="edit_flag = false" variant="danger">Cancelar</b-button>
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
    props: ['cui_year', 'cui'],
    data() {
        return {
            api_url: this.$root.api_url,            
            graduando: {},                           
            edit_flag: false,
            errors: []                    
        };
    },
    created() {        
        this.getGraduando()
    },
    methods: {
        async getGraduando() {            
            try {
                const response = await axios.get(`${this.api_url}/graduando`)                 
                this.graduando = response.data
                this.$store.dispatch('setGraduando', this.graduando)   
            } catch (error) {
                console.log(error)
            }      
        },
        actualizar() {
            this.errors = []
            
            axios.put(`${this.api_url}/graduando/${this.graduando.id}`, this.graduando)
                .then(response => {
                    this.edit_flag = false      

                    if (!response.data.error) {                                                                         
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Actualización de datos', contenido:response.data.successMessage, tipo:'success', icono: 'done'}})
                        this.getGraduando()
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
    }
};
</script>
