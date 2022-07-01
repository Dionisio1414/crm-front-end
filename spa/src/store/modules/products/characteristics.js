import axios from "axios"
import {httpClient} from "@/services";
import { PROTOCOL } from '@/domain';

export default {
  state: {
    categoriesCharacteristics: [],
    characteristics: [],
    enableSort: true,
    cloneCharacteristic: null,
    changeCharacteristic: false,
    selectedSizes: null,
    colorData: null,
    sizeData: null
  },
  mutations: {
    updateCategoryCharacteristics (state, characteristics) {
      state.categoriesCharacteristics = characteristics
    },
    setSizeData(state, payload) {
      state.sizeData = payload;
    },
    setSelectedSizes(state, content) {
      state.selectedSizes = content
    },
    setColorData(state, content) {
      state.colorData = content
    },
    removeItem(state, index) {
      if (isNaN(index)) {
        state.characteristics = state.characteristics.filter(prop => {
          if (!prop.check) {
            return true
          }
        })
      } else {
        state.characteristics.splice(index, 1)
      }
    },
    cloneItem(state, data) {
      let clone = {}
      for (let key in data.element) {
        clone[key] = data.element[key]
        clone.id = Date.now()
        clone.title = `${data.element.title} (Копия)`
      }
      state.cloneCharacteristic = clone
      // state.characteristics.splice(data.index, 0, clone)
      // setTimeout(() => {
      //   state.changeCharacteristic = true
      // }, 500)
    },
    clearClone(state) {
      state.cloneCharacteristic = null
      state.changeCharacteristic = false
    },
    updateList(state, payload) {
      state.characteristics = payload
      state.colorData = payload[0]
      state.sizeData = payload[1]
    },
    addCharacteristic(state, item) {
      state.characteristics.unshift(item)
    },
    updateItem(state, item) {
      const index = state.characteristics.findIndex(char => char.id === item.id)
      state.characteristics.splice(index, 1 , item)
      // const id = item.id
      // const title = item.title
      // const values = item.values
      // const characteristics = state.characteristics.concat()
      //
      // const idx = characteristics.findIndex(p => p.id === id)
      // const prop = characteristics[idx]

      // state.characteristics[idx] = {...prop, title, values }

      // state.characteristics = characteristics

    },
    updateChip(state, item) {
      console.log(item)
      const code = item.code
      const id = item.id
      const name = item.name
      const selectValue = item.selectValue
      const values = state.colorData.characteristic_color_value.concat()

      const idx = values.findIndex(p => p.id === id)
      const prop = values[idx]

      values[idx] = {...prop, code, name, selectValue }

      state.colorData.characteristic_color_value = values
    },
    editSize(state, item) {
      console.log(item)
      const id = item.id
      const title = item.title
      const values = state.selectedSizes.concat()

      const idx = values.findIndex(p => p.id === id)
      const prop = values[idx]

      values[idx] = {...prop, title}

      state.selectedSizes = values
    },
    deleteChip(state, item) {
      state.colorData.characteristic_color_value.splice(item, 1)
      console.log(item)
      console.log('delete')
    },
    sortList(state) {
      if (state.enableSort) {
        state.characteristics.sort((a, b) => a.title.localeCompare(b.title))
        state.enableSort = false
      } else {
        state.characteristics.sort((b, a) => a.title.localeCompare(b.title))
        state.enableSort = true
      }
    }
  },
  actions: {
    editSize({commit}, value) {
      commit('editSize', value)
    },
    async updateSizes({commit}, values) {
      try {
        const { data } = await httpClient.put('products/characteristics/updateSizeCharacteristics',{ values });
        commit('setSelectedSizes', values)
        commit('setSizeData', data.data)
      } catch (e) {
        console.log(e)
      }
    },
    clearClone({ commit }) {
      commit('clearClone')
    },
    sortList({ commit }) {
      commit('sortList')
    },
    async updateColor(context, values) {
      try {
         const color = await httpClient.put(`products/characteristics/updateColorCharacteristics`,{ values });
         await context.commit("setColorData", color.data.data);
      } catch (e) {
        console.log(e)
      }
    },
    async updateItem(context, item){
      const values = item.values;
      try {
        const editedItem = await axios.put(`products/characteristics/${item.id}`,{
          id: item.id,
          title: item.title,
          values: values
        })

        context.commit('updateItem', editedItem.data.data)
      } catch (e) {
        console.log(e)
      }
    },

    updateChip({ commit }, value) {
      commit('updateChip', value)
    },
    deleteChip({ commit }, value) {
      commit('deleteChip', value)
    },
    cloneItem({ commit }, data) {
      commit('cloneItem', data)
    },
    async removeItem({ commit }, content) {
      const items = content.map(item => ({id: item.id}));
      console.log(items)

      try {
        await httpClient.post('products/characteristics/toArchive', { items });
        commit('removeItem', content);
      } catch (e) {
        console.log(e)
      }
    },

    async fetchData({ commit }) {
      try {
        const { data } = await httpClient.get('products/characteristics')
        if (data && !data.status) {
          await commit("updateList", data.data[2].characteristics)
          await commit("setColorData", data.data[0]['characteristic_color'])
          await commit("setSizeData", data.data[1]['characteristic_size'])
          await commit("setSelectedSizes", data.data[1]['characteristic_size'].characteristic_value)
        }
      } catch (e) {
        console.log(e)
      }
    },
    async fetchCategoriesCharacteristics(context, id) {
      const token = await context.rootState.auth.token
      const domain = await context.rootState.auth.user.company.domain
      // const protocol = await context.rootState.auth.protocol
      try {
        const { data: characteristics } = await axios.get(`${PROTOCOL}://${domain}/api/v1/products/characteristics/${id}/characteristics-category`,  {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
        await context.commit("updateCategoryCharacteristics", characteristics.data)
      } catch (e) {
        console.log(e)
      }
    },
    async updateDataList( context, payload) {

      try {
        const { data } = await httpClient.post('products/characteristics/sortCharacteristics', {characteristics: payload});
        if (data && !data.status) {
          context.commit("updateList", payload)
        } else {
          console.log(data.status)
        }
      } catch (e) {
        console.log(e)
      }
    },
    async createItem({ commit }, item ) {
      const values = item.characteristic_value;

      try {
        const { data } = await httpClient.post('products/characteristics', {
          title: item.title,
          values: values
        })
        if (data && !data.status) {
          await commit('addCharacteristic', data.data);
        }
      } catch (e) {
        console.log(e)
      }
    }
  },
  getters: {
    categoriesCharacteristics: s => s.categoriesCharacteristics,
    characteristics: s => s.characteristics,
    cloneCharacteristic: s => s.cloneCharacteristic,
    changeCharacteristic: s => s.changeCharacteristic,
    colorData: s => s.colorData,
    sizeData: s => s.sizeData,
    selectedSizes: s => s.selectedSizes,
  }

}
