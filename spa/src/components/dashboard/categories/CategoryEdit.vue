<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialogCreateCategory"
        max-width="1230px"
        class="dialog-large dialog-category"
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <span>Редактирование категории</span>
          </div>
          <progress-bar
              :progressList="getProgressList"
              isToolTip
          ></progress-bar>
          <header-actions is-hide-attach @updateClose="close"></header-actions>
        </div>
        <div class="dialog-content dialog-content-large category-modal" v-if="dialogCreateCategory">
          <form class="item-form">
            <v-row>
              <v-col cols="6">
                <nested-groups
                    cols="12"
                    :parentId="parent_id"
                    :reset-id="resetId"
                    groupTitle="category"
                    :suppliersGroups="items"
                    :flat-list="flatCategoriesList"
                    :groupName="groupName"
                    @updateGroupId="onGroupId"
                >
                  <button v-if="parent_id" class="clear" @click="onReset">{{ $t('page.suppliers.modal.createGroups.reset') }}</button>
                </nested-groups>
                <div class="category-header">
                  <div class="category-photo" @click="onFilesManagerHandler">
                    <img v-if="image" :src="image.url" alt="">
                    <span v-else>Загрузите фото</span>
                  </div>
                  <div class="item-name">
                    <div class="label-title">
                      Название
                      <span class="label-text" v-if="!categoryValid">{{ $t('validation.uniq') }}</span>
                    </div>
                    <input
                        type="text"
                        @keyup="validateCategory"
                        placeholder="Введите название категории"
                        v-model="groupName"
                    >
                  </div>
                </div>
                <div class="item nested-category">
                  <div class="label-title">
                    <span>{{ $t('category.add') }} {{ $t('category.child_category') }}</span>
                    <v-tooltip
                        right
                        max-width="400px"
                        text-color="#7E7E7E"
                        content-class="gray-tooltip">

                      <template v-slot:activator="{ on, attrs }">
                              <span class="tooltip-icon">
                <span
                    v-bind="attrs"
                    v-on="on"
                    class="tooltip-icon"
                >
                  <svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite>
                </span>
              </span>
                      </template>
                      <span>
                          - Вложенная категория включает в себя все Свойства и Характеристики Основной категории <br>
                          - Вложенною Категорию можно редактировать соответственно наличия перечня Свойств Характеристик, путем исключения и добавления следующих; <br>
                          - Добавленные Новые Свойства и Характеристики во Вложенной Категории автоматически отображаются в  Основной Категории (и Других Категориях в структуре, группой выше);<br>
                          - Исключенные Свойства и Характеристики Вложенной Категории НЕ Исключаются из Основной Категории (и Других Категорий из структуры группой выше)
                      </span>
                    </v-tooltip>
                    <span class="label-text" v-if="!$v.childGroupsTitle.isUnique && !$v.childGroupsTitle.$pending">{{
                        $t('validation.uniq')
                      }}</span>
                  </div>
                  <div class="input-wrap d-flex" v-click-outside="onClickOutside">
                    <v-btn
                        @click="showGroups"
                        class="open-drop-down"
                        icon
                    >
                      <svg-sprite style="color: rgb(157,204,255)" width="11" height="6" icon-id="arrowDown"></svg-sprite>
                    </v-btn>
                    <input
                        @focus="showGroups"
                        v-model.trim="$v.childGroupsTitle.$model"
                        type="text" placeholder="Выберите из существующих или введите название новой категории">
                    <v-btn
                        @click="createChildCategory()"
                        :disabled="$v.childGroupsTitle.$error || !childGroupsTitle"
                        icon
                        class="create-nested-category"
                    >
                      <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                    </v-btn>
                    <div class="drop-down drop-down-categories" v-if="showDropDown && !this.childGroupsTitle.length">
                      <v-treeview
                          open-all
                          activatable
                          item-text="title"
                          selection-type="independent"
                          :items="items"
                          item-disabled="disabled"
                      >
                        <template slot="label" slot-scope="{item}">
                          <div item-disabled="{item.id == 1}" @click="selectCategory(item)">{{ item.title }}</div>
                        </template>
                      </v-treeview>
                    </div>
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
                <div class="select-wrap">
                  <div class="label-title">Единица измерения</div>
                  <v-select
                      content-class="units-switcher" class="select-switcher units-switcher"
                      v-model="unit_id"
                      :items="units"
                      item-text="title"
                      item-value="id"
                      solo
                      dense
                      menu-props="units"
                  >
                  </v-select>
                </div>
                <div class="item">
                  <div class="label-title">Описание</div>
                </div>
                <div class="wrap-textarea">
                    <textarea
                        v-model="description"
                        name="textarea"
                    ></textarea>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <div class="item">
                  <div class="label-title">{{ $t('category.add') }} {{ $t('category.options_products') }}</div>
                  <div class="search-list">
                    <div class="search-block">
                      <input type="text" :placeholder="$t('page.search')" v-model="searchCharacteristic">
                      <div class="search-icon">
                        <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
                      </div>
                      <v-btn icon class="add-new" color="#5893D4" @click="openCharacteristic">
                        <svg-sprite width="30" height="30" icon-id="plusFill"></svg-sprite>
                      </v-btn>
                    </div>
                    <simplebar class="wrap-list" force-visible>
                      <ul class="list" v-if="characteristicsList">
                        <li
                            v-for="element in characteristicsList"
                            :key="element.id"
                            :class="{selected: selectCharacteristics.some(item => item.id === element.id)}"
                        >
                          <span>{{ element.title }}</span>
                          <span @click="editCharacteristic(element)" class="item-edit">
                            <svg-sprite style="color: rgb(157,204,255)" width="23" height="23" icon-id="editSmall"></svg-sprite>
                          </span>
                          <span v-if="!selectCharacteristics.some(item => item.id === element.id)" class="add-value" @click="addSelectCharacteristic(element)">
                            <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                          </span>
                          <span v-else :class="element.id ? 'remove-value': ''" @click="deleteSelectCharacteristic(element.id)">
                              <svg-sprite width="30" height="30" icon-id="minus"></svg-sprite>
                          </span>
                        </li>
                      </ul>
                    </simplebar>
                  </div>
                </div>
                <div class="item">
                  <div class="label-title">{{ $t('category.options_products1') }}</div>
                </div>
                <div class="values-list">
                  <div class="value-item" v-for="(element, index) in selectCharacteristics" :key="element.id">
                    <span>{{ element.title }}</span>
                    <span @click="deleteChipCharacteristic(index)">
                      <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
                    </span>
                  </div>
                </div>
              </v-col>
              <v-col cols="6">

                <div class="item">
                  <div class="label-title">{{ $t('category.add') }} {{ $t('category.property_products') }}</div>
                  <div class="search-list">
                    <div class="search-block">
                      <input type="text" :placeholder="$t('page.search')" v-model="searchProperty">
                      <div class="search-icon">
                        <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
                      </div>
                      <v-btn icon class="add-new" color="#5893D4" @click="openProperties">
                        <svg-sprite width="30" height="30" icon-id="plusFill"></svg-sprite>
                      </v-btn>
                    </div>
                    <simplebar class="wrap-list" force-visible>
                      <ul class="list" v-if="propertiesList">
                        <li
                            v-for="(element) in propertiesList"
                            :key="element.id"
                            :class="{selected: selectProperties.some(item => item.id === element.id)}"
                        >
                          <span>{{ element.title }}</span>
                          <span v-if="(element.id !== 1 && element.id !== 2)" @click="editProperty(element)">
                            <svg-sprite style="color: rgb(157,204,255)" width="23" height="23" icon-id="editSmall"></svg-sprite>
                          </span>
                          <span
                              v-if="!selectProperties.some(item => (item.id === element.id) && (element.id !== 1 || element.id !== 2))"
                              class="add-value"
                              @click="addSelectProperty(element)"
                          >
                             <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                          </span>
                          <span
                              v-if="selectProperties.some(item => (item.id === element.id) || (element.id === 1 || element.id === 2))"
                              :class="[element.id ? 'remove-value': '', { 'opacity': (element.id === 1 || element.id === 2) }]"
                              @click="deleteSelectProperty(element.id)"
                          >
                              <svg-sprite width="30" height="30" icon-id="minus"></svg-sprite>
                          </span>
                        </li>
                      </ul>
                    </simplebar>
                  </div>
                </div>
                <div class="item">
                  <div class="label-title">{{ $t('category.property_products') }}</div>
                </div>
                <div class="values-list">
                  <div class="value-item" v-for="(element, idx) in selectProperties" :key="element.id">
                    <span>{{ element.title }}</span>
                    <span v-if="(element.id !== 1 && element.id !== 2)" @click="deleteChipProperty(idx)">
                      <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
                    </span>
                  </div>
                </div>
              </v-col>
            </v-row>
          </form>
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="close"
            >
              Закрыть
            </v-btn>
            <custom-btn
                :title="$t('page.saveButton')"
                custom-class="save"
                :is-disabled="!categoryValid || !groupName"
                color="#5893D4"
                @updateEvent="updateCategory"
            ></custom-btn>
          </div>
        </div>
      </div>
    </v-dialog>

    <!-- Create characteristic dialog -->
    <v-dialog
        max-width="1230px"
        overlay-opacity="0.7"
        v-model="dialogCharacteristic"
        class="dialog-large update-item"
    >
      <div class="dialog-header">
        <div class="header-text">
          <span v-if="editChar">{{ $t('category.edit_option') }}</span>
          <span v-else>{{ $t('category.create_option') }}</span>
        </div>
        <div class="dialog-header-actions">
          <!--          <div class="alert-save">Изменения сохранены</div>-->
          <v-btn icon color="#5893D4">
            <svg-sprite width="15" height="20" icon-id="pin"></svg-sprite>
          </v-btn>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="closeCharacteristic">
            <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
          </v-btn>
        </div>
      </div>
      <div class="dialog-content dialog-content-large page characteristic-page edit-option">
        <div class="detail-close" @click="closeCharacteristic">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад к созданию категории</span>
        </div>
        <CharacteristicEdit
            v-if="editChar"
            :characteristicEdit="characteristicEdit"
            :key="componentKey"
            ref="characteristicEditModal"
            :closeDetails="closeCharacteristic"
        ></CharacteristicEdit>
        <CharacteristicCreate
            :closeDetails="closeCharacteristic"
            ref="characteristicCreateModal" v-else></CharacteristicCreate>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              ref="characteristicModal"
              style="font-size: 13px"
              @click="closeCharacteristic"
          >
            Назад
          </v-btn>
          <v-btn
              class="popup-btn"
              color="#5893D4"
              dark
              style="font-size: 13px"
              @click.native="agreeCharacteristicChanges"
          >
            Сохранить и добавить
          </v-btn>
        </div>
      </div>
    </v-dialog>


    <!-- Create property dialog -->
    <v-dialog
        max-width="1230px"
        overlay-opacity="0.7"
        v-model="dialogProperty"
        class="dialog-large update-item"
    >
      <div class="dialog-header">
        <div class="header-text">
          <span>Создание свойства</span>
        </div>
        <div class="dialog-header-actions">
          <div class="alert-save">Изменения сохранены</div>
          <v-btn icon color="#5893D4">
            <svg-sprite width="15" height="20" icon-id="pin"></svg-sprite>
          </v-btn>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="closeCharacteristic">
            <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
          </v-btn>
        </div>
      </div>
      <div class="dialog-content dialog-content-large page">
        <div class="detail-close" @click="closeProperty">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад к созданию категории</span>
        </div>
        <PropertyEdit
            v-if="editProp"
            :key="componentKey"
            :categoryEdit="categoryEdit"
            @finishedAction="fetchData"
            ref="updateProperty"
        ></PropertyEdit>
        <PropertyCreate
            ref="createProperty"
            @finishedAction="fetchData"
            v-else></PropertyCreate>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              style="font-size: 13px"
              @click="closeProperty"
          >
            Назад
          </v-btn>
          <v-btn
              class="popup-btn"
              color="#5893D4"
              dark
              style="font-size: 13px"
              @click="updateProperty"
          >
            Сохранить и добавить
          </v-btn>
        </div>
      </div>
    </v-dialog>

    <files-manager
        v-if="isOpen"
        :isOpen="isOpen"
        typeField="radio"
        @closeFilesManager="onFilesManagerHandler(0)"
        @image="getImage"
    >
    </files-manager>

  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex"
// components
import CharacteristicCreate from "@/components/dashboard/characteristic/CharacteristicCreate"
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit"
import PropertyCreate from "@/components/dashboard/properties/PropertyCreate"
import PropertyEdit from "@/components/dashboard/properties/PropertyEdit"
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import ProgressBar from "@/components/dashboard/categories/ProgressBar";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import FilesManager from '@/components/ui/FilesManager/FilesManager';
import NestedGroups from "@/components/dashboard/products/suppliers/NestedGroups/NestedGroups";
// validate
import {required} from "vuelidate/lib/validators";
// services
import httpClient from "@/services/http-client/http-client";
// helpers
import removeDuplicates from "@/helper/removeDuplicates";
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "CategoryEdit",
  props: {
    categories: {
      type: Array,
      required: true
    },
    units: Array,
  },
  components: {
    CustomBtn,
    CharacteristicCreate,
    CharacteristicEdit,
    PropertyCreate,
    PropertyEdit,
    FilesManager,
    HeaderActions,
    ProgressBar,
    NestedGroups
  },
  validations: {
    childGroupsTitle: {
      required,
      async isUnique(value) {
        if (!value) {
          return true
        }

        const params = {
          title: value
        }

        const { data } = await httpClient.post('/products/categories/update-async-validations', {validate: {...params}});
        return new Promise((resolve) => resolve(!data.status))
      }
    }
  },
  computed: {
    ...mapGetters([
      'dataCreate',
      'dataEdit',
      'IDCreatedCategory',
      'editCategory',
      'categoryValid',
      'flatCategoriesList'
    ]),
    getProgressList() {
      return [this.image, this.unit_id,  this.description, this.selectProperties, this.selectCharacteristics];
    },
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    items() {
      return this.getNodes(this.categories, this.children)
    },
    characteristicsList() {
      if (this.dataCreate) {
        return this.dataCreate[1].characteristics.filter((item) =>
            item.title.toLowerCase().includes(this.searchCharacteristic.toLowerCase())
        )
      }
      return false
    },
    getProperties() {
      const properties = this.dataCreate[2]['properties'].map(item => ({...item}));
      properties.push({title: 'Торговая марка', id: 1}, {title: 'Модель', id: 2});

      return properties;
    },
    propertiesList() {
      if (this.dataCreate) {
        return this.getProperties.filter((item) =>
            item.title.toLowerCase().includes(this.searchProperty.toLowerCase()))
      }

      return null
    }
  },
  data: () => ({
    resetId: null,
    parent_id: null,
    isOpen: false,
    editProp: false,
    editChar: false,
    dialogCreateCategory: false,
    dialogCharacteristic: false,
    characteristicEdit: null,
    categoryEdit: null,
    dialogProperty: false,
    addCharacteristic: false,
    image: '',
    image_id: null,
    searchCharacteristic: '',
    searchProperty: '',
    unitSelect: 'Шт',
    unit_id: 1,
    showTooltip: false,
    showDropDown: false,
    childGroupsTitle: '',
    children: [],
    invalid: false,
    itemValue: '',
    characteristicName: '',
    valueExists: false,
    values: [],
    id: null,
    selectProperties: [],
    selectCharacteristics: [],
    componentKey: 0,
    groupName: '',
    description: ''
  }),
  methods: {
    ...mapActions({
      createCategory: 'addNewCategory',
      categoryValidation: 'categoryValidationUpdate',
      resetCategoryValidation: 'resetCategoryValidation'
    }),
    onReset() {
      this.resetId = getUniqueId();
      this.parent_id = 0
    },
    onGroupId({id}) {
      this.parent_id = id || 0
    },
    onClickOutside() {
      this.showDropDown = false;
    },
    getImage({data, successUrl}) {
      this.image_id = data;
      this.image = successUrl
    },
    onFilesManagerHandler(key) {
      this.isOpen = !!key
    },
    validateCategory() {
      if (this.groupName) {
        const id = this.id
        const params = {
          title: this.groupName
        }

        this.categoryValidation({ params, id })
      } else {
        this.resetCategoryValidation()
      }
    },
    forceRerender() {
      this.componentKey += 1;
    },
    agreeCharacteristicChanges() {
      if (this.editChar) {
        this.$refs.characteristicEditModal.updateItem().then(() => {
          this.$store.dispatch('fetchDataCreate')
        })

        this.closeCharacteristic()
      } else {
        this.$refs.characteristicCreateModal.createItem().then(() => {
          this.$store.dispatch('fetchDataCreate')
        })

        this.closeCharacteristic()
      }
    },
    fetchData() {
      this.$store.dispatch('fetchDataCreate')
    },
    updateProperty() {
      if (this.editProp) {
        this.$refs.updateProperty.updateProperty()
      } else {
        this.$refs.createProperty.createProperty()
      }

      this.closeProperty()
    },
    addSelectProperty(element) {
      this.selectProperties.unshift(element)
    },
    deleteSelectProperty(id) {
      if (id === 1 || id === 2) return false;

      this.selectProperties.forEach((item, index) => {
        if (item.id === id) {
          this.selectProperties.splice(index, 1)
        }
      })
    },
    deleteChipProperty(index) {
      this.selectProperties.splice(index, 1)
    },

    async editProperty(element) {
      this.editProp = true
      try {
        await this.$store.dispatch('clearDataEdit')
        await this.$store.dispatch('editProperty', element)
        this.categoryEdit = this.dataEdit

        this.forceRerender()
        this.dialogProperty = true
      } catch (e) {
        console.log(e)
      }
    },
    addSelectCharacteristic(element) {
      this.selectCharacteristics.unshift(element)
    },
    deleteSelectCharacteristic(id) {
      this.selectCharacteristics.forEach((item, index) => {
        if (item.id === id) {
          this.selectCharacteristics.splice(index, 1)
        }
      })
    },
    deleteChipCharacteristic(index) {
      this.selectCharacteristics.splice(index, 1)
    },
    async editCharacteristic(element) {
      this.editChar = true;
      this.edit = true;

      try {
        await this.$store.dispatch('clearDataEdit');
        await this.$store.dispatch('editCharacteristic', element);
        this.characteristicEdit = this.dataEdit

        this.forceRerender();
        this.dialogCharacteristic = true;
      } catch (e) {
        console.log(e)
      }
    },

    openCharacteristic() {
      this.dialogCharacteristic = true
    },
    openProperties() {
      this.dialogProperty = true
    },
    createChildCategory() {
      const category = {
        title: this.childGroupsTitle,
        desc: null,
        image: null,
        parent_id: null,
        characteristics: null,
        properties: null,
        categories: null,
      }
      this.createCategory(category).then(({ title, id }) => {
        if (this.childGroupsTitle.length > 3) {
          this.children.unshift({ title, id })
          this.childGroupsTitle = '';
          this.showDropDown = false;
        }
      })

    },
    deleteChildCategory(index) {
      this.children.splice(index, 1)
    },
    selectCategory(item) {
      if (item.disabled) {
        return false;
      } else {
        this.children.unshift(item)
        this.showGroups()
      }
    },
    showGroups() {
      this.showDropDown = !this.showDropDown
    },
    async open(category) {
      const { parent } = category;
      this.dialogCreateCategory = true;
      this.parent_id = parent?.id;
      this.id = category.id
      if (category) {
        await this.fetchEditCategory(category.id)
      }
    },

    async fetchEditCategory(id) {
      await this.$store.dispatch('fetchEditCategory', id)
      this.groupName = this.editCategory[0].category.title
      this.description = this.editCategory[0].category.desc
      this.unit_id = this.editCategory[0].category.unit_id
      this.image = this.editCategory[0].category.image ? this.editCategory[0].category.image : null
      this.children = this.editCategory[0].category.children;
      this.selectProperties = removeDuplicates(this.editCategory[0].category.properties);
      this.selectCharacteristics = this.editCategory[0].category.characteristics
    },

    getNodes(items) {
      return items.map(item => {
        const disabled = this.children.find(({id}) => id === item.id);

        return {
          id: item.id,
          title: item.title,
          disabled: Boolean(disabled),
          children: item.children ? this.getNodes(item.children) : []
        }
      })
    },

    close() {
      this.dialogCreateCategory = false
      this.$store.dispatch('clearDataEdit')
      this.$emit('updateClose')
    },
    closeCharacteristic() {
      this.$store.dispatch('clearDataEdit')
      this.dialogCharacteristic = false

    },
    closeProperty() {
      this.dialogProperty = false
      this.editProp = false
    },

    async updateCategory() {
      const category = {
        unit_id: this.unit_id,
        title: this.groupName ?? this.childGroupsTitle,
        desc: this.description,
        image_id: this.image_id,
        parent_id: this.parent_id,
        characteristics: this.selectCharacteristics,
        properties: this.selectProperties,
        categories: this.children.map(category => ({ id: category.id }))
      }


      try {
        await this.$store.dispatch('updateCategory', { category, id: this.id })
        const alertParams = {
          textItems: [
            {text: 'category', style: 'normal', translate: true},
            {text: this.groupName, style: 'bold', translate: false},
            {text: 'updated', style: 'normal', translate: true},
          ],
          link:
              {
                action: 'view-item', text: 'nomenclature.alert_massages.open',
                actionParams: {
                  url: `/products/categories/${this.id}}`
                }
              }
        }
        this.$emit('show-alert', alertParams)
        this.close()

      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.clear {
  display: inline-block;
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-decoration: underline;
  color: #9DCCFF;
  cursor: pointer;
  padding: 0 0 10px;
}

.selected span:first-child {
  opacity: .5;
}

.popup-buttons {
  display: flex;
}

.label-text {
  text-transform: initial;
  font-size: 12px;
  line-height: 12px;
  float: right;
  color: #FF9F2F;
  font-weight: 400;
}


.popup-buttons {
  display: flex;
}

.wrap-textarea {
  margin-bottom: 0;
}

.label-text {
  text-transform: initial;
  font-size: 12px;
  line-height: 12px;
  float: right;
  color: #FF9F2F;
  font-weight: 400;
}

.dialog {
  .item-form {
    height: 683px;
    padding-top: 30px;

    .item-name {
      height: fit-content;

      .label-title {
        margin-bottom: 15px;
      }
    }

  }
}

.char-option-block {
  overflow: hidden;
}

.label-title {
  color: #317CCE;
}

.category-header {
  padding-top: 0;
}

.select-wrap {
  margin-bottom: 30px;

  .label-title {
    margin-bottom: 4px;
  }
}

.list li span ~ span {
  margin-right: 0;
}
.values-list .value-item span ~ span {
  margin-right: 0;
}

.opacity {
  opacity: .5;
}
</style>
