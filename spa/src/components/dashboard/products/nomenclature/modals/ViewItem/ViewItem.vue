<template>
  <div class="dialog-wrap view-nomenclature">
    <modal-container
        :custom-dialog-class="['nomenclature-create-modal']"
        v-if="isModalOpen"
        @clickOutside="onCloseModal"
        :dialog="isModalOpen"
        :modalWidth="1200">
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <svg-sprite width="24" height="16" :icon-id="isActual ? 'cloudFull' : 'cloud'"></svg-sprite>
            {{ data.short_title }}
            <span class="header-text-small">{{ itemId }}</span>
          </div>
          <div class="dialog-header-actions">
            <span class="view-nomenclature-date">{{ data.updated_at | formatDate }}</span>
            <!--<v-btn icon color="#5893D4">
              <svg-sprite width="15" height="20" icon-id="pin"></svg-sprite>
            </v-btn>-->
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="onCloseModal">
              <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
            </v-btn>
          </div>
        </div>
      </template>
      <template v-slot:main>
        <div class="view-nomenclature-header">
          <div class="view-nomenclature-header-left">
            <div class="product-cases" @click="onCLickProductsCases" v-if="isGroup && !isCreate">
              <svg-sprite width="18" height="16" icon-id="option"></svg-sprite>
              {{ $t('nomenclature.product_cases') }}
            </div>
            <div v-if="isActual" class="view-nomenclature-header-switcher">
                <span :class="{'active': !form.is_visible}" @click="toggleVisible(false)"
                      class="switch-label">Скрыть</span>
              <div class="switcher" :class="{'active' : form.is_visible}"
                   @click="toggleVisible(!form.is_visible)">
                <div class="switcher-caret"></div>
              </div>
              <span :class="{'active': form.is_visible}" @click="toggleVisible(true)"
                    class="switch-label">Показать на сайте</span>
            </div>
          </div>
          <div class="view-nomenclature-header-right">
            <ul class="view-nomenclature-header-actions">
              <li class="view-nomenclature-header-action" style="display: none">
                <svg-sprite width="17" height="17" icon-id="print"></svg-sprite>
                Печать
                <svg-sprite width="11" height="6" icon-id="arrowDown"></svg-sprite>
              </li>
              <li class="view-nomenclature-header-action" style="display: none">
                <svg-sprite width="17" height="17" icon-id="snowflake"></svg-sprite>
                Другое
                <svg-sprite width="11" height="6" icon-id="arrowDown"></svg-sprite>
              </li>
            </ul>
          </div>
        </div>
        <div class="step-tabs">
          <button
              v-for="(step, index) in itemTabs" :key="step.title"
              :class="{'active': index === currentIndex}"
              class="step"
              @click="goToStep(index)">
            {{ step.title }}
          </button>
        </div>
        <simplebar class="wrapper">
          <keep-alive
              @click-choose-btn="openChooseModal"

              @add-related-item="addItemToRelated"
              @add-analog-item="addItemToAnalog"
              @del-related-rows="delRelatedRow"
              @del-analog-rows="delAnalogRow"
              @save-price-changes="onSavePriceChanges"
              @show-alert="onShowAlert"
              @go-to-page="paginateGoToPage"

              v-if="isModalOpen"
              :is="currentTab"
              :loading="loading"
              :mode="mode"
              :category-tree="categoryTree"
              :countries="countries"
              :units="units"
              :resource="resource"
              :form="form"
          />
        </simplebar>
      </template>
      <template v-slot:footer>
        <div class="dialog-content dialog-content-large">
          <div class="dialog-actions popup-buttons">
            <custom-btn
                title="Отменить"
                custom-class="cancel"
                color="#5893D4"
                text
                @updateEvent="close"
            ></custom-btn>
            <custom-btn
                :title="isCreate ? 'Сохранить' : 'Редактировать'"
                custom-class="save"
                color="#5893D4"
                @updateEvent="isCreate ? save(): toEdit()"
            ></custom-btn>
          </div>
        </div>
      </template>
    </modal-container>
    <choose-modal
        v-if="isOpenChooseModal"
        @close-modal="onCloseChooseModal"
        @fetch-category-items="onFetchCategoryItemsForChoose"
        @select-item-to-choose="onSelectItemToChose"
        @save-selected-items="onSaveChooseItems"
        @scrolledToEnd="onScrollToEndChooseTable"
        @toggle-all-items-to-select="onToggleAllItemsToSelect"

        :selected-items-for-choose="chooseModalSelectedItems"
        :categories="categories"
        :title-text="chooseModalTitle"
        :is-modal-open="isOpenChooseModal"
        :resource="chooseModalResource"
        :items-to-select="filteredItemsToItems"
        :selected-items="formatSelectedItems"
        isAnalog
    >
      <template v-slot:products-actions>
        <custom-btn
            title="Добавить"
            custom-class="save"
            :is-disabled="!chooseModalSelectedItems.length"
            color="#5893D4"
            @updateEvent="onClickAddChooseSelectedItems"
        ></custom-btn>
      </template>
      <template v-slot:main-table>
        <table-with-prices
            @toggle-item="onToggleItem"
            @del-select-item="onDelSelectedItem"
            :body="selectedItems && selectedItems.body"
            :headers="selectedItems ? selectedItems.headers : defaultHeaders"
        />
      </template>
    </choose-modal>
  </div>
</template>

/<script>
 // vuex
import {mapActions, mapGetters} from 'vuex';
// components
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer";
import MainInfo from "./tabs/MainInfo";
import Accompanying from "./tabs/Accompanying";
import Analogue from "./tabs/Analogue";
import Characteristics from "./tabs/Characteristics";
import Description from "./tabs/Description";
import Sales from "./tabs/Sales";
import PricesHistory from "./tabs/PricesHistory";
import httpClient from "@/services/http-client/http-client";
 import ChooseModal from "@/components/ui/ChooseModal/ChooseModal";
 import TableWithPrices from "@/components/ui/TableWithPrices";
 import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
 // services
 import {fetchItems, selectItems} from "@/services/choose";
 // constants
 import { TABLE_ACTIONS, NOMENCLATURE_RESOURCES, CHOOSE_MODAL_RESOURCES } from "@/constants/constants";
 import {eventBus} from "@/main";

export default {
  name: "ViewItem",
  components: {
    CustomBtn,
    TableWithPrices,
    ChooseModal,
    PricesHistory,
    Sales,
    Description,
    Characteristics,
    Analogue,
    Accompanying,
    MainInfo,
    ModalContainer,
    // CategoryTree,
    // ViewProperty
  },
  async created() {
    this.form = JSON.parse(JSON.stringify(this.data))
    this.form.analog = {headers: [], body: []}
    this.currentIndex = 0
    const category = this.mode === TABLE_ACTIONS.UPDATE
        ? this.data.category : this.fetchedCategory
    this.categoryTree = []
    this.getCategoryTree(category)
  },
  props: ['fetchedCategory', 'isGroup', 'isModalOpen', 'data', 'mode', 'resourceIndex', 'countries', 'suppliers', 'units'],
  computed: {
    ...mapGetters({
      product: 'product',
      properties: 'properties',
      characteristicsArr: 'characteristics',
      colorData: 'colorData',
      categories: 'categories',
      sizeData: 'sizeData',
    }),
    itemId() {
      const { convert_id } = this.data || [];
      return convert_id;
    },
    isActual() {
      const { is_actual } = this.data || [];
      return is_actual;
    },
    formatSelectedItems() {
      if (this.selectedItems) {
        return this.selectedItems.body
      }
      return []
    },
    filteredItemsToItems() {
      if (this.itemsToSelect) {
        const itemData = this.data ?? [];
        const selectedItems = this.selectedItems ? [...this.selectedItems.body, itemData] : [itemData]
        const body = this.itemsToSelect.body.map(itemToSelect => {
          const formattedArray = [...this.selectedList, ...selectedItems];
          itemToSelect.isDisabled = !!formattedArray.find(selectedItem => selectedItem.id === itemToSelect.id);
          return itemToSelect;
        })

        return {
          ...this.itemsToSelect,
          body
        }
      }

      return this.itemsToSelect
    },
    resources() {
      return [
        {...NOMENCLATURE_RESOURCES.PRODUCTS},
        {...NOMENCLATURE_RESOURCES.SERVICES},
        {...NOMENCLATURE_RESOURCES.KITS},
      ]
    },
    resource() {
      return this.resources[this.resourceIndex]
    },
    isCreate() {
      return this.mode === TABLE_ACTIONS.CREATE
    },
    itemTabs() {
      const resource = this.resource.MULTIPLE_VALUE
      // return this[`${resource}Tabs`]
      return this.allTabs.filter(tab => this[`${resource}Tabs`].includes(tab.component))
    },
    currentTab() {
      return this.itemTabs[this.currentIndex].component
    },
    baseCharacteristic() {
      if (this.mode === 'create' && this.form.base_characteristic_value) {
        let characteristic
        characteristic = {...this.characteristics.find(item => item.id === this.form.base_characteristic_value.id)}
        return characteristic
      }
      if (this.mode === 'edit' || this.mode === 'copy') {
        return this.form.base_characteristic_value
        // let baseCharacteristic = this.form.characteristic_value.find(item => !!item.is_base)
        // if (!baseCharacteristic && this.form.characteristic_color_value && this.form.characteristic_color_value.is_base) {
        //   baseCharacteristic = this.form.characteristic_color_value
        //   baseCharacteristic.is_color = true
        // }
        //
        // return baseCharacteristic ? {...this.characteristics.find(item => item.id === baseCharacteristic.id)} : null

      }

      return null
    },
    colorCharacteristic() {
      if (this.form.characteristic_color_value) {
        let characteristic
        characteristic = {...this.characteristics.find(item => item.id === this.form.characteristic_color_value.id)}
        characteristic.is_base = this.form.characteristic_color_value.is_base
        return characteristic
      }
      return false
    },
    selectedProperties() {
      if (this.form.property_value && this.properties) {
        return this.form.property_value.map(property => {
          return {
            ...property,
            property: this.properties.find(propertyVal => propertyVal.id === property.property_id)
          }
        })
      }
      return []
    },
    characteristics() {
      return [].concat(this.characteristicsArr, this.colorData, this.sizeData)

    },
    selectedCharacteristics() {
      if (this.form.characteristic_value) {
        return [...this.form.characteristic_value].map((stat) => {
          if (stat.is_base) {
            this.form.base_characteristic_value = {...stat}
            return false
          } else {
            return this.characteristics.find(fullStat => fullStat.id === stat.id)
          }
        })
      }
      return []
    },
    // selectedList() {
    //   if (this.form.related) {
    //     return this.form.related.body
    //   }
    //   return []
    // },
  },
  data() {
    return {
      dialog: false,
      selectedItems: null,
      itemsToSelect: null,
      categoryTree: [],
      isOpenChooseModal: false,
      chooseModalTitle: '',
      chooseModalResource: null,
      chooseModalSelectedItems: [],
      currentChoseModalPage: 1,
      selectedList: [],
      loading: false,
      allTabs: [
        {component: 'MainInfo', title: this.$t('nomenclature.view_item_tabs.main')},
        {component: 'Characteristics', title: this.$t('nomenclature.view_item_tabs.characteristic')},
        {component: 'Description', title: this.$t('nomenclature.view_item_tabs.desc')},
        {component: 'PricesHistory', title: this.$t('nomenclature.view_item_tabs.prices_history')},
        {component: 'Analogue', title: this.$t('nomenclature.view_item_tabs.analogue')},
        {component: 'Accompanying', title: this.$t('nomenclature.view_item_tabs.accompanying')},
        // {component: 'Sales', title: this.$t('nomenclature.view_item_tabs.sales')},
      ],
      defaultHeaders: [{"id":1,"title":"ID","is_sortable":1,"is_visible":1,"order":0,"value":"convert_id"},{"id":2,"title":"Артикул","is_sortable":1,"is_visible":1,"order":1,"value":"sku"},{"id":3,"title":"Фото","is_sortable":1,"is_visible":1,"order":0,"value":"photo"},{"id":4,"title":"Наименование","is_sortable":1,"is_visible":1,"order":0,"value":"short_title"},{"id":5,"title":"Ед.изм","is_sortable":1,"is_visible":1,"order":0,"value":"units"},{"id":6,"title":"Остаток","is_sortable":1,"is_visible":1,"order":0,"value":"balance"},{"id":7,"title":"Цена розничная","is_sortable":1,"is_visible":1,"order":0,"value":"price_2"},{"id":8,"title":"Цена оптовая","is_sortable":1,"is_visible":1,"order":0,"value":"price_3"},{"id":9,"title":"Цена закупочная","is_sortable":1,"is_visible":1,"order":0,"value":"price_1"}],
      productsTabs: ['MainInfo', 'Characteristics', 'Description', 'PricesHistory', 'Analogue', 'Accompanying'],
      servicesTabs: ['MainInfo', 'Description', 'PricesHistory'],
      currentIndex: 0,
      form: {}
    }
  },
  async mounted() {
    eventBus.$on('getAllProduct', async () => {
      let url = '/products/nomenclatures/get-products-in-related-or-analog-all';

      const {data} = await httpClient.get(url);
      this.itemsToSelect = data.data
    })

    let table = {}
    try {
      if (this.mode === TABLE_ACTIONS.CREATE) {
        const url = `/products/nomenclatures/get-table-related-products/empty`;
        table = await httpClient.get(url)
        this.form.related = table.data.data
      } else {
        const url = `/products/nomenclatures/get-table-related-products/${this.data.id}?page=1`;
        table = await httpClient.get(url);
        this.form.related = table.data.data;
      }

      if (this.mode === TABLE_ACTIONS.CREATE) {
        const url = `/products/nomenclatures/get-table-analog-products/empty`;
        table = await httpClient.get(url);
        this.form.analog = table.data.data;
      } else {
        const url = `/products/nomenclatures/get-table-analog-products/${this.data.id}?page=1`;
        table = await httpClient.get(url);
        this.form.analog = table.data.data;
      }

      this.isLoadRelated = true;
    } catch (e) {
      console.log(e);
    }
  },
  methods: {
    ...mapActions({
      fetchProperties: 'fetchCategoriesProperties',
      fetchCategoriesCharacteristics: 'fetchCategoriesCharacteristics',
      createItem: 'createProduct',
      fetchData: 'fetchData',
      changeVisibilityGroup: 'changeVisibilityGroup'
    }),
    onShowAlert(alertParams) {
      this.$emit('show-alert', alertParams)
    },
    async paginateGoToPage({page, resource}) {
      const url = `/products/nomenclatures/get-table-${resource.NAME}-products/${this.data.id}?page=${page}`
      const table = await httpClient.get(url)
      this.form[resource.NAME] = table.data.data
      this.isLoadRelated = true
    },
    onToggleAllItemsToSelect(state) {
      if (!state) {
        this.chooseModalSelectedItems = this.itemsToSelect.body.map(item => ({ id: item.id }))
      } else {
        this.chooseModalSelectedItems = []
      }
    },
    onCloseChooseModal() {
      this.isOpenChooseModal = false
      this.selectedItems = null
      this.currentChoseModalPage = 1
    },
    async onSavePriceChanges() {

    },
    async delRelatedRow(itemsToDel) {
      this.form.related.body = this.form.related.body.filter(item => {
        const index = itemsToDel.findIndex(itemToDael => itemToDael.id === item.id)
        return index === -1
      })
      try {
        const url = `/products/nomenclatures/delete-related-products/${this.data.id}`
        await httpClient.post(url, itemsToDel)
      } catch (e) {
        console.log(e);
      }
    },
    async delAnalogRow(itemsToDel) {
      this.form.analog.body = this.form.analog.body.filter(item => {
        const index = itemsToDel.findIndex(itemToDael => itemToDael.id === item.id)
        return index === -1
      })
      try {
        const url = `/products/nomenclatures/delete-analog-products/${this.data.id}`
        await httpClient.post(url, itemsToDel)
      } catch (e) {
        console.log(e);
      }
    },
    onToggleItem() {
    },
    onScrollToEndChooseTable() {
      this.currentChoseModalPage++;

      if (this.currentChoseModalPage <= this.itemsToSelect['total_page']) {
        this.paginateItemsToSelect()
      }
    },

    openChooseModal({ chooseResource }) {
      this.chooseModalTitle = (chooseResource.NAME === 'analog') ? 'Аналоги' : 'Сопутствующие товары'
      this.chooseModalResource = chooseResource;
      this.selectedList = this.form[chooseResource.NAME]['item_ids'];

      this.fetchItemsToSelect().then(() => {
        this.addScrollEvent()
      })
      this.isOpenChooseModal = true
    },
    addScrollEvent() {
      const table = document.querySelector('.choose-modal .v-data-table__wrapper')
      table.addEventListener('scroll', () => {
            if (table.scrollTop + table.clientHeight >= table.scrollHeight) {
              this.onScrollToEndChooseTable()
            }
          }
      );
    },
    async fetchItemsToSelect() {
      const url = `${this.chooseModalResource.FETCH_ALL_ITEMS}?page=${this.currentChoseModalPage}`
      const items = await httpClient.get(url)
      this.itemsToSelect = items.data.data
    },
    async paginateItemsToSelect() {
      const url = `${this.chooseModalResource.FETCH_ALL_ITEMS}?page=${this.currentChoseModalPage}`
      const items = await httpClient.get(url)
      this.itemsToSelect.body.push(...items.data.data.body)
    },
    async addItemToRelated(item) {
      try {
        this.loading = true
        const url = `/products/nomenclatures/create-related-products/${this.data.id}`
        await httpClient.post(url, {data: [{id: item.id}]})
        const fetchUrl = `${CHOOSE_MODAL_RESOURCES.ACCOMPANYING.FETCH_URL}${this.data.id}?page=1`
        const items = await httpClient.get(fetchUrl)
        this.form.related = items.data.data
        this.loading = false
      } catch (e) {
        console.log(e);
      }

    },
    async addItemToAnalog(item) {
      try {
        this.loading = true
        const url = `/products/nomenclatures/create-analog-products/${this.data.id}`
        await httpClient.post(url, {data: [{id: item.id}]})
        const fetchUrl = `${CHOOSE_MODAL_RESOURCES.ANALOGS.FETCH_URL}${this.data.id}?page=1`
        const items = await httpClient.get(fetchUrl)
        this.form.analog = items.data.data
        this.loading = false
      } catch (e) {
        console.log(e);
      }

    },
    async onFetchCategoryItemsForChoose(url) {
      console.log(url,'url')
      try {
        const res = await fetchItems(url)
        this.itemsToSelect = res.data.data
      } catch (e) {
        console.log(e);
      }
    },
    async onClickAddChooseSelectedItems() {
      const data = this.chooseModalSelectedItems
      const url = this.chooseModalResource.SELECT_ITEMS
      try {
        const res = await selectItems(url, data)
        this.chooseModalSelectedItems = []
        if (this.selectedItems) {
          this.selectedItems.body.push(...res.data.data.body)
        } else {
          this.selectedItems = res.data.data
        }
      } catch (e) {
        console.log(e);
      }
    },
    onDelSelectedItem(id) {
      const index = this.selectedItems.body.findIndex(item => item.id === id)
      this.selectedItems.body.splice(index, 1)
    },
    async onSaveChooseItems(choseResource) {
      const property = choseResource.NAME
      this.loading = true

      try {
        const params = {data: this.selectedItems.body.map(item => ({id: item.id}))}
        const url = `${choseResource.ADD_ITEMS}${this.data.id}`

        await httpClient.post(url, {...params})

        const items = await httpClient.get(`${choseResource.FETCH_URL}${this.data.id}?page=1`)
        this.form[property] = items.data.data
        this.loading = false
        this.selectedItems.body = []
      } catch (e) {
        console.log(e);
      }

      this.isOpenChooseModal = false
    },
    onSelectItemToChose({id}) {
      const index = this.chooseModalSelectedItems.findIndex(item => item.id === id)
      if (index === -1) {
        this.chooseModalSelectedItems.push({id})
      } else {
        this.chooseModalSelectedItems.splice(index, 1)
      }
    },
    goToStep(index) {
      this.currentIndex = index
    },
    toConfirm() {
      this.isConfirm = true
    },
    onCloseModal() {
      this.$emit('close-modal')
    },
    onCancel() {
      this.isConfirm = false
    },
    onConfirm() {
      this.isConfirm = false
      this.isModalOpen = false
    },
    onCLickProductsCases() {
      //this.$emit('close-modal')
      const characteristics = [...this.selectedCharacteristics].map(char => {
        return {
          id: char.id,
          values: []
        }
      })
      const generateData = {
        id: this.form.id,
        name: this.form.short_title,
        sku: this.form.sku || '',
        prices: this.form.price,
        characteristic_value: characteristics,
      }
      this.$emit('cLick-products-cases', generateData)
    },
    close() {
      this.$emit('close-modal')
    },
    async toggleVisible(val) {
      this.form.is_visible = val;
      const { id, groups = [] } = this.data;

      await this.changeVisibilityGroup({
        items: [...groups, { id }].map(item => {
          return {id: item.id}
        }),
        groupId: id,
        visible: val
      })

      await this.$store.dispatch('fetchItems', {resources: 'products'})
    },
    toEdit() {
      this.$emit('update', this.data)
      this.$emit('close-modal')
    },
    save() {
      this.$emit("create-item", {
        data: this.data,
        isGroup: this.isGroup,
        resourceIndex: this.resourceIndex
      })
    },
    statsValues(characteristic) {
      if (characteristic) {
        return characteristic.characteristic_color_value || characteristic.characteristic_value || []
      }
      return []
    },
    getCategoryTree(category) {
      this.categoryTree.push({title: category.title, id: category.id})
      if (category.parent) {
        this.getCategoryTree(category.parent)
      }
    }
  }
}
</script>

<style lang="scss">
.switcher {
  width: 33px;
  height: 18px;
  border: 1px solid #7CE1A4;
  box-sizing: border-box;
  border-radius: 50px;
  position: relative;
  cursor: pointer;

  &-caret {
    width: 12px;
    height: 12px;
    background: #4ECA80;
    border-radius: 50%;
    position: absolute;
    left: 5%;
    top: 50%;
    transform: translateY(-50%);
    transition: all .5s;
  }

  &.active {
    .switcher-caret {
      transform: translateY(-50%) translateX(16px);
      background: #4ECA80;
      border: 1px solid #7CE1A4;
    }
  }

  &:not(.active) {
    border: 1px solid #9DCCFF;
    .switcher-caret {
      background: #9DCCFF;
      border: 1px solid #9DCCFF;
    }
  }
}
</style>
<style scoped lang="scss">
.step-tabs {
  display: flex;
  align-items: center;
  margin: 30px 20px;
}

.step {
  width: 100%;
  max-width: 220px;
  box-sizing: border-box;
  height: 36px;
  border: 1px solid #9DCCFF;
  border-radius: 25px;
  font-weight: 550;
  font-size: 14px;
  line-height: 14px;
  margin: 0 10px;
  color: #9DCCFF;


  &.active {
    background: #5893D4;
    color: #fff;
  }
}

.wrapper {
  height: 520px;
}

.header-text-small {
  font-size: 15px;
  font-weight: 400;
}

.view-nomenclature {
  &-date {
    font-size: 13px;
  }
}

.info-table {
  padding-top: 0;
  padding-bottom: 0;
  margin-bottom: 35px;

  .label-title {
    margin-bottom: 0;
  }

  .item-name {
    padding-left: 10px;
    border-left: 2px solid #9DCCFF;
    height: 52px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-wrap: nowrap;
  }

  .item-value {
    font-size: 14px;
  }
}

.header-text {
  display: flex;
  align-items: center;

  svg {
    margin-right: 10px;
  }

  &-small {
    margin-left: 20px;
  }
}


.label-title__subtitle {
  color: #9DCCFF;
}


.view-nomenclature-header {
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
  padding: 20px 30px;
  width: 100%;
  display: flex;
  justify-content: space-between;

  &-left {
    display: flex;

    .product-cases {
      cursor: pointer;

      svg {
        margin-right: 10px;
      }

      display: flex;
      align-items: center;
      margin-right: 40px;
      font-size: 14px;
      line-height: 14px;
      color: #FF9F2F;
    }
  }

  &-action {
    padding: 7px 15px;
    border-right: 1px solid #E3F0FF;
    font-size: 14px;
    line-height: 14px;
    color: #5893D4;
    display: flex;
    align-items: center;
    min-width: 130px;
    justify-content: space-between;

    &:last-child {
      border-right: none;
      padding-right: 0;
    }

  }

  &-actions {
    display: flex;
    list-style: none;
  }

  &-switcher {
    display: flex;
    align-items: center;

    .switch-label {
      font-size: 13px;
      line-height: 13px;
      color: #9DCCFF;
      cursor: pointer;

      &.active {
        color: #5893D4;
      }

      &:first-child {
        margin-right: 11px;
      }

      &:last-child {
        margin-left: 11px;
      }
    }
  }
}

.item-value {
  font-weight: 400;
  font-size: 15px;
  line-height: 15px;
  color: #7E7E7E
}

.view-nomenclature-main {
  width: 100%;
  max-height: 461px;
  overflow: auto;
  padding-top: 30px;

  .item-form {
    .item-name {
      margin-bottom: 22px;
    }
  }
}

.values-list {
  .value-item {
    background-color: #F4F9FF;
  }

  .head-characteristic {
    background: #E3F0FF;
  }
}


.date-item {
  display: flex;
  padding-top: 20px;

  .label-title {
    width: 274px;
  }

  .item-value {
    padding-left: 15px;
    width: calc(100% - 274px)
  }
}

</style>
