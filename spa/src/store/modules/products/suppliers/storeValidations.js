
// import {flatMapComponents} from "vue-router/src/util/resolve-components";

import httpClient from "@/services/http-client/http-client";

export default {
    state: {
        isValidFields: '',
        nomenclatureIsValidFields: {
            key: '',
            result: false,
            message:''
        }
    },
    mutations: {
        resetNomenclatureValidations (state) {
            state.nomenclatureIsValidFields.result = false
            state.nomenclatureIsValidFields.massage = ''
            state.nomenclatureIsValidFields.key = ''


        },
        updateValid(state, payload) {
            state.isValidFields = payload;
        },
        updateNomenclatureValid (state, {result, key, massage}) {
            state.nomenclatureIsValidFields.key = key
            state.nomenclatureIsValidFields.result = result
            state.nomenclatureIsValidFields.massage = massage ? massage[key].join(' ') :''
        }
    },
    actions: {
        onResetValid({ commit }) {
            commit('updateValid', false);
        },
        async onValidAction({ commit }, value) {
            try {
                const {data} = await httpClient.post('suppliers/store-async-validations', value);
                if (data.status) {
                    const key = Object.keys(data.message)[0];
                    commit('updateValid', key);
                } else {
                    commit('updateValid', '');
                }

            } catch (e) {
                console.log(e);
            }
        },
        async nomenclatureValidation({ commit }, {mode, body, key}) {
            const url = 'products/nomenclatures/'

            try {
                const {data} = await httpClient.post(`${url}${mode}-async-products-validations`, {...body});
                commit('updateNomenclatureValid', {
                    result: !!data.status,
                    key,
                    massage: data.message
                });
            } catch (e) {
                console.log(e);
            }
        },
        resetNomenclatureValidations ({commit}) {
            commit('resetNomenclatureValidations')
        }

},
getters: {
    isValidFields: state => state.isValidFields,
    nomenclatureIsValidFields: state => state.nomenclatureIsValidFields
}
}