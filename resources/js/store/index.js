import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    idtramite: null,
    idexpediente: null,    
  },
  mutations: {
    SET_IDTRAMITE(state, id) {
        state.idtramite = id
    },
    SET_IDEXPEDIENTE(state, id) {
        state.idexpediente = id
    }    
  },
  actions: {
    setIdTramite({commit}, id) {
        commit('SET_IDTRAMITE', id)
    },
    setIdExpediente({commit}, id) {
        commit('SET_IDEXPEDIENTE', id)
    }    
  },
  modules: {
  }
})
