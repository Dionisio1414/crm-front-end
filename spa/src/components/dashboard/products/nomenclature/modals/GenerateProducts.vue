<template>
  <div class=" ">
    <v-dialog

        v-model="dialog"
        max-width="1234px"
        class="dialog-large generate-modal dialog-category"
    >
      <div class="dialog generate-modal" @click="isEdit = false">
        <div class="dialog-header">
          <div class="header-text">
            сгенерировать варианты товара
          </div>
          <header-actions is-hide-attach is-hide-dots @updateClose="close"></header-actions>
        </div>
        <div class="dialog-content dialog-content-large  add-product-stat">
          <div class="item-form">
            <v-row>
              <v-col cols="3" v-if="selectedAdditionalStats.length > 0">
                <div class="label-title">
                  {{ $t('nomenclature.option') }}
                </div>
              </v-col>
            </v-row>
            <v-row v-if="dialog">
              <characteristic-multiply-switcher
                  v-for="(char, index) in selectedAdditionalStats"
                  @select-value="onSelectValue"
                  @add-characteristic-value="onClickAddValue"
                  @click.stop
                  :key="index"
                  :char="char"
                  :selected-chars="params.characteristic_value.find(item => item.id === char.id)"
                  mode="multiply"
              />
            </v-row>
            <div class="generation-block">
              <div class="generation-block-header">
                <div class="label-title">Сгенерированные варианты товара</div>
                <div class="warn">
                  <span v-if="itemsToGenerate.length" class="warnText">Сгенирировано: {{itemsToGenerate.length}} товаров</span>
                  <v-tooltip
                      right
                      max-width="400px"
                      text-color="#7E7E7E"
                      content-class="gray-tooltip">
                    <template v-slot:activator="{ on, attrs }">
                      <span class="tooltip-icon">
                        <span v-bind="attrs" v-on="on">
                          <svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite>
                        </span>
                      </span>
                    </template>
                    <span>  - Существующие варианты товара повторно не генерируют. <br> - Создаются варианты товаров только на новые сочетания характеристик.</span>
                  </v-tooltip>
                </div>
                <div class="dialog-actions popup-buttons">
                  <v-btn
                      class="popup-btn"
                      color="#9DCCFF"
                      dark
                      @click="generate"
                  >
                    Сгенерировать
                  </v-btn>
                  <v-btn
                      class="popup-btn transparent-btn"
                      color="#9DCCFF"
                      text
                      @click="clear"
                  >
                    Очистить
                  </v-btn>
                </div>
              </div>
              <generate-table
                  :headers="headers"
                  :items="itemsToGenerate"
                  :allCharacteristics="allCharacteristics"
                  :getTableValue="getTableValue"
                  @updateRemove="onRemove"
              ></generate-table>
            </div>
          </div>
          <characteristic-edit
              v-if="isEdit"
              @click.stop
              :characteristicEdit="characteristicEdit"
              :closeDetails="closeDetails"
              without-title
          />
        </div>

        <div class="dialog-content dialog-content-large">
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="close"
            >
              Отменить
            </v-btn>

            <v-btn
                class="popup-btn"
                color="#5893D4"
                dark
                @click="save"
            >
              Сохранить
            </v-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import {mapGetters, mapActions} from "vuex";
// components
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit";
import CharacteristicMultiplySwitcher from "../ui/CharacteristicMultiplySwitcher";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import GenerateTable from "@/components/dashboard/products/nomenclature/ui/GenerateTable"
import removeDuplicates from "@/helper/removeDuplicates";

export default {
  name: "GenerateProducts",
  components: {
    CharacteristicMultiplySwitcher,
    CharacteristicEdit,
    HeaderActions,
    GenerateTable
  },
  data: () => ({
    dialog: false,
    itemsToGenerate: [],
    isEdit: false,
    characteristicEdit: { title: ''},
    params: {
      name: '',
      sku: '',
      prices: [],
      base_characteristic_value: null,
      characteristic_value: [],
      characteristic_color_value: null,
    }
  }),
  computed: {
    ...mapGetters({
      characteristicsArr: 'characteristics',
      colorData: 'colorData',
      sizeData: 'sizeData',
    }),
    headers() {
      return [
        /*{title: 'Артикул', value: 'sku', is_sortable: true},*/
        {title: 'Наименование', value: 'name', is_sortable: true},
        ...this.selectedAdditionalStats.map(char => {
          return {
            title: char.title,
            value: `char_${char.id}`,
            is_sortable: true
          }
        })
      ]
    },
    selectedAdditionalStats() {
      return [...this.params.characteristic_value].map((stat) => {
        return this.allCharacteristics.find(fullStat => fullStat.id === stat.id)
      }).sort((a, b) => a.id - b.id)
    },
    allCharacteristics() {
      if (this.sizeData && this.colorData) {
        return [].concat(this.characteristicsArr, this.colorData, this.sizeData)
      }
      return []
    },
    characteristics() {
      return [
        // {...this.params.base_characteristic_value},
        ...this.params.characteristic_value]
    }
  },

  methods: {
    ...mapActions(['generateProducts']),
    clear() {
      this.itemsToGenerate = []
    },

    closeDetails() {
      this.isEdit = false;
    },
    onClickAddValue(characteristic) {
      this.isEdit = true
      this.characteristicEdit = JSON.parse(JSON.stringify(characteristic))
    },
    statsValues(characteristic) {
      return characteristic ? characteristic : characteristic.characteristic_color_value || characteristic.characteristic_value;
    },
    open(params) {
      this.params = params
      this.dialog = true
      this.itemsToGenerate = []
    },
    filteredItems(generatedItems) {
      const array = [...this.itemsToGenerate, ...generatedItems];

      return removeDuplicates(array);
    },
    async generate() {
      const data = {
        ...this.params,
        characteristic_value: this.params.characteristic_value.filter(char => !!char.values.length)
      }

      const generatedItems = await this.generateProducts(data);
      generatedItems.forEach(item => item.sku = null)
      //delete sku

      this.itemsToGenerate = this.itemsToGenerate.length ? this.filteredItems(generatedItems) : generatedItems;
    },
    onSelectValue({charId, valId}) {
      const char = this.params.characteristic_value.find(char => char.id === charId);
      const valIndex = char.values.findIndex(item => item.id === valId);

      valIndex === -1 ? char.values.push({id: valId}) : char.values.splice(valIndex, 1);
    },
    getTableValue(value, key) {
      let formatVal = value
      if (key === 'sku') {
        return null //formatVal
      }
      if (!formatVal) {
        return null
      }

      if (key.includes('char_')) {
        const charId = key.split('_')[1];
        const char = this.allCharacteristics.find(item => item.id === +charId);

        if (char.id === 1) {
          formatVal = char.characteristic_color_value.find(val => val.id === +value);
        } else if (char.id === 2) {
          const values = [].concat(char.characteristic_value);
          formatVal = values.find(val => val.id === +value);
        } else {
          formatVal = char.characteristic_value.find(val => val.id === +value);
        }
      }

      return formatVal && formatVal.title || formatVal
    },
    save() {
      const data = this.itemsToGenerate.map(item => ({...item}))
      data.forEach(item => {
        const values = [...item.characteristic_value].map(char => {
          return this.getTableValue(char['value_id'], `char_${char.id}`)
        })
        item.base_characteristic_value = null;
        item.characteristic_color_value = {...item.characteristic_value.find(char => char.id === "1")}
        if (item.characteristic_color_value) {
          item.characteristic_color_value = { id: +item.characteristic_color_value.value_id }
        }

        item.characteristic_value = item.characteristic_value.filter(char => char.id !== "1")
        item.characteristic_value = item.characteristic_value.map(char => {
          return {id: +char['value_id']}
        })

        item.short_title = `${item.name} ${values.join(' ')}`
        delete item.name
      })

      const values = { products: data, id: this.params.id };
      this.$emit('add-products-to-group', values)
    },
    close() {
      this.dialog = false
    },
    onRemove(idx) {
      this.itemsToGenerate.splice(idx, 1);
    }
  }
}
</script>
<style lang="scss">
.generate-modal {
  .search-block{
    border: 1px solid #9DCCFF;
    border-radius: 50px;
    input {
      border-bottom: none;
    }
  }
}

</style>
<style scoped lang="scss">
.generation-block-header {
  align-items: center;
  display: flex;
  margin-bottom: 10px;

  .dialog-actions {
    display: flex;
  }
}

.flex {
  &.columns {
    display: flex;
    flex-direction: column;
  }
}

.generation-block {
  padding-top: 35px;
}

.item-form {
  padding-bottom: 33px;
}

.label-title {
  margin-bottom: 0;
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
    font-size: 15px;
    font-weight: 400;
    line-height: 1;
    color: #7E7E7E;
    padding-left: 30px;
  }

  .radio-group {
    margin-bottom: 0;

    label {
      padding-top: 0;
      margin-bottom: 20px;
    }

  }
}
.warn {
  font-size: 13px;
  white-space: nowrap;
  margin-right: 10px;
  display: flex;
  align-items: center;

  &Text {
    margin-right: 10px;
  }
}
</style>
