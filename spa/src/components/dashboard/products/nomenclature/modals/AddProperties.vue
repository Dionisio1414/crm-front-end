<template>
  <div>
    <v-dialog
        max-width="920px"
        overlay-opacity="0.7"
        v-model="dialog"
        class="dialog-large update-item"
    >
      <div class="dialog-header">
        <div class="header-text">
         {{$t('nomenclature.add_property_product')}}
        </div>
      </div>
      <div class="dialog-content dialog-content-large">
        <div class="item-from add-product-stat">
          <div class="item">
            <div class="search-list">
              <div class="search-block">
                <input type="text" :placeholder="$t('page.search')" v-model="searchProperties">
                <div class="search-icon">
                  <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
                </div>
                <v-btn icon class="add-new" @click="createProperty" color="#5893D4">
                  <svg-sprite width="30" height="30" icon-id="plusFill"></svg-sprite>
                </v-btn>
              </div>
              <simplebar class="wrap-list" force-visible>
                <ul class="list" v-if="properties">
                  <li
                      v-for="element in searchedProperties"
                      :key="element.id"
                      :class="{selected: selectProperties.find(({id}) => id === element.id)}"
                  >
                    <span>{{ element.title }}</span>
                    <span @click="editProperty(element)" class="item-edit">
                      <svg-sprite style="color: rgb(187,219,254)" width="23" height="23" icon-id="editSmall"></svg-sprite>
                    </span>
                    <span class="remove-value" @click="unselectProperty(element.id)" :class="{ disabled: category.properties.findIndex(item => item.id === element.id) !== -1}">
                      <svg-sprite width="30" height="30" icon-id="minus"></svg-sprite>
                    </span>
                    <span class="add-value" @click="selectProperty(element)">
                      <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                    </span>
                  </li>
                </ul>
              </simplebar>
            </div>
          </div>
          <div class="item">
            <div class="label-title">
              {{$t('nomenclature.products_chars')}}
            </div>
          </div>
          <div class="values-list">
            <div class="value-item" v-for="(element, index) in selectProperties" :key="`${element.title}${element.id}`">
              <span>{{ element.title }}</span>
              <span @click="deleteChipProperty(index)"
                    v-if="category.properties.findIndex(item => item.id === element.id) === -1">
                <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
              </span>
            </div>
          </div>
          <property-create @save-property="onEmitChangeProperty" @click-outside="isCreate = false" v-if="isCreate" :closeDetails="closeDetails"></property-create>
          <property-edit @save-property="onEmitChangeProperty" @click-outside="isEdit = false" v-if="isEdit" :categoryEdit="propertyEdit" :closeDetails="closeDetails"></property-edit>
        </div>
      </div>
      <div class="dialog-content dialog-content-large">
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              @click="close()"
          >
            Назад
          </v-btn>
          <v-btn
              class="popup-btn"
              color="#5893D4"
              dark
              @click="save"
          >
            Добавить
          </v-btn>
        </div>
      </div>
    </v-dialog>
  </div>

</template>

<script>
import {mapGetters, mapActions} from 'vuex'
import PropertyCreate from "@/components/dashboard/properties/PropertyCreate";
import PropertyEdit from "@/components/dashboard/properties/PropertyEdit";

export default {
  name: "AddProperties",
  components: {PropertyEdit, PropertyCreate},
  data() {
    return {
      dialog: false,
      isEdit: false,
      isCreate: false,
      propertyEdit: {},
      editChar: false,
      searchProperties: '',
      selectProperties: []
    }
  },
  computed: {
    ...mapGetters({
      properties: 'properties',
    }),
    searchedProperties () {
      if (this.searchProperties.length >= 3) {
        return [...this.properties].filter(item => item.title.includes(this.searchProperties))
      }
      else return this.properties
    }

  },
  props: [
    'category'
  ],
  methods: {
    ...mapActions({
      fetchData: 'fetchProperties',
      changeCategory: 'changeCategory'
    }),
    onEmitChangeProperty() {
      this.$emit('save-property')
    },
    open() {
      this.dialog = true
      this.selectProperties = []
      this.searchProperties = ''
      this.showSelectedProperties()
      this.fetchData()
    },
    save() {
      const updatedCategory = JSON.parse(JSON.stringify(this.category))
      updatedCategory.properties = [...this.selectProperties]
      this.changeCategory(updatedCategory).then(() => this.close())
    },
    showSelectedProperties() {
      if (this.category.properties.length > 0) {
        this.selectProperties.push(...this.category.properties)
      }
    },
    agree() {

    },
    closeDetails() {
      this.isCreate = false;
      this.isEdit = false;
    },
    close() {
      this.dialog = false
    },
    deleteChipProperty(index) {
      this.selectProperties.splice(index, 1)
    },
    unselectProperty(id) {
      const index = this.selectProperties.findIndex(item => item.id === id)
      this.selectProperties.splice(index, 1)
    },
    selectProperty(item) {
      this.selectProperties.push(item)
    },
    createProperty() {
      this.isCreate = true
    },
    editProperty(item) {
      this.propertyEdit = item
      this.isEdit = true
    }

  }
}
</script>

<style scoped lang="scss">
.item-from {
  width: 100%;
  max-width: 760px;
  padding-bottom: 30px;
  padding-top: 30px;

  .search-block {
    width: 88%;
    max-width: 100%;
  }

  .remove-value {
    &.disabled {
      pointer-events: none;
      opacity: .3;
    }
  }

  .search-list {
    height: 300px;
    margin-bottom: 26px;

    .wrap-list {
      height: 70%;
    }
  }
  input[type=number] {
   &::-webkit-outer-spin-button  {
     display: none;
   }
  }
}

.list li span ~ span {
  margin-right: 0;
}
.values-list .value-item span ~ span {
  margin-right: 0;
}
</style>
