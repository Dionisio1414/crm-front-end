<template>
  <modal-container 
      v-if="isModalOpen" 
      @clickOutside="onCloseModal"
      :dialog="isModalOpen" 
      :modalWidth="1200"
      :customDialogClass="['modal-container-contractors']"
  >
    <template v-slot:header>
      <div class="dialog-header">
        <div class="header-text">
          <span class="header-text-title">
            <template v-if="mode === 'create'">
              {{ $t('directories.contractorsContractText') }}
            </template>
            <template v-if="mode !== 'create'">
              <span>{{ $t('purchases.contract') }}</span>
            </template>
            <SvgSprite 
                v-if="localFormData.is_automatically"
                class="header-icon"
                :width="21"
                :height="20"
                iconId="automatically"
            />
          </span>
          <span v-if="mode !== 'create'" class="subtext">{{ header_id }}</span>
        </div>
        <div class="dialog-header-actions">
          <v-btn icon color="#5893D4" @click="addTab('сontracts-сontractors', 'directories.contractorsContractText')" :disabled="checkTabs" v-if="mode === 'create'">
            <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                    fill="#BBDBFE"/>
            </svg>
          </v-btn>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="cancel">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                  fill="#BBDBFE"/>
            </svg>
          </v-btn>
        </div>
      </div>
    </template>
    <template v-slot:main>
      <div class="dialog-body dialog-body--subheader">
        <div class="dialog-subheader">
          <div class="date">
            <div class="calendar">
              <calendar
                  @updateDate="onChangeDocumentDate"
                  :date-value="localFormData.updated_at"
                  :throughTimeObj="throughTimeObj"
                  custom-class="head-calendar"
                  format="DD.MM.YYYY HH:mm"
                  is-bottom-btn
                  is-choose-time
                  is-through-time
                  isTime
              />
            </div>
          </div>
          <div class="select-companies">
              <v-select class="select-switcher"
                  v-model="localFormData.organization_id"
                  :items="listOrganizations"
                  item-text="title"
                  item-value="id"
                  solo
                  dense
                  hide-details
                  :menu-props="{ contentClass: 'select-companies-list'}"
                  @change="onChangeMainSelect($event, 'organization_id')"
                  return-object
              />
          </div>
        </div>
        <div class="dialog-body-content">
          <form class="form">
            <div class="form-content">
              <v-row align="center">
                <v-col cols="6">
                  <div class="wrapper-fields" :class="{'wrapper-fields--transparent': localFormData.is_automatically}">
                    <div class="wrapper-fields-item">
                      <div class="label-title label-title--required">{{ $t('directories.typeOfDocument') }}</div>
                      <div class="select-wrap">
                          <v-select class="select-switcher"
                              v-model="localFormData.type_contract_id"
                              :items="typeContractList"
                              item-text="title"
                              item-value="directory_id"
                              solo
                              dense
                              hide-details
                              :menu-props="{ contentClass: 'base-select'}"
                              :disabled="localFormData.is_automatically"
                              @change="onChangeMainSelect($event, 'type_contract_id')"
                              return-object
                          />
                      </div>
                    </div>
                    <div class="wrapper-fields-item">
                      <div class="item-name">
                        <div class="label-title label-title--required">
                          <template v-if="localFormData.type_contract_id === 1">
                            {{ $t('directories.buyer') }}
                          </template>
                          <template v-else-if="localFormData.type_contract_id === 2">
                            {{ $t('directories.supplier') }}
                          </template>
                          <template v-else>
                            {{ $t('directories.other') }}
                          </template>
                        </div>
                        <div class="select-wrap">
                            <v-select class="select-switcher"
                                v-model="localFormData.supplier_id"
                                :items="suppliersList"
                                item-text="title"
                                item-value="id"
                                solo
                                dense
                                hide-details
                                :menu-props="{ contentClass: 'base-select'}"
                                :disabled="localFormData.is_automatically"
                                @change="onChangeMainSelect($event, 'supplier_id')"
                                return-object
                            />
                        </div>
                      </div>
                    </div>
                  </div>
                </v-col>
                <v-col cols="1">
                    <div class="label-title label-title--required">{{ $t('directories.currency') }}</div>
                    <div class="select-wrap">
                      <v-select class="select-switcher"
                          v-model="localFormData.currency_id"
                          :items="currenciesLists"
                          item-text="title"
                          item-value="id"
                          solo
                          dense
                          hide-details
                          :menu-props="{ contentClass: 'base-select'}"
                          :disabled="localFormData.is_automatically"
                          @change="onChangeMainSelect($event, 'currency_id')"
                          return-object
                      />
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="label-title">{{ $t('directories.denomination') }}</div>
                  <div class="form-field">
                    <input 
                      :class="{'field-error': mode === 'copy'}"
                      class="field"
                      type="text" 
                      placeholder="Введите наименование" 
                      :value="$t(currentDenomination)"
                      @keypress="changeTitle"
                    >
                    <div class="form-field-helper" v-if="mode === 'copy'">
                      {{ $t('validation.changeName') }}
                    </div>
                  </div>
                </v-col>
                <v-col cols="3">
                  <div class="label-title">{{ $t('directories.validity') }}</div>
                  <div class="form-field">
                    <work-time-calendar
                      :class="{'field-error': mode === 'copy'}"
                      v-if="getPeriodDate"
                      :tabIndex="currentTabIndex"
                      :typeOfAbsenceObj="typeOfAbsenceObj"
                      :initValue="getPeriodDate"
                      @updateDate="onDate"
                      isDatePickerTop
                      isThroughTime
                      :key="componentKey"
                    >
                    </work-time-calendar>
                  </div>
                </v-col>
                <v-col cols="3">
                  <div class="label-title">{{ $t('directories.status') }}</div>
                  <div class="select-wrap">
                      <v-select class="select-switcher"
                          :class="{'field-error': mode === 'copy'}"
                          v-model="localFormData.status_contract_id"
                          :items="statusLists"
                          item-text="title"
                          item-value="directory_id"
                          solo
                          dense
                          hide-details
                          :menu-props="{ contentClass: 'base-select'}"
                          @change="onChangeMainSelect($event, 'status_contract_id')"
                          return-object
                      />
                  </div>
                </v-col>
              </v-row>
              <v-row class="form-title-row">
                <v-col cols="12">
                  <div class="form-title">{{ $t('directories.existing_contract_information') }}</div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="wrapper-fields">
                    <div class="wrapper-fields-item">
                      <div class="label-title">{{ $t('directories.number_registration') }}</div>
                      <div class="form-field">
                        <input 
                          :class="{'field-error': mode === 'copy'}"
                          class="field"
                          type="text" 
                          placeholder="Введите номер регистрации" 
                          v-model="localFormData.registration_number"
                        >
                      </div>
                    </div>
                    <div class="wrapper-fields-item">
                      <div class="label-title">{{ $t('directories.agreement_date') }}</div>
                      <div class="form-field">
                        <masked-input 
                          :class="{'field': true, 'field-error': !isValidDates || mode === 'copy'}"
                          mask="11.11.1111" 
                          placeholder="ДД/ММ/ГГГГ" 
                          v-model="localFormData.contract_date"
                        />
                      </div>
                    </div>
                    <div class="wrapper-fields-item">
                      <div class="label-title">{{ $t('directories.payment_term') }}</div>
                      <div class="form-field">
                        <input 
                          :class="{'field-error': mode === 'copy'}"
                          class="field" 
                          type="text" 
                          placeholder="Введите срок оплаты" 
                          v-model.number="localFormData.due_date"
                        >
                      </div>
                    </div>
                    <div class="wrapper-fields-item">
                      <div class="checkbox">
                        <label class="checkbox-label">
                          <input type="checkbox" v-model="localFormData.is_contract_signed">
                          <div class="checkbox-text">
                            <div class="text">{{ $t('directories.contract_is_signed') }}</div>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="wrapper-fields">
                    <div class="wrapper-fields-item">
                        <div class="label-title">{{ $t('directories.typesOfPrices') }}</div>
                        <div class="select-wrap">
                          <v-select class="select-switcher"
                              :class="{'field-error': mode === 'copy'}"
                              v-model="localFormData.price_type_id"
                              :items="pricesLists"
                              item-text="title"
                              item-value="id"
                              solo
                              dense
                              hide-details
                              :menu-props="{ contentClass: 'base-select'}"
                              @change="onChangeMainSelect($event, 'price_type_id')"
                              return-object
                          />
                      </div>
                    </div>
                    <div class="wrapper-fields-item">
                      <div class="label-title">{{ $t('directories.type_of_discount') }}</div>
                      <div class="form-field">
                        <input 
                          :class="{'field-error': mode === 'copy'}"
                          class="field field-percent" 
                          type="text" 
                          placeholder="Введите вид скидки / наценки" 
                          v-model="localFormData.percent"
                        >
                        <div class="form-field-percent" :class="{'active': localFormData.percent !== null && localFormData.percent.length > 0}">
                          %
                        </div>
                      </div>
                    </div>
                  </div>
                </v-col>
              </v-row>
              <v-row class="form-title-row">
                <v-col cols="12">
                  <div class="form-title">{{ $t('directories.planning_payments_and_robots') }}</div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="wrapper-fields">
                    <div class="wrapper-fields-item">
                        <div class="label-title">{{ $t('directories.terms_of_payment') }}</div>
                        <div class="select-wrap">
                          <v-select class="select-switcher"
                              v-model="termsPayment"
                              :items="termsPaymentsList"
                              item-text="name"
                              item-value="id"
                              solo
                              dense
                              hide-details
                              :menu-props="{ contentClass: 'base-select'}"
                              return-object
                          />
                      </div>
                    </div>
                    <div class="wrapper-fields-item">
                      <div class="label-title">{{ $t('programSettings.form_payments') }}</div>
                      <div class="select-wrap">
                          <v-select class="select-switcher"
                              v-model="formPayment"
                              :items="formPaymentsList"
                              item-text="name"
                              item-value="id"
                              solo
                              dense
                              hide-details
                              :menu-props="{ contentClass: 'base-select'}"
                              return-object
                          />
                      </div>
                    </div>
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="label-title">{{ $t('page.wareHouses.stockModal.comments') }}</div>
                  <div class="form-field">
                    <textarea 
                      class="textarea"
                      rows="4" 
                      v-model="localFormData.description"
                    >
                      </textarea>
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                </v-col>
              </v-row>
            </div>
          </form>
        </div>
      </div>
    </template>
    <template v-slot:footer>
      <div class="dialog-footer">
        <div class="form-actions">
          <button 
            v-if="mode !== 'view'"
            type="button" 
            class="base-button base-button--transparent" 
            @click="cancel"
          >
            Назад
          </button>
          <button 
            v-if="mode === 'view'"
            type="button" 
            class="base-button base-button--transparent" 
            @click="cancel"
          >
            {{ $t('page.close') }}
          </button>
          <button 
            v-if="mode !== 'view'"
            type="button" 
            class="base-button base-button--blue" 
            :disabled="$v.$invalid || !isValidDates || saveFlag"
            @click="onSave"
          >
            {{ $t('page.saveButton') }}
          </button>
        </div>
      </div>
    </template>
  </modal-container>
</template>

<script>
import moment from 'moment'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import { mapActions, mapGetters } from "vuex"
import MaskedInput from 'vue-masked-input'
import getCurrentDate from '@/helper/getCurrentDate'
import Calendar from "@/components/ui/Calendar/Calendar"
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import mixin from '@/mixins/mixinTabs'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import WorkTimeCalendar from "@/components/ui/WorkTimeCalendar/WorkTimeCalendar";

export default {
  name: "ModalContractsContractors",
  props: {
    itemData: Object,
    isModalOpen: Boolean,
    modeType: String
  },
  mixins: [validationMixin, mixin],
  validations: {
    localFormData: {
      type_contract_id: { required },
      counterparty_id: { required },
      currency_id: { required }
    }
  },
  components: {
    MaskedInput,
    Calendar,
    SvgSprite,
    ModalContainer,
    WorkTimeCalendar
  },
  data() {
    return {
      mode: 'create',
      dialog: false,
      isOpenCalendarPeriod: false,
      dates: [],
      throughTimeObj: ["Завтра", "Через неделю", "Послезавтра", "Через месяц"],
      currentId: null,
      header_id: null,
      isValidDates: true,
      saveFlag: false,
      formPayment: 1,
      termsPayment: 1,
      currentTabIndex: 0,
      componentKey: 0,
      lists: {
        types_contract: null,
        currencies: null,
        status_contracts: null,
        prices_types: null,
      },
      localFormData: {
        is_automatically: false,
        archive: false,
        title: null,
        registration_number: null,
        updated_at: getCurrentDate(),
        contract_date: null,
        from_period_date: null,
        to_period_date: null,
        counterparty_id: 1,
        type_contract_id: 1,
        organization_id: 1,
        currency_id: 1,
        price_type_id: 2,
        due_date: null,
        percent: null,
        is_contract_signed: false, 
        status_contract_id: 2 ,
        description: null,
        supplier_id: null,
        is_editable: true,
      },
      termsPaymentsList: [
        { id: 1, name: "Предоплата" },
        { id: 2, name: "Частичная оплата" },
        { id: 3, name: "Оплата" },
      ],
      formPaymentsList: [
        { id: 1, name: "Наличными" },
        { id: 2, name: "На карту" },
        { id: 3, name: "Наложенный платеж" },
        { id: 4, name: "Расчетный счет" },
      ],
      typeOfAbsenceObj: [
        {title: 'Все типы', color: '#5893D4', id: 1},
        {title: 'Отпуск ежегодный', color: '#69B9E7', id: 2},
        {title: 'Командировка', color: '#FFCA2A', id: 3},
        {title: 'Больничный', color: '#FF9A40', id: 4},
        {title: 'Отпуск декретный', color: '#CA79FC', id: 5},
        {title: 'Отгул за свой счет', color: '#BDBDBD', id: 6},
        {title: 'прогул', color: '#FF6D6D', id: 7},
        {title: 'Другое', color: '#7DE2A7', id: 8}
      ]
    }
  },
  computed: {
    ...mapGetters([
      'getLists',
      'list',
      'tabsLength'
    ]),
    suppliersList() {
      if(this.localFormData.type_contract_id === 2) {
        return this.getLists('lists')['suppliers'].full_names
      }
      return []
    },
    typeContractList() {
      return this.getLists('core_lists')['types_contract']
    },
    currenciesLists() {
      return this.getLists('lists')['currencies']
    },
    statusLists() {
      return this.getLists('core_lists')['status_contracts']
    },
    pricesLists() {
      return this.getLists('lists')['prices_types']['types']
    },
    checkNumber() {
      return n => /[0-9.]/.test(n)
    },
    tableCells() {
      return this.localFormData
    },
    listOrganizations() {
      return this.list('organizations')
    },
    getPeriodDate() {
      if(this.localFormData.from_period_date || this.localFormData.to_period_date) return [ this.localFormData.from_period_date, this.localFormData.to_period_date ] 
      return []
    },
    dateRange() {
      let firstDate = this.formatDate(this.dates[0]) !== null ? this.formatDate(this.dates[0]) : '',
          secondDate = this.formatDate(this.dates[1]) !== null ? this.formatDate(this.dates[1]) : ''
      if(!firstDate || !secondDate) return null
      return `${firstDate} - ${secondDate}`
    },
    contractDate() {
      return this.inverseDate(this.localFormData.contract_date)
    },
    currentData() {
      return moment().format('DD.MM.YYYY HH:mm')
    },
    currentDenomination: {
      get() {
        if(this.localFormData.type_contract_id === 1) return 'directories.sales_contract'
        else if(this.localFormData.type_contract_id === 2) return 'directories.purchase_contract'
        else return null
      },
      set(value) {
        return this.localFormData.title = value
      }
    },
  },
  watch: {
    'contractDate': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidDates = false
        console.log('1')
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        console.log('2')
        this.isValidDates = true
      } else {
        console.log('3')
        this.isValidDates = true
      }
    },
    'localFormData.type_contract_id': async function(newVal) {
      if(newVal === 1) {
        const idx = this.pricesLists.find(item => item.id).id
        this.localFormData.price_type_id = idx
        this.localFormData.supplier_id = null
        this.localFormData.title = this.$t('directories.sales_contract')
      } else if(newVal === 2) {
        this.localFormData.price_type_id = 1
        await this.fetchList({ type: '', resources: 'suppliers', directory: '', query: '' })
        let { id:supplierId } = this.suppliersList.slice().pop()
        this.localFormData.supplier_id = supplierId
        this.localFormData.title = this.$t('directories.purchase_contract')
      } else {
        this.localFormData.price_type_id = newVal
        this.localFormData.supplier_id = null
        this.localFormData.title = null
      }
    }
  },
  methods: {
    ...mapActions([
      'fetchCompanies',
      'fetchLists',
      'fetchList',
      'updateTabs',
      'deleteCurrentTab'
    ]),
    onDate(date) {
      console.log(date)
      const [ from_period_date, to_period_date ] = date
      // if(from_period_date && to_period_date) {
      this.localFormData.from_period_date = this.inverseDate(from_period_date)
      this.localFormData.to_period_date = this.inverseDate(to_period_date)
      // }
    },
    onCloseModal() {
      if(!this.saveFlag) {
        const { params } = this.$route.params
        if(params) {
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    onSave() {
      const { params } = this.$route.params
      const transformContractDate = this.localFormData.contract_date !== null ? this.inverseDate(this.localFormData.contract_date) : null
      const data = {
        ...this.localFormData,
        contract_date: transformContractDate
      }
      console.log('data data', data, this.localFormData.updated_at)
      if(this.mode == 'copy') this.mode = 'create'

      if(params) {
        this.$emit('save', {
          action: this.mode,
          resources: 'counterparties_contracts',
          data: {...data},
          alertItem: 'directories.contractorsContractText'
        })
        this.$router.go(-1)
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.$emit('close')
      } else {
        if(this.mode === 'change') {
          this.$emit('save', {
            action: this.mode,
            resources: 'counterparties_contracts',
            data: { 
              ...data, 
              id: this.currentId,
              // from_period_date: this.inverseDate(data.from_period_date),
              // to_period_date: this.inverseDate(data.to_period_date)
            },
            alertItem: 'directories.contractorsContractText'
          })
        } else {
          this.$emit('save', {
            action: this.mode,
            resources: 'counterparties_contracts',
            data: { ...data },
            alertItem: 'directories.contractorsContractText'
          })
        }
        this.saveFlag = true
      }
    },
    onChangeDocumentDate(date) {
      this.localFormData.updated_at = date
    },
    changeTitle({ target }) {
      this.localFormData.title = target.value
    },
    onChangeDate() {
      this.localFormData.from_period_date = this.dates[0]
      this.localFormData.to_period_date = this.dates[1]
      console.log(this.dates[0], this.dates[1])
    },
    changeDefaultState() {
      this.localFormData.is_default = !this.localFormData.is_default
      if (this.mode !== 'create') {
        this.$emit('changeDefault', {id: this.localFormData.id, resources: 'counterparties_cancellations'})
      }
    },
    cancel() {
      if(!this.saveFlag) {
        const { params } = this.$route.params
        if(params) {
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    onChangeMainSelect(obj, property) {
      console.log(obj, property)
      const idx = obj.id ? obj.id : obj.directory_id
      this.localFormData[property] = idx
    },
    formatDate(date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}.${month}.${year}`
    },
    inverseDate(date) {
      if(!date) return null
      const [day, month, year] = date.split('.')
      return `${year}-${month}-${day}`
    }
  },
  created() {
    this.defaultData = this.localFormData
  },
  mounted() {
    this.mode = this.modeType
    if(this.mode === 'create') {
        this.localFormData = this.defaultData
        this.localFormData.title = this.$t(this.currentDenomination)
    } else if(this.mode === 'change') {
        const { 
            id, 
            contract_date, 
            currency_id, 
            description,
            due_date,
            from_period_date,
            is_automatically,
            is_contract_signed,
            organization_id,
            percent,
            price_type_id,
            registration_number,
            status_contract_id,
            title,
            to_period_date,
            type_contract_id,
            updated_at,
            archive,
            supplier_id,
            cells: {
              id:header_id
            }
        } = this.itemData
        this.localFormData = {
          is_automatically,
          archive,
          title,
          registration_number,
          updated_at: updated_at !== null ? updated_at.slice(0, 16).split('T').join(" ") : null,
          contract_date,
          from_period_date: from_period_date !== null ? from_period_date.slice(0, 10) : null,
          to_period_date: to_period_date !== null ? to_period_date.slice(0, 10) : null,
          type_contract_id,
          organization_id,
          currency_id,
          price_type_id,
          due_date,
          percent,
          is_contract_signed, 
          status_contract_id,
          description,
          supplier_id,
          counterparty_id: 1
        }
        this.currentId = id
        this.header_id = header_id
        this.componentKey += 1
      } else {
        const { 
            id, 
            contract_date, 
            currency_id, 
            description,
            due_date,
            from_period_date,
            is_automatically,
            is_contract_signed,
            organization_id,
            percent,
            price_type_id,
            registration_number,
            status_contract_id,
            title,
            to_period_date,
            type_contract_id,
            updated_at,
            archive,
            supplier_id,
            cells: {
              id:header_id
            }
        } = this.itemData
          this.localFormData = {
            is_automatically,
            archive,
            title,
            registration_number,
            updated_at: updated_at !== null ? updated_at.slice(0, 16).split('T').join(" ") : null,
            contract_date,
            from_period_date: from_period_date !== null ? from_period_date.slice(0, 10) : null,
            to_period_date: to_period_date !== null ? to_period_date.slice(0, 10) : null,
            type_contract_id,
            organization_id,
            currency_id,
            price_type_id,
            due_date,
            percent,
            is_contract_signed, 
            status_contract_id,
            description,
            supplier_id,
            counterparty_id: 1
          }
          this.localFormData.title = `${this.localFormData.title} (Копия)`
          this.currentId = id
          this.header_id = header_id
          this.componentKey += 1
      }
  }
}
</script>

<style lang="sass">

.select-switcher
  &.field-error
    .v-input__slot
      border-bottom: 1px solid #FF9D2B!important

.modal-container-contractors
  .form
    .validation-rules
      margin-top: 0
    &-field
      .rangeCalendarWrap
        &.field-error
          .rangeInput
            border-bottom: 1px solid #FF9D2B
        .rangeTitle
          display: none
        .rangeInput
          justify-content: space-between
          border-bottom: 1px solid #BBDBFE
          padding-left: 0
          width: 100%
          min-height: 26px
          &_text
            font-size: 14px
            font-weight: 400
            line-height: 1
            color: #7e7e7e
    &-content
      > .row
          &:not(:last-child, .form-title-row)
            margin-bottom: 30px
  .dialog
    &-body
      padding-right: 0
      padding-bottom: 0
      &-content
        overflow-x: hidden
        padding-top: 50px
        padding-right: 30px
        padding-bottom: 10px
        max-height: 500px
        &::-webkit-scrollbar
            width: 2px
        &::-webkit-scrollbar-track
            background: transparent
        &::-webkit-scrollbar-thumb
            background: #9DCCFF
    &-footer
      position: relative
      padding-top: 0
      padding-bottom: 50px
      margin-top: -10px
      z-index: 1
</style>