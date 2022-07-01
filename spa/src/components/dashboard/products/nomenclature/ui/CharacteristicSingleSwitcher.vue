<template>
 <div class="characteristic-values-block">
    <div class="label-title characteristic-title">{{ char.title }}</div>
    <div class="search-wrapper">
      <async-simple-input @updateInput="onSearchCharacteristic"></async-simple-input>
    </div>
    <simplebar class="characteristic-value">
      <div v-for="value in  statsValues"
           :key="value.id"
           @change="onSelectValue(char.id, value.id)"
           class="radio-button">
        <label :for="value.title.toLowerCase()">
          <input type="radio"
                 :checked="getButtonState(char.id, value)"
                 :id="value.title.toLowerCase()"
                 :name="char.title">
          <div class="radio-button-text">
            {{ value.title }}
            <span v-if="value.code">({{ value.code }})</span>
            <span v-if="value.color" class="color-preview" :style="{background: value.color }"></span>
          </div>
        </label>
      </div>
    </simplebar>
    <div class="add-property-value" @click.stop="onClickAddValue(char)">
      <svg-sprite width="12" height="12" icon-id="plusBlue"></svg-sprite>
      {{ $t('nomenclature.add_value') }}
    </div>
 </div>
</template>

<script>
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput";

export default {
  name: "CharacteristicSingleSwitcher",
  components: {AsyncSimpleInput},
  props: {
    char: Object,
    selectedChars: Object
  },
  data() {
    return {
      query: '',
    }
  },
  computed: {
    statsValues() {
      if (this.char) {
        const charValues = this.char.characteristic_color_value || this.char.characteristic_value
        return charValues.filter(value => value.title.includes(this.query))
      }
      return []
    },
  },
  methods: {
    onClickAddValue() {
      this.$emit('add-characteristic-value', this.char)
    },
    onSelectValue(charId, valId) {
      this.$emit('select-value', {charId, valId})
    },
    onSearchCharacteristic(query) {
      this.query = query
    },
    getButtonState(charId, value) {
      if (this.selectedChars) {
        return this.selectedChars.values.findIndex(item => item.id === value.id) !== -1
      }
      return false

    },
  }
}
</script>

<style scoped lang="scss">
.radio-button label {
  padding-top: 0;
  padding-bottom: 15px;
}
.characteristic-values-block {
  max-width: 220px;
}

.search-wrapper {
  margin-bottom: 12px;
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

.characteristic-title {
  line-height: 1;
  font-size: 12px;
  color: #5893D4;
}

.characteristic-value {
  margin-top: 10px;
  padding: 15px;
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

  .radio-group {
    margin-bottom: 0;
    display: flex;
    height: 100%;
    flex-direction: column;

    label {
      padding-top: 0;
      margin-bottom: 20px;
    }

  }

  .checkbox {
    font-size: 15px;
    font-weight: 400;
    line-height: 1;
    color: #7E7E7E;

    .checkbox-label {
      display: block;
    }
  }

  .color-preview {
    float: right;
    display: inline-block;
    width: 16px;
    height: 16px;
  }
}
</style>
