<template>
  <div
      v-if="characteristic"
      class="item-area"
      @click.stop>
    <div class="item-area-col">
      <form class="item-form" @submit.prevent>
        <div v-if="!withoutTitle" class="item-name">
          <label class="input-label">{{ $t("page.name") }}</label>
          <input
              @input="changeName"
              type="text"
              :placeholder="$t('page.typeName')"
              v-model.trim="characteristicName"
              :disabled="characteristic.id === 1 || characteristic.id === 2"
          />
          <small
              v-if="clone && clone.id === characteristicEdit.id"
              class="warn"
          >
            {{ $t('page.change_option_name') }}
          </small>
          <small
              v-if="exists"
              class="warn"
          >
            {{ $t('page.option') }} <b>{{ characteristicName }}</b>{{ $t('validation.uniq') }}
          </small>
        </div>
        <div class="item-values">
          <div
              v-if="characteristic.id === 2"
              class="values-titles"
          >
            <label class="input-label"
            >{{ $t('option.choose_size') }}</label>
            <label style="text-align: left; max-width: 215px">{{ $t('option.your_value_case') }}</label>
          </div>
          <label v-else>{{ $t("page.addValueVariant") }}</label>
          <input
              v-if="characteristic.id !== 2"
              @input="checkValue"
              type="text"
              :placeholder="$t('page.typeName')"
              v-model="itemValue.title"
              @keypress.enter="saveChip"
          />
          <small
              v-if="valueExists"
              class="warn"
          >
            {{ $t('option.option_value') }}<b>{{ itemValue.title }}</b> {{ $t('validation.uniq') }}
          </small>
          <div
              v-if="characteristic.id === 1"
              class="selection-bar"
          >
            <v-menu
                transition="slide-y-transition"
                bottom
                :close-on-content-click="closeOnContentClick"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="select-btn"
                    v-bind="attrs"
                    v-on="on"
                    @click="selectColor"
                >
                  <div class="text">{{ $t('option.choose_color') }}</div>
                </v-btn>
              </template>
              <v-color-picker
                  hide-mode-switch
                  v-model="colorValue"
                  show-swatches
                  @input="onUpdateColor"
                  ref="colorPicker"
              ></v-color-picker>
            </v-menu>
            <div
                v-if="visibleSelectValue"
                class="select-value"
            >
              <span>Выбрано: </span>
              <div
                  class="select-style"
                  :style="{ background: selectStyle2 }"
              ></div>
              <span @click="clearSelectValue">
                <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
              </span>
            </div>
          </div>
          <div
              v-if="characteristic.id === 1"
              class="vendor-code"
          >
            <div class="vendor-code-header">
              <input
                  type="checkbox"
                  id="vendor"
                  :checked="vendor"
                  v-model="vendor"
              />
              <label for="vendor">{{ $t('option.sku') }}</label>
            </div>
            <input
                :disabled="!vendor"
                type="text"
                placeholder="00"
                v-model="vendorCode"
            />
          </div>

          <div
              v-if="characteristic.id === 2"
              class="size-grid"
          >
            <div class="digital-sizes size-item">
              <div class="size-item-title">Цифровы размеры</div>
              <simplebar class="size-item-content">
                <div class="check-all" @click="selectAll(0)">
                  <input type="checkbox" id="all-digital-sizes" :checked="selectedAllNumberSize"/>
                  <label for="all-digital-sizes">Все</label>
                </div>
                <ul class="list-group">
                  <li class="list-group-item"
                      v-for="(item, index) in values.filter(i => i.type === 0)" :key="index">
                    <input
                        :id="item.id"
                        type="checkbox"
                        :checked="!!sizes.find(size => size.title === item.title)"
                        @change="updateSelectedSizes(item)"
                    />
                    <label :for="item.id">{{ item.title }}{{ }}</label>
                  </li>
                </ul>
              </simplebar>
            </div>

            <div class="character-sizes size-item">
              <div class="size-item-title">Символьные размеры</div>
              <simplebar class="size-item-content">
                <div class="check-all" @click="selectAll(1)">
                  <input type="checkbox" id="all-character-sizes" :checked="selectedAllSymbolSize"/>
                  <label for="all-character-sizes">Все</label>
                </div>
                <ul class="list-group">
                  <li class="list-group-item"
                      v-for="(item, index) in values.filter(i => i.type === 1)" :key="index">
                    <input
                        :id="item.id"
                        type="checkbox"
                        :checked="!!sizes.find(size => size.title === item.title)"
                        @change="updateSelectedSizes(item)"
                    />
                    <label :for="item.id">{{ item.title }}{{ }}</label>
                  </li>
                </ul>
              </simplebar>
            </div>
            <div class="size-item custom-size">
              <div class="custom-size-content">
                <input type="text" @keyup.enter="addCustomSize" v-model="customSize">
                <span :class="{'disabled': !customSize}" @click="addCustomSize">
                  <svg-sprite width="30" height="30" icon-id="edit"></svg-sprite>
                </span>
              </div>
            </div>
          </div>
          <button
              v-if="characteristic.id !== 2"
              type="button"
              :disabled="valueExists || !itemValue.title"
              @click="editableChip ? updateCharValue() : saveChip()"

              class="base-button base-button--lighten--transparent"
          >{{ editableChip ? $t("page.saveButton") : $t("page.addButton") }}
          </button>
        </div>
      </form>
    </div>

    <div class="item-area-col col-list">
      <draggable
          v-if="characteristic.id === 2" class="values-list size-value-list"
          tag="div"
          v-model="sizes"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
      >
        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
          <div
              class="value-item"
              v-for="(value, i) in sizes"
              :key="i"
              :class="{active: value.title === customSize, edit: value.edit}"
          >
            <span>{{ value.title }}</span>
            <span class="edit" @click="editCustomSize(value, i)">
              <svg-sprite width="17" height="18" icon-id="edit_pencil_small"></svg-sprite>
            </span>
            <span class="close" @click="deleteChip(value, i)">
              <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
            </span>
          </div>
        </transition-group>
      </draggable>
      <draggable
          v-if="characteristic.id !== 2"
          class="values-list"
          :class="{'value-list-color' : characteristic.id === 1 }"
          tag="div"
          v-model="values"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
      >
        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
          <div
              class="value-item"
              v-for="(value, i) in values"
              :key="value.title"
              :class="{active: value.title.toLowerCase() === itemValue.title.toLowerCase() }"
          >
            <span>{{ value.title }}</span>
            <span v-if="value.code">({{ value.code }})</span>
            <span class="select-style" :style="{ backgroundColor: value.color }"></span>

            <span class="edit chip" @click="editChip(value, i)">
              <svg-sprite width="17" height="18" icon-id="edit_pencil_small"></svg-sprite>
            </span>
            <span class="close chip" @click="deleteChip(value, i)">
              <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
            </span>
          </div>
        </transition-group>
      </draggable>
      <div class="used" v-if="characteristic.categories">
        <div class="title">{{ $t("page.usedCategories") }}</div>
        <div class="used-list">
          <router-link
              :to="`/products/categories/${item.id}`"
              v-for="item in characteristic.categories"
              :key="item.id"
          >
            <span>{{ item.title }}</span>
            {{item.id}}
            <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
          </router-link>
        </div>
      </div>
      <div class="sticky" v-if="!isCategory">
        <div class="text">
          Результат редактирования опции и ее значений отображается в категориях и товарах, в которых используется опция и ее значения.
        </div>
        <v-btn
            @click="clone ? createItem() : updateItem()"
            :disabled="isCheck || exists || !characteristicName.length"
            class="base-btn shadow-btn btn-sticky"
        >
          {{ $t('page.saveButton') }}
        </v-btn>
      </div>
    </div>
  </div>

</template>

<script>
import {mapGetters} from 'vuex';

export default {
  name: "characteristicEdit",
  props: {
    isCategory: Boolean,
    characteristicEdit: {
      type: Object,
      default: () => ({ title: 'title' }),
    },
    closeDetails: {
      type: Function,
    },
    set: {
      type: Boolean,
      default: true,
    },
    withoutTitle: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    characteristicEdit(val) {
      console.log(val)
      this.characteristic = val
      this.characteristicName = this.characteristicEdit?.title
      this.updateCharacteristic()
    }
  },
  computed: {
    ...mapGetters([
      'colorData',
      'sizeData',
      'selectedSizes',
      'characteristics'
    ]),
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    exists() {
      return this.characteristicEdit.title !== this.characteristicName && this.characteristics.find(char => char.title === this.characteristicName)
    },
    selectStyle2: {
      get() {
        return this.selectValue || null
      },
      set() {
        return {
          background: this.editableChip.selectValue.background,
        };
      },
    },
    selectStyle() {
      if (this.selectImage.length > 0) {
        return {
          background: `url("${this.selectImage}")`,
        };
      } else if (this.selectValue) {
        return {
          background: this.selectValue,
        };
      } else {
        return null;
      }
    },
    clone() {
      return this.$store.getters["cloneCharacteristic"]
    },
    // characteristics() {
    //   return this.$store.getters["characteristics"]
    // },
    selectedAllNumberSize() {
      const selectedNumberSizes = this.sizes.filter(size => size.type === 0)
      const numberSizes = this.values.filter(size => size.type === 0)
      return selectedNumberSizes.length === numberSizes.length
    },
    selectedAllSymbolSize() {
      const selectedSymbolSizes = this.sizes.filter(size => size.type === 1)
      const symbolSizes = this.values.filter(size => size.type === 1)
      return selectedSymbolSizes.length === symbolSizes.length
    },
    isCheck() {
      if(this.clone && this.clone.id === this.characteristicEdit.id) {
        return false;
      }
      return this.characteristicEdit.title === this.characteristicName
          && this.characteristicCheckList && this.characteristicCheckList.length === this.values.length && this.checkArray();
    }
  },
  data: () => ({
    sizes: [],
    characteristicCheckList: null,
    customSize: '',
    editableChipIndex: null,
    allSelectedS: false,
    allSelectedN: false,
    characteristic: null,
    characteristicName: "",
    editSizeIndex: null,
    colorValue: null,
    itemValue: {
      title: "",
      vendorCode: "",
      selectValue: "",
    },
    editableChip: null,
    editSize: null,
    vendor: "",
    vendorCode: "",
    values: [],
    // exists: false,
    valueExists: false,
    closeOnContentClick: false,
    selectImage: "",
    selectValue: "",
    showSelectValue: false,
    visibleSelectValue: false,
    drag: false,
    bgc: {
      background: "",
    },
    rules: [
      (value) =>
          !value ||
          value.size < 2000000 ||
          "Размер картинки не должен превышать 2 МБ!",
    ],
  }),
  methods: {
    checkArray() {
      return this.values.reduce((acc, item) => {
        acc = this.characteristicCheckList.some(body => body === item.title);
        return acc;
      }, true)
    },
    removeAll() {
      this.sizes.forEach((item, index) => {
        if (!item.check) {
          this.sizes.splice(index, 1)
        }
      })
    },
    onUpdateColor() {
      this.selectValue = this.colorValue.hex
      // this.visibleSelectValue = false
    },
    selectAll(type) {
      const typesSizes = this.values.filter(size => size.type === type)
      const typesState = type ? !!this.selectedAllSymbolSize : !!this.selectedAllNumberSize
      if (!typesState) {
        const newSizes = this.sizes.filter(size => size.type !== type)
        newSizes.push(...typesSizes.map(size => (
            {
              title: size.title,
              type: size.type,
              check: true,
              order: size.order,
            }
        )))
        this.sizes = newSizes

      } else {
        this.sizes = this.sizes.filter(selectedSize => selectedSize.type !== type)
      }
    },
    checkSelectedCheckboxes() {
      this.values.filter(i => i.type === 1).forEach((item, index) => {
        if (!item.check) {
          this.values.splice(index, 1)
        }
      });
    },
    async addCustomSize() {
      if (this.editSize) {
        await this.updateCustomSize()
      } else {
        const size = {
          edit: true,
          title: this.customSize,
          check: true,
          type: null,
          id: null
        }
        try {
          this.customSize && this.sizes.push(size)
          //await this.$store.dispatch('updateSizes', [this.sizes, size])
          this.customSize = ''
        } catch (e) {
          console.log(e)
        }
      }
    },
    editCustomSize(value, index) {
      this.customSize = value.title
      this.editSize = value
      this.editSizeIndex = index
      // this.$store.dispatch('editSize', this.editSize)
    },
    updateCustomSize() {
      // const size = {
      //   title: this.customSize,
      //   edit: true,
      //   check: true,
      //   type: null,
      // }
      this.sizes[this.editSizeIndex].title = this.customSize
      this.editSizeIndex = null

      this.editSize = ''
      this.customSize = ''

    },
    updateSelectedSizes(item) {
      const index = this.sizes.findIndex(size => size.title === item.title)
      if (index === -1) {
        item.check = true
        this.sizes.push({
          check: item.check,
          title: item.title,
          type: item.type,
          // id: item.id,
        })

      } else {
        this.sizes.splice(index, 1)
        item.check = false
      }
    },

    // selectFile() {
    //   this.visibleSelectValue = true;
    // },
    selectColor() {
      this.visibleSelectValue = true;
    },
    previewImage(event) {
      this.selectValue = ""
      let input = event.target
      if (input.files && input.files[0]) {
        let reader = new FileReader()
        reader.onload = (e) => {
          this.selectImage = e.target.result
        };
        reader.readAsDataURL(input.files[0])
      }
    },
    clearSelectValue() {
      this.selectValue = null
      this.selectImage = ""
      this.visibleSelectValue = false
    },
    checkValue() {
      this.valueExists = this.values.find(value => value.title.toLowerCase() === this.itemValue.title.toLowerCase());
    },
    existsCheck() {
      this.exists = this.$store.getters['properties'].find(prop => prop.name === this.propertyName)
    },
    async changeName() {
      await this.$store.dispatch("clearClone");
      this.existsCheck()
    },
    saveChip() {
      if (this.editableChip) {
        this.updateChip(this.editableChip)
      } else {
        const values = this.values || this.value
        const itemValue = {
          title: this.itemValue.title,
          code: this.vendorCode,
          color: this.selectValue,
          id: null,
        }

        const set = this.set;
        ((set && values.indexOf(itemValue) === -1) || !set) &&
        values.push(itemValue)
        this.itemValue.title = ""
        this.vendorCode = ""
        this.vendor = false
        this.selectValue = null
        this.clearSelectValue()
        this.valueExists = false
      }
    },
    deleteChip(value, index) {
      if (this.characteristic.id === 1) {
        this.values.splice(index, 1)
      } else if (this.characteristic.id === 2) {
        this.sizes.splice(index, 1)
      } else {
        this.values.splice(index, 1)
      }
    },
    editChip(value, index) {
      this.editableChip = value
      this.editableChipIndex = index
      this.itemValue.title = this.editableChip.title
      this.vendor = true
      if (this.characteristicEdit.id === 1) {
        this.selectValue = this.editableChip.color
        this.vendorCode = this.editableChip.code
      }
      //this.selectValue = this.editableChip.selectValue
      this.visibleSelectValue = true
    },
    updateCharValue() {
      const editableChip = this.values[this.editableChipIndex]
      editableChip.title = this.itemValue.title;
      this.editableChipIndex = null;
      this.editableChip = null;
      this.itemValue.title = '';

      if (this.characteristicEdit.id === 1) {
        editableChip.color = this.selectValue
        editableChip.code = this.vendorCode
        this.selectValue = null
        this.vendorCode = null
      }
    },
    async updateItem() {
      const item = {
        id: this.characteristicEdit.id,
        title: this.characteristicName,
        values: this.values,
        check: false,
      }

      const alertParams = {
        textItems: [
          {text: 'char', style: 'normal', translate: true},
          {text: item.title, style: 'bold', translate: false},
          {text: 'updated', style: 'normal', translate: true},
        ],
        link: {
          text: 'Показать',
          action: 'show-item',
          actionParams: item
        }
      }

      try {
        if (this.characteristicEdit.id === 1) {
          const newItem = [...item.values].map(val => {
                if (val.id) {
                  return {
                    title: val.title,
                    code: val.code,
                    color: val.color,
                    image_id: val.image,
                    id: val.id
                  }
                } else return {
                  title: val.title,
                  code: val.code,
                  color: val.color,
                  image_id: val.image,
                }
              }
          )
          await this.$store.dispatch("updateColor", newItem)
        } else if (this.characteristicEdit.id === 2) {
          await this.$store.dispatch("updateSizes", this.sizes)
        } else {
          await this.$store.dispatch("updateItem", item)
        }

        this.closeDetails()
        this.$emit('show-alert', alertParams)
      } catch (e) {
        console.log(e)
      }
    },

    async createItem() {
      const item = {
        title: this.characteristicName,
        characteristic_value: this.values,
        check: false
      }

      const dataAlert = {
        name: localStorage.getItem('locale') === 'ru' ? 'характеристика' : 'характеристика',
        title: this.characteristicName,
        createdName: localStorage.getItem('locale') === 'ru' ? 'создана' : 'створена'
      }
      try {
        await this.$store.dispatch('createItem', item)
        this.closeDetails()
        await this.$store.dispatch('alertShow', dataAlert)
      } catch (e) {
        console.log(e)
      }
    },
    updateCharacteristic() {
      if (this.characteristic.id === 1) {
        if (this.colorData) {
          this.values = JSON.parse(JSON.stringify(this.colorData.characteristic_color_value))
        } else {
          this.values = JSON.parse(JSON.stringify(this.characteristic.characteristic_color_value))
        }
      } else if (this.characteristic.id === 2) {
        if (this.sizeData) {
          this.values = this.sizeData['characteristic_size_value']
        } else {
          this.values = this.characteristicEdit.characteristic_value
        }
      } else {
        this.values = this.characteristicEdit.characteristic_value || []
      }

      if (this.selectedSizes) {
        this.sizes = [...this.selectedSizes].map(selectedSize => {
          const fullSize = this.values.find(size => size.title === selectedSize.title)
          if (!fullSize) {
            return {
              title: selectedSize.title,
              // id: selectedSize.id,
              edit: true,
              check: true,
              type: null
            }
          } else {
            return {
              // id: selectedSize.id,
              title: selectedSize.title,
              edit: selectedSize.edit,
              check: fullSize.check,
              type: fullSize.type
            }
          }
        })
      } else {
        this.sizes = this.characteristicEdit['characteristic_size_value']
      }
    },
  },
  mounted() {
    this.characteristic = this.characteristicEdit;
    this.characteristicName = this.characteristicEdit?.title;
    this.characteristicCheckList = this.characteristicEdit?.characteristic_value.map(item => item.title);
    this.updateCharacteristic()
  },
}
</script>

<style scoped lang="scss">
.chip {
  cursor: pointer;
}

.sticky {
  position: sticky;
  bottom: 20px;
  display: flex;
  align-items: center;
  margin: auto 0 0 auto;
  width: 100%;
  background: #fff;

  .text {
    font-size: 13px;
    line-height: 15px;
    color: #7E7E7E;
    opacity: 0.5;
    max-width: 450px;
    display: inline-block;
  }
}

.input-label {
  margin-bottom: 13px;
}

.base-button {
  height: 40px !important;
  margin: 0 0 0 auto;
  width: 100%;
  max-width: 160px;
}

.item-form {
  padding: 0;
}

.used {
  margin-bottom: 25px;
}

input {
  &:disabled {
    border-bottom: none !important;
  }
}
</style>
<style lang="scss">
.size-grid {
  width: 100%;
  display: flex;
  padding-top: 20px;

  .size-item {
    &:last-child {
      margin-right: 0;

      .custom-size-content {
        display: flex;
        svg {
          cursor: pointer;
          &.disabled {
            opacity: .5;
            cursor: auto;
          }
        }

        input {
          margin-right: 10px;
        }
      }
    }

    &-title {
      font-size: 14px;
      color: #7E7E7E;
    }

    input {
      margin-bottom: 0;

      &:before {
        border-color: #9DCCFF;
      }

      &:after {
        background-color: #9DCCFF;
      }
    }

    label {
      font-weight: normal;
    }

    .list-group {
      list-style: none;
      padding: 0;
      margin: 0;

      li {
        height: 36px;
        border-bottom: 1px solid #E3F0FF;
        padding-left: 20px;
        padding-right: 20px;
      }
    }

    min-width: 0;
    max-width: 230px;
    width: 30%;
    margin-right: 40px;

    .size-item-content {
      width: 100%;
      background: #fff;
      border: 1px solid #E3F0FF;
      padding: 5px 0;
      border-radius: 10px;
      max-height: 158px;
    }
  }

}
</style>
