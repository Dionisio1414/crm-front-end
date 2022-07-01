<template>
  <div class="category">
    <div class="category-top-line">
      <div class="detail-close" @click="goBack()">
        <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
        <span>Назад</span>
      </div>
      <progress-bar
          :progressList="getProgressList"
          isHideToolTip
      ></progress-bar>
      <div class="change-history" style="display: none">
        <span>История изменения категории</span>
      </div>
    </div>
    <div class="category-content" v-if="category">
      <v-skeleton-loader
          :loading="loading"
          type="list-item-avatar, list-item-three-line, card-heading, image, actions"
      >
        <div class="category-header">
          <div class="category-photo">
            <img v-if="category.image" :src="category.image !== null ? category.image.url : null" alt="">
            <span v-else>Загрузите фото</span>
          </div>
          <div class="category-info">
            <div class="switch-nesting">
                    <span class="switch-text" @click="switchShow = false"
                          :class="{active: !switchShow}">Скрыть</span>
              <switcher @toggle="onToggleVisible" :value="switchShow"></switcher>
              <span class="switch-text" @click="switchShow = true" :class="{active: switchShow}">Показать на сайте</span>
            </div>
            <div class="breadcrumbs">
              <a href="#">{{category.title}}</a>
              <category-tree :editable="false" :categories="categoryTree"/>
            </div>
          </div>
        </div>
        <div class="label-title">{{$t('nomenclature.unit')}}</div>
        <div class="unit">{{category['unit_title']}}</div>
        <div class="label-title">{{$t('nomenclature.desc')}}</div>
        <p class="category-description">
          {{category.desc}}
        </p>
        <div class="category-characteristics" v-if="category.characteristics.length">
          <div class="label-title">{{$t('nomenclature.modal_steps.products_options')}}</div>
            <div class="values-list">
              <div class="value-item" v-for="(value, i) in category.characteristics" :key="i">
                <span style="margin-right: 15px">{{value.title}}</span>
                <span @click="editCharacteristic(value)">
                  <svg-sprite width="17" height="18" icon-id="edit_pencil_small"></svg-sprite>
                </span>
            </div>
          </div>
        </div>
        <div class="category-properties" v-if="category.properties.length">
          <div class="label-title">{{$t('nomenclature.modal_steps.products_chars')}}</div>
          <div class="values-list">
            <div class="value-item" v-for="(value, i) in category.properties" :key="i">
              <span style="margin-right: 15px">{{value.title}}</span>
              <span @click="editProperty(value)">
                <svg-sprite width="17" height="18" icon-id="edit_pencil_small"></svg-sprite>
              </span>
            </div>
          </div>
        </div>
      </v-skeleton-loader>
    </div>

    <!-- Characteristic edit -->
    <v-dialog
        max-width="1230px"
        overlay-opacity="0.7"
        v-model="dialogCharacteristic"
        class="dialog-large update-item"
    >
      <div class="dialog-header">
        <div class="header-text">
          <span>Редактирование характеристики</span>
        </div>
        <div class="dialog-header-actions">
          <div class="alert-save">Изменения сохранены</div>
          <v-btn icon color="#5893D4">
            <svg-sprite width="15" height="20" icon-id="pin"></svg-sprite>
          </v-btn>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="closeCharacteristic()">
            <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
          </v-btn>
        </div>
      </div>
      <div class="dialog-content dialog-content-large page characteristic-page edit-option">
        <div class="detail-close" @click="closeCharacteristic()">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад к категории</span>
        </div>
        <CharacteristicEdit
            :characteristicEdit="characteristicEdit"
            :key="componentKey"
            ref="updateItem"
            isCategory
        ></CharacteristicEdit>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              style="font-size: 13px"
              @click="closeCharacteristic()"
          >
            Назад
          </v-btn>
          <v-btn
              class="popup-btn"
              color="#5893D4"
              dark
              style="font-size: 13px"
              @click="updateItem()"
          >
            Сохранить и добавить
          </v-btn>
        </div>
      </div>
    </v-dialog>

    <!-- Property edit -->
    <v-dialog
        max-width="1230px"
        overlay-opacity="0.7"
        v-model="dialogProperty"
        class="dialog-large update-item"
    >
      <div class="dialog-header">
        <div class="header-text">
          <span>Редактирование свойства</span>
        </div>
        <div class="dialog-header-actions">
          <div class="alert-save">Изменения сохранены</div>
          <v-btn icon color="#5893D4">
            <span class="attach">
            <svg-sprite width="15" height="20" icon-id="attach"></svg-sprite>
            </span>
          </v-btn>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="closeProperty()">
            <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
          </v-btn>
        </div>
      </div>
      <div class="dialog-content dialog-content-large page characteristic-page edit-option">
        <div class="detail-close" @click="closeProperty">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад к категории</span>
        </div>
        <PropertyEdit
            :categoryEdit="categoryEdit"
            :key="componentKey"
            ref="updateProperty"
            isCategory
        ></PropertyEdit>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              style="font-size: 13px"
              @click="closeProperty()"
          >
            Назад
          </v-btn>
          <v-btn
              class="popup-btn"
              color="#5893D4"
              dark
              style="font-size: 13px"
              @click="updateProperty()"
          >
            Сохранить и добавить
          </v-btn>
        </div>
      </div>
    </v-dialog>

  </div>
</template>

<script>
// vuex
import {mapGetters} from 'vuex';
// components
import CategoryTree from "@/components/dashboard/products/nomenclature/modals/froms/CategoryTree";
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit";
import PropertyEdit from "@/components/dashboard/properties/PropertyEdit";
import ProgressBar from "@/components/dashboard/categories/ProgressBar";
import Switcher from "@/components/ui/Swithcer";

export default {
  name: "Category",
  props: {
    closeDetails: {
      type: Function
    }
  },
  components: {
    CategoryTree,
    Switcher,
    CharacteristicEdit,
    PropertyEdit,
    ProgressBar
  },
  computed: {
    ...mapGetters([
      'category',
      'dataEdit'
    ]),
    getProgressList() {
      const { characteristics, properties, image, unit_id, desc } = this.category || {};

      return [image, unit_id, characteristics, properties, desc];
    }
  },
  watch: {
    $route: 'fetchData',
    category(val) {
      this.categoryTree = []
      this.getCategoryTree(val)
    }
  },
  data: () => ({
    photo: false,
    switchShow: true,
    loading: true,
    dialogCharacteristic: false,
    dialogProperty: false,
    componentKey: 0,
    characteristicEdit: null,
    categoryEdit: null,
    categoryTree: [],
    progressValue: 0
  }),
  methods: {
    getCategoryTree({title, id, parent}) {
      const index = this.categoryTree.findIndex(category => category.id === id)
      if (index === -1) {
        this.categoryTree.push({title, id})
        if (parent) {
          this.getCategoryTree(parent)
        }
      }
    },
    onToggleVisible ({value}) {
      this.switchShow = value
    },
    updateItem() {
      this.closeCharacteristic()
      this.$refs.updateItem.updateItem()
    },
    updateProperty() {
      this.closeProperty()
      this.$refs.updateProperty.updateProperty()
    },
    async fetchData () {
      await this.$store.dispatch('fetchCategory', this.$route.params.params)
    },
    openCharacteristic() {
      this.dialogCharacteristic = true
    },
    async editCharacteristic(element) {
      try {
        await this.$store.dispatch('clearDataEdit')
        await this.$store.dispatch('editCharacteristic', element)
        this.characteristicEdit = this.dataEdit;

        this.forceRerender()
        this.dialogCharacteristic = true
      } catch (e) {
        console.log(e)
      }
    },
    async editProperty(element) {
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
    closeCharacteristic() {
      this.$store.dispatch('clearDataEdit')
      this.dialogCharacteristic = false
    },
    closeProperty() {
      this.$store.dispatch('clearDataEdit')
      this.dialogProperty = false;
    },
    forceRerender() {
      this.componentKey += 1;
    },
    goBack() {
      this.$router.push('/products/categories/');
    }
  },
  created () {
    this.fetchData()
  },
  beforeDestroy() {
    this.closeDetails()
  },
  async mounted() {
    this.closeDetails()
    await this.$store.dispatch('fetchCategory', this.$route.params.params)
    this.loading = false;
  }
}
</script>

<style scoped lang="scss">
.value-item svg {
  cursor: pointer;
}

.label-title {
  font-size: 10px;
}

.switch-nesting {
  .switch-text {
    &:first-child {
      margin-right: 10px;
    }
    &:last-child {
      margin-left: 10px;
    }
  }
  padding-bottom: 20px;
}

</style>
