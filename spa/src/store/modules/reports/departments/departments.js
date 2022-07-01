import { httpClient } from "@/services";

export default {
    state: {
        departmentsList: null,
        departmentsListDefaultId: null
    },
    mutations: {
        updateDepartmentsList(state, payload) {
            state.departmentsList = payload;
            state.departmentsListDefaultId = payload[0].id;
        }
    },
    actions: {
        async getDepartmentsList({ commit }) {
            try {
                const { data } = await httpClient("directories/companies_departments");

                if (data && !data.status) {
                    commit("updateDepartmentsList", data.data.list);
                }
            } catch (e) {
                console.log(e)
            }
        }
    },
    getters: {
        departmentsList: s => s.departmentsList,
        departmentsListDefaultId: s => s.departmentsListDefaultId
    }
}