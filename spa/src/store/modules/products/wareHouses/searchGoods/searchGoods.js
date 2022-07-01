import {httpClient} from "@/services";

export default {
    state: {
        searchList: [],
        selectedItem: [],
        selectedServicesItem: [],
        tabIdx: 0,
        rest: []
    },
    mutations: {
        defaultStore(state) {
            state.searchList = [];
            state.selectedItem = [];
            state.selectedServicesItem = [];
            state.rest = [];
            state.tabIdx = 0;
        },
        updateTabsIdx(state, payload) {
            state.tabIdx = payload;
        },
        updateSearchList(state, payload) {
            state.searchList = payload;
        },
        updateSelectedItem(state, payload) {
            if (Array.isArray(payload.body)) {
                if (state.tabIdx) {
                    state.selectedServicesItem = [...state.selectedServicesItem, ...payload.body];
                } else {
                    state.selectedItem = [...state.selectedItem, ...payload.body];
                }
            } else {
                if (state.tabIdx) {
                    state.selectedServicesItem.push(payload.body);
                } else {
                    state.selectedItem.push(payload.body);
                }
            }
        },
        updateFillChoseItems(state, payload) {
            if (payload) {
                if (state.tabIdx) {
                    state.selectedServicesItem = [...state.selectedServicesItem, ...payload];
                } else {
                    state.selectedItem = [...state.selectedItem, ...payload];
                }
            }
        },
        updateFillItems(state, payload) {
            if (payload) {
                const { product, service } = payload;
                if (product || service) {
                    state.selectedItem = product || [];
                    state.selectedServicesItem = service || [];
                } else {
                    state.selectedItem = payload;
                }
            }
        },
        updateDeleteItems(state, payload) {
            if (state.tabIdx) {
                state.selectedServicesItem = payload;
            } else {
                state.selectedItem = payload;
            }

        },
        updateRest(state, payload) {
            state.rest = payload;
        }
    },
    actions: {
        onDefaultStore({ commit }) { commit('defaultStore'); },
        clearSearchInput({ commit }) { commit('updateSearchList', []); },
        clearSearchItems({ commit }) { commit('updateDeleteItems', []); },
        fillGoodsItems({ commit }, values) { commit('updateFillChoseItems', values); },
        onTabsIdx({ commit } , value) { commit('updateTabsIdx', value-1); },
        actonDeleteItemList({ commit, state }, ids) {
            if (state.tabIdx) {
                const cloneSelectedServicesItem = state.selectedServicesItem.slice(0);
                for (let id of ids) cloneSelectedServicesItem.splice(cloneSelectedServicesItem.findIndex(item => item.id === id), 1);
                commit('updateDeleteItems', cloneSelectedServicesItem);
            } else {
                const cloneSelectedItem = state.selectedItem.slice(0);
                for (let id of ids) cloneSelectedItem.splice(cloneSelectedItem.findIndex(item => item.id === id), 1);
                commit('updateDeleteItems', cloneSelectedItem);
            }
        },
        async getSearchGoods({ commit }, query) {
            commit('updatePending', true);

            const params = {
                s: query
            }

            try {
                let { data }  = await httpClient.get('products/nomenclatures/document/search-products', { params });

                if (data && !data.status) {
                    commit('updateSearchList', data.data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async getSelectedProduct({ commit }, query) {
            const url = `products/nomenclatures/document/table-stocks-product/${query.id}?price_id=${query.price_id}&date=${query.date}`;
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(url);

                if (data && !data.status) {
                    commit('updateSelectedItem', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async getSelectedProductWriteOff({ commit }, query) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.post('products/nomenclatures/selected-write-of-nomenclatures', query);

                if (data && !data.status) {
                    commit('updateSelectedItem', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async getFillProducts({ commit }, query) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(`products/warehouses/fill-products-stocks/${query.id}?price_id=${query.price_id}&date=${query.date}`);

                if (data && !data.status) {
                    const body = data.data.nomenclatures.body;
                    commit('updateFillItems', body);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async getFillGoods({ commit }, query) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.post('/products/nomenclatures/selected-nomenclatures', query);

                if (data && !data.status) {
                    const body = data.data.body;
                    commit('updateFillItems', body);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async getEditFillProducts({ commit }, {id, res}) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(`documents/${res}/get-document/${id}`);

                if (data && !data.status) {
                    const body = data.data.nomenclatures.body;
                    commit('updateFillItems', body);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async getRest({commit}, id) {
            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(`products/nomenclatures/select-product/${id}`);

                if (data && !data.status) {
                    commit('updateRest', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        }
    },
    getters: {
        searchList: state => state.searchList,
        selectedList: state => state.selectedItem,
        selectedServicesList: state => state.selectedServicesItem,
        rest: state => state.rest,
        tabIdx: state => state.tabIdx,
    }
}
