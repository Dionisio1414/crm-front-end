<template>
  <form action="#" class="settings-form" >
    <div class="settings-form-layout" v-if="!isLoading">
      <div class="settings-form-col">
        <div class="input-wrapper">
          <label>
            {{ $t('programSettings.interfaceLang') }}
          </label>
          <v-select 
            v-model="localeData.language_interface_id"
            :items="languages"
            item-text="name"
            item-value="id"
            @change="setLocale"
          >
          </v-select>
        </div>
        <div class="input-wrapper">
          <label class="left-icon">
            {{ $t('programSettings.terms_payments') }}
            <v-tooltip
                right
                max-width="400px"
                text-color="#7E7E7E"
                content-class="gray-tooltip">
              <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
              </template>
              <span>
                {{ $t('programSettings.form_payments_tooltip') }}
              </span>
            </v-tooltip>
          </label>
          <v-select 
            :items="terms_payment"
            v-model="localeData.terms_payment_id"
            item-text="title"
            item-value="directory_id"
          >
          </v-select>
        </div>
        <div class="input-wrapper">
          <label class="left-icon">
            {{ $t('programSettings.form_payments') }}
            <v-tooltip
                right
                max-width="400px"
                text-color="#7E7E7E"
                content-class="gray-tooltip">
              <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
              </template>
              <span>
                {{ $t('programSettings.form_payments_tooltip') }}
              </span>
            </v-tooltip>
          </label>
          <v-select 
            :items="form_payments"
            v-model="localeData.form_payments_id"
            item-text="title"
            item-value="directory_id"
          >
          </v-select>
        </div>
        <div class="input-wrapper">
          <label class="left-icon">
            {{ $t('programSettings.currency') }}
            <v-tooltip
                right
                max-width="400px"
                text-color="#7E7E7E"
                content-class="gray-tooltip">
              <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
              </template>
              <span>
                {{ $t('programSettings.currencyTooltip') }}
              </span>
            </v-tooltip>
          </label>
          <v-select 
            :items="currencies"
            v-model="localeData.currency_id"
            item-text="title"
            item-value="id"
            @change="setCurrency"
          >
          </v-select>
        </div>
        <div class="input-wrapper date-picker-wrapper">
          <label class="left-icon">
            <input
                type="checkbox"
                id="ban-date"
                @click="changeBanDateStatus"
                v-model="isBanDate"
            />
            {{ $t('programSettings.banDate') }}
            <v-tooltip
                right
                max-width="400px"
                text-color="#7E7E7E"
                content-class="gray-tooltip">
              <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon">
                <span v-bind="attrs" v-on="on">
                <svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite>
                </span>
              </span>
              </template>
              <span>
                 {{ $t('programSettings.banDateTooltip') }}
              </span>
            </v-tooltip>
          </label>
          <div class="date-picker-trigger" :class="{ 'disable': !isBanDate}">
            <input readonly type="text" v-model="formatDate" @input="onChangeLocalData" @click="toggleDatePicker">
            <span @click="toggleDatePicker">
              <svg-sprite width="16" height="15" icon-id="date"></svg-sprite>
            </span>
          </div>
          <v-date-picker
              v-if="isOpenCalendar"
              v-model="localeData.data_prohibition"
              locale="ru-Ru"
              @input="onChangeDate"
          ></v-date-picker>
        </div>
        <div class="input-wrapper">
          <label>
            {{ $t('programSettings.clearArchive') }}
          </label>
          <input
              type="number"
              v-model="localeData.archive_cleare"
          /> <span class="days-label">{{ $t('programSettings.days') }}</span>
          <div class="slider-wrapper">
            <span class="slider-label slider-label__min"
                  v-if="localeData.archive_cleare > sliderParams.min +5">{{ sliderParams.min }}</span>
            <v-slider
                :color="sliderParams.color"
                :trackColor="'#E3F0FF'"
                v-model="localeData.archive_cleare"
                :thumb-color="sliderParams.color"
                thumb-label="always"
                @change="onChangeLocalData"
                :max="sliderParams.max"
                :min="sliderParams.min"
            ></v-slider>
            <span class="slider-label slider-label__max"
                  v-if="localeData.archive_cleare < sliderParams.max -5">{{ sliderParams.max }}</span>
          </div>
        </div>
      </div>
      <div class="settings-form-col">
        <div class="input-wrapper">
          <label>
            {{ $t('programSettings.companyName') }}
          </label>
          <input class="input" v-model="localeData.name" maxlength="50"
                 @blur="onBlurInputName"    @change="onChangeLocalData"/>
        </div>
        <div class="input-wrapper">
          <label>
            {{ $t('programSettings.mainDomain') }}
          </label>
          <input class="input" maxlength="50" @blur="onBlurInputDomain" @keyup="formatDomain()" v-model="localeData.domain"  @input="onChangeLocalData"/>
          <span class="subtext">{{ localeData.main_domain }}</span>
        </div>
      </div>

      <div class="settings-form-col">
        <div class="checkbox-wrapper">
          <input
              type="checkbox"
              id="use-kits"
              v-model="localeData.is_kits"
              @change="onChangeLocalData"
          />
          <label for="use-kits">{{ $t('programSettings.useKits') }}</label>
          <v-tooltip
              right
              max-width="400px"
              text-color="#7E7E7E"
              content-class="gray-tooltip">

            <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
            </template>
            <span>
                {{ $t('programSettings.useKitsTooltip') }}
              </span>
          </v-tooltip>
        </div>
        <div class="checkbox-wrapper">
          <input
              type="checkbox"
              id="printing-labels"
              v-model="localeData.is_labels_price_tags"
              @change="onChangeLocalData"
          />
          <label for="printing-labels">{{ $t('programSettings.printingLabels') }}</label>
          <v-tooltip
              right
              max-width="400px"
              text-color="#7E7E7E"
              content-class="gray-tooltip">

            <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
            </template>
            <span>
                {{ $t('programSettings.printingLabelsTooltip') }}
              </span>
          </v-tooltip>
        </div>
        <div class="checkbox-wrapper">
          <input
              type="checkbox"
              id="control-the-balances"
              v-model="localeData.is_residue_control"
              @change="onChangeLocalData"
          />
          <label for="control-the-balances">{{ $t('programSettings.controlTheBalances') }}</label>
          <v-tooltip
              right
              max-width="400px"
              text-color="#7E7E7E"
              content-class="gray-tooltip">

            <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
            </template>
            <span>
                {{ $t('programSettings.controlTheBalancesTooltip') }}
              </span>
          </v-tooltip>
        </div>
        <div class="checkbox-wrapper">
          <input
              type="checkbox"
              id="change-history"
              v-model="localeData.is_change_history"
              @change="onChangeLocalData"
          />
          <label for="change-history">{{ $t('programSettings.changeHistory') }}</label>
          <v-tooltip
              right
              max-width="400px"
              text-color="#7E7E7E"
              content-class="gray-tooltip">

            <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon" v-bind="attrs" v-on="on">
                 <span><svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite></span>
              </span>
            </template>
            <span>
                {{ $t('programSettings.changeHistoryTooltip') }}
              </span>
          </v-tooltip>
        </div>
      </div>
    </div>
    <v-btn
        @click="updateSettings"
        class="base-btn shadow-btn">{{ $t('page.saveButton') }}
    </v-btn>
    <alert/>
  </form>
</template>

<script>
// vuex
import {mapGetters, mapActions} from 'vuex';
// eventBus
import { eventBus } from "@/main";
// components
import Alert from "@/components/ui/Alert";

export default {
  name: "ProgramSettings",
  components: {
    Alert
  },
  data: () => ({
    pattern:  /^[a-zA-Z0-9]+$!./,
    languages: [
      {
        id: 1,
        code: 'ru',
        name: 'Русский',
      },
      {
        id: 2,
        code: 'ua',
        name: 'Українська',
      },
    ],
    localeData: {
      name: '',
      domain: '',
      main_domain: '',
      language_interface_id: 1,
      currency_id: 1,
      archive_cleare: 0,
      form_payments_id: 1,
      terms_payment_id: 1,
      data_prohibition: null,
      is_kits: true,
      is_labels_price_tags: true,
      is_residue_control: true,
      is_change_history: true,
    },
    isOpenCalendar: false,
    activeLang: '',
    activeCurrency: '',
    isBanDate: false,
    isLoading: true,
    sliderParams: {
      color: '#4ECA80',
      min: 1,
      max: 31
    }

  }),
  async created() {
    await Promise.all([
      this.fetchSettings(),
      this.fetchLists({type: '', resources: 'currencies'}),
      this.fetchLists({type: 'core', resources: 'form_payments'}),
      this.fetchLists({type: 'core', resources: 'terms_payment'}),
    ]);
    this.isLoading = false
    console.log('settings', this.settings)
    localStorage.setItem('company_name', this.settings.domain.split('.')[0])
    this.localeData = { 
      ...this.settings, 
      form_payments_id: this.settings.form_payments_id !== null ? +this.settings.form_payments_id : 1, 
      terms_payment_id: this.settings.terms_payment_id !== null ? +this.settings.terms_payment_id : 1, 
      language_interface_id: +this.settings.language_interface_id,
      currency_id: +this.settings.currency_id
    }
    this.getDomain()
    if (this.settings.archive_cleare) { 
      this.isBanDate = true
    }

  },
  computed: {
    ...mapGetters({
      settings: 'getSettings',
      lists: 'getLists',
    }),
    currentCompanyName() {
      return localStorage.getItem('company_name') || ''
    },
    form_payments() {
      return this.lists('core_lists')['form_payments']
    },
    terms_payment() {
      return this.lists('core_lists')['terms_payment']
    },
    currencies() {
      return this.lists('lists')['currencies']
    },
    formatDate() {
      let formatDate =  ''
      if (typeof this.localeData.data_prohibition === 'string') {
        const date = this.localeData.data_prohibition.split(' ')[0]
        formatDate = date.split('-').join('.')
      }
      return formatDate
    },
    currentLang() {
      return this.languages.find(item => item.id === this.localeData.language_interface_id) || ''
    },
    // currentCurrency() {
    //   return this.currencies.find(item => item.id) || ''
    // },
    currentCurrency: {
      get() {
        return this.currencies.find(item => item.id) || ''
      },
      set(value) {
        return value
      }
    }
  },
  mounted () {
    this.getActiveLang()
    this.getActiveCurrency()
    eventBus.$on('setting-reset-form',  () => {
      this.onResetForm()
    });
  },
  methods: {
    ...mapActions({
      fetchSettings: 'fetchSettings',
      fetchLists: 'fetchLists',
      changeSettings: 'changeSettings',
    }),
    getDomain () {
      const index = this.localeData.domain.indexOf('.')
      if (index !== -1) {
        this.localeData.domain = this.localeData.domain.substr(0, index)
      }
    },
    onChangeDate() {
      this.isOpenCalendar = false
      this.onChangeLocalData()
    },
    updateSettings() {
      this.$emit('save', this.localeData)
    },
    onChangeLocalData() {
      this.$emit('change', this.localeData)
    },
    onResetForm () {
      this.localeData = {...this.settings}
      this.$store.dispatch('hideAlert' )
      this.activeLang = this.languages.find(item => item.id === this.localeData.language_interface_id)
      this.activeCurrency = this.currencies.find(item => item.id === this.localeData.currency_id)
      this.setLocale(this.localeData.language_interface_id)
      this.$emit('reset', this.localeData)
    },
    setLocale(id) {
      const dataAlert = {startText: 'При изменении языка интерфейса, заданные системой данные в справочниках переводятся на язык интерфейса.',text: true}
      this.$store.dispatch('alertShow', dataAlert)
      const lang = this.languages.find(item => item.id === id).code
      this.$i18n.locale = lang
      localStorage.setItem('locale', this.$i18n.locale)
      this.localeData.language_interface_id = id
      this.onChangeLocalData()
    },
    setCurrency(id) {
      this.localeData.currency_id = id
      this.onChangeLocalData()
    },
    toggleDatePicker() {
      this.isBanDate ? this.isOpenCalendar = true : this.isOpenCalendar = false
    },
    changeBanDateStatus() {
      this.localeData.data_prohibition = null
      this.isOpenCalendar = false
      this.isBanDate = !this.isBanDate
    },
    formatDomain() {
      if(this.localeData.domain.match(/[^0-9a-z-]/g) != null) this.localeData.domain = this.localeData.domain.replace(/[^0-9a-z-]/g, '')
    },
    onBlurInput(key) {
      const domainSpaceOut = !this.localeData[key].split(' ').join('')
      console.log('domainSpaceOut', domainSpaceOut)
      if(domainSpaceOut) {
        this.localeData[key] = this.settings.name
      }
    },
    onBlurInputName() {
      const domainSpaceOut = !this.localeData.name.split(' ').join('')
      console.log('domainSpaceOut', domainSpaceOut)
      if(domainSpaceOut) {
        this.localeData.name = this.settings.name
      }
    },
    onBlurInputDomain() {
      const domainSpaceOut = !this.localeData.domain.split(' ').join('')
      console.log('domainSpaceOut', domainSpaceOut)
      if(domainSpaceOut) {
        this.localeData.domain = this.currentCompanyName
      }
    },
    getActiveLang() {
      this.activeLang = this.languages.find(item => item.id === this.localeData.language_interface_id)
    },
    getActiveCurrency() {
      this.activeCurrency = this.currencies.find(item => item.id === this.localeData.currency_id)
    },
  }
}
</script>

<style lang="scss">
.settings-form {
  height: 100%;
  display: flex;

  &-layout {
    display: flex;
    width: 100%;
  }

  .base-btn {
    margin: auto;
    box-shadow: 0 0 0 10px #E6F3FD !important;
  }

  .v-slider {
    &--horizontal {
      margin: 0;
    }

    &__thumb {
      height: 9px;
      width: 9px;
    }

    &__thumb-label {
      background: none !important;
      border: none !important;
      color: #5893D4;
      transform: translateX(-50%) !important;
      font-size: 14px;
      line-height: 14px;

      div {
        transform: none;
      }
    }
  }

  .v-picker__body.theme--light {
    width: 287px !important;
    margin: 0;
    background-color: #fff;
    filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.05));

    .theme--light.v-btn {
      color: #317CCE;

      &.v-btn--active {
        color: #fff;
        background-color: #FF9D2B !important;
        border-color: #FF9D2B !important;
      }
    }

    .theme--light.v-date-picker-header {
      .v-date-picker-header__value {
        button {
          color: #317CCE !important;
        }
      }
    }

  }

  .theme--light.v-input {
    caret-color: transparent !important;
    color: transparent;
  }

  .v-picker.v-card.v-picker--date.theme--light {
    top: 105%;
    left: 0;
  }

  .v-text-field {

  }

  .input-wrapper {
    margin-bottom: 16px;
    position: relative;

    .days-label {
      font-size: 14px;
      margin-left: 15px;
      color: #7E7E7E;
    }
    .input {
      outline: none !important;
      width: 100%;
      border-bottom: 1px solid #9DCCFF;
      position: relative;
      padding-right: 20px;
      height: 36px;
    }

    .subtext {
      font-size: 14px;
      line-height: 110%;
      text-align: right;
      color: #7E7E7E;
      opacity: 0.5;
      position: absolute;
      right: 0;
      bottom: 25px;
    }

    input {
      font-size: 14px;
      line-height: 14px;
      color: #7E7E7E;
      font-weight: 400;
      padding-bottom: 4px;
      padding-top: 12px;
    }

    .theme--light.v-text-field {
      padding-top: 0;
      margin-top: 0;

      .v-input__control .v-input__slot:before {
        border-color: #9DCCFF;
      }
    }

    label {
      font-weight: bold;
      font-size: 13px;
      line-height: 1;
      text-transform: uppercase;
      color: #317CCE;
      display: flex;
      align-items: center;

      &.left-icon {
        .tooltip-icon {
          position: relative;
          max-height: 13px;
          display: flex;
          align-items: center;
        }
      }
    }

    input[type=number] {
      border: 1px solid #9DCCFF;
      border-radius: 5px;
      outline: none !important;
      max-width: 70px;
      height: 24px;
      text-align: center;
      margin: 15px 0;
      padding: 5px 0;
      font-size: 14px;
      line-height: 14px;
      color: #7E7E7E;

      &::-webkit-outer-spin-button,
      &::-webkit-inner-spin-button {
        -webkit-appearance: none;
      }
    }
  }


  .date-picker-trigger {
    margin-bottom: 30px;

    &.disable {
      opacity: .5;
    }

    input {
      outline: none !important;
      width: 100%;
      border-bottom: 1px solid #9DCCFF;
      position: relative;
      padding-right: 20px;
      height: 40px;
    }

    svg {
      position: absolute;
      right: 0;
      bottom: 6px;
    }
  }

  .checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    margin-bottom: 22px;
    width: 100%;
    position: relative;
    cursor: pointer;

    label {
      display: inline-block;
      max-width: 200px;
      font-weight: 400;
      font-size: 14px;
      color: #7E7E7E
    }
  }

  input[type=checkbox] {
    &:after {
      cursor: pointer;
      background-color: #7CE1A4;
    }

    &:before {
      border-color: #7CE1A4;
      cursor: pointer;
    }
  }

  .checkboxes {
    display: flex;
    flex-wrap: wrap;


  }

  .tooltip-icon {
    margin-left: 10px;
    position: absolute;
    right: 0;
    top: 0;

    svg {
      outline: none !important;
    }
  }

  .slider-wrapper {
    display: flex;
    position: relative;
    padding-top: 15px;

    .slider-label {
      font-size: 14px;
      line-height: 14px;
      color: #7E7E7E;
      position: absolute;
      bottom: 48px;

      &__min {
        left: 0;
      }

      &__max {
        right: 0;
      }
    }
  }

  &-col {
    margin-right: 64px;
    width: 100%;
    max-width: 256px;

    &:last-child {
      margin-right: 0;
    }
  }
}
</style>