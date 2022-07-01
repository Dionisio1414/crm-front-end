<template>
  <div>
    <modal-container
        custom-dialog-class="purchases-modal dialog-wrap stock stockBody disabledElement"
        @clickOutside="closeModal"
        :dialog="!!purchasesModal"
        :modalWidth="1230"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <svg-sprite width="24" height="16" :icon-id="isCapitalizedDocument ? 'cloudFull' : 'cloud'"></svg-sprite>
            <span class="text">{{ getPurchasesModalTitle }}</span>
            <span v-if="form.organization_convert_id" class="idDoc">{{ form.organization_convert_id }}</span>
          </div>
          <div v-if="amountSum" class="sum">{{ $t('page.wareHouses.stockModal.amountOfDeal') }}: <span class="count">{{ amountSum | formatPrice }}</span> {{currency}}</div>
          <header-actions
              id="purchases"
              :title="getPurchasesModalTitle"
              @onCloseModal="closeModal"
              :is-hide-attach="!isAdd || isHideAttach"
              @updateClose="closeModal"
              isHideDots
          ></header-actions>
        </div>
      </template>
      <template v-slot:main>
        <div class="dialog-content-header" :class="{ 'disabledBlock': isCapitalizedDocument }">
          <div class="left">
            <div class="calendar">
              <calendar
                  @updateDate="onChangeDocumentDate"
                  @updateTime="onTime"
                  :date-value="form.date"
                  :throughTimeObj="throughTimeObj"
                  custom-class="head-calendar"
                  format="DD.MM.YYYY HH:mm"
                  is-bottom-btn
                  is-choose-time
                  is-through-time
                  is-time
              />
            </div>
            <v-select
                class="companies"
                :placeholder="$t('page.wareHouses.stockModal.selectCompany')"
                :menu-props="{ contentClass: 'companiesDropDown defaultDropDown resp', closeOnContentClick: true }"
                item-text="title"
                item-value="id"
                v-model="form.organization_id"
                :items="organizations"
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
        <simplebar  class="dialog-main">
          <form ref="form">
            <v-row>
              <v-col cols="3">
                <div class="select-wrap">
                  <div class="label-title">{{$t('purchases.modal.warehouse')}} *</div>
                  <v-select
                      class="select-switcher"
                      :class="{'error-on-select':  $v.form.warehouse_id.$error }"
                      @blur="$v.form.warehouse_id.$touch()"
                      :items="wareHouses"
                      item-text="title"
                      placeholder="Веберите склад"
                      :menu-props="{ contentClass: 'defaultDropDown resp'}"
                      item-value="id"
                      v-model="form.warehouse_id"
                      :disabled="isCapitalizedDocument"
                      ref="scrollTo"
                      solo
                      dense
                  >
                    <template slot="no-data">
                      <div :value="true" class="no-data">
                        <v-list-item dense class="no-data">
                          Нет склада
                        </v-list-item>
                      </div>
                    </template>
                  </v-select>
                </div>
              </v-col>
              <v-col cols="3">
                <div class="select-wrap">
                  <div class="label-title">{{$t('purchases.modal.supplier')}} *</div>
                  <v-select
                      class="select-switcher"
                      :class="{'error-on-select':  $v.form.supplier_id.$error }"
                      @blur="$v.form.supplier_id.$touch()"
                      placeholder="Выберите поставщика"
                      :menu-props="{ contentClass: 'defaultDropDown resp'}"
                      :items="suppliers"
                      item-text="title"
                      item-value="id"
                      :disabled="isCapitalizedDocument"
                      @change="onChangeSupplier"
                      v-model="form.supplier_id"
                      solo
                      dense
                  >
                    <template slot="no-data">
                      <div :value="true" class="no-data">
                        <v-list-item dense class="no-data">
                          Нет поставщиков
                        </v-list-item>
                      </div>
                    </template>
                    <template v-slot:prepend-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>
                            <div class="add-select-option" @click="onClickAddSupplier">
                              <svg-sprite style="color: rgb(88,147,212)" width="14" height="14" icon-id="plusBlue"></svg-sprite>
                              {{ $t('page.addButton') }}
                            </div>
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-select>
                </div>
              </v-col>
              <v-col cols="3">
                <div class="select-wrap">
                  <div class="label-title">{{$t('purchases.modal.contract')}} *</div>
                  <v-select
                      class="select-switcher"
                      :class="{'error-on-select':  $v.form.contract_id.$error }"
                      @blur="$v.form.contract_id.$touch()"
                      placeholder="Выберите договор"
                      no-data-text="Добавьте новое значение"
                      :menu-props="{ contentClass: 'defaultDropDown resp'}"
                      item-text="title"
                      item-value="id"
                      :items="contracts"
                      @change="onContractChange"
                      :disabled="isCapitalizedDocument || !form.supplier_id"
                      v-model="form.contract_id"
                      solo
                      dense
                  >
                  </v-select>
                </div>
              </v-col>
              <v-col cols="1.5">
                <div class="select-wrap">
                  <div class="label-title">{{$t('purchases.modal.currency')}}</div>
                  <div class="purchases-small-text">
                    {{ currency }}
                  </div>
                </div>
              </v-col>
              <v-col cols="1.5">
                <div class="select-wrap">
                  <div class="label-title">{{$t('purchases.modal.typeOfPrice')}}</div>
                  <div class="purchases-small-text">
                    {{ priceType }}
                  </div>
                </div>
              </v-col>
              <v-col cols="6">
                <v-row
                    :class="{'active' : focusRow === 'payment'}"
                    class="dark-row left">
                  <v-col cols="6">
                    <div class="select-wrap">
                      <div class="label-title">{{$t('purchases.modal.termsOfPayment')}}</div>
                      <v-select
                          class="select-switcher"
                          :placeholder="$t('purchases.modal.selectPaymentTerms')"
                          :items="terms_payment"
                          :menu-props="{ contentClass: 'defaultDropDown resp'}"
                          @focus="changeFocusRow('payment')"
                          @input="checkFocusedRow('payment')"
                          item-text="title"
                          item-value="directory_id"
                          v-model="form.terms_payment_id"
                          :disabled="isCapitalizedDocument"
                          solo
                          dense
                      >
                      </v-select>
                    </div>
                  </v-col>
                  <v-col cols="6">
                    <div class="select-wrap">
                      <div class="label-title">{{$t('purchases.modal.paymentForm')}}</div>
                      <v-select
                          class="select-switcher"
                          :placeholder="$t('purchases.modal.chooseFormOfPayment')"
                          :menu-props="{ contentClass: 'defaultDropDown resp'}"
                          :items="form_payments"
                          @focus="changeFocusRow('payment')"
                          @input="checkFocusedRow('payment')"
                          item-text="title"
                          item-value="directory_id"
                          v-model="form.form_payment_id"
                          :disabled="isCapitalizedDocument"
                          solo
                          dense
                      >
                      </v-select>
                    </div>
                  </v-col>
                </v-row>
                <v-row class="dark-row" :class="{'active': focusRow === 'status'}">
                  <v-col cols="6">
                    <div class="select-wrap">
                      <div class="label-title">{{$t('purchases.modal.status')}} *</div>
                      <v-select
                          class="select-switcher"
                          placeholder="Выберите статус"
                          @focus="changeFocusRow('status')"
                          @input="checkFocusedRow('status')"
                          :menu-props="{ contentClass: 'defaultDropDown resp'}"
                          :items="getStatusProducts"
                          item-text="title"
                          item-value="directory_id"
                          @change="onChangeProductStatus"
                          v-model="form.status_product_id"
                          solo
                          dense
                      >
                      </v-select>
                    </div>
                  </v-col>
                  <v-col cols="6">
                    <div class="select-wrap">
                      <div class="label-title">{{$t('purchases.modal.statusOfPayment')}} *</div>
                      <v-select
                          class="select-switcher"
                          :placeholder="$t('purchases.modal.selectPaymentStatus')"
                          :items="bindStatusItems"
                          @focus="changeFocusRow('status')"
                          @input="checkFocusedRow('status')"
                          :menu-props="{ contentClass: 'defaultDropDown resp'}"
                          item-text="title"
                          :disabled="disabledSelect"
                          @change="onChangePaymentStatus"
                          item-value="directory_id"
                          v-model="form.status_payment_id"
                          solo
                          dense
                      >
                      </v-select>
                    </div>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="6">
                <v-row
                    :class="{'active' : focusRow === 'schedule_dates'}"
                    class="dark-row left">
                  <v-col cols="6">
                    <div class="label-title">{{$t('purchases.modal.dateOfReceiptPlanned')}}</div>
                    <calendar
                        @focus="changeFocusRow('schedule_dates')"
                        @blur="checkFocusedRow('schedule_dates')"
                        @updateDate="onDate"
                        :allowDate="form.date"
                        :date-value="form.receipt_date_scheduled"
                        custom-class="default-calendar"
                        date-key="receipt_date_scheduled"
                        :is-disabled="isCapitalizedDocument"
                        isCloseOnContentClick
                        isAllowDates
                    />
                  </v-col>
                  <v-col cols="6">
                    <div class="label-title">{{$t('purchases.modal.scheduledPaymentDate')}}</div>
                    <calendar
                        @focus="changeFocusRow('schedule_dates')"
                        @blur="checkFocusedRow('schedule_dates')"
                        @updateDate="onDate"
                        :date-value="form.payment_date_scheduled"
                        :allowDate="form.date"
                        custom-class="default-calendar"
                        date-key="payment_date_scheduled"
                        :is-disabled="isCapitalizedDocument"
                        isCloseOnContentClick
                        isAllowDates
                    />
                  </v-col>
                </v-row>
                <v-row class="dark-row right" :class="{'active' : focusRow === 'actual_dates'}">
                  <v-col cols="6">
                    <div class="label-title">{{$t('purchases.modal.dateOfReceiptActual')}}</div>
                    <calendar
                        @updateDate="onDate"
                        @focus="changeFocusRow('actual_dates')"
                        @blur="checkFocusedRow('actual_dates')"
                        placeholder="Введите дату"
                        :allowDate="form.date"
                        :date-value="form.receipt_date_actual"
                        custom-class="default-calendar"
                        date-key="receipt_date_actual"
                        isAllowDates
                        isCloseOnContentClick
                        is-clear
                    />
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols='12'>
                <div class="center">
                  <div class="centerHeader">
                    <div class="left">
                      <warehouse-tabs @updateTabValue="onTabValue" is-small :tabs="tabs"></warehouse-tabs>
                    </div>
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
                          color="#F4F9FF"
                          customClass="delete"
                          :tooltip-text="$t('page.wareHouses.stockModal.deleteString')"
                          :iconOption="{  width: '13px', height: '15px', iconId: 'bin'}"
                          @updateClickValue="onDeleteList"
                      ></tooltip-with-icon>
                    </div>
                  </div>
                </div>
                <div class="stockTable" :class="{ 'disabledBlock': isCapitalizedDocument }" v-if="getHeaders">
                  <keep-alive>
                    <component
                        :is="currentComponent"
                        :header="getHeaders"
                        :tabIdx="getTabIdx"
                        :body="!!getTabIdx ? getServicesList : getSelectedList"
                        :isSearchInput="isSearchInput"
                        @updateGetListIds="getListIds"
                        @updateSearchInput="onClearSearchInput"
                        @updateAmountSum="onAmountSum"
                        @updateAmountCount="onAmountCount"
                        is-check-item
                        is-stock
                    ></component>
                  </keep-alive>
                  <div class="purchasesTotal">
                    <span class="text">{{$t('purchases.modal.total')}}:</span>
                    <div class="count">
                      <span class="totalCount">{{ amountCount }}</span>
                      <span class="totalSum">{{ amountSum }}</span>
                    </div>
                  </div>
                </div>
              </v-col>
              <delivery-block
                  v-if="editDelivery"
                  @updateDeliveryValues="onDeliveryValues"
                  customClass="purchasesDelivery"
                  :editDelivery="editDelivery"
                  :cities="cities"
                  :delivery_methods="delivery_methods"
                  :type_deliveries="type_deliveries"
                  :departments="departments"
                  :isCapitalizedDocument="isCapitalizedDocument"
              ></delivery-block>
              <v-col cols="6">
                <v-row
                    class="dark-row left"
                >
                  <v-col cols="12">
                    <div class="select-wrap">
                      <div class="label-title">{{$t('purchases.modal.reasonReturningItem')}}</div>
                      <v-select
                          class="select-switcher"
                          :placeholder="$t('purchases.modal.selectReasonReturningItem')"
                          :items="counterparties_returns"
                          :menu-props="{ contentClass: 'defaultDropDown resp'}"
                          item-text="title"
                          item-value="id"
                          :disabled="isCapitalizedDocument || !isReturn"
                          v-model="form.directory_return_id"
                          solo
                          dense
                      >
                      </v-select>
                    </div>
                  </v-col>
                  <v-col cols="12">
                    <div class="select-wrap">
                      <div class="label-title">{{$t('purchases.modal.reasonForCancelingPurchase')}}</div>
                      <v-select
                          class="select-switcher"
                          :placeholder="$t('purchases.modal.selectReasonCancelingPurchase')"
                          :items="counterparties_cancellations"
                          :menu-props="{ contentClass: 'defaultDropDown resp'}"
                          item-text="title"
                          item-value="id"
                          :disabled="isCapitalizedDocument || !isReturn"
                          v-model="form.directory_cancell_id"
                          solo
                          dense
                      >
                      </v-select>
                    </div>
                  </v-col>
                </v-row>
                <v-row class="dark-row right">
                  <v-col cols="6">
                    <div class="label-title">{{$t('purchases.modal.dateOfSupplierDoc')}}</div>
                    <calendar
                        @updateDate="onDate"
                        :placeholder="$t('purchases.modal.enterTheDate')"
                        :date-value="form.supplier_document_date"
                        custom-class="default-calendar"
                        date-key="supplier_document_date"
                        icon-color="#9DCCFF"
                        :is-disabled="isCapitalizedDocument"
                        isCloseOnContentClick
                        is-icon
                    />
                  </v-col>
                  <v-col cols="6" :class="{'disabledBlock': isCapitalizedDocument}">
                    <div class="item-name">
                      <div class="label-title">{{$t('purchases.modal.numberOfSupplierDoc')}}</div>
                      <input
                          v-model="form.supplier_document_number"
                          type="text"
                          :placeholder="$t('purchases.modal.enterTheNumber')"
                      >
                    </div>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <div class="label-title">{{ $t('page.wareHouses.stockModal.comments') }}</div>
              </v-col>
              <v-col cols="6">
              </v-col>
              <v-col cols="6">
                <div class="item-name">
                  <v-textarea
                      name="comments"
                      height="90"
                      v-model="form.description"
                      dense
                      solo
                  ></v-textarea>
                </div>
              </v-col>
              <v-col cols="6" class="responsible">
                <div v-if="form.manager"> Менеджер: <span>{{ form.manager }}</span></div>
                <div>{{ $t('page.wareHouses.stockModal.responsible') }}: <span>{{ getFullNameUser }}</span></div>
                <div>{{ $t('page.wareHouses.stockModal.dateCreateDocument') }}: <span> {{ form.document_date | formatDate }}</span></div>
              </v-col>
            </v-row>
            <annotation :items="annotationItems"></annotation>
          </form>
        </simplebar>
      </template>
      <template v-slot:footer>
        <div class="dialog-actions popup-buttons">
          <custom-btn
              custom-class="cancel"
              :title="$t(`page.suppliers.modal.createGroups.${getCorrectText ? 'close': 'cancel'}`)"
              color="#5893D4"
              @updateEvent="closeModal"
              text
          ></custom-btn>
          <popover-menu
              @updateSaveDocument="onSaveDocument"
              :isCapitalizedDocument="isCapitalizedDocument"
              :isEdit="isEdit"
              :isDisabled="!amountCount"
          />
        </div>
      </template>
    </modal-container>
    <chose-wrapper
        v-if="isOpenChooseModal"
        :isOpenChooseModal="isOpenChooseModal"
        @updateChooseModal="onChoseClose"
        @updateGetListIds="getListIds"
        @updateAmountSum="onAmountCount"
        @updateAmountCount="onAmountCount"
        @updateSelectItems="onSelectItems"
        :titleText="titleText"
        :getHeaders="getHeaders"
        :getTabIdx="getTabIdx"
        :selectedList="!!getTabIdx ? getServicesList : getSelectedList"
        :getFullTime="date"
        :warehouseId="form.warehouse_id"
        isStock
    ></chose-wrapper>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex"
// components
import PurchasesModalFirstTable from "@/components/dashboard/products/purchases/modal/table/PurchasesModalFirstTable";
import PurchasesModalSecondTable from "@/components/dashboard/products/purchases/modal/table/PurchasesModalSecondTable";
import TooltipWithIcon from "@/components/dashboard/products/wareHouses/StockModal/components/TooltipWithIcon";
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer";
import PopoverMenu from "@/components/dashboard/products/wareHouses/StockModal/components/PopoverMenu/PopoverMenu";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import Calendar from "@/components/ui/Calendar/Calendar";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import DeliveryBlock from "@/components/ui/DeliveryBlock/DeliveryBlock";
import WarehouseTabs from "@/components/dashboard/products/wareHouses/WarehouseTabs/WarehouseTabs";
import WareHMenu from "@/components/dashboard/products/wareHouses/WareHMenu/WareHMenu";
import Annotation from "@/components/ui/Annotation/Annotation";
// components async
const ChoseWrapper = () => import("@/components/dashboard/products/wareHouses/ChoseWrapper/ChoseWrapper");
// services
import httpClient from "@/services/http-client/http-client";
// validate
import {required} from 'vuelidate/lib/validators';
// helper
import getCurrentDate from "@/helper/getCurrentDate";
import getDataUser from "@/helper/getDataUser";
import getUniqueId from "@/helper/getUniqueId";
// constants
import {headers} from "@/constants/purchases";
import {MODE_TYPES } from "@/constants/constants";

export default {
  name: "PurchasesModal",
  components: {
    'header-actions': HeaderActions,
    'warehouse-tabs': WarehouseTabs,
    'ware-h-menu': WareHMenu,
    ChoseWrapper,
    DeliveryBlock,
    TooltipWithIcon,
    CustomBtn,
    PopoverMenu,
    Calendar,
    PurchasesModalFirstTable,
    PurchasesModalSecondTable,
    ModalContainer,
    Annotation
  },
  props: {
    mode: String,
    purchasesModal: String,
    currencies: Array,
    suppliers: Array,
    type_warehouse: Array,
    terms_payment: Array,
    cities: Array,
    delivery_methods: Array,
    type_deliveries: Array,
    departments: Array,
    source_attractions: Array,
    form_payments: Array,
    counterparties_returns: Array,
    status_payment: Array,
    status_product: Array,
    counterparties_cancellations: Array,
    user: Object,
    documentTitle: String,
    organizations: Array,
    wareHouses: Array,
    settings: Object,
    documentEditData: Object,
    pricesTypes: Object,
    isHideAttach: Boolean,
    supplierId: Object,
    mark: {
      type: String,
      default: 'fetchPurchases'
    }
  },
  watch: {
    amountCount(val) {
      if (this.documentTitle) return false;

      if (val) {
        this.getStatusProducts =  this.status_product.map(item => {
          if (item['directory_id'] === 5) {
            return item.disabled = true;
          }

          return item.disabled = false;
        })
      } else {
        this.getStatusProducts =  this.status_product.map(item => {
          if (item['directory_id'] === 4 || item['directory_id'] === 5) {
            return item.disabled = true;
          }

          return item.disabled = false;
        })
        if (this.form.status_product_id === 4) this.form.status_product_id = this.getFirstItem(this.status_product)
      }

    }
  },
  computed: {
    ...mapGetters([
      'dataCreate',
      'dataEdit',
      'IDCreatedCategory',
      'requestError',
      'isGroupSuccess',
      'pending',
      'flatList',
      'selectedList',
      'selectedServicesList',
      'tabIdx'
    ]),
    getStatusProducts: {
      get() {
        return this.status_product;
      },
      set(value) {
        return value;
      }
    },
    getStatusPayment: {
      get() {
        return this.status_payment;
      },
      set(value) {
        return value;
      }
    },
    getCorrectText() {
      return this.documentTitle === MODE_TYPES.EDIT ||
          this.documentTitle === MODE_TYPES.MOVE ||
          this.documentTitle === MODE_TYPES.REMOVE
    },
    getOrganizationDefault() {
      return this.organizations.find(item => item['is_default'])
    },
    currentComponent() {
      return this.tabsComponents[this.tabIdx]
    },
    tabs() {
      // disabled item => add 'status: true'
      return [
        {title: 'Товары', id: 1},
        {title: 'Услуги', id: 2}
      ]
    },
    getTabIdx() {
      return this.tabIdx;
    },
    isAdd() {
      return !this.documentTitle;
    },
    isCopy() {
      return this.documentTitle === MODE_TYPES.COPY;
    },
    getSelectedList() {
      return this.selectedList
    },
    getServicesList() {
      return this.selectedServicesList
    },
    getPurchasesModalTitle() {
      if (this.isCopy) {
        return `Закупки (${this.$t('page.wareHouses.stockModal.copy')})`;
      }

      return 'Закупки';
    },
    getDataUser() {
      return getDataUser();
    },
    getFullNameUser() {
      const {first_name, last_name, middle_name} = this.getDataUser;
      return last_name + " " + first_name + " " + middle_name;
    },
    isReturn() {
      return this.form.status_product_id === 5
    },
    company() {
      return this.user.company
    },
    getHeaders() {
      return [...headers];

      //return this.getFormatHeaders(copyHeaders);
    },
    bindStatusItems: {
      get() {
        if (this.form.status_product_id === 2 || this.form.status_product_id === 4) {
          return [...this.status_payment].filter(status => status['directory_id'] === 2 || status['directory_id'] === 3)
        }

        return this.status_payment
      },
      set(value) {
        return value
      }
    },
    disabledSelect() {
      return this.form.status_product_id === 1 || this.form.status_product_id === 3 || this.form.status_product_id === 5
    },
    isCapitalizedDocument() {
      const { status } = this.documentEditData || {};
      return status === 1;
    },
    getDateDocument() {
      return { value: getCurrentDate(), format: "DD.MM.YYYY HH:mm" }
    },
    otherItems() {
      return [
        {id: 1, title: this.$t('page.wareHouses.otherItems.capitalized'), disabled: true},
        {id: 2, title: this.$t('page.wareHouses.otherItems.cancel'), disabled: true},
        {id: 3, title: this.$t('page.wareHouses.otherItems.delete'), disabled: true},
      ]
    },
    isEdit() {
      return this.documentTitle === MODE_TYPES.EDIT
    },
    getDefaultWarehouse() {
      return this.wareHouses.find(item => item.is_default)
    }
  },
  data() {
    return {
      titleText: 'Выбрать товары для закупки',
      currency: '',
      priceType: '',
      tabsComponents: ['PurchasesModalFirstTable', 'PurchasesModalSecondTable'],
      editDelivery: null,
      list: [],
      randomId: '',
      date: getCurrentDate(),
      dialog: false,
      amountSum: 0,
      firstSum: 0,
      secondSum: 0,
      localeData: '',
      isOpenChooseModal: false,
      isSearchInput: false,
      statusDocument: false,
      contracts: [],
      focusRow: null,
      amountCount: 0,
      firstCount: 0,
      secondCount: 0,
      throughTimeObj: ["Завтра", "Через неделю", "Послезавтра", "Через месяц"],
      annotationItems: [
        `(*) - ${this.$t('annotation.firstText')}`,
        `(**) - ${this.$t('annotation.secondText')}`,
        `(***) - ${this.$t('annotation.thirdText')}`
      ],
      form: {
        product: [],
        services: [],
        company: this.getFirstItem(this.organizations),
        date: getCurrentDate(),
        organization_convert_id: "",
        organization_id: 1,
        document_id: null,
        warehouse_id: 1,
        supplier_id: null,
        contract_id: null,
        price_id: null,
        currency_id: null,
        status_product_id: this.getFirstItem(this.status_product),
        status_payment_id: this.getFirstItem(this.status_payment),
        create_document_date: getCurrentDate(),
        receipt_date_scheduled: getCurrentDate(),
        payment_date_scheduled: getCurrentDate(),
        payment_date_actual: null,
        receipt_date_actual: '',
        terms_payment_id: null,
        form_payment_id: null,
        directory_cancell_id: null,
        directory_return_id: null,
        supplier_document_date: '',
        supplier_document_number: '',
        description: "",
        document_amount: null,
        responsible: null,
        archive: false,
        manager: "",
        delivery: {
          delivery_methods_id: 1,
          type_deliveries_id: 1,
          department_city_id: '',
          department_id: '',
          ttn_number: '',
          ttn_date: '',
          is_power_of_attorney: true,
          time_power_of_attorney: '',
          number_power_of_attorney: "ww55245",
          confidant_id: 1
        }
      }
    }
  },
  validations: {
    form: {
      contract_id: {required},
      warehouse_id: {required},
      supplier_id: {required},
    }
  },
  methods: {
    ...mapActions([
      'onAddSuppliersGroup',
      'onEditGroup',
      'actonDeleteItemList',
      'addPurchase',
      'onTabsIdx',
      'addDocument',
      'addCapitalizedDocument',
      'editDocument',
      'editCapitalizedDocument',
      'getEditFillProducts',
      'canceledCapitalizedDocument'
    ]),
    onContractChange(id) {
      const { currency_id, price_type_id } = this.contracts.find(item => item.id === id);

      this.getCurrency(currency_id);
      this.getPriceType(price_type_id);
    },
    getCurrency(currency_id) {
      const obj = this.currencies.find(currency => currency.id === currency_id);

      this.currency = obj?.title ?? '';
    },
    getPriceType(price_type_id) {
      const obj = this.pricesTypes.types.find(type => type.id === price_type_id);

      this.priceType = obj?.title ?? '';
    },
    getCorrectItemsData(data) {
      return data.map(item => {
        const {id, count = 0, isPack, price = 0, cells: { packing = 1 }} = item;
        const balance_base = (packing && !isPack) ? (count * packing) : count;

        return { id, balance_base, price, is_packing: !isPack }
      })
    },
    onTabValue(id) {
      this.isSearchInput = false;
      this.onTabsIdx(id);
    },
    onDeleteList() {
      this.randomId = getUniqueId();
      if (this.iDs.length) this.actonDeleteItemList(this.iDs);
    },
    onSelectItems() {
      this.isOpenChooseModal = false;
    },
    onChoseClose() {
      this.randomId = getUniqueId();
      this.isOpenChooseModal = false
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
    onChoice() {
      this.randomId = getUniqueId();
      this.isOpenChooseModal = true;
    },
    onDeliveryValues(values) {
      this.form.delivery = {...this.form.delivery, ...values}
    },
    changeFocusRow(name) {
      this.focusRow = name
    },
    checkFocusedRow(name) {

      if (this.focusRow === name) {
        this.focusRow = null
      }
    },
    async onChangeSupplier(supplier_id, contract_id) {
      const url = `directories/counterparties_contracts/list?supplier_id=${supplier_id}`;
      const { data } = await httpClient.get(url);

      if (data && !data.status) {
        this.contracts = data.data.list;
        const contract = contract_id ? this.contracts.find(item => item.id === contract_id) : this.contracts[0] || {};

        if (contract) {
          const { id, currency_id, price_type_id, manager } = contract;
          this.form.contract_id = id;
          this.form.currency_id = currency_id;
          this.form.price_id = price_type_id;
          this.form.manager = manager;

          this.getCurrency(currency_id);
          this.getPriceType(price_type_id);
        }
      } else {
        console.log('Houston we have a problem')
      }
    },
    onSearchInput() {
      this.isSearchInput = !this.isSearchInput;
      this.randomId = getUniqueId();
    },
    open() {
      this.dialog = true
    },
    onChangeDocumentDate(date) {
      this.form.date = date;
      this.form.payment_date_scheduled = date;
      this.form.receipt_date_scheduled = date;
    },
    onDate(params) {
      this.form[params.key] = params.date;
    },
    onTime(time) {
      this.form.time = time;
    },
    onDefaultTime(value) {
      if (this.documentEditData) {
        const { date } = this.documentEditData;
        const [initDate, time] = date.split(' ');
        const formatTime = time.slice(0, -3);

        this.form.documentTime = initDate + " " + formatTime;
      } else {
        this.form.documentTime = value;
      }
    },
    getFirstItem(list) {
      if (list && list.length)
        return list[0]['directory_id'] || list[0].id
    },
    getListIds(iDs) {
      this.iDs = iDs;
    },
    onClearSearchInput() {
      this.isSearchInput = false
    },
    onAmountSum(value, idx) {
      if (idx) {
      this.secondSum = +value;
    } else {
      this.firstSum = +value;
    }

    this.amountSum = this.secondSum + this.firstSum;
    },
    onAmountCount(value, idx) {
      if (idx) {
        this.secondCount = +value;
      } else {
        this.firstCount = +value;
      }

      this.amountCount = this.secondCount + this.firstCount;

      this.otherItems.forEach((item) => {
        if (item.id === 1 && !this.isCapitalizedDocument) item.disabled = !this.amountCount
        if (item.id === 2 && !this.isCopy) item.disabled = !this.isCapitalizedDocument
      })
    },
    closeModal() {
      this.$emit('close-modal')
    },
    onChangePaymentStatus() {
      console.log('onChangePaymentStatus')
    },
    onChangeProductStatus() {
      const switchedStatus = this.form.status_product_id
      if (switchedStatus === 1) {
        this.form.status_payment_id = 1
      }
      if (switchedStatus === 2 || switchedStatus === 4 && this.form.status_payment_id !== 3) {
        this.form.status_payment_id = 2
      }
      if (switchedStatus === 4) {
        this.form.receipt_date_actual = getCurrentDate()
      }
      if (switchedStatus === 3) {
        this.form.status_payment_id = 4
      }
      if (switchedStatus === 5) {
        this.form.status_payment_id = 5
      }
    },
    onChangeValue(idx) {
      if (idx === 0) this.onSaveDocument();
      if (idx === 1) this.onCanceledDocument();
    },
    async onCanceledDocument() {
      const doc_id = await this.documentEditData?.document_purchases?.document_id;
      const document_id = await this.documentEditData?.document_id;

      const values = {
        resource: 'purchases',
        updateTable: 'fetchPurchases',
        id: doc_id || document_id
      }

      await this.canceledCapitalizedDocument(values);

      this.$emit('updateStatusDocument');
      this.onAmountCount(this.amountCount);
    },
    onClickAddSupplier() {
      this.$emit('click-add-supplier')
    },
    onGroupId(item) {
      this.parentItem = item;
    },
    close() {
      this.dialog = false;
    },
    async onSaveDocument(point = 0) {
      await this.$v.form.$touch();
      if (this.$v.$invalid) return false;

      const products = this.getCorrectItemsData(this.selectedList);
      const services = this.getCorrectItemsData(this.selectedServicesList);

      const nomenclatures = [...products, ...services];
      const {
        create_document_date,
        receipt_date_scheduled,
        payment_date_scheduled,
        payment_date_actual,
        receipt_date_actual,
        terms_payment_id,
        form_payment_id,
        directory_cancell_id,
        supplier_document_date,
        supplier_document_number,
        directory_return_id,
        date,
        organization_convert_id,
        document_amount,
        manager,
        responsible,
        description,
        warehouse_id,
        organization_id,
        supplier_id,
        contract_id,
        price_id,
        currency_id,
        status_product_id,
        status_payment_id,
        delivery,
        documentTime
      } = this.form;

      const values = {
        create_document_date,
        receipt_date_scheduled,
        payment_date_scheduled,
        payment_date_actual,
        receipt_date_actual,
        terms_payment_id,
        form_payment_id,
        directory_cancell_id,
        supplier_document_date,
        supplier_document_number,
        directory_return_id,
        date,
        organization_convert_id,
        document_amount,
        manager,
        responsible,
        description,
        warehouse_id,
        supplier_id,
        contract_id,
        price_id,
        currency_id,
        status_product_id,
        status_payment_id,
        delivery,
        documentTime,
        organization_id: organization_id.id ? organization_id.id : organization_id,
        resource: 'purchases',
        updateTable: this.mark,
        nomenclatures
      }

      if (!this.documentTitle || this.isCopy) {
        point
            ? await this.addDocument(values)
            : await this.addCapitalizedDocument(values);
      }

      if (this.isEdit) {
        const { document_purchases, document_receipt_stocks, document_transfer_stocks, document_write_off_stocks, document_id: currentId  } = await this.documentEditData;
        if(currentId) {
          values.id = currentId;
        } else {
          const { document_id } = document_purchases || document_receipt_stocks || document_transfer_stocks || document_write_off_stocks;
          values.id = document_id;
        }

        point
            ? await this.editDocument(values)
            : await this.editCapitalizedDocument(values);
      }
    },
    async onEditData() {
      if (this.documentTitle === MODE_TYPES.EDIT || this.isCopy) {
        const docPurchases = this.documentEditData['document_purchases'];

        const {
          document_id,
          date,
          delivery,
          supplier_document_date,
          supplier_document_number,
          payment_date_actual,
          payment_date_scheduled,
          receipt_date_actual,
          organization_id,
          receipt_date_scheduled,
          directory_cancell_id,
          directory_return_id,
          create_document_date,
          description,
          warehouse_id,
          contract_id,
          supplier_id,
          terms_payment_id,
          form_payment_id,
          status_product_id,
          status_payment_id,
          organization_convert_id
        } = await docPurchases ?? this.documentEditData;

        if (this.isCapitalizedDocument) {
          if (status_product_id === 4 && status_payment_id === 3) {
            this.getStatusProducts = this.status_product.map(item => {
              if (item['directory_id'] === 3) {
                return item.disabled = true
              }

              return item.disabled = false;
            });

            this.bindStatusItems = this.status_payment.map(item => {
              if (item['directory_id'] === 2) {
                return item.disabled = true
              }

              return item.disabled = false;
            })
          } else {
            this.getStatusProducts = this.status_product.map(item => item.disabled = false)
            this.bindStatusItems = this.status_payment.map(item => item.disabled = false)
          }
        } else {
          this.getStatusProducts = this.status_product.map(item => item.disabled = false)
          this.bindStatusItems = this.status_payment.map(item => item.disabled = false)
        }

        this.form.documentTime = this.$options.filters.dateForDatePicker({ value: date, format: "DD.MM.YYYY HH:mm" });

        this.form = {
          supplier_document_date,
          supplier_document_number,
          payment_date_actual,
          payment_date_scheduled,
          directory_cancell_id,
          directory_return_id,
          receipt_date_actual,
          receipt_date_scheduled,
          document_date: create_document_date,
          date: this.isCopy ? getCurrentDate() : payment_date_scheduled,
          organization_id,
          delivery,
          description,
          warehouse_id,
          supplier_id,
          contract_id,
          terms_payment_id,
          form_payment_id,
          status_product_id,
          status_payment_id,
          organization_convert_id
        };

        this.editDelivery = delivery;

        await this.onChangeSupplier(supplier_id, contract_id)
        await this.getEditFillProducts({ id: document_id, res: 'purchases' });
        this.getEditFullAmount();

      }

      if (this.isCopy) {
        this.$emit('updateStatusDocument');
        this.onAmountCount(this.amountCount);
      }
    },
    getEditFullAmount() {
      const amount = this.selectedList[0]?.cells?.amount || 0;
      const count = this.selectedList[0]?.balance_base || 0;
      const serviceAmount = this.selectedServicesList[0]?.cells?.amount || 0;
      const serviceCount = this.selectedServicesList[0]?.balance_base || 0;

      this.amountSum = amount + serviceAmount;
      this.amountCount = count + serviceCount;
    }
  },
  async mounted() {
    if (this.documentEditData) {
      await this.onEditData();
    } else {
      this.form.documentTime = await this.$options.filters.dateForDatePicker(this.getDateDocument);
      this.form.form_payment_id = await this.settings['form_payments_id']
      this.form.terms_payment_id = await this.settings.terms_payment_id;
      this.editDelivery = this.form.delivery;
      this.form.warehouse_id = this.getDefaultWarehouse?.id ?? this.getDefaultWarehouse;

      this.getStatusProducts =  this.status_product.map(item => {
        if (item['directory_id'] === 4 || item['directory_id'] === 5) {
          return item.disabled = true;
        }

        return item.disabled = false;
      })
    }

    if (this.supplierId) {
      const { id } = this.supplierId;
      this.form.supplier_id = id;
      await this.onChangeSupplier(id)
    }
  },
  destroyed() {
    this.$store.dispatch('onClearDocumentList');
    this.$store.dispatch('onDefaultStore')
  }
}
</script>

<style scoped lang="scss">
@import "PurchasesModal";
</style>
<style lang="scss">
.defaultDropDown .v-list-item--disabled {
  opacity: .6;
}

.no-data.v-list-item.v-list-item--dense.theme--light{
  font-size: 14px !important;
  background: none !important;
  color: rgba(26, 34, 38, .4) !important;
}

.add-select-option {
  display: flex;
  align-items: center;
  font-size: 15px;
  line-height: 15px;
  color: #5893D4;
  cursor: pointer;

  svg {
    margin-right: 10px;
  }
}

.popup-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 15px 50px 15px;
}

.suppliersModal .v-treeview-node__content {
  transition: color .3s ease;
  cursor: pointer;

  &:hover {
    color: #FF9D2B;
  }
}

.disabledBlock {
  pointer-events: none;

  [type=text] {
    border-bottom: none;
  }

  .count {
    .timeBlockBtn {
      opacity: 0;
    }

    .numericWrap .timeBlockField input {
      border: none;
    }
  }

  .right, .comments {
    pointer-events: auto;
  }

  .theme--light.v-text-field > .v-input__control > .v-input__slot:before {
    border-color: transparent !important;
  }

  .unitsSelect {
    border: none !important;
  }

  .v-input__append-inner {
    opacity: 0;
  }
}
.v-input__icon--clear {
  .v-icon {
    font-size: 16px !important;
  }
}

.disabledElement {
  .theme--light.v-input input, .theme--light.v-input textarea {
    color: #7E7E7E !important;
  }

  .select-switcher.v-input--is-disabled {
    opacity: 1 !important;

    .v-input__slot {
      border-bottom: none;
      .v-input__append-inner {
        opacity: 0;
      }
    }
  }

}

.purchases-modal {
  .simplebar-content {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
  }

  .dialog-content-header {
    padding: 12px 30px;

    .left {
      align-items: center;

      .v-input__slot {
        margin-bottom: 0;
      }
    }
  }
}
</style>
