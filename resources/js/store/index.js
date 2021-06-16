import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    graduando: null,
    idtramite: null,
    idexpediente: null, 
    ruta: null,
    errors: []   
  },
  mutations: {
    SET_GRADUANDO(state, graduando) {
      state.graduando = graduando
    },
    SET_IDTRAMITE(state, id) {
      state.idtramite = id
    },
    SET_IDEXPEDIENTE(state, id) {
      state.idexpediente = id
    },
    SET_ERRORS(state, errors) {
      state.errors = errors
    },    
    SET_RUTA(state, ruta) {
      state.ruta = ruta
    }
  },
  actions: {
    setGraduando({commit}, graduando) {
      commit('SET_GRADUANDO', graduando)
    },
    setIdTramite({commit}, id) {
      commit('SET_IDTRAMITE', id)
    },
    setIdExpediente({commit}, id) {
      commit('SET_IDEXPEDIENTE', id)
    },  
    setRuta({commit}, ruta) {
      commit('SET_RUTA', ruta)
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
    getGraduando: state => {
      return state.graduando;
    },    
    getRuta: state => {
      return state.ruta;
    },
    getErrors: state => {
      return state.errors;
    },        
  },
  modules: {
  }
})
