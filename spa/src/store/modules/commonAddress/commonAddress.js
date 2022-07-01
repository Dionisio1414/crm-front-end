import {httpClient} from "@/services";

export default {
    state: {
        suppliers: {
            physical: {
                countries: [],
                regions: [],
                cities: []
            },
            legal: {
                countries: [],
                regions: [],
                cities: []
            },
            delivery: {
                countries: [],
                regions: [],
                cities: []
            },
            warehouse: {
                countries: [],
                regions: [],
                cities: []
            }
        },
        organizations: {
            legal: {
                countries: [],
                regions: [],
                cities: []
            },
            actual: {
                countries: [],
                regions: [],
                cities: []
            },
        }
    },
    mutations: {
        updateAddressList(state, payload) {
            const { types, data: { list } } = payload;
            const { type, type_address, type_list } = types;

            state[type][type_address][type_list] = list;
        },
        updateAddressListWithPaginate(state, { types, data, page }) {

            const { type, type_address, type_list } = types;
            const { list, total_page } = data

            if(list.length && page <= total_page) {
                state[type][type_address][type_list] = [ ...state[type][type_address][type_list], ...list ]
            } else if(!list.length && page > total_page) {
                state[type][type_address][type_list] = [ ...state[type][type_address][type_list] ]
            } else {
                state[type][type_address][type_list] = list
            }
        }
    },
    actions: {
        async getAddressList({ commit }, values) {
            const { types, query = '' } = values;

            try {
                const { data: { data } } = await httpClient.get(`/directories/${types.type_list}/list${query}`)
                commit('updateAddressList', { types, data })
            } catch(e) {
                throw new Error(e)
            }
        },
        async getAddressListWithPaginate({ commit }, { types, query, paginate, page }) {
            const checkQuery = query.length ? query : '';
            try {
                const { data } = await httpClient.get(`/directories/${types.type_list}/list${checkQuery}`)
                if(data && !data.status) {
                    if(!paginate) {
                        commit('updateAddressList', { types, data: data.data })
                    } else {
                        commit('updateAddressListWithPaginate', { types, data: data.data, page })

                    }
                } else {
                    console.log('status', data.status)
                }
            } catch(e) {
                throw new Error(e)
            }
        }
    },
    getters: {
        getAddress: s => (type, type_address) => s[type][type_address]
    }
}
