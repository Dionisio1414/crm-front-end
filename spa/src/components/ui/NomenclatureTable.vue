<template>
  <div class="table-wrapper">
    <div class="table-responsive">
      <v-data-table
          :headers="formatHeader"
          :items="[body, formatHeader]"
          :sortable="true"
          :sort-desc.sync="sortDesc"
          :sort-by.sync="sortBy"
          hide-default-header
          hide-default-footer
          :items-per-page="11"
          class="table"
          ref="table"
      >
        <template v-slot:header="{props}">
          <thead>
          <tr>
            <th>
              <div class="checkbox">
                <label class="checkbox-label">
                  <input :checked="isAllSelected" type="checkbox" @click="toggleAll">
                  <span class="checkbox-text"></span>
                </label>
              </div>
            </th>
            <th
                v-for="header in props.headers"
                :key="header.id"
                :ref="header.value"
                v-show="header.is_visible"
                @click.prevent="toggleOrder($event, header.value)"
            >
              <span class="header-title">{{ header.title }}</span>
              <span class="sortable-icon" v-if="header.is_sortable">
                <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
              </span>
            </th>
            <th></th>
            <th class="thTable-settings">
              <div class="table-settings" v-click-outside="hideOutsideSettings">
                <button type="button" class="btn-settings" @click="showSettings">
                  <span class="icon">
                    <svg-sprite width="18" height="18" icon-id="settings"></svg-sprite>
                  </span>
                </button>
                <simplebar class="settings-popup" v-if="settingsFlag">
                  <div class="settings-title">настройки таблицы</div>
                  <draggable
                      class="settings-list"
                      tag="div"
                      v-bind="dragOptions"
                      @update="checkMove"
                      @start="drag = true"
                      @end="drag = false">
                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                      <div v-for="(val, index) in computedHeader" :key="val.id"
                           class="settings-list-item">
                        <div class="checkbox">
                          <label class="checkbox-label">
                            <input type="checkbox" v-model="val.is_visible">
                            <span class="checkbox-text">
                              <div class="text">{{ val.title }}</div>
                            </span>
                          </label>
                        </div>
                        <div class="sorting">
                          <v-btn color="#9DCCFF" fab small
                                 class="action-btn circle-btn swap-btn"
                                 @click.stop="move(index, index - 1)"
                                 :disabled="index === 0"
                          >
                            <svg-sprite width="6" height="12" icon-id="sort"></svg-sprite>
                          </v-btn>
                          <v-btn color="#9DCCFF" fab small
                                 class="action-btn circle-btn swap-btn"
                                 @click.stop="move(index, index + 1)"
                                 :disabled="index===(settingsTable.header.length-1)"
                          >
                            <svg-sprite width="6" height="12" icon-id="sortArrowDown"></svg-sprite>
                          </v-btn>
                        </div>
                      </div>
                    </transition-group>
                  </draggable>
                  <button type="button" class="base-btn darken-btn" @click="saveTableSettings">
                    Сохранить
                  </button>
                </simplebar>
              </div>
            </th>
          </tr>
          </thead>
        </template>
        <template v-slot:body="{items}">
          <tbody v-if="!isEmpty">
          <template v-for="(val, index) in items[0]">
            <nomenclature-product-row
                :key="val.id"
                v-if="!val['is_groups'] && resource==='products'|| val.type === 'product' && !val['is_groups']"
                @changePrice="onChangePrice"
                @select-item="selectItem"
                @view-item="onViewItem"
                @emit-row-action="onEmitRowAction"
                @toggleVisible="toggleVisible"
                :selected="onChecked(val.id)"
                :item="val"
                :actions="actions"
                product
                :items="items"
                @contextmenu.prevent="$refs.menu.open($event, { val, index })"
            />
            <nomenclature-group-row
                v-if="val['is_groups'] && resource==='products' || val.type === 'product' && val['is_groups']"
                :key="val.id"
                @emit-row-action="onEmitRowAction"
                @changePrice="onChangePrice"
                @select-item="selectItem"
                @view-item="onViewItem"
                @toggleVisible="toggleVisible"
                @contextmenu.prevent="$refs.menu.open($event, { val, index })"
                @open-child="openChild"
                :item="val"
                :actions="actions"
                :selected-items="selectedItems"
                :selected="onChecked(val.id)"
                :items="items" />
            <nomenclature-groups-product-row
                v-for="product in val.groups"
                :isOpenGroup="isOpenGroup"
                @select-item="selectItem"
                @toggleVisible="toggleVisible"
                @emit-row-action="onEmitRowAction"
                @view-item="onViewItem"
                @changePrice="onChangePrice"
                :selected="onChecked(product.id)"
                :parentId="val.id"
                :key="product.id"
                :actions="actions"
                :items="items"
                :item="product" />
            <nomenclature-service-row
                v-if="resource==='services' || val.type === 'service'"
                :key="val.id"
                :selected="onChecked(val.id)"
                @emit-row-action="onEmitRowAction"
                @view-item="onViewItem"
                @select-item="selectItem"
                @toggleVisible="toggleVisible"
                @changePrice="onChangePrice"
                :item="val"
                :actions="actions"
                :items="items" />
          </template>
          <tr v-if="body.length < 11"></tr>
          </tbody>
        </template>
      </v-data-table>
    </div>
    <div class="table-controls">
      <div class="controls-list">
        <div class="controls-list-item" @click="onClickCopy">
          <button :disabled="selectedItems.length !== 1" class="control-btn" type="button">
                <span class="icon">
                  <svg-sprite width="21" height="21" icon-id="contextMenuLargeCopy"></svg-sprite>
                </span>
          </button>
        </div>
        <div class="controls-list-item" style="display: none">
          <button class="control-btn" type="button">
                <span class="icon">
                  <svg-sprite width="21" height="21" icon-id="contextMenuLargeLoad"></svg-sprite>
                </span>
          </button>
        </div>
        <div class="controls-list-item">
          <button :disabled="!canTransfer" class="control-btn" type="button" @click="onClickMoveItems">
                <span class="icon">
                  <svg-sprite width="22" height="21" icon-id="contextMenuLargeAnotherMove"></svg-sprite>
                </span>
          </button>
        </div>
        <div class="controls-list-item">
          <button :disabled="selectedItems.length !== 1" @click="onClickChange" class="control-btn"
                  type="button">
                <span class="icon">
                  <svg-sprite width="23" height="23" icon-id="contextMenuLargeEdit"></svg-sprite>
                </span>
          </button>
        </div>
        <div class="controls-list-item">
          <button :disabled="selectedItems.length < 1" @click="onClickDel" class="control-btn" type="button">
                <span class="icon">
                  <svg-sprite width="19" height="21" icon-id="contextMenuLargeRemove"></svg-sprite>
                </span>
          </button>
        </div>
        <div v-if="actual" class="controls-list-item">
          <button class="control-btn" type="button" :disabled="selectedItems.length < 1"
                  @click="onClickAutActual">
                <span class="icon">
                 <svg width="28" height="20" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <mask id="path-1-outside-1" maskUnits="userSpaceOnUse" x="0" y="0" width="28" height="20"
                        fill="black">
                  <rect fill="white" width="28" height="20"/>
                  <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M21.35 8.04C21.0141 6.33772 20.0976 4.80486 18.7571 3.70325C17.4165 2.60163 15.7351 1.99961 14 2C11.11 2 8.6 3.64 7.35 6.04C5.88023 6.19883 4.52101 6.89521 3.53349 7.99532C2.54597 9.09543 1.99983 10.5217 2 12C2 15.31 4.69 18 8 18H21C23.76 18 26 15.76 26 13C26 10.36 23.95 8.22 21.35 8.04ZM16.8526 7L13.9912 9.8614L11.1474 7.01765L10.0177 8.14742L12.8614 10.9912L10 13.8526L11.1281 14.9807L13.9895 12.1193L16.8702 15L18 13.8702L15.1193 10.9895L17.9807 8.12814L16.8526 7Z"/>
                  </mask>
                  <path
                      d="M18.7571 3.70325L17.8047 4.86214L17.8047 4.86214L18.7571 3.70325ZM21.35 8.04L19.8784 8.33039L20.1007 9.4571L21.2464 9.53642L21.35 8.04ZM14 2V3.5L14.0003 3.5L14 2ZM7.35 6.04L7.51116 7.53132L8.30946 7.44505L8.68037 6.7329L7.35 6.04ZM3.53349 7.99532L2.41724 6.99332H2.41724L3.53349 7.99532ZM2 12H3.5V11.9998L2 12ZM13.9912 9.8614L12.9305 10.9221L13.9912 11.9827L15.0518 10.9221L13.9912 9.8614ZM16.8526 7L17.9132 5.93934L16.8526 4.87868L15.7919 5.93934L16.8526 7ZM11.1474 7.01765L12.2081 5.95699L11.1474 4.89633L10.0868 5.95699L11.1474 7.01765ZM10.0177 8.14742L8.95699 7.08676L7.89633 8.14742L8.95699 9.20809L10.0177 8.14742ZM12.8614 10.9912L13.9221 12.0518L14.9827 10.9912L13.9221 9.93051L12.8614 10.9912ZM10 13.8526L8.93934 12.7919L7.87868 13.8526L8.93934 14.9132L10 13.8526ZM11.1281 14.9807L10.0675 16.0414L11.1281 17.102L12.1888 16.0414L11.1281 14.9807ZM13.9895 12.1193L15.0502 11.0587L13.9895 9.998L12.9289 11.0587L13.9895 12.1193ZM16.8702 15L15.8096 16.0607L16.8702 17.1213L17.9309 16.0607L16.8702 15ZM18 13.8702L19.0607 14.9309L20.1213 13.8702L19.0607 12.8096L18 13.8702ZM15.1193 10.9895L14.0587 9.92889L12.998 10.9895L14.0587 12.0502L15.1193 10.9895ZM17.9807 8.12814L19.0414 9.1888L20.102 8.12814L19.0414 7.06748L17.9807 8.12814ZM17.8047 4.86214C18.8768 5.74314 19.6097 6.96902 19.8784 8.33039L22.8216 7.74961C22.4185 5.70642 21.3184 3.86658 19.7094 2.54435L17.8047 4.86214ZM14.0003 3.5C15.388 3.49969 16.7326 3.98115 17.8047 4.86214L19.7094 2.54435C18.1004 1.22212 16.0823 0.49953 13.9997 0.5L14.0003 3.5ZM8.68037 6.7329C9.68266 4.80851 11.6919 3.5 14 3.5V0.5C10.5281 0.5 7.51734 2.47149 6.01963 5.3471L8.68037 6.7329ZM4.64973 8.99732C5.39012 8.17251 6.4092 7.6504 7.51116 7.53132L7.18884 4.54868C5.35126 4.74726 3.65189 5.61791 2.41724 6.99332L4.64973 8.99732ZM3.5 11.9998C3.49987 10.8915 3.90934 9.82213 4.64973 8.99732L2.41724 6.99332C1.1826 8.36874 0.499785 10.1519 0.5 12.0002L3.5 11.9998ZM8 16.5C5.51843 16.5 3.5 14.4816 3.5 12H0.5C0.5 16.1384 3.86157 19.5 8 19.5V16.5ZM21 16.5H8V19.5H21V16.5ZM24.5 13C24.5 14.9316 22.9316 16.5 21 16.5V19.5C24.5884 19.5 27.5 16.5884 27.5 13H24.5ZM21.2464 9.53642C23.0753 9.66304 24.5 11.1634 24.5 13H27.5C27.5 9.55655 24.8247 6.77696 21.4536 6.54358L21.2464 9.53642ZM15.0518 10.9221L17.9132 8.06066L15.7919 5.93934L12.9305 8.80074L15.0518 10.9221ZM10.0868 8.07831L12.9305 10.9221L15.0518 8.80074L12.2081 5.95699L10.0868 8.07831ZM11.0783 9.20809L12.2081 8.07831L10.0868 5.95699L8.95699 7.08676L11.0783 9.20809ZM13.9221 9.93051L11.0783 7.08676L8.95699 9.20809L11.8007 12.0518L13.9221 9.93051ZM11.0607 14.9132L13.9221 12.0518L11.8007 9.93051L8.93934 12.7919L11.0607 14.9132ZM12.1888 13.9201L11.0607 12.7919L8.93934 14.9132L10.0675 16.0414L12.1888 13.9201ZM12.9289 11.0587L10.0675 13.9201L12.1888 16.0414L15.0502 13.18L12.9289 11.0587ZM17.9309 13.9393L15.0502 11.0587L12.9289 13.18L15.8096 16.0607L17.9309 13.9393ZM16.9393 12.8096L15.8096 13.9393L17.9309 16.0607L19.0607 14.9309L16.9393 12.8096ZM14.0587 12.0502L16.9393 14.9309L19.0607 12.8096L16.18 9.92889L14.0587 12.0502ZM16.9201 7.06748L14.0587 9.92889L16.18 12.0502L19.0414 9.1888L16.9201 7.06748ZM15.7919 8.06066L16.9201 9.1888L19.0414 7.06748L17.9132 5.93934L15.7919 8.06066Z"
                      fill="white" mask="url(#path-1-outside-1)"/>
                  </svg>
                </span>
          </button>
        </div>
        <div v-else class="controls-list-item">
          <button class="control-btn" type="button" :disabled="selectedItems.length < 1"
                  @click="onClickToActual">
                <span class="icon">
                  <svg-sprite width="28" height="20" icon-id="contextMenuLargeToActual"></svg-sprite>
                </span>
          </button>
        </div>
      </div>
      <div class="controls-scroll-top">
        <button type="button" class="btn-top" @click="onToTop">
              <span class="icon">
                <svg-sprite width="17" height="8" icon-id="toTop"></svg-sprite>
              </span>
        </button>
      </div>
    </div>
    <div class="total-quantity" v-if="!isEmpty">
      <span>Количество элементов: {{ total }}</span>
      <span>Выбрано элементов: {{ selectedItems.length }}</span>
    </div>
    <div v-else class="empty-table-text">
      {{ $t(this.emptyText) }}
    </div>
  </div>
</template>
<script>
// vuex
import {mapActions} from 'vuex';
// components
import NomenclatureProductRow from "@/components/ui/NomenclatureProductRow";
import NomenclatureGroupRow from "@/components/ui/NomenclatureGroupRow";
import NomenclatureGroupsProductRow from "@/components/ui/NomenclatureGroupsProductRow";
import NomenclatureServiceRow from "@/components/ui/NomenclatureServiceRow";
// services
import {httpClient} from "@/services";
import {MODE_TYPES} from "@/constants/constants";

Array.prototype.move = function (from, to) {
  this.splice(to, 0, this.splice(from, 1)[0]);
  return this;
};

export default {
  name: "NomenclatureTable",
  components: {
    NomenclatureServiceRow,
    NomenclatureGroupsProductRow,
    NomenclatureGroupRow,
    NomenclatureProductRow
  },
  props: {
    total: Number,
    header: Array,
    body: Array,
    resource: String,
    suppliersMenu: String,
    actual: Boolean,
    actions: Array,
    selectedItems: Array,
    emptyText: {
      type: String,
      default: 'page.empty_table_text'
    }
  },
  data: () => ({
    isOpenGroup: [],
    selected: false,
    sortDesc: false,
    sortBy: null,
    favorite: null,
    settingsFlag: false,
    drag: false,
    closeOnClick: false,
    settingsTable: {
      copyHeader: null,
      header: null,
      body: null,
      transformHeaders: null,
    }
  }),
  mounted() {
    const listElm = document.querySelector('.v-data-table__wrapper');
    listElm.addEventListener('scroll', () => {
      if (listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) this.$emit('scrolledToEnd')
    });
  },
  computed: {
    isEmpty() {
      return !this.body.length
    },
    canTransfer() {
      return this.selectedItems.length > 0 && !this.selectedItems.some(item => item.isChild && !this.selectedItems.find(nomenclature => nomenclature.id === item.parentId))
    },
    computedHeader: {
      get() {
        return [...this.settingsTable.header].sort((a, b) => a.order - b.order)
      },
      set(value) {
        return value
      }
    },
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    isAllSelected() {
      const fullBody = []
      this.body.forEach(item => {
        fullBody.push(item)
        if (item.groups && item.groups.length) {
          fullBody.push(...item.groups)
        }
      })

      return this.selectedItems.length && this.selectedItems.length === fullBody.length
    },
    formatHeader() {
      if (this.header) {
        return [...this.header].filter(item => item.is_visible).sort((a, b) => a.order - b.order)
      }

      return []
    },
  },
  methods: {
    ...mapActions({
      changeHeaders: 'changeNomenclatureHeaders'
    }),
    onChecked(id) {
      return this.selectedItems.some(item => item.id === id)
    },
    onEmitRowAction({action, argumentsItem}) {
      this.$emit(`${action}-item`, argumentsItem)
    },
    onToTop() {
      const ref = this.$refs.table.$el;
      const scrollBlock = ref.querySelector('.v-data-table__wrapper');
      scrollBlock.scrollTo({
        top: 0, left: 0, behavior: "smooth"
      })
    },
    toggleAll() {
      this.$emit('toggle-all', this.isAllSelected)
    },
    onViewItem(params) {
      this.$emit('view-item', params)
    },
    toggleVisible(params) {
      this.$emit('toggle-visible', {
        params
      })
    },
    selectItem(newItem) {
      this.$emit('select-item', newItem)
    },
    toggleOrder(e, column) {
      this.sortDesc = !this.sortDesc
      this.sortBy = column
    },
    openChild(values) {
      const isExist = this.isOpenGroup.some(item => item.id === values.id);
      if (isExist) {
        this.isOpenGroup = this.isOpenGroup.map(item => {
          if (item.id === values.id) {
            return values;
          }

          return item;
        })
      } else {
        this.isOpenGroup.push(values);
      }
    },
    onClickDel() {
      // this.$emit('delete-item', [...this.selectedItems]);
      const val = [...this.selectedItems];
      const values = {
        val,
        name: '',
        title: MODE_TYPES.REMOVE
      }

      this.$emit('delete-item', values)

      this.$emit('clear-selected-items')
    },
    onClickCopy() {
      this.$emit('copy-item', ...this.selectedItems)
    },
    onClickAutActual() {
      if (this.selectedItems.length > 1) {
        this.$emit('aut-actual-selected-items')
      } else {
        this.$emit('aut-actual-item', ...this.selectedItems)
      }
      this.$emit('clear-selected-items')
    },
    onClickToActual() {
      if (this.selectedItems.length > 1) {
        this.$emit('to-actual-selected-items')
      } else {
        this.$emit('to-actual-item', ...this.selectedItems)
      }
      this.$emit('clear-selected-items')
    },
    onClickChange() {
      this.$emit('update-item', ...this.selectedItems)
    },
    onClickMoveItems() {
      if (this.selectedItems.length > 1) {
        this.$emit('move-selected-items')
      } else {
        this.$emit('move-item', ...this.selectedItems)
      }
    },
    onChangePrice(params) {
      this.$emit('changePrice', params)
    },
    showSettings() {
      this.settingsTable.header = JSON.parse(JSON.stringify(this.header))
      this.settingsFlag = !this.settingsFlag
    },
    hideOutsideSettings() {
      this.settingsFlag = false
    },
    move(to, from) {
      this.settingsTable.header.move(from, to)
      this.settingsTable.header.forEach((item, index) => {
        item.order = index
      })
    },
    async saveTableSettings() {
      const data = [...this.settingsTable.header].map(item => {
            return {
              id: item.id,
              is_sortable: item.is_sortable,
              is_visible: item.is_visible,
              order: item.order,
            }
          });
      await this.hideOutsideSettings()
      try {
        const hewHeaders = await httpClient.put('products/nomenclatures/product/headers',{data})
        this.changeHeaders(hewHeaders.data.data)
      } catch (e) {
        console.log(e)
      }
    },
    checkMove(e) {
      this.move(e.newIndex, e.oldIndex)
    }
  },
}
</script>
<style lang="sass" scoped>
.base-btn.darken-btn:hover
  background-color: #FF9F2F !important
.thTable-settings
  position: absolute
  right: 0
  top: 2px

tbody
  tr
    height: 60px
.btn-top
  svg
    color: #9DCCFF
    transition: color .3s ease
  &:hover svg
    color: #fff
.empty-table-text
  font-weight: 550
  font-size: 24px
  line-height: 24px
  color: #BBDBFE
  position: absolute
  left: 50%
  top: 50%
  transform: translate(-50%, -50%)

  .settings-popup
    max-height: 450px

    .fade-enter-active,
    .fade-leave-active
      transition: .25s ease-in-out

    .fade-enter,
    .fade-leave-to
      opacity: 0

    .flip-list-move
      transition: transform .5s

    .table-switcher
      display: flex
      align-items: center
      justify-content: center

      .switcher
        width: 33px
        height: 18px
        border: 1px solid #9DCCFF

        box-sizing: border-box
        border-radius: 50px
        position: relative

        &-caret
          width: 12px
          height: 12px
          background: #9DCCFF
          //background: #4ECA80
          border-radius: 50%
          position: absolute
          left: 5%
          top: 50%
          transform: translateY(-50%)
          transition: all .5s


        &.active
          border: 1px solid #7CE1A4

          .switcher-caret
            transform: translateY(-50%) translateX(16px)
            background: #4ECA80
            border: 1px solid #7CE1A4


</style>
