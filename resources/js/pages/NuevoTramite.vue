<template>
  <div>
    <b-card header="Nuevo Trámite" header-tag="header">
      <div class="d-flex justify-content-center mb-3">
        <b-button variant="outline-info" :to="{ name: 'inicio' }"
          >Volver</b-button
        >
      </div>
      <div class="d-flex justify-content-center">
        <b-form-checkbox
          id="checkbox-1"
          v-model="datos_correctos"
          name="checkbox-1"
          value="acepto"
          unchecked-value="no_acepto"
          description="We'll never share your email with anyone else."
        >
          <b>Declaro que los datos de información personal son correctos</b>
        </b-form-checkbox>
      </div>
      <div class="d-flex justify-content-center">
        <small
          >En caso su información no es correcta comunicarse con DUFA al correo
          dufa@unsa.edu.pe</small
        >
      </div>
      <template v-if="datos_correctos == 'acepto'">
        <hr class="bg-light" />
        <b-form>
          <b-form-group
            id="input-group-3"
            label="Escuela o Programa:"
            label-for="input-3"
          >
            <b-form-select
              id="input-3"
              v-model="escuela"
              :options="escuelas"
              required
            >
              <template v-slot:first>
                <option :value="null" disabled>
                  -- Por favor seleccione una opción --
                </option>
              </template>
            </b-form-select>
          </b-form-group>
          <b-form-group
            v-show="grado_titulos.length > 0"
            id="input-group-3"
            label="Grado ó Título:"
            label-for="input-3"
          >
            <b-form-select
              id="input-3"
              v-model="idgrado_titulo"
              :options="grado_titulos"
              required
            >
              <template v-slot:first>
                <option :value="null" disabled>
                  -- Por favor seleccione una opción --
                </option>
              </template>
            </b-form-select>
          </b-form-group>
          <b-form-group
            v-show="grado_modalidades.length > 0"
            id="input-group-3"
            label="Modalidad de obtención:"
            label-for="input-3"
          >
            <b-form-select
              id="input-3"
              v-model="idgrado_modalidad"
              :options="grado_modalidades"
              required
            >
              <template v-slot:first>
                <option :value="null" disabled>
                  -- Por favor seleccione una opción --
                </option>
              </template>
            </b-form-select>
          </b-form-group>
          <b-button
            v-if="idgrado_modalidad !== null"
            variant="primary"
            @click="registrarTramite"
            >Registrar trámite</b-button
          >
        </b-form>
      </template>
    </b-card>
  </div>
</template>
<script>
export default {
  name: "nuevo-tramite",
  data() {
    return {
      api_url: this.$root.api_url,
      show: 3,
      escuela: null,
      escuelas: [],
      idgrado_titulo: null,
      grado_titulos: [],
      idgrado_modalidad: null,
      grado_modalidades: [],
      datos_correctos: "no_acepto",
    };
  },
  watch: {
    escuela: function (val) {
      this.idgrado_titulo = null;

      axios
        .get(`${this.api_url}/GradoModalidad/grado_titulo`, {
          params: {
            nive: val.nive,
            codigo: val.nues.substr(0, 1),
          },
        })
        .then((response) => {
          this.grado_titulos = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    idgrado_titulo: function (val) {
      this.idgrado_modalidad = null;

      axios
        .get(`${this.api_url}/GradoModalidad/modalidad_obtencion`, {
          params: {
            idgrado_titulo: val,
          },
        })
        .then((response) => {
          this.grado_modalidades = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  created() {
    this.getEscuelas();
  },
  methods: {
    getEscuelas() {
      axios
        .get(`${this.api_url}/escuela`)
        .then((response) => {
          this.escuelas = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    registrarTramite() {
      //validar que cui nues y espe no tenga registro en proceso en gt_expediente
      //si hay por lo menos un registro no mostrar mensaje de error con respectivo mensaje
      //warning ud tiene un expediente en proceso para la escuela o programa seleccionado

      axios
        .get(`${this.api_url}/expediente/registrar/`, {
          params: {
            idgrado_modalidad: this.idgrado_modalidad,
            nues: this.escuela.nues,
            espe: this.escuela.espe,
          },
        })
        .then((response) => {
          this.$vs.notify({
            title: "Éxito",
            text: "¡Su trámite fue registrado con éxito!",
            color: "success",
            icon: "done",
            position: "top-center",
            time: 4000,
          });

          this.$router.push({ name: "inicio" });
        })
        .catch((error) => {
          console.log(error);
          this.$vs.notify({
            title: "Error",
            text: "No se pudo registrar su trámite",
            color: "danger",
            icon: "error",
            position: "top-center",
            time: 4000,
          });
        });
    },
  },
};
</script>
