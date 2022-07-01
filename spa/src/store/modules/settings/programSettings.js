import {httpClient} from "@/services";

export default {
    state: {
        settings: null
    },
    getters: {
        getSettings: s => s.settings
    },
    actions: {
        async fetchSettings({commit}) {
            try {
                const {data} = await httpClient.get('/settings/main')
                commit('setSettings', data.data)
            } catch (e) {
                console.log(e);
            }
        },
        async changeSettings({commit}, newSettings) {
            try {
                const {data} = await httpClient.post('/settings/main', newSettings)
                commit('setSettings', data.data)
                commit('setUserCompany', newSettings.company, { root: true })
            } catch (e) {
                console.log(e);
            }
        }
    },
    mutations: {
        setSettings (state, payload) {
            state.settings  = { ...payload }
        }
    }
}