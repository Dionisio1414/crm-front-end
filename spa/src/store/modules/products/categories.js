import httpClient from "@/services/http-client/http-client";
import getFlatMap from "@/helper/getFlatMap";

export default {
    state: {
        categories: null,
        category: null,
        editCategory: null,
        dataCreate: null,
        dataEdit: null,
        IDCreatedCategory: null,
        categoryValid: true,
        flatCategoriesList: null,
    },
    mutations: {
        updateCategoryValid(state, payload) {
            state.categoryValid = payload;
        },
        updateFlatCategoriesList(state, payload) {
            state.flatCategoriesList = payload;
        },
        resetCategoryValidation (state) {
            state.categoryValid = true;
        },
        updateCategories: (state, payload) => {
            state.categories = payload
        },
        resetCategory(state) {
            state.category = null
        },
        setCategory: (state, payload) => {
            state.category = payload
        },
        setEditCategory: (state, payload) => {
            state.editCategory = payload
        },
        setDataCreate(state, payload) {
            state.dataCreate = payload
        },
        setDataEdit(state, payload) {
            state.dataEdit = payload
        },
        clearDataEdit(state) {
            state.dataEdit = null
        },
        setIDCreatedCategory(state, payload) {
            state.IDCreatedCategory = payload
        },
        addCategory(state, category) {
            state.categories.unshift(category)
        },
    },
    actions: {
        resetCategoryValidation ({commit}) {
            commit('resetCategoryValidation')
        },
        async categoryValidationUpdate ({commit}, {id, params}) {
            try {
                const {data} = await httpClient.post('/products/categories/update-async-validations', {
                    id,
                    validate: {...params}
                });
                commit('updateCategoryValid', !data.status);

            } catch (e) {
                console.log(e);
            }
        },

        async categoryValidationCreate({commit}, params) {
            try {
                const {data} = await httpClient.post('/products/categories/store-async-validations', {validate:{...params}});
                commit('updateCategoryValid', !data.status);

            } catch (e) {
                console.log(e);
            }
        },

        /* Edit characteristics */
        async editCharacteristic(context, element) {
            let url
            if (element.id === 1) {
                url = 'editColorCharacteristics'
            } else if (element.id === 2) {
                url = 'editSizeCharacteristics'
            } else {
                url = `${element.id}/edit`
            }
            try {
                const {data: data} = await httpClient.get(`products/characteristics/${url}`);
                await context.commit("setDataEdit", data.data)
            } catch (e) {
                console.log(e)
            }
        },

        /* Edit property */
        async editProperty(context, element) {
            try {
                const {data: data} = await httpClient.get(`products/properties/${element.id}/edit`)
                await context.commit("setDataEdit", data.data)
            } catch (e) {
                console.log(e)
            }
        },

        clearDataEdit(context) {
            context.commit("clearDataEdit")
        },

        updateCategories: ({commit}, payload) => {
            commit("updateCategories", payload);
        },
        resetCategory({commit}) {
            commit('resetCategory')
        },
        async fetchCategories({ commit }) {
            try {
                const {data: data} = await httpClient.get('products/categories');
                await commit("updateCategories", data.data);
                await commit('updateFlatCategoriesList', getFlatMap(data.data));
            } catch (e) {
                console.log(e)
            }
        },
        async moveCategories(context, data) {
            try {
                await httpClient.post('products/categories/sortCategories',
                    {
                        categories: [
                            {... data}
                        ]
                    })
            } catch (e) {
                console.log(e)
            }
        },
        async sortCategories({ commit }, values) {
            try {
                const { data } = await httpClient.post('products/categories/sortItemCategories', { categories: values })
                if (data && !data.status) {
                    await commit("updateCategories", data.data)
                } else {
                    console.log('We have a problem!')
                }
            } catch (e) {
                console.log(e)
            }
        },
        async fetchEditCategory({ commit }, id) {
            try {
                const { data } = await httpClient.get(`products/categories/${id}/edit`);

                if (data && !data.status) {
                    commit("setEditCategory", data.data);
                } else {
                    console.log('We have a problem')
                }

            } catch (e) {
                console.log(e)
            }
        },
        async fetchCategory({ commit }, id) {
            try {
                const { data } = await httpClient.get(`products/categories/${id}`)
                if (data && !data.status) {
                    commit("setCategory", data.data)
                } else {
                    console.log('We have a problem')
                }
            } catch (e) {
                console.log(e)
            }
        },
        async fetchDataCreate({ commit }) {
            try {
                const { data } = await httpClient.get('products/categories/create');

                commit("setDataCreate", data.data)
            } catch (e) {
                console.log(e)
            }
        },
        async updateCategory({ commit, state, dispatch }, { category, id }) {
            try {
                const newCategory = await httpClient.put(`products/categories/${id}`, {
                    title: category.title,
                    desc: category.desc,
                    image_id: category.image_id,
                    parent_id: category.parent_id,
                    properties: category.properties,
                    characteristics: category.characteristics,
                    categories: category.categories,
                    unit_id: category.unit_id
                })
                await dispatch('fetchCategories');
                await commit('setIDCreatedCategory', category.id);

                if (!state.category) return false;

                if (state.category.id === id) {
                    commit('setCategory',  newCategory.data.data)
                }
            } catch (e) {
                console.log(e)
            }
        },
        async deleteCategory({ dispatch }, categories) {
            try {
                await httpClient.post('products/categories/toArchive', { categories });
                await dispatch('fetchCategories');
            } catch (e) {
                console.log(e)
            }
        },
        async changeCategory({ commit }, { title, desc, id, image, parent_id, status, properties, categories = [], characteristics, unit_id }) {
            try {
                const ChangedCategory = await httpClient.put(`products/categories/${id}`, {
                    title,
                    desc,
                    image,
                    parent_id,
                    status,
                    properties,
                    categories,
                    characteristics,
                    unit_id
                })

                commit('setCategory', ChangedCategory.data.data);
            } catch (e) {
                console.log(e)
            }
        },
        async addNewCategory({ commit, dispatch }, data) {
            const { title, desc = null, image_id, id, parent_id = 0, characteristics, properties, categories, unit_id } = data;
            const newCategory = { title, desc, image_id, id, parent_id, characteristics, properties, categories, unit_id }

            try {
                const { data: items } = await httpClient.post('products/categories', newCategory)

                await commit('addCategory', items.data);
                await dispatch('fetchCategories');
                await commit('setIDCreatedCategory', items.data.id);

                return items.data
            } catch (e) {
                console.log(e)
            }
        }
    },
    getters: {
        categoryValid: s => s.categoryValid,
        categories: s => s.categories,
        category: s => s.category,
        editCategory: s => s.editCategory,
        dataCreate: s => s.dataCreate,
        dataEdit: s => s.dataEdit,
        IDCreatedCategory: s => s.IDCreatedCategory,
        categoryById: s => id => s.categories.find(t => t.id === id),
        flatCategoriesList: s => s.flatCategoriesList
    }

}
