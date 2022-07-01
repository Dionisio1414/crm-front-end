import httpClient from "@/services/http-client/http-client";
const commonUrl = 'products/warehouses';

export default {
    state: {
        wareHouseGroupsList: null,
        allWareHouses: null,
        favoriteId: null,
        requestErrorWarehouse: null
    },
    mutations: {
        updateGroupsList(state, payload) {
            state.wareHouseGroupsList = payload;
        },
        updateAllWareHouses(state, payload) {
            state.allWareHouses = payload;
        },
        updateFavorite(state, payload) {
            state.favoriteId = payload;
        },
        updateRequestErrorWarehouse(state, payload) {
            state.requestErrorWarehouse = payload;
        },
    },
    actions: {
        async getGroupsList({ commit }) {
            commit('updatePending', true);
            commit('updateRequestSuccess', null);

            try {
                let {data} = await httpClient.get(commonUrl);

                if (data && !data.status) {
                    commit('updateGroupsList', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async getAllWareHouse({ commit }) {
            commit('updatePending', true);
            commit('updateRequestSuccess', null);

            try {
                let {data} = await httpClient.get(`${commonUrl}/get-warehouses`);

                if (data && !data.status) {
                    commit('updateAllWareHouses', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async getTableProduct({ commit }, id) {
            commit('updatePending', true);
            commit('updateRequestSuccess', null);

            try {
                let {data} = await httpClient.get(`${commonUrl}/products/${id}`);

                if (data && !data.status) {
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
            commit('updatePending', false);
        },
        async addWareHouse({ commit, dispatch}, value) {
            const {isSingle} = value;
            delete value.isSingle;

            try {
                let {data} = await httpClient.post(commonUrl, value);

                if (data && !data.status) {
                    await dispatch('getGroupsList');
                    await dispatch('getAllWareHouse');

                    if (!isSingle) {
                        const itemData = {...data.data, isWarehouse: true};

                        commit('updateRequestSuccess', itemData);
                    }
                    commit('updateRequestErrorWarehouse', null);
                } else {
                    commit('updateRequestErrorWarehouse', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async addWareHouseGroup({commit, dispatch}, value) {
            const {isSingle} = value;
            delete value.isSingle;

            try {
                let {data} = await httpClient.post(`${commonUrl}/create-warehouse-group`, value);

                if (data && !data.status) {
                    await dispatch('getGroupsList');

                    if (!isSingle) {
                        const itemData = {...data.data};

                        commit('updateRequestSuccess', itemData);
                    }
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async editWareHouseGroup({ commit, dispatch}, values) {
            const {id} = values;
            delete values.id;

            try {
                let {data} = await httpClient.put(`${commonUrl}/update-warehouse-group/${id}`, values);

                if (data && !data.status) {
                    await dispatch('getGroupsList');

                    const itemData = {...data.data};

                    commit('updateRequestSuccess', itemData);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async onMoveWarehouseToGroup({commit, dispatch}, values) {
            const {currentWareHouseId} = values;
            delete values.currentWareHouseId;

            try {
                let {data} = await httpClient.put(`${commonUrl}/move-warehouses/${currentWareHouseId}`, {data: [values]});

                if (data && !data.status) {
                    await dispatch('getGroupsList');

                    const itemData = data.data;

                    commit('updateRequestSuccess', itemData);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async editWareHouse({commit, dispatch}, values) {
            const {id} = values;
            delete values.id;

            try {
                let {data} = await httpClient.put(`${commonUrl}/${id}`, values);

                if (data && !data.status) {
                    await dispatch('getGroupsList');
                    await dispatch('getAllWareHouse');

                    const itemData = {...data.data};

                    commit('updateRequestSuccess', itemData);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async onWareHouseToArchive({commit, dispatch}, id) {
            const body = {
                "data": [{"id": `${id}`}]
            }

            try {
                const {data} = await httpClient.post(`${commonUrl}/toArchiveWarehouse`, body);

                if (data && !data.status) {
                    await dispatch('getGroupsList');

                    const itemData = data.data;

                    commit('updateRequestSuccess', itemData);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onWareHouseGroupsToArchive({commit, dispatch}, id) {
            const body = {
                "data": [{"id": `${id}`}]
            }

            try {
                const {data} = await httpClient.post(`${commonUrl}/toArchiveWarehouseGroup`, body);

                if (data && !data.status) {
                    await dispatch('getGroupsList');

                    const itemData = data.data;

                    commit('updateRequestSuccess', itemData);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async getFavorite({ commit }) {
            try {
                const {data} = await httpClient.get(`${commonUrl}/get-default-warehouse`);

                if (data && !data.status) {
                    commit('updateFavorite', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onActionFavorite({commit}, id) {
            try {
                const {data} = await httpClient.put(`${commonUrl}/choose-default-warehouse/${id}`);

                if (data && !data.status) {
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        }
    },
    getters: {
        wareHouseGroupsList: state => state.wareHouseGroupsList && state.wareHouseGroupsList.slice(0, -1),
        allWareHouses: state => state.allWareHouses,
        favoriteId: state => state.favoriteId,
        requestErrorWarehouse: state => state.requestErrorWarehouse,
        wareHouses: state => {
            const wareHouse = state.wareHouseGroupsList && state.wareHouseGroupsList.slice(0).pop();
            const {not_warehouse} = wareHouse || {};

            return not_warehouse;
        }
    }
}