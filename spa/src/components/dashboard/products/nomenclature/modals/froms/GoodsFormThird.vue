<template>
  <form class="item-form create-new-item">
    <v-row>
      <v-col cols="3" v-if="brand">
        <div class="item-name">
          <!--                    <div class="label-title">{{$t('nomenclature.brand')}}</div>-->
          <property-select
              placeholder="Выберите торговую марку"
              @change-property="onChangeProperty"
              @add-value="addPropertyValue"
              :property="brand"
              :value="getPropertyValue(brand.id)"
          />
        </div>
      </v-col>
      <v-col cols="3" v-if="model">
        <div class="item-name">
          <!--                    <div class="label-title">{{$t('nomenclature.model')}}</div>-->
          <property-select
              placeholder="Выберите модель"
              @change-property="onChangeProperty"
              @add-value="addPropertyValue"
              :property="model"
              :value="getPropertyValue(model.id)"
          />
        </div>
      </v-col>
      <v-col cols="3">
        <div class="select-wrap">
          <div class="label-title">{{ $t('nomenclature.supplier_country') }}</div>
          <v-select
              placeholder="Выберите страну из списка"
              class="select-switcher"
              @change="emitFormData"
              :items="countries"
              item-text="title"
              item-value="id"
              v-model="form.country_id"
              solo
              dense
              menu-props="countries"
          >
          </v-select>
        </div>
      </v-col>
      <v-col cols="3">
        <v-row>
          <v-col cols="6">
            <div class="item-name double-input">
              <div class="label-title">{{ $t('nomenclature.weight') }}</div>
              <input @change="emitFormData" :max="999" v-model="form.weight" type="number">
              <div class="select-wrap">
                <v-select
                    class="select-switcher"
                    item-text="title"
                    item-value="id"
                    :items="units"
                    solo
                    @change="emitFormData"
                    dense
                    v-model="form.weight_id"
                >
                </v-select>
              </div>
            </div>
          </v-col>
          <v-col cols="6">
            <div class="item-name double-input volume">
              <div class="label-title">{{ $t('nomenclature.volume') }}</div>
              <input
                  @change="emitFormData"
                  :max="999"
                  v-model="form.volume"
                  type="number"
              >
            </div>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <!--<div
        class="add-property-value"
        v-if="category  && properties.length !== 0"
        @click="openPropertyModal"
    >
      <svg-sprite width="12" height="12" icon-id="plusBlue"></svg-sprite>
      Добавить свойство
    </div>-->
    <v-row v-if="properties">
      <v-col v-for="property in properties" :key="property.id" cols="6">
        <property-select
            placeholder="Выберите значение характеристики из списка"
            @change-property="onChangeProperty"
            @add-value="onAddPropertyValue"
            :property="property"
            :value="getPropertyValue(property.id)"
        />
      </v-col>
    </v-row>
    <div class="warning-text" v-if="category && !properties.length">
      <span class="warning-link" @click="openPropertyModal">{{ $t('nomenclature.add_property_to_category') }}</span>
    </div>
    <div v-if="!category" class="warning-text">
      {{ $t('nomenclature.warning_category') }}
    </div>
    <add-properties v-if="category" :category="category" ref="propertiesModal"></add-properties>
  </form>
</template>

<script>
// vuex
import {mapGetters, mapActions} from 'vuex'
import {eventBus} from "@/main";
// components
import PropertySelect from "@/components/dashboard/products/nomenclature/modals/froms/PropertySelect";
import AddProperties from "@/components/dashboard/products/nomenclature/modals/AddProperties";

export default {
  name: "GoodsFormThird",
  components: {AddProperties, PropertySelect},
  data() {
    return {
      radioGroup: {
        createSingleItem: true,
        creatMultipleItems: false,
      },
      form: {
        weight: 0,
        volume: 0,
        weight_id: 1,
        volume_id: 1,
        country_id: null,
        property_value: [],
        model: null,
        brand: null,
      }
    }
  },
  created() {
    if (this.mode !== 'create') {
      console.log(this.data.property_value, 'this.data.property_value')
      const brand = this.data.property_value.find(item => item.property_id === 1);
      const model =  this.data.property_value.find(item => item.property_id === 2);

      if (brand) this.onChangeProperty(brand.id);
      if (model) this.onChangeProperty(model.id);

    }
    const data = JSON.parse(JSON.stringify(this.data))
    Object.keys(this.form).forEach((item => {
      this.form[item] = data[item]
    }))
  },
  mounted() {
    eventBus.$on('change-product', data => {
      this.form = {...this.form, ...data}
    })
    eventBus.$on('on-click-add-property-value', () => {
      this.openPropertyModal()
    })
  },
  computed: {
    ...mapGetters({
      brandGetter: "brand",
      modelGetter: "model"
    }),
    brand() {
      return this.brandGetter
      // if (this.mode === 'create') {
      //   return this.brandGetter
      // } else {
      //   return {...this.brandGetter, property_value: [this.data.property_value.find(item => item.property_id === 1)]}
      // }
    },
    model() {
      return this.modelGetter
      // if (this.mode === 'create') {
      //   return this.modelGetter
      // } else {
      //   return {...this.modelGetter, property_value: [this.data.property_value.find(item => item.property_id === 2)]}
      //
      // }
    },
    properties() {
      if (this.category) {
        return this.category.properties.filter(property => (property.id !== 1 && property.id !== 2))
      }
      return null

    },
  },
  props: ['category', 'data', 'countries', 'units', 'mode'],
  methods: {
    ...mapActions(['addPropertyValue']),
    getPropertyValue(valueId) {
      const property = this.form.property_value.find(value => value.property_id === valueId);
      return property ? property : '';
    },
    changeState(item) {
      this.radioGroup.createSingleItem = false
      this.radioGroup.creatMultipleItems = false
      this.radioGroup[item] = true
    },
    validateForm() {
      return false
    },

    onChangeProperty(value) {
      console.log(value);
      const index = this.form.property_value.findIndex(item => item.property_id === value.property_id)
      if (index !== -1) {
        this.form.property_value.splice(index, 1, value)
      } else {
        this.form.property_value.push(value)
      }
      this.emitFormData()
    },
    emitFormData() {
      this.$emit('stepUpdated', {
        data: this.form,
        isValid: true,
        resource: 'product',
        title: 'products_chars'
      })
    },
    async onAddPropertyValue(params) {
      try {
        await this.addPropertyValue(params)
        await this.$store.dispatch('fetchCategory', this.category.id)
      } catch (e) {
        console.log(e);
      }
    },
    openPropertyModal() {
      this.$refs.propertiesModal.open()
    },
  }


}
</script>

<style scoped lang="scss">
.item-form {
  padding-top: 30px;
  padding-left: 0;
  padding-right: 0;
}
.col {
  .col {
    padding-top: 0;
  }
}

.custom-input {
  padding-bottom: 4px;
}

.label-title {
  margin-bottom: 13px !important;
  line-height: 1;
}

.double-input {
  &.volume {
    height: 100%;
    input {
      width: 100%;
    }
  }
  display: flex;

  .label-title {
    margin-bottom: 13px !important;
    line-height: 1;
  }

  input {
    font-size: 14px;
    line-height: 14px;
    color: #7E7E7E;
    width: 49px;
    padding-bottom: 0;
    -moz-appearance: textfield;

    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  }

  .select-wrap {
    width: 50px;
    margin-left: 15px;
  }
}

.warning-text {
  .warning-link {
    color: #317CCE;
    text-decoration: underline;
    cursor: pointer;

    &:hover {
      text-decoration: none;
    }
  }

  position: absolute;
  text-align: center;
  top: 50%;
  left: 50%;
  transform: translate(-50%);
  font-size: 15px;
  line-height: 130%;
  color: #9DCCFF;
}

.add-property-value {
  text-align: right;
  font-size: 15px;
  line-height: 1;
  color: #5893D4;
  cursor: pointer;
  height: 100%;
  justify-content: flex-end;
  align-items: flex-end;
  display: flex;
  padding-bottom: 12px;
  transform: translateY(-130px);

  svg {
    margin-right: 10px;
  }

  &:hover {
    text-decoration: underline;
  }
}

</style>
