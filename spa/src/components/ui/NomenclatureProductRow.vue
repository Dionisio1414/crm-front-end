<template>
  <tr :key="item.id"
      v-click-outside="onClickOutside"
      @contextmenu.prevent="openMenu($event)"
      :class="{'selected': selected}"
  >
    <td>
      <div class="checkbox" @click.stop="(() => false)">
        <label class="checkbox-label">
          <input type="checkbox" @change="selectItem(item)" :checked="selected">
          <div class="checkbox-text"></div>
        </label>
      </div>
    </td>
    <td v-for="(value, key) in items[1]" :key="key" :ref="key" @click="selectItem(item)">
      <div v-if="value.value ==='is_visible'" class="table-switcher">
        <switcher @toggle="toggleVisible(item)" :value="item.cells[value.value]"></switcher>
      </div>
      <price-popup
          v-else-if="value.value.includes('price_')"
          @clickPrice="onClickViewItem(item)"
          :price-value="item.cells[value.value]"
          :options="item.cells[`${value.value}_option`]"
          :price-name="value.value"
          @changePrice="onChangePrice">
      </price-popup>
      <span v-else @click.stop="onClickViewItem">{{ item.cells[value.value] }}</span>
    </td>
    <td></td>
    <td class="row-actions">
      <div class="nomenclature-actions">

        <span :class="{'active': isEditPurchase}">
          <div v-if="isEditPurchase" class="nomenclature-counter">
          <input class="small" type="number" v-model="purchaseCount">
          <div class="nomenclature-counter-buttons">
             <span @click.stop="purchaseCount +=1">+</span>
            <span @click.stop="purchaseCount -=1">-</span>
          </div>
          <span class="units">шт</span>
          <button class="nomenclature-counter-submit"></button>
        </div>
                <svg @click="openPurchaseModal" width="20" height="24" viewBox="0 0 20 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.45126 4.60156H0V6.44171H2.98918L5.56221 18.1064H5.57336V18.4027H17.649V18.1745L19.686 9.28566L20 8.28186H5.25289L4.72342 5.87954L4.45126 4.60156Z"
                        fill="#BBDBFE"/>
                  <path
                      d="M8.35974 23.0045C8.85246 23.0045 9.32499 22.8106 9.67339 22.4655C10.0218 22.1205 10.2175 21.6524 10.2175 21.1644C10.2175 20.6763 10.0218 20.2083 9.67339 19.8632C9.32499 19.5181 8.85246 19.3242 8.35974 19.3242C7.86702 19.3242 7.39449 19.5181 7.04609 19.8632C6.69768 20.2083 6.50195 20.6763 6.50195 21.1644C6.50195 21.6524 6.69768 22.1205 7.04609 22.4655C7.39449 22.8106 7.86702 23.0045 8.35974 23.0045Z"
                      fill="#BBDBFE"/>
                  <path
                      d="M16.7195 21.1644C16.7195 21.6524 16.5237 22.1205 16.1753 22.4655C15.8269 22.8106 15.3544 23.0045 14.8617 23.0045C14.369 23.0045 13.8964 22.8106 13.548 22.4655C13.1996 22.1205 13.0039 21.6524 13.0039 21.1644C13.0039 20.6763 13.1996 20.2083 13.548 19.8632C13.8964 19.5181 14.369 19.3242 14.8617 19.3242C15.3544 19.3242 15.8269 19.5181 16.1753 19.8632C16.5237 20.2083 16.7195 20.6763 16.7195 21.1644Z"
                      fill="#BBDBFE"/>
                  <path
                      d="M11.5183 0.000284549L15.6055 5.93045L13.4035 5.93045L13.4035 10.1211L9.63315 10.1211L9.63315 5.93045L7.43121 5.93045L11.5183 0.000284549Z"
                      fill="#BBDBFE"/>
              </svg>
              </span>
        <span :class="{'active': isEditSales  }">
          <div v-if="isEditSales" class="nomenclature-counter">
          <input class="small" type="number" v-model="salesCount">
          <div class="nomenclature-counter-buttons">
            <span @click.stop="salesCount +=1">+</span>
            <span @click.stop="salesCount -=1">-</span>
          </div>
          <span class="units">шт</span>
          <button class="nomenclature-counter-submit"></button>
        </div>
                <svg @click="openSalesModal" width="20" height="20" viewBox="0 0 20 20" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.18154 12.7266H5.45436V14.545C5.45436 15.0469 5.0471 15.4542 4.5453 15.4542C4.0435 15.4542 3.63624 15.0469 3.63624 14.545V12.7266H0.90906C0.407259 12.7266 0 13.1339 0 13.6358V19.0911C0 19.593 0.407259 20.0003 0.90906 20.0003H8.18154C8.68335 20.0003 9.0906 19.593 9.0906 19.0911V13.6358C9.0906 13.1339 8.68335 12.7266 8.18154 12.7266Z"
                    fill="#BBDBFE"/>
                <path
                    d="M19.0897 12.7266H16.3626V14.545C16.3626 15.0469 15.9553 15.4542 15.4535 15.4542C14.9517 15.4542 14.5444 15.0469 14.5444 14.545V12.7266H11.8173C11.3155 12.7266 10.9082 13.1339 10.9082 13.6358V19.0911C10.9082 19.593 11.3155 20.0003 11.8173 20.0003H19.0897C19.5915 20.0003 19.9988 19.593 19.9988 19.0911V13.6358C19.9988 13.1339 19.5915 12.7266 19.0897 12.7266Z"
                    fill="#BBDBFE"/>
                <path d="M9.92134 10.0004L5.92188 4.1408H8.07658V0H11.7661V4.1408H13.9208L9.92134 10.0004Z"
                      fill="#BBDBFE"/>
              </svg>
              </span>
      </div>

    </td>
    <context-menu
        @context-event="onContextMenuEvent"
        ref="contextMenuParent"
        :state="menuState"
        :actions="actions"/>
  </tr>
</template>

<script>
import {eventBus} from "@/main";
import PricePopup from "./NomeclatureTable/PricePopup";
import ContextMenu from "../ui/ContextMenu/ContextMenu";
import {NOMENCLATURE_RESOURCES} from "@/constants/constants"
import Switcher from "./Swithcer";

export default {
  name: "NomenclatureProductRow",
  components: {
    Switcher,
    ContextMenu,
    PricePopup
  },
  props: ['items', 'item', 'selected', 'actions'],
  data() {
    return {
      priceIsEdit: false,
      isEditPurchase: false,
      isEditSales: false,
      menuState: false,
      resource: NOMENCLATURE_RESOURCES.PRODUCTS,
      salesCount: 0,
      purchaseCount: 0
    }
  },
  computed: {
    argumentsItem() {
      return {
        id: this.item.id,
        parentId: null,
        isGroup: false,
        isChild: false,
        isKit: false,
        resource: this.resource,
        title: this.item.cells.short_title,
      }
    }
  },
  mounted() {
    eventBus.$on('selectedAllNomenclatures', (val) => {
      if (!val && !this.selected) {
        this.selectItem()
      }
    })
  },
  methods: {
    onContextMenuEvent(action) {
      this.$emit('emit-row-action', {argumentsItem: this.argumentsItem, action})
    },
    onMove() {
      this.$emit('move-item', this.argumentsItem)
    },
    onCopy() {
      this.$emit('copy-item', this.argumentsItem)
    },
    onUpdate() {
      this.$emit('update-item', this.argumentsItem)
    },
    onDelete() {
      this.$emit('delete-item', this.argumentsItem)
    },
    onOutActual() {
      this.$emit('out-actual-item', this.argumentsItem)
    },
    onToActual() {
      this.$emit('to-actual-item', this.argumentsItem)
    },
    openMenu(event) {
      eventBus.$emit('close-context-menu')
      this.$refs.contextMenuParent.open(event)
    },
    onClickViewItem() {
      this.$emit('view-item', this.argumentsItem)
    },
    openSalesModal() {
      this.isEditSales = !this.isEditSales
      this.isEditPurchase = false
    },
    onClickEditPrice(price) {
      this.priceIsEdit = price
    },
    openPurchaseModal() {
      this.isEditPurchase = !this.isEditPurchase
      this.isEditSales = false
    },
    onClickOutside() {
      this.isEditPurchase = false
      this.isEditSales = false
    },
    onChangePrice(params) {
      const data = {
        ...this.argumentsItem,
        ...params
      }
      this.$emit('changePrice', data)
    },
    selectItem() {
      this.$emit('select-item', this.argumentsItem)
    },
    toggleVisible() {
      this.$emit('toggleVisible', {
        ...this.argumentsItem,
        is_visible: this.item.cells.is_visible,

      })
    },
  }
}
</script>

<style scoped lang="sass">
td
  &.row-actions
      position: sticky
      right: 1px

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

</style>
