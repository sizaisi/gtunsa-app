<template>
    <div>    
        <b-form @submit.prevent="registrarAsesor" class="mb-3">
            <b-row>
                <b-col col lg="8">
                    <v-select   
                        v-model="idasesor"
                        :options="docentes"                                              
                        :reduce="docente => docente.id" 
                        label="apn"
                        class="style-chooser"
                        placeholder="-- Seleccionar asesor de proyecto de tesis--"                                                                                                             
                    > 
                        <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!idasesor"
                                v-bind="attributes"
                                v-on="events"
                            />
                        </template>
                        <template slot="no-options">
                            Opción no encontrada...
                        </template>
                    </v-select>
                </b-col>                    
                <b-col col lg="2">
                    <b-button style="height:34px" variant="success" size="sm" type="submit" title="Añadir" :disabled="asesor!=null">
                        <b-icon icon="plus-circle"></b-icon> Agregar
                    </b-button>
                </b-col>                    
            </b-row>  
        </b-form>        
        <table class="table table-bordered table-sm" v-if="asesor != null">   
            <thead>
                <th class="text-center">Docente Asesor</th> 
                <th class="text-center">Departamento</th>                                                                  
                <th class="text-center">Eliminar</th>
            </thead>
            <tbody>
                <tr>                              
                    <td class="text-center" v-text="asesor.apn"></td>
                    <td class="text-center" v-text="asesor.departamento"></td>                                                
                    <td class="text-center">
                        <b-button variant="danger" size="sm" title="Eliminar asesor" @click="eliminarAsesor()">
                            <b-icon icon="trash"></b-icon>
                        </b-button>
                    </td>
                </tr>                                                
            </tbody>
        </table>                             
    </div>    
</template>

<script>
export default {
    name: 'docentes-facultad',
    props: ["idexpediente"],
    data() {
        return {             
            api_url: this.$root.api_url,                          
            idasesor : null,
            docentes : [],            
            asesor: null,                                                                           
        }
    },
    created() {             
        this.getAsesor()        
        this.getDocentes()                         
    },
    methods: {                           
        getDocentes() {           
            axios.get(`${this.api_url}/docente/${this.idexpediente}`)
                .then(response => {                                       
                    this.docentes = response.data                    
                })
        },        
        getAsesor() {            
            axios.get(`${this.api_url}/expediente/asesor/${this.idexpediente}`)
                .then(response => {
                    console.log('getasesor')                                            
                    this.asesor = response.data                    
                })  
        },                  
        registrarAsesor() {                                            
            axios.put(`${this.api_url}/expediente/asesor`, {
                    idexpediente: this.idexpediente,
                    idasesor: this.idasesor,                    
                })
                .then(response => {                                                                                                  
                    if (!response.data.error) {                        
                        this.$vs.notify({
                            title: "Registro de asesor propuesto",
                            text: response.data.successMessage,
                            color: "success",
                            icon: "done",
                            position: "bottom-right",
                            time: 4000
                        })            
                        this.getAsesor()   
                        this.resetearValores()
                    } else {
                        this.$vs.notify({
                            title: "Registro de asesor propuesto",
                            text: response.data.errorMessage,
                            color: "warning",
                            icon: "error",
                            position: "bottom-right",
                            time: 4000
                        });
                    }                           
                })      
        },
        eliminarAsesor() {                               
            this.$bvModal.msgBoxConfirm("¿Esta seguro de eliminar su asesor?", {
                title: "Eliminar asesor",
                okVariant: "danger",
                okTitle: "SI",
                cancelTitle: "NO",
                centered: true,
            })
            .then((value) => {
                if (value) {                   
                    axios.delete(`${this.api_url}/expediente/asesor`, {
                        params: {
                            idexpediente: this.idexpediente
                        }
                    })
                    .then(response => {                        
                        if (!response.data.error) {                                                        
                            this.$vs.notify({
                                title: "Éxito",
                                text: response.data.successMessage,
                                color: "success",
                                icon: "done",
                                position: "top-right",
                                time: 4000
                            });                                               
                            this.getAsesor()
                            this.resetearValores()
                        } else {
                            this.$vs.notify({
                                title: "Aviso",
                                text: response.data.errorMessage,
                                color: "warning",
                                icon: "error",
                                position: "top-right",
                                time: 4000
                            });
                        }                                    
                    })              
                }
            });             
        },                        
        resetearValores() {          
            this.idasesor = null          
            this.resetParent()
        },
        resetParent() {
            this.$emit('reset');
        },
    },        
}
</script>
<style scoped>
    ul {
        margin-bottom: 0px;    
    }         
</style>
<style>
    .disabledTab{
        pointer-events: none;
    }      
    .style-chooser .vs__search::placeholder,
    .style-chooser .vs__dropdown-toggle,
    .style-chooser .vs__dropdown-menu {    
        max-height: 200px;
    }
</style>

