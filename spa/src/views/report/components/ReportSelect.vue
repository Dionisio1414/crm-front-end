<template>
  <div :class="customClass">
    <span class="absenceType_title">{{title}}:</span>
    <v-select
        :style="`color: ${color}`"
        class="department_select"
        v-model="value"
        :color="color"
        :items="items"
        item-value="id"
        item-text="title"
        :menu-props="{contentClass: 'currencyDropDown'}"
        @change="onChange"
        :disabled="workTimeListPending"
        hide-selected
        light
        dense
        solo
        return-object
    >
      <template v-slot:item="{ item, attrs, on }">
        <v-list-item
            v-bind="attrs"
            v-on="on"
        >
          <span class="selectItem" :style="`color: ${item.color ? item.color : '#5893D4'}`">{{ item.title }}</span>
        </v-list-item>
      </template>
    </v-select>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
  name: "ReportSelect",
  props: {
    items: Array,
    title: String,
    customClass: String,
    rangeDays: Array,
    isDepartment: Boolean
  },
  computed: mapGetters(['workTimeListPending']),
  data: () => ({
    value: null,
    color: '#5893D4'
  }),
  methods: {
    ...mapActions(['actionWorkTimeList']),
    async onChange({color, id}) {
      if (this.isDepartment) {
        const values = {
          company_departments_id: id
        };

        if (this.rangeDays && this.rangeDays.length > 1) {
          values.date_from =  this.rangeDays[0];
          values.date_to =  this.rangeDays[1];
        }

        console.log(values,'values')

        //await this.actionWorkTimeList(values);
      }
      this.color = color;
      this.$emit('updateReportSelect', id)
    }
  },
  mounted() {
    this.value = this.items.length ? this.items[0] : []
  }
}
</script>

<style scoped lang="scss">
.department, .absenceType {
  display: flex;
  align-items: center;

  &_title {
    display: inline-block;
    margin-right: 15px;
    font-size: 13px;
    line-height: 13px;
    color: #5893D4;
  }

  &_select {
    max-width: 153px;
  }

  &.v-text-field.v-text-field--solo.v-input--dense > .v-input__control {
    min-height: 32px;
  }
}
</style>

<style lang="scss">
.department, .absenceType {
  .v-text-field.v-text-field--solo.v-input--dense > .v-input__control {
    min-height: 32px;
  }

  .theme--light.v-select {
    .v-select__selection {
      font-weight: 600;
      font-size: 12px;
      line-height: 16px;
      text-transform: uppercase;
      color: #5893D4;
    }

    .v-select__selection {
      color: inherit;
    }
  }

  .v-text-field.v-text-field--solo:not(.v-text-field--solo-flat) > .v-input__control > .v-input__slot {
    border: 1px solid #9DCCFF;
    box-sizing: border-box;
    border-radius: 10px;
    box-shadow: none;
  }

  .v-select--is-menu-active {
    .v-input__slot {
      border: none !important;
      border-bottom: 1px solid #E3F0FF !important;
      border-radius: 10px 10px 0 0 !important;
    }
  }
}
</style>