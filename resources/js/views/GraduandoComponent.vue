<template>
    <div>
        <b-card no-body>
            <div class="text-center">
                <img
                    v-if="cui_anio !== null"
                    class="avatar border-gray"
                    :src="
                        `http://190.119.145.150:8023/fotos/${cui_anio}/${
                            graduando.cui
                        }.jpg`
                    "
                />
            </div>
            <b-card-body title="Información Personal">
                <b-card-text>
                    <label> <b>CUI:</b> {{ graduando.cui }} </label> <br />
                    <label> <b>Apellidos:</b> {{ graduando.apellidos }} </label>
                    <br />
                    <label> <b>Nombres:</b> {{ graduando.nombres }} </label>
                    <br />
                    <label> <b>DNI:</b> {{ graduando.dni }} </label> <br />
                    <!--<label> <b>E-mail:</b> {{ graduando.email }} </label> <br>-->
                    <div v-if="!edit_flag">
                        <label>
                            <b>Teléfono fijo:</b> {{ graduando.telefono_fijo }}
                        </label>
                        <br />
                        <label>
                            <b>Teléfono móvil:</b>
                            {{ graduando.telefono_movil }}
                        </label>
                        <br />
                        <label>
                            <b>Dirección:</b> {{ graduando.direccion }}
                        </label>
                        <br />
                        <b-button variant="warning" @click="changeEdit"
                            ><b-icon
                                icon="pencil-square"
                                aria-hidden="true"
                            ></b-icon>
                            Editar</b-button
                        >
                    </div>
                    <div v-else>
                        <b-form @submit.prevent="actualizarDatos">
                            <b-form-group
                                id="input-group-tel-fijo"
                                label="Teléfono fijo:"
                                label-for="input-tel-fijo"
                            >
                                <b-form-input
                                    id="input-tel-fijo"
                                    v-model="graduando.telefono_fijo"
                                ></b-form-input>
                                <span
                                    v-if="errors.telefono_fijo"
                                    class="text-danger"
                                    >{{ errors.telefono_fijo[0] }}</span
                                >
                            </b-form-group>
                            <b-form-group
                                id="input-group-tel-movil"
                                label="Teléfono móvil:"
                                label-for="input-tel-movil"
                            >
                                <b-form-input
                                    id="input-tel-movil"
                                    v-model="graduando.telefono_movil"
                                    required
                                ></b-form-input>
                                <span
                                    v-if="errors.telefono_movil"
                                    class="text-danger"
                                    >{{ errors.telefono_movil[0] }}</span
                                >
                            </b-form-group>
                            <b-form-group
                                id="input-group-direccion"
                                label="Dirección:"
                                label-for="input-direccion"
                            >
                                <b-form-input
                                    id="input-direccion"
                                    v-model="graduando.direccion"
                                    required
                                ></b-form-input>
                                <span
                                    v-if="errors.direccion"
                                    class="text-danger"
                                    >{{ errors.direccion[0] }}</span
                                >
                            </b-form-group>
                            <b-button type="submit" variant="success"
                                >Actualizar</b-button
                            >
                            <b-button @click="changeEdit" variant="danger"
                                >Cancelar</b-button
                            >
                        </b-form>
                    </div>
                </b-card-text>
            </b-card-body>
        </b-card>
    </div>
</template>

<script>
export default {
    name: "graduando-information",
    data() {
        return {
            api_url: this.$root.api_url,
            graduando: {},
            cui_anio: null,
            tmp_graduando: {},
            edit_flag: false,
            errors: []
        };
    },
    created() {
        this.getGraduando();
    },
    methods: {
        getGraduando() {
            axios
                .get(`${this.api_url}/graduando`)
                .then(response => {
                    this.graduando = response.data;
                    this.cui_anio = this.graduando.cui.substr(0, 4);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        actualizarDatos() {
            this.errors = [];

            axios
                .put(
                    `${this.api_url}/graduando/actualizar/${this.graduando.id}`,
                    {
                        telefono_fijo: this.graduando.telefono_fijo,
                        telefono_movil: this.graduando.telefono_movil,
                        direccion: this.graduando.direccion
                    }
                )
                .then(response => {
                    this.edit_flag = false;
                    this.$vs.notify({
                        title: "Actualización de datos",
                        text: "Se actualizó su información personal",
                        color: "success",
                        icon: "done",
                        position: "top-left",
                        time: 4000
                    });
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.$vs.notify({
                            title: "Actualización de datos",
                            text:
                                "No se pudo actualizar su informacion personal",
                            color: "danger",
                            icon: "error",
                            position: "top-left",
                            time: 4000
                        });
                    }
                });
        },
        changeEdit() {
            if (!this.edit_flag) {
                this.edit_flag = true;
                this.tmp_graduando = Object.assign({}, this.graduando);
            } else {
                this.edit_flag = false;
                this.graduando = Object.assign({}, this.tmp_graduando);
            }
        }
    }
};
</script>
