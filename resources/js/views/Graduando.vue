<template>
    <div>        
        <b-card no-body>
            <div class="text-center">
                <img v-if="url" :src="url" width="100" height="120"/>
                <img v-else-if="graduando.foto === null"                  
                    class="avatar border-gray"
                    :src="`http://190.119.145.150:8023/fotos/${cui_year}/${cui}.jpg`"
                />
                <img v-else
                    class="avatar border-gray"
                    width="100" height="120"
                    :src="fotoGraduando"                    
                />
            </div>
            <b-card-body>
                <b-card-text>
                    <div class="text-center"><b>C.U.I.:</b> {{ cui }}</div>                                       
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
                            <div style="background-color: #6f42c1" class="text-center text-light rounded" id="upload">
                                <label>
                                    <span v-if="!status_foto">Subir nueva fotografía</span>
                                    <span v-else>Cambiar fotografía</span>
                                    <input @change="vistaPrevia($event)" type="file" class="sr-only">
                                </label>
                            </div>
                            <b-popover target="upload" triggers="hover" placement="right">
                                <template #title>Requisitos de la fotografía</template>
                                <ul>
                                    <li>Imagen a color con fondo <b>blanco</b>, de frente, con traje formal, sin gorra y sin lentes.</li>
                                    <li>Sin sellos ni enmendaduras, la imagen debe enfocarse en el rostro del estudiante a partir de los hombros.</li>
                                    <li>No mostrar medio cuerpo, ni acercarse mucho cubriendo el fondo, precaución con el cabello voluminoso.</li>
                                    <li>Evitar vestir completamente de blanco.</li>
                                    <li><b>ANCHO DE 240 PX </b></li>
                                    <li><b>ALTO DE 288 PX</b></li>
                                    <li><b>RESOLUCION EN DPI 300</b></li>
                                    <li><b>FOTO SIN BORDES BLANCOS</b></li>
                                </ul>
                            </b-popover>
                            <div v-if="errors_foto.length" class="text-danger">
                                La fotografía no cumple con los requisitos establecidos:
                                <ul>
                                    <li v-for="error in errors_foto" :key="error.id" class="block">{{error}}</li>
                                </ul>
                            </div>
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
                            <b-button @click="reiniciar_variables()" variant="danger">Cancelar</b-button>
                        </b-form>
                    </div>
                </b-card-text>
            </b-card-body>


        </b-card>
        <!--<div class="d-inline">
            <b-button @click="open1" variant="success">bachiller 1</b-button>
            <b-button @click="open2" variant="success">bachiller 2</b-button>
            <b-button @click="open3" variant="success">titulo 1</b-button>
            <b-button @click="open4" variant="success">titulo 2</b-button>
        </div>   -->
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
            errors: [],
            errors_foto: [],
            status_foto: false,
            file_foto: "",
            url: null,         
            fotoGraduando: null          
        };
    },
    created() {        
        this.getGraduando();
    },
    methods: {
        open1(){
                window.open(`${this.api_url}/pdf/bachiller_solicitud_proyecto`,'_blank')
        },
        open2(){
                window.open(`${this.api_url}/pdf/bachiller_solicitud_automatico`,'_blank')
        },
        open3(){
                window.open(`${this.api_url}/pdf/titulacion_solicitud_proyecto`,'_blank')
        },
        open4(){
                window.open(`${this.api_url}/pdf/titulacion_solicitud_modalidad`,'_blank')
        },
        vistaPrevia(event, i) {
            this.file_foto = event.target.files[0];
            this.status_foto = true;
            this.url = URL.createObjectURL(this.file_foto);
        },
        reiniciar_variables(){
            this.edit_flag = false;
            this.status_foto = false;
            //this.url = null;
            this.file_foto = "";
            this.errors_foto = [];            
        },
        async getGraduando() {            
            try {
                const response = await axios.get(`${this.api_url}/graduando`)                                 
                this.graduando = response.data
                
                if (!this.url) {
                    this.fotoGraduando = `${this.api_url}/storage/fotos/${this.cui}.jpg?${new Date().getTime()}`
                }                

                this.$store.dispatch('setGraduando', this.graduando)   
            } catch (error) {
                console.log(error)
            }      
        },
        actualizar() {
            this.errors = []
            this.errors_foto = []
            if(this.url != null)
                this.actualizar_foto(this.file_foto);
            else
                this.actualizar_datos();
        },
        actualizar_foto(file){
            var self = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            };
            // form data
            let formData = new FormData();
            formData.append('cui', this.graduando.cui);
            formData.append('foto', file);
            
            axios.post(`files/upload-photo/${this.graduando.id}`, formData, config)
            .then(function (response) {                
                self.actualizar_datos()                
            })
            .catch(error => {
                if (error.response) {
                    this.errors_foto = error.response.data.errors.foto;
                }
                console.log("Error subiendo la foto: "+ error);
            });
        },
        actualizar_datos(){
            axios.put(`${this.api_url}/graduando/${this.graduando.id}`, this.graduando)
                .then(response => {
                    this.reiniciar_variables();
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
