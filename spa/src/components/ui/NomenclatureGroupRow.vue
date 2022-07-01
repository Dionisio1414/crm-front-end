<template>
  <tr class="is-group" :key="item.id"
      @contextmenu.prevent="openMenu($event)"
      :class="{'selected': selected}">
    <td>
      <div class="wrapper">
        <div class="checkbox" @click.stop="(() => false)">
          <label class="checkbox-label">
            <input type="checkbox" @change="selectAllGroups" value="" :checked="selected">
            <span class="checkbox-text"></span>
          </label>
        </div>
        <span :class="{'disabled': !groupsChildLength, 'active': isOpen }" @click="onOpenChildren(item.id)" class="open-child">
          <svg-sprite width="25" height="20" icon-id="btnGroup"></svg-sprite>
        </span>
      </div>
    </td>
    <td @click="onClickRow()" v-for="(value, key) in items[1]" :key="key" :ref="key">
      <div v-if="value.value ==='is_visible'" class="table-switcher">
        <div v-if="groupsChildLength" class="switcher" :class="{'active' : !!isVisible}"
             @click.stop="toggleVisible(item)">
          <div class="switcher-caret"></div>
        </div>
        <div class="switcher-label" v-if="groupsChildLength"><span class="visible">{{ isVisible }}</span>/{{
            groupsChildLength
          }}
        </div>
      </div>
      <price-popup
          v-else-if="value.value.includes('price_')"
          @clickPrice="onClickChangeItem(item)"
          :price-value="item.cells[value.value]"
          :options="item.cells[`${value.value}_option`]"
          :price-name="value.value"
          @changePrice="onChangePrice"
      />
      <span v-else @click.stop="onClickViewItem(item)">{{ item.cells[value.value] }}</span>
    </td>
    <td></td>
    <td></td>
    <context-menu
        @context-event="onContextMenuEvent"
        ref="contextMenuParent"
        :state="menuState"
        :actions="formattedActions"/>
  </tr>
</template>

<script>
import PricePopup from "./NomeclatureTable/PricePopup";
import {NOMENCLATURE_RESOURCES, TABLE_ACTIONS} from "@/constants/constants";
import ContextMenu from "../ui/ContextMenu/ContextMenu";
import {eventBus} from "@/main";

export default {
  name: "NomenclatureGroupRow",
  components: { ContextMenu, PricePopup },
  props: ['items', 'item', 'selected', 'actions', 'selectedItems'],
  data() {
    return {
      isOpen: false,
      isEditPurchase: false,
      isEditSales: false,
      menuState: false,
      salesCount: 0,
      resource: NOMENCLATURE_RESOURCES.PRODUCTS,
      purchaseCount: 0
    }
  },
  computed: {
    isVisible() {
      if (this.item.groups) {
        return this.item.groups.filter(item => item.cells.is_visible).length
      }
      return null
    },
    formattedActions() {
      return this.actions.map(action => {
        if (action.action === TABLE_ACTIONS.COPY) {
          return {
            action: action.action,
            disabled: true,
          }
        }
        return action
      })
    },
    isAllSelected() {
      if (Array.isArray(this.item.groups)) {
        return this.item.groups.every(groupsItem => this.selectedItems.some(selectedItem => selectedItem.id === groupsItem.id))
      }

      return this.item.groups;
    },
    argumentsItem() {
      return {
        id: this.item.id,
        parentId: null,
        isGroup: true,
        isChild: false,
        isKit: false,
        resource: this.resource,
        title: this.item.cells.short_title,
        products: this.item.groups || [].map(product => ({ id: product.id }))
      }
    },
    groupsChildLength() {
      if (this.item.groups) {
        return this.item.groups.length
      }
      return null

    }
  },
  watch: {
    isAllSelected() {
      this.$emit('select-item', {
        ...this.argumentsItem
      })
    }
  },
  methods: {
    onOpenChildren(id) {
      this.isOpen = !this.isOpen;
      const values = {
        value: this.isOpen,
        id
      }

      this.$emit('open-child', values)
    },
    onContextMenuEvent(action) {
      this.$emit(`emit-row-action`, {argumentsItem: this.argumentsItem, action})
    },
    openMenu(event) {
      eventBus.$emit('close-context-menu')
      this.$refs.contextMenuParent.open(event)
    },
    onClickViewItem() {
      this.$emit('view-item', this.argumentsItem)
    },
    onChangePrice(params) {
      const data = {
        ...this.argumentsItem,
        ...params
      }
      this.$emit('changePrice', data)
    },
    onClickChangeItem(item) {
      this.$emit('changeItem', item)
    },
    onClickRow(item) {
      this.selectAllGroups(item)
    },
    selectAllGroups () {
      if (this.item.groups.length) {
        const params = {
          id: this.item.id,
          isAllSelected: this.isAllSelected
        }

        eventBus.$emit('selectedAllChildNomenclatures', params)
      } else  {
        this.$emit('select-item', {
          ...this.argumentsItem
        })
      }
    },
    // selectItem() {
    //   this.$emit('select-item', {
    //     ...this.argumentsItem
    //   })
    // },
    toggleVisible() {
      const values = {
        ...this.argumentsItem,
        is_visible: !!this.isVisible,
      }

      this.$emit('toggleVisible', values)
    },
  }
}
</script>

<style scoped lang="sass">
td

  .wrapper
    display: flex

    svg
      transition: all .5s
      cursor: pointer

      &:hover
        transform: scale(1.1)

  .open-child
    margin-left: 15px
    &.active
      transform: rotate(180deg)
      position: relative
      bottom: 5px

    &.disabled
      opacity: 0.5
      pointer-events: none


  .nomenclature-actions
    display: flex
    justify-content: flex-start

    span
      position: relative
      margin-right: 10px

      &:last-child
        margin-right: 0

      path
        transition: .5s all

      &.active, &:hover
        svg
          path
            fill: #5893D4

    .nomenclature-counter
      width: 175px
      height: 121px
      background: #FFFFFF
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1)
      border-radius: 10px
      display: flex
      align-items: center
      justify-content: space-around
      padding: 20px
      position: absolute
      right: 0
      top: 100%
      z-index: 2

    .nomenclature-counter-buttons
      display: flex
      flex-direction: column
      margin-left: 1px
      margin-right: 10px

      span
        position: relative
        width: 25px
        height: 18px
        background: #E3F0FF
        border-radius: 10px
        text-align: center
        color: #5893D4
        cursor: pointer
        font-weight: 500

        &:first-child
          margin-bottom: 5px

    input
      border: 1px solid #E3F0FF
      height: 32px
      border-radius: 100px
      max-width: 106px
      outline: none !important
      padding: 0 10px
      text-align: center

    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button
      -webkit-appearance: none
      margin: 0

      &.small
        max-width: 80px

  .switcher-label
    color: #BBDBFE
    padding-left: 10px
    font-size: 14px
    line-height: 14px

    .visible
      color: #5893D4


</style>
