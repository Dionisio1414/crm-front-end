import {httpClient} from "@/services";
import formattedEditData from "@/helper/formattedEditData";

async function getHelperRequest(url, values, request, commit, dispatch, rootGetters, updateTable) {
    console.log(updateTable)
    try {
        let { data } = await httpClient[request](url, values);

        if (data && !data.status) {
            commit('updateClearEditDocumentData');
            await dispatch(updateTable);
            const formattedData = formattedEditData(rootGetters[updateTable === "fetchPurchases" ? 'purchasesTable' : 'documentTableList'].body, data.data);
            commit('updateEditDocumentData', formattedData);
            commit('updateRequestError', null);
        } else {
            commit('updateRequestError', data.message);
        }
    } catch (e) {
        console.log(e)
    }
}

export default {
    state: {
        editDocumentData: null,
    },
    mutations: {
        updateEditDocumentData(state, payload) {
            state.editDocumentData = payload
        },
        updateClearEditDocumentData(state) {
            state.editDocumentData = null;
        },
    },
    actions: {
        async addDocument({ commit, dispatch, rootGetters }, values) {
            const { resource, updateTable } = values;
            delete values.resource;
            delete values.updateTable;

            await getHelperRequest(`documents/${resource}/store-document`, values, "post", commit, dispatch, rootGetters, updateTable);
        },
        async addCapitalizedDocument({ commit, dispatch, rootGetters }, values) {
            const { resource, updateTable } = values;
            delete values.resource;
            delete values.updateTable;

            await getHelperRequest(`documents/${resource}/store-capitalized-document`, values, "post", commit, dispatch, rootGetters, updateTable);
        },
        async editDocument({ commit, dispatch, rootGetters }, values) {
            const  { id, resource, updateTable } = values;
            delete values.id;
            delete values.resource;
            delete values.updateTable;

            await getHelperRequest(`documents/${resource}/update-document/${id}`, values, "put", commit, dispatch, rootGetters, updateTable);
        },
        async editCapitalizedDocument({ commit, dispatch, rootGetters }, values) {
            const  { id, resource, updateTable } = values;
            delete values.id;
            delete values.resource;
            delete values.updateTable;

            await getHelperRequest(`documents/${resource}/capitalized-document/${id}`, values, "put", commit, dispatch, rootGetters, updateTable);
        },
        async canceledCapitalizedDocument({ commit, dispatch }, values) {
            const { id, resource, updateTable } = values;
            delete values.id;
            delete values.resource;
            delete values.updateTable;

            try {
                let {data} = await httpClient.put(`documents/${resource}/canceled-capitalized-document/${id}`);

                if (data && !data.status) {
                    await dispatch(updateTable);
                    commit('updateRequestError', null);
                } else {
                    commit('updateRequestError', data.message);
                }
            } catch (e) {
                console.log(e)
            }
        },
    },
    getters: {
        editDocumentData: state => state.editDocumentData
    }
}
