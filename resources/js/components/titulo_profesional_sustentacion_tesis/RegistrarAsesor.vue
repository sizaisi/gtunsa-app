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
    name: 'registrar-asesor',
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
            axios.get(`${this.api_url}/expediente_titulo_tesis/asesor/${this.idexpediente}`)
                .then(response => {                                                                
                    this.asesor = response.data                    
                })  
        },                  
        registrarAsesor() {                                            
            axios.put(`${this.api_url}/expediente_titulo_tesis/asesor`, {
                    idexpediente: this.idexpediente,
                    idasesor: this.idasesor,                    
                })
                .then(response => {                                                                                                  
                    if (!response.data.error) {                                                
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Registro de asesor propuesto', contenido:response.data.successMessage, tipo:'success', icono: 'done'}})   
                        this.getAsesor()   
                        this.resetearValores()
                    } else {
                        this.$store.dispatch('showAlert', { vm:this, 
                            alert:{titulo:'Registro de asesor propuesto', contenido:response.data.errorMessage, tipo:'danger', icono: 'error'}})                           
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
                    axios.delete(`${this.api_url}/expediente_titulo_tesis/asesor`, {
                        params: { idexpediente: this.idexpediente }
                    })
                    .then(response => {                        
                        if (!response.data.error) {                                                                                             
                            this.$store.dispatch('showAlert', { vm:this, 
                                alert:{titulo:'Eliminar asesor', contenido:response.data.successMessage, tipo:'success', icono: 'done'}})
                            this.getAsesor()    
                        } else {
                            this.$store.dispatch('showAlert', { vm:this, 
                                alert:{titulo:'Eliminar asesor', contenido:response.data.errorMessage, tipo:'danger', posicion: 'error'}})                            
                        }                           
                        this.resetearValores()                                 
                    })              
                }
            });             
        },                        
        resetearValores() {          
            this.idasesor = null          
            this.$emit('reset');
        },        
    },        
}
</script>
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

