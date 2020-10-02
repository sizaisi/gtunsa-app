<template>
  <div>
    <b-card>
      <router-link :to="{ name: 'nuevo-tramite' }" class="btn btn-success"
        >Nuevo trámite</router-link
      >
      <hr />
      <b-card v-for="tramite in tramites" :key="tramite.id">
        <b-card-text>
          <b>Código de Expediente:</b> {{ tramite.codExpediente }} <br />
          <b>Grado ó Título:</b> {{ tramite.nombre_grado_titulo }} <br />
          <b>Modalidad de Obtención:</b> {{ tramite.nombre_modalidad }} <br />
          <b>Escuela o Programa:</b> {{ tramite.nesc }} <br />
        </b-card-text>
        <b-button variant="info" @click="verEstados(tramite)"
          >Iniciar / Seguir proceso</b-button
        >
      </b-card>
    </b-card>
  </div>
</template>

<script>
export default {
  name: "inicio",
  data() {
    return {
      api_url: this.$root.api_url,
      tramites: [],
    };
  },
  created() {
    this.getTramites();
  },
  methods: {
    getTramites() {
      axios
        .get(`${this.api_url}/expediente/tramite`)
        .then((response) => {
          this.tramites = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    verEstados(tramite) {
      this.$router.push({
        name: "estados",
        params: {
          idgrado_modalidad: tramite.idgrado_modalidad,
          idexpediente: tramite.idexpediente,
          idgrado_procedimiento_actual: tramite.idgrado_procedimiento_actual,
        },
      });
    },
  },
};
</script>
