<template>    
     <div>
        <subir-archivos
            :idexpediente="idexpediente" 
            :idprocedimiento="idprocedimiento_actual"
            :idruta="ruta.id"                
            :array_opciones="array_tipo_documento"
            :max_docs="max_docs"
            ref="documentos"            
        ></subir-archivos>        
        <mover-expediente 
            :ruta="ruta" 
            :idexpediente="idexpediente"
            @reload-parent="actualizarEstados"
        />
    </div>            
</template>
<script>
import SubirArchivos from "./../../components/SubirArchivos";
import MoverExpediente from "./../../components/MoverExpediente";

export default {
    name: "b_a_registrar_requisitos_externos",
    components: {        
        SubirArchivos,
        MoverExpediente
    },
    props: ["idexpediente", "idprocedimiento_actual"],
    data() {
        return {
            api_url: this.$root.api_url,        
            ruta: {},    
            rutas: [],                        
            array_tipo_documento: [
                { value: null, text: 'Tipo Documento', disabled: true },                
                { value: 'DNI Anverso', text: 'DNI Anverso', disabled: false},
                { value: 'DNI Reverso', text: 'DNI Reverso', disabled: false},
                { value: 'Certificado Antecendentes Penales', text: 'Certificado Antecendentes Penales', disabled: false},
                { value: 'Constancia Nivel Intermedio de Idiomas', text: 'Constancia Nivel Intermedio de Idiomas', disabled: false},           
            ],
            max_docs: 4,            
        };
    },
    created() {        
        this.getRutas();        
    },
    methods: {
        getRutas() {
            axios.get(`${this.api_url}/movimiento/ruta`, {
                    params: {
                        idprocedimiento_actual: this.idprocedimiento_actual
                    }
                })
                .then(response => {                
                    this.rutas = response.data;                    
                    
                    if (this.rutas.length == 1) {                        
                        this.ruta = this.rutas[0]
                    }                   
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        actualizarEstados() {
            this.$emit("reload-parent");
        },                  
    },    
};
</script>