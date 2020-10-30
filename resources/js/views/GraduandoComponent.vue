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
            <b-card-body>
                <b-card-text>
                    <label> <b>CUI:</b> {{ graduando.cui }} </label> <br />                    
                    <label> <b>E-mail:</b> {{ graduando.email }} </label>                                    
                    <label> <b>Nombres:</b> {{ graduando.nombres }} </label>                             
                    <br />
                    <label> <b>Apellidos:</b> {{ graduando.apellidos }} </label>                                     
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
    }
};
</script>
