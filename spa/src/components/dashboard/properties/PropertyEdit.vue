<template>
  <div class="item-area" v-click-outside="onClickOutside">
    <div class="item-area-col">
      <form class="item-form">
        <div class="item-name">
          <label class="input-label">{{ $t('page.name') }}</label>
          <input :disabled="isModelOrBrand" type="text" :placeholder="$t('page.typeName')"
                 v-model.trim="propertyName">
          <small class="warn" v-if="!exists && clone && clone.id === categoryEdit.id">Измените название</small>
          <small v-if="exists" class="warn">{{$t('validation.uniq')}}</small>
        </div>
        <div class="item-values">
          <label class="input-label">{{ $t('page.addValueVariant') }}</label>
          <input type="text" :placeholder="$t('page.typeName')" v-model="itemValue"
                 @keypress.enter="saveChip">
          <small class="warn" v-if="valueExists">Значения характеристики <b>{{ itemValue }}</b> уже существует </small>
          <button
              type="button"
              :disabled="!itemValue.length || valueExists"
              @click="editbleChip ? updatePropertyValue() : saveChip()"
              class="base-button base-button--lighten--transparent"
          >{{ editbleChip ? $t("page.saveButton") : $t("page.addButton") }}
          </button>
        </div>
      </form>
    </div>
    <div class="item-area-col col-list">
      <draggable
          class="values-list"
          tag="div"
          v-model="values"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
      >
        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
          <div class="value-item"
               v-for="(value, i) in values"
               :key="i"
               :class="{'active' : value.title.toLowerCase() === itemValue.toLowerCase()}"
          >
            <span>{{ value.title }}</span>
            <span class="chip" @click="editChip(value, i)">
              <svg-sprite width="17" height="18" icon-id="edit_pencil_small"></svg-sprite>
            </span>
            <span class="chip" @click="deleteChip(i)">
              <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
            </span>
          </div>
        </transition-group>
      </draggable>
      <div class="used" v-if="usedCategories && usedCategories.length > 0">
        <div class="title">{{ $t('page.usedCategories') }}</div>
        <div class="used-list">
          <router-link :to="`/products/categories/${item.id}`" v-for="item in usedCategories" :key="item.id">
            <span>{{ item.title }}</span>
            <span>
              <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
            </span>
          </router-link>
        </div>
      </div>
      <div v-if="!isCategory" class="sticky">
        <div class="text">
          Результат редактирования опции и ее значений отображается в категориях и товарах, в которых используется опция и ее значения.
        </div>
        <v-btn
            @click="clone ? createProperty() : updateProperty()"
            :disabled="isCheck || exists || !propertyName.length"
            class="base-btn shadow-btn"
        >
          {{ $t('page.saveButton') }}
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'


export default {
  name: "PropertyEdit",
  props: {
    isCategory: Boolean,
    categoryEdit: {
      type: Object
    },
    closeDetails: {
      type: Function
    },
    set: {
      type: Boolean,
      default: true
    }
  },
  data: () => ({
    propertiesCheckList: null,
    propertyName: '',
    itemValue: '',
    values: [],
    editbleChip: '',
    editbleChipIndex: null,
    // valueExists: false,
    drag: false,
    usedCategories: null
  }),
  computed: {
    ...mapGetters({
      clone: 'clone',
      properties: 'properties'
    }),
    exists() {
      if (!this.propertyName || !this.properties.length) {
        return false
      }
      return this.propertyName !== this.categoryEdit.title &&
          !!this.properties.find(prop => prop.title === this.propertyName)
    },
    isModelOrBrand() {
      return this.categoryEdit.id === 1 || this.categoryEdit.id === 2
    },
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    valueExists() {
      if (!this.itemValue || !this.values.length) {
        return false
      }
      const itemValue = this.itemValue.toLowerCase();
      const editableChip = this.editbleChip.toLowerCase();

      return this.values.find(value => value.title.toLowerCase() === itemValue) && itemValue !== editableChip;
    },
    isCheck() {
      return this.categoryEdit.title === this.propertyName
          && this.propertiesCheckList.length === this.values.length && this.checkArray()
    }
  },
  methods: {
    ...mapActions({
      updateItem: "updateProperty",
      createItem: 'createProperty',
    }),
    /*changeName() {
      this.exists = this.$store.getters['properties'].some(prop => (prop.title || prop.name) === this.propertyName)
    },*/
    checkArray() {
      return this.values.reduce((acc, item) => {
        acc = this.propertiesCheckList.some(body => body === item.title);
        return acc;
      }, true)
    },
    saveChip() {
      const values = this.categoryEdit.property_value || this.categoryEdit.value;
      const itemValue = {
        id: null,
        order: 0,
        property_id: this.categoryEdit.id,
        title: this.itemValue
      }
      values.push(itemValue)
      this.itemValue = ''
    },
    editChip(value, index) {
      this.itemValue = value.title
      this.editbleChip = value.title
      this.editbleChipIndex = index
    },
    updatePropertyValue() {
      this.values[this.editbleChipIndex].title = this.itemValue
      this.itemValue = ''
      this.editbleChip = ''
      this.editbleChipIndex = null
    },


    deleteChip(index) {
      this.categoryEdit.property_value.splice(index, 1)
    },
    onClickOutside() {
      this.$emit('click-outside')
    },
    async createProperty() {
      this.$emit('save-property')
      const property = {
        title: this.propertyName,
        property_value: this.values,
        check: false
      }

      try {
        const item = await this.createItem(property)
        this.$emit('finishedAction')
        const alertParams = {
          textItems: [
            {text: 'char', style: 'normal', translate: true},
            {text: item.title, style: 'bold', translate: false},
            {text: 'created', style: 'normal', translate: true},
          ],
          link: {
            text: 'Показать',
            action: 'show-item',
            actionParams: item
          }
        }
        this.$emit('show-alert', alertParams)
        this.closeDetails()
      } catch (e) {
        console.log(e)
      }
    },
    async updateProperty() {
      this.$emit('save-property');
      const property = {
        id: this.categoryEdit.id,
        title: this.propertyName,
        values: this.property && this.property.values || this.values,
        check: false
      }

      this.$emit('finishedAction')
      try {
        const item = await this.updateItem(property)
        await this.$store.dispatch('fetchDataCreate')

        if (this.closeDetails) {
          this.closeDetails()
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
        this.$emit('show-alert', alertParams)
      } catch (e) {
        console.log(e)
      }
    }
  },

  mounted() {
    this.propertyName = this.categoryEdit.title;
    this.values = this.categoryEdit.property_value || this.categoryEdit.value;
    this.propertiesCheckList = this.categoryEdit.property_value.map(item => item.title);
    this.usedCategories = this.categoryEdit.categories
  }
}
</script>

<style scoped lang="scss">
.chip {
  cursor: pointer;
}

.sticky {
  position: sticky;
  bottom: 0;
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

.item-form {
  padding: 0;
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

input {
  line-height: 1.2;
  &:disabled {
    border-bottom: none !important;
  }
}
.item-area {
  margin: 0 -25px;
  &-col {
    padding: 0 25px;
  }
}
</style>
