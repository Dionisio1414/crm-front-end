<template>
  <div class="item-form create-new-item create-new-item-first ">
    <v-row class="custom-row">
      <v-col cols="6" class="without-label-margin">
        <v-row>
          <v-col cols="12">
            <div class="item-name">

              <div class="label-title">
                {{ $t(`nomenclature.short_title`) }} *
                <span class="label-text"
                      v-if=" form.short_title && isValidFields.result && isValidFields.key === 'short_title'">
                      {{ isValidFields.massage }}
                </span>
                <span class="label-text"
                      v-if="mode === 'copy' || !isEdited && !isValidFields.result && !isValidFields.key === 'short_title'">
                        Измените наименование
                </span>
              </div>
              <input
                  type="text"
                  @keyup="onDouble"
                  :class="{'error-on-input': $v.form.short_title.$error }"
                  @blur="$v.form.short_title.$touch()"
                  @change="onValid('short_title', 'short_title')"
                  @input="emitFormData" v-model="form.short_title"
                  :placeholder="$t('nomenclature.name_placeholder')">
            </div>
          </v-col>
          <v-col cols="12">
            <div class="item-name ">
              <div class="label-title">{{ $t(`nomenclature.full_title`) }} <span
                  class="label-subtitle">({{ $t('nomenclature.in_document') }}) *</span>
              </div>
              <input type="text"
                     v-model="form.dock_title"
                     @change="onFullTitleBlur(form.dock_title)"
                     @input="emitFormData"
                     @blur="$v.form.dock_title.$touch()"
                     :class="{'error-on-input': $v.form.dock_title.$error }"
                     :placeholder="$t('nomenclature.name_placeholder')">
            </div>
          </v-col>
          <v-col cols="12">
            <div class="select-wrap" v-click-outside="onClickOutsideHead">
              <div class="item select-category nested-category ">
                <div class="label-title">
                  {{ $t(`nomenclature.category`) }}
                </div>
                <div class="input-wrap select-category-wrap"
                     :class="{'error-on-input':  $v.form.category_id.$error }"
                >
                  <input readonly
                         v-model="form.category_id"
                         @click="showDropHeadDown = !showDropHeadDown"
                         class="dropdown-trigger"

                         @focus="$v.form.category_id.$touch()"
                         @change="getCategoryTree(category)"
                         type="text"
                         :placeholder="$t('nomenclature.category_placeholder')">
                  <div class="category-tree-wrapper" @click="showDropHeadDown = !showDropHeadDown">
                    <category-tree
                        :categories="categoryTree"
                        @changeCategory="selectCategory"
                        editable
                    ></category-tree>
                  </div>
                  <v-btn class="open-drop-down" icon @click="showDropHeadDown = !showDropHeadDown">
                    <svg-sprite width="11" height="6" icon-id="arrowDown"></svg-sprite>
                  </v-btn>
                  <simplebar class="drop-down drop-down-categories" v-if="showDropHeadDown">
                    <v-treeview
                        open-all
                        activatable
                        item-text="title"
                        selection-type="independent"
                        :items="categories"
                    >
                      <template slot="label" slot-scope="{item}">
                        <div class="category-item"
                             :class="{'active': form.category_id === item.id}" @click="selectCategory(item)">
                          {{ item.title }}
                        </div>
                      </template>
                    </v-treeview>
                  </simplebar>
                </div>
              </div>
            </div>
          </v-col>
          <v-col cols="12">
            <div class="item nested-category" v-click-outside="onClickOutsideAdditional">
              <div class="label-title">
                <span>{{ $t('nomenclature.additional_category') }} <span
                    class="label-subtitle">({{ $t('nomenclature.on_site') }})</span></span>
                <span class="label-text" v-if="!$v.childGroupsTitle.isUnique && !$v.childGroupsTitle.$pending">{{
                    $t('validation.uniq')
                  }}</span>
              </div>
              <div class="input-wrap d-flex">
                <input
                    @focus="showDropDown = !showDropDown"
                    v-model.trim="$v.childGroupsTitle.$model"
                    type="text"
                    :placeholder="$t('nomenclature.additional_category_placeholder')">
                <v-btn class="open-drop-down" icon @click="showDropDown = !showDropDown">
                  <svg-sprite width="11" height="6" icon-id="arrowDown"></svg-sprite>
                </v-btn>
                <button
                    @click="createChildCategory"
                    :disabled="!childGroupsTitle || $v.childGroupsTitle.$error"
                    class="create-nested-category"
                >
                  <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                </button>
                <simplebar
                    v-if="showDropDown && !this.childGroupsTitle.length"
                    class="drop-down drop-down-categories">
                  <v-treeview
                      item-text="title"
                      selection-type="independent"
                      :items="categoriesItems"
                      item-disabled="disabled"
                      open-all
                      activatable
                  >
                    <template slot="label" slot-scope="{item}">
                      <div class="category-item" item-disabled="{item.id == 1}"
                           :active="!!form.categories.find(category => category.id === item.id)"
                           @click="selectChildCategory(item)">
                        {{ item.title }}
                      </div>
                    </template>
                  </v-treeview>
                </simplebar>
              </div>
            </div>
            <div class="values-list">
              <div class="value-item" v-for="(item, i) in children" :key="item.id">
                <span>{{ item.title }}</span>
                <span @click="deleteChildCategory(i)">
                   <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
                </span>
              </div>
            </div>
          </v-col>
          <v-col cols="6">
            <div class="item-name">
              <div class="label-title">
                {{ $t('nomenclature.sku') }}
                <span
                    v-if="isValidFields.result && isValidFields.key === 'sku'"
                    class="label-text">
                    {{ isValidFields.massage }}
                </span>
              </div>
              <input
                  type="text"
                  @input="emitFormData"
                  @keyup="onValid('sku', 'sku')"
                  v-model="form.sku"
                  :placeholder="$t('nomenclature.sku_placeholder')"
              >
            </div>
          </v-col>
          <v-col cols="6">
            <div class="item-name input-number-wrapper min-price">
              <div class="label-title">{{ $t('nomenclature.min_price') }}
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
                  <span>  {{ $t('nomenclature.min_price_tooltip') }} </span>
                </v-tooltip>
              </div>
              <input
                  @input="emitFormData"
                  type="number"
                  v-model="form.min_price"
                  :placeholder="$t('nomenclature.min_price_placeholder')"
              >
            </div>
          </v-col>
          <v-col cols="12">
            <v-row>
              <v-col cols="6">
                <div class="select-wrap">
                  <div class="label-title">{{ $t('nomenclature.price') }}</div>
                  <v-select
                      class="select-switcher"
                      :items="pricesTypes"
                      item-text="title"
                      item-value="id"
                      v-model="selectedPrice"
                      solo
                      dense
                      menu-props="p">
                  </v-select>
                </div>
              </v-col>
              <v-col cols="6">
                <div class="input-number-wrapper prices">
                  <input type="number" v-model="selectedPriceValue"
                         :placeholder="$t('nomenclature.price_placeholder')">
                  <button
                      @click="createChildPrice"
                      v-show="!lessThenMinPrice"
                      :disabled="!selectedPriceValue || !selectedPrice"
                  >
                    <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                  </button>
                  <v-tooltip
                      right
                      max-width="400px"
                      text-color="#7E7E7E"
                      content-class="gray-tooltip">
                    <template
                        v-slot:activator="{ on, attrs }">
                          <span
                              v-bind="attrs"
                              class="disabled-span"
                              v-show="lessThenMinPrice"
                              disabled
                              v-on="on"
                          >
                            <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                          </span>
                    </template>
                    <span>  {{ $t('nomenclature.min_price_disabled_tooltip') }} </span>
                  </v-tooltip>
                </div>
              </v-col>
              <div class="values-list prices-values">
                <div class="value-item" v-for="(item, i) in selectedPrices" :key="item.id">
                  <span>{{ item.title }}</span>
                  <span>{{ item.value }}</span>
                  <span @click="deleteChildPrice(i)">
                    <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
                  </span>
                </div>
              </div>
            </v-row>
          </v-col>
          <v-col cols="6">
            <div class="item">
              <div class="label-title">{{ $t('nomenclature.unit') }}</div>
            </div>
            <div class="select-wrap">
              <v-select
                  class="select-switcher"
                  :items="units"
                  item-text="title"
                  item-value="id"
                  v-model="form.unit_id"
                  solo
                  dense
                  menu-props="units"
                  @change="emitFormData"
              >
              </v-select>
            </div>
          </v-col>
          <v-col cols="6" v-if="isProduct">
            <div class="item checkbox-wrapper">
              <div class="checkbox">
                <label class="checkbox-label checkbox-label-price">
                  <input :checked="form.packing || packing" @click="onClickPacking" type="checkbox"
                         value="">
                  <div class="checkbox-text"></div>
                  <div class="label-title">{{ $t('nomenclature.packing') }}</div>
                </label>
              </div>
              <input v-if="packing || form.packing" type="text" @change="emitFormData"
                     v-model="form.packing"
                     placeholder="00">
              <input v-else disabled type="text">
            </div>
          </v-col>
          <v-col cols="6" v-if="isProduct">
            <div class="item-name input-number-wrapper">
              <div class="label-title">{{ $t('nomenclature.lower_limit') }}</div>
              <input
                  @change="emitFormData"
                  type="number"
                  v-model="form.lower_limit"
                  :placeholder="$t('nomenclature.lower_limit_placeholder')"
              >
            </div>
          </v-col>
          <v-col cols="6">
            <div class="select-wrap">
              <div class="label-title">{{ $t('nomenclature.supplier') }}</div>
              <v-select
                  class="select-switcher"
                  @change="emitFormData"
                  :items="suppliers"
                  item-text="title"
                  no-data-text="Создайте поставщика"
                  v-model="form.supplier_id"
                  item-value="id"
                  solo
                  dense
                  menu-props="units"
                  :placeholder="$t('nomenclature.supplier_placeholder')"
              >
              </v-select>
            </div>
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="6">
        <v-row>
          <v-col cols="12" v-if="isProduct">
            <div class="item-name photos">
              <div class="label-title">
                {{ $t('nomenclature.photos') }}
                <span class="photos-quantity"><span>{{ files && files.length }}</span>/10</span>
                <button type="button" class="remove-all" @click="removeAllFiles" v-if="files && files.length">Удалить все</button>
              </div>
              <div class="photos-container">
                <div class="photo-items" v-if="files && files.length">
                  <div class="photo-item" v-for="(val, index) in files || []" :key="index" :style="`background-image: url(${val.url})`">
                     <button type="button" class="photo-remove" @click="removePhoto(index)">
                       <svg-sprite width="12" height="12" removes></svg-sprite>
                    </button>
                  </div>
                </div>
                <div class="photos-input-trigger" @click="filesManagerHandler">
                  Добавить <br> {{ $t('nomenclature.photos') }}
                </div>
              </div>
            </div>
          </v-col>
          <!-- <v-col cols="9" v-if="isProduct"></v-col> -->
          <v-col cols="3" v-if="isProduct">
            <div class="item-name file">
              <div class="label-title">{{ $t('nomenclature.files') }}</div>
              <div class="file-input-trigger">
                Добавить {{ $t('nomenclature.files') }}
              </div>
            </div>
          </v-col>
          <v-col cols="12">
            <div class="item-name">
              <div class="label-title">{{ $t('nomenclature.desc') }}</div>
              <textarea @input="emitFormData" v-model="form.desc"></textarea>
            </div>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <files-manager
      v-if="isOpen"
      :isOpen="isOpen"
      :limit="10"
      typeField="checkbox"
      @closeFilesManager="closeFilesManager"
      @image="getImage"
      @selectItems="selectItems"
    >
    </files-manager>
  </div>
</template>
<script>
// vuex
import {eventBus} from "@/main";
import {mapGetters, mapActions} from 'vuex';
// components
import CategoryTree from "@/components/dashboard/products/nomenclature/modals/froms/CategoryTree";
// validates
import {required} from 'vuelidate/lib/validators'
// constants
import {TABLE_ACTIONS, NOMENCLATURE_RESOURCES} from "@/constants/constants";
// services
import {httpClient} from "@/services";
// Files manager
import FilesManager from '@/components/ui/FilesManager/FilesManager'
import removeDuplicates from "@/helper/removeDuplicates";

export default {
  name: "GoodsFormFirst",
  components: {CategoryTree, FilesManager},
  data() {
    return {
      isOpen: false,
      packing: false,
      categoryTree: [],
      isEdited: false,
      children: [],
      childGroupsTitle: '',
      showDropDown: false,
      showDropHeadDown: false,
      selectedPrice: '',
      parents: [],
      selectedPriceValue: null,
      double: true,
      prices: [],
      files: [],
      filesId: [],
      form: {
        desc: null,
        category_id: null,
        categories: [],
        min_price: null,
        short_title: null,
        dock_title: null,
        sku: null,
        lower_limit: null,
        unit_id: 1,
        supplier_id: null,
        prices: [],
        packing: null
      },
    }
  },
  props: {
    resource: Object,
    categories: Array,
    category: Object,
    data: Object,
    mode: String,
    units: Array,
    pricesTypes: Array,
    countries: Array,
    suppliers: Array
  },
  validations: {
    form: {
      category_id: {required},
      short_title: {required},
      dock_title: {required},
    },
    childGroupsTitle: {
      required,
      async isUnique(value) {
        if (!value) {
          return true
        }
        const params = {
          title: value
        }
        const {data} = await httpClient.post('/products/categories/store-async-validations', {validate: {...params}});
        return new Promise((resolve) => resolve(!data.status))
      }
    }
  },
  created() {
    this.selectedPrice = this.pricesTypes[0].id
    if (this.mode === TABLE_ACTIONS.CREATE) {
      this.unit_id = this.units[0].id
    }
    if (this.mode === TABLE_ACTIONS.UPDATE || this.mode === TABLE_ACTIONS.COPY) {
      this.double = false
      Object.keys(this.form).forEach((item => this.form[item] = this.data[item]))

      this.children = this.form.categories
      this.form.category_id = this.data.category.id
      this.files = this.data.images ?? [];
      this.filesId = this.data.images || [].map(item => ({ id: item.id }));
      this.$emit('changeCategory', this.form.category_id)
    }
    if (this.mode === TABLE_ACTIONS.COPY) {
      this.onValid('short_title', 'short_title')
    }
  },
  computed: {
    ...mapGetters({
      isValidFields: 'nomenclatureIsValidFields'
    }),
    isProduct() {
      return this.resource.SINGLE_VALUE === NOMENCLATURE_RESOURCES.PRODUCTS.SINGLE_VALUE
    },
    categoriesItems() {
      return this.getNodes(this.freeCategories, this.children)
    },
    freeCategories() {
      // return [...this.categories].filter(category => category.id !== this.form.category_id)
      return [...this.categories]
    },
    selectedPrices() {
      if (this.form.prices) {
        if (this.mode === 'create') {
          return [...this.form.prices].map(item => {
            return {
              title: this.pricesTypes.find(price => price.id === item.id).title,
              value: item.value
            }
          })
        } else {
          return this.form.prices
        }
      }
      return false
    },
    lessThenMinPrice() {
      if (this.selectedPrice) {
        const price = this.pricesTypes.find(item => item.id === +this.selectedPrice) || null
        const result = !price.is_buy ? +this.selectedPriceValue < this.form.min_price : false
        return result
      }
      return false
    }
  },
  mounted() {
    eventBus.$on('validate-nomenclature-form', () => {
      this.$v.form.$touch()
    });
  },
  watch: {
    category() {
      if (this.category) {
        this.categoryTree = []
        this.getCategoryTree(this.category)
        this.form.unit_id = this.category.unit_id || 1
      }
    },
  },
  methods: {
    ...mapActions({
      addNewCategory: 'addNewCategory',
      onValidAction: 'nomenclatureValidation',
    }),
    removeAllFiles() {
      this.files = []
      this.filesId = []
      this.emitFormData()
    },
    removePhoto(index) {
      this.files.splice(index, 1)
      this.filesId.splice(index, 1)
      this.emitFormData()
    },
    selectItems(files, ids) {
      console.log(files, ids)
      this.files = [ ...this.files, ...files]
      this.filesId = [ ...this.filesId, ...ids ]
      this.emitFormData()
    },
    filesManagerHandler() {
      this.isOpen = true
    },
    closeFilesManager() {
      this.isOpen = false
    },
    getImage(params) {
      this.files.push({ url: params.successUrl, id: params.data })
      this.filesId.push({ id: params.data })
      this.emitFormData()
      this.closeFilesManager()
    },
    onClickPacking() {
      this.packing = !this.packing
      this.form.packing = null
    },
    emitFormData() {
      this.$emit('stepUpdated', {
        data: { ...this.form, files: this.filesId },
        isValid: !this.$v.form.$invalid && !this.isValidFields.result,
        resource: this.resource.SINGLE_VALUE,
        title: 'main_info'
      })
    },
    onDouble() {
      this.onValid('short_title', 'short_title')
      this.isEdited = true
      if (this.double) {
        this.form.dock_title = this.form.short_title
      }
    },
    onValid(invalidKey, key) {
      let mode
      const resource = 'products'
      if (this.mode === 'create' || this.mode === TABLE_ACTIONS.COPY) {
        mode = 'store'
      }
      if (this.mode === 'edit') {
        mode = 'update'
      }
      if (this.form[key]) {
        const body = {
          validate: {[`${invalidKey}`]: this.form[key]}
        }
        this.onValidAction({body, mode, resource, key: invalidKey}).then(() => {
          this.emitFormData()
        })
        this.$v.form[key] && this.$v.form[key].$touch()
      }
    },
    onFullTitleBlur(val) {
      this.double = !val
    },
    onClickOutsideHead() {
      this.showDropHeadDown = false
    },
    onClickOutsideAdditional() {
      this.showDropDown = false
    },
    getCategoryTree(category) {
      this.emitFormData()
      this.categoryTree.push({title: category.title, id: category.id})
      if (category.parent !== null) {
        this.getCategoryTree(category.parent)
      } else
        return false
    },
    getNodes(items) {
      return items.map(item => {
        const disabled = this.form.categories.find(({id}) => id === item.id)
        return {
          id: item.id,
          title: item.title,
          disabled: Boolean(disabled),
          children: item.children ? this.getNodes(item.children) : []
        }
      })
    },
    async createChildCategory() {
      const newCategory = {
        title: this.childGroupsTitle
      }

      try {
        const category = await this.addNewCategory(newCategory);

        if (this.childGroupsTitle.length > 3) {
          const copyChildren = this.children.map(item => ({...item}))
          copyChildren.unshift(category)
          this.children = removeDuplicates(copyChildren)
          this.form.categories.unshift(category)
          this.childGroupsTitle = ''
          this.showDropDown = false
        }
      } catch (e) {
        console.log(e);
      }
    },
    createChildPrice() {
      if (this.selectedPrice && this.selectedPriceValue) {
        const item = {
          ...this.pricesTypes.find(price => price.id === +this.selectedPrice),
          value: this.selectedPriceValue,
        }
        const index = this.form.prices.findIndex(price => price.id === item.id)
        if (index !== -1) {
          this.form.prices.splice(index, 1, item)
        } else {
          this.form.prices.push(item)
        }
        this.selectedPrice = this.pricesTypes[0].id
        this.selectedPriceValue = ''
        this.emitFormData()
      }
    },
    validateForm() {
      this.$v.from.$touch()
    },
    deleteChildCategory(index) {
      this.children.splice(index, 1)
      this.form.categories.splice(index, 1)
      this.emitFormData()
    },
    deleteChildPrice(index) {
      this.form.prices.splice(index, 1)
      this.emitFormData()
    },
    selectChildCategory(item) {
      if (item.disabled) {
        return false
      } else {
        this.children.unshift(item);
        this.form.categories.unshift(item);
        this.showDropDown = false;
      }
    },
    selectCategory(item) {
      this.form.category_id = item.id
      this.showDropHeadDown = false
      const index = this.children.findIndex(child => child.id === item.id)
      if (index !== -1) {
        this.children.splice(index, 1)
      }
      this.emitFormData()
      this.$emit('changeCategory', this.form.category_id)
    },

  }
}
</script>

<style scoped lang="scss">
.checkbox-label-price {
  width: max-content;
}

.create-new-item {
  padding: 0 !important;

  .min-price {
    justify-content: center;
    align-items: flex-start;
    position: relative;

    svg {
      position: absolute;
      right: 12px;
      top: -3px;
    }
  }

  .without-label-margin {

    .item-name {
      .label-title {
        line-height: 1 !important;
      }
    }

    .label-title {
      line-height: 1 !important;
      margin-bottom: 10px;
    }
  }
}

.label-subtitle {
  color: #9DCCFF;
}

.custom-row {
  align-items: flex-start !important;
}

.label-text {
  text-transform: initial;
  font-size: 12px;
  line-height: 12px;
  float: right;
  color: #FF9F2F;
  font-weight: 400;
}

.checkbox-wrapper {
  input {
    min-height: 23px;
  }
}

.category-tree-wrapper {
  position: absolute;
  bottom: 1px;
  z-index: 2;
  background-color: white;
}

.lang-switch {
  display: inline-flex;
  justify-content: flex-start;
  float: right;

  .lang-btn {
    width: 40px;
    height: 20px;
    background-color: transparent;
    border: 1px solid #E6F3FD;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    margin-right: 10px;

    &:last-child {
      margin-right: 0;
    }

    &.active {
      background-color: #BBDBFE;
    }
  }
}

.checkbox-wrapper {
  .checkbox {
    display: block;
  }

  .checkbox-text {
    height: 16px;
  }
}

.values-list {
  margin-bottom: 0;

  &.prices-values {
    padding-left: 17px;
  }

  .value-item {
    margin: 10px 10px 0 0;
  }
}

.select-category {
  .open-drop-down {
    position: absolute;
    right: 0;
    top: -10px;
  }

  input {
    color: transparent;
  }

  .dropdown-trigger {
    border-bottom: 1px solid #9DCCFF;

    &.error-on-input {
      border-bottom: 1px solid #FF9F2F;
    }
  }

  .error-on-input {
    border-bottom: 1px solid #FF9F2F !important;
  }
}

.photos {
  .remove-all {
    font-size: 13px;
    font-weight: 400;
    line-height: 1;
    text-decoration: underline;
    margin-left: auto;
    color: #9DCCFF;
  }
  .label-title {
    display: flex;
  }
  &-container {
    display: flex;
    .photo-items {
      display: flex;
      align-items: center;
      flex-wrap: nowrap;
      padding-bottom: 11px;
      overflow-y: hidden;
      overflow-x: auto;
      max-width: 460px;
      margin-right: 10px;
      &::-webkit-scrollbar {
        width: 2px;
        height: 2px;
      }
      &::-webkit-scrollbar-track {
        background: #E3F0FF;
      }
      &::-webkit-scrollbar-thumb {
        background: #9DCCFF;
      }
    }
    .photo-item {
      position: relative;
      background-color: #F4F9FF;
      background-size: contain;
      background-position: center center;
      border-radius: 10px;
      width: 108px;
      min-width: 108px;
      height: 80px;
      &:not(:last-child) {
        margin-right: 10px;
      }
      .photo-remove {
        position: absolute;
        top: 10px;
        right: 10px;
      }
    }
  }

  .label-title {
    margin-bottom: 16px;
  }

  &-quantity {
    margin-left: 20px;
  }

  &-input-trigger {
    width: 101.42px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    height: 80px;
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;
    font-size: 13px;
    color: #9DCCFF;
    cursor: pointer;

    &:hover {
      color: #E3F0FF;
      background: #9DCCFF;
    }
  }
}

.item {
  &.checkbox-wrapper {
    input {
      padding: 0;
    }
  }
}

.file {
  .label-title {
    margin-bottom: 18px;
  }

  &-input-trigger {
    width: 160px;
    text-align: center;
    padding: 6px 0;
    border: 1px solid #E3F0FF;
    display: flex;
    min-height: 36px;
    align-items: center;
    justify-content: center;
    border-radius: 32.5652px;
    font-size: 13px;
    color: #9DCCFF;
    cursor: pointer;

    &:hover {
      color: #E3F0FF;
      background: #9DCCFF;
    }
  }

}

.disabled-span {
  opacity: .5;
}

button {
  &:disabled {
    opacity: .5;
  }
}

.item-name {
  textarea {
    height: 223px;
  }
}

.item-form {
  margin-right: -50px;
}

.category-item {
  font-weight: 400;
  font-size: 14px;
  line-height: 14px;
  cursor: pointer;
  color: #7E7E7E !important;

  &[active="true"] {
    color: #FF9F2F !important;
  }

  &.active {
    color: #FF9F2F !important;
  }
}


</style>
