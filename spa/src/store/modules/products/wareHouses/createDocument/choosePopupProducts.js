import httpClient from "@/services/http-client/http-client";

export default {
    state: {
        documentCategories: [],
        searchProductList: [],
        documentProduct: null,
        documentServices: null,
        choosePopupPending: false
    },
    mutations: {
        updateChoosePopupPending(state, payload) {
            state.choosePopupPending = payload;
        },
        updateCategory(state, payload) {
            state.documentCategories = payload;
        },
        updateDocumentProduct(state, payload) {
            state.documentProduct = payload;
        },
        updateDocumentServices(state, payload) {
            state.documentServices = payload;
        },
        updateSearchProduct(state, payload) {
            state.searchProductList = payload;
        }
    },
    actions: {
        async getDocumentCategories({ commit }) {
            commit('updateChoosePopupPending', true);
            try {
                let {data} = await httpClient.get('products/categories');

                if (data && !data.status) {
                    commit('updateCategory', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updateChoosePopupPending', false);
        },
       async searchProductTable({ commit }, values) {
            const { value, parent_id } = values;
            const url = `products/nomenclatures/search-products-document-table?s=${value}&parent_id=${parent_id}`;

           try {
               const {data} = await httpClient.get(url);

               if (data && !data.status) {
                   commit('updateRequestError', null);
                   return data;
               } else {
                   commit('updateRequestError', data.message);
               }
           } catch (e) {
               console.log(e)
           }
       },
        async getSelectProductAll({ commit }, id) {
            const params = {
                warehouse_id: id
            }

            commit('updateChoosePopupPending', true);

            try {
                const {data} = await httpClient.get('products/nomenclatures/get-select-products-all', { params });

                if (data && !data.status) {
                    commit('updateDocumentProduct', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updateChoosePopupPending', false);
        },
        async getSelectServicesAll({ commit }) {
            commit('updateChoosePopupPending', true);
            try {
                const {data} = await httpClient.get('products/nomenclatures/get-select-services-all');

                if (data && !data.status) {
                    commit('updateDocumentServices', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updateChoosePopupPending', false);
        },
        async getFillWarehouseProducts({ commit }, query) {
            commit('updateChoosePopupPending', true);
            try {
                let { data }  = await httpClient.get(`products/warehouses/fill-products-stocks/${query.id}?price_id=${query.price_id}&date=${query.date}`);
                const body = data.data.nomenclatures;

                if (data && !data.status) {
                    commit('updateDocumentProduct', body);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updateChoosePopupPending', false);
        },
    },
    getters: {
        documentCategories: state => state.documentCategories,
        documentProduct: state => state.documentProduct,
        documentServices: state => state.documentServices,
        searchProductList: state => state.searchProductList,
        choosePopupPending: state => state.choosePopupPending
    }
}
