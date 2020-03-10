<template>
    <b-card no-body>       
        <b-card-img src="img/person.png" class="rounded-0" height="250px"></b-card-img>
        <b-card-body title="Información Personal">
            <b-card-text>                
                <!--<label> <b>CUI:</b> {{ graduando.cui }} </label> <br>-->
                <label> <b>Apellidos:</b> {{ graduando.apellidos }} </label> <br> 
                <label> <b>Nombres:</b> {{ graduando.nombres }} </label> <br>                
                <label> <b>DNI:</b> {{ graduando.dni }} </label> <br>                                
                <!--<label> <b>E-mail:</b> {{ graduando.email }} </label> <br>-->
                <div v-if="!edit_flag">
                    <label> <b>Teléfono fijo:</b> {{ graduando.telefono_fijo }} </label> <br>   
                    <label> <b>Teléfono móvil:</b> {{ graduando.telefono_movil }} </label> <br>         
                    <label> <b>Dirección:</b> {{ graduando.direccion }} </label> <br>              
                    <b-button variant="success" @click="changeEdit">Actualizar datos</b-button>
                </div>                
                <div v-else>
                    <b-form @submit.prevent="actualizarDatos">
                        <b-form-group id="input-group-tel-fijo" label="Teléfono fijo:" label-for="input-tel-fijo">
                            <b-form-input
                                id="input-tel-fijo"
                                v-model="graduando.telefono_fijo"                                
                            ></b-form-input>
                            <span v-if="errors.telefono_fijo" class="text-danger">{{ errors.telefono_fijo[0] }}</span>
                        </b-form-group>
                        <b-form-group id="input-group-tel-movil" label="Teléfono móvil:" label-for="input-tel-movil">
                            <b-form-input
                                id="input-tel-movil"
                                v-model="graduando.telefono_movil"
                                required                                
                            ></b-form-input>
                            <span v-if="errors.telefono_movil" class="text-danger">{{ errors.telefono_movil[0] }}</span>
                        </b-form-group>
                        <b-form-group id="input-group-direccion" label="Dirección:" label-for="input-direccion">
                            <b-form-input
                                id="input-direccion"
                                v-model="graduando.direccion"
                                required                                
                            ></b-form-input>
                            <span v-if="errors.direccion" class="text-danger">{{ errors.direccion[0] }}</span>
                        </b-form-group>
                        <b-button type="submit" variant="primary">Actualizar</b-button>
                        <b-button @click="changeEdit" variant="danger">Cancelar</b-button>                                                
                    </b-form>
                </div>
            </b-card-text>
        </b-card-body>
    </b-card>
</template>

<script>
    export default {
        props : ['ruta'],
        data() {
            return {
                graduando : {},
                tmp_graduando : {},
                edit_flag: false,
                errors : []        
            }
        },
        methods : {
            getGraduando() {
                let me = this

                axios.get(`${this.ruta}/graduando`)
                .then(function (response) {                                    
                    me.graduando = response.data                                                             
                })
                .catch(function (error) {                    
                    console.log(error)
                })
            },
            actualizarDatos() {                
                let me = this
                this.errors = []

                axios.put(`${this.ruta}/graduando/actualizar/${this.graduando.id}`, {                                        
                    'telefono_fijo': this.graduando.telefono_fijo,
                    'telefono_movil': this.graduando.telefono_movil,
                    'direccion': this.graduando.direccion,
                }).then(function (response) {
                    me.edit_flag = false
                    me.$vs.notify({
                        title: 'Éxito',
                        text: 'Se actualizó su información personal',
                        color: 'success',
                        icon: 'done',
                        position: 'top-left',
                        time: 4000
                    })
                }).catch(function (error) {    
                    //console.log(error)                
                    if (error.response.status==422) {
                        me.errors = error.response.data.errors;
                    }
                    else {
                        me.$vs.notify({
                            title: 'Error',
                            text: 'No se pudo actualizar su informacion personal',
                            color: 'danger',
                            icon: 'error',
                            position: 'top-left',
                            time: 4000
                        })
                    }                    
                })                
            },
            changeEdit() {
                if(!this.edit_flag){
                    this.edit_flag = true
                    this.tmp_graduando = Object.assign({}, this.graduando)
                }
                else{
                    this.edit_flag = false
                    this.graduando = Object.assign({}, this.tmp_graduando)
                }
            },
        },        
        mounted() {            
            this.getGraduando()
        }
    }
</script>
