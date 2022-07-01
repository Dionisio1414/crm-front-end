<template>
  <div class="suppliers page with-tabs" :class="{'with-small-nav': isNavSmall}">
    <div class="page-header page-header__with-tabs">
      <tabs-groups></tabs-groups>
      <div class="actions-section">
        <search-block isFilter></search-block>
        <div class="actions-buttons">
          <v-btn :disabled="pending || loading" @click="addNewItem" class="orange-btn">
            <span>
              <svg-sprite width="14" height="14" icon-id="plus"></svg-sprite>
            {{ $t('page.createBtn') }}
            </span>
          </v-btn>
        </div>
      </div>
    </div>
    <div class="card-grid tabs-grid">
      <transition name="slide-fade">
        <div v-if="!isNavSmall" class="card list-item">
          <btn-nav-reduce :isNavSmall="isNavSmall" @updateBtnValue="onBtnToggle"></btn-nav-reduce>
          <left-header @openCreateGroups="onCreateGroups"></left-header>
          <div class="tree-menu suppliersMenu" :class="{'loading': pending || loading}">
            <supplier-groups
                v-for="group in suppliersGroupList"
                :key="group.id"
                :groups="group"
                :label="group.title"
                :depth="0"
                :id="group.id"
                :ref="$refs.menu"
                @updateTableCurrentItem="onTableCurrentItem"
                @createChildrenCategory="addGroup"
            >
            </supplier-groups>
          </div>
        </div>
      </transition>
      <btn-nav-reduce customClass="listReduced" :isNavSmall="isNavSmall" @updateBtnValue="onBtnToggle"></btn-nav-reduce>
      <suppliers-table
          @editList="onEditList"
          @copyList="onCopyList"
          @updateDeleteItems="onDeleteList"
          @moveList="onMoveList"
          @updateRow="onClickRow"
          :loading="loading"
      ></suppliers-table>
    </div>
    <!--new modal-->
    <create-groups
        :groupTitle="groupTitle"
        :isChild="isChild"
        :suppliersGroups="suppliersGroupList"
        ref="createGroups"
    ></create-groups>
    <success-group
        v-if="!!isGroupSuccess"
        :isGroupSuccess="isGroupSuccess"
        :groupTitle="groupTitle"
        @updateInfoSupplier="onInfoSupplier"
    ></success-group>
    <move-suppliers
        v-if="isMoveSuppliers"
        :editData="editData"
        :isModal="isMoveSuppliers"
        :items="suppliersGroupList"
        @updateStateModal="onCloseModal"
    ></move-suppliers>
    <supplier-card
        v-if="isSupplierCard"
        :isSupplierCard="isSupplierCard"
        :flatList="flatList"
        :supplierData="supplierEditData"
        ref="supplierCard"
        @updateSupplierCard="closeSupplierCard"
        @updateEdit="onEditList"
        @updatePurchasesItem="addPurchasesItem"
    ></supplier-card>
    <create-supplier
        :suppliersGroups="suppliersGroupList"
        @updateSuppliersList="onSuppliersList"
        ref="createSupplier"
    ></create-supplier>
    <purchases-modal
        v-if="purchasesModal"
        :purchasesModal="purchasesModal"
        :documentTitle="documentTitle"
        :documentEditData="documentEditData"
        @updateStatusDocument="onStatusDocument"
        :currencies="currencies"
        :cities="cities"
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
        :wareHouses="[...wareHouses, ...allWareHouses]"
        :prices-types="pricesTypes"
        :supplierId="supplierEditData"
        :user="user"
        :settings="getSettings"
        @click-add-supplier="onClickAddSupplier"
        @close-modal="onClosePurchasesModal"
        isHideAttach
    ></purchases-modal>
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
        isView
        isMove
        @updateContext="onContextMenu"
    ></context-menu>
  </div>
</template>

<script>
// vuex
import { mapActions, mapGetters } from 'vuex';
// components
import SearchBlock from "@/components/dashboard/products/suppliers/leftheader/components/SearchBlock";
import SupplierGroups from "@/components/dashboard/products/suppliers/SupplierGroups/SupplierGroups";
import SuppliersTable from "@/components/dashboard/products/suppliers/SuppliersTable/SuppliersTable";
import MoveSuppliers from "@/components/dashboard/products/suppliers/MoveSuppliers/MoveSuppliers";
import BtnNavReduce from "@/components/dashboard/products/suppliers/BtnNavReduce/BtnNavReduce";
import ContextMenu from "@/components/dashboard/products/suppliers/ContextMenu/ContextMenu";
import TabsGroups from "@/components/dashboard/products/suppliers/TabsGroups/TabsGroups";
import LeftHeader from "@/components/dashboard/products/suppliers/leftheader/LeftHeader";
import Alert from "@/components/dashboard/products/wareHouses/Alert/Alert";
// Components async
const CreateGroups = () => import("@/components/dashboard/products/suppliers/modal/CreateGroups/CreateGroups");
const PurchasesModal = () => import("@/components/dashboard/products/purchases/modal/PurchasesModal/PurchasesModal");
const CreateSupplier = () => import("@/components/dashboard/products/suppliers/modal/CreateSupplier/CreateSupplier");
const SuccessGroup = () => import("@/components/dashboard/products/suppliers/modal/SuccessGroup/SuccessGroup");
const SupplierCard = () => import("@/components/dashboard/products/suppliers/modal/SupplierCard/SupplierCard");
const ModalDelete = () => import("@/components/ui/ModalDelete");
// constants
import { MODE_TYPES, TYPE_OF_STOCK_MODAL } from "@/constants/constants";
// helper
import getPathWay from "@/helper/getPathWay";

export default {
  name: "Suppliers",
  components: {
    'supplier-groups': SupplierGroups,
    'create-groups': CreateGroups,
    'left-header': LeftHeader,
    'btn-nav-reduce': BtnNavReduce,
    'search-block': SearchBlock,
    'create-supplier': CreateSupplier,
    'success-group': SuccessGroup,
    'modal-delete': ModalDelete,
    'context-menu': ContextMenu,
    'tabs-groups': TabsGroups,
    'supplier-card': SupplierCard,
    'move-suppliers': MoveSuppliers,
    'purchases-modal': PurchasesModal,
    'suppliers-table': SuppliersTable,
    'alert': Alert
  },
  computed: {
    ...mapGetters([
      'suppliersGroupList',
      'isGroupSuccess',
      'currentId',
      'editData',
      'suppliersDataTable',
      'pending',
      'flatList',
      'supplierData',
      'getNomenclatureSuppliers',
      'wareHouses',
      'allWareHouses',
      'getLists',
      'list',
      'user',
      'getSettings',
      'editDocumentData'
    ]),
    currencies() {
      return this.getLists('lists')['currencies']
    },
    cities() {
      return this.getLists('lists')['cities']
    },
    countries() {
      return this.getLists('lists')['countries']
    },
    terms_payment() {
      return this.getLists('core_lists')['terms_payment']
    },
    type_warehouse() {
      return this.getLists('core_lists')['type_warehouse']
    },
    delivery_methods() {
      return this.getLists('core_lists')['delivery_methods']
    },
    source_attractions() {
      return this.getLists('core_lists')['source_attractions']
    },
    form_payments() {
      return this.getLists('core_lists')['form_payments']
    },
    departments() {
      return this.getLists('lists')['departments']
    },
    status_payment() {
      return this.getLists('core_lists')['status_payment']
    },
    counterparties_cancellations() {
      return this.getLists('lists')['counterparties_cancellations']
    },
    status_product() {
      return this.getLists('core_lists')['status_product']
    },
    type_deliveries() {
      return this.getLists('core_lists')['type_deliveries']
    },
    counterparties_returns() {
      return this.getLists('lists')['counterparties_returns']
    },
    organizations() {
      return this.list('organizations')
    },
    pricesTypes() {
      return this.getLists('lists')['prices_types']
    },
    getSupplierData: {
      get() {
        return this.supplierData
      },
      set(value) {
        this.supplierEditData = value;
      }
    }
  },
  data() {
    return {
      purchasesModal: null,
      documentTitle: '',
      documentEditData: null,
      loading: false,

      isSupplierCard: false,
      isNavSmall: false,
      supplierEditData: null,
      rowId: null,
      search: '',
      isId: null,
      isChild: false,
      groupTitle: 'add',
      isMoveSuppliers: false
    }
  },
  watch: {
    isGroupSuccess(val, oldVal) {
      if (val !== oldVal) {
        this.$refs.createGroups.close();
        this.$refs.createSupplier.onHandlerModal(false);
        this.isMoveSuppliers = false;
      }
    },
    $route(to) {
      if(to.params.params) this.$refs.createSupplier.onHandlerModal(true);
    },
    editDocumentData(val, oldVal) {
      if (val !== oldVal) {
        this.purchasesModal = null;
      }
    },
  },
  methods: {
    ...mapActions([
      'getSuppliersGroup',
      'onAddArchive',
      'getSuppliersTable',
      'fetchLists',
      'onAddArchiveList',
      'getGroupsList',
      'getAllWareHouse',
      'fetchCompanies',
      'fetchNomenclatureSuppliersList',
      'fetchSettings'
    ]),
     async onSuppliersList() {
      await this.fetchNomenclatureSuppliersList()
       this.getSupplierData = this.supplierData
    },
    onEditItem(values) {
      const {val, title = MODE_TYPES.EDIT} = values;
      this.purchasesModal = TYPE_OF_STOCK_MODAL.POST;
      this.documentTitle = title;
      this.documentEditData = val || values;
    },
    addPurchasesItem() {
      this.purchasesModal = TYPE_OF_STOCK_MODAL.PURCHASES;
      this.isSupplierCard = null;
    },
    onStatusDocument() {
      this.documentEditData.status = 2;
    },
    onClickAddSupplier() {
      this.$refs.createSupplier.onHandlerModal(true);
      this.purchasesModal = null;
    },
    onClosePurchasesModal() {
      this.purchasesModal = null;
      this.documentEditData = null;
      this.documentTitle = '';
    },

    onCloseModal() {
      this.isMoveSuppliers = false;
    },
    closeSupplierCard() {
      this.isSupplierCard = false;
      this.getSupplierData = null;
    },
    async onTableCurrentItem(id) {
      const values = {id};
      await this.getSuppliersTable(values);
    },
    async successDelete(data) {
      await this.onAddArchiveList(data);
    },
    onInfoSupplier() {
      this.isSupplierCard = true;
    },
    onDeleteList(itemsToDelete) {
      const { val, name = '' } = itemsToDelete;
      const directory = '';
      const items = val.length > 1 ? `${val.length} поставщиков` : `поставщика(ов) ${name}`;

      this.$refs.modalDelete.data = val;
      this.$refs.modalDelete.open = true;
      this.$refs.modalDelete.options = {
        directory,
        items
      }
    },
    onCopyList(values) {
      this.groupTitle = MODE_TYPES.COPY;
      this.$refs.createSupplier.onHandlerModal(true, values)
    },
    onEditList(values) {
      const { val, value } = values;
      if (value === 'title_supplier') {
        this.getSupplierData = val;
        this.onInfoSupplier();
      } else {
        this.groupTitle = MODE_TYPES.EDIT;
        this.$refs.createSupplier.onHandlerModal(true, values)
      }
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
    addNewItem() {
      this.groupTitle = MODE_TYPES.ADD;

      this.$refs.createSupplier.onHandlerModal(true)
    },
    onCreateGroups() {
      this.groupTitle = undefined;
      this.isId = null;
      this.isChild = false;

      this.$refs.createGroups.open()
    },
    addGroup(value) {
      this.groupTitle = undefined;
      this.isId = this.currentId ? this.currentId : value.id;
      this.isChild = value.isChild;

      this.$refs.createGroups.open(this.isId ,null, getPathWay(this.flatList, this.isId));
    },
    onContextMenu(value) {
      if (value === MODE_TYPES.COPY) {
        this.editData.copyText = '(копия)';
      }

      if (value === MODE_TYPES.MOVE) {
        this.isMoveSuppliers = true;
        this.groupTitle = value;
        this.isChild = false;
      }

      if (value === MODE_TYPES.COPY || value === MODE_TYPES.EDIT) {
        this.groupTitle = value;
        this.isChild = false;

        this.$refs.createGroups.open(this.currentId, this.editData);
      }

      if (value === MODE_TYPES.REMOVE) {
        this.onAddArchive(this.currentId);
      }
    },
    onIdRow(id) {
      this.rowId = id;
    }
  },
  async mounted() {
    this.loading = true;
    await Promise.all([
      this.getSuppliersGroup(),
      this.fetchLists({ type: 'core', resources: 'sex' }),
      this.fetchLists({ type: 'core', resources: 'type_documents' }),
      this.fetchLists({ type: 'core', resources: 'source_attractions' }),
      this.fetchLists({type: 'core', resources: 'status_payment'}),
      this.fetchLists({type: 'core', resources: 'type_warehouse'}),
      this.fetchLists({type: 'core', resources: 'delivery_methods'}),
      this.fetchLists({type: 'core', resources: 'form_payments'}),
      this.fetchLists({type: 'core', resources: 'terms_payment'}),
      this.fetchLists({type: 'core', resources: 'type_deliveries'}),
      this.fetchLists({type: 'core', resources: 'status_product'}),
      this.fetchLists({ type: '', resources: 'currencies' }),
      this.fetchLists({ type: '', resources: 'positions' }),
      this.fetchLists({ type: '', resources: 'employees' }),
      this.fetchLists({ type: '', resources: 'countries' }),
/*      this.fetchLists({ type: '', resources: 'regions' }),
      this.fetchLists({ type: '', resources: 'cities' }),*/
      this.fetchLists({type: '', resources: 'counterparties_returns'}),
      this.fetchLists({type: '', resources: 'counterparties_cancellations'}),
      this.fetchLists({type: '', resources: 'prices_types'}),
      this.fetchLists({type: '', resources: 'departments'}),
      this.fetchLists({type: '', resources: 'units'}),
      this.fetchCompanies('organizations'),
      this.fetchNomenclatureSuppliersList(),
      this.getGroupsList(),
      this.fetchSettings(),
      this.getAllWareHouse()
    ]);

    const { params } = this.$route.params
    params && this.$refs.createSupplier.onHandlerModal(true);
    this.getSupplierData = this.supplierData;
    this.loading = false;
  }
}
</script>

<style scoped lang="scss">
  @import "suppliers";
</style>
<style lang="scss">
.v-data-table.table > .v-data-table__wrapper > table > tbody > tr > td {
  padding: 23px 30px;
}
</style>
