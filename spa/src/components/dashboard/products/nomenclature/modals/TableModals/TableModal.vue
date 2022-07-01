<template>
  <div>
    <button @click="onOpenModal"> open</button>
    <modal-container
        v-if="isModalOpen"
        :custom-dialog-class="['table-modal']"
        @clickOutside="onCloseModal"
        :dialog="isModalOpen"
        :modalWidth="1200"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t(`context_menu.${resource.NAME}`) }} {{ data.title }}</span>
          </div>
          <header-actions isHideDots is-hide-attach @updateClose="onCloseModal"></header-actions>
        </div>
      </template>
      <template v-slot:main>
        <div class="dialog-main" v-if="!isPrices">
          <div class="table-actions">
            <div class="table-actions-left">
              <paginate-for-table
                  v-if="!isPrices && items"
                  :current-page="currentPage"
                  :total-page="items['total_page']"
                  @click-next="onClickNext"
                  @click-prev="onClickPrev"
                  @click-last="onClickLast"
                  @click-first="onClickFirst"
              />
              <async-simple-input></async-simple-input>
            </div>
            <div class="table-actions-right">
              <button
                  type="button"
                  @click="isGroupsProduct ? onCLickGenerateBtn() : onClickChooseBtn()"
                  class="transparent-btn"
              >
                {{ $t(isGroupsProduct ? 'nomenclature.generate' : 'page.сhoose') }}
              </button>
              <icon-with-tooltip
                  v-if="!isGroupsProduct"
                  color="#F4F9FF"
                  icon="mdi-plus"
                  customClass="delete"
                  tooltip-text="Добавить строку"
                  @push-btn="onClickAddRow"
              />
              <icon-with-tooltip
                  color="#F4F9FF"
                  customClass="delete"
                  @push-btn="onClickDelRow"
                  icon="mdi-trash-can-outline"
                  tooltip-text="Удалить строку"
              />
            </div>
          </div>
          <table-with-prices
              v-if="items"
              @del-select-item="onDelItem"
              @select-item="onSelectItem"
              @toggle-item="onSelectProduct"
              @toggle-all="onToggleAll"

              :selected-items="selectedNomenclatures"
              :with-checkbox="true"
              :searchable="isSearchItem"
              :body="items.body"
              :headers="items.headers"
          />
        </div>
        <div class="accompanying" v-else>
          <div class="accompanying-header">
            <div class="search-block">
              <input type="text" :placeholder="$t('page.search')" v-model="search">
              <div class="search-icon">
                <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
              </div>
            </div>
            <div class="header-right">
              <icon-with-tooltip
                  color="#F4F9FF"
                  icon="mdi-pencil-outline"
                  :loading="selectedPrices.length !== 1"
                  customClass="delete"
                  tooltip-text="Изменить цену"
                  @push-btn="onClickChangeRow"
              />
              <icon-with-tooltip
                  color="#F4F9FF"
                  customClass="delete"
                  @push-btn="onClickDelPrices"
                  icon="mdi-trash-can-outline"
                  tooltip-text="Удалить цену"
              />
            </div>
          </div>
          <div class="table-wrapper table-layout-fixed" :class="{'loading': loading}">
            <div class="table-responsive" v-if="items">
              <v-data-table
                  :headers="formatHeaders"
                  :items="[items.body, formatHeaders]"
                  :sort-desc.sync="sortDesc"
                  :sort-by.sync="sortBy"
                  :items-per-page="10"
                  hide-default-header
                  hide-default-footer
                  sortable
                  class="table"
                  ref="table"
              >
                <template v-slot:header="{props}">
                  <thead>
                  <tr>
                    <th class="td-checkbox">
                      <div class="checkbox">
                        <label class="checkbox-label">
                          <input type="checkbox" :checked="isAllSelected" @click="toggleAll">
                          <div class="checkbox-text"/>
                        </label>
                      </div>
                    </th>
                    <template
                        v-for="header in props.headers"
                    >
                      <th :class="header.value"
                          :key="header.title"
                          :ref="header.value"
                          v-show="header.is_visible"
                      >
                        <div class="th-block">
                          <span v-if="header.value === 'date'" class="header-title">{{ header.title }}</span>
                          <span v-else class="header-title">{{ header.title }}</span>
                          <span class="sortable-icon" v-if="header.is_sortable">
                            <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
                          </span>
                        </div>
                      </th>
                    </template>
                  </tr>
                  </thead>
                </template>
                <template v-slot:body="{items}">
                  <tbody>
                  <tr v-for="val in items[0]"
                      :key="val.id">
                    <td class="td-checkbox">
                      <div class="checkbox">
                        <label class="checkbox-label">
                          <input
                              :checked="selectedPrices.some(item => item.id === val.id)"
                              type="checkbox"
                              @change="selectItem(val.id)"
                          >
                          <span class="checkbox-text"></span>
                        </label>
                      </div>
                    </td>
                    <td v-for="(value, key) in items[1]"
                        :key="key"
                        :ref="key">
                      <span v-if="value.value === 'date'">{{ val.cells[value.value] | formatDate }} </span>
                      <span v-else>{{ val.cells[value.value] }}</span>
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-data-table>
            </div>
          </div>
          <change-price-modal
              v-if="isOpenChangePriceModal"
              @closeModal="onCloseChangePriceModal"
              @save="onSavePriceChanges"

              :data="editedPrice"
              :is-open="isOpenChangePriceModal"/>
          <change-price-warning-modal
              @continue="onContinueUpdatePrice"
              @closeModal="onCloseChangePriceWarningModal"

              :is-open="isOpenChangePriceWarningModal"/>
        </div>
      </template>
      <template v-slot:footer>
        <div class="dialog-footer">
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel"
                title="Закрыть"
                color="#5893D4"
                @updateEvent="onCloseModal"
                text
            ></custom-btn>
          </div>
        </div>
      </template>
    </modal-container>
    <choose-modal
        v-if="isOpenChooseModal"
        @fetch-category-items="onFetchCategoryItemsForChoose"
        @select-item-to-choose="onSelectItemToChose"
        @save-selected-items="onSaveSelected"
        @close-modal="onCloseChoseModal"
        @scrolledToEnd="onScrollToEndChooseTable"
        @toggle-all-items-to-select="onToggleAllItemsToSelect"

        :selected-items-for-choose="chooseModalSelectedItems"
        :categories="categories"
        :title-text="chooseModalTitle"
        :is-modal-open="isOpenChooseModal"
        :items-to-select="filteredItemsToItems"
        :selected-items="formatSelectedItems"
        :resource="resource"
        isNomenclature
    >
      <template v-slot:products-actions>
        <!--        <async-simple-input @updateInput="onInput"></async-simple-input>-->
        <custom-btn
            title="Добавить"
            custom-class="save"
            :is-disabled="!chooseModalSelectedItems.length"
            color="#5893D4"
            @updateEvent="onClickAddChooseSelectedItems"
        ></custom-btn>
      </template>
      <template v-slot:main-table>
        <div class="table-header">
          <div class="label-title">Выбранные товары</div>
          <div class="table-header-right"></div>
        </div>
        <table-with-prices
            @del-select-item="onDelSelectedItem"

            :body="selectedItems && selectedItems.body"
            :headers="selectedItems ? selectedItems.headers : defaultHeaders"
        />
      </template>
    </choose-modal>
  </div>
</template>

<script>
// vuex
import {mapGetters} from 'vuex';
import {eventBus} from "@/main";
// moment
import moment from "moment";
// components
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
// constants
import ChangePriceWarningModal from "@/components/dashboard/products/nomenclature/modals/ChangePriceWarningModal";
import ChangePriceModal from "@/components/dashboard/products/nomenclature/modals/ChangePriceModal";
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput";
import ChooseModal from "@/components/ui/ChooseModal/ChooseModal";
import PaginateForTable from "@/components/ui/PaginateForTable";
import TableWithPrices from "@/components/ui/TableWithPrices";
import IconWithTooltip from "@/components/ui/IconWithTooltip";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import {TABLE_RESOURCES} from "@/constants/nomenclature";
// services
import httpClient from "@/services/http-client/http-client";
import {selectItems} from "@/services/choose";

export default {
  name: "TableModal",
  components: {
    ChangePriceWarningModal,
    ChangePriceModal,
    PaginateForTable,
    AsyncSimpleInput,
    CustomBtn,
    ChooseModal,
    IconWithTooltip,
    TableWithPrices,
    ModalContainer,
    HeaderActions,
  },
  props: {
    isModalOpen: Boolean,
    data: Object,
    resource: Object,
    nomenclatureResource: Object,
  },
  data() {
    return {
      items: null,
      search: '',
      isSearchItem: false,
      selectedItems: null,
      selectedPrices: [],
      chooseModalTitle: '',
      sortBy: null,
      sortDesc: null,
      loading: false,
      isOpenChooseModal: false,
      selectedNomenclatures: [],
      chooseModalSelectedItems: [],
      itemsToSelect: null,
      selectedList: [],
      currentPage: 1,
      isOpenChangePriceWarningModal: false,
      isOpenChangePriceModal: false,
      editedPrice: null,
      currentChoseModalPage: 1,
      defaultHeaders: [{"id":1,"title":"ID","is_sortable":1,"is_visible":1,"order":0,"value":"convert_id"},{"id":2,"title":"Артикул","is_sortable":1,"is_visible":1,"order":1,"value":"sku"},{"id":3,"title":"Фото","is_sortable":1,"is_visible":1,"order":0,"value":"photo"},{"id":4,"title":"Наименование","is_sortable":1,"is_visible":1,"order":0,"value":"short_title"},{"id":5,"title":"Ед.изм","is_sortable":1,"is_visible":1,"order":0,"value":"units"},{"id":6,"title":"Остаток","is_sortable":1,"is_visible":1,"order":0,"value":"balance"},{"id":7,"title":"Цена розничная","is_sortable":1,"is_visible":1,"order":0,"value":"price_2"},{"id":8,"title":"Цена оптовая","is_sortable":1,"is_visible":1,"order":0,"value":"price_3"},{"id":9,"title":"Цена закупочная","is_sortable":1,"is_visible":1,"order":0,"value":"price_1"}]
    }
  },
  computed: {
    ...mapGetters({
      categories: 'categories'
    }),
    formatSelectedItems() {
      if (this.selectedItems) {
        return this.selectedItems.body
      }
      return []
    },
    formatHeaders() {
      if (this.items) {
        return this.items.headers.filter(item => item.is_visible && !item.value !== 'sku').sort((a, b) => a.order - b.order)
      }
      return []
    },
    isAllSelected() {
      if (this.items.body) {
        return this.selectedPrices.length === this.items.body.length
      }
      return false
    },
    filteredItemsToItems() {
      const selectedItems = this.selectedItems?.body ?? [];
      const itemsToSelect = this.itemsToSelect?.body ?? [];

      const body = itemsToSelect.map(itemToSelect => {
        const formattedArray = this.selectedList.concat(selectedItems)

        itemToSelect.isDisabled =
            formattedArray.some(selectedItem => selectedItem.id === itemToSelect.id) || itemToSelect.id === this.data.id;
        return itemToSelect;
      })

      return {
        ...this.itemsToSelect,
        body
      }
    },
    isGroupsProduct() {
      return this.resource === TABLE_RESOURCES.GROUPS_PRODUCTS
    },
    isAnalog() {
      return this.resource === TABLE_RESOURCES.ANALOGS
    },
    isRelated() {
      return this.resource === TABLE_RESOURCES.RELATED
    },
    isPrices() {
      return this.resource === TABLE_RESOURCES.PRICES
    }
  }
  ,
  async mounted() {
    eventBus.$on('getAllProduct', async () => {
      let url = '';
      if (this.isAnalog) {
        url = '/products/nomenclatures/get-products-in-related-or-analog-all';
      }

      if (this.isRelated)  {
        url = '/products/nomenclatures/get-select-related-products-all';
      }

      const {data} = await httpClient.get(url);
      this.itemsToSelect = data.data
    })
    // if (this.isGroupsProduct) {
    //   const items = await httpClient.get(`products/nomenclatures/get-groups-nomenclatures/${this.data.id}`);
    //   this.items = items.data.data
    // }
    // if (this.isRelated) {
    //   const items = await httpClient.get(`/products/nomenclatures/get-table-related-products/${this.data.id}`);
    //   this.items = items.data.data
    //   this.selectedList = this.items.body
    // }
    if (this.isPrices) {
      const url = `products/prices/get-nomenclature-price/${this.data.id}`;
      const {data} = await httpClient.get(url);
      this.items = data.data
    } else {
      const url = `${this.resource.FETCH_URL}${this.data.id}`;
      const {data} = await httpClient.get(url);

      this.items = data.data
      this.selectedList = this.items['item_ids'];
      if (this.selectedList) this.selectedList.push({id: this.data.id})
    }
  }
  ,
  methods: {
    toConfirm() {
      this.isConfirm = true
    },
    async onFetchCategoryItemsForChoose({ id }) {
      try {
        this.$store.commit('updateChoosePopupPending', true);
        const { data } = await httpClient.get(`/products/nomenclatures/get-products-in-related-or-analog/${id}`)
        this.itemsToSelect = {...data.data}

      } catch (e) {
        console.log(e);
      }
      this.$store.commit('updateChoosePopupPending', false);
    },
    onDelSelectedItem(id) {
      const index = this.selectedItems.body.findIndex(item => item.id === id)
      this.selectedItems.body.splice(index, 1)
    },
    onToggleAllItemsToSelect(state) {
      if (!state) {
        this.chooseModalSelectedItems = this.itemsToSelect.body.map(item => ({id: item.id}))
      } else {
        this.chooseModalSelectedItems = []
      }
    }
    ,
    onScrollToEndChooseTable() {
      this.currentChoseModalPage++
      if (this.currentChoseModalPage <= this.itemsToSelect['total_page']) {
        this.fetchItemsToSelect()
      }
    },
    onInput(searchData) {
      console.log(searchData);
    },
    onCloseModal() {
      this.$emit('close-modal')
    },
    onCancel() {
      this.isConfirm = false
    },
    onOpenModal() {
      this.isModalOpen = true
    },
    onDelItem() {

    },
    async fetchItems() {
      this.selectedNomenclatures = []
      const url = this.resource.FETCH_URL
      const { data } = await httpClient.get(`${url}${this.data.id}?page=${this.currentPage}`);
      this.items = data.data
    },
    onClickNext() {
      ++this.currentPage
      this.fetchItems()
    },
    onClickPrev() {
      --this.currentPage
      this.fetchItems()
    },
    onClickLast() {
      this.currentPage = this.items['total_page']
      this.fetchItems()
    },
    onClickFirst() {
      this.currentPage = 1
      this.fetchItems()
    },
    onSelectItemToChose({id}) {
      const index = this.chooseModalSelectedItems.findIndex(item => item.id === id)
      if (index === -1) {
        this.chooseModalSelectedItems.push({id})
      } else {
        this.chooseModalSelectedItems.splice(index, 1)
      }
    },
    async onSaveSelected() {
      const data = this.selectedItems.body.map(item => ({id: item.id}))
      const url = `${this.resource.ADD_URL}${this.data.id}`
      await httpClient.post(url, {data})
      this.currentPage = 1
      this.currentChoseModalPage = 1
      await this.fetchItems()
      this.isOpenChooseModal = false
      this.itemsToSelect = null;

    },
    async onSave() {
      try {
        const data = {data: this.items.body.map(item => ({id: item.id}))}
        const url = `/products/nomenclatures/update-or-create-related-products/${this.data.id}`
        await httpClient.post(url, data)
        this.$emit('close-modal')
      } catch (e) {
        console.log(e);
      }
    },
    async onClickAddChooseSelectedItems() {
      const url = `/products/nomenclatures/selected-related-or-analog-nomenclatures`
      const { data } = await selectItems(url, this.chooseModalSelectedItems)
      this.chooseModalSelectedItems = [];
      if (data) {
        if (this.selectedItems) {
          this.selectedItems.body.push(...data.data.body)
        } else {
          this.selectedItems = data.data
        }
      }
    },
    async onSaveChooseItems() {
      try {
        const data = { data: this.items.body.map(item => ({ id: item.id })) };
        const url = `/products/nomenclatures/update-or-create-related-products/${this.data.id}`;
        const items = await httpClient.post(url, data)
        this.items.body.push(...items.data.data.body)
      } catch (e) {
        console.log(e);
      }
      this.isOpenChooseModal = false
    },
    onCloseChoseModal() {
      this.currentChoseModalPage = 1
      this.isOpenChooseModal = false
      this.itemsToSelect = null
    },
    async onSelectItem(item) {
      const { id: childId } = item;
      const { id } = this.data;

      const data = [{id: childId}]
      await httpClient.post(`${this.resource.ADD_URL}${id}`, {data});

      this.items.body.unshift(item);
      this.isSearchItem = false;
    },
    onSelectProduct(id) {
      const index = this.selectedNomenclatures.findIndex(nomenclature => nomenclature.id === id)
      if (index === -1) {
        this.selectedNomenclatures.push({id})
      } else {
        this.selectedNomenclatures.splice(index, 1)
      }

    },
    onToggleAll(val) {
      if (val) {
        this.selectedNomenclatures = []
      } else {
        this.selectedNomenclatures = this.items.body.map(nomenclature => ({id: nomenclature.id}))
      }
    },
    onClickAddRow() {
      this.isSearchItem = true
    },
    async onClickDelRow() {
      if (this.resource === TABLE_RESOURCES.GROUPS_PRODUCTS) {
        const nomenclatures = this.selectedNomenclatures;
        const url = `${this.resource.DEL_URL}`;
        await httpClient.post(url, {nomenclatures})
      } else {
        const data = this.selectedNomenclatures;
        const url = `${this.resource.DEL_URL}${this.data.id}`;
        await httpClient.post(url, {data})
      }

      if (this.selectedNomenclatures.length === this.items.body.length) {
        this.currentPage = this.currentPage !== 1 ? this.currentPage - 1 : 1
      }
      await this.fetchItems()
    },
    onCLickGenerateBtn() {
      this.$emit('generate', this.data)
    },
    async fetchItemsToSelect() {
      const url = `/products/nomenclatures/get-products-in-related-or-analog-all?page=${this.currentChoseModalPage}`
      const { data } = await httpClient.get(url);

      if (this.itemsToSelect) {
        this.itemsToSelect.body.push(...data.data.body)
      } else {
        this.itemsToSelect = data.data;
      }
    },
    onClickChooseBtn() {
      this.chooseResource = this.resource.NAME
      this.selectedList = this.items.body
      this.selectedItems = null
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
    onCloseChangePriceWarningModal() {
      this.isOpenChangePriceWarningModal = false
    },
    formatDate(value) {
      const newValue = moment(value).format('DD.MM.YYYY')
      return newValue.split('/').join('.')
    },
    onCloseChangePriceModal() {
      this.isOpenChangePriceModal = false
    },
    async onSavePriceChanges(data) {
      const url = '/products/nomenclatures/change-price-nomenclature-history';
      const item = this.items.body.find(item => item.id === data.id);

      const alertParams = {
        textItems: [
          {text: 'prices_history.first_part', style: 'normal', translate: true},
          {text: item.cells.title_price, style: 'normal', translate: false},
          {text: 'prices_history.second_part', style: 'normal', translate: true},
          {text: this.formatDate(item.cells.date), style: 'normal', translate: false}
        ],
        link: false
      }

      await httpClient.put(url, data)
      item.cells.price = data.value
      this.$emit('show-alert', alertParams)
      this.isOpenChangePriceModal = false
    },
    onClickChangeRow() {
      this.isOpenChangePriceWarningModal = true
      this.editedPrice = this.selectedPrices[0]
    },
    onContinueUpdatePrice() {
      this.isOpenChangePriceWarningModal = false
      this.isOpenChangePriceModal = true
    },
    async onClickDelPrices() {
      const url = '/products/prices/nomenclatures/deletePrices'
      await httpClient.post(url, {data: this.selectedPrices})
      this.items.body = this.items.body.filter(item => !this.selectedPrices.find(nomenclature => nomenclature.id === item.id))
      this.selectedItems = []
    },
    selectItem(id) {
      const index = this.selectedPrices.findIndex(price => price.id === id)
      if (index === -1) {
        this.selectedPrices.push({id})
      } else {
        this.selectedPrices.splice(index, 1)
      }

    },
    toggleAll() {
      if (this.isAllSelected) {
        this.selectedPrices = []
      } else {
        this.selectedPrices = this.items.body.map(item => ({id: item.id}))
      }

    }
  }
}
</script>
<style scoped lang="scss">
.table-responsive {
  max-height: 380px;
}

.dialog.modal-container .dialog-footer {
  padding-top: 0;
}

.table-actions {
  align-items: center;

  &-left {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-grow: 2;
  }

  &-rifht {

  }
}

.popup-buttons {
  display: flex;
  justify-content: center;
  width: 100%;
  padding: 30px 0 50px;
}

.transparent-btn {
  min-width: 160px;
  margin-left: 10px;
  margin-right: 10px;
  background-color: #fff;
  color: #9DCCFF;

  &:hover {
    background-color: #9DCCFF;
    border: 1px solid #9DCCFF;
    color: #fff;
  }
}

.td-checkbox {
  width: 25px;
  padding-right: 20px !important;
}

.accompanying {
  .table-wrapper {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;
    min-height: 380px;
    overflow: hidden;
  }

  padding: 25px 30px 0;

  &-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;

    .label-title {
      width: auto;
      white-space: nowrap;
    }

    &-right {
      width: 100%;
      display: flex;
      justify-content: flex-end;
    }

    .search-block {
      max-width: 392px;

      input {
        border: 1px solid #9DCCFF;
      }
    }


  }
}


.table-layout-fixed {
  table {
    table-layout: fixed;
  }
}
</style>
