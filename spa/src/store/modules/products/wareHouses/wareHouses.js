import groups from "@/store/modules/products/wareHouses/groups/groups";
import searchGoods from "@/store/modules/products/wareHouses/searchGoods/searchGoods";
import createDocument from "@/store/modules/products/wareHouses/createDocument/createDocument";
import documentTable from "@/store/modules/products/wareHouses/documentTable/documentTable";
import choosePopupProducts from "@/store/modules/products/wareHouses/createDocument/choosePopupProducts";

export default {
    state: {
        loading: false,
        requestError: null,
        requestSuccess: null,
    },
    mutations: {
        updatePending(state, payload) {
            state.loading = payload;
        },
        updateRequestError(state, payload) {
            state.requestError = payload;
        },
        updateRequestSuccess(state, payload) {
            state.requestSuccess = payload;
        },
    },
    getters: {
        loading: state => state.loading,
        requestSuccess: state => state.requestSuccess
    },
    modules: {
        groups,
        searchGoods,
        createDocument,
        documentTable,
        choosePopupProducts
    }
}