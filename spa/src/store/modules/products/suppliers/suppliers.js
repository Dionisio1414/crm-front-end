import getFlatMap from "@/helper/getFlatMap";
import storeValidations from "@/store/modules/products/suppliers/storeValidations";
import suppliersList from "@/store/modules/products/suppliers/suppliersList";
import httpClient from "@/services/http-client/http-client";

    export default {
    state: {
        suppliersGroupList: [],
        suppliersGroupTotal: null,
        suppliersGroupTotalPage: null,
        requestError: null,
        pending: false,
        isGroupSuccess: null,
        currentId: null,
        refContextMenu: null,
        editData: null,
        flatList: null,
    },
    mutations:{
        updateCurrentId(state, payload) {
            state.currentId = payload;
        },
        updateEditData(state, payload) {
            state.editData = payload;
        },
        updatePending(state, payload) {
            state.pending = payload;
        },
        updateGroupSuccess(state, value) {
            state.isGroupSuccess = value;
        },
        updateSuppliersGroup(state, payload) {
            state.suppliersGroupList = payload.list;
            state.suppliersGroupTotal = payload.total;
            state.isGroupSuccess = payload.title;
            state.suppliersGroupTotalPage = payload['total_page'];
        },
        updateRequestError(state, payload) {
            state.requestError = payload;
        },
        updateRefContextMenu(state, payload) {
            state.refContextMenu = payload;
        },
        updateFlatList(state, payload) {
            state.flatList = payload;
        },
    },
    actions: {
        onCurrentId({ commit }, value) {
            commit('updateCurrentId', value)
        },
        getEditData({ commit }, value) {
            commit('updateEditData', value)
        },
        onActionSuppliersList({ commit }, payload) {
            commit('updateSuppliersAddressList', payload);
        },
        onGroupSuccess({ commit }, value) {
            commit('updateGroupSuccess', value)
        },
        getRefContextMenu({ commit }, ref) {
            commit('updateRefContextMenu', ref)
        },
        async getSuppliersGroup({ commit }) {
            try {
                let { data }  = await httpClient.get('suppliers/groups');

                if (data && !data.status) {
                    await commit('updateSuppliersGroup', data.data);

                    commit('updateFlatList', getFlatMap(data.data.list));

                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
        async getSearchGroup({ commit }, query) {
            commit('updatePending', true);

            const params = {
                s: `${query}`
            }

            try {
                let { data }  = await httpClient.get('suppliers/groups', { params });

                if (data && !data.status) {
                    commit('updateSuppliersGroup', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async onAddSuppliersGroup({ commit, dispatch }, values) {
            commit('updatePending', true);

            try {
                const { data } = await httpClient.post('suppliers/groups', values);

                if (data && !data.status) {
                    await dispatch('getSuppliersGroup');

                    commit('updateGroupSuccess', `Добавлена группа "${data.data.title}"`);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onAddArchive({ commit, dispatch }, id) {
            const body = {
                "data": [{ "id": `${id}` }]
            }

            try {
                const { data } = await httpClient.post('suppliers/groups/toArchive', body);

                if (data && !data.status) {
                    await dispatch('getSuppliersGroup');

                    commit('updateGroupSuccess', `Удаление группы "${data.success}"`)
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onEditGroup({ commit, dispatch }, values) {
            commit('updatePending', true);
            try {
                const { data } = await httpClient.put(`suppliers/groups/${values.id}`, values);

                if (data && !data.status) {
                    await dispatch('getSuppliersGroup');

                    commit('updateGroupSuccess', `Изменена группа "${data.data.title}"`);
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
        suppliersGroupList: s => s.suppliersGroupList,
        suppliersGroupTotal:s => s.suppliersGroupTotal,
        suppliersGroupTotalPage: s => s.suppliersGroupTotalPage,
        suppliersGroups: s => s.suppliersGroups,
        requestError: s => s.requestError,
        isGroupSuccess: s => s.isGroupSuccess,
        pending: s => s.pending,
        currentId: s => s.currentId,
        refContextMenu: s => s.refContextMenu,
        editData: s => s.editData,
        flatList: s => s.flatList
    },
    modules:{
        storeValidations,
        suppliersList
    }
}
