<template>
  <div>
    <b-list-group>
        <b-list-group-item v-for="(observacion, index) in observaciones" :key="index">
            {{ observacion.descripcion }}
        </b-list-group-item>        
    </b-list-group>
  </div>
</template>
<script>
export default {
  name: "observaciones",
  props: ["idexpediente"],
  data() {
    return {
      api_url: this.$root.api_url, 
      observaciones: []     
    };
  },
  created() {
      this.getObservaciones()
  },
  methods: {
    getObservaciones() {                  
        axios.get(`${this.api_url}/observaciones/get`, {
                params: {
                    idexpediente: this.idexpediente,                
                }
            })
            .then(response => {                
                this.observaciones = response.data                       
            });
    },
  }    
}
</script>