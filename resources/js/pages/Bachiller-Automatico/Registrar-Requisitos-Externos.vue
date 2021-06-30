<template>    
    <div class="text-center">      
        <template v-if="ruta === null">
            <h4>Elija a una acción para el expediente seleccionado</h4>
        </template>                
        <template v-else-if="ruta.accion.nombre === 'Registrar'">
            <subir-archivos
                :idexpediente="idexpediente" 
                :idprocedimiento="idprocedimiento_actual"
                :idruta="ruta.id"                
                :array_opciones="array_tipo_documento"
                :max_docs="max_docs"
                ref="documentos"            
            ></subir-archivos>        
        </template>                     
    </div>    
</template>
<script>
import SubirArchivos from "./../../components/SubirArchivos";

export default {
    name: "Registrar-Requisitos-Externos",   
    props: ["idexpediente", "idprocedimiento_actual"],
    components: {
        SubirArchivos,
    },    
    data() {
        return {
            array_tipo_documento: [
                { value: null, text: 'Tipo Documento', disabled: true },                
                { value: 'Solicitud de Grado Académico', text: 'Solicitud de Grado Académico', disabled: false},
                { value: 'Declaración Jurada', text: 'Declaración Jurada', disabled: false},
                { value: 'Certificado Antecendentes Penales', text: 'Certificado Antecendentes Penales', disabled: false},
                { value: 'Constancia Nivel Intermedio de Idiomas', text: 'Constancia Nivel Intermedio de Idiomas', disabled: false},           
            ],
            max_docs: 4,      
        }
    },
    computed: {
        ruta () {
            return this.$store.state.ruta
        }
    },
    created() {                  
    },
    methods: {        
        
    }
};
</script>
