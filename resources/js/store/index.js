import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    idgrado_modalidad: null,
    idexpediente: null,    
  },
  mutations: {
    SET_IDGRADO_MODALIDAD(state, id) {
        state.idgrado_modalidad = id
    },
    SET_IDEXPEDIENTE(state, id) {
        state.idexpediente = id
    }    
  },
  actions: {
    setIdGradoModalidad({commit}, id) {
        commit('SET_IDGRADO_MODALIDAD', id)
    },
    setIdExpediente({commit}, id) {
        commit('SET_IDEXPEDIENTE', id)
    }    
  },
  modules: {
  }
})
