import axios from 'axios'
import httpClient from "@/services/http-client/http-client"
import { PROTOCOL } from '@/domain'

export default {
    state: {
        core_lists: {
            type_documents: [],
            source_attractions: [],
            type_warehouse: [],
            type_deliveries: [],
            status_product: [],
            status_payment: [],
            terms_payment: [],
            form_payments: [],
            delivery_methods: [],
            counterparty_type: [],
            importance: [],
            condition: [],
            type_prices: [],
            types_contract: [],
            status_contracts: [],
            sex: [],
        },
        lists: {
            employees: [],
            users: [],
            individuals: [],
            individuals_documents: [],
            counterparties_contracts: [],
            counterparties: [],
            companies_departments: [],
            organizations: [],
            employee_documents: [],
            counterparties_returns: [],
            counterparties_cancellations: [],
            departments: [],
            countries: [],
            cities: {},
            regions: [],
            units: [],
            prices_types: {},
            positions: [],
            currencies: [],
            suppliers: []
        },
    },
    mutations: {
        clearList(state, { type, resources }) { state[type][resources] = [] },
        updateLists(state, { type, data, resources }) {
            if(type === 'core') {
                state[`${type.trim()}_lists`][resources] = data
            } else {
                let { list } = data
                state['lists'][resources] = list
            }
        },
        updateListsWithPaginate(state, { type, data, resources, page }) {
            if(type === 'core') {
                state[`${type.trim()}_lists`][resources] = [ ...state[`${type.trim()}_lists`][resources], ...data ]
            } else {
                let { list, total_page } = data
                console.log(list, total_page, page)
                if(list.length && page <= total_page) {
                    state['lists'][resources] = [ ...state['lists'][resources], ...list ]
                } else if(!list.length && page > total_page) {
                    state['lists'][resources] = [ ...state['lists'][resources] ]
                } else {
                    state['lists'][resources] = list
                }
            }
        }
    },
    actions: {
        resetList({ commit }, { type, resources }) { commit('clearList', { type, resources }) },
        async fetchLists(context, { type, resources }) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            const checkType = type.length ? `${type}/` : ''
            try {
                const { data: { data } } = await axios.get(`${PROTOCOL}://${domain}/api/v1/directories/${checkType}${resources}/list`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                context.commit('updateLists', { type, data, resources })
            } catch(e) {
                throw new Error(e)
            }
        },
        async fetchList({ commit }, { type, resources, directory, query, paginate, page }) {
            const checkType = type.length ? `${type}/` : ''
            const checkQuery = query.length ? query : ''
            const checkDirectory = directory.length ? `${directory}/` : ''
            try {
                const { data } = await httpClient.get(`${checkDirectory}${checkType}${resources}/list${checkQuery}`)
                console.log(type, data, resources)
                if(!paginate) {
                    if(data && !data.status) await commit('updateLists', { type, data: data.data, resources })
                    else console.log('status', data.status)
                } else {
                    if(data && !data.status) await commit('updateListsWithPaginate', { type, data: data.data, resources, page })
                    else console.log('status', data.status)
                }
            } catch(e) {
                throw new Error(e)
            }
        }
    },
    getters: {
        getLists: s => type => s[type]
    }
}
