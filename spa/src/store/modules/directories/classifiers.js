import axios from "axios"
// import { MAIN_DOMAIN } from '@/domain'
import { PROTOCOL } from '@/domain'

export default {
    state: {
        currencies: {},
        countries: {},
        units: {},
        cities: {},
        departments: {},
        counterparties_cancellations: {},
        counterparties_returns: {},
        counterparties_contracts: {},
        individuals_documents: {},
        positions: {},
        employee_documents: {},
        employees: {},
        users: {},
        counterparties: {},
        directoriesLists: {
            units: [],
            countries: [],
        },
        regions: {},
        prices_types: {},
        paginationCounter: 1,
    },
    getters: {
        paginationCounter: state => state.paginationCounter,
        getTypeOfPrices: state => state.prices_types,
        getCountries: (state) => state.countries,
        getCurrency: (state) => state.currencies,
        getMeasurementsPoint: (state) => state.units,
        getCities: (state) => state.cities,
        getDepartments: (state) => state.departments,
        getPositions: (state) => state.positions,
        getEmployeesDocuments: s => s.employee_documents,
        getCounterpartiesCancellations: s => s.counterparties_cancellations,
        getCounterpartiesReturns: s => s.counterparties_returns,
        getEmployees: s => s.employees,
        getUsers: s => s.users,
        getRegions: s => s.regions,
        getClassifiersItems: s => resources => s[resources],
        // getClassifiersLastItem: s => resource => s[resource].body.slice().shift()
    },
    actions: {
        changeDefaultItem({commit}, {id, resources}) {
            commit('setDefaultItem', {id, resources})
        },
        getPaginationCounter({ commit }, mark) {
            commit('updatePaginationCounter', mark)
        },
        async getPaginationItems(context, {page, resources}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            const items = (await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/${resources}/table?page=${page}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })).data.data
            await context.commit('updatePagination', { items: items.body, resources })
        },
        async createClassifiersItem(context, {data, resources, type}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            if(data.id) {
                delete data.id
            }
            console.log('createClassifiersItem resources', resources, type)
            try {
                const item = await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${resources}/list`,
                    {...data},
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                console.log('item.data.data', item.data.data)
                await context.dispatch('fetchClassifiersItems', resources)
                // console.log('resources', resources)
                // context.commit('addClassifiersItem', {item: item.data.data, resources: type ? type : resources})
                // context.commit('setItemsQuantity', {inc: 1 , resources: type ? type : resources})
            } catch (e) {
                console.log(e)
            }
        },
        async changeClassifiersItem(context, {data, resources, id}) {
            try {
                const token = await context.rootState.auth.token
                const domain = await context.rootState.auth.user.company.domain
                // const protocol = await context.rootState.auth.protocol
                const idx = data.id ? data.id : id
                console.log('idx', idx)
                await axios.put(`${PROTOCOL}://${domain}/api/v1/directories/${resources}/list/${idx}`,
                    {...data},
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                console.log('Change action', data, resources)
                // await context.commit('setClassifiersItem', {data, resources, id})
                await context.dispatch('fetchClassifiersItems', resources)

            } catch (e) {
                console.log(e)
            }
        },
        async deleteClassifiersItems(context, {resources, itemsToDelete}) {
            // console.log('itemsToDelete', data, context.state[resources].body)
            // context.state[resources].body.map((item => { 
            //     // !data.includes(item.id)
            //     console.log('items', !data.includes(item.id), item.id)
            // }))
            // console.log('items', items)
            // console.log('state', context.state[resources].body)
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${resources}/toArchive`,
                    {...itemsToDelete},
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                // context.commit('satClassifiersTableItems', {items, resources})  
                await context.dispatch('fetchClassifiersItems', resources)
                // await context.commit('setItemsQuantity', {inc: -itemsToDelete.data.length, resources})
            } catch (e) {
                console.log(e);
            }
        },
        async fetchClassifiersItems(context, resources) {
            console.log('resources', resources)
            try {
                const token = await context.rootState.auth.token
                const domain = await context.rootState.auth.user.company.domain
                // const protocol = await context.rootState.auth.protocol
                const items = (await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/${resources}/table`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })).data.data
                console.log('items', items)
                context.commit('setClassifiersItems', {items, resources})
            } catch (e) {
                console.log(e);
            }
        },
        async filteredEmployees({ rootState, commit }, { resource, id }) {
            const token = await rootState.auth.token
            const domain = await rootState.auth.user.company.domain
            // const protocol = await rootState.auth.protocol
            let checkType;
            if(id === 2 || id === 3) checkType = `?active_employees=${id === 2 ? false : true}`
            else if(id === 4) checkType = `?invited_users=true`
            else checkType = ''
            console.log('checkType', checkType)
            try {
                const { data: { data:items } } = await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/${resource}/table${checkType}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                console.log('items', items)
                commit('setClassifiersItems', { items, resources: resource })
            } catch (e) {
                console.log(e)
            }
        },
        async fetchCities(context, resources) {
            try {                
                const token = await context.rootState.auth.token
                const domain = await context.rootState.auth.user.company.domain
                // const protocol = await context.rootState.auth.protocol
                const items = (await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/cities/table`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })).data.data
                console.log('data', items, resources)
                context.commit('setClassifiersItems', { items, resources })
            } catch(e) {
                throw new Error(e)
            }
        },
        async fetchDepartments(context, resources) {
            try {                
                const token = await context.rootState.auth.token
                const domain = await context.rootState.auth.user.company.domain
                // const protocol = await context.rootState.auth.protocol
                const items = (await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/departments/table`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })).data.data
                console.log('data', items, resources)
                context.commit('setClassifiersItems', { items, resources })
            } catch(e) {
                throw new Error(e)
            }
        },
        async employeesAsyncValidation({ rootState }, { type, obj }) {
            const token = await rootState.auth.token
            const domain = await rootState.auth.user.company.domain
            // const protocol = await rootState.auth.protocol 
            try {
                const { data } = await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${type}/store-async-validations`,
                    { validate: { ...obj } },
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                return data
            } catch (e) {
                console.log(e)
            }
        },
        async resetHeaders({ dispatch, rootState }, { resource }) {
            const token = await rootState.auth.token
            const domain = await rootState.auth.user.company.domain
            // const protocol = await rootState.auth.protocol 
            console.log(resource)
            try {
                await axios.post(`${PROTOCOL}://${domain}/api/v1/directories/${resource}/headers/toDefault`,
                    {},
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    }
                )
                await dispatch('fetchClassifiersItems', resource)
            } catch(e) {
                console.log(e)
            }
        },
        userSendInvite({ rootState }, payload) {
            const token = rootState.auth.token
            const domain = rootState.auth.user.company.domain
            // const protocol = rootState.auth.protocol 
            console.log('data data data data', payload)
            return new Promise((resolve, reject) => {
                axios.post(`${PROTOCOL}://${domain}/api/v1/user/send/invite`,
                    { data: { ...payload } },
                    { headers: {'Authorization': `Bearer ${token}`}}
                ).then(response => {
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            })
        }
    },
    mutations: {
        setItemsQuantity(state, {inc, resources}) {
            state[resources].total += inc
        },
        addClassifiersItem(state, {item, resources}) {
            console.log('addClassifiersItem item', item)
            console.log('addClassifiersItem resources', resources, state[resources])
            state[resources].body.unshift(item)
        },
        setClassifiersItem(state, {data, resources, id}) {
            console.log(state, data, resources)
            const body = state[resources].body
            const idx = data.id ? data.id : id
            const itemIndex = body.findIndex(item => item.id === idx)
            console.log(itemIndex, body)
            body.splice(itemIndex, 1, data)
        },
        setDefaultItem(state, {id, resources}) {
            const body = state[resources].body
            const isDefaultIndex = body.findIndex((item) => !!item.is_default)
            const isNewDefaultIndex = body.findIndex((item) => item.id === id)
            body[isDefaultIndex].is_default = false
            body[isNewDefaultIndex].is_default = true
        },
        satClassifiersTableItems(state, {items, resources}) {
            console.log(items, resources)
            state[resources].body = items
        },
        setClassifiersItems(state, { items, resources }) {
            state[resources] = items
        },
        updatePagination(state, { items, resources }) {
            console.log('updatePagination', state[resources].body, items)
            state[resources].body = [...state[resources].body, ...items]
        },
        updatePaginationCounter(state, payload) {
            if(payload) state.paginationCounter = 1
            else state.paginationCounter++
        },
    }
}