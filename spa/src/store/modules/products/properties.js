import axios from "axios"
import {httpClient} from "@/services";
// import Api from "../../../services/API";
import { PROTOCOL } from '@/domain'

export default {

    state: {
        properties: [],
        categoryProperties: [],
        enableSort: true,
        clone: null,
        changeItem: false,
        // domain: JSON.parse(localStorage.getItem('domain'))
    },
    mutations: {
        setProperty(state, value) {
            const property = this.state.properties.properties.find(property => property.id === +value.property_id)
            property.property_value.push(value)
        },
        removeProperty(state, index) {
            let deleted = []
            if (isNaN(index)) {
                const updateArray = state.properties.filter(prop => {
                    if (!prop.check) {
                        return true
                    } else {
                        deleted.push(prop.id)
                    }
                })
                console.info('Deleted properties', deleted)
                state.properties = updateArray
            } else {
                state.properties.splice(index, 1)
            }
        },
        updateCategoryProperties(state, items) {
            state.categoryProperties = items
        },
        cloneProperty(state, data) {
            let clone = {}
            for (let key in data.element) {
                clone[key] = data.element[key]
                clone.title = `${data.element.title} (Копия)`
            }
            state.clone = clone
            // state.properties.splice(data.index, 0 , clone)
            // setTimeout(() => {
            //   state.changeItem = true
            // },500)
        },
        clearClone(state) {
            state.clone = null
            state.changeItem = false
        },
        updateProperties(state, payload) {
            state.properties = payload
        },
        addProperty(state, property) {
            if (property) state.properties.splice(2, 0, property)
        },
        async updateProperty(state, property) {
            const id = property.id
            const title = property.title
            const values = property.values
            const properties = state.properties.concat()
            const idx = properties.findIndex(p => p.id === id)
            const prop = properties[idx]
            properties[idx] = {...prop, title, values}
            state.properties = properties
        },
        sortProperties(state) {
            if (state.enableSort) {
                state.properties.sort((a, b) => a.title.localeCompare(b.title))
                state.enableSort = false
            } else {
                state.properties.sort((b, a) => a.title.localeCompare(b.title))
                state.enableSort = true
            }
        }
    },
    actions: {
        clearClone({commit}) {
            commit('clearClone')
        },
        sortProperties({commit}) {
            commit('sortProperties')
        },
        async updateProperty(context, property) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            const values = property.values.map(val => ({title: val.title, id: val.id}))
            try {
               const updatedProperty =  await axios.put(`${PROTOCOL}://${domain}/api/v1/products/properties/${property.id}`, {
                    // id: property.id,
                    title: property.title,
                    values: values
                }, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                console.log(updatedProperty.data.data);
                context.commit('updateProperty', updatedProperty.data.data)
                return updatedProperty.data.data
            } catch (e) {
                console.log(e)
            }
        },
        cloneProperty({commit}, data) {
            console.log(data)
            commit('cloneProperty', data)
        },

        async removeProperty({commit}, [removeArrayID, index]) {
            const properties = removeArrayID;
            try {
                await httpClient.post('products/properties/toArchive', { properties });
                commit('removeProperty', index);
            } catch (e) {
                console.log(e)
            }
        },

        async fetchProperties(context) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            try {
                const {data: properties} = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/properties`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })

                await context.commit("updateProperties", properties.data)
            } catch (e) {
                console.log(e)
            }
        },
        async fetchCategoriesProperties(context, id) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            try {
                const {data: properties} = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/properties/${id}/properties-category`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })

                await context.commit("updateCategoryProperties", properties.data)
            } catch (e) {
                console.log(e)
            }
        },
        async updatePropertiesList(context, payload) {
            context.commit("updateProperties", payload)
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            try {
                await axios.post(`${PROTOCOL}://${domain}/api/v1/products/properties/sortProperties`, {
                    properties: payload
                }, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
            } catch (e) {
                console.log(e)
            }
        },
        async createProperty({ commit }, property) {
            const values = property.property_value.map(val => val.title);

            try {
                const { data } = await httpClient.post(`products/properties`, {
                    id: property.id,
                    title: property.title,
                    values: values
                })

                await commit('addProperty', data.data)
                return data.data
            } catch (e) {
                console.log(e)
            }
        },
        async addPropertyValue({commit, rootState}, {id, title}) {
            const token = await rootState.auth.token
            const domain = await rootState.auth.user.company.domain
            try {
                const updatedProperty = await axios.post(`${PROTOCOL}://${domain}/api/v1/products/properties/${id}/add-property-value`, {title}, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                // const updatedProperty = await Api().post(`/products/properties/${id}/add-property-value`, {title})
                commit('setProperty', updatedProperty.data.data)

            } catch (e) {
                console.log(e);
            }


        }
    },
    getters: {
        categoryProperties: s => s.categoryProperties,
        properties: s => s.properties,
        clone: s => s.clone,
        changeItem: s => s.changeItem,
        brand: state => state.properties.find(property => property.id === 1) || null,
        model: state => state.properties.find(property => property.id === 2) || null
    }

}
