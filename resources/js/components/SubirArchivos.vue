<template>
  <div>
    <b-form @submit.prevent="registrarArchivo" class="mb-3">
      <b-row>
        <b-col sm="12" md="3" lg="3">
          <b-form-select class="mr-3" v-model="opcion_documento" :options="array_opciones" required></b-form-select>
        </b-col>
        <b-col sm="12" md="7" lg="7">
          <b-form-file
            v-model="file"
            placeholder="Seleccione un archivo..."
            accept=".pdf"
            required
          ></b-form-file>
        </b-col>
        <b-col sm="12" md="2" lg="2">
          <b-button
            type="submit"
            variant="primary"
            title="Subir Archivo"                        
            :disabled="array_documento.length == max_docs"
          >
            <b-icon icon="plus-circle"></b-icon>
          </b-button>
        </b-col>
      </b-row>      
    </b-form>
    <b-table
      :items="array_documento"
      :fields="columnas_documento"
      striped
      bordered
      small
      show-empty
      empty-text="No hay documentos que mostrar."
      primary-key="id"
      :busy="estaOcupado"
    >      
      <template v-slot:cell(acciones)="data">        
        <b-button
          variant="warning"
          size="sm"
          title="Visualizar"          
          class="mr-1"
          @click="mostrarDocumento(data.item.archivo_id)"
        >
          <b-icon icon="eye"></b-icon>
        </b-button>
        <b-button         
          @click="eliminarArchivo(data.item)" 
          variant="danger"
          size="sm"
          title="Eliminar"
        >
          <b-icon icon="trash"></b-icon>
        </b-button>
      </template>
      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Cargando...</strong>
        </div>
      </template>
    </b-table>    
    <mostrar-errores :errors="errors"/>
  </div>
</template>

<script>
import MostrarErrores from "./MostrarErrores";

export default {
  name: "subir-archivos",
  props: ["idexpediente", "idprocedimiento", "idruta", "array_opciones", "max_docs"],
  components: {
    MostrarErrores     
  },
  data() {
    return {
      api_url: this.$root.api_url,  
      array_documento: [],
      columnas_documento: [
        { key: "nombre_asignado", label: "Documento" },
        { key: "nombre_archivo", label: "Archivo adjuntado" },
        { key: "acciones", label: "Acciones", class: "text-center" },
      ],
      opcion_documento: null,                        
      file: null,
      estaOcupado: false,
      modal: 0,
      nombre_documento: "",
      opcion_documento: null,      
      errors: []
    };
  },
  created () {
    this.getArchivo();
  },
  methods: {     
    validarCantidadDocumentos() {        
        let errors = []  

        if (this.array_documento.length < this.max_docs) { 
          errors.push(`Debe registrar ${this.max_docs} documentos para este procedimiento.`)            
        }                      

        this.$store.dispatch('setErrors', errors)
    }, 
    errorTamanioArchivo() {        
        this.errors = []       
        
        if (this.file.type !== 'application/pdf') { //si es mayor a un 1MB
          this.errors.push(`El archivo ${this.file.name} debe ser un documento en formato PDF.`)
        }   
        //else if (this.file.size > 1048576) { //si es mayor a un 1MB
        else if (this.file.size > 500000) { //si es mayor a un 1/2MB
          this.errors.push(`El archivo ${this.file.name} no debe pesar más de 0.5 megabytes.`)
        }                                                

        this.$store.dispatch('setErrors', this.errors)

        if (this.errors.length) {
          return true
        }      

        return false
    },                
    getArchivo() {                  
      axios.get(`${this.api_url}/archivo/get`, {
          params: {
            idexpediente: this.idexpediente,
            idprocedimiento: this.idprocedimiento,                        
          }
        })
        .then(response => {                
          this.array_documento = response.data     
          this.validarCantidadDocumentos()  
          
          for (let i in this.array_documento) {                                    
            this.deshabilitarTipoDocumento(this.array_documento[i].nombre_asignado)
          }             
        });
    },
    registrarArchivo() {                
      if (this.errorTamanioArchivo()) {
        return
      }

      this.estaOcupado = true

      this.file.arrayBuffer().then((buffer) => {
        axios.post(`${this.api_url}/archivo/registrar`, {                    
            idexpediente: this.idexpediente,
            idprocedimiento: this.idprocedimiento,                           
            idruta: this.idruta,                           
            file: this.getB64Str(buffer),
            type: this.file.type,
            nombre_asignado: this.opcion_documento,            
            nombre_archivo: this.file.name,            
          }).then(response => {                    
            this.resetearValores()
            
            if (!response.data.error) {
              this.getArchivo()                            
              this.$store.dispatch('showAlert', 
                {vm:this, alert:{titulo:'Registro de documento', contenido:response.data.successMessage, tipo:'success', posicion:'b-toaster-top-right'}})                  
            } else {
              this.$store.dispatch('showAlert', 
                {vm:this, alert:{titulo:'Registro de documento', contenido:response.data.errorMessage, tipo:'danger', posicion:'b-toaster-top-right'}})                   
            }          
        })
      });
    },
    eliminarArchivo(archivo) {     
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de eliminar el documento?", {
          title: "Eliminar documento",
          okVariant: "danger",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.estaOcupado = true;

            axios.delete(`${this.api_url}/archivo/eliminar`, {
                params: { archivo_id: archivo.archivo_id }
              })
              .then(response => {
                this.getArchivo()
                this.resetearValores()
                this.habilitarTipoDocumento(archivo.nombre_asignado)   

                if (!response.data.error) {                   
                  this.$store.dispatch('showAlert', 
                    {vm:this, alert:{titulo:'Eliminar documento', contenido:response.data.successMessage, tipo:'success', posicion:'b-toaster-top-right'}})                  
                } else {
                  this.$store.dispatch('showAlert', 
                    {vm:this, alert:{titulo:'Eliminar documento', contenido:response.data.errorMessage, tipo:'success', posicion:'b-toaster-top-right'}})                  
                }                                    
              })              
          }
        });
    },   
    habilitarTipoDocumento(tipo) {
      for (let i in this.array_opciones) {
        if (this.array_opciones[i].value == tipo) {
          this.array_opciones[i].disabled = false                     
          break
        }
      }                             
    },
    deshabilitarTipoDocumento(tipo) {
      for (let i in this.array_opciones) {
        if (this.array_opciones[i].value == tipo) {
          this.array_opciones[i].disabled = true                     
          break
        }
      }                             
    },     
    getB64Str(buffer) {
      let binary = '';
      let bytes = new Uint8Array(buffer);
      let len = bytes.byteLength;
      
      for (let i = 0; i < len; i++) {
          binary += String.fromCharCode(bytes[i]);
      }

      return window.btoa(binary);
    },     
    resetearValores() {
      this.file = null;      
      this.opcion_documento = null;
      this.estaOcupado = false;      
    },     
    mostrarDocumento(archivo_id) {
      window.open(`${this.api_url}/archivo/mostrar/${archivo_id}`,'_blank');     
    } 
  }  
};
</script>
<style>
.custom-file-input:lang(es) ~ .custom-file-label::after {
  content: "Elegir";
}
</style>