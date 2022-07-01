export default {

  state: {
    alert: {
      name: '',
      title: '',
      createdName: '',
      link: '',
      startText: '',
      endText: '',
      item: ''
    },
    alertVisible: false,

  },
  mutations: {
    alertShow(state, dataAlert) {
      state.alert.text = dataAlert.text
      state.alert.startText = dataAlert.startText
      state.alert.endText = dataAlert.endText
      state.alert.item = dataAlert.item
      state.alert.name = dataAlert.name
      state.alert.title = dataAlert.title
      state.alert.createdName = dataAlert.createdName
      state.alert.link = dataAlert.link ? dataAlert.link : '/products'
      state.alertVisible = true
      setTimeout(() => {
        if (state.alertVisible) {
          state.alertVisible = false
        }
      },5000)
    },
    hideAlert(state) {
      state.alertVisible = false
    }
  },
  actions: {
    alertShow({commit}, dataAlert) {
      commit('alertShow', dataAlert)
    },
    hideAlert({commit}) {
      commit('hideAlert')
    }
  },
  getters: {
    alert: s => s.alert,
    alertVisible: s => s.alertVisible,
  }

}