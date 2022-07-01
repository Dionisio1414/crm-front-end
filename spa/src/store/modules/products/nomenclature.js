import axios from "axios";
import { httpClient } from "@/services";
import { PROTOCOL } from '@/domain';

export default {
    state: {
        nomenclatureItems: null,
        nomenclatureItem: {},
        itemsToSelect: null,
        selectedItems: null,
        nomenclatureSuppliers: {
            list: {
                full_names: [],
            }
        },
        pagination: {
            page: 1,

        }
    },
    getters: {
        getProducts: state => state.products,
        itemsToSelect: state => state.itemsToSelect,
        selectedItems: state => state.selectedItems,
        nomenclatureItems: state => state.nomenclatureItems,
        nomenclatureItem: state => state.nomenclatureItem,
        product: state => state.product,
        service: state => state.service,
        getServices: state => state.services,
        pagination: state => state.pagination,
        getNomenclatureSuppliers: state => state.nomenclatureSuppliers.list.full_names || [],
    },
    actions: {
        async resetPagination(context) {
            await context.commit('resetAllPagination')
        },

        async fetchItemsForSelect(context, {id}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const items = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/get-select-related-products/${id}`,
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })

                context.commit('setItemsToSelect', items.data.data)
            } catch (e) {
                console.log(e);
            }

        },


        async addChooseSelectedItems(context, data) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const items = await axios.post(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/selected-related-nomenclatures`,
                    {data},
                    {headers: {'Authorization': `Bearer ${token}`}})

                context.commit('setSelectedItems', items.data.data)
            } catch (e) {
                console.log(e);
            }

        },
         async fetchItems({commit}, { resources }) {
          const url = `products/nomenclatures/get-${resources}-all`
            try {
                const { data } = await httpClient.get(url)

                if (data) {
                    commit('setItems', { items: data.data, resources })
                }
            } catch (e) {
                console.log(e);
            }
        },
        async loadMoreItems(context, {resource, page}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const items = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/get-${resource}-all?page=${page}`,
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })

                context.commit('addItems', {items: items.data.data, resource})
                context.commit('setPaginate', {page})
            } catch (e) {
                console.log(e);
            }

        },
        async fetchCategoryItems(context, {resources, id}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const items = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/get-${resources}/${id}`,
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })

                context.commit('setItems', {items: items.data.data})
            } catch (e) {
                console.log(e);
            }

        },
        async fetchNomenclatureSuppliersList({ commit }) {
            try {
                const { data } = await httpClient.get('suppliers/list')
                if(data && !data.status) {
                    commit('fetchSuppliers', {items: data.data})
                }
            } catch (e) {
                console.log(e);
            }

        },
        async moveProducts(context, {id, data}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                await axios.put(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/move-products/${id}`,
                    {data},
                    {headers: {'Authorization': `Bearer ${token}`}})
            } catch (e) {
                console.log(e);
            }

        },
        async fetchItem(context, {resource, id}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const item = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/get-${resource.SINGLE_VALUE}/${id}`,
                    {
                        headers: {'Authorization': `Bearer ${token}`}
                    })
                context.commit('setItem', {item: item.data.data, resource})
                return item.data.data
            } catch (e) {
                console.log(e);
            }

        },
        async createNomenclatureItem({commit}, { data, isGroup, resource }) {
            const isGroupUrl = isGroup ? '-group' : ''
            try {
                const { data: item } = await httpClient.post(`products/nomenclatures/store${isGroupUrl}-${resource}`, data);

                if (item) {
                    await commit('addNomenclatureItem', { item: item.data })
                    return item.data
                }
            } catch (e) {
                console.log(e);
            }
        },

        async updateNomenclatureItem(context, {data, resource, parentId, isChild}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const changedItem = await axios.put(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/update-${resource}/${data.id}`,
                    {...data},
                    {headers: {'Authorization': `Bearer ${token}`}})
                if (isChild) {
                    const params = {
                        changedItem: changedItem.data.data,
                        parentId,
                    }

                    context.commit('setNomenclatureChildItem', params)
                } else {
                    context.commit('setNomenclatureItem', changedItem.data.data)
                }
            } catch (e) {
                console.log(e);
            }
        },


        async changeVisibility({ commit }, data) {
            const visibility = data.is_visible ? 'out' : 'to';
            const itemsToChange = data.isGroup ? [{id: data.id}, ...data.products] : [{id: data.id}];
            const items = itemsToChange.map(item => ({ id: item.id }));

            try {
                await httpClient.post(`products/nomenclatures/${visibility}Visibility`,{nomenclatures: items})
                if (data.isGroup) {
                    commit('changeGroupVisibility', {id: data.id, is_visible: !data.is_visible})
                } else if (data.isChild) {
                    commit('changeChildVisibility', {
                        id: data.id,
                        is_visible: !data.is_visible,
                        parentId: data.parentId
                    })
                } else {
                    commit('changeVisibility', {id: data.id, is_visible: !data.is_visible})
                }
            } catch (e) {
                console.log(e);
            }
        },
        async changeVisibilityGroup({ commit }, data) {
            const visibility = data.visible ? 'to' : 'out';
            const items = data.items.map(item => ({ id: item.id }));
            try {
                await httpClient.post(`products/nomenclatures/${visibility}Visibility`,{ nomenclatures: items });
                commit('changeGroupVisibility', data)
            } catch (e) {
                console.log(e);
            }
        },
        async changeVisibilityChild(context, data) {
            const visibility = data.visible ? 'to' : 'out';
            const items = data.items.map(item => ({ id: item.id }));

            try {
                await httpClient.post(`products/nomenclatures/${visibility}Visibility`,{ nomenclatures: items })
            } catch (e) {
                console.log(e);
            }
        },

        async createProduct(context, {data, isGroup}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            const isGroupUrl = isGroup ? '-group' : ''
            try {
                const item = await axios.post(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/store${isGroupUrl}-product`,
                    {...data},
                    {headers: {'Authorization': `Bearer ${token}`}})
                context.commit('addItem', {item: item.data.data, resources: 'products'})
                return item.data.data
            } catch (e) {
                console.log(e);
            }
        },
        changeNomenclatureHeaders ({commit}, data) {
            commit('changeNomenclatureHeaders', data)
        },
        async createService(context, {data}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const item = await axios.post(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/store-service`,
                    {...data},
                    {headers: {'Authorization': `Bearer ${token}`}})
                console.log(item);
                context.commit('addItem', {item: item.data.data, resources: 'services'})
            } catch (e) {
                console.log(e);
            }
        },

        async deleteItems({ commit }, items) {
            const body = { nomenclatures: items }

            try {
                const { data } = await httpClient.post('products/nomenclatures/toArchive', body);
                console.log(data)

                if (data && !data.status) {
                    const childItems = items.filter(item => item.isChild)
                    const notChildItems = items.filter(item => !item.isChild)

                    commit('delItems', notChildItems)
                    commit('delChildItems', childItems)
                }

            } catch (e) {
                console.log(e);
            }
        },
        async deleteItem({ commit }, item) {
            try {
                await httpClient.post('products/nomenclatures/toArchive',{nomenclatures: item});

                if (item.isChild) {
                    commit('delChildItems', [item])
                } else {
                    commit('delItems', item)
                }

            } catch (e) {
                console.log(e);
            }
        },
        async outActualItem({ commit }, item) {
            try {
                await httpClient.post('products/nomenclatures/outActual',{nomenclatures: [{id: item.id}]})

                if (item.isChild) {
                    commit('delChildItems', [item])
                } else {
                    commit('delItems', [item])
                }
            } catch (e) {
                console.log(e);
            }
        },
        async outActualItems({ commit }, items) {
            try {
                await httpClient.post('products/nomenclatures/outActual',{nomenclatures: items.map(item => ({id: item.id}))})

                const childItems = items.filter(item => item.isChild);
                const notChildItems = items.filter(item => !item.isChild);

                commit('delItems', notChildItems)
                commit('delChildItems', childItems)
            } catch (e) {
                console.log(e);
            }
        },
        async toActualItem(context, item) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                await axios.post(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/toActual`,
                    {nomenclatures: [{id: item.id}]},
                    {headers: {'Authorization': `Bearer ${token}`}})

                if (item.isChild) {
                    context.commit('delChildItems', [item])
                } else {
                    context.commit('delItems', [item])
                }
            } catch (e) {
                console.log(e);
            }
        },
        async toActualItems(context, items) {
            console.log(items);
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                await axios.post(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/toActual`,
                    {nomenclatures: items.map(item => ({id: item.id}))},
                    {headers: {'Authorization': `Bearer ${token}`}})

                const childItems = items.filter(item => item.isChild)
                const notChildItems = items.filter(item => !item.isChild)
                context.commit('delItems', notChildItems)
                context.commit('delChildItems', childItems)
            } catch (e) {
                console.log(e);
            }
        },
        async changeNomenclaturePrice(context, {id, value, priceId, isChild, isGroup, price, parentId, currency}) {
            console.log(isChild, isGroup, parentId);
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const {data} = await axios.put(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/change-price-nomenclature`,
                    {id: priceId, value},
                    {headers: {'Authorization': `Bearer ${token}`}})
                const res = data.data
                if (res.status !== 'error') {
                    context.commit(`change${isChild ? 'Child' : ''}NomenclaturePrice`, {
                        value,
                        id,
                        price,
                        parentId,
                        isGroup,
                    })
                }
                return {...res, currency}
            } catch (e) {
                console.log(e);
            }
        },
        async changeMinPrice({ commit }, { id, value, isGroup, isChild, parentId }) {
            try {
                await httpClient.put(`/products/nomenclatures/change-min_price-nomenclature/${id}`,{ value })

                commit(`change${isChild ? 'Child' : ''}NomenclaturePrice`, {
                    value,
                    id,
                    price: 'min_price',
                    parentId,
                    isGroup,
                })
            } catch (e) {
                console.log(e);
            }
        },
        async changeGroupsProduct(context, {data, id, groupId}) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            console.log(data);
            try {
                const item = await axios.put(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/update-product/${id}`,
                    {...data},
                    {headers: {'Authorization': `Bearer ${token}`}})
                console.log(item);
                context.commit('setGroupsProduct', {newItem: item.data.data, groupId})
            } catch (e) {
                console.log(e);
            }
        },
        async generateProducts(context, data) {
            try {
                const products = await httpClient.post('products/nomenclatures/generate',{...data});

                return products.data.data
            } catch (e) {
                console.log(e);
            }
        },
        async addGroupProducts({ commit }, {products, id}) {
            try {
                const { data } = await httpClient.post(`products/nomenclatures/store-groups-product/${id}`,{products});
                if (data && !data.status) {
                    commit('setProduct', data.data);
                }

            } catch (e) {
                console.log(e);
            }
        },
        async saveNomenclatureTableSettings(context, data) {
            const token = await context.rootState.auth.token
            const domain = await context.rootState.auth.user.company.domain
            // const protocol = await context.rootState.auth.protocol
            try {
                const headers = await axios.put(`${PROTOCOL}://${domain}/api/v1/products/nomenclatures/product/headers`,
                    {data},
                    {headers: {'Authorization': `Bearer ${token}`}}
                )
                context.commit('changeHeaders', {headers})
            } catch (e) {
                console.log(e)
            }
        },
    },
    mutations: {
        changeNomenclatureHeaders (state, headers) {
           state.nomenclatureItems.headers = headers
        },
        changeHeaders(state, data) {
            state.products.headers = data
        },
        setItemsToSelect(state, items) {
            state.itemsToSelect = items
        },
        setNomenclatureItem(state, changedItem) {
            const index = state.nomenclatureItems.body.findIndex(item => item.id === changedItem.id)
            state.nomenclatureItems.body.splice(index, 1, changedItem)
        },
        setNomenclatureChildItem(state, {parentId, changedItem}) {
            const parent = state.nomenclatureItems.body.find(item => item.id === parentId)
            const index = parent.groups.findIndex(item => item.id === changedItem.id)
            parent.groups.splice(index, 1, changedItem)
        },
        setSelectedItems(state, items) {
            if (state.selectedItems) {
                state.selectedItems.body.push(...items.body)
            } else {
                state.selectedItems = items
            }

        },
        changeGroupVisibility(state, { id, is_visible }) {
            const group = state.nomenclatureItems.body.find(item => item.id === id)
            group && group.groups.forEach(groupItem => {
                groupItem.cells.is_visible = is_visible
            })
        },
        changeChildVisibility(state, {id, is_visible, parentId}) {
            const group = state.nomenclatureItems.body.find(item => item.id === parentId)
            const item = group.groups.find(item => item.id === id)
            item.cells.is_visible = is_visible
        },
        changeVisibility(state, {id, is_visible}) {
            const item = state.nomenclatureItems.body.find(item => item.id === id)
            item.cells.is_visible = is_visible
        },
        fetchSuppliers(state, {items}) {
            state.nomenclatureSuppliers = items
        },
        changeNomenclaturePrice(state, {price, value, isGroup, id}) {
            const item = state.nomenclatureItems.body.find(item => item.id === id)
            if (isGroup) {
                item.groups = item.groups.forEach(child => {
                    child.cells[price] = +value
                })
            }
            item.cells[price] = +value
        },
        changeChildNomenclaturePrice(state, {price, value, parentId, id}) {
            const parent = state.nomenclatureItems.body.find(item => item.id === parentId)
            const item = parent.groups.find(item => item.id === id)
            item.cells[price] = +value
        },
        changeNomenclatureMinPrice(state, {price, value, isGroup, id}) {
            const item = state.nomenclatureItems.body.find(item => item.id === id)
            if (isGroup) {
                item.groups = item.groups.forEach(child => {
                    child.cells[price] = +value
                })
            }
            item.cells[price] = +value
        },
        changeChildNomenclatureMinPrice(state, {price, value, parentId, id}) {
            const parent = state.nomenclatureItems.body.find(item => item.id === parentId)
            const item = parent.groups.find(item => item.id === id)
            item.cells[price] = +value
        },
        setItems(state, {items}) {
            state.nomenclatureItems = items
        },

        setPaginate(state, {page}) {
            state.pagination.page = page
        },
        addItems(state, {items}) {
            state.nomenclatureItems.body.push(...items.body)
        },
        resetAllPagination(state) {
            state.pagination.page = 1
        },
        setItem(state, {item}) {
            state.item = item
        },
        setProduct(state, newItem) {
            const index = state.nomenclatureItems.body.findIndex(item => item.id === newItem.id)
            state.nomenclatureItems.body.splice(index, 1, newItem)
        },
        setGroupsProduct(state, {newItem, groupId}) {
            const product = state.nomenclatureItems.body.find(item => item.id === groupId)
            const index = product.groups.findIndex(item => item.id === newItem.id)
            product.groups.splice(index, 1, newItem)
        },
        setService(state, newItem) {
            const index = state.nomenclatureItems.body.findIndex(item => item.id === newItem.id)
            state.nomenclatureItems.body.splice(index, 1, newItem)
        },
        // addItem(state, {item, resources}) {
        //     state[resources].body.unshift(item)
        // },
        addNomenclatureItem(state, {item}) {
            state.nomenclatureItem = item
            state.nomenclatureItems.body.unshift({...item})
        },
        delItems(state, items) {
            const itemsArr = items.map(deletedItem => deletedItem.id)
            state.nomenclatureItems.body = state.nomenclatureItems.body.filter(item => !itemsArr.includes(item.id))
        },
        delChildItems(state, items) {
            const groupsContainer = state.nomenclatureItems.body
            items.forEach(item => {
                const itemGroup = groupsContainer.find(group => group.id === item.parentId)
                if (!itemGroup) {
                    return false
                }
                const index = itemGroup.groups
                    .findIndex(groupItem => groupItem.id === item.id)
                itemGroup.groups.splice(index, 1)
            })
        }
    }
}

