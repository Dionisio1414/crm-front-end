import httpClient from "@/services/http-client/http-client"

export default {
    state: {
        counterparties_contracts_filter: {},
    },
    mutations: {
        updateFilters(state, { data, payload }) {
            state[`${payload}_filter`] = data
        },
        changeFilter(state, payload) {
            const type = Object.keys(payload)[0]
            state[`${payload.resource}_filter`].is_active_filter = true
            state[`${payload.resource}_filter`][type] = { 
                ...state[`${payload.resource}_filter`][type],
                ...payload[type]
            }
        }
    },
    actions: {
        async getFilters({ commit }, payload) {
            try {
                const { data } = await httpClient.get(`filters/${payload}`)

                if(data && !data.status) {
                    await commit('updateFilters', { data: data.data, payload })
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async applyFilters({ commit }, payload) {
            const currentData = {
                ...payload.data,
                is_active_filter: true
            }
            console.log(currentData)
            try {
                const { data } = await httpClient.post(`filters/${payload.resource}`, currentData)
                console.log('data', data, commit)

                if(data && !data.status) {
                    await commit('updateFilters', { data: data.data, payload })
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async resetFilters({ commit }, payload) {
            try {
                const { data } = await httpClient.post(`filters/${payload}`, { is_active_filter: false })
                console.log('data', data)

                if(data && !data.status) {
                    await commit('updateFilters', { data: data.data, payload })
                } else {
                    console.log('status', data.status)
                }
            } catch(e) {
                console.log(e)
            }
        },
        changeDataFilter({ commit }, payload) {
            commit('changeFilter', payload)
        }
    },
    getters: {
        getFilterData: s => type => s[`${type}_filter`]
    }
}