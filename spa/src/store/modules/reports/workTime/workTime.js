import { httpClient } from "@/services";

export default {
    state: {
        workTimeList: null,
        workTimeListError: null,
        workTimeListPending: false
    },
    mutations: {
        updateWorkTimeList(state, payload) {
            state.workTimeList = payload
        },
        updateWorkTimeListPending(state, payload) {
            state.workTimeListPending = payload
        },
        updateWorkTimeListError(state, payload) {
            state.workTimeListError = payload
        }
    },
    actions: {
        async getWorkTimeList({ commit }) {
            commit('updateWorkTimeListPending', true);

            try {
                const { data } = await httpClient.get('reports/worktime');

                if (data && !data.status) {
                    commit('updateWorkTimeList', data.data);
                    commit('updateWorkTimeListError', null);
                } else {
                    commit('updateWorkTimeListError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updateWorkTimeListPending', false);
        },
        async actionWorkTimeList({ commit }, values) {
            commit('updateWorkTimeListPending', true);

            try {
                const { data } = await httpClient.post('reports/worktime', values);

                if (data && !data.status) {
                    commit('updateWorkTimeList', data.data);
                    commit('updateWorkTimeListError', null);
                } else {
                    commit('updateWorkTimeListError', data.message);
                }
            } catch (e) {
                console.log(e)
            }

            commit('updateWorkTimeListPending', false);
        }
    },
    getters: {
        workTimeList: s => s.workTimeList,
        workTimeListError: s => s.workTimeListError,
        workTimeListPending: s => s.workTimeListPending
    }
}