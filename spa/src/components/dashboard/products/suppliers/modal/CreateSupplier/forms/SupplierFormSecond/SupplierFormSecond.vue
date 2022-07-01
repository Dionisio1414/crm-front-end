<template>
  <simplebar class="item-form supplierSecondForm create-new-item">
    <v-row>
      <address-form
          @updateDoubleAddress="onDoubleAddress"
          @updateAddressForm="onLegalAddress"
          :types="{ type: 'suppliers', type_address: 'physical', type_list: 'countries' }"
          :getCopyData="getCopyData"
          :title="$t('page.suppliers.modal.createSupplier.secondForm.checkBoxTitle')"
          :checkBoxText="$t('page.suppliers.modal.createSupplier.secondForm.checkBoxPlaceholder')"
          isCheckBox
      >
        <v-row>
          <v-col cols="6">
            <div class="item-name item-name_phone">
              <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.secondForm.phoneText') }}</div>
              <vue-phone
                v-bind="phoneSettings"
                :valueField="getPhone"
                @updateInput="inputHandler"
              >
              </vue-phone>
            </div>
          </v-col>
          <v-col cols="4" align-self="center" v-if="isValidPhone">
            <span class="error-text">
              {{ $t('page.suppliers.modal.createSupplier.secondForm.error.isExist') }}
            </span>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="6">
            <div class="item-name item-name_actions">
              <div class="label-title">
                {{ $t('page.suppliers.modal.createSupplier.secondForm.email') }}
              </div>
              <input
                  type="text"
                  :class="{'error-on-select':  $v.form.email.$error }"
                  :placeholder="$t('page.suppliers.modal.createSupplier.secondForm.emailPlaceholder')"
                  @input="setName($event.target.value)"
                  v-model.trim="form.email"
              >
            </div>
          </v-col>
        </v-row>
      </address-form>
      <address-form
          @updateAddressForm="onPhysicalAddress"
          :types="{ type: 'suppliers', type_address: 'legal', type_list: 'countries' }"
          :doubleData="doubleData"
          :title="$t('page.suppliers.modal.createSupplier.secondForm.physicalAddress')"
          :getListLength="getListLength"
          :physicalAddress="form.physicalAddress"
          :isDataSafe="isDataSafe"
          isPhysicalAddress
      >
        <v-row>
          <v-col cols="8" class="PhysicalText">
            {{ $t('page.suppliers.modal.createSupplier.secondForm.notice') }}
          </v-col>
          <form-btn
              v-if="isDataSafe"
              @updateValue="onAddPhysicalAddress"
              :disabled="!this.isExistValue"
              cols="4"
              offset="0"
              :title="$t('page.suppliers.modal.createSupplier.add')"
          ></form-btn>
          <form-btn
              v-else
              cols="4"
              offset="0"
              @updateValue="onDataSafe"
              :title="$t('page.suppliers.modal.createSupplier.safe')"
          ></form-btn>
        </v-row>
        <v-row justify="start">
          <physical-address-list
              v-for="(item, idx) in physicalAddressList"
              :key="item.key"
              :idx="idx"
              :id="item.id"
              :activeId="id"
              :countryValue="item.country && item.country.title"
              :regionValue="item.region && item.region.title"
              :cityValue="item.city && item.city.title"
              :street="item.street"
              :house="item.house"
              :isDataSafe="isDataSafe"
              @updateRemoveItem="removeItem"
              @updateEdit="editItem"
          ></physical-address-list>
        </v-row>
      </address-form>
    </v-row>
  </simplebar>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import AddressForm from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/AddressForm";
import FormBtn from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/FormBtn";
import PhysicalAddressList
  from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/SupplierFormSecond/components/PhysicalAddressList";
// helper
import getUniqueId from "@/helper/getUniqueId";
// import getAddressItem from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/helper/getAddressItem";
// validate
import {email} from 'vuelidate/lib/validators';
// Vue Phone
import VuePhone from '@/components/ui/VuePhone/VuePhone';

export default {
  name: "SupplierFormSecond",
  components: {
    // MaskedInput,
    'physical-address-list': PhysicalAddressList,
    'address-form': AddressForm,
    'form-btn': FormBtn,
    'vue-phone': VuePhone
  },
  props: {
    copySecondTab: Object
  },
  computed: {
    ...mapGetters(['getLists', 'isValidFields']),
    isValidPhone() {
      return this.isValidFields === 'phone'
    },
    getListLength() {
      return this.physicalAddressList.length;
    },
    getPhone() {
      return this.getCopyData?.phone ?? ''
    },
    getCopyData() {
      if (!this.copySecondTab) return null;

      const { cities, country, region, street, house, email, phone, isDataSafe, actualAddress } = this.copySecondTab;

      // const transformActualAddress = actualAddress.map((item) => item)

      return { cities, country, region, street, house, email, phone, isDataSafe, actualAddress }
    }
  },
  mounted() {
    if (this.copySecondTab) {
      const { actualAddress } = this.getCopyData;
      this.form = this.getCopyData;
      this.physicalAddressList = actualAddress;
    }
  },
  deactivated() {
    const { email, mobile_phone } = this.form;

    const {
        house: legal_address_number_housing,
        street: legal_address_street,
        country: { id: legal_address_country_id },
        region: { id: legal_address_region_id },
        city: { id: legal_city_id }
    } = this.legalAddress;

    const actual_address = this.physicalAddressList.map((item) => {
      const { country, region, city, house: number_housing, street } = item;

      const country_id = country ? country.id : null
      const region_id = region ? region.id : null
      const city_id = city ? city.id : null

      return { city_id, country_id, number_housing, region_id, street }
    });

    const form = {
      actual_address,
      is_legal_equal_actual_address: this.isDataSafe,
      legal_address_street,
      legal_address_number_housing,
      legal_address_country_id,
      legal_address_region_id,
      legal_city_id,
      email,
      phone: mobile_phone,
    }

    this.$emit('stepUpdated', form);
  },
  data() {
    return {
      isExistValue: false,
      id: null,
      doubleData: null,
      physicalAddressList: [],
      legalAddress: {
        house: '',
        street: '',
        country: {},
        region: {},
        city: {}
      },
      isDataSafe: true,
      form: {
        email: '',
        number_code: '',
        mobile_phone: null,
        physicalAddress: null
      },
      phoneSettings: {
        defaultCountry: 'UA',
        preferredCountries: ['UA', 'RU'],
        customPhoneClass: ['phone-default'],
        isFlags: true
      }
    }
  },
  validations: {
    form:{
      email:{
        email
      }
    }
  },
  methods: {
    ...mapActions(['onActionSuppliersList']),
    /* eslint-disable-next-line */
    inputHandler(val, flag) {
      this.form.mobile_phone = val
    },
    setName(value) {
      this.form.email = value
      this.$v.form.email.$touch()
    },
    onValidPhone() {
      // console.log(123123)
/*      if (this.form.mobile_phone) {
        const body = {
          validate: {
            "phone": this.form.mobile_phone
          }
        }

        this.onValid(body)
      }*/
    },
    onDoubleAddress(obj) {
      this.doubleData = {...obj};
    },
    onLegalAddress(obj) {
      this.legalAddress = {...obj};
    },
    onPhysicalAddress(obj) {
      this.form.physicalAddress = {...obj};
      this.getExistValue();
    },
    getExistValue() {
      this.isExistValue = Object.values(this.form.physicalAddress).some((item) => !!item);
    },
    onAddPhysicalAddress() {
      if (this.isExistValue) {
        const id = getUniqueId();

        this.physicalAddressList.push({ ...this.form.physicalAddress, id });

        this.onActionSuppliersList(this.physicalAddressList);
      }
    },
    changeCodes(value) {
      this.form.number_code = value
    },
    getFilteredAddress(idx) {
      return this.physicalAddressList.filter((item, index) => index !== idx);
    },
    getEditData(id) {
      return this.physicalAddressList.find((item) => item.id === id);
    },
    removeItem(idx) {
      this.physicalAddressList = this.getFilteredAddress(idx);

      this.onActionSuppliersList(this.physicalAddressList);
    },
    getSwitchListData() {
      return this.physicalAddressList.reduce((acc, item) => {
        if (item.id === this.id) {
          acc.push({ ...this.form.physicalAddress, id: this.id });
        } else {
          acc.push(item);
        }

        return acc;
      }, []);
    },
    onDataSafe() {
      this.isDataSafe = true;
      this.physicalAddressList = this.getSwitchListData();
    },
    editItem(id) {
      this.id = id;
      this.form.physicalAddress = this.getEditData(id);
      this.isDataSafe = false;
    }
  }
}
</script>

<style lang="scss" scoped>
  .PhysicalText {
    font-size: 13px;
    line-height: 130%;
    color: #7E7E7E;
    opacity: 0.5;
  }

  .topBlock {
    margin-bottom: 20px;
  }
  </style>
  <style lang="scss">
  .supplierSecondForm {
    .row {
      margin-bottom: 20px;
      width: 100%;
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

  .v-autocomplete__content.v-menu__content.theme--light.v-menu__content--fixed.menuable__content__active {
    margin-top: 0;
  }

  .item-form {
    input {
      line-height: 1.4;
    }
  }
  .item-name {
    &.item-name_actions {
      input {
        flex: 0 1 100%;
      }
    }
  }

</style>
