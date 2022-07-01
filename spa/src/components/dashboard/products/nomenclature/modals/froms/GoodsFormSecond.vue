<template>
  <form class="item-form create-new-item add-product-stat" @click="isEdit = false">
    <v-row>
      <v-col cols="12">
        <div class="radio-group" :class="{'disabled': mode === 'update'}">
          <div class="radio-button">
            <label for="huey">
              <input
                  type="radio"
                  :disabled="mode === 'update'"
                  id="huey"
                  name="id-group"
                  :checked="!isGroup"
                  @click="changeState(false) ">
              <div class="radio-button-text" :class="{'active' : !isGroup}">
                {{ $t('nomenclature.once_create') }}
              </div>
            </label></div>
          <div class="radio-button">
            <label for="dewey">
              <input
                  type="radio"
                  id="dewey"
                  :disabled="mode === 'update'"
                  name="id-group"
                  :checked="isGroup"
                  @click="changeState(true) ">
              <div class="radio-button-text" :class="{'active' : isGroup}">
                {{ $t('nomenclature.multiply_create') }}
              </div>
            </label>
          </div>
        </div>
      </v-col>
    </v-row>
    <v-row v-if="category && category.characteristics.length !== 0">
      <v-col cols="6">
        <div class="select-wrap">
          <div class="label-title">{{ $t('nomenclature.option') }}</div>
          <v-select
              class="select-switcher"
              placeholder="Выберите опцию"
              item-text="title"
              item-value="id"
              v-model="selectedAdditionalCharacteristic"
              @change="addAdditionalCharacteristic"
              :disabled="selectedAdditionalStats.length >= 4"
              :items="characteristics"
              solo
              dense
              menu-props="units"
          >
          </v-select>
          <v-btn @click="openCharacteristicModal" icon class="create-nested-category">
            <svg-sprite width="30" height="30" icon-id="edit"></svg-sprite>
          </v-btn>
        </div>
      </v-col>
      <v-col cols="6"></v-col>
      <v-col cols="6">
        <div class="values-list">
          <div class="value-item" v-for="(item, i) in selectedAdditionalStats" :key="item.id">
            <span>{{ item.title }}</span>
            <span class="iconSvg" @click="deleteAdditionalCharacteristic(i)">
              <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
            </span>
          </div>
        </div>
      </v-col>
      <v-col cols="12">
        <v-row v-if="!isGroup">
          <v-col
              class="characteristic"
              v-for="characteristic in selectedAdditionalStats" :key="characteristic.id"
          >
            <characteristic-single-switcher
                @add-characteristic-value="onClickAddValue"
                @select-value="onChangeCharacteristicValue"
                :char="characteristic"
                :selected-chars="form.characteristic_value.find(item => item.id === characteristic.id)"
            />
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <div
        class="warning-text"
        v-else-if="category && category.characteristics.length === 0"
    >
      <span
          class="warning-link"
          @click="openCharacteristicModal">
          {{ $t('nomenclature.add_char_to_category') }}
      </span>
    </div>
    <div v-else class="warning-text">
      {{ $t('nomenclature.warning_category') }}
    </div>
    <add-characteric
        :category="category"
        ref="characteristicModal">
    </add-characteric>
    <characteristic-edit
        v-if="isEdit"
        @click.stop
        :characteristicEdit="characteristicEdit"
        :closeDetails="closeDetails"
        without-title
    ></characteristic-edit>
  </form>
</template>

<script>
// vuex
import { mapGetters } from "vuex";
// components
import AddCharacteric from "@/components/dashboard/products/nomenclature/modals/AddCharacterstic";
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit";
import CharacteristicSingleSwitcher from "@/components/dashboard/products/nomenclature/ui/CharacteristicSingleSwitcher";
// constants
import {TABLE_ACTIONS} from '@/constants/constants';

const isNotEmpty = (values) => {
  return !!values.length
}

export default {
  name: "GoodsFormSecond",
  components: {
    CharacteristicSingleSwitcher,
    CharacteristicEdit,
    AddCharacteric
  },
  data() {
    return {
      selectedAdditionalCharacteristic: '',
      additionalCharacteristic: [],
      headCharacteristic: '',
      showDropDown: false,
      characteristicEdit: {
        title: ''
      },
      isEdit: false,
      form: {
        characteristic_value: [],
        characteristic_color_value: null,
        base_characteristic_value: null,
      }
    }
  },
  validations: {
    form: {
      characteristic_value: {
        $each: {
          values: {isNotEmpty}
        }
      },
      // base_characteristic_value: {isHeadValidation}
    }
  },
  created() {
    if (this.mode === TABLE_ACTIONS.CREATE) {
      this.$emit('change-group-state', false)
    }
    if (this.mode === TABLE_ACTIONS.UPDATE || this.mode === TABLE_ACTIONS.COPY) {
      Object.keys(this.form).forEach((item => this.form[item] = this.data[item]))
    }
  },
  computed: {
    ...mapGetters({
      characteristicsArr: 'characteristics',
      colorData: 'colorData',
      sizeData: 'sizeData'

    }),
    // baseCharacteristic() {
    //     if (this.form.base_characteristic_value) {
    //         return this.allCharacteristics.find(item => item.id === this.form.base_characteristic_value.id)
    //     }
    //     return false
    // },
    characteristics() {
      return [...this.category.characteristics].filter(
          item => {
            const isInclude = !this.form.characteristic_value.find(char => char.id === item.id)
            const idHead = item.id !== this.headCharacteristic
            return isInclude && idHead
          }
      )
    },
    allCharacteristics() {
      return [].concat(this.characteristicsArr, [this.colorData], [this.sizeData])
    },
    selectedAdditionalStats() {
      return [...this.form.characteristic_value].map((stat) => this.allCharacteristics.find(fullStat => fullStat.id === stat.id)).sort((a, b) => a.id - b.id)
    },
  },
  props: ['category', 'data', 'categories', 'mode', 'isGroup'],

  methods: {
    getButtonState(charId, value) {
      if (this.form.characteristic_value.length) {
        const char = this.form.characteristic_value.find(item => item.id === charId)
        return char.values.findIndex(item => item.id === value.id) !== -1
      }
      return false

    },
    getBaseButtonState(value) {

      if (this.form.base_characteristic_value) {
        const char = this.form.base_characteristic_value
        if (char.id === 1) {
          return char.values.id === value.id
        } else if (char.values[0]) {
          return char.values[0].id
        }
      }
      return false
    },
    changeState(val) {
      this.$emit('change-group-state', val);
      this.form.characteristic_value = this.form.characteristic_value.map(option => ({...option, values: []}));
      const valid = val ? !!this.form.characteristic_value.length : !this.$v.$invalid
      this.$emit('stepUpdated', {
        data: this.form,
        isValid: valid,
        resource: 'product',
        title: 'products_options'
      })
    },
    closeDetails() {
      this.isEdit = false;
    },
    onClickAddValue(characteristic) {
      this.characteristicEdit = JSON.parse(JSON.stringify(characteristic))
      this.isEdit = true
    },
    onClickOutside() {
      if (this.isEdit) {
        this.isEdit = false;
      }
    },
    emitFormData() {
      const valid = this.isGroup ? !!this.form.characteristic_value.length : !this.$v.$invalid
      this.$emit('stepUpdated', {
        data: this.form,
        isValid: valid,
        resource: 'product',
        title: 'products_options'
      })
    },
    addAdditionalCharacteristic() {
      console.log(this.form.characteristic_value, this.selectedAdditionalCharacteristic);
      const id = this.selectedAdditionalCharacteristic;
      const index = this.form.characteristic_value.findIndex(item => item.id === id);

      if (index === -1) {
        const newChar = {
          id: this.selectedAdditionalCharacteristic,
          values: []
        }

        this.form.characteristic_value.push(newChar)
      }

      this.selectedAdditionalCharacteristic = null;
      this.emitFormData()
    },
    deleteAdditionalCharacteristic(index) {
      this.additionalCharacteristic.splice(index, 1);
      this.form.characteristic_value.splice(index, 1);
      this.selectedAdditionalCharacteristic = null;
      this.emitFormData();
    },
    onChangeHeadCharacteristicValue(id) {
      this.form.base_characteristic_value.values = [{id}]
      this.emitFormData()
    },
    onChangeCharacteristicValue({charId, valId}) {
      console.log(charId, valId);
      let characteristic = this.form.characteristic_value.find(item => item.id === charId)
      characteristic.values = [{id: valId}]
      this.emitFormData()
    },
    openCharacteristicModal() {
      this.$refs.characteristicModal.open()
    },
    checkCharacteristics() {
      const id = this.headCharacteristic
      this.form.base_characteristic_value = {
        id,
        is_color: id === 1,
        values: []
      }
      const index = this.form.characteristic_value.findIndex(item => item.id === id)
      if (index !== -1) {
        this.form.characteristic_value.splice(index, 1)
      }
      this.emitFormData()
    }
  },
}
</script>

<style scoped lang="scss">
.characteristic {
  max-width: 246px;
}

.label-title {
  line-height: 1;
  height: 26px;
}

.select-wrap {
  display: flex;
  width: 100%;
  flex-wrap: wrap;

  button {
    margin-left: 10px;
  }

  .label-title {
    width: 100%;
  }
}

.add-property-value {
  font-size: 15px;
  line-height: 1;
  color: #5893D4;
  margin-top: 15px;
  cursor: pointer;

  &:hover {
    text-decoration: underline;
  }
}

.select-style {
  display: block;
  width: 20px;
  height: 20px;
}

.radio-button-text {
  font-size: 15px;
  line-height: 15px;
  color: #9DCCFF;

  &.active {
    color: #5893D4
  }
}

.characteristic-value {
  .checkbox {
    width: 100%;
  }

  margin-top: 10px;
  padding: 20px 25px;
  border: 1px solid #9DCCFF;
  border-radius: 10px;
  height: 190px;

  .radio-button-text, .checkbox-text {

    font-size: 15px;
    font-weight: 400;
    line-height: 1;
    color: #7E7E7E;
    padding-left: 30px;
  }

  .color-preview {
    float: right;
    display: inline-block;
    width: 16px;
    height: 16px;
  }

  .radio-group {
    margin-bottom: 0;

    label {
      padding-top: 0;
      margin-bottom: 20px;
    }

  }

}

.item-form {
  max-width: 100%;
  padding-top: 30px;
  .radio-group {
    margin-bottom: 0;

    label {
      padding-top: 0;
      margin-bottom: 20px;
    }

  }
}

.values-list {
  margin-bottom: 0;
  margin-top: -12px;

  .value-item {
    margin: 0 10px 10px 0;
  }
}

.iconSvg {
  margin-right: 0 !important;
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
</style>
