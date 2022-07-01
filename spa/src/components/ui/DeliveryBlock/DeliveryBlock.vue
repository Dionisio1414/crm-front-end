<template>
  <v-col cols="6" :class="customClass">
    <v-row class="dark-row">
      <delivery-block-select
          :title="$t('deliveryBlock.deliveryMethod')"
          :placeholder="$t('deliveryBlock.selectDeliveryMethod')"
          :mark="getDelivery.DELIVERY_METHODS"
          :items="delivery_methods"
          :defaultValue="1"
          itemValue="directory_id"
          @updateValue="onDeliveryValue"
      ></delivery-block-select>
      <delivery-block-select
          :title="$t('deliveryBlock.deliveryType')"
          :placeholder="$t('deliveryBlock.selectDeliveryType')"
          :mark="getDelivery.TYPE_OF_DELIVERY"
          :items="type_deliveries"
          :defaultValue="1"
          itemValue="directory_id"
          @updateValue="onDeliveryValue"
      ></delivery-block-select>
      <delivery-block-select
          :title="$t('deliveryBlock.city')"
          :placeholder="$t('deliveryBlock.selectCity')"
          :mark="getDelivery.CITIES"
          :defaultValue="delivery.department_city_id"
          :items="cities"
          itemValue="id"
          @updateValue="onDeliveryValue"
      ></delivery-block-select>
      <delivery-block-select
          :title="$t('deliveryBlock.department')"
          :placeholder="$t('deliveryBlock.selectDepartment')"
          :mark="getDelivery.DEPARTMENTS"
          :defaultValue="delivery.department_id"
          :items="departments"
          itemValue="id"
          @updateValue="onDeliveryValue"
      ></delivery-block-select>
      <v-col cols="6" align-self="center">
        <div class="item-name">
          <div class="label-title tthTitle">{{$t('deliveryBlock.ttn')}}</div>
          <input
              v-model="delivery.ttn_number"
              @change="onTtn"
              type="number"
              :placeholder="$t('deliveryBlock.enterTtn')"
          >
        </div>
      </v-col>
      <v-col cols="6">
        <div class="label-title">{{$t('deliveryBlock.dateTtn')}}</div>
        <calendar
            placeholder="Введите дату"
            @updateDate="onChangeDeliveryDate"
            :date-value="delivery.ttn_date"
            custom-class="default-calendar"
            date-key="supplier_document_date"
            icon-color="#9DCCFF"
            isCloseOnContentClick
            is-icon
        />
      </v-col>
      <div class="ttn-status">{{$t('deliveryBlock.statusTtn')}}</div>
    </v-row>
  </v-col>
</template>

<script>
// components
import Calendar from "@/components/ui/Calendar/Calendar";
import DeliveryBlockSelect from "./components/DeliveryBlockSelect";
// constants
import {DELIVERY} from '@/components/ui/DeliveryBlock/constants';

export default {
  name: "DeliveryBlock",
  components: {
    Calendar,
    DeliveryBlockSelect
  },
  props: {
    cities: Array,
    delivery_methods: Array,
    type_deliveries: Array,
    departments: Array,
    editDelivery: Object,
    isCapitalizedDocument: Boolean,
    customClass: String
  },
  computed: {
    getDelivery() {
      return DELIVERY;
    }
  },
  data: () => ({
    delivery: {}
  }),
  methods: {
    onChangeDeliveryDate(params) {
      this.delivery.ttn_date = params.date;
      this.$emit('updateDeliveryValues', this.delivery)
    },
    onTtn(event) {
      this.delivery.ttn_number = event.target.value;
      this.$emit('updateDeliveryValues', this.delivery)
    },
    onDeliveryValue({id, mark}) {
      if (mark === DELIVERY.DELIVERY_METHODS) {
        this.delivery.delivery_methods_id = id;
      }

      if (mark === DELIVERY.TYPE_OF_DELIVERY) {
        this.delivery.type_deliveries_id = id;
      }

      if (mark === DELIVERY.CITIES) {
        this.delivery.department_city_id = id;
      }

      if (mark === DELIVERY.DEPARTMENTS) {
        this.delivery.department_id = id;
      }

      this.$emit('updateDeliveryValues', this.delivery)
    }
  },
  beforeMount() {
    const { delivery_methods_id = 1, type_deliveries_id = 1, department_city_id, department_id, ttn_number, ttn_date } = this.editDelivery || {};

    this.delivery = { delivery_methods_id, type_deliveries_id, department_city_id, department_id, ttn_number, ttn_date }
  }
}
</script>

<style scoped lang="scss">
@import "deliveryBlock";
</style>

<style lang="scss">
.dark-row {
  .theme--light.v-text-field--solo > .v-input__control > .v-input__slot {
    background: transparent;
  }

  .v-text-field.v-text-field--solo.v-input--dense > .v-input__control {
    min-height: 0;
  }
}
</style>