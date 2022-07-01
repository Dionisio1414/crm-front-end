<template>
  <v-col class="optionItem">
    <div class="label-title characteristic-title">{{ char.title }}</div>
    <div class="search-wrapper">
      <async-simple-input @updateInput="onSearchCharacteristic"></async-simple-input>
    </div>
    <span @click="isHideUnSelected = !isHideUnSelected" class="add-property-value">
      <svg-sprite width="14" height="16" :icon-id="isHideUnSelected ? 'reset' : 'return'"></svg-sprite>
      {{isHideUnSelected ? 'Показать все' : 'Скрыть невыбранные'}}
    </span>
    <simplebar class="characteristic-value">
      <div class="radio-group">
        <div class="checkbox wrap" v-if="!isHideUnSelected">
          <label class="checkbox-label">
            <input
                type="checkbox"
                @change="onSelectAll"
                v-model="selectAll"
                name="characteristic-value "
            >
            <span class="checkbox-text">
              Все
            </span>
          </label>
        </div>
        <div
            class="checkbox"
            v-for="value in statsValues"
            :key="value.title"
        >
          <label class="checkbox-label" :for="value.title" v-if="mode === 'multiply'">
            <input
                type="checkbox"
                @change="onSelectValue(char.id, value.id)"
                :id="value.title" name="characteristic-value"
                ref="input"
            >
            <span class="checkbox-text">
              {{ value.title }}
              <span v-if="value.code">({{ value.code }})</span>
              <span v-if="value.color" class="color-preview" :style="{background: value.color }"></span>
            </span>
          </label>
        </div>
      </div>
    </simplebar>
    <div class="add-property-value" @click.stop="onClickAddValue(char)">
      <svg-sprite width="12" height="12" icon-id="plusBlue"></svg-sprite>
      {{ $t('nomenclature.add_value') }}
    </div>
  </v-col>
</template>

<script>
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput";

export default {
  name: "CharacteristicMultiplySwitcher",
  components: {AsyncSimpleInput},
  props: {
    char: Object,
    mode: String,
    selectedChars: Object
  },
  data() {
    return {
      query: '',
      isHideUnSelected: false,
      selectAll: false
    }
  },
  computed: {
    statsValues() {
      if (this.char) {
        let charValues = this.char.characteristic_color_value || this.char.characteristic_value
        if (this.isHideUnSelected) {
          charValues = charValues.filter(char => !!this.selectedChars.values.find(item => item.id === char.id))
        }

        return charValues.filter(value => {
          const title = value.title.toLowerCase()
          return title.includes(this.query)
        })
      }
      return []
    },
  },
  methods: {
    onClickAddValue() {
      this.$emit('add-characteristic-value', this.char)
    },
    onSelectValue(charId, valId) {
      this.selectAll = false;
      this.$emit('select-value', { charId, valId })
    },
    onSearchCharacteristic(query) {
      this.query = query
    },
    onSelectAll() {
      const list = this.$refs.input;
      list.forEach(item => (item.checked = this.selectAll))
      this.statsValues.forEach(item => this.$emit('select-value', { charId: this.char.id, valId: item.id }))
    }
  }
}
</script>

<style scoped lang="scss">
.optionItem {
  width: 100%;
  max-width: 244px;
}

.search-wrapper {
  margin-bottom: 12px;
}

.add-property-value {
  font-size: 15px;
  line-height: 1;
  color: #9DCCFF;
  margin-top: 15px;
  cursor: pointer;
}

.characteristic-title {
  line-height: 1;
  height: 26px;
  font-size: 12px;
  color: #5893D4;
}

.characteristic-value {
  margin-top: 10px;
  padding: 20px 25px;
  border: 1px solid #9DCCFF;
  border-radius: 10px;
  height: 190px;

  .radio-button-text, .checkbox-text {
    width: 100%;
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

    &.wrap {
      height: initial;
      position: relative;

      &:after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 1px;
        bottom: 10px;
        background: #9dccff;
      }
    }

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
