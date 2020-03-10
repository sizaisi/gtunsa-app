<template>
    <div>
    <b-card no-body class="overflow-hidden p-1" style="max-width: 1000px; border-radius: 10px;">
        <b-row no-gutters>
            <b-col md="0" v-if="hideDescription">
                <b-card-img :src="image" class="rounded-0"></b-card-img>
            </b-col>
            <b-col md="0" v-else>
                <b-card-img :src="image" class="rounded-0" @click="collapseClick"></b-card-img>
            </b-col>
            <b-col class="ml-1">
                <b-card
                  no-body
                  border-variant="light"
                >
                <template v-slot:header>
                  <h5 class="mb-0">{{ title }}</h5>
                </template>
                  <b-collapse id="description" v-model="showDescription_">
                    <b-card-body>
                      <b-card-sub-title class="mb-2">{{ desc }}</b-card-sub-title>
                      <template v-if="idrol_area != 1 || color != 'actual'">
                        <div class="container">
                          <div class="row">
                            <div class="text-lg-right text-md-right text-sm-left py-2 col-lg-3 col-md-4 col-sm-12 border bg-light"><b>Área / Responsable:</b></div>
                            <div v-if="tipo_rol == 'asesor' || tipo_rol == 'jurado'" class="py-2 col-lg-9 col-md-8 col-sm-12 border">{{ tipo_rol.toUpperCase() }}</div>
                            <div v-else class="py-2 col-lg-9 col-md-8 col-sm-12 border">{{ rol_area }}</div>
                          </div>
                          <div class="row">
                            <div class="text-lg-right text-md-right text-sm-left py-2 col-lg-3 col-md-4 col-sm-12 border bg-light"><b>Estado:</b></div>
                            <div class="py-2 col-lg-9 col-md-8 col-sm-12 border"><b-badge :variant="etiquetas[color]">{{ color | estado }}</b-badge></div>
                          </div>
                          <div class="row" v-if="fecha != ''">
                            <div class="text-lg-right text-md-right text-sm-left py-2 col-lg-3 col-md-4 col-sm-12 border bg-light"><b>Fecha - Hora:</b></div>
                            <div class="py-2 col-lg-9 col-md-8 col-sm-12 border">{{ fecha }}</div>
                          </div>
                        </div>
                      </template>
                      <template v-else><!-- es proc actual y procedimiento del graduando -->
                        <component :is="url_form" :ruta="ruta" :idexpediente="idexpediente" :idgrado_procedimiento_actual="idgrado_procedimiento_actual"/>
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

import config from '../config'

import tp_st_registrar_proyecto_grado from '../procedimientos/tp_st_registrar_proyecto_grado.vue'
import tp_st_corregir_observacion_jurado from '../procedimientos/tp_st_corregir_observacion_jurado.vue'
import tp_st_corregir_obs_post_sustentacion from '../procedimientos/tp_st_corregir_obs_post_sustentacion.vue'

export default {
    name: 'estado-component',
    components: {
      tp_st_registrar_proyecto_grado,
      tp_st_corregir_observacion_jurado,
      tp_st_corregir_obs_post_sustentacion
    },
    props : ['ruta', 'idexpediente', 'title',
             'idgrado_procedimiento_actual', 'number',
             'color', 'click', 'showDescription',
             'hideDescription', 'url_form', 'idrol_area',
             'rol_area', 'tipo_rol', 'desc', 'fecha', 'tail'],
    data(){
        return{
            image: '',
            showDescription_: false,
            rutas: [],
            color_mostrar : '',
            titulo : '',
            file: [],
            etiquetas : config.etiquetas
        }
    },
    created() {
        if(this.showDescription) {
          this.showDescription_ = true;
        }

        this.getColor();

        if (this.number != '.') {
          this.image = "https://ui-avatars.com/api/?background="
                          + this.color_mostrar + "&color=fff&name="
                          + this.number + "&rounded=true&size=50";
        }
        else {
          this.image = "https://ui-avatars.com/api/?background="
                          + this.color_mostrar + "&color=fff&name="
                          + '%E2%80%A2' + "&rounded=true&size=50";
        }
    },
    methods:{
        getColor(){
            if(!this.color) this.color_mostrar = '0D8ABC';
            else if(this.color == 'success') this.color_mostrar = '4BB543';
            else if(this.color == 'aceptar') this.color_mostrar = '4BB543';
            else if(this.color == 'enviar') this.color_mostrar = '4BB543';
            else if(this.color == 'derivar') this.color_mostrar = '4BB543';
            else if(this.color == 'aprobar') this.color_mostrar = '4BB543';
            else if(this.color == 'gray') this.color_mostrar = '7D7D7D';
            else if(this.color == 'light_gray') this.color_mostrar = 'CCC';
            else if(this.color == 'denegar') this.color_mostrar = 'CC0202';
            else if(this.color == 'rechazar') this.color_mostrar = 'FFC107';
            else if(this.color == 'observar') this.color_mostrar = 'FFC107';
            else if(this.color == 'actual') this.color_mostrar = '0D8ABC';
        },
        collapseClick(){
            if(!this.click) {
              this.showDescription_ = !this.showDescription_;
              this.image = "https://ui-avatars.com/api/?background="
                              + this.color_mostrar + "&color=fff&name="
                              + this.number + "&rounded=true&size=50";
            }
            else {
              this.$emit(this.click);
            }
        },
    },
    filters: {
        estado: function (value) {
          switch (value) {
            case 'actual':
              return 'EN CALIFICACIÓN'
              break;
            case 'enviar':
              return 'REGISTRADO'
              break;
            case 'aprobar':
              return 'APROBADO'
              break;
            case 'aceptar':
              return 'ACEPTADO'
              break;
            case 'derivar':
              return 'DERIVADO'
              break;
            case 'observar':
              return 'OBSERVADO'
              break;
            case 'devolver':
              return 'DEVUELTO'
              break;
            case 'denegar':
              return 'DENEGADO'
              break;
            case 'rechazar':
              return 'RECHAZADO'
              break;
          }
        }
    },
}
</script>
