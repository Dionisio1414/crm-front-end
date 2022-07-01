import httpClient from "@/services/http-client/http-client"

export default {
    state: {
        files: {
            list: [],
            total: null
        },
        currentFile: null,
        paginationFilesCounter: 1,
    },
    mutations: {
        updateFiles(state, { data, total }) {
            state.files.list = data
            state.files.total = total
        },
        appendNewFile(state, payload) {
            state.files.list.unshift(payload)
        },
        deleteFile(state, { data }) {
            const currentItemIndex = state.files.list.findIndex(item => item.id === data[0].id)
            state.files.list.splice(currentItemIndex, 1)
        },
        updatePaginationFilesCounter(state, payload) {
            if(payload) state.paginationFilesCounter = 1
            else state.paginationFilesCounter++
        },
        updateFilesPagination(state, { data, total }) {
            console.log('updateFIlesPagination', data, total)
            state.files.list = [...state.files.list, ...data]
            state.files.total = total
        },
        updateCurrentFile(state, payload) {
            state.currentFile = payload
        }
    },
    actions: {
        paginationFilesCounter({ commit }, mark) {
            commit('updatePaginationFilesCounter', mark)
        },
        async updateFetchFiles({ commit }, payload) {
            try {
                const { data } = await httpClient.get(`file_manager/list?paginate=12&page=${payload}`)

                if(data && !data.status) {
                    await commit('updateFilesPagination', { data: data.data.list, total: data.data.total })
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async fetchCurrentFile(context, id) {
            try {
                const { data } = await httpClient.get(`file_manager/file/${id}`)

                if(data && !data.status) {
                    console.log(data.data, context)
                    // await commit('updateCurrentFile', { ...data.data })
                    return Promise.resolve({ ...data.data })
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async fetchFiles({ commit }, payload = {}) {
            const { isSearch, searchValue } = payload
            const checkSearch = isSearch && searchValue ? `&s=${searchValue}` : ""
            try {
                const { data } = await httpClient.get(`file_manager/list?paginate=12${checkSearch}`)

                if(data && !data.status) {
                    await commit('updateFiles', { data: data.data.list, total: data.data.total })
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async addFile({ dispatch }, { payload, mode }) {
            try {
                const { data } = await httpClient.post(`file_manager/file`, { data: { ...payload } } )
                console.log('mode', mode, data)
                if(data && !data.status) {
                    // await commit('appendNewFile', data.data)
                    await dispatch('fetchFiles')
                    return Promise.resolve(data)
                } else {
                    console.log('status', data.status)
                    await Promise.reject(data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
        async deleteFile({ commit }, payload) {
            try {
                const { data } = await httpClient.post(`file_manager/delete`, payload)

                if(data && !data.status) {
                    await commit('deleteFile', payload)
                } else {
                    console.log('status', data.status)
                }

            } catch(e) {
                console.log(e)
            }
        },
    },
    getters: {
        getCurrentFile: s => s.currentFile,
        getFiles: s => s.files.list,
        getPaginationFilesCounter: s => s.paginationFilesCounter,
        getFilesTotal: s => s.files.total
    }
}