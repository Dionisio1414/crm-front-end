import axios from "axios"
import { PROTOCOL } from '@/domain'

export default {
    state: {
        company_loading: true,
        organizations: {},
        companies_departments: {},
        employees_list: {},
        async_validate: false,
        paginateCounter: 1
    },
    mutations: {
        updateValid(state, payload) {
            if(!payload.success)
                state.async_validate = false
            else
                state.async_validate = true
        },
        updateCompanies(state, { type, items }) {
            console.log(items)
            items.list.sort((a, b) => b.is_default - a.is_default)
            state[type] = items
            state.company_loading = false
            console.log('state loader end', state.company_loading)
        },
        updatePaginateCounter(state, payload) {
            if(payload) state.paginateCounter = 1
            else state.paginateCounter++
        },
        updateCompaniesWithPaginate(state, { type, items }) {
            console.log('state', state[type].list)
            state[type].list = [ ...state[type].list, ...items.list ]
        },
        changeItem(state, { items, type }) {
            let currentElement = state[type].list.findIndex(item => item.id === items.id)
            state[type].list[currentElement] = items 
        },
        createItemList(state, { type, items: { data } }) {
            console.log('createItemList', data)
            state[type].list.push(data)
        },
        changeDefaultItem(state, {id, resource}) {
            const listItem = state[resource].list
            const isDefaultIndex = listItem.findIndex(item => !!item.is_default)
            const isNewDefaultIndex = listItem.findIndex(item => item.id === id)
            listItem[isDefaultIndex].is_default = false
            listItem[isNewDefaultIndex].is_default = true
            listItem.sort((a, b) => b.is_default - a.is_default)
        },
        getEmployeesList(state, list) {
            state.employees_list = list
            state.company_loading = false
        }
    },
    
    actions: {
        updatePaginateCounter({ commit }, mark) {
            commit('updatePaginateCounter', mark)
        },
        async changeDefaultList(context, { id, resource }) {
            await context.commit('changeDefaultItem', { id, resource })
        },
        async fetchCompaniesWithPaginate(context, { type, page }) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            try {
                // context.state.company_loading = true
                const items = (await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/${type}?page=${page}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })).data.data
                console.log('items action', items.list)
                await context.commit("updateCompaniesWithPaginate", { type, items }) 
            } catch (e) {
                console.log(e)
            }
        },
        async fetchCompanies(context, type) {
            context.state.company_loading = false
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                context.state.company_loading = true
                const items = (await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/${type}?paginate=13`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })).data.data
                // console.log('items', items)
                await context.commit("updateCompanies", { type, items }) 
            } catch (e) {
                console.log(e)
            }
        },
        async updateCompanies(context, { data, type }) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            console.log('updateCompanies data', data, type)
            try {
                const { data:items }= await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${type}/`, 
                    {...data },
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    }
                )
                console.log('updateCompanies item', items)
                // await context.commit("createItemList", { type, items }) 
                await context.dispatch('fetchCompanies', type)
            } catch (e) {
                console.log(e)
            }
        },
        async deleteCompanies(context, { type, data }) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            console.log('deleteCompanies data', data)
            try {
                context.state.company_loading = true
                await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${type}/toArchive`, 
                    { data: {...data } },
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                await context.dispatch('fetchCompanies', type)
            } catch (e) {
                console.log(e)
            }
        },
        async changeCompanyItem(context, {data, type, id}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                context.state.company_loading = true
                console.log('state loader begin', context.state.company_loading)
                const { data: { data:items } } = await axios.put(`${PROTOCOL}://${domain}/api/v1/directories/${type}/list/${id}`,
                    { ...data },
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                console.log('Change action', data, type, context, items)
                await context.commit('changeItem', { items, type })
                await context.dispatch('fetchCompanies', type)
            } catch (e) {
                console.log(e)
            }
        },
        async fetchEmployees(context, id) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol 
            try {
                context.state.company_loading = false
                const { data: { data } } = await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/employees/list/?without_company_department_id=${id}`,
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                console.log('Change action', data, id)
                context.commit('getEmployeesList', data)
            } catch (e) {
                console.log(e)
            }
        },
        async createAsyncValidation({ rootState }, { type, obj }) {
            const token = await rootState.auth.token
            const domain = await rootState.auth.user.company.domain
            try {
                const { data } = await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${type}/store-async-validations`,
                    {  validate: { ...obj } },
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                // commit('updateValid', data)
                return data
            } catch (e) {
                console.log(e)
            }
        },
     },
    getters: {
        list: s => resource => s[resource]['list'],
        employeesList: s => s.employees_list,
        companyLoading: s => s.company_loading,
        isAsyncValidate: s => s.async_validate,
        getCompaniesPaginateCounter: s => s.paginateCounter
    }
}