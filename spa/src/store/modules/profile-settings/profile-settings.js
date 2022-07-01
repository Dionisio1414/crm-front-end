import httpClient from "@/services/http-client/http-client"

export default {
    state: {
        authData: {
            checkPassword: false,
            checkChangePassword: false,
            checkSuccessEmail: false,
            checkSuccessPhone: false
        }
    },
    actions: {
        async checkOldPassword({ commit }, payload) {
            try {
                const { data } = await httpClient.post('/user/password/check_old_password', { data: { ...payload.data } })

                if(data && !data.status) {
                    console.log(data)
                    await commit('checkUserState', { data: data.success, type: payload.type })
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async changePassword(context, payload) {
            try {
                const { data } = await httpClient.post('/user/password/change', { data: { ...payload.data } })

                if(data && !data.status) {
                    console.log(data)
                    // await commit('checkUserState', { data: data.success, type: payload.type })
                    return Promise.resolve(data)
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async userSend(context, payload) {
            try {
                const { data } = await httpClient.post('/user/authorization/send_token', { data: { ...payload.data } })

                if(data && !data.status) {
                    console.log(data)
                    // await commit('checkUserState', { data: !data.message ? data.success : data.message, type: payload.type })
                    return Promise.resolve(data)
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async userChange(context, payload) {
            try {
                const { data } = await httpClient.post('/user/authorization/change', { data: { ...payload.data } })

                if(data && !data.status) {
                    console.log(data)
                    // await commit('checkUserState', { data: data.success, type: payload.type })
                    return Promise.resolve(data)
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async connectedSocial(context, payload) {
            try {
                const { data } = await httpClient.post('/user/authorization/change/social', { data: { ...payload.data } })

                if(data && !data.status) {
                    console.log(data)
                    // await commit('checkUserState', { data: data.success, type: payload.type })
                    return Promise.resolve(data)
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async removeConnectedSocial(context, payload) {
            try {
                const { data } = await httpClient.post('/user/authorization/delete/social', { data: { ...payload.data } })

                if(data && !data.status) {
                    console.log(data)
                    // await commit('checkUserState', { data: data.success, type: payload.type })
                    return Promise.resolve(data)
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
    },
    mutations: {
        checkUserState(state, { data, type }) { state.authData[type] = data }
    },
    getters: {
        getAuthCheckPassword: s => s.authData.checkPassword,
        getAuthChangePassword: s => s.authData.checkChangePassword,
        getAuthEmailState: s => s.authData.checkSuccessPhone
    }
}