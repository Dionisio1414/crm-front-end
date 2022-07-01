<template>
  <div class="dialog-wrap stock">
    <v-dialog
        :value="!!stockModal"
        max-width="1230px"
        class="dialog-large"
        content-class="stockContent"
        @click:outside="onClose"
    >
      <div class="dialog stockBody">
        <div class="dialog-header">
          <div class="header-text">
            <svg-sprite width="24" height="16" :icon-id="isCapitalizedDocument ? 'cloudFull' : 'cloud'"></svg-sprite>
            <span class="text">{{ getStockModalTitle }}</span>
            <span v-if="getItemId" class="idDoc">{{ getItemId }}</span>
          </div>
          <div v-if="isStock" class="sum">{{ $t('page.wareHouses.stockModal.amountOfDeal') }}: <span class="count">{{ amountSum | formatPrice }}</span> Грн</div>
          <header-actions
              @updateClose="onClose"
              @onCloseModal="onClose"
              :id="stockModal"
              :title="getStockModalTitle"
              :is-hide-attach="!isAdd"
              is-hide-dots
          ></header-actions>
        </div>

        <div class="dialog-content dialog-content-large">
          <div class="dialog-content_header" :class="{ 'disabledBlock': isCapitalizedDocument }">
            <div class="left">
              <calendar
                  :date-value="date"
                  :throughTimeObj="throughTimeObj"
                  @updateDate="onDate"
                  format="DD.MM.YYYY HH:mm"
                  custom-class="head-calendar"
                  is-bottom-btn
                  is-choose-time
                  is-through-time
                  isTime
              ></calendar>
              <v-select
                  class="companies"
                  :placeholder="$t('page.wareHouses.stockModal.selectCompany')"
                  :menu-props="{ contentClass: 'companiesDropDown', closeOnContentClick: true }"
                  item-text="title"
                  item-value="id"
                  v-model="form.companyValue"
                  :items="listOrganizations"
                  height="20"
                  dence
                  return-object
              ></v-select>
            </div>
            <div class="right">
              <ware-h-menu
                  :title="$t('page.wareHouses.stockModal.other')"
                  :items="otherItems"
                  wrapperClass="otherWrapper"
                  customClass="otherMenu"
                  customDropDown="otherDropDown"
                  min-width="250"
                  :amountCount="!amountCount"
                  :isCapitalizedDocument="isCapitalizedDocument"
                  color-icon="#9DCCFF"
                  @updateMenu="onChangeValue"
                  left
              >
                <span class="plus">
                  <svg-sprite style="color: rgb(157,204,255)" width="17" height="17" icon-id="snowflake"></svg-sprite>
                </span>
              </ware-h-menu>
            </div>
          </div>
          <div
              class="dialog-content_body"
              :class="{ 'disabledBlock': isCapitalizedDocument }"
          >
            <div class="top">
              <div
                  v-if="allWareHouses"
                  class="wareHouse"
                  :class="{'moveStockWr': isMoveStock, 'active': activeItem}"
              >
                <select-groups
                    :cols="12"
                    customClass="warehouseBinding"
                    :title="(typeof getWarehouseTitle === 'object') ? getWarehouseTitle.warehouseFrom : getWarehouseTitle"
                    :favorite="getFavorite"
                    :isMoveStock="isMoveStock"
                    :wareHouses="allWareHouses"
                    @updateActive="onActive"
                    @updateUnActive="onAnActive"
                    @updateSelectIds="getListIds"
                    @updateWarehouse="getEditItem"
                    @updateWareHouseId="getWareHouseId"
                    isWarehouseBind
                    isGroups
                    isReducePopup
                    isEdit
                ></select-groups>
              </div>
              <div
                  v-if="allWareHouses && isMoveStock"
                  class="wareHouse"
                  :class="{'moveStockWr': isMoveStock, 'active': activeItem}"
              >
                <select-groups
                    :cols="12"
                    customClass="warehouseBinding"
                    :title="getWarehouseTitle.warehouseTo"
                    :isMoveStock="isMoveStock"
                    :wareHouses="allWareHouses"
                    @updateSelectIds="getListIds"
                    @updateWarehouse="getEditItem"
                    @updateWareHouseId="getWareHouseToId"
                    @updateActive="onActive"
                    @updateUnActive="onAnActive"
                    is-default-value
                    isWarehouseBind
                    isEdit
                ></select-groups>
              </div>
              <type-with-tooltip
                  v-if="isStock"
                  :title="$t('page.wareHouses.stockModal.currency')"
                  :text="defaultCurrency && defaultCurrency.title"
                  :tooltipText="$t('page.wareHouses.stockModal.accountingCurrency')"
              ></type-with-tooltip>
              <type-with-tooltip
                  v-if="isStock"
                  :title="$t('page.wareHouses.stockModal.typePriceText')"
                  :text="$t('page.wareHouses.stockModal.typePrice')"
              ></type-with-tooltip>
            </div>
            <div class="center">
              <div class="centerHeader">
                <div class="left"> {{$t('page.wareHouses.stockModal.stock')}} * </div>
                <div class="right" v-if="!isCapitalizedDocument">
                  <custom-btn
                      :title="$t('page.wareHouses.stockModal.choice')"
                      custom-class="choice"
                      color="#5893D4"
                      @updateEvent="onChoice"
                  ></custom-btn>
                  <tooltip-with-icon
                      color="#F4F9FF"
                      :tooltip-text="$t('page.wareHouses.stockModal.addString')"
                      :iconOption="{  width: '14px', height: '14px', iconId: 'plus'}"
                      @updateClickValue="onSearchInput"
                  ></tooltip-with-icon>
                  <tooltip-with-icon
                      :loading="loading"
                      color="#F4F9FF"
                      :tooltip-text="$t('page.wareHouses.stockModal.fillStock')"
                      :iconOption="{  width: '16px', height: '16px', iconId: 'fill'}"
                      @updateClickValue="onFill"
                  ></tooltip-with-icon>
                  <tooltip-with-icon
                      color="#F4F9FF"
                      customClass="delete"
                      :tooltip-text="$t('page.wareHouses.stockModal.deleteString')"
                      :iconOption="{  width: '13px', height: '15px', iconId: 'bin'}"
                      @updateClickValue="onDeleteList"
                  ></tooltip-with-icon>
                </div>
              </div>
              <div class="stockTable" v-if="getHeaders">
                <stock-table
                    :header="getHeaders"
                    :body="getSelectedList"
                    :isStock="isStock"
                    :isWriteOf="isWriteOf"
                    :warehouseId="form.warehouseId"
                    :isSearchInput="isSearchInput"
                    :getFullTime="date"
                    :randomId="randomId"
                    @updateGetListIds="getListIds"
                    @updateSearchInput="onClearSearchInput"
                    @updateAmountSum="onAmountSum"
                    @updateAmountCount="onAmountCount"
                    is-check-item
                    isStockTable
                    ref="table"
                ></stock-table>
              </div>
            </div>
            <v-row v-if="!isMoveStock" class="reason">
              <v-col cols="6">
                <div class="item-name">
                  <div class="label-title">{{ $t('page.wareHouses.stockModal.base') }}</div>
                  <div class="select-wrap">
                    <v-select
                        v-model="form.reason"
                        :items="reasonItems"
                        item-text="title"
                        :menu-props="{ contentClass: 'defaultDropDown reason'}"
                        item-value="id"
                        :no-data-text="$t('page.wareHouses.stockModal.empty')"
                        :placeholder="$t('page.wareHouses.stockModal.selectInventoryCount')"
                        dense
                    ></v-select>
                  </div>
                </div>
              </v-col>
            </v-row>
            <div v-else>
              <delivery-block
                  @updateDeliveryValues="onDeliveryValues"
                  :editDelivery="editDelivery"
                  :cities="cities"
                  :delivery_methods="delivery_methods"
                  :type_deliveries="type_deliveries"
                  :departments="departments"
              ></delivery-block>
            </div>
            <v-row>
              <v-col cols="6">
                <div class="item-name comments">
                  <div class="label-title">{{ $t('page.wareHouses.stockModal.comments') }}</div>
                  <div class="select-wrap">
                    <v-textarea
                        name="comments"
                        height="90"
                        v-model="form.comments"
                        dense
                        solo
                    ></v-textarea>
                  </div>
                </div>
              </v-col>
              <v-col cols="6" class="responsible">
                <div>{{ $t('page.wareHouses.stockModal.responsible') }}: <span>{{ getFullNameUser }}</span></div>
                <div>{{ $t('page.wareHouses.stockModal.dateCreateDocument') }}: <span> {{ form.documentTime }}</span></div>
              </v-col>
            </v-row>
            <annotation :items="annotationItems"></annotation>
          </div>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel"
                :title="$t(`page.suppliers.modal.createGroups.${getCorrectText ? 'close': 'cancel'}`)"
                color="#5893D4"
                @updateEvent="onClose"
                text
            ></custom-btn>
            <popover-menu
                :isCapitalizedDocument="isCapitalizedDocument"
                :isDisabled="!amountCount"
                :isEdit="isEdit"
                @updateSaveDocument="onSaveDocument"
            ></popover-menu>
          </div>
          <chose-wrapper
              v-if="isOpenChooseModal"
              :isOpenChooseModal="isOpenChooseModal"
              @updateChooseModal="onChoseClose"
              @updateGetListIds="getListIds"
              @updateAmountSum="onAmountSum"
              @updateAmountCount="onAmountCount"
              @updateSelectItems="onSelectItems"
              :isStock="isStock"
              :isWriteOf="isWriteOf"
              :isMoveStock="isMoveStock"
              :titleText="titleText"
              :getHeaders="getHeaders"
              :selectedList="getSelectedList"
              :getFullTime="date"
              :warehouseId="form.warehouseId"
          ></chose-wrapper>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
// components
import WareHMenu from "@/components/dashboard/products/wareHouses/WareHMenu/WareHMenu";
import SelectGroups from "@/components/dashboard/products/wareHouses/SelectGroups/SelectGroups";
import StockTable from "@/components/dashboard/products/wareHouses/StockModal/components/StockTable/StockTable";
import PopoverMenu from "@/components/dashboard/products/wareHouses/StockModal/components/PopoverMenu/PopoverMenu";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import Calendar from "@/components/ui/Calendar/Calendar";
import TypeWithTooltip from "./components/TypeWithTooltip";
import TooltipWithIcon from "./components/TooltipWithIcon";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import DeliveryBlock from "@/components/ui/DeliveryBlock/DeliveryBlock";
import Annotation from "@/components/ui/Annotation/Annotation";
// components async
const ChoseWrapper = () => import("@/components/dashboard/products/wareHouses/ChoseWrapper/ChoseWrapper");
// constants
import { MODE_TYPES, TYPE_OF_STOCK_MODAL } from "@/constants/constants";
// helpers
import getDataUser from "@/helper/getDataUser";
import getCurrentDate from "@/helper/getCurrentDate";
import getUniqueId from "@/helper/getUniqueId";
// constants
import { headers } from "./constants";

export default {
  name: "StockModal",
  components: {
    'ware-h-menu': WareHMenu,
    'select-groups': SelectGroups,
    'stock-table': StockTable,
    'calendar': Calendar,
    'type-with-tooltip': TypeWithTooltip,
    'tooltip-with-icon': TooltipWithIcon,
    'popover-menu': PopoverMenu,
    'custom-btn': CustomBtn,
    'header-actions': HeaderActions,
    'chose-wrapper': ChoseWrapper,
    'delivery-block': DeliveryBlock,
    'annotation': Annotation
  },
  props: {
    allWareHouses: Array,
    stockModal: String,
    favorite: Object,
    documentTitle: String,
    documentEditData: Object,
    cities: Array,
    delivery_methods: Array,
    type_deliveries: Array,
    departments: Array,
    titleText: String,
    mark: String
  },
  watch: {
    iDs({warehouse_group_id: val}, {warehouse_group_id: oldVal}) {
      if ((val !== oldVal) && !this.isStock) this.clearSearchItems();
    }
  },
  computed: {
    ...mapGetters(['selectedList', 'list', 'getLists', 'loading']),
    getCorrectText() {
      return this.documentTitle === MODE_TYPES.EDIT ||
             this.documentTitle === MODE_TYPES.MOVE ||
            this.documentTitle === MODE_TYPES.REMOVE
    },
    getSelectedList() {
      return this.selectedList
    },
    getHeaders() {
      const copyHeaders = [...headers];
      if (this.isStock) return copyHeaders;

      return this.getFormatHeaders(copyHeaders);
    },
    getFavorite() {
      if (this.documentTitle) {
        const {warehouses: [{id, title}]} = this.documentEditData;
        return {id, title};
      }

      return this.favorite;
    },
    isAdd() {
      return !this.documentTitle;
    },
    isCopy() {
      return this.documentTitle === MODE_TYPES.COPY;
    },
    getItemId() {
      if (this.documentEditData && !this.isCopy) {
        const { id, convert_id } = this.documentEditData;
        return convert_id ? convert_id: id;
      }
      return '';
    },
    isStock() {
      return this.stockModal === TYPE_OF_STOCK_MODAL.POST
    },
    isWriteOf() {
      return this.stockModal === TYPE_OF_STOCK_MODAL.WRITE_OF
    },
    isMoveStock() {
      return this.stockModal === TYPE_OF_STOCK_MODAL.MOVE_STOCK
    },
    getStockModalTitle() {
      if (this.isStock) {
        if (this.isCopy) {
          return `${this.$t('page.wareHouses.stockModal.inventoryCapitalization')} (${this.$t('page.wareHouses.stockModal.copy')})`;
        }

        return this.$t('page.wareHouses.stockModal.inventoryCapitalization');
      }
      if (this.isWriteOf) {
        if (this.isCopy) {
          return `${this.$t('page.wareHouses.stockModal.inventoryWriteOff')} (${this.$t('page.wareHouses.stockModal.copy')})`;
        }

        return this.$t('page.wareHouses.stockModal.inventoryWriteOff');
      }
      if (this.isMoveStock) {
        if (this.isCopy) {
          return `${this.$t('page.wareHouses.stockModal.StockTransfer')} (${this.$t('page.wareHouses.stockModal.copy')})`;
        }

        return this.$t('page.wareHouses.stockModal.StockTransfer');
      }
      return '';
    },
    getWarehouseTitle() {
      if (this.isMoveStock) {
        return {
          warehouseFrom: this.$t('page.wareHouses.stockModal.warehouseSender'),
          warehouseTo: this.$t('page.wareHouses.stockModal.WarehouseConsignee')
        }
      }

      return this.$t('page.wareHouses.stockModal.warehouse');
    },
    listOrganizations() {
      return this.list('organizations')
    },
    getDefaultCompany() {
      // return this.listOrganizations.find(item => !!item.is_default)
      return this.listOrganizations[0]
    },
    defaultCurrency() {
      const getCurrency = this.getLists('lists')['currencies'];

      return getCurrency.find(item => item.is_default);
    },
    getDataUser() {
      return getDataUser();
    },
    getFullNameUser() {
      const {first_name, last_name, middle_name} = this.getDataUser;
      return last_name + " " + first_name + " " + middle_name;
    },
    isCapitalizedDocument() {
      const { status } = this.documentEditData || {};
      return status === 1;
    },
    otherItems() {
      return [
        {id: 1, title: this.$t('page.wareHouses.otherItems.capitalized'), disabled: true},
        {id: 2, title: this.$t('page.wareHouses.otherItems.cancel'), disabled: true},
        {id: 3, title: this.$t('page.wareHouses.otherItems.delete'), disabled: true},
      ]
    },
    reasonItems() {
      return [
        {id: 1, title: '000000000000001 07.06.2020 инвентаризация запасов'},
        {id: 2, title: '000000000000001 07.07.2020 инвентаризация запасов'},
        {id: 3, title: '000000000000001 07.08.2020 инвентаризация запасов'}
      ]
    },
    getDateDocument() {
      return { value: getCurrentDate(), format: "DD.MM.YYYY HH:mm" }
    },
    isEdit() {
      return this.documentTitle === MODE_TYPES.EDIT
    }
  },
  data: (v) => ({
    activeItem: null,
    annotationItems: [
      `(*) - ${v.$t('annotation.firstText')}`,
      `(**) - ${v.$t('annotation.secondText')}`,
      `(***) - ${v.$t('annotation.thirdText')}`
    ],
    isOpenChooseModal: false,
    isSearchInput: false,
    statusDocument: false,
    throughTimeObj: ["Завтра", "Через неделю", "Послезавтра", "Через месяц"],
    randomId: '',
    date: getCurrentDate(),
    iDs: [],
    amountSum: 0,
    amountCount: 0,
    editDelivery: {},
    form: {
      warehouseId: null,
      warehouseToId: null,
      comments: '',
      reason: '',
      companyValue: null,
      documentTime: '',
      delivery: {
        delivery_methods_id: 1,
        type_deliveries_id: 1,
        department_city_id: '',
        department_id: '',
        ttn_number: '',
        ttn_date: ''
      }
    }
  }),
  methods: {
    ...mapActions([
      'actonDeleteItemList',
      'clearSearchItems',
      'addDocument',
      'addCapitalizedDocument',
      'getFillProducts',
      'editDocument',
      'editCapitalizedDocument',
      'canceledCapitalizedDocument',
      'getEditFillProducts'
    ]),
    onActive() {
      this.activeItem = true;
    },
    onAnActive() {
      this.activeItem = false;
    },
    onDeliveryValues(values) {
      this.form.delivery = {...this.form.delivery, ...values}
    },
    onChoice() {
      this.randomId = getUniqueId();
      this.isOpenChooseModal = true;
    },
    onChoseClose() {
      this.randomId = getUniqueId();
      this.isOpenChooseModal = false
    },
    onAmountSum(value) {
      this.amountSum = value;
    },
    onAmountCount(value) {
      this.amountCount = value;

      this.otherItems.forEach((item) => {
        if (item.id === 1 && !this.isCapitalizedDocument) item.disabled = !this.amountCount
        if (item.id === 2 && !this.isCopy) item.disabled = !this.isCapitalizedDocument
      })
    },
    getFormatHeaders(headers) {
      return headers.map(item => {
        const copy = {...item};
        if (copy.id === 7 || copy.id === 6) {
          copy.is_visible = 0;
          return copy;
        }

        return copy;
      });
    },
    getCorrectItemsData(data) {
      return data.map(item => {
        const {id, count, isPack, price = 0, cells: { packing }} = item;
        const balance_base = (packing && !isPack) ? (count * packing) : count;

        return {id, balance_base, price, is_packing: !isPack}
      })
    },
    async onCanceledDocument() {
      const { id } = await this.documentEditData;
      const values = {
        resource: this.stockModal,
        id
      }

      await this.canceledCapitalizedDocument(values);

      this.$emit('updateStatusDocument');
      this.onAmountCount(this.amountCount);
    },
    async onSaveDocument(point = 0) {
      // const user_id = this.getDataUser.id;
      const currency_id = this.defaultCurrency && this.defaultCurrency.id;

      const {
        warehouseId: warehouse_id,
        comments,
        companyValue,
        delivery,
        warehouseToId
      } = this.form;

      const nomenclatures = this.getCorrectItemsData(this.selectedList);

      const values = {
        resource: this.stockModal,
        updateTable: this.mark,
        nomenclatures,
        to_warehouse_id: warehouseToId,
        comments,
        warehouse_id,
        currency_id,
        price_id: 1,
        responsible_id: 1,
        organization_id: companyValue?.id,
        delivery,
        date: this.date
      }

      if (!this.documentTitle || this.isCopy) {
        point
            ? await this.addDocument(values)
            : await this.addCapitalizedDocument(values);
      }

      if (this.isEdit) {
        const { id } = await this.documentEditData;
        values.id = id;

        point
            ? await this.editDocument(values)
            : await this.editCapitalizedDocument(values);
      }
    },
    getWareHouseId(warehouseId) {
      this.form.warehouseId = warehouseId;
    },
    getWareHouseToId(warehouseId) {
      this.form.warehouseToId = warehouseId;
    },
    onClearSearchInput() {
      this.isSearchInput = false;
    },
    onClose() {
      this.$emit('updateStateModal');
    },
    getEditItem(value) {
      this.$emit('updateWarehouse', value);
    },
    onSearchInput() {
      this.isSearchInput = !this.isSearchInput;
      this.randomId = getUniqueId();
    },
    onDeleteList() {
      this.randomId = getUniqueId();
      if (this.iDs.length) this.actonDeleteItemList(this.iDs);
    },
    getListIds(iDs) {
      this.iDs = iDs;
    },
    onChangeValue(idx) {
      if (idx === 0) this.onSaveDocument();
      if (idx === 1) this.onCanceledDocument();
    },
    onDate(date) {
      this.date = date;
    },
    onTime(time) {
      this.form.time = time;
    },
    getResource(post, writeOff, moveStock, purchases) {
      if (post) return 'receipt_stocks';
      if (writeOff) return 'write_off_stocks'
      if (moveStock) return 'transfer_stocks'
      if (purchases) return 'purchases'
    },
    async onFill() {
      await this.clearSearchItems();
      const query = {
        id: this.form.warehouseId,
        price_id: 1,
        date: this.date
      }

      await this.getFillProducts(query)
    },
    async onEditData() {
      if (this.documentTitle === MODE_TYPES.EDIT || this.isCopy) {
        const { id, document_purchase, document_receipt_stocks, document_write_off_stocks, document_transfer_stocks, date } = this.documentEditData;
        let res = this.getResource(document_receipt_stocks, document_write_off_stocks, document_transfer_stocks , document_purchase);
        const { organization, comments, delivery } = document_purchase || document_receipt_stocks || document_write_off_stocks || document_transfer_stocks;

        this.form.documentTime = this.$options.filters.dateForDatePicker({ value: date, format: "DD.MM.YYYY HH:mm" });
        this.form.companyValue = organization;
        this.form.comments = comments;
        if(this.isMoveStock) this.editDelivery = delivery;

        await this.getEditFillProducts({ id, res });
      }

      if (this.isCopy) {
        this.$emit('updateStatusDocument');
        this.onAmountCount(this.amountCount);
      }
    },
    onSelectItems() {
      this.isOpenChooseModal = false;
    }
  },
  beforeMount() {
    if (this.documentEditData) {
      this.onEditData();
    } else {
      this.form.documentTime = this.$options.filters.dateForDatePicker(this.getDateDocument);
    }

    if (!this.documentTitle && this.getDefaultCompany) {
      this.form.companyValue = this.getDefaultCompany;
    }
  },
  destroyed() {
    this.clearSearchItems();
    this.$store.dispatch('onClearDocumentList');
  }
}
</script>

<style lang="scss">
@import "../../../../../sass/mixins";
@import "stockModal";
</style>
