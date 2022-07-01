<template>
  <div class="dialog-wrap view-nomenclature">
    <v-dialog
        v-model="dialog"
        max-width="930px"
        class="dialog-large dialog-category"
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M19.35 6.04C19.0141 4.33772 18.0976 2.80486 16.7571 1.70325C15.4165 0.601633 13.7351 -0.000392242 12 1.91737e-07C9.11 1.91737e-07 6.6 1.64 5.35 4.04C3.88023 4.19883 2.52101 4.89521 1.53349 5.99532C0.545971 7.09543 -0.000171702 8.52168 4.04928e-08 10C4.04928e-08 13.31 2.69 16 6 16H19C21.76 16 24 13.76 24 11C24 8.36 21.95 6.22 19.35 6.04ZM10 13L6.5 9.5L7.91 8.09L10 10.17L15.18 5L16.59 6.41L10 13Z"
                  fill="white"/>
            </svg>
            {{ form.short_title }}
            <span class="header-text-small">022154562</span>
          </div>
          <div class="dialog-header-actions">
            <span class="view-nomenclature-date">{{ form.updated_at | formatDate }}</span>
            <v-btn icon color="#5893D4">
              <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                      fill="#BBDBFE"/>
              </svg>
            </v-btn>
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="close()">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                    fill="#BBDBFE"/>
              </svg>
            </v-btn>
          </div>
        </div>
        <div class="dialog-content dialog-content-large">
          <div class="view-nomenclature-header" v-if="mode !== 'create'">
            <div class="view-nomenclature-header-left">
              <div class="view-nomenclature-header-switcher">
                <span :class="{'active': !form.is_visible}" @click="toggleVisible(false)"
                      class="switch-label">Скрыть</span>
                <div class="switcher" :class="{'active' : form.is_visible}" @click="toggleVisible(!form.is_visible)">
                  <div class="switcher-caret"></div>
                </div>
                <span :class="{'active': form.is_visible}" @click="toggleVisible(true)" class="switch-label">Показать на сайте</span>
              </div>
            </div>
            <div class="view-nomenclature-header-right">
              <ul class="view-nomenclature-header-actions">
                <li class="view-nomenclature-header-action" v-if="form.is_groups && mode === 'edit'"
                    @click="onCLickProductsCases">Варианты товара
                </li>
                <li class="view-nomenclature-header-action" style="display: none">
                  <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14.3083 3.97021V0H2.69167V3.97021C1.96595 4.0074 1.28218 4.32166 0.781361 4.84818C0.280541 5.3747 0.000861864 6.07333 0 6.8V13.6H2.40833V12.4667H1.13333V6.8C1.13384 6.34929 1.31311 5.91718 1.63181 5.59848C1.95051 5.27978 2.38262 5.10051 2.83333 5.1H14.1667C14.6174 5.10051 15.0495 5.27978 15.3682 5.59848C15.6869 5.91718 15.8662 6.34929 15.8667 6.8V12.4667H14.3083V13.6H17V6.8C16.9991 6.07333 16.7195 5.3747 16.2186 4.84818C15.7178 4.32166 15.034 4.0074 14.3083 3.97021ZM13.175 3.96667H3.825V1.13333H13.175V3.96667Z"
                        fill="#9DCCFF"/>
                    <path d="M13.458 6.51758H14.5913V7.65091H13.458V6.51758Z" fill="#9DCCFF"/>
                    <path
                        d="M3.54167 8.7832H2.125V9.91654H3.54167V16.9999H13.175V9.91654H14.5917V8.7832H3.54167ZM12.0417 15.8665H4.675V9.91654H12.0417V15.8665Z"
                        fill="#9DCCFF"/>
                  </svg>
                  Печать
                  <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580386 1.1228 -1.94744e-07 0.961732 -1.9792e-07C0.800489 -2.01099e-07 0.649442 0.0580386 0.536044 0.163156L0.17573 0.497855C0.0624218 0.603055 8.86371e-09 0.743295 1.06479e-08 0.892917C1.24322e-08 1.04254 0.0624218 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z"
                        fill="#9DCCFF"/>
                  </svg>
                </li>
                <li class="view-nomenclature-header-action">
                  <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.43975 2.17531L4.83146 6.37332L0.0157811 6.39179L0 10.6765L4.81463 10.6562L2.38995 14.8715L6.05954 17L8.48316 12.7829L10.8749 16.9809L14.5602 14.8247L12.1685 10.6267L16.9842 10.6082L17 6.32345L12.1854 6.34377L14.6101 2.12853L10.9405 0L8.51684 4.21709L6.12513 0.0190841L2.43975 2.17531ZM4.37114 2.69037L5.5996 1.97163L8.5062 7.07339L11.4508 1.94941L12.673 2.65727L9.72846 7.78126L15.5796 7.75904L15.5734 9.18565L9.72226 9.20787L12.6289 14.3096L11.4004 15.0284L8.4938 9.92661L5.54922 15.0506L4.32696 14.3427L7.27154 9.21874L1.42036 9.24096L1.42656 7.81436L7.27774 7.79214L4.37114 2.69037Z"
                        fill="#9DCCFF"/>
                  </svg>
                  Другое
                  <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580386 1.1228 -1.94744e-07 0.961732 -1.9792e-07C0.800489 -2.01099e-07 0.649442 0.0580386 0.536044 0.163156L0.17573 0.497855C0.0624218 0.603055 8.86371e-09 0.743295 1.06479e-08 0.892917C1.24322e-08 1.04254 0.0624218 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z"
                        fill="#9DCCFF"/>
                  </svg>
                </li>
              </ul>
            </div>
          </div>
          <simplebar class="view-nomenclature-main">
            <div class="item-form">
              <div class="item-name" v-for="title in form.dock_title" :key="title.code">
                <div class="label-title">полное наименование <span class="label-title__subtitle">(отображение в документе укр {{
                    title.code
                  }})</span>
                </div>
                <div class="item-value">{{ title.title }}</div>
              </div>
              <div class="item-name">
                <div class="label-title">Категория</div>
                <div class="item-value">
                  <category-tree :editable="false" :categories="categoryTree"></category-tree>
                </div>
              </div>
              <div class="item-name">
                <div class="label-title">дополнительные категории товаров <span class="label-title__subtitle">(отображение на сайте)</span>
                </div>
                <div class="values-list">
                  <div class="value-item" v-for="(item) in form.categories" :key="item.id">
                    <span>{{ item.title }}</span>
                  </div>
                </div>
              </div>
              <v-row>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">артикул</div>
                    <div class="item-value">{{ form.sku }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">штрих-код</div>
                    <div class="item-value">{{ form.barcode }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">штрих-код поставщика</div>
                    <div class="item-value">{{ form.barcode_supplier }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table"></v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">единица измерения</div>
                    <div class="item-value">{{ form.units }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">упаковка</div>
                    <div class="item-value">{{ form.packing }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">Вес</div>
                    <div class="item-value">{{ form.weight }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">объем</div>
                    <div class="item-value">{{ form.volume }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">поставщик</div>
                    <div class="item-value">{{ form.barcode_supplier }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">нижняя граница остатка</div>
                    <div class="item-value">{{ form.lower_limit }}</div>
                  </div>
                </v-col>
                <v-col cols="3" class="info-table">
                  <div class="item-name">
                    <div class="label-title">минимальная цена товара</div>
                    <div class="item-value">{{ form.min_price }}</div>
                  </div>
                </v-col>
              </v-row>
              <div class="item-name">
                <div class="label-title">Фото</div>
              </div>
              <div class="item-name">
                <div class="label-title">Файлы</div>
              </div>
              <div class="item-name">
                <div class="label-title">описание</div>
                <p class="item-value">
                  {{ form.desc }}
                </p>
              </div>
              <div class="item-name ">
                <div class="label-title">Характеристики товара</div>
                <div class="values-list">
                  <div class="value-item head-characteristic" v-if="baseCharacteristic">
                    <span>{{ baseCharacteristic.title }}</span>
                  </div>
<!--                  <div class="value-item" v-if="colorCharacteristic"-->
<!--                       :class="{'head-characteristic' : colorCharacteristic.is_base}">-->
<!--                    <span>{{ colorCharacteristic.title }}{{ colorCharacteristic.is_base }}</span>-->
<!--                  </div>-->
                  <div class="value-item" :class="{'head-characteristic': item.is_base }"
                       v-for="(item) in selectedCharacteristics" :key="item.id">
                    <span>{{ item.title }} {{ item.is_base }}</span>
                  </div>
                </div>
              </div>
              <div class="item-name">
                <div class="label-title">Свойства товара</div>
                <view-property @change-value="onChangeProperty" v-for="property in selectedProperties"
                               :property_value="property"
                               :key="property.id"></view-property>
              </div>
              <div class="item-name date-item">
                <div class="label-title">Дата создания</div>
                <div class="item-value">{{ form.created_at | formatDate }}</div>
              </div>
            </div>
          </simplebar>
        </div>

        <div class="dialog-content dialog-content-large">
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="close()"
            >
              Отменить
            </v-btn>

            <v-btn
                v-if="mode === 'create'"
                class="popup-btn"
                color="#5893D4"
                dark
                @click="save"
            >
              Сохранить
            </v-btn>
            <v-btn
                v-else
                class="popup-btn"
                color="#5893D4"
                dark
                @click="toEdit"
            >
              Редактировать
            </v-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import ViewProperty from "@/components/dashboard/products/nomenclature/modals/froms/ViewProperty";
import CategoryTree from "@/components/dashboard/products/nomenclature/modals/froms/CategoryTree";

export default {
  name: "ViewProduct",
  components: {CategoryTree, ViewProperty},
  data() {
    return {
      dialog: false,
      categoryTree: [],
      mode: '',
      form: {
        characteristic_color_value: null,
        base_characteristic_value: null,
        characteristic_value: [],
        is_visible: false
      }
    }
  },
  computed: {
    ...mapGetters({
      product: 'product',
      properties: 'properties',
      characteristicsArr: 'characteristics',
      colorData: 'colorData',
      sizeData: 'sizeData',


    }),
    baseCharacteristic() {
      if (this.mode === 'create' && this.form.base_characteristic_value) {
        let characteristic
        characteristic = {...this.characteristics.find(item => item.id === this.form.base_characteristic_value.id)}
        return characteristic
      }
      if (this.mode === 'edit' || this.mode === 'copy') {
        return this.form.base_characteristic_value
        // let baseCharacteristic = this.form.characteristic_value.find(item => !!item.is_base)
        // if (!baseCharacteristic && this.form.characteristic_color_value && this.form.characteristic_color_value.is_base) {
        //   baseCharacteristic = this.form.characteristic_color_value
        //   baseCharacteristic.is_color = true
        // }
        //
        // return baseCharacteristic ? {...this.characteristics.find(item => item.id === baseCharacteristic.id)} : null

      }

      return null
    },
    colorCharacteristic() {

      if (this.form.characteristic_color_value) {
        let characteristic
        characteristic = {...this.characteristics.find(item => item.id === this.form.characteristic_color_value.id)}
        characteristic.is_base = this.form.characteristic_color_value.is_base
        return characteristic
      }
      return false
    },
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
    characteristics() {
      return [].concat(this.characteristicsArr, this.colorData, this.sizeData)

    },
    selectedCharacteristics() {
      if (this.form.characteristic_value) {
        return [...this.form.characteristic_value].map((stat) => {
          if (stat.is_base) {
            this.form.base_characteristic_value = {...stat}
            return false
          } else {
            return this.characteristics.find(fullStat => fullStat.id === stat.id)
          }
        })
      }
      return []
    }
  },
  props: ['category', 'isGroup'],
  methods: {
    ...mapActions({
      fetchProperties: 'fetchCategoriesProperties',
      fetchCategoriesCharacteristics: 'fetchCategoriesCharacteristics',
      createItem: 'createProduct',
      createGroup: 'createGroupProduct',
      fetchData: 'fetchData'
    }),
    onCLickProductsCases() {
      const baseCharacteristic = {
        id: this.baseCharacteristic.id,
        values: []
      }
      const characteristics = [...this.selectedCharacteristics].map(char => {
        return {
          id: char.id,
          values: []
        }
      })
      const generateData = {
        id: this.form.id,
        name: this.form.short_title,
        sku: this.form.sku || '',
        prices: this.form.price,
        characteristic_value: characteristics,
        base_characteristic_value: baseCharacteristic,
      }

      this.$emit('cLickProductsCases', generateData)
    },
    open(itemData, mode) {
      this.mode = mode
      this.categoryTree = []
      if (mode === 'create') {
        this.getCategoryTree(this.category)
        this.form = itemData
        this.dialog = true
      } else {
        this.categoryTree = []
        this.getCategoryTree(this.product.category)
        this.form = {...this.product}
        this.dialog = true
        this.form.characteristic_value.forEach((item, index) => {
          if (item.is_base) {
            this.form.base_characteristic_value = {...item}
            this.form.characteristic_value.splice(index, 1)
          }
        })
        if (itemData.characteristic_color_value && itemData.characteristic_color_value.is_base) {
          this.form.base_characteristic_value = {...itemData.characteristic_color_value}
          this.form.characteristic_color_value = null
        }
        if (itemData.characteristic_color_value && !itemData.characteristic_color_value.is_base) {
          this.form.characteristic_value.push(itemData.characteristic_color_value)
        }
        console.log(this.form);
      }

    },
    close() {
      this.dialog = false
    },
    toggleVisible(val) {
      this.form.is_visible = val
    },
    onChangeProperty(params) {
      console.log(params);
    },
    toEdit() {
      const data = JSON.parse(JSON.stringify(this.form))
      console.log(data);
      this.$emit('edit', data)
    },
    save() {
      this.isGroup ? this.saveGroup() : this.saveProduct()

    },
    statsValues(characteristic) {
      if (characteristic) {
        return characteristic.characteristic_color_value || characteristic.characteristic_value.concat(characteristic.characteristic_size_value || [])
      }
      return []
    },
    saveProduct() {
      const data = JSON.parse(JSON.stringify(this.form))
      const statsValuesArr = []
      if (data.base_characteristic_value) {
        data.base_characteristic_value = {
          is_color: data.base_characteristic_value.is_color,
          ids: data.base_characteristic_value.values
        }
      }
      const color = data.characteristic_value.find(char => char.id === 1) || null
      data.characteristic_color_value = color ? [...color.values] : null
      data.characteristic_value = data.characteristic_value.filter((item) => item.id !== 1)
      data.characteristic_value.forEach((item) => {
        item.values.forEach(val => statsValuesArr.push({...val}))
      })
      data.characteristic_value = statsValuesArr
      data.property_value = data.property_value.map(item => {
        return {id: item.id}
      })
      this.createItem({data}).then(() => {
        this.$emit('create', {mode: 'product', data})
        this.close()
      })
    },
    saveGroup() {
      const data = JSON.parse(JSON.stringify(this.form))
      data.base_characteristic = {
        ids: [{
          id: data.base_characteristic_value.id
        }]
      }
      data.characteristic = [...data.characteristic_value].map(char => {
        return {id: char.id}
      })
      if (data.characteristic_color_value) {
        data.characteristic.push({id: 1})
      }
      delete data.characteristic_value
      delete data.base_characteristic_value
      delete data.characteristic_color_value
      this.createGroup({data}).then((id) => {
        let generateData = {
          id,
          name: this.form.short_title,
          sku: this.form.sku || '',
          prices: this.form.price,
          characteristic_value: this.form.characteristic_value,
          base_characteristic_value: this.form.base_characteristic_value,
        }
        if (generateData.characteristic_value.length) {
          generateData.characteristic_value.forEach(item => {
            item.values = []
          })
        }
        if (this.form.characteristic_color_value) {
          generateData.characteristic_value.push(this.form.characteristic_color_value)
        }
        generateData.base_characteristic_value.values = []
        this.$emit('create', {mode: 'group', data: generateData})
        this.close()
      })
    },
    getCategoryTree(category) {
      this.categoryTree.push({title: category.title, id: category.id})
      if (category.parent) {
        this.getCategoryTree(category.parent)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.header-text-small {
  font-size: 15px;
  font-weight: 400;
}

.view-nomenclature {
  &-date {
    font-size: 13px;
  }
}

.info-table {
  padding-top: 0;
  padding-bottom: 0;
  margin-bottom: 35px;

  .label-title {
    margin-bottom: 0;
  }

  .item-name {
    padding-left: 10px;
    border-left: 2px solid #9DCCFF;
    height: 52px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-wrap: nowrap;
  }

  .item-value {
    font-size: 14px;
  }
}

.header-text {
  &-small {
    margin-left: 20px;
  }
}


.label-title__subtitle {
  color: #9DCCFF;
}

.view-nomenclature-header {
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
  padding: 20px 30px;
  width: 100%;
  display: flex;
  justify-content: space-between;

  &-action {
    padding: 7px 15px;
    border-right: 1px solid #E3F0FF;
    font-size: 14px;
    line-height: 14px;
    color: #5893D4;
    display: flex;
    align-items: center;
    min-width: 130px;
    justify-content: space-between;

    &:last-child {
      border-right: none;
      padding-right: 0;
    }

  }

  &-actions {
    display: flex;
    list-style: none;
  }

  &-switcher {
    display: flex;
    align-items: center;

    .switch-label {
      font-size: 13px;
      line-height: 13px;
      color: #9DCCFF;
      cursor: pointer;

      &.active {
        color: #5893D4;
      }

      &:first-child {
        margin-right: 11px;
      }

      &:last-child {
        margin-left: 11px;
      }
    }
  }
}

.item-value {
  font-weight: 400;
  font-size: 15px;
  line-height: 15px;
  color: #7E7E7E
}

.switcher {
  width: 33px;
  height: 18px;
  border: 1px solid #7CE1A4;
  box-sizing: border-box;
  border-radius: 50px;
  position: relative;

  &-caret {
    width: 12px;
    height: 12px;
    background: #4ECA80;
    border-radius: 50%;
    position: absolute;
    left: 5%;
    top: 50%;
    transform: translateY(-50%);
    transition: all .5s;
  }

  &.active {
    .switcher-caret {
      transform: translateY(-50%) translateX(16px);
      background: #4ECA80;
      border: 1px solid #7CE1A4;
    }
  }
}

.view-nomenclature-main {
  width: 100%;
  max-height: 461px;
  overflow: auto;
  padding-top: 30px;

  .item-form {
    .item-name {
      margin-bottom: 22px;
    }
  }
}

.values-list {
  .value-item {
    background-color: #F4F9FF;
  }

  .head-characteristic {
    background: #E3F0FF;
  }
}


.date-item {
  display: flex;
  padding-top: 20px;

  .label-title {
    width: 274px;
  }

  .item-value {
    padding-left: 15px;
    width: calc(100% - 274px)
  }
}

</style>