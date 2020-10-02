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
      <b-button
        @click="registrarProyectoTesis(ruta)"
        :variant="etiquetas[ruta.etiqueta]"
        :key="ruta.etiqueta"
      >
        {{ ruta.etiqueta | capitalize }}
      </b-button>
    </template>
    <b-button type="reset" variant="danger">Cancelar</b-button>
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
      titulo: "",
      file: [],
      etiquetas: config.etiquetas,
    };
  },
  created() {
    this.getRutas();
  },
  methods: {
    getRutas() {
      // rutas del procedimiento
      axios
        .get(`${this.api_url}/movimiento/ruta`, {
          params: {
            idgrado_procedimiento_actual: this.idgrado_procedimiento_actual,
          },
        })
        .then((response) => {
          this.rutas = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    registrarProyectoTesis(ruta) {
      this.file.arrayBuffer().then((buffer) => {
        axios
          .post(`${this.api_url}/graduando/registrar_proyecto`, {
            titulo: this.titulo,
            data: this.getB64Str(buffer),
            extension: this.file.type,
            idexpediente: this.idexpediente,
            idruta: ruta.id,
            idgradproc_origen: ruta.idgradproc_origen,
            idgradproc_destino: ruta.idgradproc_destino,
          })
          .then((response) => {
            thus.$vs.notify({
              title: "Éxito",
              text: "Se registro su proyecto de tesis correctamente",
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
      });
    },
    getB64Str(buffer) {
      var binary = "";
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
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
};
</script>