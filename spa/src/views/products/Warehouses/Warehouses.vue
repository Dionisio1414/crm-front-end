<template>
  <div class="warehouse page with-tabs" :class="{'with-small-nav': isNavSmall}">
    <div class="page-header page-header__with-tabs">
      <ware-h-menu
          :title="$t('page.wareHouses.menu.add')"
          :items="menuItems"
          colorIcon="#9DCCFF"
          customClass="addMenu"
          customDropDown="add"
          @updateMenu="onChangeValue"
      >
        <span class="plus">
          <svg-sprite width="14" height="14" icon-id="plus"></svg-sprite>
        </span>
      </ware-h-menu>
      <div class="actions-section">
        <switch-nested
          :firstTitle="$t('page.wareHouses.switch.document')"
          :secondTitle="$t('page.wareHouses.switch.goods')"
          @updateSwitch="onSwitch">
        </switch-nested>
        <!--<warehouse-tabs v-if="isSwitchTable" :tabs="tabs"></warehouse-tabs>-->
        <div class="right_block">
          <search-block isFilter></search-block>

          <div class="actions-buttons">
            <ware-h-menu
                v-if="!isSwitchTable"
                :title="$t('page.createBtn')"
                :items="createItems"
                wrapperClass="createWrapper"
                customClass="createMenu"
                customDropDown="create"
                @updateMenu="onCreateStock"
                isIcon
            >
              <svg-sprite width="14" height="18" icon-id="document"></svg-sprite>
            </ware-h-menu>
            <ware-h-menu
                :items="isSwitchTable ? goodsItems : tableMenuItems"
                wrapperClass="createWrapper"
                customClass="tableMenu"
                customDropDown="create"
                @updateMenu="onTableMenu"
            >
              <svg-sprite style="color: white" width="17" height="17" icon-id="snowflake"></svg-sprite>
            </ware-h-menu>
          </div>
        </div>
      </div>
    </div>

    <div class="card-grid tabs-grid">
      <transition name="slide-fade">
        <div v-if="!isNavSmall" class="card list-item">
          <btn-nav-reduce :isNavSmall="isNavSmall" @updateBtnValue="onBtnToggle"></btn-nav-reduce>
          <div class="wareHouseMenu" :class="{ 'wPreloader': pending }">
            <div class="showAll">
              <button @click="onDocumentAll">{{ $t('page.wareHouses.switch.showAll') }}</button>
            </div>
            <collapse-block
                v-if="wareHouseGroupsList"
                :title="$t('page.wareHouses.collapse.groupsOfWarehouses')"
                customClass="wareHouse"
                :isDisabled="!wareHouseGroupsList.length"
            >
              <tree-view
                  :items="wareHouseGroupsList"
                  @updateContextMenu="onContextHandler"
                  @updateBelongItem="getBelongItem"
                  @updateFavorite="onFavorite"
                  :favorite="favorite"
                  :isSwitchTable="isSwitchTable"
                  isHoverable
              ></tree-view>
            </collapse-block>
            <collapse-block
                v-if="wareHouses"
                :title="$t('page.wareHouses.collapse.warehousesOutGroup')"
                customClass="wareHouse border"
                :isDisabled="!wareHouses.length"
            >
              <tree-view
                  :items="wareHouses"
                  @updateContextMenu="onContextHandler"
                  @updateBelongItem="getBelongItem"
                  @updateFavorite="onFavorite"
                  :favorite="favorite"
                  :isSwitchTable="isSwitchTable"
                  isHoverable
              ></tree-view>
            </collapse-block>
          </div>
        </div>
      </transition>
      <btn-nav-reduce customClass="listReduced" :isNavSmall="isNavSmall" @updateBtnValue="onBtnToggle"></btn-nav-reduce>
      <div class="card card-table">
        <document-table
            v-if="!isSwitchTable"
            @editList="onEditItem"
            @copyList="onCopyList"
            @updateDeleteItems="deleteModal"
        ></document-table>
        <goods-table @updateSelectedItems="onSelectedItems" v-else></goods-table>
      </div>
    </div>
    <!--new modal-->
    <create-group-ware-houses
        v-if="isGroupWareHouse"
        :isGroupWareHouse="isGroupWareHouse"
        :wareHouseGroupsList="wareHouseGroupsList"
        :editData="editData"
        @updateStateModal="onCloseModal"
        :groupTitle="groupTitle"
        :wareHouses="wareHouses"
        ref="createGroupWareHouses"
    ></create-group-ware-houses>
    <create-ware-house
        v-if="isWareHouse"
        :isWareHouse="isWareHouse"
        :editData="editData"
        @updateStateModal="onCloseModal"
        :groupTitle="groupTitle"
        :wareHouses="wareHouseGroupsList"
        ref="createWareHouses"
    ></create-ware-house>
    <ware-house-card
        v-if="isWareHousesCard"
        :isWareHousesCard="isWareHousesCard"
        :editData="editData"
        :wareHouses="wareHouseGroupsList"
        :requestSuccess="requestSuccess"
        @updateStateModal="onCloseModal"
        @updateEditWareHouse="onEditWareHouse"
        ref="wareHousesCard"
    ></ware-house-card>
    <move-warehouse
        v-if="isMoveWareHouse"
        :editData="editData"
        :isMoveWareHouse="isMoveWareHouse"
        :items="wareHouseGroupsList"
        @updateStateModal="onCloseModal"
    ></move-warehouse>
    <!--create stock-->
    <stock-modal
        v-if="stockModal && favorite"
        :stockModal="stockModal"
        :allWareHouses="allWareHouses"
        :documentEditData="documentEditData"
        :documentTitle="documentTitle"
        :favorite="favorite"
        :delivery_methods="delivery_methods"
        :cities="cities.cities"
        :type_deliveries="type_deliveries"
        :departments="departments"
        :titleText="getTitleText"
        mark="getDocumentTable"
        @updateStateModal="onCloseStockModal"
        @updateWarehouse="onStockWarehouseEdit"
        @updateStatusDocument="onStatusDocument"
    ></stock-modal>
    <!--create stock-end-->
    <purchases-modal
        v-if="purchasesModal"
        :purchasesModal="purchasesModal"
        :documentTitle="documentTitle"
        :documentEditData="documentEditData"
        :currencies="currencies"
        :cities="cities.cities"
        :terms_payment="terms_payment"
        :suppliers="getNomenclatureSuppliers"
        :type_warehouse="type_warehouse"
        :delivery_methods="delivery_methods"
        :source_attractions="source_attractions"
        :form_payments="form_payments"
        :departments="departments"
        :status_payment="status_payment"
        :counterparties_cancellations="counterparties_cancellations"
        :status_product="status_product"
        :type_deliveries="type_deliveries"
        :counterparties_returns="counterparties_returns"
        :organizations="organizations"
        :wareHouses="allWareHouses"
        :prices-types="pricesTypes"
        :user="user"
        :settings="getSettings"
        mark="getDocumentTable"
        @updateStatusDocument="onStatusDocument"
        @close-modal="onCloseStockModal"
    ></purchases-modal>
    <alert
        v-if="requestSuccess"
        :groupTitle="groupTitle"
        :requestSuccess="requestSuccess"
        @openInfoBlock="onOpenInfoBlock"
    ></alert>
    <alert
        v-if="editDocumentData"
        :requestSuccess="editDocumentData"
        @openInfoBlock="onEditItem"
    ></alert>
    <modal-delete
        @successDelete="successDelete"
        ref="modalDelete"
        isDocument
    >
    </modal-delete>
    <!--context-menu-->
    <context-menu
        :isView="isGroup"
        :isMove="!!wareHouseGroupsList && !!wareHouseGroupsList.length"
        :isGroup="isGroup"
        @updateContext="onContextMenu"
    ></context-menu>
  </div>
</template>

<script>
// vuex
import { mapActions, mapGetters } from 'vuex';
// components async
const StockModal = () => import("@/components/dashboard/products/wareHouses/StockModal/StockModal");
const PurchasesModal = () => import("@/components/dashboard/products/purchases/modal/PurchasesModal/PurchasesModal");
// components
import BtnNavReduce from "@/components/dashboard/products/suppliers/BtnNavReduce/BtnNavReduce";
import SearchBlock from "@/components/dashboard/products/suppliers/leftheader/components/SearchBlock";
import ContextMenu from "@/components/dashboard/products/suppliers/ContextMenu/ContextMenu";
import MoveWareHouse from "@/components/dashboard/products/wareHouses/MoveWareHouse/MoveWareHouse";
import TreeView from "@/components/dashboard/products/wareHouses/TreeView/TreeView";
import DocumentTable from "@/components/dashboard/products/wareHouses/DocumentTable/DocumentTable";
import GoodsTable from "@/components/dashboard/products/wareHouses/GoodsTable/GoodsTable";
import ModalDelete from "@/components/ui/ModalDelete";
// import WarehouseTabs from "@/components/dashboard/products/wareHouses/WarehouseTabs/WarehouseTabs";
// components modal
import CreateGroupWareHouses
  from '@/components/dashboard/products/wareHouses/CreateGroupWareHouses/CreateGroupWareHouses';
import CreateWareHouse from '@/components/dashboard/products/wareHouses/CreateWareHouse/CreateWareHouse';
import WareHMenu from "@/components/dashboard/products/wareHouses/WareHMenu/WareHMenu";
import SwitchNested from "@/components/dashboard/products/wareHouses/SwitchNested/SwitchNested";
import WareHouseCard from "@/components/dashboard/products/wareHouses/WareHouseCard/WareHouseCard";
import Alert from "@/components/dashboard/products/wareHouses/Alert/Alert";
import CollapseBlock from "@/components/dashboard/products/suppliers/CollapseBlock/CollapseBlock";
// helpers
import {DOCUMENT_TYPES, MODE_TYPES, TYPE_OF_STOCK_MODAL} from "@/constants/constants";

export default {
  name: "WareHouses",
  components: {
    'alert': Alert,
    'ware-house-card': WareHouseCard,
    'create-ware-house': CreateWareHouse,
    'create-group-ware-houses': CreateGroupWareHouses,
    "collapse-block": CollapseBlock,
    'btn-nav-reduce': BtnNavReduce,
    'search-block': SearchBlock,
    'goods-table': GoodsTable,
    'move-warehouse': MoveWareHouse,
    'modal-delete': ModalDelete,
    'tree-view': TreeView,
    'document-table': DocumentTable,
    'context-menu': ContextMenu,
    'ware-h-menu': WareHMenu,
    'switch-nested': SwitchNested,
    'stock-modal': StockModal,
    'purchases-modal': PurchasesModal,
    /*'warehouse-tabs': WarehouseTabs*/
  },
  computed: {
    ...mapGetters([
      'wareHouseGroupsList',
      'wareHouses',
      'refContextMenu',
      'requestSuccess',
      'pending',
      'allWareHouses',
      'flatList',
      'favoriteId',
      'editDocumentData',
      'selectedTableItems',
      'user',
      'getLists',
      'list',
      'getSettings',
      'getNomenclatureSuppliers'
    ]),
    currencies() {
      return this.getLists('lists')['currencies']
    },
    getTitleText() {
      switch (this.stockModal) {
        case DOCUMENT_TYPES.RECEIPT_STOCKS:
          return 'Выбрать товары для оприходования';
        case DOCUMENT_TYPES.WRITE_OF_STOCKS:
          return 'Выбрать товары для списания';
        case DOCUMENT_TYPES.TRANSFER_STOCKS:
          return 'Выбрать товары для перемещения';
          default:
            return 'Выбрать товары для закупок'
      }
    },
    pricesTypes() {
      return this.getLists('lists')['prices_types']
    },
    organizations() {
      return this.list('organizations')
    },
    counterparties_returns() {
      return this.getLists('lists')['counterparties_returns']
    },
    status_product() {
      return this.getLists('core_lists')['status_product']
    },
    counterparties_cancellations() {
      return this.getLists('lists')['counterparties_cancellations']
    },
    form_payments() {
      return this.getLists('core_lists')['form_payments']
    },
    status_payment() {
      return this.getLists('core_lists')['status_payment']
    },
    type_warehouse() {
      return this.getLists('core_lists')['type_warehouse']
    },
    source_attractions() {
      return this.getLists('core_lists')['source_attractions']
    },
    terms_payment() {
      return this.getLists('core_lists')['terms_payment']
    },
    departments() {
      return this.getLists('lists')['departments']
    },
    type_deliveries() {
      return this.getLists('core_lists')['type_deliveries']
    },
    cities() {
      return this.getLists('lists')['cities']
    },
    delivery_methods() {
      return this.getLists('core_lists')['delivery_methods']
    },
  },
  watch: {
    selectedTableItems(val) {
      this.formatTableMenu(val)
    },
    requestSuccess(val, oldVal) {
      if (val !== oldVal) {
        this.isGroupWareHouse = false;
        this.isWareHouse = false;
        this.isMoveWareHouse = false;
      }
    },
    editDocumentData(val, oldVal) {
      if (val !== oldVal) {
        this.stockModal = false;
        this.purchasesModal = false;
      }
    },
    $route(to) {
      this.getBookMarks(to.params);
    }
  },
  data() {
    return {
      isNavSmall: false,
      isSwitchTable: false,
      selectedIds: [],
      search: '',
      isId: null,
      isChild: false,
      editData: null,
      documentEditData: null,
      isGroup: null,
      groupTitle: 'add',
      documentTitle: '',
      isGroupWareHouse: false,
      isWareHouse: false,
      isWareHousesCard: false,
      isMoveWareHouse: false,
      stockModal: null,
      purchasesModal: null,
      favorite: null,
      tabs: [
        {id: 1, title: 'Товары'},
        {id: 2, title: 'Наборы'}
      ],
      menuItems: [
        {title: this.$t('page.wareHouses.menu.group')},
        {title: this.$t('page.wareHouses.menu.warehouse')}
      ],
      createItems: [
        {title: this.$t('page.wareHouses.create.add')},
        {title: this.$t('page.wareHouses.create.remove')},
        {title: this.$t('page.wareHouses.create.move')}
      ],
      goodsItems: [
        {title: this.$t('page.wareHouses.create.add')},
        {title: 'Заказ'},
        {title: 'Закупка'}
      ],
      tableMenuItems: [
        {id: 1, title: 'Провести', disabled: false},
        {id: 2, title: 'Отменить проведение', disabled: false}
      ],
      actions: [
        {type: 'edit'},
        {type: 'copy'},
        {type: 'move'},
        {type: 'delete'}
      ]
    }
  },
  methods: {
    ...mapActions([
      'getGroupsList',
      'fetchLists',
      'onWareHouseToArchive',
      'onWareHouseGroupsToArchive',
      'getTableProduct',
      'getAllWareHouse',
      'fetchCompanies',
      'onActionFavorite',
      'getFavorite',
      'onDocumentToArchive',
      'getDocumentTable',
      'fetchItems',
      'getDocPaginationCount',
      'getFillGoods',
       'fetchNomenclatureSuppliersList',
       'fetchSettings',
    ]),
    onCloseChooseModal() {
      this.isOpenChooseModal = false
    },
    async onFavorite(item) {
      const {id, title} = item;
      this.favorite = {id, title};

      if (id) await this.onActionFavorite(id);
    },
    onSwitch(value) {
      this.isSwitchTable = value;
    },
    onCloseModal() {
      this.isGroupWareHouse = false;
      this.isWareHousesCard = false;
      this.isWareHouse = false;
      this.isMoveWareHouse = false;
      this.editData = null;
    },
    onCloseStockModal() {
      this.stockModal = null;
      this.purchasesModal = null;
      this.documentEditData = null;
      this.documentTitle = '';
    },
    onOpenInfoBlock(values) {
      this.editData = values;
      this.isWareHousesCard = true;
    },
    onEditWareHouse(values) {
      this.groupTitle = MODE_TYPES.EDIT;
      this.editData = values;
      this.isWareHouse = true;
    },
    onStockWarehouseEdit(values) {
      this.groupTitle = MODE_TYPES.EDIT;
      this.editData = values;
      this.isWareHouse = true;
    },
    onBtnToggle(value) {
      this.isNavSmall = value;
    },
    onChangeValue(idx) {
      this.groupTitle = MODE_TYPES.ADD;
      this.editData = null;

      if (idx) {
        this.isWareHouse = true;
      } else {
        this.isGroupWareHouse = true;
      }
    },
    formatTableMenu(val) {
      console.log(val)
    },
    onCreateStock(idx) {
      switch (idx) {
        case 1:
          this.stockModal = TYPE_OF_STOCK_MODAL.WRITE_OF;
          break;
        case 2:
          this.stockModal = TYPE_OF_STOCK_MODAL.MOVE_STOCK;
          break;
        default:
          this.stockModal = TYPE_OF_STOCK_MODAL.POST;
          break;
      }
    },
    onSelectedItems(values) {
      this.selectedIds = values.map(val => ({ id: val.id }));
    },
    async onTableMenu(idx) {
      if (this.isSwitchTable) {
        this.onCreateStock(idx)
        const values = {
          nomenclatures: this.selectedIds,
          price_id: 1
        }

        await this.getFillGoods(values)

      } else {
        switch (idx) {
          case 1:
            console.log(idx)
            break;
          default:
            console.log(idx)
            break;
        }
      }
    },
    getBelongItem(item) {
      const isGroup = Object.keys(item).includes('children');

      if (!isGroup) {
        const {id} = item;
        console.log(id)
        // this.getTableProduct(id);
      }
    },
    onContextMenu(value) {
      this.groupTitle = value;

      if (value === MODE_TYPES.VIEW) {
        this.isWareHousesCard = true;
      }

      if (value === MODE_TYPES.COPY ||
          value === MODE_TYPES.EDIT) {

        if (this.isGroup) {
          this.isGroupWareHouse = true;
        } else {
          this.isWareHouse = true;
        }
      }

      if (value === MODE_TYPES.MOVE) {
        this.isMoveWareHouse = true;
      }

      if (value === MODE_TYPES.REMOVE) {
        const {id} = this.editData;

        if (this.isGroup) {
          this.onWareHouseGroupsToArchive(id)
        } else {
          this.onWareHouseToArchive(id);
        }
      }
    },
    onContextHandler(evt, item) {
      console.log(item,'item')
      this.isGroup = Object.keys(item).includes('children');

      this.editData = item;
      this.refContextMenu.open(evt)
    },
    deleteModal(itemsToDelete) {
      const {val, name = ''} = itemsToDelete;
      const directory = '';
      const items = val.length > 1 ? val.length : `документ(ы) ${name} запасов`;

      this.$refs.modalDelete.data = val;
      this.$refs.modalDelete.open = true;
      this.$refs.modalDelete.options = {
        directory,
        items
      }
    },
    async successDelete(arrayId) {
      const values = {
        arrayId,
        updateTable: 'getDocumentTable'
      }

      await this.onDocumentToArchive(values);
    },
    async onDocumentAll() {
      if (this.isSwitchTable) {
        await this.fetchItems({resources: 'products'})
      } else {
        await this.getDocumentTable();
      }

      this.getDocPaginationCount('clear');
    },
    onEditItem(values) {
      const {type, val, title = MODE_TYPES.EDIT} = values;
      if (type === DOCUMENT_TYPES.RECEIPT_STOCKS) {
        this.stockModal = TYPE_OF_STOCK_MODAL.POST;
      }

      if (type === DOCUMENT_TYPES.WRITE_OF_STOCKS) {
        this.stockModal = TYPE_OF_STOCK_MODAL.WRITE_OF;
      }

      if (type === DOCUMENT_TYPES.TRANSFER_STOCKS) {
        this.stockModal = TYPE_OF_STOCK_MODAL.MOVE_STOCK;
      }

      if (type === DOCUMENT_TYPES.PURCHASE) {
        this.purchasesModal = TYPE_OF_STOCK_MODAL.PURCHASES;
      }

      this.documentTitle = title;
      this.documentEditData = val || values;
    },
    onCopyList(values) {
      const {type, val, title} = values;
      if (type === DOCUMENT_TYPES.RECEIPT_STOCKS) {
        this.stockModal = TYPE_OF_STOCK_MODAL.POST;
      }

      if (type === DOCUMENT_TYPES.WRITE_OF_STOCKS) {
        this.stockModal = TYPE_OF_STOCK_MODAL.WRITE_OF;
      }

      if (type === DOCUMENT_TYPES.TRANSFER_STOCKS) {
        this.stockModal = TYPE_OF_STOCK_MODAL.MOVE_STOCK;
      }

      if (type === DOCUMENT_TYPES.PURCHASE) {
        this.purchasesModal = TYPE_OF_STOCK_MODAL.PURCHASES;
      }

      this.documentTitle = title;
      this.documentEditData = val || values;
    },
    onStatusDocument() {
      this.documentEditData.status = 2;
    },
    getBookMarks(data) {
      const { params } = data;
      switch (params) {
        case 'groups':
          this.isGroupWareHouse = true; break;
        case 'warehouse':
          this.isWareHouse = true; break;
        case 'receipt_stocks':
          this.stockModal = 'receipt_stocks'; break;
        case 'write_off_stocks':
          this.stockModal = 'write_off_stocks'; break;
        case 'transfer_stocks':
          this.stockModal = 'transfer_stocks'; break;
          default:
            return false;
      }
    }
  },
  async mounted() {
    this.$store.commit('updateClearEditDocumentData');
    await Promise.all([
      this.getGroupsList(),
      this.getAllWareHouse(),
      this.fetchLists({type: '', resources: 'currencies'}),
      this.fetchLists({type: '', resources: 'employees'}),
      this.fetchLists({type: '', resources: 'countries'}),
      this.fetchLists({type: '', resources: 'regions'}),
      this.fetchLists({type: '', resources: 'cities'}),
      this.fetchLists({type: '', resources: 'units'}),
      this.fetchLists({type: 'core', resources: 'delivery_methods'}),
      this.fetchLists({type: 'core', resources: 'type_deliveries'}),
      this.fetchLists({type: '', resources: 'departments'}),
      this.fetchLists({type: 'core', resources: 'status_payment'}),
      this.fetchLists({type: 'core', resources: 'type_warehouse'}),
      this.fetchLists({type: 'core', resources: 'source_attractions'}),
      this.fetchLists({type: 'core', resources: 'form_payments'}),
      this.fetchLists({type: 'core', resources: 'terms_payment'}),
      this.fetchLists({type: '', resources: 'counterparties_returns'}),
      this.fetchLists({type: '', resources: 'counterparties_cancellations'}),
      this.fetchLists({type: 'core', resources: 'status_product'}),
      this.fetchLists({type: '', resources: 'positions'}),
      this.fetchLists({type: '', resources: 'prices_types'}),
      this.fetchCompanies('organizations'),
      this.fetchNomenclatureSuppliersList(),
      this.fetchSettings(),
      this.getFavorite()
    ]);
    this.favorite = await this.favoriteId;

    this.getBookMarks(this.$route.params);
  }
}
</script>

<style scoped lang="scss">
@import "warehouses";
</style>

<style lang="scss">
.warehouse .v-data-table.table > .v-data-table__wrapper > table > tbody > tr > td {
  padding: 8px 30px;
}

.wareHouseMenu {
  padding: 0;
  overflow-y: auto;

  .mdi-chevron-down::before {
    content: "\F0140";
    color: #5893d4;
    transform: scale(0.8);
  }
}

.menuable__content__active .v-menu__content {
  border-radius: 0 0 20px 20px;
}

.right_block {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  width: 100%;
}

.showAll {
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-decoration-line: underline;
  color: #9DCCFF;
  height: 48px;
  display: flex;
  align-items: center;
  padding: 0 15px;
  border-bottom: 1px solid #E3F0FF;
}

.v-menu__content, .v-autocomplete__content.v-menu__content {
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  border-radius: 0 0 10px 10px;
}
</style>
