<template>
  <simplebar class="item-form supplierSecondForm create-new-item">
    <v-row>
      <address-form
          :title="$t('page.suppliers.modal.createSupplier.fourthForm.addressDelivery')"
          :checkBoxText="$t('page.suppliers.modal.createSupplier.fourthForm.checkBoxText')"
          :types="{ type: 'suppliers', type_address: 'delivery', type_list: 'countries' }"
          @updateDoubleAddress="onDoubleAddress"
          @updateAddressForm="onPhysicalAddress"
          :doubleData="doubleData"
          :getListLength="getListLength"
          :physicalAddress="form.physicalAddress"
          :isDataSafe="isDataSafe"
          isPhysicalAddress
          isCheckBox
      >
        <v-row>
          <form-btn
              v-if="isDataSafe"
              :title="$t('page.suppliers.modal.createSupplier.add')"
              @updateValue="onAddPhysicalAddress"
              :disabled="!this.isExistValue"
              cols="4"
              offset="0"
          ></form-btn>
          <form-btn
              v-else
              :title="$t('page.suppliers.modal.createSupplier.safe')"
              cols="4"
              offset="0"
              @updateValue="onDataSafe"
          ></form-btn>
        </v-row>
        <v-row>
          <physical-address-list
              v-for="(item, idx) in deliveryAddress"
              :key="item.key"
              :item="item"
              :idx="idx"
              :id="item.id"
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
import {mapGetters} from "vuex";
// components
import AddressForm from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/AddressForm";
import FormBtn from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/FormBtn";
import PhysicalAddressList
  from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/SupplierFormSecond/components/PhysicalAddressList";
// helper
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "SupplierFormFourth",
  components: {
    'physical-address-list': PhysicalAddressList,
    'address-form': AddressForm,
    'form-btn': FormBtn
  },
  props: {
    currentStep: Number,
    item: Object,
    copyFourthTab: Object
  },
  computed: {
    ...mapGetters(['suppliersAddressList', 'getLists']),
    getCopyData() {
      return this.copyFourthTab
    },
    getListLength() {
      return this.deliveryAddress.length;
    }
  },
  data() {
    return {
      isExistValue: false,
      id: null,
      doubleData: null,
      deliveryAddress: [],
      isDataSafe: true,
      form: {
        mobile_phone: null,
        physicalAddress: null
      }
    }
  },
  methods: {
    onDoubleAddress() {
      this.doubleData = {...this.suppliersAddressList[0]};
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

        this.deliveryAddress.push({ ...this.form.physicalAddress, id });
      }

      this.onSafe();
    },
    getFilteredAddress(idx) {
      return this.deliveryAddress.filter((item, index) => index !== idx);
    },
    getEditData(id) {
      return this.deliveryAddress.find((item) => item.id === id);
    },
    removeItem(idx) {
      this.deliveryAddress = this.getFilteredAddress(idx);
    },
    onDataSafe() {
      this.isDataSafe = true;

      this.deliveryAddress = this.deliveryAddress.reduce((acc, item) => {
        if (item.id === this.id) {
          acc.push({ ...this.form.physicalAddress, id: this.id });
        } else {
          acc.push(item);
        }

        return acc;
      }, []);
    },
    onSafe() {
      const delivery_address = this.deliveryAddress.map((item) => {

        const { country, region, city, house: number_housing, street } = item;

        const country_id = country ? country.id : null
        const region_id = region ? region.id : null
        const city_id = city ? city.id : null

        return { city_id, country_id, number_housing, region_id, street }
      });

      const form = {
        delivery_address,
        is_delivery_equal_actual_address: this.isDataSafe
      }

      this.$emit('stepUpdated', form);
    },
    editItem(id) {
      this.id = id;
      this.form.physicalAddress = this.getEditData(id);
      this.isDataSafe = false;
    }
  },
  mounted() {
    if (this.copyFourthTab) {
      const { deliveryAddress, isDataSafe } = this.getCopyData;
      this.isDataSafe = isDataSafe;
      this.deliveryAddress = deliveryAddress
    }
  },
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

.item-form input {
  line-height: 1.4;
}

</style>
