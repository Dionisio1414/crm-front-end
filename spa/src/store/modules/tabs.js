export default {
    state: {
        tabs: []
    },
    mutations: {
        setTabs(state, payload) {
            const tabItem = state.tabs.findIndex(item => item.title === payload.title)
            if(tabItem === -1) {
                state.tabs.push(payload)
                localStorage.setItem('tabs', JSON.stringify(state.tabs))
            }
        },
        deleteCurrentTab(state, id) {
            state.tabs.splice(id, 1)
            localStorage.setItem('tabs', JSON.stringify(state.tabs))
        },
        initTabs(state) {
            if(localStorage.getItem('tabs')) {
                state.tabs = null,
                state.tabs = JSON.parse(localStorage.getItem('tabs'))
            }
        }
    },
    actions: {
        updateTabs({ commit }, payload) { commit('setTabs', payload) },
        deleteTab({ commit }, payload) { commit('deleteCurrentTab', payload) },
        initialTabs({ commit }) { commit('initTabs') }
    },
    getters: {
        tabs: s => s.tabs,
        tabsLength: s => s.tabs.length
    }
}
