<template>
  <modal-container 
      v-if="isModalOpen" 
      @clickOutside="onCloseModal"
      :dialog="isModalOpen" 
      :modalWidth="1234"
      :customDialogClass="['modal-container-prices']"
  >
    <template v-slot:header>
      <div class="dialog-header">
        <div class="header-text">
          <span>{{ $t('directories.typesOfPrice') }}</span>
        </div>
        <div class="dialog-header-actions">
          <v-btn icon color="#5893D4" @click="addTab('prices_types', 'directories.typesOfPrice')" :disabled="checkTabs" v-if="mode === 'create'">
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
      <div class="dialog-body">
        <div class="dialog-body-content">
          <form class="form">
            <div class="form-content">
              <v-row>
                <v-col cols="6">
                  <v-row>
                    <v-col cols="12">
                      <div class="label-title label-title--required label-title--singular">
                        <div class="label-title-text">
                          {{ $t('directories.name') }}
                        </div>
                        <!-- <span class="default-icon" @click="changeDefaultState">
                            <svg v-if="localFormData.is_default" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                              <path
                                  d="M3.61065 15.9392C3.22465 16.1504 2.78665 15.7803 2.86465 15.3078L3.69465 10.2626L0.171653 6.68298C-0.157347 6.34806 0.0136534 5.73581 0.454653 5.66968L5.35265 4.9273L7.53665 0.31199C7.73365 -0.103997 8.26665 -0.103997 8.46365 0.31199L10.6477 4.9273L15.5457 5.66968C15.9867 5.73581 16.1577 6.34806 15.8287 6.68298L12.3057 10.2626L13.1357 15.3078C13.2137 15.7803 12.7757 16.1504 12.3897 15.9392L7.99865 13.5329L3.60965 15.9392H3.61065Z"
                                  fill="#FFE923"/>
                            </svg>
                            <svg v-else width="16" height="16" viewBox="0 0 16 16" fill="none"
                                  xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M2.86465 15.3067C2.78665 15.7803 3.22465 16.1504 3.61065 15.9392L8.00065 13.533L12.3897 15.9392C12.7757 16.1504 13.2137 15.7803 13.1357 15.3078L12.3057 10.2628L15.8287 6.68338C16.1577 6.34847 15.9867 5.73625 15.5457 5.67012L10.6477 4.92778L8.46365 0.312663C8.42205 0.219111 8.35641 0.140071 8.27436 0.0847254C8.19232 0.0293796 8.09723 0 8.00015 0C7.90308 0 7.80799 0.0293796 7.72594 0.0847254C7.6439 0.140071 7.57826 0.219111 7.53665 0.312663L5.35265 4.92884L0.454653 5.67119C0.0136534 5.73732 -0.157347 6.34954 0.171653 6.68444L3.69465 10.2639L2.86465 15.3089V15.3067ZM7.76965 12.3555L4.08365 14.3756L4.77765 10.1551C4.79391 10.058 4.78755 9.95804 4.75913 9.86412C4.73071 9.7702 4.6811 9.68517 4.61465 9.61649L1.70865 6.66205L5.76065 6.04769C5.84456 6.03418 5.92414 5.99917 5.99259 5.94568C6.06103 5.89218 6.1163 5.82178 6.15365 5.74052L8.00065 1.83895L9.84665 5.74052C9.88401 5.82178 9.93928 5.89218 10.0077 5.94568C10.0762 5.99917 10.1557 6.03418 10.2397 6.04769L14.2917 6.66098L11.3857 9.61542C11.319 9.68416 11.2692 9.76936 11.2408 9.8635C11.2124 9.95763 11.2061 10.0578 11.2227 10.1551L11.9167 14.3756L8.23065 12.3555C8.15938 12.3163 8.08035 12.2958 8.00015 12.2958C7.91996 12.2958 7.84093 12.3163 7.76965 12.3555Z"
                                    fill="#E3F0FF"/>
                              </svg>
                        </span> -->
                      </div>
                      <div class="form-field">
                        <input 
                          class="field" 
                          type="text" 
                          :readonly="mode === 'view'" 
                          v-model="localFormData.core_title"
                          @input="onDouble"
                          :placeholder="$t('directories.name_placeholder')"
                        >
                      </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <div class="label-title">{{ $t('directories.name_in_list') }}</div>
                      <div class="form-field">
                        <input 
                          class="field" 
                          type="text" 
                          @focus="onFocusDouble"
                          v-model="localFormData.core_title_price_list"
                          :placeholder="$t('directories.name_in_list_placeholder')"
                        >
                      </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <div class="radio-wrapper" :class="{'radio-wrapper--disabled': margin}">
                        <div class="radio-wrapper-title">
                          {{ $t('directories.rounding') }}
                        </div>
                        <div class="radio-wrapper-row">
                          <div class="radio">
                            <label class="radio-label">
                              <input 
                                type="radio" 
                                name="radio"
                                value="true" 
                                @change="onChangeRadio($event, 'is_rounding_without')" 
                                v-model="localFormData.is_rounding_without"
                                :disabled="mode === 'view'"
                              >
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.rounding_without_text') }}
                                </div>
                              </div>
                            </label>
                          </div>
                          <div class="prices-row">  
                            <div class="price-list">
                              <div 
                                class="price-list-item" 
                                v-for="(val, index) in roundedArrays.firstRow[0]" 
                                :key="index"
                              >
                                {{ val }}
                              </div>
                            </div>
                            <div class="price-list">
                              <div 
                                class="price-list-item" 
                                v-for="(val, index) in roundedArrays.firstRow[1]" 
                                :key="index"
                              >
                                {{ val }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="radio-wrapper-row">
                          <div class="radio">
                            <label class="radio-label">
                              <input 
                                type="radio" 
                                name="radio"
                                value="true"
                                @change="onChangeRadio($event, 'is_rounding_with')"
                                v-model="localFormData.is_rounding_with"
                              >
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.rounding_with_text') }}
                                </div>
                              </div>
                            </label>
                          </div>
                          <div class="prices-row">  
                            <div class="price-list">
                              <div class="price-list-item" v-for="(val, index) in roundedArrays.secondRow[0]" :key="index">
                                {{ val }}
                              </div>
                            </div>
                            <div class="price-list">
                              <div class="price-list-item" v-for="(val, index) in roundedArrays.secondRow[1]" :key="index">
                                {{ val }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="radio-wrapper-row">
                          <div class="radio">
                            <label class="radio-label">
                              <input 
                                type="radio" 
                                name="radio" 
                                value="true"
                                @change="onChangeRadio($event, 'is_step_with')"
                                v-model="localFormData.is_step_with"
                              >
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.step_with_text') }}
                                </div>
                              </div>
                            </label>
                          </div>
                          <div class="prices-row">  
                            <div class="price-list">
                            <div class="price-list">
                              <div class="price-list-item" v-for="(val, index) in roundedArrays.thirdRow[0]" :key="index">
                                {{ val }}
                              </div>
                            </div>
                            </div>
                            <div class="price-list">
                              <div class="price-list-item" v-for="(val, index) in roundedArrays.thirdRow[1]" :key="index">
                                {{ val }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </v-col>
                  </v-row>
                </v-col>

                <v-col cols="6">
                  <v-row>
                    <v-col cols="6">
                      <div class="label-title label-title--required">{{ $t('directories.type_of_price') }}</div>
                      <div class="select-wrap">
                        <v-select 
                          :items="typePricesList"
                          class="select-switcher"
                          :disabled="typePrice === 1"
                          solo
                          dense
                          :menu-props="{ contentClass: 'base-select'}"
                          v-model="localFormData.core_type_price_id"
                          item-text="title"
                          @change="onChangeMainSelect($event, 'core_type_price_id')"
                          item-value="directory_id"
                          return-object
                        >
                        </v-select>
                      </div>
                    </v-col>
                    <v-col cols="6" v-if="typePrice !== 1">
                      <div class="label-title label-title--required">{{ $t('directories.currency') }}</div>
                      <div class="select-wrap">
                        <v-select 
                          :items="currenciesList"
                          class="select-switcher"
                          solo
                          dense
                          :menu-props="{ contentClass: 'base-select'}"
                          v-model="localFormData.core_currency_id"
                          item-text="title"
                          item-value="id"
                          @change="onChangeMainSelect($event, 'core_currency_id')"
                          return-object
                        >
                        </v-select>
                      </div>
                    </v-col>
                    <v-col cols="6" v-else>
                      <div class="radio-wrapper">
                        <div class="radio-wrapper-title radio-wrapper-title--required">
                          {{ $t('directories.purpose') }} 
                        </div>
                        <div class="radio-wrapper-row">
                          <div class="radio">
                            <label class="radio-label">
                              <input 
                                type="radio" 
                                name="radio-1" 
                                :value="true" 
                                v-model="localFormData.is_buy"
                              >
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.purchase') }} 
                                </div>
                              </div>
                            </label>
                          </div>
                        </div>
                        <div class="radio-wrapper-row">
                          <div class="radio" :style="{'opacity': typePrice === 1 ? '.5' : '1', 'pointer-events': typePrice === 1 ? 'none' : 'visible'}">
                            <label class="radio-label">
                              <input 
                                type="radio" 
                                name="radio-1" 
                                :value="false" 
                                v-model="localFormData.is_buy"
                              >
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.sale') }} 
                                </div>
                              </div>
                            </label>
                          </div>
                        </div>
                      </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="6" :style="{ 'opacity': margin ? 0.4 : 1 }">
                      <div class="label-title label-title--required">{{ $t('directories.price_of_type') }}</div>
                      <div class="select-wrap">
                        <v-select 
                          :disabled="margin"
                          :items="pricesTypesList"
                          class="select-switcher"
                          solo
                          dense
                          :menu-props="{ contentClass: 'base-select'}"
                          v-model="localFormData.core_type_price_calculate_margin_percent_id"
                          item-text="title"
                          @change="onChangeMainSelect($event, 'core_type_price_calculate_margin_percent_id')"
                          item-value="id"
                          return-object
                        >
                        </v-select>
                      </div>
                    </v-col>
                    <v-col cols="6" v-if="typePrice !== 1">
                      <div class="radio-wrapper">
                        <div class="radio-wrapper-title radio-wrapper-title--required">
                          {{ $t('directories.purpose') }} 
                        </div>
                        <div class="radio-wrapper-row">
                          <div 
                            class="radio" 
                            :style="{
                              'opacity': typePrice === 2 || typePrice === 3 ? '.5' : '1', 
                              'pointer-events': typePrice === 2 || typePrice === 3 ? 'none' : 'visible'
                            }"
                          >
                            <label class="radio-label">
                              <input type="radio" name="radio-1" :value="true" v-model="localFormData.is_buy">
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.purchase') }} 
                                </div>
                              </div>
                            </label>
                          </div>
                        </div>
                        <div class="radio-wrapper-row">
                          <div class="radio">
                            <label class="radio-label">
                              <input type="radio" name="radio-1" :value="false" v-model="localFormData.is_buy">
                              <div class="radio-text">
                                <div class="text">
                                  {{ $t('directories.sale') }} 
                                </div>
                              </div>
                            </label>
                          </div>
                        </div>
                      </div>
                    </v-col>
                    <v-col cols="6" :style="{ 'opacity': margin ? 0.4 : 1 }">
                      <div class="label-title">{{ $t('directories.markup') }}</div>
                      <div class="form-field">
                        <input
                          class="field-number"
                          :disabled="margin"
                          type="text"
                          v-model="localFormData.margin_percent"
                        >
                        <span class="field-label">
                          %
                        </span>
                      </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="6" :style="{ 'opacity': margin ? 0.4 : 1 }">
                      <div class="slider-wrapper">
                        <span 
                          class="slider-label slider-label__min"
                          v-if="localFormData.margin_percent > sliderParams.min + 15">
                          {{ sliderParams.min }}
                        </span>
                        <v-slider
                            :disabled="margin"
                            :color="sliderParams.color"
                            :trackColor="'#E3F0FF'"
                            v-model="localFormData.margin_percent"
                            :thumb-color="sliderParams.color"
                            thumb-label="always"
                            :max="sliderParams.max"
                            :min="sliderParams.min"
                        >
                        </v-slider>
                        <span 
                          class="slider-label slider-label__max"
                          v-if="localFormData.margin_percent < sliderParams.max -20">
                          {{ sliderParams.max }}
                        </span>
                      </div>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div 
                    class="validation-rules"
                  >
                    {{ $t('directories.validationRules') }}
                </div>
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
            v-if="mode === 'view' || mode === 'change' || mode === 'create'"
            type="button" 
            class="base-button base-button--transparent" 
            :disabled="loader"
            @click="cancel"
          >
            <template v-if="mode === 'create'">
              {{ $t('page.cancelButton') }}
            </template>
            <template v-else>
              {{ $t('page.close') }}
            </template>
          </button>
          <button 
            type="button" 
            class="base-button base-button--blue" 
            :disabled="$v.localFormData.$invalid || loader"
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
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import { mapActions, mapGetters } from 'vuex'
import mixin from '@/mixins/mixinTabs'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"

export default {
  name: "ModalTypes",
  props: {
    itemData: Object,
    isModalOpen: Boolean,
    modeType: String
  },
  components: {
    ModalContainer
  },
  mixins: [validationMixin, mixin],
  validations: {
    localFormData: {
      core_title: { required },
      core_type_price_id: { required },
      core_currency_id: { required },
      is_buy: { required }
    }
  },
  data: () => ({
    mode: 'create',
    dialog: false,
    id: 'type-price',
    title: 'directories.typesOfPrice',
    isDouble: true,
    loader: false,
    localFormData: {
      is_default: false,
      is_editable: true,
      is_rounding_without: false,
      is_rounding_with: false,
      is_step_with: false,
      core_type_price_id: 1,
      core_currency_id: 1,
      is_buy: false,
      core_type_price_calculate_margin_percent_id: 1,
      margin_percent: null,
      core_title: null,
      core_title_price_list: null,
    },
    sliderParams: {
      color: '#4ECA80',
      min: 0,
      max: 100
    },
    roundedArrays: {
      firstRow: [['10,01', '11'], ['10,55', '11']],
      secondRow: [['10,01', '10,50'], ['10,55', '11,00']],
      thirdRow: [['10,01', '10,10'], ['10,55', '11,60']]
    }
  }),
  computed: {  
    ...mapGetters([
      'getLists',
    ]),
    tableCells() {
      return this.localFormData
    },
    margin() {
      if(this.localFormData.core_type_price_id !== 2) return true
      return false
    },
    currenciesList() {
      return this.getLists('lists')['currencies']
    },
    typePricesList() {
      return this.getLists('core_lists')['type_prices']
    },
    pricesTypesList() {
      return this.getLists('lists')['prices_types'].types
    },
    typePrice() {
      if(this.mode === 'view' && this.localFormData.id && this.localFormData.id === 1) {
        return this.localFormData.id
      } else if(this.mode === 'view' && this.localFormData.id && this.localFormData.id === 2 || this.mode === 'view' && this.localFormData.id && this.localFormData.id === 3) {
        return this.localFormData.id
      } else {
        return false
      }
    }
  },
  methods: {
    ...mapActions(['getPaginationCounter']),
    onCloseModal() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(this.$route.params.params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    onDouble() {
      const { core_title } = this.localFormData
      if(this.isDouble) this.localFormData.core_title_price_list = core_title
    },
    onFocusDouble() {
      this.isDouble = false
    },
    onSave() {
      const { params } = this.$route.params
      if(params) {
        this.$emit('save', {
          action: this.mode,
          resources: 'prices_types',
          data: {
            ...this.localFormData,
            margin_percent: this.localFormData.core_type_price_id === 1 ? null : this.localFormData.margin_percent,
          },
          alertItem: 'directories.typesOfPrices'
        })
        this.$router.go(-1)
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.onCloseModal()
        this.getPaginationCounter('clear')
      } else {
          this.$emit('save', {
          action: this.mode === 'view' || this.mode === 'change' ? 'change' : 'create',
          resources: 'prices_types',
          data: {
            ...this.localFormData,
            margin_percent: this.localFormData.core_type_price_id === 1 ? null : this.localFormData.margin_percent,
            core_currency_id: this.localFormData.id === 1 ? null : this.localFormData.core_currency_id
          },
          alertItem: 'directories.typesOfPrices'
        })
        this.loader = true
        this.getPaginationCounter('clear')
      }
    },
    changeDefaultState() {
      this.localFormData.is_default = !this.localFormData.is_default
      if (this.mode !== 'create') {
        this.$emit('changeDefault', {id: this.localFormData.id, resources: 'units'})
      }
    },
    cancel() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(this.$route.params.params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.onCloseModal()
      }
    },
    onChangeMainSelect(obj, value) {
      const correctKeyId = obj.id ? obj.id : obj.directory_id
      console.log(correctKeyId, value)
      this.localFormData[value] = correctKeyId
      this.localFormData.margin_percent = 0
      if(value === 'core_type_price_id') {
        this.localFormData.is_rounding_without = false
        this.localFormData.is_rounding_with = false
        this.localFormData.is_step_with = false
      }
    },
    onChangeRadio(e, type) {
      console.log('work')
      switch(type) {
        case "is_rounding_without":
          this.localFormData[type] = Boolean(e.target.value)
          this.localFormData['is_rounding_with'] = false
          this.localFormData['is_step_with'] = false
          break
        case "is_rounding_with":
          this.localFormData[type] = Boolean(e.target.value)
          this.localFormData['is_rounding_without'] = false
          this.localFormData['is_step_with'] = false
          break
        case "is_step_with":
          this.localFormData[type] = Boolean(e.target.value)
          this.localFormData['is_rounding_without'] = false
          this.localFormData['is_rounding_with'] = false
          break
        default:
          this.localFormData[type] = Boolean(e.target.value)
      }
    }
  },
  mounted() {
    console.log(this.itemData)
    this.mode = this.modeType
    if(this.modeType !== 'create') {
      this.localFormData = {
        ...this.itemData,
        core_currency_id: this.itemData.core_currency_id !== null ? this.itemData.core_currency_id : 1,
        core_title: this.itemData.cells.title,
        core_title_price_list: this.itemData.cells.title_price_list,
        core_type_price_calculate_margin_percent_id: this.itemData.core_type_price_calculate_margin_percent_id !== null ? this.itemData.core_type_price_calculate_margin_percent_id : 1,
        is_step_with: this.itemData.is_step_with === 1 ? true : false
      }
    }
  },
}
</script>

<style lang="sass">
.modal-container-prices
  .select-wrap .theme--light.v-select .v-select__selection--comma.v-select__selection--disabled
    color: #000!important
  .form
    &-content
      > .row
          .col-6
            &:first-child
              > .row
                  &:not(:last-child)
                    margin-bottom: 30px
            &:last-child
              .radio-wrapper
                &-row
                  &:not(:last-child)
                    margin-bottom: 15px
              > .row
                  &:not(:last-child)
                    margin-bottom: 30px

  .radio-text
    .text
      padding-right: 0

  .radio-wrapper
    &--disabled
      opacity: .5
      pointer-events: none
    &-title
      display: inline-block
      position: relative
      font-size: 13px
      font-weight: bold
      line-height: 1
      text-transform: uppercase
      margin-bottom: 15px
      color: #317CCE
      &--required
        &::before
          content: "*"
          position: absolute
          right: -10px
          top: 0
          color: #9DCCFF

    &-row
      display: flex
      align-items: center
      justify-content: space-between
      &:not(:last-child)
        margin-bottom: 2px
      .prices-row
        background: #F4F9FF
        display: flex
        align-items: center
        justify-content: space-between
        padding: 11px 20px
        border-radius: 10px
        flex: 0 1 calc(50% - 15px)
        .price-list
          display: flex
          align-items: center
          flex: 0 1 calc(50% - 15px)
          &:not(:last-child)
            margin-right: 25px
          &-item
            position: relative
            font-size: 14px
            line-height: 1
            color: rgba(126,126,126, .7)
            + .price-list-item
                padding-left: 30px
                &::before
                  content: ""
                  background-image: url("~@/assets/icons/small-arrow-right.svg")
                  background-repeat: no-repeat
                  background-size: contain
                  position: absolute
                  top: 50%
                  left: 12px
                  transform: translateY(-50%)
                  width: 15px
                  height: 15px

</style>