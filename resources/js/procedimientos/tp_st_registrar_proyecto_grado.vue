<template>
  <div>
    <b-form>
        <b-form-group
            id="input-group-1"
            label="Título:"
            label-for="input-1"
            description="Por favor el título debe coincidir con el título del archivo pdf."
        >
            <b-form-input
                v-model="titulo"
                id="input-1"
                required
                placeholder="Ingrese el título de plan de tesis"
            ></b-form-input>
        </b-form-group>

        <b-form-group
            id="file-group"
            label="Plan de tesis:"
            label-for="file-input"
        >
          <b-form-file
            v-model="file"
            :state="Boolean(file)"
            placeholder="Choose a file or drop it here..."
            drop-placeholder="Drop file here..."
          ></b-form-file>
        </b-form-group>
    </b-form>
    <template v-for="ruta in rutas">
       <b-button @click="registrarProyectoTesis(ruta)" :variant="etiquetas[ruta.etiqueta]" :key="ruta.etiqueta">
          {{ ruta.etiqueta | capitalize }}
       </b-button>
    </template>
    <b-button type="reset" variant="danger">Cancelar</b-button>
  </div>
</template>
<script>

import config from '../config'

export default {
  name: 'tp_st_registrar_proyecto_grado',
  props : ['ruta', 'idexpediente', 'idgrado_procedimiento_actual'],
  data(){
      return{
          rutas: [],
          titulo : '',
          file: [],
          etiquetas : config.etiquetas
      }
  },
  methods:{
      getRutas() { // rutas del procedimiento
          let me = this

          axios.get(`${this.ruta}/movimiento/ruta`, {
              params: {
                  'idgrado_procedimiento_actual': this.idgrado_procedimiento_actual
              }
          })
          .then(function (response) {
              me.rutas = response.data
          })
          .catch(function (error) {
              console.log(error)
          })
      },
      registrarProyectoTesis(ruta) {
          let me = this

          this.file.arrayBuffer().then(function (buffer){
            axios.post(`${me.ruta}/graduando/registrar_proyecto`, {
                  'titulo': me.titulo,
                  'data': me.getB64Str(buffer),
                  'extension': me.file.type,
                  'idexpediente': me.idexpediente,
                  'idruta': ruta.id,
                  'idgradproc_origen': ruta.idgradproc_origen,
                  'idgradproc_destino': ruta.idgradproc_destino,
            }).then(function (response) {
                me.$vs.notify({
                    title: 'Éxito',
                    text: 'Se registro su proyecto de tesis correctamente',
                    color: 'success',
                    icon: 'done',
                    position: 'top-center',
                    time: 4000
                })
            }).catch(function (error) {
                console.log(error)
                if (error.response.status==422) {
                    //me.errors = error.response.data.errors;
                }
                else {
                    me.$vs.notify({
                        title: 'Error',
                        text: 'No se pudo registrar su proyecto de tesis',
                        color: 'danger',
                        icon: 'error',
                        position: 'top-left',
                        time: 4000
                    })
                }
            })
          });
      },
      getB64Str(buffer) {
        var binary = '';
        var bytes = new Uint8Array(buffer);
        var len = bytes.byteLength;
        for (var i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
      },
  },
  filters: {
      capitalize: function (value) {
         if (!value) return ''
         value = value.toString()
         return value.charAt(0).toUpperCase() + value.slice(1)
      },
  },
  mounted() {
      this.getRutas()
  }
}
</script>
<style scoped>

</style>
