<template>
  <div class="page properties-page">
    <div class="page-header">
      <div class="actions-section">
        <div class="search-block">
          <input type="text" :placeholder="$t('page.search')" v-model="search">
          <div class="search-icon">
            <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
          </div>
        </div>
        <div class="actions-buttons">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-bind="attrs" v-on="on" icon color="indigo" class="action-btn sortable-btn" @click="sortByName">
                <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
              </v-btn>
            </template>
            <span>Сортировка по алфавиту</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-bind="attrs" v-on="on" color="#9DCCFF" fab small class="action-btn circle-btn"
                     @click="cloneCheck()" :disabled="selectProperties.length > 1 || selectProperties.length === 0">
                <svg-sprite width="16" height="16" icon-id="contextMenuCopy"></svg-sprite>
              </v-btn>
            </template>
            <span>Копировать</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-bind="attrs" v-on="on" color="#9DCCFF" fab small class="action-btn circle-btn"
                     @click="removeProperty" :disabled="selectProperties.length == 0">
                <svg-sprite width="13" height="15" icon-id="bin"></svg-sprite>
              </v-btn>
            </template>
            <span>Удалить</span>
          </v-tooltip>
        </div>
      </div>
      <v-btn class="orange-btn" @click="addProperty()" :disabled="!(!create && !edit)">
        <svg-sprite width="14" height="14" icon-id="plus"></svg-sprite>
        <span>{{ $t('page.addButton') }}</span>
      </v-btn>
    </div>
    <div class="card-grid">
      <simplebar class="card list-items" force-visible>
        <div class="check-all">
          <input type="checkbox" id="all" @click="selectAll" v-model="allSelected">
          <label for="all">{{ $t('page.chooseAll') }}</label>
        </div>
        <v-skeleton-loader
            :loading="loading"
            type="sentences@15"
        >
          <draggable
              class="list-group"
              tag="ul"
              v-model="propertiesList"
              v-bind="dragOptions"
              @start="drag = true"
              @end="drag = false"
          >
            <transition-group type="transition" :name="!drag ? 'flip-list' : null">
              <li
                  class="list-group-item"
                  :class="{'active': currentId === element.id}"
                  v-for="element in modelAndBrand"
                  :key="element.title"
              >
                <div @click="propertyEdit(element)">
                  <span>{{ element.title }}</span>
                </div>
              </li>
              <li
                  class="list-group-item"
                  :class="{'active': currentId === element.id}"
                  v-for="(element, index) in propertiesList"
                  :key="index"
                  @contextmenu.prevent="$refs.menu.open($event, { element, index })"
              >
                <input type="checkbox" v-model="element.check" @click="checkSelectedCheckboxes">
                <div @click="propertyEdit(element)">
                  <span>{{ element.title }}</span>
                </div>
                <div class="sort-buttons">
                  <v-btn color="#9DCCFF" fab small
                         class="action-btn circle-btn swap-btn"
                         @click="move(index,index - 1)"
                         :disabled="index === 0"
                  >
                    <svg-sprite width="6" height="12" icon-id="sortArrowUp"></svg-sprite>
                  </v-btn>
                  <v-btn color="#9DCCFF" fab small
                         class="action-btn circle-btn swap-btn"
                         @click="move(index,index + 1)"
                         :disabled="index===(propertiesList.length-1)"
                  >
                    <svg-sprite width="6" height="12" icon-id="sortArrowDown"></svg-sprite>
                  </v-btn>
                </div>
              </li>
            </transition-group>
          </draggable>

          <vue-context class="context-menu" ref="menu" v-slot="{ data }">
            <li class="context-menu-item">
              <div class="item-icon">
                <svg-sprite width="13" height="13" icon-id="contextMenuCopy"></svg-sprite>
              </div>
              <a @click.prevent="onClickCopy($event.target, data)">Копировать</a>
            </li>
            <li class="context-menu-item">
              <div class="item-icon">
                <svg-sprite width="13" height="13" icon-id="contextMenuEdit"></svg-sprite>
              </div>
              <a @click.prevent="onClickEdit($event.target, data)">Редактировать</a>
            </li>
            <li class="context-menu-item">
              <div class="item-icon">
                <svg-sprite width="10" height="11" icon-id="contextMenuRemove"></svg-sprite>
              </div>
              <a @click.prevent="onClickDelete($event.target, data)">Удалить</a>
            </li>
          </vue-context>

        </v-skeleton-loader>
      </simplebar>
      <div class="card detail-item">
        <div @click="closeDetails" class="detail-close" v-show="create || edit">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад</span>
        </div>
        <div class="select-item" v-if="!create && !edit">
          <img src="@/assets/icons/area-bg.svg" alt="">
          <span>
            {{ $t('page.selectItem') }}
          </span>
        </div>
        <PropertyCreate v-if="create" :closeDetails="closeDetails" @show-alert="onShowAlert"/>
        <keep-alive>
          <PropertyEdit v-if="edit" :closeDetails="closeDetails" @show-alert="onShowAlert" :categoryEdit="categoryEdit"
                        :key="componentKey" v-click-outside="onClickOutside"/>
        </keep-alive>
      </div>
    </div>
    <alert @close-alert="onCloseAlert"
           @show-item="onShowItem"
           :text-items="alertTextItems" :link="alertLink"
           v-if="isAlertShow"/>
    <Dialog ref="confirm"/>
    <DialogSave ref="confirmSave">
      <div class="header-text" slot="dialog-header">
        <span>Копирование</span>
      </div>
      <div class="dialog-text" slot="dialog-text" v-if="clone">
        Сохранить скопированое свойство
        <span style="font-weight: 550">{{ clone.name }}</span> ?
      </div>
    </DialogSave>
  </div>
</template>

<script>
import PropertyCreate from "@/components/dashboard/properties/PropertyCreate"
import PropertyEdit from "@/components/dashboard/properties/PropertyEdit"
import Alert from "@/components/dashboard/products/nomenclature/ui/Alert"
import Dialog from "@/components/ui/dialog/DialogDelete"
import DialogSave from "@/components/ui/dialog/DialogSave"
import {eventBus} from "@/main"
import {mapGetters} from 'vuex'

Array.prototype.move = function (from, to) {
  this.splice(to, 0, this.splice(from, 1)[0]);
  return this;
};

export default {
  name: "PropertiesProducts",
  components: {
    PropertyCreate,
    PropertyEdit,
    Alert,
    Dialog,
    DialogSave
  },
  data: () => ({
    currentId: null,
    search: '',
    create: false,
    edit: false,
    drag: false,
    loading: true,
    categoryEdit: null,
    delete: false,
    selected: [],
    allSelected: false,
    dataSelect: [],
    componentKey: 0,
    checkSave: false,
    alertTextItems: null,
    alertLink: null,
    isAlertShow: false
  }),
  created() {
    // eventBus.$on('delete', data => {
    //  this.delete = true
    // })
  },
  computed: {
    ...mapGetters([
      'deleteConfirm',
      'properties',
      'changeItem'
    ]),
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    selectProperties() {
      if (this.properties) {
        return this.properties.filter((prop, index) => {
              if (prop.check) {
                const data = {
                  element: prop,
                  index: index
                }
                this.dataSelect = data
                //console.log(this.dataSelect)
                return this.dataSelect
              }
            }
        )
      } else {
        return ''
      }
    },
    modelAndBrand() {
      if (this.properties) {
        const properties = this.properties.filter(property => property.id === 1 || property.id === 2)
        return properties
      }
      return []
    },
    propertiesList: {
      get() {
        if (this.properties) {
          const properties = this.properties.filter(property => property.id !== 1 && property.id !== 2)
          return properties.filter(
              prop => prop.title.toLowerCase().includes(this.search.toLowerCase())
          )
        } else {
          return null
        }
      },
      set(value) {
        const sortedPropertiesList = [...this.modelAndBrand, ...value]
        this.$store.dispatch('updatePropertiesList', sortedPropertiesList)
      }
    },
    checkedProperties() {
      if (this.properties) {
        return this.properties.filter(prop => {
          if (!prop.check) {
            return true
          }
        })
      } else {
        return ''
      }
    },
    clone() {
      return this.$store.getters['cloneCharacteristic']
    }

  },
  watch: {},
  methods: {
    onShowItem(params) {
      this.propertyEdit(params)
    },
    showAlert() {
      this.isAlertShow = true
      setTimeout(
          () => {
            this.isAlertShow = false
          },
          2500
      )
    },
    onShowAlert({textItems, link}) {
      this.alertTextItems = textItems
      this.alertLink = link || false
      this.showAlert()
    },
    onCloseAlert() {
      this.isAlertShow = false
    },
    move(from, to) {
      this.propertiesList = this.propertiesList.move(from, to);
    },

    forceRerender() {
      this.componentKey += 1;
    },

    /* The method to clone the item that was checked */
    cloneCheck() {
      let text = ''
      this.onClickCopy(text, this.dataSelect)
      this.propertiesList.forEach(prop => prop.check = false)
    },

    /* Methods for context menu */
    async onClickCopy(text, data) {
      /* data -  is the element that was clicked to copy from the context menu */
      //await this.$store.dispatch('clearClone')
      await this.$store.dispatch('cloneProperty', data)
      await this.propertyEdit(this.$store.getters['clone'])
    },
    onClickEdit(text, data) {
      console.log(data)
      this.propertyEdit(data.element)
    },
    onClickDelete(text, data) {
      this.checkSelectedCheckboxes()
      console.log('Delete - context menu')
      console.log(data.index)
      data.element.check = true
      this.removeProperty()
    },

    /* Method for transferring data to a popup */
    openDialog() {
      eventBus.$emit('openDialog', {
        name: 'property',
        names: 'properties'
      })
    },

    selectAll() {
      console.log('Select All', this.propertiesList)
      this.propertiesList.forEach(prop => {
        if (!this.allSelected) {
          prop.check = true
          this.checkSelectedCheckboxes()
          this.closeDetails()
        } else {
          prop.check = false
          this.checkSelectedCheckboxes()
        }
      })
    },

    checkSelectedCheckboxes() {
      const selected = []
      this.propertiesList.forEach(prop => {
        if (prop.check) {
          selected.push(prop)
        }
      })
      this.selected = selected
    },

    async removeProperty(index) {
      console.log(index)
      console.log(this.propertiesList)
      let removeArrayID = this.propertiesList.filter(val => {
        if (val.check) {
          return [val.id]
        }
      }).map((val) => {
        return {
          id: val.id
        }
      })
      const properties = {...removeArrayID}
      this.checkSelectedCheckboxes()
      if (await this.$refs.confirm.open(
          'Delete',
          'Are you sure?'
          , {
            name: this.selected.length > 1 ? 'выбраные свойства' : `свойство  ${this.selected[0].title}`,
            nameLength: this.selected.length > 1 ? 'выбраные свойства' : `свойство  ${this.selected[0].title}`
          })) {
        await this.$store.dispatch('removeProperty', [properties, index])
        this.allSelected = false
        this.closeDetails()
      } else {
        this.propertiesList.forEach(prop => {
          prop.check = false
        })
      }
    },

    onClickOutside() {
      console.log('onClickOutside')
    },

    async saveConfirm() {
      this.checkSelectedCheckboxes()
      if (await this.$refs.confirmSave.open(
          'Save',
          'Are you sure?'
      )) {
        await this.$store.dispatch('updateProperty', this.$store.getters['cloneCharacteristic'])
        await this.$store.dispatch('clearClone')
        this.allSelected = false
      } else {
        await this.$store.dispatch('clearClone')
        this.closeDetails()
      }

    },

    sortByName() {
      this.$store.dispatch('sortProperties')
    },

    sort() {
      this.propertiesList = this.propertiesList.sort((a, b) => a.order - b.order);
    },

    propertyEdit(property) {
      this.currentId = property?.id;
      this.closeDetails()
      this.edit = true
      this.categoryEdit = property
      this.forceRerender()
    },
    addProperty() {
      this.create = true
    },
    closeDetails() {
      this.create = false
      this.edit = false
    }
  },
  beforeDestroy() {
    if (this.$store.getters['cloneCharacteristic']) {
      this.saveConfirm()
    }
  },
  async mounted() {
    try {
      await this.$store.dispatch('fetchProperties')
      this.loading = false
    } catch (e) {
      console.log(e)
    }
  },

  deactivated() {
    console.log('About has been deactivated')
  }
}
</script>

<style lang="sass" scoped>
.list-group-item.active::before
  content: ''
  background: #F4F9FF
  position: absolute
  left: 0
  right: 0
  top: 0
  bottom: 0
  z-index: -1
  pointer-events: none

</style>
