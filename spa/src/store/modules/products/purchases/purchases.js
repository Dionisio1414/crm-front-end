import {httpClient} from "@/services";

export default {
    state: {
        purchasesTable: null,
        purPaginationCount: 1,
    },
    mutations: {
        updateDocument (state, payload) {
           state.purchasesTable = payload
        },
        updatePaginationPurchasesTable(state, payload) {
            state.purchasesTable.body = [...state.purchasesTable.body, ...payload];
        },
        updatePurPaginationCount(state, payload) {
            if (payload) {
                state.purPaginationCount = 1;
            } else {
                state.purPaginationCount++;
            }
        },
    },
    actions: {
        getPurPaginationCount({ commit }, mark) {
            commit('updatePurPaginationCount', mark)
        },
        async getFilteredPurchasesTable({ commit }, values) {
            const url = values ? `documents/purchases/table?is_capitalized=${values.point}&s=${values.text}` : 'documents/purchases/table';
            try {
                const { data } = await httpClient.get(url)
                if (data && !data.status) {
                    commit('updateDocument', data.data);
                } else  {
                    console.log('error')
                }
            } catch(e) {
                throw new Error(e)
            }
        },
        async fetchPurchases({commit}, page) {
            const url = page ? `documents/purchases/table?page=${page}` : 'documents/purchases/table';

            try {
                const { data } = await httpClient.get(url)
                if (data && !data.status) {
                    if (page) {
                        commit('updatePaginationPurchasesTable', data.data.body )
                    } else {
                        commit('updateDocument', data.data);
                    }
                } else  {
                    console.log('error')
                }
            } catch(e) {
                throw new Error(e)
            }
        },
        async addPurchase({ commit, dispatch }, values) {
            try {
                const { data } = await httpClient.post('documents/purchases/store-document', values);

                if (data && !data.status) {
                    await dispatch('fetchPurchases');
                    commit('updateDocument', data)
                } else  {
                    console.log('error')
                }

            } catch(e) {
                throw new Error(e)
            }
        }
    },
    getters: {
        purchasesTable: state => state.purchasesTable,
        purPaginationCount: state => state.purPaginationCount
    }
}