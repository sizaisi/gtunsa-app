<template>
  <div>
    <h3>Enviar correciones para el jurado</h3>
    <template v-for="ruta in rutas">
       <b-button @click="mover(ruta)" :variant="etiquetas[ruta.etiqueta]">
          {{ ruta.etiqueta | capitalize }}
       </b-button>
    </template>
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
      mover(ruta) {
          let me = this
                    
          axios.post(`${me.ruta}/graduando/mover`, {
                'idexpediente': me.idexpediente,
                'idruta': ruta.id,
                'idgradproc_origen': ruta.idgradproc_origen, /*verificar jeiken*/
                'idgradproc_destino': ruta.idgradproc_destino,
          }).then(function (response) {
              me.$vs.notify({
                  title: 'Éxito',
                  text: 'Se mensaje se ha enviado correctamente',
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
