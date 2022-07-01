<template>
  <choose-modal
      v-if="isOpenChooseModal"
      @close-modal="onCloseChooseModal"
      @fetch-category-items="onFetchCategoryItemsForChoose"
      @select-item-to-choose="onSelectItemToChose"
      @save-selected-items="onSaveChooseItems"
      @toggle-all-items-to-select="onToggleAllItemsToSelect"
      :selected-items-for-choose="chooseModalSelectedItems"
      @updateRest="onRest"
      :resource="chooseModalResource"
      :categories="documentCategories"
      :title-text="titleText"
      :is-modal-open="isOpenChooseModal"
      :items-to-select="filteredItemsToItems"
      :selected-items="selectedItems"
      :warehouseId="warehouseId"
      :isStock="isStock"
      :getTabIdx="getTabIdx"
      isRest
  >
    <template v-slot:products-actions>
      <div class="headerActions">
        <async-simple-input @updateInput="onInput"></async-simple-input>
        <custom-btn
            title="Добавить"
            custom-class="table-btn"
            :is-disabled="!chooseModalSelectedItems.length"
            color="#5893D4"
            @updateEvent="onClickAddChooseSelectedItems"
        ></custom-btn>
        <custom-btn
            title="Аналоги"
            custom-class="table-btn"
            color="#5893D4"
            @updateEvent="() => false"
        ></custom-btn>
      </div>
    </template>
    <template v-slot:rest>
      <table-rest v-if="rest.length" :rest="rest"></table-rest>
    </template>
    <template v-slot:main-table>
      <div class="stock-header">
        <div class="stock-header-title">выбранные товары</div>
        <custom-btn
            title="Очистить все"
            custom-class="table-btn"
            color="#5893D4"
            :is-disabled="isDisabled"
            @updateEvent="onCleanSelectedItems"
        ></custom-btn>
      </div>
      <stock-table
          :header="getHeaders"
          :isStock="isStock"
          :body="selectedItems"
          customClass="stockTable"
          :getFullTime="getFullTime"
          @updateGetListIds="getListIds"
          @updateAmountSum="onAmountSum"
          @updateAmountCount="onAmountCount"
          @updateDelete="onDelete"
          @updateItems="onItems"
          isCrossDelete
          isStockTable
          ref="table"
      ></stock-table>
    </template>
  </choose-modal>
</template>

<script>
// vuex
import {eventBus} from "@/main";
import {mapActions, mapGetters} from "vuex";
// components
import ChooseModal from "@/components/ui/ChooseModal/ChooseModal";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput";
import StockTable from "@/components/dashboard/products/wareHouses/StockModal/components/StockTable/StockTable";
import TableRest from "./components/TableRest";
// services
import { httpClient } from "@/services";
// constants
import { CHOOSE_MODAL_RESOURCES } from "@/constants/constants";

export default {
  name: "ChoseWrapper",
  components:{
    'choose-modal': ChooseModal,
    'custom-btn': CustomBtn,
    'stock-table': StockTable,
    'async-simple-input': AsyncSimpleInput,
    'table-rest': TableRest
  },
  props: {
    isOpenChooseModal: Boolean,
    getHeaders: Array,
    selectedList: Array,
    getFullTime:String,
    isStock: Boolean,
    isWriteOf: Boolean,
    isMoveStock: Boolean,
    warehouseId: Number,
    getTabIdx: Number,
    titleText: String
  },
  computed: {
    ...mapGetters(['documentCategories', 'documentProduct', 'documentServices', 'rest']),
    filteredRelativelyItems() {
      return this.selectedList;
    },
    filteredItemsToItems() {
      if (this.selectedItems) {
        const body = this.itemsToSelect.body.map(itemToSelect => {
          const concat = [...this.filteredRelativelyItems, ...this.selectedItems];
          if (itemToSelect['is_groups'] && itemToSelect.groups) {
            itemToSelect.groups.forEach(one => {
              const isExist = concat.some(selectedItem => selectedItem.id === one.id);
              this.$set(one, 'dataChecked', isExist);
              one.isDisabled = isExist;
            })
          } else {
            itemToSelect.isDisabled = concat.some(selectedItem => selectedItem.id === itemToSelect.id)
          }

          return itemToSelect;
        })

        return {
          ...this.itemsToSelect,
          body
        }
      }

      return this.itemsToSelect
    },
    isDisabled() {
      return !this.selectedItems || !this.selectedItems.length
    }
  },
  data: ()=> ({
    selectedItems: null,
    chooseModalResource: CHOOSE_MODAL_RESOURCES.POSTING,
    chooseModalSelectedItems: [],
    itemsToSelect: null,
    search: '',
    items: []
  }),
  methods: {
    ...mapActions(['getDocumentCategories', 'getSelectProductAll', 'getSelectServicesAll', 'fillGoodsItems', 'searchProductTable', 'getRest', 'getFillWarehouseProducts']),
    onCloseChooseModal() {
      this.$emit('updateChooseModal')
    },
    async onFetchCategoryItemsForChoose(url) {
      try {
        this.$store.commit('updateChoosePopupPending', true);
        const {data} = await httpClient.get(url)
        this.itemsToSelect = {...data.data}
        this.getFormattedList();
      } catch (e) {
        console.log(e);
      }
      this.$store.commit('updateChoosePopupPending', false);
    },
    async onInput(value) {
      if (value) {
        const values = {
          value,
          parent_id: null
        }

        try {
          const {data} = await this.searchProductTable(values)
          this.itemsToSelect = {...data}
          this.getFormattedList();
        } catch (e) {
          console.log(e);
        }
      } else {
        try {
          const {data} = await httpClient.get(CHOOSE_MODAL_RESOURCES.POSTING.SEARCH)
          this.itemsToSelect = {...data.data}
          this.getFormattedList();
        } catch (e) {
          console.log(e);
        }
      }
    },
    getFormattedList() {
      this.itemsToSelect.body.forEach(item => {
        if (item['is_groups'] && item.groups) {
          item.groups.forEach(group => {
            this.$set(group, 'isDisabled', this.filteredRelativelyItems.some(selectedItem => selectedItem.id === group.id));
            // group.isDisabled = this.filteredRelativelyItems.some(selectedItem => selectedItem.id === group.id)
          })
        } else {
          item.isDisabled = this.filteredRelativelyItems.some(selectedItem => selectedItem.id === item.id)
        }
      })
    },
    onSelectItemToChose(values) {
      if (Array.isArray(values)) {
        values.forEach(itemId => {
          const index = this.chooseModalSelectedItems.findIndex(item => item.id === itemId);
          if (index === -1) {
            this.chooseModalSelectedItems.push({id: itemId})
          } else {
            this.chooseModalSelectedItems.splice(index, 1)
          }
        })
      } else {
        const { id } = values;

        const index = this.chooseModalSelectedItems.findIndex(item => item.id === id);

        if (index === -1) {
          this.chooseModalSelectedItems.push({id})
        } else {
          this.chooseModalSelectedItems.splice(index, 1)
        }
      }

      console.log(this.chooseModalSelectedItems, 'this.chooseModalSelectedItems');
    },
    getIdsToSave() {
      return this.itemsToSelect.body.reduce((acc, item) => {
        if (!item.isDisabled) {
          if (item['is_groups'] && item.groups) {
            let groupsIds = item.groups.filter(body => !body.isDisabled || (!body.isDisabled && !body.checked)).map(body => ({ id: body.id }));
            acc = [...acc, ...groupsIds]
          } else {
            acc.push({ id: item.id })
          }
        }

        return acc;
      }, [])
    },
    onToggleAllItemsToSelect (state) {
      if (!state) {
        this.chooseModalSelectedItems = this.getIdsToSave();
      } else {
        this.chooseModalSelectedItems = []
      }
    },
    onItems(value) {
      this.items = value
    },
    onSaveChooseItems () {
      this.items.forEach(item => {
        const { count, isPack, fullPrice, price = 0 } = item;
        item.cells.units = isPack ? 'шт': 'уп';
        item.cells.balance = isPack ? count : (fullPrice/price);
        item.cells.price = price;
      })

      this.fillGoodsItems(this.items);
      this.$emit('updateSelectItems');
    },
    async onClickAddChooseSelectedItems() {
      const iDs = this.chooseModalSelectedItems;
      const value = {
        nomenclatures: iDs,
        price_id: 1,
        date: this.getFullTime
      }

      const url = this.chooseModalResource[this.getTabIdx ? 'SELECT_SERVICES_ITEMS' : 'SELECT_ITEMS'];

      try {
        const {data} = await httpClient.post(url, value);

        this.chooseModalSelectedItems = []
        if (this.selectedItems) {
          this.selectedItems = [...this.selectedItems, ...data.data.body]
        } else {
          this.selectedItems = data.data.body
        }
      } catch (e) {
        console.log(e);
      }
    },
    onDelSelectedItem(id) {
      const index = this.selectedItems.body.findIndex(item => item.id === id)
      this.selectedItems.body.splice(index, 1)
    },
    getListIds(iDs) {
      this.$emit('updateGetListIds', iDs)
    },
    onAmountSum(value) {
      this.amountSum = value;
      this.$emit('updateAmountSum', value);
    },
    onAmountCount(value) {
      this.$emit('updateAmountCount', value);
    },
    onDelete(idx) {
      this.selectedItems = this.selectedItems.filter((item, index) => index !== idx)
    },
    onCleanSelectedItems() {
      this.selectedItems = [];
    },
    async onRest(id) {
      await this.getRest(id)
    }
  },
  async mounted() {
    eventBus.$on('getAllProduct', async () => {
      await this.getSelectProductAll();
      this.itemsToSelect = this.documentProduct;
    })

    console.log(this.isStock,' this.isStock')

    if (this.isStock || this.isWriteOf || this.isMoveStock) {
      if (this.getTabIdx) {
        await this.getSelectServicesAll();
      } else {
        await this.getSelectProductAll();
      }
    } else {
      await this.getSelectProductAll(this.warehouseId);
    }

    await this.getDocumentCategories();
    this.itemsToSelect = this.getTabIdx ? this.documentServices : this.documentProduct;
    this.getFormattedList();
  }
}
</script>

<style scoped lang="scss">
.stock-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 10px 0;

  &-title {
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #317CCE;
  }
}

.headerActions {
  display: flex;
  align-items: center;

  .search-block {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 100px;
    max-width: 420px;
  }
}

</style>
