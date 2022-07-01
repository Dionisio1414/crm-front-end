<template>
  <simplebar class="wrapper">
    <div class="item-name">
      <view-property v-if="!includeBrand"
                     @change-value="onChangeProperty"
                     :property_value="formatBrand"/>
      <view-property v-if="!includeModel"
                     @change-value="onChangeProperty"
                     :property_value="formatModel"/>
      <view-property @change-value="onChangeProperty" v-for="property in selectedProperties"
                     :property_value="property"
                     :key="property.id"/>
      <view-property @change-value="onChangeProperty"
                     :property_value="formatCountry"/>
      <view-property @change-value="onChangeProperty"
                     :property_value="formatWeight"/>
      <view-property @change-value="onChangeProperty"
                     :property_value="formatVolume"/>
    </div>
  </simplebar>
</template>

<script>
import ViewProperty from "../../froms/ViewProperty";
import {mapGetters} from 'vuex'

export default {
  name: "Characteristics",
  components: {ViewProperty},
  props: {
    form: Object,
    countries: Array,
    units: Array,
  },
  methods: {
    onChangeProperty(params) {
      console.log(params);
    },
  },
  computed: {
    ...mapGetters({
      properties: 'properties',
      brand: "brand",
      model: "model"
    }),

    selectedProperties() {
      if (this.form.property_value && this.properties) {
        return this.form.property_value.map(property => {
          return {
            ...property,
            property: this.properties.find(propertyVal => propertyVal.id === property.property_id)
          }
        })
      }
      return []
    },
    formatBrand() {
      const brandValue = {title: ''}
      return {
        ...brandValue,
        property: this.brand,
      }

    },
    formatModel() {
      const modelValue = {title: ''}
      return {

        ...modelValue,
        property: this.model,
      }
    },

    includeModel() {
      // console.log(this.selectedProperties, !!this.selectedProperties.find(property => property.property_id === 2) );
      return !!this.selectedProperties.find(property => property.property_id === 2)
    },
    includeBrand() {
      // console.log(this.selectedProperties, !!this.selectedProperties.find(property => property.property_id === 1));
      return !!this.selectedProperties.find(property => property.property_id === 1)
    },

    formatWeight() {
      const unit = this.units.find(unit => unit.id === this.form.weight_id)
      const weight = {
        title: this.form.weight ? `${this.form.weight} ${unit.title}` : ''
      }
      return {
        editable: false,
        ...weight,
        property: {title: this.$t('nomenclature.weight')},
      }
    },
    formatVolume() {
      const volume = {
        title: this.form.volume ? this.form.volume   : ''
      }
      return {
        ...volume,
        editable: false,
        property: {title: this.$t('nomenclature.volume')},
      }

    },
    formatCountry() {
      const country = this.countries.find(country => country.id === this.form.country_id)
      return {
        editable: false,
        ...country,
        property: {title: this.$t('nomenclature.supplier_country')},
      }
    }
  }
}
</script>

<style scoped lang="scss">
.wrapper {
  padding: 0 30px;
}

</style>