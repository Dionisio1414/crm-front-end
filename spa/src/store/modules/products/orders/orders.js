import {httpClient} from "@/services";

export default {
    state: {
        ordersTable: null,
        ordersPaginationCount: 1,
    },
    mutations: {
        updateDocument (state, payload) {
            state.ordersTable = payload
        },
        updatePaginationOrdersTable(state, payload) {
            state.ordersTable.body = [...state.ordersTable.body, ...payload];
        },
        updateOrdersPaginationCount(state, payload) {
            if (payload) {
                state.OrderPaginationCount = 1;
            } else {
                state.OrderPaginationCount++;
            }
        },
    },
    actions: {
        getOrdersPaginationCount({ commit }, mark) {
            commit('updateOrdersPaginationCount', mark)
        },
        async getFilteredOrdersTable({ commit }, values) {
            const url = values ? `documents/orders/table?is_capitalized=${values.point}&s=${values.text}` : 'documents/orders/table';
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
        async fetchOrders({commit}, page) {
            const url = page ? `documents/orders/table?page=${page}` : 'documents/orders/table';

            try {
                const { data } = await httpClient.get(url)
                if (data && !data.status) {
                    if (page) {
                        commit('updatePaginationOrdersTable', data.data.body )
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
                const { data } = await httpClient.post('documents/orders/store-document', values);

                if (data && !data.status) {
                    await dispatch('fetchOrders');
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
        ordersTable: state => state.ordersTable,
        ordersPaginationCount: state => state.ordersPaginationCount
    }
}
