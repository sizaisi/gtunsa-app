<template>
    <div>                                  
        <div class="mb-4">
            <b>Asesor asignado: </b> {{ asesor.apn }}
        </div>
        <div class="mt-3">                        
            <b-button
                v-for="ruta in rutas"
                :key="ruta.id"         
                @click="mover(ruta)"                   
                :variant="etiquetas[ruta.etiqueta]"  
                class="mr-3"                    
            >
                {{ ruta.etiqueta | capitalize }}
            </b-button>
        </div>            
    </div>
</template>
<script>
import config from "./../../config";

export default {
    name: "evaluar-asesor-asignado",    
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,
            rutas: [], 
            asesor: {},            
            etiquetas: config.etiquetas,            
        };
    },
    created() {
        this.getRutas();
        this.getAsesor();
    },
    filters: {
        capitalize: function(value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    },
    methods: {                        
        getAsesor() {
            axios
                .get(`${this.api_url}/docente/getAsesor/${this.idexpediente}`)
                .then(response => {                                                                
                    this.asesor = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getRutas() {
            axios.get(`${this.api_url}/movimiento/ruta`, {
                    params: {
                        idprocedimiento_actual: this.idprocedimiento_actual
                    }
                })
                .then(response => {                                                                
                    this.rutas = response.data;                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },        
        mover(ruta) {                     

            this.$bvModal.msgBoxConfirm(
                '¿Seguro que quiere ' + ruta.etiqueta + ' este expediente?', {
                title: ruta.etiqueta.charAt(0).toUpperCase()+ruta.etiqueta.slice(1) + ' Expediente',                    
                okVariant: 'success',
                okTitle: ruta.etiqueta.charAt(0).toUpperCase()+ruta.etiqueta.slice(1),
                cancelTitle: 'Cancelar',          
                centered: true
            }).then(value => {
                if (value) {
                    axios.post(`${this.api_url}/movimiento/mover`, {
                        idexpediente: this.idexpediente,
                        idruta: ruta.id,
                        idproc_origen: ruta.idproc_origen,
                        idproc_destino: ruta.idproc_destino,
                    })
                    .then((response) => {                        
                        if (!response.data.error) { 
                            this.$store.dispatch('showAlert', { vm:this, 
                                alert:{titulo:'Registro de plan de tesis', contenido:response.data.successMessage, tipo:'success', icono:'done'}})
                        } 
                        else {
                            this.$store.dispatch('showAlert', {vm:this, 
                                alert:{titulo:'Registro de plan de tesis', contenido:response.data.errorMessage, tipo:'danger', icono:'error'}})                        
                        }   
                        this.$emit("reload-parent");
                    })
                }                   
            })    
        },
        resetChild() {
            this.errors = [];
        },      
    }
};
</script>