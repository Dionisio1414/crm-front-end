<template>
  <div class="priceDiscount page with-tabs" :class="{'with-small-nav': isNavSmall}">
    <div class="page-header page-header__with-tabs">
      <div class="priceTitle">{{ $t('crm.priceDiscount.price') }} </div>
      <div class="actions-section">
        <div class="right_block">
          <search-block isFilter></search-block>
          <div class="actions-buttons">
            <custom-btn
                custom-class="orange"
                title="Установить цену"
                color="#5893D4"
                @updateEvent="onHandler"
            ></custom-btn>
          </div>
        </div>
      </div>
    </div>

    <div class="card-grid tabs-grid">
      <transition name="slide-fade">
        <div v-if="!isNavSmall" class="card list-item">
          <btn-nav-reduce :isNavSmall="isNavSmall" @updateBtnValue="onBtnToggle"></btn-nav-reduce>
          <tabs-list
              :tabs="tabs"
              :idx="currentTab.index"
              @updateTabs="checkTab"
          ></tabs-list>
        </div>
      </transition>
      <btn-nav-reduce customClass="listReduced" :isNavSmall="isNavSmall" @updateBtnValue="onBtnToggle"></btn-nav-reduce>
      <div class="card card-table">
        <keep-alive>
          <component
              :is="currentComponent"
              :items="priceDiscountList"
              @editList="onEditItem"
              @updateDeleteItems="deleteModal"
              @change="onChangeData"
          ></component>
        </keep-alive>
      </div>
    </div>
    <modal-delete
        @successDelete="successDelete"
        ref="modalDelete"
        isDocument
    >
    </modal-delete>
    <!--context-menu-->
    <context-menu
        @updateContext="onContextMenu"
        @updateTabs="checkTab"
    ></context-menu>
    <set-price-modal v-if="true" :pricesTable="pricesTable" :isOpen="!isOpen" @updateOpen="onCloseModal"></set-price-modal>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from 'vuex';
// components
import SetPriceModal from "@/components/dashboard/crm/SetPriceModal/SetPriceModal";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import BtnNavReduce from "@/components/dashboard/products/suppliers/BtnNavReduce/BtnNavReduce";
import SearchBlock from "@/components/dashboard/products/suppliers/leftheader/components/SearchBlock";
import ContextMenu from "@/components/dashboard/products/suppliers/ContextMenu/ContextMenu";
import ModalDelete from "@/components/ui/ModalDelete"
import PriceDiscountTable from "@/components/dashboard/crm/PriceDiscountsTable/PriceDiscountTable";
import TabsList from "@/components/dashboard/crm/TabsList/TabsList";

// helpers
import { MODE_TYPES } from "@/constants/constants";

export default {
  name: "PriceDiscounts",
  components: {
    PriceDiscountTable,
    BtnNavReduce,
    SearchBlock,
    ModalDelete,
    ContextMenu,
    TabsList,
    SetPriceModal,
    CustomBtn
  },
  computed: {
    ...mapGetters(['priceDiscountList', 'pricesTable']),
    currentComponent() {
      return this.tabsComponents[this.currentTab.index]
    }
  },
  data: () => ({
    isNavSmall: false,
    search: '',
    editData: null,
    documentEditData: null,
    tabsComponents: ['PriceDiscountTable'],
    isOpen: false,
    currentTab: {
      index: 0
    },
    tabs: [
      {title: "crm.priceDiscount.price"},
    ],
  }),
  methods: {
    ...mapActions(['getPriceDiscountList', 'getPricesTable']),
    onCloseModal() {
      this.isOpen = false;
    },
    onHandler() {
      this.isOpen = true;
    },
    checkTab(index, title) {
      this.isBlockTop = title === "price";
      if (this.currentTab.isEdited && !this.currentTab.isSaved) {
        this.saveConfirmOnChangeTab(index)
      } else {
        this.changeTab(index)
      }
    },
    async saveConfirmOnChangeTab(index) {
      if (await this.$refs.confirmSave.open('Save', 'Are you sure?')) {
        await this.onSaveData()
        this.changeTab(index)
      } else {
        this.changeTab(index)
      }
    },
    changeTab(index) {
      this.currentTab.index = index
      this.currentTab.isEdited = false
      this.currentTab.isSaved = false
    },
    onBtnToggle(value) {
      this.isNavSmall = value;
    },
    onChangeData(data) {
      this.currentTab.isEdited = true
      this.currentTab.formData = data
    },
    onContextMenu(value) {
 /*     if (value === MODE_TYPES.COPY ||
          value === MODE_TYPES.EDIT) {
log
      }*/

      if (value === MODE_TYPES.REMOVE) {
        const {id} = this.editData;

        if (this.isGroup) {
          this.onWareHouseGroupsToArchive(id)
        } else {
          this.onWareHouseToArchive(id);
        }
      }
    },
    deleteModal(itemsToDelete) {
      const { val } = itemsToDelete;
      const directory = '';
      const items = val.length > 1 ? val.length : 'Удалена(ы) цена(ы) товара из базы';

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
    onEditItem() {
      /*const { type, val, title = MODE_TYPES.EDIT } = values;
      if (type === DOCUMENT_TYPES.RECEIPT_STOCKS) {
        this.documentEditData = val || values;
      }*/
    }
  },
  async mounted() {
    await Promise.all([this.getPriceDiscountList(), this.getPricesTable()])
  },
}
</script>

<style scoped lang="scss">
.priceDiscount.page.with-tabs.with-small-nav .card.card-table {
  width: 100% !important;
  overflow-y: hidden;
}

.wareHouseMenu {
  overflow-y: auto;
  position: relative;

  &.wPreloader .priceDiscount {
    opacity: .4;
    pointer-events: none;
  }
}

.other-btn {
  background-color: #9DCCFF !important;
  border-radius: 100px;
  margin-left: 20px;
  width: 73px;
  height: 40px;
  padding: 0 15px;

  span {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
  }
}

.slide-fade-enter-active {
  transition: all .5s ease;
}

.slide-fade-enter, .slide-fade-leave-to {
  transform: translateX(-100%);
}

.tree-menu {
  padding: 20px;
}

.priceDiscount.page.with-tabs {
  .page-header .actions-section {
    width: calc(100% - 280px);
    justify-content: space-between;
    align-items: center;
    margin-left: 20px;

    .actions-buttons {
      margin-left: inherit;

      .v-btn {
        margin-left: 10px;
        width: inherit;
      }
    }
  }

  .card-grid .card.list-item {
    width: 100%;
    max-width: 260px;
  }

  &.with-small-nav .card {
    margin-left: 0;
    overflow-y: inherit;
  }
}

::-webkit-scrollbar {
  width: 2px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #7CE1A4;
}
</style>

<style lang="scss">
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

.priceTitle {
  font-weight: bold;
  font-size: 13px;
  line-height: 13px;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #317CCE;
}

.right_block {
  display: flex;
  align-items: center;
  justify-content: space-between;
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
