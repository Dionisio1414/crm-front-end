<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialog"
        max-width="1230px"
        class="dialog-large dialog-category"
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('directories.currencyWritingOption') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4">
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
          <form class="item-form currency-writing-option-form">
            <div class="lang-switch">
              <div class="lang-btn"
                   v-for="(lang, index) in languages"
                   :class="{ active: lang.code === currentLanguage() }"
                   :key="index"
              >
                {{ lang.name }}
              </div>
              <v-tooltip
                  right
                  max-width="400px"
                  text-color="#7E7E7E"
                  content-class="gray-tooltip">

                <template v-slot:activator="{ on, attrs }">
              <span class="tooltip-icon">
                <svg v-bind="attrs"
                     v-on="on"
                     width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.35569 9.59822V9.23702C7.35569 8.82315 7.43002 8.47888 7.57869 8.20422C7.72735 7.92955 7.98751 7.64549 8.35917 7.35201C8.80145 6.99834 9.08577 6.72368 9.21214 6.52803C9.34222 6.33238 9.40726 6.0991 9.40726 5.8282C9.40726 5.51215 9.30319 5.26947 9.09506 5.10016C8.88693 4.93085 8.58775 4.84619 8.1975 4.84619C7.84442 4.84619 7.51736 4.89699 7.21632 4.99857C6.91527 5.10016 6.62166 5.22244 6.33548 5.36542L5.86719 4.37212C6.62166 3.94696 7.43002 3.73438 8.29227 3.73438C9.02073 3.73438 9.59866 3.91497 10.0261 4.27617C10.4535 4.63737 10.6672 5.1359 10.6672 5.77176C10.6672 6.05395 10.6263 6.30604 10.5445 6.52803C10.4628 6.74625 10.3383 6.95507 10.171 7.15448C10.0075 7.35389 9.72317 7.61351 9.31806 7.93332C8.97241 8.20798 8.74013 8.43561 8.62119 8.61621C8.50598 8.79681 8.44837 9.03949 8.44837 9.34425V9.59822H7.35569ZM7.12712 11.3986C7.12712 10.8304 7.40029 10.5464 7.94663 10.5464C8.21423 10.5464 8.41864 10.6216 8.55987 10.7721C8.7011 10.9189 8.77172 11.1277 8.77172 11.3986C8.77172 11.6657 8.69924 11.8783 8.5543 12.0363C8.41306 12.1906 8.21051 12.2677 7.94663 12.2677C7.68275 12.2677 7.4802 12.1925 7.33896 12.042C7.19773 11.8877 7.12712 11.6732 7.12712 11.3986Z"
                    fill="#BBDBFE"/>
                <circle cx="8" cy="8" r="7.5" stroke="#BBDBFE"/>
              </svg>
              </span>
                </template>
                <span>
                {{ $t('directories.currencyWritingOptionTooltip') }}
              </span>
              </v-tooltip>
            </div>
            <v-row>
              <v-col cols="12">
                <div class="label-title">{{ $t('directories.wholePart') }}</div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle mb-1">{{ $t('directories.gender') }}</div>
                  <v-select
                      content-class="units-switcher" class="select-switcher units-switcher"
                      solo
                      dense
                      menu-props="units"
                  ></v-select>
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.aloneFemale') }}</div>
                  <input type="text" placeholder="Гривна">
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.twoFemale') }}</div>
                  <input type="text" placeholder="Гривни">
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.fiveMale') }}</div>
                  <input type="text" placeholder="Гривен">
                </div>
              </v-col>
              <v-col cols="12" class="mt-8">
                <div class="label-title">{{ $t('directories.fractionalPart') }}</div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle mb-1">{{ $t('directories.gender') }}</div>
                  <v-select
                      content-class="units-switcher" class="select-switcher units-switcher"
                      solo
                      dense
                      menu-props="units"
                  ></v-select>
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.aloneFemale') }}</div>
                  <input type="text" placeholder="Копейка">
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.twoFemale') }}</div>
                  <input type="text" placeholder="Копейки">
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.fiveMale') }}</div>
                  <input type="text" placeholder="Копеек">
                </div>
              </v-col>
              <v-col cols="12" class="mt-8">
                <div class="label-title">{{ $t('directories.example') }}</div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.sum') }}</div>
                  <input type="text" placeholder="0.00">
                </div>
              </v-col>
              <v-col cols="3">
                <div class="item-name">
                  <div class="label-title label-title__subtitle">{{ $t('directories.sumCursive') }}</div>
                  <input type="text" placeholder="Ноль гривен 00 копеек">
                </div>
              </v-col>
            </v-row>
          </form>
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="cancel"
            >
              {{ $t('page.cancelButton') }}
            </v-btn>
            <v-btn
                class="popup-btn"
                color="#5893D4"
                dark
                @click="onSave"
            >
              {{ $t('page.saveButton') }}
            </v-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "currencyWritingOption",
  data() {
    return {
      dialog: false,
      localeFormDate: {
        wholePart: {
          gender: '',
          aloneFemale: '',
          aloneMale: '',
          twoFemale: '',
          twoMale: '',
          fiveFemale: '',
          fiveMale: '',
        },
        fractionalPart: {
          gender: '',
          aloneFemale: '',
          aloneMale: '',
          twoFemale: '',
          twoMale: '',
          fiveFemale: '',
          fiveMale: '',
        }
      },
      languages: [
        {
          code: 'ru',
          name: 'Рус',
        },
        {
          code: 'ua',
          name: 'Укр',
        },
      ]
    }
  },
  methods: {
    cancel() {
      this.dialog = false
    },
    open() {
      this.dialog = true
    },
    currentLanguage(){
      return this.$i18n.locale
    },
    onSave() {

    }
  }
}
</script>

<style lang="scss">

.currency-writing-option-form {
  padding-bottom: 100px !important;
  .lang-switch {
    display: flex;
    justify-content: flex-start;
    padding-top: 30px;
    margin-bottom: 30px;
    .lang-btn {
      width: 40px;
      height: 20px;
      background-color: transparent;
      border: 1px solid #E6F3FD;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      margin-right: 10px;
      &:last-child {
        margin-right: 0;
      }
      &.active {
        background-color: #BBDBFE;
      }
    }
  }
  .label-title__subtitle {
    color: #9DCCFF;
    margin-bottom: 0;
  }
  .select-switcher.units-switcher {
    max-width: 100%;
  }
  .v-text-field__details{
    display: none;
  }
  .col {
    padding: 0 12px;
  }
  .v-text-field.v-text-field--solo.v-input--dense > .v-input__control {
    min-height: 32px;
  }
}
</style>