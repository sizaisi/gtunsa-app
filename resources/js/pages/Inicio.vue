<template>
    <div>
        <b-card>
            <!--<router-link :to="{ name: 'nuevo-tramite' }" class="btn btn-primary"
                >Nuevo trámite</router-link
            >-->
            <b-button variant="primary" @click="nuevoTramite"
                >Nuevo trámite</b-button
            >
            <hr />
            <b-card
                v-for="tramite in tramites"
                :key="tramite.id"
                :sub-title="`Código de Expediente: ${tramite.codExpediente}`"
                class="mb-3"
            >
                <template v-slot:header>
                    <h6
                        class="mb-0"
                        v-text="
                            `${tramite.nombre_grado_titulo} - ${
                                tramite.nombre_modalidad
                            }`
                        "
                    ></h6>
                </template>
                <b-card-text>
                    Trámite presentado para la obtención de
                    <b>{{ tramite.nombre_grado_titulo }}</b
                    >, por medio de la modalidad de
                    <b>{{ tramite.nombre_modalidad }}</b> del programa de
                    estudios de
                    <b>{{ tramite.nesc }}</b>
                </b-card-text>
                <b-button variant="info" @click="verEstados(tramite)"
                    >Seguimiento de trámite</b-button
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
            tramites: []
        };
    },
    created() {
        this.getTramites();
    },
    methods: {
        nuevoTramite() {
            axios
                .get(`${this.api_url}/graduando`)
                .then(response => {
                    let graduando = response.data;
                    if (!graduando.telefono_movil || !graduando.direccion) {
                        this.$vs.notify({
                            title: "Nuevo trámite",
                            text: "Debe actualizar su información personal",
                            color: "warning",
                            icon: "error",
                            position: "top-center",
                            time: 4000
                        });
                    } else {
                        this.$router.push({
                            name: "nuevo-tramite"
                        });
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getTramites() {
            axios
                .get(`${this.api_url}/expediente/tramite`)
                .then(response => {
                    this.tramites = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        verEstados(tramite) {
            this.$router.push({
                name: "estados",
                params: {
                    idgrado_modalidad: tramite.idgrado_modalidad,
                    idexpediente: tramite.idexpediente,
                    idgrado_procedimiento_actual:
                        tramite.idgrado_procedimiento_actual
                }
            });
        }
    }
};
</script>
