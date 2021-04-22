import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    idtramite: null,
    idexpediente: null, 
    errors: []   
  },
  mutations: {
    SET_IDTRAMITE(state, id) {
      state.idtramite = id
    },
    SET_IDEXPEDIENTE(state, id) {
      state.idexpediente = id
    },
    SET_ERRORS(state, errors) {
      state.errors = errors
    }    
  },
  actions: {
    setIdTramite({commit}, id) {
      commit('SET_IDTRAMITE', id)
    },
    setIdExpediente({commit}, id) {
      commit('SET_IDEXPEDIENTE', id)
    },   
    setErrors({commit}, errors) {
      commit('SET_ERRORS', errors)
    },   
    showAlert({ commit }, {vm, alert}) {                
      vm.$vs.notify({
        title: alert.titulo,
        text: alert.contenido,
        color: alert.tipo,
        icon: alert.icono,
        position: 'bottom-right',
        time: 4000
      })         
    }
  },
  getters: {
    getErrors: state => {
      return state.errors;
    },        
  },
  modules: {
  }
})
