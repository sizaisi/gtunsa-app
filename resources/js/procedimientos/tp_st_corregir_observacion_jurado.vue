<template>
  <div>
    <h3>Enviar correciones para el jurado</h3>
    <template v-for="(ruta, index) in rutas">
      <b-button
        @click="mover(ruta)"
        :variant="etiquetas[ruta.etiqueta]"
        :key="index"
      >
        {{ ruta.etiqueta | capitalize }}
      </b-button>
    </template>
  </div>
</template>
<script>
import config from "../config";

export default {
  name: "tp_st_registrar_proyecto_grado",
  props: ["idexpediente", "idgrado_procedimiento_actual"],
  data() {
    return {
      api_url: this.$root.api_url,
      rutas: [],
      etiquetas: config.etiquetas,
    };
  },
  created() {
    this.getRutas();
  },
  methods: {
    getRutas() {
      axios
        .get(`${this.api_url}/movimiento/ruta`, {
          params: {
            idgrado_procedimiento_actual: this.idgrado_procedimiento_actual,
          },
        })
        .then((response) => {
          this.rutas = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    mover(ruta) {
      axios
        .post(`${this.api_url}/graduando/mover`, {
          idexpediente: this.idexpediente,
          idruta: ruta.id,
          idgradproc_origen: ruta.idgradproc_origen /*verificar jeiken*/,
          idgradproc_destino: ruta.idgradproc_destino,
        })
        .then((response) => {
          this.$vs.notify({
            title: "Éxito",
            text: "Se mensaje se ha enviado correctamente",
            color: "success",
            icon: "done",
            position: "top-center",
            time: 4000,
          });
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 422) {
            //me.errors = error.response.data.errors;
          } else {
            this.$vs.notify({
              title: "Error",
              text: "No se pudo registrar su proyecto de tesis",
              color: "danger",
              icon: "error",
              position: "top-left",
              time: 4000,
            });
          }
        });
    },
  },
  filters: {
    capitalize: function (value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
};
</script>