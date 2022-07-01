export default {

  state: {
    dialogConfirm: true,
    dialogDone: false,
    deleteConfirm: false
  },
  mutations: {
    openDialog(state) {
      state.dialogConfirm = true
    },
    closeDialog(state) {
      state.dialogConfirm = false
    },
    deleteConfirm(state) {
      state.deleteConfirm = true
    }
  },
  actions: {
    openDialog({commit}) {
      commit('openDialog')
    },
    closeDialog({commit}) {
      commit('closeDialog')
    },
    deleteConfirm({commit}) {
      commit('deleteConfirm')
    },

  },
  getters: {
    dialogConfirm: s => s.dialogConfirm,
    dialogDone: s => s.dialogDone,
    deleteConfirm: s => s.deleteConfirm,
  }

}