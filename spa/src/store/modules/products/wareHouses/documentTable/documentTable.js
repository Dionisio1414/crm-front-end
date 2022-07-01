import httpClient from "@/services/http-client/http-client";

export default {
    state: {
        documentTableList: null,
        docPaginationCount: 1,
        selectedTableItems: null
    },
    mutations: {
        updateDocumentTable(state, payload) {
            state.documentTableList = payload;
        },
        updatePaginationDocumentTable(state, payload) {
            state.documentTableList.body = [...state.documentTableList.body, ...payload];
        },
        updateDocPaginationCount(state, payload) {
            if (payload) {
                state.docPaginationCount = 1;
            } else {
                state.docPaginationCount++;
            }
        },
        updateSelectedItems(state, payload) {
            state.selectedTableItems = payload;
        }
    },
    actions: {
        onClearDocumentList({ commit }) {
            commit('updateSelectedItems', null);
        },
        getDocPaginationCount({ commit }, mark) {
            commit('updateDocPaginationCount', mark)
        },
        async getDocumentTable({ commit }, page) {
            const url = page ? `documents/get-warehouse-documents-all?page=${page}` : 'documents/get-warehouse-documents-all';
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(url);

                if (data && !data.status) {
                    if (page) {
                        commit('updatePaginationDocumentTable', data.data.body);
                    } else {
                        commit('updateDocumentTable', data.data);
                    }

                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async getWarehouseProductsAll({ commit }) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get('products/warehouses/products-all');

                if (data && !data.status) {
                    commit('setItems', { items: data.data, resources: 'products' });
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async getWarehouseProducts({ commit }, id) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(`products/warehouses/products/${id}`);

                if (data && !data.status) {
                    commit('setItems', { items: data.data, resources: 'products' });
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async getWarehouseDocuments({ commit }, id) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(`documents/get-warehouse-documents/${id}`);

                if (data && !data.status) {
                    commit('updateDocumentTable', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async onDocumentToArchive({ commit, dispatch }, values) {
            const { arrayId, updateTable } = values;
            const arrayIdDelete = arrayId.map((item) => {
                return { "id": `${item}` }
            })

            const body = {
                "data": arrayIdDelete
            }

            commit('updatePending', true);

            try {
                const { data } = await httpClient.post('documents/toArchive', body);

                if (data && !data.status) {
                    await dispatch(updateTable);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        getSelectedItems({ commit }, value) {
            commit('updateSelectedItems', value);
        }
    },
    getters: {
        docPaginationCount: state => state.docPaginationCount,
        documentTableList: state => state.documentTableList,
        selectedTableItems: state => state.selectedTableItems
    }
}
