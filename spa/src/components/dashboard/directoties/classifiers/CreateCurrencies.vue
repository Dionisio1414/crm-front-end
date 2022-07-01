<template>
  <div class="dialog-wrap currency-form">
    <v-dialog
        v-model="dialog"
        max-width="1230px"
        class="dialog-large dialog-category"
        @click:outside="cancel"
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('directories.currency') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" @click="addTab('currencies', 'directories.currency')" :disabled="checkTabs" v-if="mode === 'create'">
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
        <div class="dialog-content dialog-content-large">
          <form class="item-form currency-form">
            <v-row>
              <v-col cols="6">
                <v-row>
                  <v-col cols="9">
                    <div class="item-name">
                      <div class="label-title ">{{ $t('directories.currencyName') }} <span class="mark-required">*</span>
                        <span class="default-icon" @click="localFormData.is_default = !localFormData.is_default">
                          <svg v-if=localFormData.is_default width="16" height="16" viewBox="0 0 16 16" fill="none"
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
                        </span>
                      </div>
                      <span class="writing-options" @click="onCurrencyWritingClick">
                        Параметры прописи валюты
                      </span>
                      <input type="text" placeholder="Введите наименование валюты" :readonly=" mode === 'view'" v-model="localFormData.cells.title">
                    </div>
                  </v-col>
                  <v-col cols="9" class="currency-course">
                    <div class="item-name">
                      <div class="label-title">{{ $t('directories.currencyCourse') }}</div>
                    </div>
                    <div class="checkbox-wrapper">
                      <input
                          type="checkbox"
                          id="entered-heandle"
                          v-model="localFormData.is_manual_currency"
                      />
                      <label for="entered-heandle">{{ $t('directories.handleCourseParams') }}</label>
                    </div>
                    <div class="checkbox-wrapper">
                      <input
                          type="checkbox"
                          id="download"
                          disabled
                          v-model="localFormData.is_download_currency"
                      />
                      <label for="download">{{ $t('directories.downloadedCourseParams') }}</label>
                    </div>
                    <div class="checkbox-wrapper">
                      <input
                          type="checkbox"
                          id="bind-to-other"
                          v-model="localFormData.is_other_currency"
                          disabled
                      />
                      <label for="bind-to-other">{{ $t('directories.tiedCourseParams') }}</label>
                    </div>
                    <div class="additional-params" :class="{ 'disabled-row': !bindToOther }">
                      <v-row>
                        <v-col cols="8" class="pt-0">
                          <div class="item-name">
                            <div class="label-title">{{ $t('directories.tiedCurrency') }}</div>
                            <v-select content-class="units-switcher" class="select-switcher units-switcher"
                                      item-text="name"
                                      item-value="name"
                                      placeholder="Выберите валюту"
                                      v-model="tiedParams.currency"
                                      solo
                                      dense
                                      menu-props="units"
                                      :disabled="!bindToOther"
                            ></v-select>
                          </div>
                        </v-col>
                        <v-col cols="4" class="pt-0">
                          <div class="item-name">
                            <div class="label-title">наценка</div>
                            <div class="input-wrapper">
                              <svg width="12" height="18" viewBox="0 0 12 18" fill="none"
                                   xmlns="http://www.w3.org/2000/svg">
                                <rect y="16" width="12" height="2" fill="#9DCCFF"/>
                                <rect y="5" width="12" height="2" fill="#9DCCFF"/>
                                <rect x="7" width="12" height="2" transform="rotate(90 7 0)" fill="#9DCCFF"/>
                              </svg>
                              <input type="number" step="0.50" :readonly="!bindToOther" v-model="tiedParams.addedValue"
                                     placeholder="0.00">
                              <div class="appearance">
                                  <span type="none" @click="enlargeAddedValue">
                                  <svg width="11" height="6" viewBox="0 0 11 6" fill="none"
                                       xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.07078 0.162909L0.17573 4.70779C0.0624223 4.8129 1.24827e-08 4.95323 1.06984e-08 5.10285C8.9142e-09 5.25247 0.0624223 5.39279 0.17573 5.49791L0.536134 5.83261C0.770977 6.0504 1.15267 6.0504 1.38715 5.83261L5.49772 2.01616L9.61285 5.83684C9.72616 5.94196 9.8772 6 10.0383 6C10.1995 6 10.3506 5.94196 10.464 5.83684L10.8243 5.50215C10.9376 5.39695 11 5.25671 11 5.10708C11 4.95746 10.9376 4.81714 10.8243 4.71202L5.92475 0.162909C5.81108 0.0575421 5.65932 -0.000330202 5.49799 1.67004e-06C5.33603 -0.000330216 5.18436 0.0575421 5.07078 0.162909Z"
                                        fill="#317CCE"/>
                                  </svg>
                                  </span>
                                <span @click="enlargeAddedValue">
                                    <svg width="11" height="6" viewBox="0 0 11 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z"
                                          fill="#317CCE"/>
                                    </svg>
                                  </span>
                              </div>
                              %
                            </div>

                          </div>
                        </v-col>
                      </v-row>
                    </div>
                    <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="6">
                <v-row>
                  <v-col cols="3">
                    <div class="item-name item-name--nwrp">
                      <div class="label-title">{{ $t('directories.characterCode') }} <span class="mark-required">*</span></div>
                      <input placeholder="Введите код" type="text" :readonly=" mode === 'view'" v-model="localFormData.cells.symbol_code">
                    </div>
                  </v-col>
                  <v-col cols="3">
                    <div class="item-name">
                      <div class="label-title">{{ $t('directories.digitalCode') }}</div>
                      <input placeholder="Введите код" type="text" :readonly=" mode === 'view'" v-model="localFormData.cells.digital_code">
                    </div>
                  </v-col>
                  <v-col cols="3">
                    <div class="item-name">
                      <div class="label-title">{{ $t('directories.symbol') }}</div>
                      <input placeholder="Введите символ" type="text" :readonly=" mode === 'view'" v-model="localFormData.cells.symbol">
                    </div>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </form>
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn base-button--canc"
                color="#5893D4"
                text
                @click="cancel"
            >
              {{ $t('page.cancelButton') }}
            </v-btn>
            <v-btn
                class="popup-btn white--text base-button base-button--save"
                color="#5893D4"
                text-color="#fff"
                :disabled="!(localFormData.cells.digital_code && localFormData.cells.title)"
                @click="onSave"
            >
              {{ $t('page.saveButton') }}
            </v-btn>
          </div>
        </div>
      </div>
    </v-dialog>
    <currency-writing-option ref="modal"></currency-writing-option>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import CurrencyWritingOption from "@/components/dashboard/directoties/classifiers/CurrencyWritingOption";
import mixin from '@/mixins/mixinTabs'

export default {
  name: "CreateCurrencies",
  components: { CurrencyWritingOption },
  mixins: [mixin],
  props: {
    itemData: Object,
  },
  data() {
    return {
      dialog: false,
      bindToOther: false,
      mode: 'create',
      localFormData: {
        is_default: false,
        is_download_currency: false,
        is_editable: true,
        is_manual_currency: true,
        is_other_currency: false,
        cells: {
          title: "",
          symbol_code: "",
          digital_code: null,
          exchange_rate: null,
          multiple: null,
          symbol: "",
        },
      },
      tiedParams: {
        currency: '',
        addedValue: 0
      }
    }
  },
  created () {
    this.defaultData = JSON.parse(JSON.stringify(this.localFormData))
  },
  computed: {
    ...mapGetters([
      'tabsLength'
    ]),
    validation() {
      const validationResult = this.localFormData.digitalCode.length && this.localFormData.name.length
      return validationResult
    },
  },
  methods: {
    ...mapActions([
      'updateTabs'
    ]),
    onSave() {
      const { params } = this.$route.params

      if(params) {
        this.$emit('save', {
          action: this.mode,
          resources: 'currencies',
          data: {...this.localFormData},
          alertItem: 'directories.currencies'
        })
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.$router.go(-1)
        this.dialog = false
      } else {
        this.$emit('save', {
          action: this.mode,
          resources: 'currencies',
          data: {...this.localFormData},
          alertItem: 'directories.currencies'
        })
        this.dialog = false
      }
    },
    enlargeAddedValue() {
      if (this.bindToOther) {
        this.tiedParams.addedValue = parseFloat(this.tiedParams.addedValue) + 0.5
      }
    },
    decreaseAddedValue() {
      if (this.bindToOther) {
        this.tiedParams.addedValue = parseFloat(this.tiedParams.addedValue) - 0.5

      }
    },
    changeDefaultState() {
      this.localFormData.is_default = !this.localFormData.is_default
      if (this.mode !== 'create') {
        this.$emit('changeDefault', {id: this.localFormData.id, resources: 'units'})
      }
    },
    onCurrencyWritingClick() {
      this.$refs.modal.open()
    },
    cancel() {
      const { params } = this.$route.params
      if(params) {
        this.$router.go(-1)
        this.$router.push(this.$route.path.slice(0, -params.length))
      }
      this.dialog = false
    },
    open(mode, itemData) {
      this.mode = mode
      this.localFormData = mode === 'create' ? JSON.parse(JSON.stringify(this.defaultData)) : { ...itemData }
      this.dialog = true
    },
    close() {
      this.dialog = false
    }
  }

}
</script>

<style lang="scss">
.currency-form {
  .label-title {
    font-size: 12px;
    margin-bottom: 0;
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
      font-weight: 400;
      font-size: 15px;
      color: #7E7E7E;
      text-transform: inherit;
    }
  }

  .input-wrapper {
    display: flex;
    align-items: center;

    input[type=number] {
      appearance: none;
      margin: 0;
    }

    .appearance {
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: space-around;
      padding: 0 5px;

      span {
        display: inline-flex;
        cursor: pointer;
      }
    }

    svg {
      margin-right: 5px;
      min-width: 12px;
    }
  }

  .writing-options {
    font-weight: 400;
    font-size: 13px;
    line-height: 13px;
    position: absolute;
    right: 0;
    bottom: 10px;
    cursor: pointer;
    text-decoration: underline;

    &:hover {
      text-decoration: none;
    }
  }

  .default-icon {
    float: right;
    max-height: 18px;
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

  .v-text-field__details {
    display: none;
  }

  .checkboxes {
    display: flex;
    flex-wrap: wrap;
  }

  .select-switcher.units-switcher {
    max-width: 100%;
  }

  .additional-params {
    &.disabled-row {
      opacity: 50%;
    }
  }

  .validation-rules {
    font-size: 15px;
    line-height: 15px;
    color: #9DCCFF;
  }

  .v-text-field.v-text-field--solo.v-input--dense > .v-input__control {
    min-height: 32px;
  }

  .currency-course {
    margin-top: 10px;
    > .item-name {
      margin-bottom: 18px;
      > .label-title {
        margin-bottom: 0;
      }
    }
    .validation-rules {
      margin-top: 32px;
    }
  }
}
.item-name--nwrp {
  white-space: nowrap;
}
.base-button--save:hover {
  background: #FF9F2F!important;
}
.base-button--canc {
  transition: 0.25s ease-in-out;
}
.base-button--canc:hover {
  background: #5893D4!important;
  color: #fff!important; 
}
</style>