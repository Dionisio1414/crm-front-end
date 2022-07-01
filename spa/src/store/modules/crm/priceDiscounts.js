import {httpClient} from "@/services";

export default {
    state: {
        priceDiscountList: null,
        requestError: null,
        pricesTable: null
    },
    mutations: {
        updatePriceDiscountList(state, payload) {
            state.priceDiscountList = payload;
        },
        updateRequestError(state, payload) {
            state.requestError = payload;
        },
        updatePricesTable(state, payload) {
            state.pricesTable = payload;
        }
    },
    actions: {
        async getPriceDiscountList({ commit }) {
            try {
                let {data} = await httpClient.get('/products/prices/get-nomenclature-crm-prices');

                if (data && !data.status) {
                    commit('updatePriceDiscountList', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async getPricesTable({commit}) {
            try {
                const { data } = await httpClient.get('directories/prices_types/table');

                if (data && !data.status) {
                    commit('updatePricesTable', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        }
    },
    getters: {
        priceDiscountList: s => s.priceDiscountList,
        pricesTable: s => s.pricesTable
    }
}
