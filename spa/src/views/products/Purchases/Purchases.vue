<template>
  <div class="page purchases">
    <div class="page-header">
      <div class="actions-section">
        <div class="actions-section-right">
          <div class="purchases-switcher" v-click-outside="onClickOutside">
            <span
                @click="isOpenPurchaseSwitched = !isOpenPurchaseSwitched"
                class="switched-purchase"
                :class="{'is-open': isOpenPurchaseSwitched}"
            >
              {{ $t(`purchases.${currentPurchase.title}`) }}
              <svg-sprite width="11" height="7" icon-id="arrowUp"></svg-sprite>
            </span>
            <ul
                v-if="isOpenPurchaseSwitched"
                class="purchases-switcher-list"
                :class="{'is-open': isOpenPurchaseSwitched}"
            >
              <li v-for="purchase in purchases" @click="onClickPurchase(purchase)" :key="purchase.id">
                {{ $t(`purchases.${purchase.title}`) }}
              </li>
            </ul>
          </div>
          <warehouse-tabs :default-tab="defaultTab" @updateTabValue="onTabValue" :tabs="tabs"></warehouse-tabs>
          <search-block customClass="searchPurchases" isFilter></search-block>
        </div>
        <div class="actions-section-left">
          <div class="actions-buttons">
            <custom-btn
                custom-class="orangeBtn icon w-156"
                :title="$t('purchases.create')"
                :iconOption="{  w: '14px', h: '14px', iconId: 'plus'}"
                color="#FF9F2F"
                :is-disabled="loading"
                @updateEvent="addNewItem"
            ></custom-btn>
            <ware-h-menu
                :items="tableMenuItems"
                wrapperClass="createWrapper"
                customClass="tableMenu"
                customDropDown="create"
            >
              <svg-sprite style="color: white" width="17" height="17" icon-id="snowflake"></svg-sprite>
            </ware-h-menu>
          </div>
        </div>
      </div>
    </div>
    <purchases-table
        @editList="onEditItem"
        @copyList="onCopyItem"
        @updateDeleteItems="deleteModal"
        :loading="loading"
    ></purchases-table>
    <!--new modal-->
    <purchases-modal
        v-if="purchasesModal"
        :purchasesModal="purchasesModal"
        :documentTitle="documentTitle"
        :documentEditData="documentEditData"
        @updateStatusDocument="onStatusDocument"
        :currencies="currencies"
        :cities="cities.cities"
        :terms_payment="terms_payment"
        :suppliers="suppliers"
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
        :wareHouses="[...wareHouses, ...allWareHouses]"
        :prices-types="pricesTypes"
        :user="user"
        :settings="settings"
        @click-add-supplier="onClickAddSupplier"
        @close-modal="onClosePurchasesModal"
    ></purchases-modal>
    <create-supplier
        @createSupplier="onCreateSupplier"
        :suppliersGroups="suppliersGroupList"
        ref="createSupplier"
    ></create-supplier>
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
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from 'vuex';
// components
import SearchBlock from "@/components/dashboard/products/suppliers/leftheader/components/SearchBlock";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import WareHMenu from "@/components/dashboard/products/wareHouses/WareHMenu/WareHMenu";
import WarehouseTabs from "@/components/dashboard/products/wareHouses/WarehouseTabs/WarehouseTabs";
import PurchasesTable from "@/components/dashboard/products/purchases/PurchasesTable/PurchasesTable";
// Components async
const CreateSupplier = () => import("@/components/dashboard/products/suppliers/modal/CreateSupplier/CreateSupplier");
import Alert from "@/components/dashboard/products/wareHouses/Alert/Alert";
const ModalDelete = () => import("@/components/ui/ModalDelete");
const PurchasesModal = () => import("@/components/dashboard/products/purchases/modal/PurchasesModal/PurchasesModal");
// constants
import {MODE_TYPES, TABLE_ACTIONS, TYPE_OF_STOCK_MODAL} from "@/constants/constants";
// helper
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "Purchases",
  components: {
    PurchasesModal,
    PurchasesTable,
    'ware-h-menu': WareHMenu,
    'warehouse-tabs': WarehouseTabs,
    CustomBtn,
    Alert,
    'modal-delete': ModalDelete,
    'search-block': SearchBlock,
    'create-supplier': CreateSupplier
  },
  watch: {
    editDocumentData(val, oldVal) {
      if (val !== oldVal) {
        this.purchasesModal = null;
        this.defaultTab = getUniqueId();
      }
    },
    $route(to) {
      this.getBookMarks(to.params);
    }
  },
  computed: {
    ...mapGetters({
      companyList: 'list',
      lists: 'getLists',
      purchasesTable: 'purchasesTable',
      suppliers: 'getNomenclatureSuppliers',
      user: 'user',
      wareHouses: 'wareHouses',
      allWareHouses: 'allWareHouses',
      settings: 'getSettings',
      editDocumentData: 'editDocumentData',
      suppliersGroupList: 'suppliersGroupList'
    }),
    tabs() {
      return [
        {title: this.$t('tabs.all'), id: 1},
        {title: this.$t('tabs.carriedOut'), id: 2},
        {title: this.$t('tabs.notCarriedOut'), id: 3},
      ]
    },
    tableActions() {
      return TABLE_ACTIONS
    },
    currencies() {
      return this.lists('lists')['currencies']
    },
    cities() {
      return this.lists('lists')['cities']
    },
    type_warehouse() {
      return this.lists('core_lists')['type_warehouse']
    },
    delivery_methods() {
      return this.lists('core_lists')['delivery_methods']
    },
    source_attractions() {
      return this.lists('core_lists')['source_attractions']
    },
    form_payments() {
      return this.lists('core_lists')['form_payments']
    },
    pricesTypes() {
      return this.lists('lists')['prices_types']
    },
    terms_payment() {
      return this.lists('core_lists')['terms_payment']
    },
    type_deliveries() {
      return this.lists('core_lists')['type_deliveries']
    },
    status_payment() {
      return this.lists('core_lists')['status_payment']
    },
    status_product() {
      return this.lists('core_lists')['status_product']
    },
    counterparties_returns() {
      return this.lists('lists')['counterparties_returns']
    },
    departments() {
      return this.lists('lists')['departments']
    },
    counterparties_cancellations() {
      return this.lists('lists')['counterparties_cancellations']
    },
    organizations() {
      return this.companyList('organizations')
    }
  },
  data() {
    return {
      loading: false,
      defaultTab: '',
      purchasesModal: null,
      documentEditData: null,
      documentTitle: '',
      actions: [
        {type: 'edit'},
        {type: 'copy'},
        {type: 'move'},
        {type: 'delete'}
      ],
      tableMenuItems: [
        {id: 1, title: 'Провести', disabled: false},
        {id: 2, title: 'Отменить проведение', disabled: false}
      ],
      search: '',
      isOpenPurchaseSwitched: false,
      isChild: false,
      groupTitle: 'add',
      currentPurchase: {title: 'purchasingJournalTitle', resource: ''},
      currentTab: {title: 'all', value: 'all'},
      openOtherDropDown: false,
      purchases: [
        {title: 'purchasingJournalTitle', resource: ''},
        {title: 'purchasesTitle', resource: ''},
      ]
    }
  },
  methods: {
    ...mapActions({
      fetchSuppliersList: 'fetchNomenclatureSuppliersList',
      fetchLists: 'fetchLists',
      fetchCompanies: 'fetchCompanies',
      getGroupsList: 'getGroupsList',
      getAllWareHouse: 'getAllWareHouse',
      fetchSettings: 'fetchSettings',
      onDocumentToArchive: 'onDocumentToArchive',
      getSuppliersGroup: 'getSuppliersGroup'
    }),
    onTabValue(id) {
      switch (id) {
        case 2:
          this.$store.dispatch('getFilteredPurchasesTable', {point: true, text: ''});
          break;
        case 3:
          this.$store.dispatch('getFilteredPurchasesTable', {point: false, text: ''});
          break;
        default:
          this.$store.dispatch('getFilteredPurchasesTable', null);
          break;
      }
    },
    onStatusDocument() {
      this.documentEditData.status = 2;
    },
    onEditItem(values) {
      const {val, title = MODE_TYPES.EDIT} = values;
      this.purchasesModal = TYPE_OF_STOCK_MODAL.POST;
      this.documentTitle = title;
      this.documentEditData = val || values;
    },
    onCopyItem(values) {
      const {val, title} = values;
      this.documentTitle = title;
      this.purchasesModal = TYPE_OF_STOCK_MODAL.PURCHASES;
      this.documentEditData = val || values;
    },
    onClickOutside () {
      this.isOpenPurchaseSwitched = false
    },
    onCreateSupplier() {
      this.fetchSuppliersList()
    },
    onClickAddSupplier() {
      this.$refs.createSupplier.onHandlerModal(true)
    },
    onEditList(values) {
      this.groupTitle = MODE_TYPES.EDIT;
      this.$refs.createSupplier.onHandlerModal(true, values)
    },
    onMoveList(values) {
      this.groupTitle = MODE_TYPES.MOVE;

      this.$refs.createSupplier.onHandlerModal(true, values)
    },
    onBtnToggle(value) {
      this.isNavSmall = value;
    },
    onClickRow(itemData) {
      this.$refs.productView.open(itemData)
    },
    onClickPurchase(purchase) {
      this.isOpenPurchaseSwitched = false
      this.currentPurchase = purchase
    },
    onClickTab(tab) {
      this.currentTab = tab
    },
    getPathWay(array, id, pathList) {
      const currentItem = array.find((item) => item.id === id);

      if (currentItem.parent_id) {
        this.getPathWay(array, currentItem.parent_id, pathList);
      }

      pathList.push(currentItem);
      return pathList;
    },
    onContextMenu(value) {
      if (value === MODE_TYPES.COPY) {
        this.editData.copyText = '(копия)';
      }

      if (value === MODE_TYPES.COPY ||
          value === MODE_TYPES.EDIT ||
          value === MODE_TYPES.MOVE) {
        this.groupTitle = value;
        this.isChild = false;

        this.$refs.createGroups.open(this.currentId, this.editData);
      }

      if (value === MODE_TYPES.REMOVE) {
        this.onAddArchive(this.currentId);
      }
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
        updateTable: 'fetchPurchases'
      }
      await this.onDocumentToArchive(values);
    },
    onClosePurchasesModal() {
      this.purchasesModal = null;
      this.documentEditData = null;
      this.documentTitle = '';
    },
    addNewItem() {
      this.purchasesModal = TYPE_OF_STOCK_MODAL.PURCHASES;
    },
    getBookMarks(data) {
      const { params } = data;
      if (params) {
        this.purchasesModal = TYPE_OF_STOCK_MODAL.PURCHASES;
      }
    }
  },
  async created() {
    this.loading = true;
    this.$store.commit('updateClearEditDocumentData');
    await Promise.all([
      this.getSuppliersGroup(),
      this.fetchLists({type: 'core', resources: 'status_payment'}),
      this.fetchLists({type: 'core', resources: 'type_warehouse'}),
      this.fetchLists({type: 'core', resources: 'delivery_methods'}),
      this.fetchLists({type: 'core', resources: 'source_attractions'}),
      this.fetchLists({type: 'core', resources: 'form_payments'}),
      this.fetchLists({type: 'core', resources: 'terms_payment'}),
      this.fetchLists({type: 'core', resources: 'type_deliveries'}),
      this.fetchLists({type: '', resources: 'counterparties_returns'}),
      this.fetchLists({type: '', resources: 'counterparties_cancellations'}),
      this.fetchLists({type: 'core', resources: 'status_product'}),
      this.fetchLists({ type: '', resources: 'employees' }),
      this.fetchLists({type: '', resources: 'positions'}),
      this.fetchLists({type: '', resources: 'currencies'}),
      this.fetchLists({type: '', resources: 'prices_types'}),
      this.fetchLists({ type: 'core', resources: 'sex' }),
      this.fetchLists({ type: 'core', resources: 'type_documents' }),
      this.fetchLists({ type: '', resources: 'countries' }),
      this.fetchLists({type: '', resources: 'cities'}),
      this.fetchLists({type: '', resources: 'departments'}),
      this.fetchLists({type: '', resources: 'units'}),
      this.fetchCompanies('organizations'),
      this.fetchSettings(),
      this.fetchSuppliersList(),
      this.getGroupsList(),
      this.getAllWareHouse()])

    this.getBookMarks(this.$route.params);
    this.loading = false;
  },
}
</script>

<style>
.purchases .v-data-table.table > .v-data-table__wrapper > table > tbody > tr > td {
  padding: 8px 30px;
}
</style>
<style scoped lang="scss">
  @import "purchases";
</style>
