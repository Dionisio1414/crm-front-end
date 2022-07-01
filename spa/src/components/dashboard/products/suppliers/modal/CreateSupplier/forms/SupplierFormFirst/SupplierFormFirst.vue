<template>
  <simplebar class="item-form supplierFirstForm create-new-item">
    <v-row no-gutters>
      <v-col cols="6">
        <v-row>
          <v-col cols="6">
            <div class="item-name">
              <div class="label-title label-title-select required">
                {{ $t('page.suppliers.modal.createSupplier.firstForm.view') }}
              </div>
              <div class="select-wrap">
                <div class="item select-category">
                  <div class="input-wrap">
                    <v-select
                        class="select-switcher"
                        v-model="form.statusSupplier"
                        :items="viewAll"
                        :menu-props="{contentClass: 'currencyDropDown'}"
                        :class="{'error-on-select':  $v.form.statusSupplier.$error }"
                        @blur="$v.form.statusSupplier.$touch()"
                        item-text="title"
                        item-value="id"
                        no-data-text="Пусто"
                        :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.selectView')"
                        @change="onViewSelect"
                        dense
                    ></v-select>
                  </div>
                </div>
              </div>
            </div>
          </v-col>
          <v-col cols="12">
            <div class="item-name">
              <div class="label-title label-text-wrap">
                {{ $t('page.suppliers.modal.createSupplier.firstForm.nameSupplier') }} *
                <span class="label-text" v-if="isValidTitle">
                  {{ $t('page.suppliers.modal.createSupplier.firstForm.error.nameExist') }}
                </span>
                <span v-if="(isCopyText && copyFirstTab && copyFirstTab.isCopy) && !isValidTitle" class="label-text copy">
                  {{ $t('page.suppliers.modal.createSupplier.firstForm.error.nameExistText') }}
                </span>
              </div>
              <v-row>
                <v-col :cols="status === null || status ? 12: 4">
                  <div class="item-name">
                    <input
                        type="text"
                        @input="onDouble"
                        v-model="form.nameOfSupplier"
                        :class="{'error-on-select':  $v.form.nameOfSupplier.$error }"
                        @change="$v.form.nameOfSupplier.$touch()"
                        @blur="onValid('title_supplier','nameOfSupplier')"
                        :placeholder="status === null || status
                        ? $t('page.suppliers.modal.createSupplier.firstForm.placeholderNameSupplier')
                        : $t('page.suppliers.modal.createSupplier.firstForm.surname')"
                    >
                  </div>
                </v-col>
                <v-col v-if="status !== null && !status" cols="4">
                  <div class="item-name">
                    <input
                        type="text"
                        @input="onDouble"
                        v-model="form.nameOfSupplierSecond"
                        :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.name')"
                    >
                  </div>
                </v-col>
                <v-col v-if="status !== null && !status" cols="4">
                  <div class="item-name">
                    <input
                        type="text"
                        @input="onDouble"
                        v-model="form.nameOfSupplierThird"
                        :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.patronymic')"
                    >
                  </div>
                </v-col>
              </v-row>
            </div>
          </v-col>
          <v-col cols="12">
            <div class="item-name">
              <div class="label-title">
                {{ $t('page.suppliers.modal.createSupplier.firstForm.nameOfCompany') }}
                <span>({{ $t('page.suppliers.modal.createSupplier.firstForm.viewInDocument') }})*</span>
              </div>
              <input
                  type="text"
                  :class="{'error-on-select':  $v.form.nameOfCompany.$error }"
                  @blur="onValid('title_company', 'nameOfCompany')"
                  @focus="onFocus"
                  v-model="form.nameOfCompany"
                  :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.placeholderNameOfCompany')"
              >
            </div>
          </v-col>
          <v-col cols="12">
            <div v-click-outside="onClickOutsideHead" class="item nested-category">
              <div class="label-title label-title-select">
                <span>{{ $t('page.suppliers.modal.createSupplier.firstForm.groupText') }}</span>
              </div>
              <div class="input-wrap">
                <input
                    @focus="showDropDown = !showDropDown"
                    v-model="groupTitle"
                    type="text"
                    :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.groupPlaceHolder')">
                <v-btn
                    class="open-drop-down"
                    @click="showDropDown = !showDropDown"
                    icon
                >
                  <svg-sprite style="color: rgb(157,204,255)" width="11" height="16" icon-id="arrowDown"></svg-sprite>
                </v-btn>
                <div class="drop-down drop-down-categories supl" v-if="showDropDown">
                  <div class="drop-down--body">
                    <v-treeview
                        open-all
                        activatable
                        item-text="title"
                        selection-type="independent"
                        :menu-props="{contentClass: 'currencyDropDown'}"
                        :items="items"
                        item-disabled="disabled"
                    >
                      <template slot="label" slot-scope="{item}">
                        <div :item-disabled="item.id === 1" @click="selectGroup(item)">{{ item.title }}</div>
                      </template>
                    </v-treeview>
                  </div>
                </div>
              </div>
            </div>
          </v-col>
          <v-col cols="6">
            <div class="item checkbox-wrapper statusSupplier">
              <div class="checkbox">
                <span class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.foreignCompany') }}</span>
                <label class="checkbox-label">
                  <input
                      v-model="form.foreignCompany"
                      type="checkbox"
                  >
                  <span class="checkbox-text"></span>
                  <span class="text">Да</span>
                </label>
              </div>
            </div>
          </v-col>
          <v-col cols="6">
            <div class="item-name currency">
              <div class="label-title label-title-select">{{ $t('page.suppliers.modal.createSupplier.firstForm.contractCurrency') }} *</div>
              <div class="select-wrap">
                <v-select
                    class="select-switcher"
                    item-text="title"
                    item-value="id"
                    :menu-props="{contentClass: 'currencyDropDown'}"
                    :items="getCurrency"
                    v-model="form.currencyValue"
                    hide-selected
                    dense
                ></v-select>
              </div>
            </div>
          </v-col>
          <field
              cols="12"
              custom-class="manager select-wrap"
              :title="$t('page.suppliers.modal.createSupplier.firstForm.manager')"
          >
            <v-select
                class="select-switcher"
                :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.placeholderManager')"
                item-text="full_name"
                :menu-props="{contentClass: 'currencyDropDown'}"
                item-value="id"
                :items="getManager"
                v-model="form.managerValue"
                dense
            ></v-select>
          </field>
        </v-row>
      </v-col>

      <v-col v-if="status !== null" cols="6">
        <v-row v-if="status">
          <field
              cols="6"
              :title="$t('page.suppliers.modal.createSupplier.firstForm.enn')"
              :isValidInn="isValidInn"
          >
            <input
                type="number"
                v-mask="'###########'"
                @blur="onValid('partner_inn', 'idCode')"
                :class="{'error-on-select':  $v.form.idCode.$error }"
                v-model="form.idCode"
                :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.fillTheEnn')"
            >
          </field>
          <field
              cols="6"
              :title="$t('page.suppliers.modal.createSupplier.firstForm.edrpu')"
          >
            <input
                type="number"
                v-mask="'########'"
                :class="{ 'error-on-select':  $v.form.lawCode.$error }"
                @blur="$v.form.lawCode.$touch()"
                v-model="form.lawCode"
                :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.fillTheCode')"
            >
          </field>
        </v-row>
        <v-row v-if="!status">
          <field
              cols="4"
              :title="$t('page.suppliers.modal.createSupplier.firstForm.enn')"
          >
            <input
                type="number"
                :class="{ 'error-on-select':  $v.form.idCode.$error }"
                @blur="onValid('partner_inn', 'idCode')"
                v-mask="'###########'"
                v-model="form.idCode"
                :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.fillTheEnn')"
            >
          </field>
          <field
              cols="4"
              :title="$t('page.suppliers.modal.createSupplier.firstForm.dateOfBirthday')"
          >
            <masked-input
                :class="{'field': true, 'error-on-select': !isValidDate && (form.birthdayDay && form.birthdayDay.length)}"
                v-model="form.birthdayDay"
                mask="11.11.1111"
                :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.formatDate')"
            />
          </field>
          <field
              cols="4"
              customClass="birthday select-wrap"
              title="Пол"
          >
            <v-select
                class="select-switcher"
                item-text="title"
                item-value="directory_id"
                :items="getTypeOfSex"
                v-model="form.sexId"
                @change="onSex"
                :menu-props="{contentClass: 'currencyDropDown'}"
                dense
            ></v-select>
          </field>
        </v-row>
      </v-col>
    </v-row>
    <div class="annotation">(*) - {{ $t('page.suppliers.modal.createSupplier.firstForm.error.requiredFields') }}</div>
  </simplebar>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// moment
import moment from 'moment';
// components
import Field from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/Field";
import MaskedInput from "vue-masked-input";
// validate
import {required, minLength} from 'vuelidate/lib/validators';
// helpers
import inverseDate from "@/helper/inverseDate";

export default {
  name: "GoodsFormFirst",
  components: {
    'field': Field,
    'masked-input': MaskedInput
  },
  props: {
    category: Object,
    copyFirstTab: Object,
    suppliersGroups: {
      type: Array,
      default() {
        return [];
      }
    },
  },
  deactivated() {
    this.$emit('updateValid', this.$v.$invalid);
    this.$v.$touch();

    const { birthdayDay } = this.form;
    const formatDate = inverseDate(birthdayDay);

    this.$emit('stepUpdated', { ...this.form, formatDate });
  },
  computed: {
    ...mapGetters(['getLists', 'isValidFields']),
    getTypeOfSex() {
      return this.getLists('core_lists')['sex']
    },
    getCurrency() {
      return this.getLists('lists')['currencies']
    },
    defaultCurrency() {
      return this.getCurrency.find(item => item.is_default);
    },
    getManager() {
      return this.getLists('lists')['employees']
    },
    items() {
      return this.getNodes(this.suppliersGroups)
    },
    isValidDate() {
      const formatFate = inverseDate(this.form.birthdayDay);
      return moment(formatFate, 'YYYY-MM-DD', true).isValid()
    },
    isValidTitle() {
      return this.isValidFields === 'title_supplier'
    },
    isValidInn() {
      return this.isValidFields === 'partner_inn'
    }
  },
  data:() => ({
    viewAll: [
      {
        title: 'Юридическое лицо',
        id: 1
      },{
        title: 'Физическое лицо',
        id: 2
      },{
        title: 'ФОП',
        id: 3
      }],
    children: [],
    showDropHeadDown: false,
    isDouble: true,
    status: null,
    showDropDown: false,
    groupTitle: null,
    isCopyText: false,
    form: {
      statusSupplier: null,
      foreignCompany: false,
      nameOfSupplier: '',
      nameOfSupplierSecond: '',
      nameOfSupplierThird: '',
      nameOfCompany: '',
      currencyValue: 1,
      managerValue: '',
      sexId: 1,
      birthdayDay: '',
      idCode: null,
      lawCode: null,
      groupId: null,
    },
  }),
  validations: {
    form: {
      statusSupplier: { required },
      nameOfSupplier: { required },
      nameOfCompany: { required },
      lawCode: { minLength: minLength(8)},
      idCode: { minLength: minLength(11)}
    }
  },
  methods: {
    ...mapActions(['onValidAction']),
    onValid(invalidKey, key) {
      if (this.form[key]) {
        const body = {
          validate: {
            [`${invalidKey}`]: this.form[key]
          }
        }

        this.onValidAction(body)
      }

      this.$v.form[key] && this.$v.form[key].$touch()
    },
    onSex(value) {
      this.form.sexId = value;
    },
    getNodes(items) {
      return items || [].map(item => {
        const disabled = this.children.find(({ id }) => id === item.id);

        return {
          id: item.id,
          title: item.title,
          disabled: Boolean(disabled),
          children: item.children ? this.getNodes(item.children) : []
        }
      })
    },
    onDouble() {
      this.isCopyText = false
      const { nameOfSupplier, nameOfSupplierSecond, nameOfSupplierThird } = this.form;

      const fullName = `${nameOfSupplier} ${nameOfSupplierSecond ?? ''} ${nameOfSupplierThird ?? ''}`;

      if (this.isDouble) this.form.nameOfCompany = fullName;
    },
    onFocus() {
      if(this.form.nameOfCompany) this.isDouble = false;
    },
    deleteChildCategory(index) {
      this.children.splice(index, 1)
    },
    selectGroup(item) {
      const { id, title } = item;

      this.form.groupId = id;
      this.groupTitle = title;
      this.showDropDown = false;
    },
    resetFields() {
      this.form.nameOfSupplier = '';
      this.form.nameOfCompany = '';
      this.form.nameOfSupplierSecond = '';
      this.form.nameOfSupplierThird = '';
      this.isDouble = true;
    },
    onViewSelect(value) {
      this.resetFields();

      this.status = (value === 1);
    },
    onClickOutsideHead() {
      this.showDropDown = false
    }
  },
  mounted() {
    this.form.currencyValue = this.defaultCurrency?.id;

    if (this.copyFirstTab) {
      this.isCopyText = true;
      const { statusSupplier, groupItem } = this.copyFirstTab;

      const groupId = groupItem && groupItem.id;

      if (groupItem) {
        this.selectGroup(groupItem);
        delete this.copyFirstTab.groupItem;
      }

      this.form = {...this.copyFirstTab, groupId};
      this.status = (statusSupplier === 1);
    }
  },
}
</script>

<style scoped lang="scss">
.annotation {
  font-size: 15px;
  line-height: 15px;
  color: #9DCCFF;
}

.input-wrap {
  .open-drop-down {
    right: -7px !important;
    top: -4px !important;
  }
}

input {
  color: #7E7E7E !important;
}

.dialog-content .item-form .item-name, .dialog-content .item-form .item {
  .label-title.label-title-select {
    margin-bottom: 7px;
  }
}

.label-title.required:after {
  content: '*';
  font-size: 16px;
  vertical-align: top;
  color: rgba(49, 124, 206, .3);
}

.category-tree-wrapper {
  position: absolute;
  bottom: 1px;
  z-index: 2;
  background-color: white;
}

.lang-switch {
  display: inline-flex;
  justify-content: flex-start;
  float: right;

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

.checkbox-wrapper {
  .checkbox {
    display: block;
  }

  .checkbox-text {
    height: 16px;
  }
}

.values-list {
  margin-bottom: 0;

  .value-item {
    margin: 10px 10px 0 0;
  }
}

.select-category {
  .open-drop-down {
    position: absolute;
    right: 0;
  }

  .dropdown-trigger {
    border-bottom: 1px solid #9DCCFF;
  }
}

.item {
  &.checkbox-wrapper {
    input {
      padding: 0;
    }
  }
}

.item-name {
  textarea {
    height: 223px;
  }
}

.item-name span {
  color: #9DCCFF;
}

.label-title {
  font-weight: bold;
  font-size: 13px;
  line-height: 13px;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #317CCE;
}

.label-title.label-text-wrap {
  display: flex;
  justify-content: space-between;
  margin-bottom: 7px;

  .label-text {
    font-weight: 550;
    font-size: 12px;
    line-height: 12px;
    text-transform: none;
    color: #317CCE;

    &.copy {
      color: #FF9D2B;
    }
  }
}

.drop-down.drop-down-categories.supl {
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  border-radius: 0 0 10px 10px;
  max-width: 576px;
  padding: 10px 0;
}

.statusSupplier {
  .label-title {
    margin-bottom: 7px;
    display: block;
  }

  .text {
    font-size: 14px;
    line-height: 1.4;
    font-weight: normal;
    color: #7E7E7E;
    text-transform: none;
    margin-left: 10px;
  }
}

.currency {
  .label-title {
    margin-bottom: 0 !important;
  }
}

.dialog-content .item-form .row {
  align-items: flex-start;
}

.item-form input {
  line-height: 1.4;
}

.item.nested-category .input-wrap input {
  margin-right: 0;
}

.item.nested-category .open-drop-down {
  right: -4px;
}
</style>

<style lang="scss">
@import "../spa/src/sass/mixins";

.stockContent {
  .v-input__append-inner {
    position: relative;
    // right: -3px;
  }
}

  .supplierFirstForm {

    .v-menu__content.theme--light.v-menu__content--fixed.menuable__content__active {
      margin-top: 30px;
    }
  }

  .currencyDropDown {
    max-height: 200px !important;
    margin-top: 30px !important;
    box-shadow: 0 3px 7px rgba(0, 0, 0, 0.1);
    border-radius: 0 0 10px 10px !important;

    .v-list-item {
      font-size: 14px;
      color: #7E7E7E;

      &:hover {
        &:before {
          display: none;
        }

        background: #E3F0FF;
        .v-list-item__title {
          color: #317CCE !important;
        }
      }
    }

    @include custom-scroll-bar();
  }

.drop-down.drop-down-categories.supl {
  .v-treeview-node__root, .v-treeview-node__children {
    &:hover {
      background: #F4F9FF;
    }
  }
}


</style>
