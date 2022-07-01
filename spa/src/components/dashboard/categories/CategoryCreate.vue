<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialogEditCategory"
        max-width="1230px"
        class="dialog-large dialog-category "
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <span>{{isCopy ? $t('category.copy_category') : $t('category.add_category')}}</span>
          </div>
          <progress-bar
              :progressList="getProgressList"
              isToolTip
          ></progress-bar>
          <header-actions
              @updateClose="close"
              @onCloseModal="close"
              :id="bookMarkId"
              :title="bookMarkTitle"
              is-hide-dots
          ></header-actions>
        </div>
        <div class="dialog-content dialog-content-large category-modal" v-if="dialogEditCategory">
          <simplebar class="item-form">
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
                  <div class="category-photo" @click="filesManagerHandler">
                    <img v-if="image" :src="image" alt="">
                    <span v-else>{{ $t('user.addPhoto') }}</span>
                  </div>
                  <div class="item-name">
                    <div class="label-title">
                      <span>Название</span>
                      <div class="right">
                        <span v-if="isCopy" class="label-text">Измените название</span>
                        <span class="label-text" v-if="!$v.groupName.isUnique && !$v.groupName.$pending && groupName">{{ $t('validation.uniq') }}</span>
                      </div>
                    </div>
                    <input type="text" placeholder="Введите название категории"
                           v-model.trim="$v.groupName.$model">
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
                          <span
                              v-bind="attrs"
                              v-on="on"
                              class="tooltip-icon"
                          >
                            <svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite>
                         </span>
                      </template>
                      <span>
                          - Вложенная категория включает в себя все Свойства и Характеристики Основной категории <br>
                          - Вложенною Категорию можно редактировать соответственно наличия перечня Свойств Характеристик, путем исключения и добавления следующих; <br>
                          - Добавленные Новые Свойства и Характеристики во Вложенной Категории автоматически отображаются в  Основной Категории (и Других Категориях в структуре, группой выше);<br>
                          - Исключенные Свойства и Характеристики Вложенной Категории НЕ Исключаются из Основной Категории (и Других Категорий из структуры группой выше) <br>
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
                        type="text"
                        placeholder="Выберите из существующих или введите название новой категории"
                    >
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
                      content-class="units-switcher"
                      class="select-switcher units-switcher"
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
                <div class="item char-option-block">
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
                            :class="{selected: selectCharacteristics.some(({ id }) => id === element.id)}"
                        >
                          <span>{{ element.title }}</span>
                          <span @click="editCharacteristic(element)">
                            <svg-sprite style="color: rgb(157,204,255)" width="23" height="23" icon-id="editSmall"></svg-sprite>
                          </span>
                          <span v-if="!selectCharacteristics.find(({ id }) => id === element.id)" @click="addSelectCharacteristic(element)">
                            <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                          </span>
                          <span v-else class="remove-value" @click="deleteSelectCharacteristic(element.id)">
                              <svg-sprite width="30" height="30" icon-id="minus"></svg-sprite>
                          </span>
                        </li>
                      </ul>
                    </simplebar>
                  </div>
                </div>
                <div class="item">
                  <div class="label-title">{{ $t('category.options_products') }}</div>
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
                            v-for="element in propertiesList"
                            :key="element.id"
                            :class="{selected: selectProperties.find(({ id }) => id === element.id)}"
                        >
                          <span>{{ element.title }}</span>
                          <span v-if="(element.id !== 1 && element.id !== 2)" @click="editProperty(element)">
                            <svg-sprite style="color: rgb(157,204,255)" width="23" height="23" icon-id="editSmall"></svg-sprite>
                          </span>
                          <span
                              v-if="!selectProperties.find(({ id }) => id === element.id) && (element.id !== 1 || element.id !== 2)"
                              @click="addSelectProperty(element)"
                          >
                             <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                          </span>
                          <span
                              v-if="selectProperties.find(({ id }) => id === element.id) || (element.id === 1 || element.id === 2)"
                              :class="[!element.isDefault ? 'remove-value': '', {'opacity': (element.id === 1 || element.id === 2)}]"
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
                    <span v-if="(element.id !== 1 && element.id !== 2)"  @click="deleteChipProperty(idx)">
                      <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
                    </span>
                  </div>
                </div>
              </v-col>
            </v-row>
          </simplebar>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel"
                title="Отменить"
                color="#5893D4"
                @updateEvent="close"
                text
            ></custom-btn>
            <custom-btn
                title="Добавить"
                custom-class="save"
                :is-disabled="$v.groupName.$pending || $v.groupName.$error || !groupName "
                color="#5893D4"
                @updateEvent="addNewCategory"
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
        <header-actions is-hide-dots @updateClose="closeCharacteristic"></header-actions>
      </div>
      <div class="dialog-content dialog-content-large page characteristic-page edit-option" v-if="dialogCharacteristic">
        <div class="detail-close" @click="closeCharacteristic">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад к созданию категории</span>
        </div>
        <CharacteristicEdit
            v-if="editChar && characteristicEdit"
            :characteristicEdit="characteristicEdit"
            :key="componentKey"
            ref="characteristicEditModal"
        ></CharacteristicEdit>
        <CharacteristicCreate
            v-else
            ref="characteristicCreateModal"
        ></CharacteristicCreate>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
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
          <span v-if="editProp">{{ $t('category.edit_char') }}</span>
          <span v-else>{{ $t('category.create_char') }}</span>
        </div>
        <header-actions isHideDots is-hide-attach @updateClose="closeProperty"></header-actions>
      </div>
      <div class="dialog-content dialog-content-large page" v-if="dialogProperty">
        <div class="detail-close" @click="closeProperty">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад к созданию категории</span>
        </div>
        <PropertyEdit
            v-if="editProp"
            :key="componentKey"
            :categoryEdit="categoryEdit"
            ref="updateProperty"
            @finishedAction="fetchData"
        />
        <PropertyCreate
            v-else
            @finishedAction="fetchData"
            ref="createProperty"
        />
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
      @closeFilesManager="closeFilesManager"
      @image="getImage"
    >
    </files-manager>

  </div>
</template>

<script>
// vuex
import {mapGetters, mapActions} from "vuex";
// components
import CharacteristicCreate from "@/components/dashboard/characteristic/CharacteristicCreate";
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit";
import PropertyCreate from "@/components/dashboard/properties/PropertyCreate";
import PropertyEdit from "@/components/dashboard/properties/PropertyEdit";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import NestedGroups from "@/components/dashboard/products/suppliers/NestedGroups/NestedGroups";
import ProgressBar from "@/components/dashboard/categories/ProgressBar";
// validate
import { required } from 'vuelidate/lib/validators';
// services
import httpClient from "@/services/http-client/http-client";
import FilesManager from '@/components/ui/FilesManager/FilesManager';
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "CategoryCreate",
  components: {
    CustomBtn,
    CharacteristicCreate,
    CharacteristicEdit,
    PropertyCreate,
    HeaderActions,
    NestedGroups,
    PropertyEdit,
    FilesManager,
    ProgressBar
  },
  props: {
    bookMarkTitle: String,
    bookMarkId: String,
    categories: {
      type: Array,
    },
    units: Array,
  },
  validations: {
    groupName: {
      required,
      async isUnique(value) {
        const params = {
          title: value
        }
        const {data} = await httpClient.post('/products/categories/store-async-validations', {validate: {...params}});
        return new Promise((resolve) => {
          resolve(!data.status)

        })
      }
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
        return new Promise((resolve) => {
          resolve(!data.status)
        })
      }
    }
  },
  data: () => ({
    resetId: null,
    editProp: false,
    editChar: false,
    dialogEditCategory: false,
    dialogCharacteristic: false,
    characteristicEdit: null,
    categoryEdit: null,
    dialogProperty: false,
    parent_id: null,
    addCharacteristic: false,
    image: '',
    searchCharacteristic: '',
    searchProperty: '',
    unitSelect: 'Шт',
    showTooltip: false,
    showDropDown: false,
    childGroupsTitle: '',
    children: [],
    invalid: false,
    itemValue: '',
    characteristicName: '',
    valueExists: false,
    values: [],
    componentKey: 0,
    isOpen: false,
    groupName: '',
    description: '',
    selectProperties: [],
    selectCharacteristics: [],
    progressValue: 0,
    unit_id: 1,
    image_id: null,
    isCopy: null
  }),
  computed: {
    ...mapGetters([
      'dataCreate',
      'dataEdit',
      'IDCreatedCategory',
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
    getCharacteristicsList() {
      if (this.dataCreate) {
        return this.dataCreate[1].characteristics
      }

      return null;
    },
    characteristicsList() {
      if (this.getCharacteristicsList) {
        return this.getCharacteristicsList.filter((item) =>
            item.title.toLowerCase().includes(this.searchCharacteristic.toLowerCase())
        )
      }

      return null;
    },
    getProperties() {
      if (this.dataCreate) {
        const properties = this.dataCreate[2]['properties'].map(item => ({...item}));
        properties.push({title: 'Торговая марка', id: 1}, {title: 'Модель', id: 2});

        return properties;
      }

      return null;
    },
    propertiesList() {
      if (this.getProperties) {
        return this.getProperties.filter((item) =>
            item.title.toLowerCase().includes(this.searchProperty.toLowerCase()))
      }

      return null
    }
  },
  methods: {
    ...mapActions({
      createCategory: 'addNewCategory',
      categoryValidation: 'categoryValidationCreate',
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
    getImage({ data, successUrl }) {
      this.image_id = data
      this.image = successUrl
    },
    filesManagerHandler() {
      this.isOpen = true
    },
    closeFilesManager() {
      this.isOpen = false
    },
    validateCategory() {
      if (this.groupName) {
        const params = {
          title: this.groupName
        }

        this.categoryValidation(params);
      } else {
        this.resetCategoryValidation();
      }

    },
    forceRerender() {
      this.componentKey += 1;
    },
    fetchData() {
      this.editProp = null
      this.$store.dispatch('fetchDataCreate');
      this.closeProperty();
    },
    agreeCharacteristicChanges() {
      if (this.editChar) {
        this.$refs.characteristicEditModal.updateItem().then(() => {
          this.$store.dispatch('fetchDataCreate')
          this.editChar = null
        })
        this.closeCharacteristic()
      } else {
        this.$refs.characteristicCreateModal.createItem().then(() => {
          this.$store.dispatch('fetchDataCreate')
        })
        this.closeCharacteristic()
      }
    },
    /* Properties methods */
    updateProperty() {
      if (this.editProp) {
        this.$refs.updateProperty.updateProperty()
      } else {
        this.$refs.createProperty.createProperty()
      }
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
    /* Characteristic methods */
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
        await this.$store.dispatch('clearDataEdit')
        await this.$store.dispatch('editCharacteristic', element)
        this.characteristicEdit = this.dataEdit
        this.forceRerender()
        this.dialogCharacteristic = true
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

      this.createCategory(category).then(newCategory => {
        if (this.childGroupsTitle.length > 3) {
          this.children.unshift({
            title: newCategory.title,
            id: newCategory.id,
          })
          this.childGroupsTitle = ''
          this.showDropDown = false
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
    open(parent_id, {category, mode}) {
      this.parent_id = null;
      if (mode === 'copy') {
        this.groupName = `${category.title}(копия)`
        this.selectCharacteristics = category.characteristics
        this.selectProperties = category.properties
        this.description = category.desc
        this.parent_id = category.parent_id
        this.isCopy = true;
      }

      if (parent_id) {
        this.parent_id = parent_id
      }

      if (mode === 'create') {
        this.groupName = ''
        this.description = ''
        this.children = []
        this.selectProperties = [{title: 'Торговая марка', id: 1}, {title: 'Модель', id: 2}]
        this.selectCharacteristics = []
        this.resetCategoryValidation()
        this.isCopy = null;
      }

      this.dialogEditCategory = true;
    },

    getNodes(items) {
      return items.map(item => {
        const disabled = this.children.find(({id}) => id === item.id)
        return {
          id: item.id,
          title: item.title,
          disabled: Boolean(disabled),
          children: item.children ? this.getNodes(item.children) : []
        }
      })
    },

    close() {
      this.dialogEditCategory = false;
      this.$store.dispatch('clearDataEdit')
    },
    closeCharacteristic() {
      this.$store.dispatch('clearDataEdit')
      this.dialogCharacteristic = false
      this.editChar = false;
      this.characteristicEdit = null;

    },
    closeProperty() {
      this.dialogProperty = false
      this.editProp = false
    },

    async addNewCategory() {
      const category = {
        title: this.groupName ? this.groupName : this.childGroupsTitle,
        desc: this.description,
        image_id: this.image_id,
        unit_id: this.unit_id,
        parent_id: this.parent_id || 0,
        characteristics: this.selectCharacteristics,
        properties: this.selectProperties.filter(item => item.id !== 1 || item.id !== 2),
        categories: [...this.children].map(category => {
          return {id: category.id}
        })
      }

      const alertParams = {
        textItems: [
          {text: 'category', style: 'normal', translate: true},
          {text: this.groupName, style: 'bold', translate: false},
          {text: 'created', style: 'normal', translate: true},

        ],
        link:
            {
              action: 'view-item', text: 'nomenclature.alert_massages.open',
              actionParams: {
                url: `/products/categories/${this.IDCreatedCategory}`
              }
            }
      }

      await this.$store.dispatch('addNewCategory', category)
      this.$emit('show-alert', alertParams)
      try {
        // const dataAlert = {
        //   name: localStorage.getItem('locale') == 'ru' ? 'Категория' : 'Категорія',
        //   title: this.groupName,
        //   createdName: localStorage.getItem('locale') == 'ru' ? 'создана' : 'створена',
        //   link: `/products/categories/${this.IDCreatedCategory}`
        // }
        this.close()
        // await this.$store.dispatch('alertShow', dataAlert)
        console.log(category)
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
</style>
<style lang="scss">
@import "src/sass/mixins";

.v-btn.v-btn--disabled {
  opacity: .5;
}
</style>
<style scoped lang="scss">
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
        display: flex;
        justify-content: space-between;

        .label-text {
          margin-left: 5px;
        }
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

.dialog-actions {
  width: 100%;
  justify-content: center;
}

.selected span:first-child {
  opacity: .5;
}

.opacity {
  opacity: .5;
}
</style>
