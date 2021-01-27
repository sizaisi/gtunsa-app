<template>
  <div>
    <div v-if="errors.length" class="alert alert-danger" role="alert">
        <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
    </div> 
  </div>
</template>
<script>
export default {
  name: "mostrar-errores",
  props: ["errors"],      
}
</script>
<style scoped>
    ul {
        margin-bottom: 0px;    
    }         
</style>