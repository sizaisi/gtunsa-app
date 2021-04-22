<template>
  <div>
    <b-card
      no-body
      class="overflow-hidden p-1"
      style="max-width: 1000px; border-radius: 10px"
    >
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
                <b-card-sub-title class="mb-2">{{ desc }}</b-card-sub-title>
                <br />
                <template v-if="rol == 'Graduando' && accion == 'actual'">
                  <component
                    :is="componente"
                    :idexpediente="idexpediente"
                    :idprocedimiento_actual="idprocedimiento_actual"
                    @reload-parent="actualizarEstados"
                  />
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

//bachiller automatico
import b_a_registrar_requisitos_externos from "../procedimientos/bachiller_automatico/registrar_requisitos_externos.vue";

//titulo profesional sustentacion tesis
import tp_st_registrar_proyecto_grado from "../procedimientos/titulo_profesional_sustentacion_tesis/registrar_proyecto_grado.vue";
import tp_st_evaluar_asesor_asignado from "../procedimientos/titulo_profesional_sustentacion_tesis/evaluar_asesor_asignado.vue";
import tp_st_corregir_observaciones_jurado from "../procedimientos/titulo_profesional_sustentacion_tesis/corregir_observaciones_jurado.vue";
import tp_st_registrar_requisitos_externos from "../procedimientos/titulo_profesional_sustentacion_tesis/registrar_requisitos_externos.vue";
import tp_st_corregir_obs_post_sustentacion from "../procedimientos/titulo_profesional_sustentacion_tesis/corregir_obs_post_sustentacion.vue";

export default {
  name: "estado-component",
  props: ["idexpediente", "title", "idprocedimiento_actual", "number", "color",
          "accion", "click", "showDescription", "hideDescription", "componente", 
          "rol", "tipo_rol", "desc", "fecha", "tail"],
  components: {
    tp_st_registrar_proyecto_grado,
    tp_st_evaluar_asesor_asignado,
    tp_st_corregir_observaciones_jurado,
    tp_st_registrar_requisitos_externos,
    tp_st_corregir_obs_post_sustentacion,    
    b_a_registrar_requisitos_externos
  },  
  data() {
    return {
      image: "",
      showDescription_: false,
      rutas: [],      
      titulo: "",
      file: [],
    };
  },
  created() {    
    if (this.showDescription) {
      this.showDescription_ = true;
    }   

    if (this.number == "+") {
      this.image =
        "https://ui-avatars.com/api/?background=" +
        this.color +
        "&color=fff&name=" +
        "%2B" +
        "&rounded=true&size=50";
    } 
    else if (this.number == "..") {
      this.image =
        "https://ui-avatars.com/api/?background=" +
        this.color +
        "&color=fff&name=" +
        "%C2%B7 %C2%B7" +        
        "&rounded=true&size=50";  
    } 
    else if (this.number == ".") {
      this.image =
        "https://ui-avatars.com/api/?background=" +
        this.color +
        "&color=fff&name=" +
        "%E2%80%A2" +
        "&rounded=true&size=50";
    } 
    else {
      this.image =
        "https://ui-avatars.com/api/?background=" +
        this.color +
        "&color=fff&name=" +
        "" +
        "&rounded=true&size=50";
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
          this.image =
          "https://ui-avatars.com/api/?background=" +
          this.color +
          "&color=fff&name=" +
          "%E2%80%93" +
          "&rounded=true&size=50";
        }
        else {
          this.image =
          "https://ui-avatars.com/api/?background=" +
          this.color +
          "&color=fff&name=" +
          "%2B" +
          "&rounded=true&size=50";
        }
        
      } else {
        this.$emit(this.click);
      }
    },
  },  
};
</script>
