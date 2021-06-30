<template>
  <div>
    <b-card no-body class="overflow-hidden p-1" style="max-width: 1000px; border-radius: 10px">
      <b-row no-gutters>
        <b-col md="0" v-if="hideDescription">
          <b-card-img :src="image" class="rounded-0"></b-card-img>
        </b-col>
        <b-col md="0" v-else-if="color == 'actual'">
          <b-card-img
            :src="image"
            class="rounded-0"            
          ></b-card-img>
        </b-col>
        <b-col md="0" v-else>
          <b-card-img
            :src="image"
            class="rounded-0"
            @click="collapseClick"
          ></b-card-img>
        </b-col>
        <b-col class="ml-1">
          <b-card no-body border-variant="light">
            <template v-slot:header>
              <h5 class="mb-0">{{ title }}</h5>
            </template>
            <b-collapse id="description" v-model="showDescription_">
              <b-card-body>                
                <template v-if="rol_id == 3 && accion == 'actual'">
                  <b-card-sub-title class="mb-2" v-html="desc"></b-card-sub-title>
                  <br />
                  <div class="row justify-content-center">
                    <div class="col col-lg-3"> 
                        <b-form-select    
                            v-model="ruta"
                            :options="rutas"
                            @change="actualizarRutaStore()"                                                   
                        >
                            <template v-slot:first>
                                <option :value="null" disabled>
                                    -- Elija acción a realizar --
                                </option>
                            </template>
                        </b-form-select>  
                    </div>
                  </div> 
                  <hr> 
                  <component
                    :is="componente"
                    :idexpediente="idexpediente"           
                    :idprocedimiento_actual="idprocedimiento_actual"                             
                  />
                  <hr>
                  <derivar-expediente :expediente_id="idexpediente" @reload-parent="actualizarEstados"/>
                </template>
                <template v-else>
                  <div class="container">
                    <div class="row">
                      <div class="text-lg-right text-md-right text-sm-left py-2 col-lg-3 col-md-4 col-sm-12 border bg-light">
                        <b>Responsable:</b>
                      </div>
                      <div v-if="tipo_rol == 'asesor' || tipo_rol == 'jurado'" class="py-2 col-lg-9 col-md-8 col-sm-12 border">
                        {{ tipo_rol.toUpperCase() }}
                      </div>
                      <div v-else class="py-2 col-lg-9 col-md-8 col-sm-12 border">{{ rol }}</div>
                    </div>
                    <div class="row">
                      <div class="text-lg-right text-md-right text-sm-left py-2 col-lg-3 col-md-4 col-sm-12 border bg-light">
                        <b>Acción realizada:</b>
                      </div>
                       <div v-if="fecha != ''" class="py-2 col-lg-9 col-md-8 col-sm-12 border">{{ accion }}</div>
                       <div v-else class="py-2 col-lg-9 col-md-8 col-sm-12 border"></div>
                    </div>
                    <div class="row">
                      <div class="text-lg-right text-md-right text-sm-left py-2 col-lg-3 col-md-4 col-sm-12 border bg-light">
                        <b>Fecha - Hora:</b>
                      </div>
                      <div v-if="fecha != ''" class="py-2 col-lg-9 col-md-8 col-sm-12 border">{{ fecha }}</div>
                      <div v-else class="py-2 col-lg-9 col-md-8 col-sm-12 border"></div>
                    </div>
                  </div>
                </template>                
              </b-card-body>
            </b-collapse>
          </b-card>
        </b-col>
      </b-row>
    </b-card>
    <b-row v-if="tail">
      <b-col>
        <div :style="'height: 30px; margin-left: 27px; border-left: 6px solid #c0c0c0'"></div>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import Registrar_Requisitos_Externos from "./Registrar-Requisitos-Externos";
import DerivarExpediente from "./../../components/DerivarExpediente";

export default {
  name: "bachiller-automatico.procedimiento",
  props: ["idexpediente", "title", "idprocedimiento_actual", "number", "color",
          "accion", "click", "showDescription", "hideDescription", "componente", 
          "rol_id", "rol", "tipo_rol", "desc", "fecha", "tail"],
  components: {      
    Registrar_Requisitos_Externos,
    DerivarExpediente
  },  
  data() {
    return {
      api_url: this.$root.api_url,
      image: "",
      showDescription_: false,
      ruta: null,
      rutas: [],     
      titulo: "",
      file: [],
    };
  },
  created() {    
    if (this.rol_id == 3 && this.accion == 'actual') {
      this.getRutas()
    }   
    if (this.showDescription) {
      this.showDescription_ = true
    }   
    if (this.number == "+") {
      this.image = `https://ui-avatars.com/api/?background=${this.color}&color=fff&name=%2B&rounded=true&size=50`;
    } 
    else if (this.number == "..") {
      this.image = `https://ui-avatars.com/api/?background=${this.color}&color=fff&name=%C2%B7 %C2%B7&rounded=true&size=50`
    } 
    else if (this.number == ".") {
      this.image = `https://ui-avatars.com/api/?background=${this.color}&color=fff&name=%E2%80%A2&rounded=true&size=50`
    } 
    else {
      this.image = `https://ui-avatars.com/api/?background=${this.color}&color=fff&name=&rounded=true&size=50`
    }
  },
  methods: {    
    actualizarEstados() {
      this.$emit("reload-parent");
    },
    collapseClick() {
      if (!this.click) {
        this.showDescription_ = !this.showDescription_;

        if (this.showDescription_) {
          this.image = `https://ui-avatars.com/api/?background=${this.color}&color=fff&name=%E2%80%93&rounded=true&size=50`
        }
        else {
          this.image = `https://ui-avatars.com/api/?background=${this.color}&color=fff&name=%2B&rounded=true&size=50`;
        }        
      } else {
        this.$emit(this.click);
      }
    },
    getRutas() {
      axios.get(`${this.api_url}/procedimiento/rutas`, {
            params: {
                idprocedimiento_actual: this.idprocedimiento_actual
            }
        })
        .then(response => {                                                                          
          this.rutas = response.data
        })
        .catch(function(error) {
            console.log(error)
        })
    }, 
    actualizarRutaStore() {            
      this.$store.dispatch('setRuta', this.ruta)
    } 
  },  
};
</script>