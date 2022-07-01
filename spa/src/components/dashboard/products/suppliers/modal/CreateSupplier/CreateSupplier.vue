<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialogSupplier"
        :max-width="getTitleMove ? '920px':'1230px'"
        content-class="stockContent"
        class="dialog-large dialog-category"
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <span> {{ $t(`page.suppliers.modal.createSupplier.mainTitle.${getTitle}`) }} </span>
          </div>
          <header-actions
              @updateClose="onHandlerModal(false, null, 1)"
              @onCloseModal="onHandlerModal(false, null, 1)"
              id="createSuppliers"
              :title="$t(`page.suppliers.modal.createSupplier.mainTitle.${getTitle}`)"
              :is-hide-attach="!isAddTitle"
              is-hide-dots
          ></header-actions>
        </div>
        <div v-if="dialogSupplier && !getTitleMove" class="dialog-content dialog-content-large">
          <div v-if="selectedOption.value === 'good'" class="form-wrapper">
            <div class="step-tabs">
              <button
                  v-for="(step, index) in formSteps" :key="step.title"
                  :class="{'active': index === currentStep}"
                  class="step"
                  @click="goToStep(index)">
                  {{ step.title }}
              </button>
            </div>
            <transition name="fade" mode="out-in">
              <keep-alive>
                <component
                    :is="currentComponent"
                    @stepUpdated="mergeStepData"
                    :suppliersGroups="suppliersGroups"
                    :categories="categories"
                    :copyFirstTab="copyFirstTab"
                    :copySecondTab="copySecondTab"
                    :copyThirdTab="copyThirdTab"
                    :copyFourthTab="copyFourthTab"
                    :category="category"
                    @updateValid="onValid"
                    ref="currentComponent"
                />
              </keep-alive>
            </transition>
          </div>
        </div>
        <div v-else class="dialog-content dialog-content-large supplierNested">
          <nested-groups
              v-if="suppliersGroups.length"
              :groupsMoveTitles="groupsMoveTitles"
              cols="10"
              :flatList="flatList"
              :suppliersGroups="suppliersGroups"
              :getSupplierTitle="getSupplierTitle"
              @updateGroupId="onGroupId"
              isSuppliers
              getMove
              isMove
          ></nested-groups>
        </div>
        <div class="dialog-content dialog-content-large">
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel smallText"
                :title="$t(`page.suppliers.modal.createSupplier.${!currentStep ? getCorrectText ? 'close' : 'cancel': 'back'}`)"
                color="#5893D4"
                @updateEvent="onHandlerModal(false, null)"
                text
            ></custom-btn>
            <custom-btn
                v-if="!canCreate && !getTitleMove"
                custom-class="save smallText"
                :title="$t('page.suppliers.modal.createSupplier.next')"
                color="#5893D4"
                @updateEvent="goToNextStep"
            ></custom-btn>
            <custom-btn
                v-if="canCreate || getTitleMove"
                custom-class="save smallText"
                :title="getTitleMove? $t('page.suppliers.modal.createSupplier.mainTitle.move') :  $t('page.suppliers.modal.createSupplier.safe')"
                :is-disabled="pending"
                color="#5893D4"
                @updateEvent="addSupplier"
            ></custom-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import { mapActions, mapGetters } from "vuex";
// components
import ServiceForm from "@/components/dashboard/products/nomenclature/modals/froms/ServiceForm";
import NestedGroups from "@/components/dashboard/products/suppliers/NestedGroups/NestedGroups"
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// components of form
import SupplierFormFirst from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/SupplierFormFirst/SupplierFormFirst";
import SupplierFormSecond from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/SupplierFormSecond/SupplierFormSecond";
import SupplierFormThird from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/SupplierFormThird/SupplierFormThird";
import SupplierFormFourth from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/SupplierFormFourth/SupplierFormFourth";
// helper
import getFlatMap from "@/helper/getFlatMap";
import formatDate from "@/helper/formatDate";
// constants
import {MODE_TYPES} from "@/constants/constants";

export default {
  name: "CreateSupplier",
  components: {
    ServiceForm,
    SupplierFormFirst,
    SupplierFormSecond,
    SupplierFormThird,
    'nested-groups': NestedGroups,
    SupplierFormFourth,
    'header-actions': HeaderActions,
    'custom-btn': CustomBtn
  },
  props: {
    categories: Array,
    suppliersGroups: {
      type: Array,
      default() {
        return [];
      }
    },
  },
  computed: {
    ...mapGetters(['category', 'flatList', 'requestError', 'pending', 'suppliersDataTable']),
    getCorrectText() {
      return this.mainTitle === MODE_TYPES.EDIT ||
            this.mainTitle === MODE_TYPES.MOVE ||
            this.mainTitle === MODE_TYPES.REMOVE
    },
    currentComponent() {
      return this.formSteps[this.currentStep].component;
    },
    getTitle() {
      if (this.mainTitle === MODE_TYPES.COPY ||
          this.mainTitle === MODE_TYPES.EDIT ||
          this.mainTitle === MODE_TYPES.REMOVE ||
          this.mainTitle === MODE_TYPES.MOVE) {
        return this.mainTitle;
      }

      return MODE_TYPES.ADD
    },
    getTitleMove() {
      return this.mainTitle === MODE_TYPES.MOVE
    },
    isAddTitle() {
      return this.getTitle === MODE_TYPES.ADD
    },
    getSupplierTitle() {
      return this.groupsMoveTitles.length > 1 ? `(${this.groupsMoveTitles.length})` : this.groupsMoveTitles[0]
    }
  },
  data() {
    return {
      canCreate:false,
      selectedOption: { title: 'товар', value: 'good'},
      dialogSupplier: false,
      currentStep: 0,
      isValid: true,
      copyFirstTab: null,
      copySecondTab: null,
      copyThirdTab: null,
      copyFourthTab: null,
      mainTitle: null,
      listId: null,
      childGroupsTitle: '',
      showDropDown: false,
      parentItem: null,
      moveGroups: [],
      groupsMoveTitles: [],
      form: {},
      formSteps: [
        {component: 'SupplierFormFirst', title: this.$t('page.suppliers.modal.createSupplier.tabInfo')},
        {component: 'SupplierFormSecond', title: this.$t('page.suppliers.modal.createSupplier.tabContactData')},
        {component: 'SupplierFormThird', title: this.$t('page.suppliers.modal.createSupplier.tabAddContactFace')},
        {component: 'SupplierFormFourth', title: this.$t('page.suppliers.modal.createSupplier.tabDelivery')}
      ],
    }
  },
  methods: {
    ...mapActions(['onCreateSupplier', 'onEditSupplier', 'onMoveSupplierList', 'onResetValid']),
    onGroupId(item) {
      this.parentItem = item;
    },
    getTransformGroups(array) {
      const { id } = this.parentItem;
      return array.map((item) => {
        return {
          "group_id": id,
          "id": item
        }
      })
    },
    onHandlerModal(value, data, point) {
      if (!this.currentStep || point) {
        this.dialogSupplier = value;
        const { val, title } = data || {};
        this.mainTitle = title;
        this.onResetValid();

        if (Array.isArray(val)) {
          this.moveGroups = val;

          this.groupsMoveTitles = this.suppliersDataTable.body.reduce((acc, item) => {
            val.forEach((id) => {
              if (id === item.id) {
                acc.push(item.title_company);
              }
            })

            return acc;
          }, []);
        }

        if (val && !Array.isArray(val)) {
          this.listId = val.id;
          this.copyFirstTab = this.getFirstCopy(val);
          this.copySecondTab = this.getSecondCopy(val);
          this.copyThirdTab = this.getThirdCopy(val);
          this.copyFourthTab = this.getFourthCopy(val);
        } else {
          this.copyFirstTab = this.copySecondTab = this.copyThirdTab = this.copyFourthTab = null;
          this.goToStep(0);
        }
      } else {
        this.goToStep(this.currentStep -1)
      }
    },
    getFirstCopy(data) {
      const {
        date_of_birth,
        is_foreign_company: foreignCompany,
        group_id,
        partner_inn: idCode,
        partner_edrpou: lawCode,
        title_company,
        title_supplier_first_name,
        title_supplier_middle_name: nameOfSupplierSecond,
        title_supplier_last_name: nameOfSupplierThird,
        sex_id: sexId,
        currency_id: currencyValue,
        manager_id: managerValue,
        partner_type_id: statusSupplier,
      } = data;

      const birthdayDay = formatDate(date_of_birth);
      const groupItem = group_id ? this.getGroupItem(group_id): {};
      const nameOfSupplier = this.mainTitle === MODE_TYPES.COPY ? `${title_supplier_first_name} (копия)`: title_supplier_first_name;
      const nameOfCompany = this.mainTitle === MODE_TYPES.COPY ? `${title_company} (копия)`: title_company;

      return  {
        birthdayDay,
        foreignCompany,
        groupItem,
        idCode: idCode ? idCode : null,
        lawCode,
        nameOfCompany,
        nameOfSupplier,
        nameOfSupplierSecond,
        nameOfSupplierThird,
        sexId,
        currencyValue,
        managerValue,
        statusSupplier,
        isCopy: (this.mainTitle === MODE_TYPES.COPY)
      }
    },
    getSecondCopy(data) {
      const {
        legal_address_street: street,
        legal_address_number_housing: house,
        phone,
        email,
        legal_address_country_id: countryId,
        legal_address_country_title: countryTitle,
        legal_address_region_id: regionId,
        legal_address_region_title: regionTitle,
        legal_city_id: cityId,
        legal_city_title: cityTitle,
        is_delivery_equal_actual_address: isDataSafe,
        actual_address
      } = data;

      const actualAddress = actual_address.map((item) => {
        const { id } = item;
        const { city_id, city_title, country_id, country_title, number_housing: house, region_id, region_title, street } = item['individual_address'] || item;
        const country = { id: country_id, title: country_title }
        const region = { id: region_id, title: region_title }
        const city = { id: city_id, title: city_title }

        return { city, country,house, region, street, id };
      })

      const country = { id: countryId, title: countryTitle }
      const region = { id: regionId, title: regionTitle }
      const cities = { id: cityId, title: cityTitle }

      return  { street, house, country, region, cities, phone, email, isDataSafe, actualAddress }
    },
    getThirdCopy(data) {
      const { contacts } = data;

      return contacts.map((item) => {
        const {
          date_of_birth,
          document_id,
          email,
          first_name: name,
          last_name: patronymic,
          middle_name: surname,
          number_document: number,
          phone: mobile_phone,
          position_id,
          sex_id,
          source_attractions_id
        } = item;

        const birthdayDay = formatDate(date_of_birth);

        return {
          surname,
          name,
          patronymic,
          mobile_phone,
          email,
          number,
          birthdayDay,
          position_id,
          sex_id,
          source_attractions_id,
          document_id
        }
      })
    },
    getFourthCopy(data) {
      const {
        is_delivery_equal_actual_address: isDataSafe,
        delivery_address
      } = data;

      const deliveryAddress = delivery_address.map((item) => {
        const { id } = item;
        const { city_id, city_title, country_id, country_title, number_housing: house, region_id, region_title, street } = item['delivery_address'] || item;
        const country = { id: country_id, title: country_title }
        const region = { id: region_id, title: region_title }
        const city = { id: city_id, title: city_title }

        return { country, region, city, house, street, id };
      })

      return  { isDataSafe, deliveryAddress }
    },
    getGroupItem(id) {
      const flatGroups = getFlatMap(this.suppliersGroups);
      return flatGroups.find(item => item.id === id)
    },
    onValid(valid) {
      this.isValid = valid;
    },
   async addSupplier() {
     const { params } = this.$route.params;
      if (this.isValid) {
        this.goToStep(0)

      } else {
        if (this.getTitle === MODE_TYPES.COPY) {
          await this.onCreateSupplier({...this.form, copy: true});
        }

        if (this.getTitle === MODE_TYPES.ADD) {
          await this.onCreateSupplier({...this.form, add: true});
          this.$emit('createSupplier')
        }

        if (this.getTitle === MODE_TYPES.EDIT) {
          await this.onEditSupplier({...this.form, id: this.listId})
        }

        if (this.requestError) {
          this.goToStep(0)
        } else {
          this.onHandlerModal(false, null, 1)
        }
      }

      if (this.getTitle === MODE_TYPES.MOVE) {
        const data = this.getTransformGroups(this.moveGroups);
        const values = {
          data,
          title: this.getSupplierTitle,
          groupTitle: this.parentItem.title
        }

        await this.onMoveSupplierList(values)
      }

     if (params) {
       this.$router.go(-1);
     }

     this.$emit('updateSuppliersList')
    },
    goToNextStep() {
      this.currentStep++;

      this.canCreate = (this.currentStep === this.formSteps.length -1);
    },
    mergeStepData(step) {
      this.form = {...this.form, ...step}
    },
    goToStep(index) {
      this.currentStep = index

      this.canCreate = this.currentStep === this.formSteps.length - 1;
    }
  }
}
</script>

<style scoped lang="scss">
.popup-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}

.stockContent {
  overflow-y: inherit !important;
}

  .step-tabs {
    display: flex;
    align-items: center;
    margin: 30px 20px;
  }

.step {
  font-weight: 550;
  font-size: 14px;
  line-height: 14px;
  width: 100%;
  max-width: 220px;
  box-sizing: border-box;
  color: #9DCCFF;
  height: 36px;
  border: 1px solid #9DCCFF;
  border-radius: 25px;
  margin: 0 10px;

  &.active {
    background: #5893D4;
    border: none;
    color: #fff;
  }
}

.form-wrapper {
  width: 100%;
}

.products-select {
  width: 131px;
  border: 1px solid #9DCCFF;
  box-sizing: border-box;
  border-radius: 25px;
  padding: 14px 15px 11px;
  margin-left: 7px;
  position: relative;
  display: inline-flex;
  justify-content: space-between;
  align-items: center;

  &-option {
    position: absolute;
    background: #FFFFFF;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    font-weight: 550;
    font-size: 15px;
    line-height: 15px;
    color: #7E7E7E;
    list-style: none;
    left: 0;
    right: 0;
    top: 100%;
    z-index: 2;
    padding: 10px 0;
    li {
      padding: 11px 15px 4px;
      margin-bottom: 9px;
      &.active{
        background: #F4F9FF;
      }

    }
  }
}
</style>

<style lang="scss">
  .supplierNested {
    padding-top: 30px;
    min-height: 350px;
    justify-content: flex-start;

    .drop-down.drop-down-categories {
      max-width: inherit !important;
    }
}

  input {
    outline: none !important;
  }

.supplierFirstForm, .supplierSecondForm, .supplierThirdForm, .supplierFourthForm {
  .theme--light.v-text-field > .v-input__control > .v-input__slot:before {
    border-color: #9DCCFF;
  }

  .theme--light.v-text-field:not(.v-input--has-state):hover > .v-input__control > .v-input__slot:before {
    border-color: #9DCCFF;
  }

  .error-on-select {
    border-color: #FF9D2B !important;

    & .v-input__slot:before {
      border-color: #FF9D2B !important;
    }
  }

  .v-text-field.v-input--is-focused > .v-input__control > .v-input__slot:after {
    transform: scaleX(0);
  }

  .v-select__slot input {
    // border: none !important;
  }

  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .error-text {
    font-weight: 550;
    font-size: 12px;
    line-height: 12px;
    text-align: right;
    color: #FF9F2F;
    text-transform: lowercase;
  }
}

.supplierFourthForm, .supplierSecondForm {
  .row {
    margin-bottom: 0;
  }

  .foreignCompany {
    .text {
      font-size: 15px;
      line-height: 15px;
      color: #7E7E7E;
      margin: 5px 0 0 15px;
    }
  }

  .physicalAddressDescription {
    color: rgba(126, 126, 126, .5);
    font-size: 13px;
    line-height: 130%;
  }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .2s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
