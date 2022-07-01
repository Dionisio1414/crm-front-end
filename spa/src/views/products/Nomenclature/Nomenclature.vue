<template>
  <div class="page nomenclature with-tabs" :class="{'with-small-nav': navIsSmall}">
    <div class="page-header page-header__with-tabs">
      <h2 class="page-title">
        <span class="page-title-tab" @click="getCategoriesGoods" :class="{'active': activeTab === 'category'}">по Категориям</span>
        <span class="page-title-tab" @click="getAllGoods" :class="{'active': activeTab === 'all'}">Все</span>
      </h2>
      <div class="right-side">
        <div class="actions-section">
          <div class="tab-headers">
              <span
                  v-for="(resource, index) in resources"
                  :key="resource.MULTIPLE_VALUE"
                  :class="{'active': resource.MULTIPLE_VALUE === currentResource.MULTIPLE_VALUE}"
                  @click="changeResources(index)"
              >
                  {{ $t(`nomenclature.modal_resource.${resource.MULTIPLE_VALUE}`) }}
              </span>
          </div>
          <div class="search-block">
            <input
                v-model="search"
                type="text"
                :placeholder="$t('page.search')"
            >
            <div class="search-icon">
              <svg-sprite icon-id="search" width="17" height="17"></svg-sprite>
            </div>
          </div>
        </div>
        <v-btn v-if="!isUnActual" @click="addNewItem" class="orange-btn">
        <span>
          <svg-sprite icon-id="plus" width="14" height="14"></svg-sprite>
        {{ $t('page.addButton') }}
        </span>
        </v-btn>
      </div>
      <ul class="other-list" v-show="otherDropDown">
        <li class="other-item" v-for="item in otherDropDownInner" @click="goToItemAction(item)"
            :key="item.title">
          {{ item.title }}
        </li>
      </ul>
    </div>
    <div class="card-grid tabs-grid">
      <div class="card list-item">
        <span class="resize-btn" @click="navIsSmall = !navIsSmall">
          <svg-sprite v-if="!navIsSmall" width="12" height="30" icon-id="reduce"></svg-sprite>
          <svg-sprite v-else width="42" height="44" icon-id="reduceSecond"></svg-sprite>
        </span>
        <div class="nomenclature-left-header">
          <div class="search-block">
            <input
                type="text"
                :placeholder="$t('page.search')"
                v-model="searchCategory"
                @keyup="onSearch"
            >
            <div class="search-icon">
              <svg-sprite icon-id="search" width="17" height="17"></svg-sprite>
            </div>
          </div>
          <v-btn icon class="add-new" @click="createCategory(false)">
            <svg-sprite icon-id="plusFill" width="30" height="30"></svg-sprite>
          </v-btn>
        </div>
        <div class="tree-menu" v-if="isExistCategories">
          <supplier-groups
              v-for="group in searchedCategory"
              :key="group.id"
              :groups="group"
              :label="group.title"
              :depth="0"
              :id="group.id"
              :ref="$refs.menu"
              @updateTableCurrentItem="onTableCurrentItem"
              @createChildrenCategory="addChildrenCategory"
          >
          </supplier-groups>
        </div>
        <span v-else class="create-new-category" @click="createCategory(false)">
          Создать категорию
        </span>
      </div>
      <div class="card card-table">
        <nomenclature-table
            v-if="isTableLoaded && tableItems"
            :total="tableItems.total"
            :header=" tableItems.headers"
            :body=" tableItems.body"
            :suppliers="suppliers"
            :resource="currentResource.MULTIPLE_VALUE"
            :actual="!isUnActual"
            :actions="tableActions"
            :empty-text="emptyText"
            :selected-items="selectedNomenclatures"
            @update-item="onUpdateItem"
            @view-item="onViewItemForUpdate"
            @copy-item="onCopyItem"
            @move-item="onMoveItem"
            @delete-item="onDeleteItem"
            @delete-selected-items="onDeleteItem"
            @select-item="onSelectItem"
            @changePrice="onChangePrice"
            @clear-selected-items="onClearSelected"
            @move-selected-items="onMoveSelectedItems"
            @analogs-items="onClickAnalogsItem"
            @related-item="onClickRelatedItem"
            @analogs-item="onClickAnalogsItem"
            @prices-item="onClickPricesItem"
            @toggle-all="onToggleAll"
            @aut-actual-selected-items="onAutActualItems"
            @aut-actual-item="onAutActualItem"
            @to-actual-selected-items="onToActualItems"
            @to-actual-item="onToActualItem"
            @changeProductPrice="onChangeProductPrice"
            @deleteSelected="onDeleteSelected"
            @autActualSelected="onAutActualSelected"
            @click-transfer="onClickTransfer"
            @toggle-visible="onToggleVisible"
            @toggle-visible-group="onToggleVisibleGroup"
            @toggle-visible-child="onToggleVisibleChild"
            @changeProduct="changeProduct"
            @changeGroupsProduct="changeGroupsProduct"
            @changeService="changeService"
            @copy-product="onCopyProduct"
            @scrolledToEnd="onScrollToEnd"
            @toActualSelected="toActualSelected"
            ref="table"
        />
      </div>
    </div>
    <category-create
        :categories="categories"
        :units="units"
        bookMarkTitle="Добавить категорию в номенклатуре"
        bookMarkId="addCategoryNomenclature"
        ref="categoryCreate"
    />
    <category-edit
        v-if="category"
        @updateClose="onClose"
        @show-alert="onShowAlert"
        :categories="categories"
        :category="category"
        ref="editCategory"
    ></category-edit>
    <move-categories
        v-if="categories"
        ref="MoveCategory"
        @save="onSaveMoveCategories"
        :categories="categories"
        isHideBtnAdd
    />
    <create-good
        v-if="isOpenCreateModal"
        @close-modals="onCloseModals"
        @change-group-state="onChangeGroupState"
        @view-item="onViewItemForCreate"
        @toggle-resource="OnToggleModalResource"
        @change-item="onChangeItem"
        @show-alert="onShowAlert"
        :categories="categories"
        @close-modal="isOpenCreateModal = false"
        :is-modal-open="isOpenCreateModal"
        :data="itemToView"
        :units="units"
        :is-group="is_group"
        :resource-index="modalResourceIndex"
        :pricesTypes="pricesTypes.types"
        :countries="countries"
        :suppliers="suppliers"
        :mode="mode"
    />
    <view-item
        v-if="isOpenViewModal"
        @create-item="onCreateItem"
        @close-modal="onCloseViewModal"
        @update="onEditProduct"
        @cLick-products-cases="onClickProductsCases"
        :mode="mode"
        :countries="countries"
        :suppliers="suppliers"
        :resource-index="modalResourceIndex"
        :is-group="is_group"
        :units="units"
        :data="itemToView"
        :fetchedCategory="category"
        :is-modal-open="isOpenViewModal"
        @show-alert="onShowAlert"
    />
    <confirm-generation ref="ConfirmGeneration" />
    <generate-products
        @add-products-to-group="onGenerateProducts"
        ref="generateProducts"
    />
    <transfer-modal
        :categories="categories"
        @save="onSaveTransfer"
        ref="transferModal"
    />
    <change-price-min-price
        v-if="isMinPriceWarning"
        :is-open="isMinPriceWarning"
        :params="minPriceWarningParams"
        @closeModal="closePriceWarningModal"
    ></change-price-min-price>
    <none-categories-modal
        @category-create="onCategoryCreate"
        @close-modal="isOpenNonCategoriesModal = false"
        :is-modal-open="isOpenNonCategoriesModal"
    />
    <table-modal
        v-if="isOpenTableModal"
        @close-modal="isOpenTableModal = false"
        @generate="toGenerateModal"
        :nomenclature-resource="nomenclatureResource"
        :resource="tableModalResources"
        :data="tableModalData"
        :is-modal-open="isOpenTableModal"
    />
    <alert
        @close-alert="onCloseAlert"
        @view-item="onViewItemForUpdate"
        @view-un-actual-items="onClickViewUnActualItems"

        :text-items="alertTextItems" :link="alertLink"
        v-if="isAlertShow"></alert>
    <aut-actual-conformation ref="actualConformationModal"></aut-actual-conformation>
    <context-menu
        isView
        isMove
        @updateContext="onContextMenu"
    ></context-menu>
    <modal-delete
        @successDelete="successDelete"
        ref="modalDelete"
        isDocument
    >
    </modal-delete>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from 'vuex'
import {eventBus} from "@/main"
// components
import CreateGood from "@/components/dashboard/products/nomenclature/modals/CreateGood";
import NomenclatureTable from "@/components/ui/NomenclatureTable";
import ConfirmGeneration from "@/components/dashboard/products/nomenclature/modals/ConfirmGeneration";
import GenerateProducts from "@/components/dashboard/products/nomenclature/modals/GenerateProducts";
import TransferModal from "@/components/dashboard/products/nomenclature/modals/TransferModal";
import SupplierGroups from "@/components/dashboard/products/suppliers/SupplierGroups/SupplierGroups";
import ContextMenu from "@/components/dashboard/products/suppliers/ContextMenu/ContextMenu";
import ChangePriceMinPrice from "@/components/dashboard/products/nomenclature/modals/ChangePriceMinPrice";
import CategoryCreate from "@/components/dashboard/categories/CategoryCreate";
import ViewItem from "@/components/dashboard/products/nomenclature/modals/ViewItem/ViewItem";
import NoneCategoriesModal from "@/components/dashboard/products/nomenclature/modals/NoneCategoriesModal";
import Alert from "@/components/dashboard/products/nomenclature/ui/Alert";
import TableModal from "@/components/dashboard/products/nomenclature/modals/TableModals/TableModal";
import AutActualConformation from "@/components/dashboard/products/nomenclature/ui/AutActualConformation";
import CategoryEdit from "@/components/dashboard/categories/CategoryEdit";
import MoveCategories from "@/components/dashboard/categories/MoveCategories";
// async components
const ModalDelete = () => import("@/components/ui/ModalDelete");
// decorators
import { nomenclatureGetProductDecorator, nomenclaturePostProductDecorator } from '@/decorators';
// constants
import {TABLE_ACTIONS, NOMENCLATURE_RESOURCES, MODE_TYPES} from "@/constants/constants";
import {TABLE_RESOURCES} from "@/constants/nomenclature";

export default {
  name: "Nomenclature",
  components: {
    AutActualConformation,
    TableModal,
    Alert,
    NoneCategoriesModal,
    ViewItem,
    ChangePriceMinPrice,
    TransferModal,
    GenerateProducts,
    ConfirmGeneration,
    NomenclatureTable,
    CreateGood,
    CategoryCreate,
    ContextMenu,
    CategoryEdit,
    SupplierGroups,
    MoveCategories,
    ModalDelete,
  },
  computed: {
    ...mapGetters({
      categories: 'categories',
      category: 'category',
      getLists: 'getLists',
      items: 'nomenclatureItems',
      item: 'nomenclatureItem',
      pagination: 'pagination',
      suppliers: 'getNomenclatureSuppliers',
      itemsToSelect: 'itemsToSelect',
      flatCategoriesList: 'flatCategoriesList',
      currentId: 'currentId',
    }),
    isExistCategories() {
      return Array.isArray(this.categories) ? this.categories.length : this.categories;
    },
    resources() {
      return [
        {...NOMENCLATURE_RESOURCES.PRODUCTS},
        {...NOMENCLATURE_RESOURCES.SERVICES},
        // {...NOMENCLATURE_RESOURCES.KITS},
        {...NOMENCLATURE_RESOURCES.NOT_ACTUAL},
      ]
    },
    isUnActual() {
      return this.currentResource.SINGLE_VALUE === 'not-actual-nomenclatures'
    },
    isNonCategories() {
      if (this.categories) {
        return !this.categories.length
      }

      return false
    },
    isNonItems() {
      if (this.items) {
        return !this.items.body.length
      }

      return false
    },
    emptyText() {
      if (this.isNonCategories) {
        return 'nomenclature.non_categories'
      }

      if (this.isNonItems) {
        return 'nomenclature.non_items'
      }

      return 'page.empty_table_text'
    },
    searchedCategory() {
      return this.searchResult || this.categories
    },
    tableItems() {
      return this.items
    },
    lists() {
      return this.getLists
    },
    units() {
      return this.getLists('lists')['units']
    },
    pricesTypes() {
      return this.getLists('lists')['prices_types']
    },
    countries() {
      return this.getLists('lists')['countries']
    },
    currentResource() {
      return this.resources[this.currentResourceIndex]
    }
  },
  watch: {
    $route(to) {
      this.getBookMarks(to.params);
    }
  },
  data() {
    return {
      isTableLoaded: false,
      tableModalData: null,
      alertTextItems: [],
      nomenclatureResource: null,
      alertLink: false,
      isOpenNonCategoriesModal: false,
      tableModalResources: null,
      navIsSmall: false,
      search: '',
      is_group: false,
      itemToView: null,
      isAlertShow: false,
      selectedItems: [],
      selectedNomenclatures: [],
      mode: TABLE_ACTIONS.CREATE,
      currentResourceIndex: 0,
      searchCategory: '',
      isOpenViewModal: false,
      isOpenTableModal: false,
      chooseModalSelectedItems: [],
      isOpenCreateModal: false,
      searchResult: false,
      activeTab: 'all',
      currentCategory: null,
      otherDropDown: false,
      isEditGroupsProduct: false,
      isMinPriceWarning: false,
      minPriceWarningParams: null,
      editedProductParents: null,
      modalResourceIndex: 0,
      tableActions: [
        { action: TABLE_ACTIONS.UPDATE, disabled: false},
        { action: TABLE_ACTIONS.COPY, disabled: false },
        { action: TABLE_ACTIONS.MOVE, disabled: false },
        { action: TABLE_ACTIONS.DELETE, disabled: false },
        { action: TABLE_ACTIONS.PRICES, disabled: false },
        { action: TABLE_ACTIONS.ANALOGS, disabled: false },
        { action: TABLE_ACTIONS.RELATED, disabled: false },
      ],
      otherDropDownInner: [
        {
          title: ' Цены товара',
          action: this.openPrice
        },
        {
          title: 'Неактульные',
          action: this.getUnactual
        }

      ]
    }
  },
  async created() {
    this.isTableLoaded = true
    await Promise.all([
      this.fetchItems({resources: 'products'}),
      this.fetchCategories(),
      this.fetchData(),
      this.fetchProperties(),
      this.fetchDataCreate(),
      this.fetchLists({type: '', resources: 'units'}),
      this.fetchLists({type: '', resources: 'countries'}),
      this.fetchLists({type: '', resources: 'prices_types'}),
      this.fetchSuppliers(),
      this.resetPagination()
    ])
    this.currentCategory = null;

    this.getBookMarks(this.$route.params);
  },
  methods: {
    ...mapActions({
      fetchCategories: 'fetchCategories',
      fetchItems: 'fetchItems',
      changeVisibility: 'changeVisibility',
      fetchData: 'fetchData',
      fetchProperties: 'fetchProperties',
      fetchItem: 'fetchItem',
      fetchCategoryItems: 'fetchCategoryItems',
      fetchDataCreate: 'fetchDataCreate',
      move: 'moveProducts',
      fetchLists: 'fetchLists',
      loadMoreItems: 'loadMoreItems',
      resetPagination: 'resetPagination',
      addGroupProducts: 'addGroupProducts',
      outActual: 'outActual',
      toActual: 'toActual',
      fetchSuppliers: 'fetchNomenclatureSuppliersList',
      deleteGroupItems: 'deleteGroupItems',
      changeVisibilityGroup: 'changeVisibilityGroup',
      changeVisibilityChild: 'changeVisibilityChild',
      changePrice: "changeNomenclaturePrice",
      createItem: 'createNomenclatureItem',
      fetchItemsForSelect: "fetchItemsForSelect",
      addChooseSelectedItems: "addChooseSelectedItems",
      updateNomenclatureItem: 'updateNomenclatureItem',
      deleteItem: 'deleteItem',
      deleteItems: 'deleteItems',
      outActualItem: 'outActualItem',
      outActualItems: 'outActualItems',
      toActualItem: 'toActualItem',
      toActualItems: 'toActualItems',
      fetchCategory: 'fetchCategory',
      moveCategories: 'moveCategories',
      deleteCategories: 'deleteCategory',
    }),
    async onSaveMoveCategories({ data, alertParams }) {
      await this.moveCategories(data);
      await this.fetchCategories();

      this.onShowAlert(alertParams);
      this.$refs.MoveCategory.close()
    },
    onClose() {
      this.isEditCategories = false;
    },
    async onContextMenu(value) {
      if(value !== MODE_TYPES.REMOVE) await this.fetchCategory(this.currentId);
      if(value === MODE_TYPES.REMOVE) {
        const values = [{ id: this.currentId }]
        await this.deleteCategories(values);
      }

      if(value === MODE_TYPES.COPY) {
        const parent_id = this.category.parent_id || null
        const params = {
          category: this.category,
          mode: value
        }

        this.$refs.categoryCreate.open(parent_id, params);
      }
      if (value === MODE_TYPES.EDIT) {
        this.$refs.editCategory.open(this.category)
      }

      if (value === MODE_TYPES.MOVE) {
        this.$refs.MoveCategory.open(this.category)
      }
    },
    async onTableCurrentItem(id) {
      if (this.currentCategory !== id) {
        this.currentCategory = {id}
        this.activeTab = 'category';
        const values = {
          id,
          resources: this.currentResource.MULTIPLE_VALUE
        };

        await this.fetchCategoryItems(values);
        await this.resetPagination();
      }
    },
    onCategoryCreate() {
      this.isOpenNonCategoriesModal = false
      this.isOpenCreateModal = true
    },
    onClickAnalogsItem(data) {
      this.tableModalResources = TABLE_RESOURCES.ANALOGS
      this.tableModalData = data
      this.isOpenTableModal = true
    },
    onClickRelatedItem(data) {
      this.tableModalResources = TABLE_RESOURCES.RELATED
      this.tableModalData = data
      this.isOpenTableModal = true
    },
    onClickPricesItem(data) {
      this.tableModalResources = TABLE_RESOURCES.PRICES
      this.tableModalData = data
      this.isOpenTableModal = true
    },
    toGenerateModal(data) {
      this.isOpenTableModal = false
      this.$refs.generateProducts.open(data)
    },
    onFetchCategoryItemsForChoose({id}) {
      this.fetchItemsForSelect({id})
    },
    onClickAddChooseSelectedItems() {
      this.addChooseSelectedItems(this.chooseModalSelectedItems)
    },
    onClearSelected() {
      this.selectedItems = []
    },
    onShowAlert({ textItems, link }) {
      this.alertTextItems = textItems;
      this.alertLink = link || false;
      this.showAlert()
    },
    onCloseAlert() {
      this.alertTextItems = []
      this.alertLink = false
      this.isAlertShow = false
    },
    onMoveItem(item) {
      const params = {
        items: [{id: item.id}],
        title: item.title,
        mode: 'move',
        resource: item.resource
      }

      this.$refs.transferModal.open(params)
      this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup)
    },
    onMoveSelectedItems() {
      const selectedNomenclatures = this.selectedNomenclatures.filter(item => !item.isChild)
      const firstItem = selectedNomenclatures[0]
      const params = {
        items: this.selectedNomenclatures.map(item => ({id: item.id})),
        title: selectedNomenclatures.length > 1 ? this.selectedNomenclatures.length : firstItem.title,
        mode: 'move',
        resource: firstItem.resource
      }

      this.$refs.transferModal.open(params)
      this.selectedNomenclatures = this.selectedNomenclatures.filter(item => !item.isChild)
    },
    onSelectItemToChose({id}) {
      const index = this.chooseModalSelectedItems.findIndex(item => item.id === id)
      if (index === -1) {
        this.chooseModalSelectedItems.push({ id })
      } else {
        this.chooseModalSelectedItems.splice(index, 1)
      }
    },
    async onCreateItem({ resourceIndex, data, isGroup }) {
      const resource = this.resources[resourceIndex];

      const params = {
        data: {...data},
        isGroup: !this.modalResourceIndex ? isGroup : '',
        resource: this.currentResource.SINGLE_VALUE
      }

      params.data = nomenclaturePostProductDecorator(params.data, params.isGroup, resource.SINGLE_VALUE)
      if (this.currentResourceIndex !== resourceIndex) {
        this.currentResourceIndex = resourceIndex
      }

      await this.createItem(params);
      await this.fetchItems({ resources: this.currentResource.MULTIPLE_VALUE })
      this.isOpenViewModal = false;
      this.isOpenCreateModal = false;

      const alertParams = {
        textItems: [
          { text: this.mode, style: 'normal', translate: true },
          { text: params.resource, style: 'normal', translate: true },
          { text: data.short_title, style: 'bold', translate: false }
        ],
        link: {
            action: 'view-item', text: 'Показать.',
            actionParams: {
              id: this.item.id,
              resource,
              isGroup
            }
          }
      }

      if (this.item['is_groups'] && !this.modalResourceIndex) {
        const dataForGenerate = {
          id: this.item.id,
          name: data.short_title,
          sku: data.sku || '',
          prices: data.prices,
          characteristic_value: data.characteristic_value,
        }

        await this.waitConfirm(dataForGenerate)
      } else {
        this.onShowAlert(alertParams)
      }
    },
    onUpdateItem({id, isChild, parentId, resource}) {
      this.isEditGroupsProduct = isChild
      this.editedProductParents = parentId
      this.mode = TABLE_ACTIONS.UPDATE
      this.modalResourceIndex = this.resources.findIndex(item => item.SINGLE_VALUE === resource.SINGLE_VALUE)
      this.fetchItem({id, resource}).then((item) => {
            this.onChangeGroupState(!!item['is_groups'])
            this.itemToView = nomenclatureGetProductDecorator(item)

            this.isOpenCreateModal = true
          }
      )
    },

    onChangeItem({item, resource}) {
      const params = {
        isChild: this.isEditGroupsProduct,
        parentId: this.editedProductParents,
        data: nomenclaturePostProductDecorator(item, false, resource),
        resource,
      }

      this.updateNomenclatureItem(params)
      this.isOpenCreateModal = false
    },

    onCopyItem({id, isChild, parentId, resource}) {
      this.isEditGroupsProduct = isChild
      this.editedProductParents = parentId
      this.mode = TABLE_ACTIONS.COPY
      this.modalResourceIndex = this.resources.findIndex(item => item.SINGLE_VALUE === resource.SINGLE_VALUE)
      this.fetchItem({id, resource}).then((item) => {
            this.onChangeGroupState(!!item['is_groups'])
            this.itemToView = nomenclatureGetProductDecorator(item)

            this.isOpenCreateModal = true
          }
      )
    },
    async successDelete(data) {
      const resources = this.currentResourceIndex ? 'services': 'products';

     await this.deleteItem(data);
     await this.fetchItems({ resources });

      const cloneList = this.selectedNomenclatures.map(item => ({...item}));
      const ids = data.map(item => item.id)
      ids.forEach(id => {
        cloneList.splice(cloneList.findIndex(item => item.id === id), 1);
      })

      this.selectedNomenclatures = cloneList;
    },
    onDeleteItem(itemsToDelete) {
      let val = [];
      let name = '';

      if(Array.isArray(itemsToDelete.val)) {
        val = itemsToDelete.val.map(item => ({ id: item.id }))
      } else {
        if (itemsToDelete.products) {
          const { id, products } = itemsToDelete;
          let iDs = products.map(item => ({ id: item.id }))
          val = [...val, ...iDs, {id}];
        } else {
          const { id, title } = itemsToDelete;
          name = title;
          val.push({id})
        }
      }

      const directory = '';
      const items = val.length > 1 ? `${val.length} товара(ов)` : `товар ${name}`;

      this.$refs.modalDelete.data = val;
      this.$refs.modalDelete.open = true;
      this.$refs.modalDelete.options = {
        directory,
        items
      }
    },
    async onAutActualItem(item) {
      const resource = item.resource.SINGLE_VALUE;
      const modalParams = [
        {text: `${resource}.first_part`, style: 'normal', translate: true},
        {text: item.title, style: 'bold', translate: false},
        {text: `${resource}.second_part`, style: 'normal', translate: true},
      ];

      const alertParams = {
        textItems: [
          {text: `outActual.${resource}.first_part`, style: 'normal', translate: true},
          {text: item.title, style: 'bold', translate: false},
          {text: `outActual.${resource}.second_part`, style: 'normal', translate: true},
        ],
        link: {
          action: 'view-un-actual-items',
          text: 'Показать.',
          actionParams: null
        }
      }

      const isSent = await this.$refs.actualConformationModal.open(modalParams, resource, false)
      if(isSent) {
        await this.outActualItem(item)
        this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup)
        this.onShowAlert(alertParams)
      }

    },
    async onAutActualItems() {
      const nomenclatures = this.selectedNomenclatures.filter(item => {
                const isIncludeParent = this.selectedNomenclatures.find(parent => parent.id === item.parentId)
                return !isIncludeParent
      })
      const firstItem = nomenclatures[0]
      const resource = firstItem.resource.SINGLE_VALUE
      const title = nomenclatures.length === 1 ? firstItem.title : nomenclatures.length
      const modalParams = [
        {text: `${resource}.first_part`, style: 'normal', translate: true},
        {text: title, style: 'bold', translate: false},
        {text: `${resource}.second_part`, style: 'normal', translate: true},
      ]

      const alertParams = {
        textItems: [
          {text: `outActual.${resource}.first_part`, style: 'normal', translate: true},
          {text: title, style: 'bold', translate: false},
          {text: `outActual.${resource}.second_part`, style: 'normal', translate: true},
        ],
        link: {
          action: 'view-un-actual-items',
          text: 'Показать.',
          actionParams: null
        }
      }

      const isSent = await this.$refs.actualConformationModal.open(modalParams, resource, false);

      if (isSent) {
        await this.outActualItems(this.selectedNomenclatures)
        this.onShowAlert(alertParams)
        this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup).filter(item => !item.isGroup)
      }
    },
    onClickViewUnActualItems() {
      this.changeResources(3)
    },
    async onToActualItem(item) {
      const resource = item.resource.SINGLE_VALUE
      const modalParams = [
        {text: `toActual`, style: 'normal', translate: true},
        {text: item.title, style: 'bold', translate: false},
      ]
      const alertParams = {
        textItems: [
          {text: `toActual.first_part`, style: 'normal', translate: true},
          {text: item.title, style: 'bold', translate: false},
          {text: `toActual.second_part`, style: 'normal', translate: true},
        ],
        link: {
          action: 'view-un-actual-items',
          text: 'Показать.',
          actionParams: null
        }
      }

      const isSent = await this.$refs.actualConformationModal.open(modalParams, resource, false);
      if (isSent) {
        await this.toActualItem(item)
        this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup)
        this.onShowAlert(alertParams)
      }
    },
    async onToActualItems() {
      const nomenclatures = this.selectedNomenclatures.filter(item => {
                const isIncludeParent = this.selectedNomenclatures.find(parent => parent.id === item.parentId)
                return !isIncludeParent
              })
      const firstItem = nomenclatures[0]
      const resource = firstItem.resource.SINGLE_VALUE
      const title = nomenclatures.length === 1 ? firstItem.title : nomenclatures.length
      const modalParams = [
        {text: `toActual`, style: 'normal', translate: true},
        {text: title, style: 'bold', translate: false},
      ]
      const alertParams = {
        textItems: [
          {text: `toActual.first_part`, style: 'normal', translate: true},
          {text: title, style: 'bold', translate: false},
          {text: `toActual.second_part`, style: 'normal', translate: true},
        ],
        link: {
          action: 'view-actual-items',
          text: 'Показать.',
          actionParams: null
        }
      }
      const isSent = await this.$refs.actualConformationModal.open(modalParams, resource, false);
      if (isSent) {
        await this.toActualItems(this.selectedNomenclatures)
        this.onShowAlert(alertParams)

        this.selectedNomenclatures = this.selectedNomenclatures
            .filter(item => item.isGroup)
            .filter(item => !item.isGroup)
      }
    },
    onSelectItem(newItem) {
      const index = this.selectedNomenclatures.findIndex((item) => item.id === newItem.id)
      if (index === -1) {
        this.selectedNomenclatures.push(newItem)
      } else {
        this.selectedNomenclatures.splice(index, 1)
      }
    },
    onDelSelectedItem(id) {
      const index = this.selectedItems.findIndex(item => item.id === id)
      this.selectedItems.splice(index, 1)
    },
    onCloseViewModal() {
      this.isOpenViewModal = false
    },
    onChangeGroupState(val) {
      this.is_group = val
    },
    OnToggleModalResource(index) {
      this.modalResourceIndex = index
    },
    closePriceWarningModal() {
      this.isMinPriceWarning = false
    },
    onClickProductsCases(data) {
      this.tableModalData = data
      this.isOpenTableModal = true
      this.tableModalResources = TABLE_RESOURCES.GROUPS_PRODUCTS;
    },
    openOtherDropDown() {
      this.otherDropDown = !this.otherDropDown
    },
    goToActual() {
      this.getAllGoods()
    },
    onViewItemForCreate({item}) {
      this.mode = TABLE_ACTIONS.CREATE
      this.itemToView = item
      this.isOpenViewModal = true
    },
    onViewItemForUpdate({id, resource, isGroup}) {
      this.is_group = isGroup || false
      this.mode = TABLE_ACTIONS.UPDATE
      this.modalResourceIndex = this.resources.findIndex(item => item.SINGLE_VALUE === resource.SINGLE_VALUE)

      this.fetchItem({id, resource}).then((item) => {
            this.itemToView = nomenclatureGetProductDecorator(item)
            this.isOpenViewModal = true
          })
    },
    goToItemAction(item) {
      this.otherDropDown = false;
      item.action()
    },
    onChangeProductPrice(data) {
      this.changeProductPrice(data).then(({status, message, currency}) => {
        if (status === 'error') {
          this.isMinPriceWarning = true
          this.minPriceWarningParams = { message, currency }
        }
      })
    },
    onChangeGroupPrice(data) {
      this.changeGroupPrice(data).then(({status, message, currency}) => {
        if (status === 'error') {
          this.isMinPriceWarning = true
          this.minPriceWarningParams = {message, currency}
        }
      })
    },
    onChangeGroupsProductPrice(data) {
      this.changeGroupsProductPrice(data).then(({status, message, currency}) => {
        if (status === 'error') {
          this.isMinPriceWarning = true
          this.minPriceWarningParams = {message, currency}
        }
      })
    },
    getCategoriesGoods() {
      this.activeTab = 'category'
    },
    async changeResources(index) {
      this.isTableLoaded = false;
      this.currentResourceIndex = index;
      this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup);
      await this.fetchItems({resources: this.currentResource.MULTIPLE_VALUE});
      this.isTableLoaded = true;
    },
    onSaveTransfer({mode, items, categoryId, alertParams}) {
      this[mode]({
        data: items,
        id: categoryId
      }).then(() => {
        this.$refs.transferModal.close()
        this.onChangeActiveCategory({id: categoryId})
        this.onShowAlert(alertParams)
      })
    },
    onToggleVisible({params}) {
      this.changeVisibility(params)
    },
    onToggleVisibleGroup({params}) {
      const group = this.products.body.find(item => item.id === params.id)
      this.changeVisibilityGroup({
        items: [...group.groups, {id: group.id}].map(item => {
          return {id: item.id}
        }),
        groupId: group.id,
        visible: params.is_visible
      })
    },
    onToggleVisibleChild({params}) {
      const group = this.products.body.find(item => item.id === params.parentId)
      this.changeVisibilityChild({
        items: [{id: params.id}].map(item => {
          return {id: item.id}
        }),
        groupId: group.id,
        visible: params.is_visible
      })
    },
    changeProduct(itemData) {
      this.isEditGroupsProduct = false;
      this.editedProductParents = null;
      this.mode = TABLE_ACTIONS.UPDATE;

      this.fetchItem({id: itemData.id, resources: 'product'}).then((item) => {
            this.itemToView = nomenclatureGetProductDecorator(item)
            this.isOpenViewModal = true
          })
    },
    changeGroupsProduct(itemData, parentId) {
      this.isEditGroupsProduct = true
      this.editedProductParents = parentId
      this.fetchItem({id: itemData.id, resources: 'product'}).then((item) => {
            this.$refs.productView.open(item, 'edit')
          })
    },
    changeService(itemData) {
      this.isEditGroupsProduct = false
      this.editedProductParents = null
      this.fetchItem({id: itemData.id, resources: 'service'}).then(() => {
            this.$refs.serviceView.open(itemData, 'edit')
          })
    },
    async onGenerateProducts(options) {
      this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup)
      this.$refs.generateProducts.close()
      await this.addGroupProducts(options);
      this.selectedNomenclatures = [];
    },
    onCopyProduct({id, resources}) {
      this.fetchItem({id, resources}).then(() => {
        this.$refs.createGood.open(
            this[resources],
            {
              mode: TABLE_ACTIONS.COPY,

            }
        )
      })
    },
    onScrollToEnd() {
      if (this.pagination.page < this.tableItems['total_page']) {
        const resource = this.currentResource.MULTIPLE_VALUE
        this.loadMoreItems({resource, page: ++this.pagination.page})
      }
    },
    onDeleteSelectedChildren({items, title}) {
      const params = {
        items,
        title,
      }
      this.deleteGroupItems({items: params.items, resources: this.resources})
    },
    onDeleteSelected({items, title}) {
      const params = {
        items: items.map(item => {
          return {id: item.id}
        }),
        title,
      }

      this.deleteItems({ items: params.items, resources: this.resources })
      this.selectedNomenclatures = []
    },
    onChangePrice(params) {
      this.changePrice(params).then(({ status, message = '', currency }) => {
        if (status === 'error') {
          let minPrice = message.match(/\d+/)[0];
          this.isMinPriceWarning = true;
          this.minPriceWarningParams = { message: this.$t(`nomenclature.minPriceText`), currency: currency.toLocaleString(), minPrice, data: params }
        }
      })
    },
    onAutActualSelected({items, title}) {
      const params = {
        items: items.map(item => {
          return {id: item.id}
        }),
        title,
      }
      this.outActual({items: params.items, resources: this.resources})
    },
    toActualSelected({items, title}) {
      const params = {
        items: items.map(item => {
          return {id: item.id}
        }),
        title,
      }
      this.toActual({items: params.items, resources: this.resources})
    },
    onClickTransfer({items, title}) {
      const params = {
        items: items.filter(item => !item.isChild),
        title,
        mode: 'transfer'
      }
      this.$refs.transferModal.open(params)
    },
    onCloseModals() {
      this.$refs.createGood.close()
      this.$refs.productView.close()
    },
    getAllGoods() {
      const resource = this.currentResource.MULTIPLE_VALUE
      this.resetPagination({resources: resource})
      this.currentCategory = null
      this.fetchItems({resources: resource})
      this.activeTab = 'all'
      this.minimizeCategories()
    },
    onEditProduct(data) {
      this.itemToView = data
      this.mode = TABLE_ACTIONS.UPDATE
      this.modalResourceIndex = this.currentResourceIndex
      this.isOpenCreateModal = true
    },
    onEditService(data) {
      this.$refs.createGood.open(data, {mode: TABLE_ACTIONS.UPDATE, resource: 'service'})
    },
    minimizeCategories() {
      eventBus.$emit("minimizeCategories")
    },
    async waitConfirm(data) {
      if (await this.$refs.ConfirmGeneration.open(data.name)) {
        this.$refs.generateProducts.open(data)
      }
    },
    createCategory() {
      this.$refs.categoryCreate.open(null, {category: null, mode: 'create'})
    },
    onSearch() {
      this.searchResult = false
      this.recursiveSearch(this.searchCategory, this.categories)
    },
    onToggleAll(isAllSelected) {
      if (isAllSelected) {
        this.selectedNomenclatures = this.selectedNomenclatures.filter(item => item.isGroup);
      } else {
        eventBus.$emit('selectedAllNomenclatures', isAllSelected)
      }
    },
    addNewItem() {
      if (this.isNonCategories) {
        this.isOpenNonCategoriesModal = true
      } else {
        this.mode = TABLE_ACTIONS.CREATE
        this.modalResourceIndex = this.currentResourceIndex
        this.isOpenCreateModal = true
      }
    },
    recursiveSearch(searchQuery, array) {
      array.map(item => {
        const title = item.title.toLowerCase()
        if (title === searchQuery) {
          this.searchResult = [item]
        } else {
          if (item.children.length > 0) {
            this.recursiveSearch(searchQuery, item.children)
          }
        }
      })
    },
    addChildrenCategory(value) {
      this.groupTitle = undefined;
      this.isId = this.currentId ? this.currentId : value.id;
      this.isChild = value.isChild;

      this.$refs.categoryCreate.open(this.isId , {category: null, mode: 'create'});
    },
    showAlert() {
      this.isAlertShow = true;
      setTimeout(
          () => {
            this.isAlertShow = false
          },
          2500
      )
    },
    onChangeActiveCategory({ id }) {
      if (this.currentCategory !== id) {
        this.currentCategory = { id }
        this.activeTab = 'category'
        this.resetPagination()
        this.fetchCategoryItems({
          id,
          resources: this.currentResource.MULTIPLE_VALUE
        }).then(() => {
          eventBus.$emit('resetSwitchedCategory', id)
        })
      }
    },
    getBookMarks(data) {
      const { params } = data;
      if (params) {
        if(params === 'addCategoryNomenclature') {
          this.$refs.categoryCreate.open(null, { category: null, mode: 'create' });
        }

        if(params === 'goods') {
          this.onCategoryCreate();
        }
      }
    }
  }
}

</script>

<style>
.nomenclature .v-data-table.table > .v-data-table__wrapper > table > tbody > tr > td {
  padding: 8px 30px;
}
</style>
<style scoped lang="scss">
@import "nomenclature";
</style>
