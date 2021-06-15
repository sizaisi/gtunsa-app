<template>
    <div>    
        <b-form-group
            id="textarea-group-1"            
            label-for="textarea-1"
            description="Este título debe coincidir con el título de plan de tesis del archivo adjunto"
        >            
            <template v-slot:label>
                Título de plan de tesis <span style="color:red;">*</span>
            </template>  
            <b-form-textarea
                id="textarea-1"                
                v-model="titulo"                
                rows="2"                
                required
                :disabled="!editar"
            >
            </b-form-textarea>
            <span v-if="errors.titulo" class="text-danger">{{ errors.titulo[0] }}</span>
        </b-form-group>  
        <div class="mb-3">
            <b-button variant="warning" v-show="!editar" @click="editar=true">Editar</b-button>                   
            <b-button variant="success" v-show="editar" @click="guardarTitulo">Guardar</b-button>                   
        </div>        
    </div>    
</template>

<script>
export default {
    name: 'registrar-titulo',   
    props: ["idexpediente"], 
    data() {
        return {             
            api_url: this.$root.api_url,                          
            titulo: "", 
            editar: false,
            errors: []          
        }
    },
    created() {             
        this.getTitulo()
    },
    methods: {    
        getTitulo() {            
            axios.get(`${this.api_url}/expediente_titulo_tesis/titulo/${this.idexpediente}`)
                .then(response => {                                                                
                    this.titulo = response.data
                })  
        },
        guardarTitulo() {
            this.errors = [];
            
            axios.put(`${this.api_url}/expediente_titulo_tesis/titulo`, {
                    idexpediente: this.idexpediente,
                    titulo: this.titulo,                    
                })
                .then(response => {                                                                                                  
                    if (!response.data.error) {                                                
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Registro de título de plan de tesis', contenido:response.data.successMessage, tipo:'success', icono: 'done'}})                                                                  
                        this.editar = false
                    } else {
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Registro de título de plan de tesis', contenido:response.data.errorMessage, tipo:'danger', icono: 'error'}})
                    }                                               
                    this.resetearValores()
                })    
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });            
        },
        resetearValores() {                            
            this.$emit('reset');
        },    
    },        
}
</script>