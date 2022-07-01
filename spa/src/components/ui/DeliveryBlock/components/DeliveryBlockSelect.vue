<template>
  <v-col cols="6">
    <div class="select-wrap">
      <div class="label-title">{{ title }}</div>
      <v-select
          class="select-switcher"
          :placeholder="placeholder"
          :items="items"
          :menu-props="{ contentClass: 'defaultDropDown resp'}"
          item-text="title"
          :item-value="itemValue"
          @change="onChange"
          :value="value"
          :disabled="isCapitalizedDocument"
          solo
          dense
      >
      </v-select>
    </div>
  </v-col>
</template>

<script>
export default {
  name: "DeliveryBlockSelect",
  props: {
    items: Array,
    title: String,
    placeholder: String,
    itemValue: String,
    mark: String,
    defaultValue: [String, Number],
    isCapitalizedDocument: Boolean
  },
  data: () => ({
    value: ''
  }),
  methods: {
    onChange(id) {
      const value = {
        id,
        mark: this.mark
      }

      this.$emit('updateValue', value)
    }
  },
  mounted() {
    this.value = this.defaultValue;
  }
}
</script>

<style scoped>

</style>