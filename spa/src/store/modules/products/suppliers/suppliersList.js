import getSupplierData from "@/helper/getSupplierData";
import httpClient from "@/services/http-client/http-client";

export default {
    state:{
        suppliersAddressList: [],
        suppliersDataTable: null,
        supplierData: null,
        suppliersPaginationCount: 1
    },
    mutations: {
        updateSuppliersAddressList(state, payload) {
            state.suppliersAddressList = payload;
        },
        updatePaginationSuppliersTable(state, payload) {
            state.suppliersDataTable.body = [...state.suppliersDataTable.body, ...payload];
        },
        updateSuppliersDataTable(state, payload) {
            state.suppliersDataTable = payload;
        },
        updateSupplierData(state, payload) {
            state.supplierData = payload;
        },
        updateSuppliersPaginationCount(state, payload) {
            if (payload) {
                state.suppliersPaginationCount = 1;
            } else {
                state.suppliersPaginationCount++;
            }
        },
    },
    actions: {
        getSupplierPaginationCount({ commit }, mark) {
            commit('updateSuppliersPaginationCount', mark)
        },
        async getSuppliersTable({ commit }, values) {
            const { page, id } = values || {};
            const url = page ? `suppliers/table?page=${page}` : 'suppliers/table';
            const params = id && {
                group_id: `${id}`
            }

            commit('updatePending', true);

            try {
                let { data }  = await httpClient.get(url, { params });

                if (data && !data.status) {
                    if (page) {
                        commit('updatePaginationSuppliersTable', data.data.body);
                    } else {
                        commit('updateSuppliersDataTable', data.data);
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
        async getSearchTable({ commit }, query) {
            commit('updatePending', true);

            const params = {
                s: `${query}`
            }

            try {
                let { data }  = await httpClient.get('suppliers/table', { params });

                if (data && !data.status) {
                    commit('updateSuppliersDataTable', data.data);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updatePending', false);
        },
        async onCreateSupplier({ commit, dispatch }, values) {
            const { copy, add, nameOfCompany } = values;

            const supplierData = getSupplierData(values);

            commit('updatePending', true);

            try {
                const { data } = await httpClient.post('suppliers/list', supplierData);

                if (data && !data.status) {
                    await dispatch('getSuppliersTable');

                    commit('updateSupplierData', data.data);
                    commit('updateRequestError', null);

                    if (copy) commit('updateGroupSuccess', `Поставщик "${nameOfCompany}" скопирован`)
                    if (add) commit('updateGroupSuccess', `Создан поставщик "${nameOfCompany}"`)
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onEditSupplier({ commit, dispatch }, values) {
            const { id, nameOfCompany } = values;
            const supplierData = getSupplierData(values);

            commit('updatePending', true);

            try {
                const { data } = await httpClient.put(`suppliers/list/${id}`, supplierData);

                if (data && !data.status) {
                    commit('updateGroupSuccess', `Поставщик ${nameOfCompany} изменен`)
                    await commit('updateRequestError', null);
                    dispatch('getSuppliersTable');
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onMoveSupplierList({ commit, dispatch }, values) {
            const { data, title, groupTitle } = values;

            const body = { data }

            commit('updatePending', true);

            try {
                const { data } = await httpClient.post('suppliers/changeGroups', body);

                if (data && !data.status) {
                    await dispatch('getSuppliersTable');

                    commit('updateGroupSuccess', `Перенесен(ы) поставщик(и) ${title} в группу ${groupTitle}`)
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
        async onAddArchiveList({ commit, dispatch }, arrayId) {
            const arrayIdDelete = arrayId.map((item) => {
                return { "id": `${item}` }
            })

            const body = {
                "data": arrayIdDelete
            }

            commit('updatePending', true);

            try {
                const { data } = await httpClient.post('suppliers/toArchive', body);

                if (data && !data.status) {
                    await dispatch('getSuppliersTable');
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }

                commit('updatePending', false);
            } catch (e) {
                console.log(e);
            }
        },
    },
    getters: {
        suppliersDataTable: state => state.suppliersDataTable,
        suppliersAddressList: state => state.suppliersAddressList,
        supplierData: state => state.supplierData,
        suppliersPaginationCount: state => state.suppliersPaginationCount
    }
}
