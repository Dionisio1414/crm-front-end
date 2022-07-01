<template>
  <div class="table-wrapper" :class="{'loading': loading}">
    <div class="table-responsive">
      <v-data-table
          :headers="formatHeader"
          :items="[body, formatHeader]"
          :sortable="true"
          :sort-desc.sync="sortDesc"
          :sort-by.sync="sortBy"
          :items-per-page="10"
          hide-default-header
          hide-default-footer
          class="table"
      >
        <template v-slot:header="{props}">
          <thead>
          <tr>
            <th>
              <div class="checkbox">
                <label class="checkbox-label">
                  <input type="checkbox" @click="toggleAll" v-model="selected">
                  <div class="checkbox-text"/>
                </label>
              </div>
            </th>
            <template
                v-for="header in props.headers"
            >
              <th
                  :key="header.title"
                  :ref="header.value"
                  v-show="header.is_visible"
                  @click.prevent="toggleOrder($event, header.value)"
              >
                <div class="th-block">
                  <span class="header-title">{{ header.title }}</span>
                  <span class="sortable-icon" v-if="header.is_sortable">
                    <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
                  </span>
                </div>
              </th>
            </template>

            <th class="thTable-settings">
              <div class="table-settings" v-click-outside="hideOutsideSettings">
                <button type="button" class="btn-settings" @click="showSettings">
                  <span class="icon">
                    <svg-sprite width="18" height="18" icon-id="settings"></svg-sprite>
                  </span>
                </button>
                <div class="settings-popup" v-if="settingsFlag">
                  <div class="settings-title">{{ $t('page.wareHouses.setTable') }}</div>
                  <div class="settings-default">
                    <button type="button" class="btn btn-default">
                      <span class="btn-default-text">{{ $t('page.wareHouses.setDefault') }}</span>
                      <svg-sprite width="15" height="12" icon-id="defaultIcon"></svg-sprite>
                    </button>
                  </div>
                  <draggable
                      class="settings-list"
                      tag="div"
                      v-bind="dragOptions"
                      @update="checkMove"
                      @start="drag = true"
                      @end="drag = false"
                  >
                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                      <div v-for="(val, index) in settingsTable.header" :key="val.id" class="settings-list-item">
                        <div class="checkbox">
                          <label class="checkbox-label">
                            <input type="checkbox" v-model="val.is_visible">
                            <div class="checkbox-text">
                              <div class="text">{{ val.title }}</div>
                            </div>
                          </label>
                        </div>
                        <div class="sorting">
                          <v-btn color="#9DCCFF" fab small
                                 class="action-btn circle-btn swap-btn"
                                 @click.stop="move(index, index - 1)"
                                 :disabled="!index"
                          >
                            <svg-sprite width="6" height="12" icon-id="sortArrowUp"></svg-sprite>
                          </v-btn>
                          <v-btn color="#9DCCFF" fab small
                                 class="action-btn circle-btn swap-btn"
                                 @click.stop="move(index, index + 1)"
                                 :disabled="index === (settingsTable.header.length-1)"
                          >
                            <svg-sprite width="6" height="12" icon-id="sortArrowDown"></svg-sprite>
                          </v-btn>
                        </div>
                      </div>
                    </transition-group>
                  </draggable>
                  <button type="button" class="base-btn darken-btn" @click="saveTableSettings">
                    {{ $t('page.wareHouses.saveTable') }}
                  </button>
                </div>
              </div>
            </th>
          </tr>
          </thead>
        </template>
        <template v-slot:body="{items}">
          <tbody>
          <tr v-for="(val, index) in items[0]"
              :key="val.id"
              :class="{'is-group': val['is_groups'], 'active': val.id === rowId}"
              @contextmenu.prevent="onContextMenuOpen($event, {val, index})"
              @mouseenter="onContactInfo($event, val)"
          >
            <td class="checkboxTd" :class="{ 'border': val.status === 1 }">
              <div class="checkbox">
                <label class="checkbox-label">
                  <input
                      type="checkbox"
                      @change.stop.prevent="selectItem(val)"
                      :checked="selected"
                      ref="selected"
                  >
                  <span class="checkbox-text" :class="{'disabled': !isCheckItem && !val.is_editable}"></span>
                </label>
              </div>
              <div v-if="!isCheckItem" :class="{'favorite': true, 'favorite-active': val.is_default}"
                   @click.stop="setFavorite(val.id)">
                <svg-sprite width="16" height="16" icon-id="favorite"></svg-sprite>
              </div>
            </td>
            <template v-for="(value, key) in items[1]">
              <td :class="{'link': getLink(value.value)}" @click="onPreView(val, value)"
                  :key="key"
                  :ref="key"
              >
                <div :class="{'scheduled': value.value === 'payment_date_scheduled'}"
                     v-html="getFormattedValue(value.value, val.cells[value.value])"></div>
                <svg-sprite v-if="value.value === 'payment_date_scheduled'" height="20" width="20"
                            :icon-id="'status' + getStatusId(val.cells['status'])"></svg-sprite>
              </td>
            </template>

            <td></td>
          </tr>
          </tbody>
        </template>
      </v-data-table>
    </div>

    <div class="table-footer">
      <div class="total-quantity">
        <span>Количество элементов: {{ total }}</span>
        <span>Выбрано элементов: {{ selectedItems.length }}</span>
      </div>

      <div class="table-controls" v-if="body.length">
        <div class="controls-list" v-if="actions.length">
          <div class="controls-list-item" v-for="(val, index) in actions" :key="index">

            <button class="control-btn" :disabled="selectedItems.length !== 1" type="button"
                    @click.prevent="onClickCopy" v-if="val.type === 'copy'">
              <span class="icon">
                <svg-sprite width="21" height="21" icon-id="contextMenuLargeCopy"></svg-sprite>
              </span>
            </button>

            <button :disabled="!selectedItems.length" class="control-btn" type="button" @click="onClickMove"
                    v-if="val.type === 'move'">
              <span class="icon">
                <svg-sprite width="46" height="46" icon-id="contextMenuLargeMove"></svg-sprite>
              </span>
            </button>

            <button :disabled="selectedItems.length !== 1" @click="onClickChange" class="control-btn" type="button"
                    v-if="val.type === 'edit'">
              <span class="icon">
                <svg-sprite width="23" height="23" icon-id="contextMenuLargeEdit"></svg-sprite>
              </span>
            </button>

            <button :disabled="selectedItems.length < 1" @click="onClickDel" class="control-btn" type="button"
                    v-if="val.type === 'delete'">
              <span class="icon">
                <svg-sprite width="19" height="21" icon-id="contextMenuLargeRemove"></svg-sprite>
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
    </div>

    <vue-context class="context-menu" ref="menu" :close-on-click="closeOnClick" v-slot="{ data }">
      <li v-if="!isHideCopy" class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="13" height="13" icon-id="contextMenuCopy"></svg-sprite>
        </div>
        <a @click.prevent="onClickContextCopy($event, data)">{{ $t('page.contextMenu.copy') }}</a>
      </li>
      <li class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="13" height="13" icon-id="contextMenuEdit"></svg-sprite>
        </div>
        <a @click.prevent="onClickContextChange($event, data)">{{ $t('page.contextMenu.edit') }}</a>
      </li>
      <li v-if="!isHideMove" class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="25" height="25" icon-id="contextMenuMove"></svg-sprite>
        </div>
        <a @click.prevent="onClickContextMove($event, data)">{{ $t('page.contextMenu.move') }}</a>
      </li>
      <li class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="11" height="11" icon-id="contextMenuRemove"></svg-sprite>
        </div>
        <a @click.prevent="onClickContextDelete($event, data)">{{ $t('page.contextMenu.remove') }}</a>
      </li>
    </vue-context>
  </div>
</template>
<script>
// vuex
import {mapActions, mapGetters} from "vuex";
import { eventBus } from "@/main";
// components
import ClickOutside from 'vue-click-outside';
// constants
import {MODE_TYPES} from "@/constants/constants";
// services
import httpClient from "@/services/http-client/http-client";

Array.prototype.move = function (from, to) {
  this.splice(to, 0, this.splice(from, 1)[0]);
  return this;
};

export default {
  name: "DTable",
  props: {
    paginationCount: Number,
    total: Number,
    header: Array,
    body: Array,
    resource: String,
    actions: Array,
    isCheckItem: Boolean,
    isHideMove: Boolean,
    isHideCopy: Boolean,
    isPurchases: Boolean,
    rowId: Number,
    isContact: Boolean
  },
  computed: {
    ...mapGetters(['loading']),
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    computedHeader() {
      return this.header.slice()
    },
    formatHeader() {
      if (this.header) {
        return [...this.header].filter(item => item.is_visible).sort((a, b) => a.order - b.order)
      }
      return []
    },
  },
  watch: {
    body(val, oldVal) {
      if (val.length !== oldVal.length) {
        this.selectedItems = [];
        this.$refs.selected || [].forEach(input => input.checked = false);
      }
    }
  },
  data: () => ({
    selectedItems: [],
    selectionProduct: '',
    selectionProductItems: [],
    selected: false,
    sortDesc: false,
    sortBy: null,
    favorite: null,
    settingsFlag: false,
    drag: false,
    closeOnClick: false,
    isRecord: false,
    settingsTable: {
      copyHeader: null,
      header: null,
      body: null,
      transformHeaders: null,
    }
  }),
  methods: {
    ...mapActions(['getSelectedItems']),
    onContactInfo(event, data) {
      if (!this.isContact) return false;
      eventBus.$on('updateContact', value => this.isRecord = value)

      if (this.isRecord) return false;

      console.log(data, 'data')

      const { contacts, legal_city_title, legal_address_street, legal_address_region_title, legal_address_number_housing, legal_address_country_title } = data;
      const top = event.target.offsetTop;
      const firstElem = contacts[0] ?? {};
      const values = {
        top,
        contacts: { ...firstElem, legal_city_title, legal_address_street, legal_address_region_title, legal_address_number_housing, legal_address_country_title }
      }

      this.$emit('updateContactInfo', values);
    },
    clickRow(id) {
      this.$emit('updateId', id)
    },
    toggleAll() {
      this.selectedItems = [];
      if (!this.selected) {
        const currentId = this.isPurchases ? 'document_id' : 'id';
        this.body.forEach(item => this.selectedItems.push(item[currentId]))
      }
    },
    toggleVisible(item) {
      item.cells.is_visible = !item.cells.is_visible
      this.$emit('toggleVisible', {id: item.id, is_visible: item.cells.is_visible})
    },
    selectItem({id, document_id}) {
      const currentId = this.isPurchases ? document_id : id;
      const index = this.selectedItems.findIndex((item) => item === currentId)
      if (index === -1) {
        this.selectedItems.push(currentId)
      } else {
        this.selectedItems.splice(index, 1)
      }

      this.getSelectedItems(this.selectedItems)
    },
    toggleOrder(e, column) {
      this.sortDesc = !this.sortDesc
      this.sortBy = column;
    },
    setFavorite(id) {
      const defaultItem = this.body.find((item) => item.id === id)
      if (!defaultItem.is_default) {
        this.$emit('changeDefaultItem', id)
      }
    },
    onClickDel() {
      this.$emit('deleteSelected', [...this.selectedItems]);
      const val = [...this.selectedItems];
      const values = {
        val,
        name: '',
        title: MODE_TYPES.REMOVE
      }

      this.$emit('deleteList', values)
      this.selected = false
    },
    onClickChange() {
      const item = this.body.find(item => item[this.isPurchases ? 'document_id' : 'id'] === this.selectedItems[0]);
      const {type} = item;
      const values = {
        val: {...item}, type, title: MODE_TYPES.EDIT
      }

      this.$emit('editList', values)
    },
    onClickMove() {
      const val = [...this.selectedItems];
      const values = {
        val, title: MODE_TYPES.MOVE
      }

      this.$emit('moveList', values)
    },
    onClickCopy() {
      const item = this.body.find(item => item[this.isPurchases ? 'document_id' : 'id'] === this.selectedItems[0]);
      const {type} = item;
      const values = {
        val: {...item}, type, title: MODE_TYPES.COPY
      }

      this.$emit('copyList', values)
    },
    onClickTransfer() {
      let title = null
      const firstItemTitle = this.body.find(item => item.id === this.selectedItems[0]).cells.short_title
      title = this.selectedItems.length > 1 ? this.selectedItems.length : firstItemTitle
      this.$emit('click-transfer', {
        items: [...this.selectedItems],
        title
      })
      this.selectedItems = []
    },
    onClickCopyProduct() {
      this.$emit('copy-product', this.selectedItems[0])
    },
    onClickCopyIn() {
      console.log(this.selectedItems);
    },
    onClickContextChange(e, {val}) {
      const {type} = val;
      const values = {
        val: {...val},
        type,
        title: MODE_TYPES.EDIT
      }

      this.$emit('changeItem', val)
      this.$emit('editList', values)
    },
    onClickContextMove(e, {val}) {
      const values = {
        val: [val.id], title: MODE_TYPES.MOVE
      }

      this.$emit('moveList', values)
    },
    onClickContextDelete(e, {val}) {
      const {id: itemId, document_id, cells: {document_type = '', title_supplier = ''}} = val;
      const id = [this.isPurchases ? document_id : itemId];
      const values = {
        val: id,
        name: document_type || title_supplier || 'Перемещение',
        title: MODE_TYPES.REMOVE
      }

      this.$emit('deleteList', values)
    },
    onClickContextCopy(e, {val}) {
      const {type} = val;
      const values = {
        val: {...val},
        type,
        title: MODE_TYPES.COPY
      }

      this.$emit('copyList', values)
    },
    clearChecked() {
      this.selectedItems = []
      this.selected = false
    },
    showSettings() {
      this.settingsTable.header = this.header;
      this.settingsFlag = !this.settingsFlag;
    },
    hideOutsideSettings() {
      this.settingsFlag = false
    },
    transformHeadersHandler(headers) {
      let queryKeys = ['id', 'is_visible', 'order']
      let headersQuery = {data: []}
      headers.forEach(item => {
        let obj = Object.keys(item)
            .filter(key => queryKeys.includes(key))
            .reduce((obj, key) => {
              return {
                ...obj,
                [key]: item[key]
              }
            }, {})
        headersQuery.data.push(obj)
      })
      this.settingsTable.transformHeaders = headersQuery
    },
    move(from, to) {
      this.settingsTable.header.move(from, to)
      this.settingsTable.header.forEach((item, index) => {
        item.order = index
      })
    },
    async saveTableSettings() {
      this.transformHeadersHandler(this.settingsTable.header);
      const data = this.settingsTable.transformHeaders;
      this.header = this.settingsTable.header;
      this.hideOutsideSettings();

      try {
        await httpClient.put(this.resource, {...data})
      } catch (e) {
        console.log(e)
      }
    },
    checkMove(e) {
      this.move(e.oldIndex, e.newIndex)
    },
    onToTop() {
      const scrollBlock = this.$el.querySelector('.v-data-table__wrapper');
      scrollBlock.scrollTo({
        top: 0, behavior: "smooth"
      })
    },
    scrollPagination() {
      const listElm = this.$el.querySelector('.v-data-table__wrapper');

      listElm.addEventListener('scroll', () => {
        if (listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) {
          this.pagination();
          console.log('scrolledToEnd');
        }
      })
    },
    async pagination() {
      const isAmountPage = +this.total > (this.paginationCount * 10);
      if (isAmountPage) {
        await this.$emit('updatePagination');
        this.$emit('scrolledToEnd', this.paginationCount);
      }
    },
    getLink(key) {
      return ['convert_id', 'document_type', 'title_supplier'].includes(key);
    },
    onPreView(val, {value}) {
      const isLink = this.getLink(value);
      const {id, type} = val;
      if (isLink) {
        const values = {
          val,
          value,
          type,
          title: MODE_TYPES.EDIT
        }

        this.$emit('changeItem', val)
        this.$emit('editList', values)
      }

      this.$emit('updateIdRow', id)
    },
    onContextMenuOpen($event, obj) {
      this.$refs.menu.open($event, obj);
      const {val: {id}} = obj;
      this.$emit('updateIdRow', id)
    },
    getFormattedValue(key, value) {
      const formattedValue = value === 0 ? '' : value;
      switch (key) {
        case 'amount':
          return this.getFormatAmount(formattedValue);
        case 'status':
          return this.getFormatStatus(formattedValue)
        case 'date':
          return this.getFormatDate(formattedValue)
        case 'receipt_date_actual':
          return this.getFormatDate(formattedValue)
        case 'receipt_date_scheduled':
          return this.getFormatDate(formattedValue)
        case 'payment_date_scheduled':
          return this.getFormatDate(formattedValue)
        case 'delivery':
          return this.getDeliveryCell(formattedValue)
        case 'description':
          return this.getLimit(formattedValue)
        default:
          return formattedValue;
      }
    },
    getLimit(text) {
      if (!text) return '';
      return `<span class="limitText">${text}</span>`;
    },
    getDeliveryCell(url) {
      if (!url) return '';
      const isUrl = url.includes(':');
      if (!isUrl) return url;

      return `<img class="deliveryIcon" src="${url}" alt="delivery_icon" />`
    },
    getFormatDateActual(value) {
      if (!value) return '';
      const date = value.slice(0, -9);
      return `<span class="greenLabel">${date}</span>`
    },
    getFormatDate(value) {
      if (!value) return '';
      const [date, time] = value.slice(0, -3).split(' ');
      return `<div class="dateDoc"><span class="dateDocText">${date}</span><span class="dateDocSubText">${time}</span><div/>`
    },
    getFormatAmount(value) {
      if (value) {
        const [cur, sign] = value.split(' ');
        return this.$options.filters.formatPrice(cur) + ' ' + sign.toLowerCase()
      }
      return ''
    },
    getStatusId(value) {
      switch (value) {
        case 1:
          return 'New'
        case 2:
          return 'Wait'
        case 3:
          return 'Cancel'
        case 4:
          return 'Accept'
        default:
          return 'Return'
      }
    },
    getFormatStatus(value) {
      switch (value) {
        case 1:
          return `<span class="statusLabel new">Новый</span>`
        case 2:
          return `<span class="statusLabel inWork">В работе</span>`
        case 3:
          return `<span class="statusLabel cancel">Отменен</span>`
        case 4:
          return `<span class="statusLabel received">Получен</span>`
        default:
          return `<span class="statusLabel return">Возврат</span>`
      }
    }
  },
  mounted() {
    this.scrollPagination();
  },
  directives: {
    ClickOutside
  }
}
</script>
<style lang="sass">
.limitText
  display: block
  overflow: hidden
  text-overflow: ellipsis
  max-width: 170px
</style>
<style lang="sass" scoped>
.scheduled
  display: inline-block
  margin-right: 5px

  & + svg
    vertical-align: sub

.link
  cursor: pointer

  &:hover
    text-decoration: underline
tr.active
  background: #F4F9FF !important

.checkboxTd
  pointer-events: none

  .checkbox
    pointer-events: auto

.table-wrapper.loading
  opacity: .4

.controls-list-item
  font-size: 0

.border
  position: relative

  &:before
    content: ''
    position: absolute
    top: 0
    bottom: 0
    left: 0
    width: 4px
    background: #4ECA80


.fade-enter-active,
.fade-leave-active
  transition: .25s ease-in-out

.fade-enter,
.fade-leave-to
  opacity: 0

.flip-list-move
  transition: transform .5s

.thTable-settings
  position: absolute
  right: 0
  top: 2px
  background: none !important

  &:after
    content: ''
    position: absolute
    top: 0
    right: 0
    left: 0
    bottom: 0
    background: rgba(255, 255, 255, .5) !important

.table-settings
  z-index: 1

td
  white-space: nowrap

.btn-top
  svg
    color: #9DCCFF
    transition: color .3s ease

  &:hover svg
    color: #fff

</style>
<style lang="scss">
.page .base-btn.darken-btn:hover {
  background-color: #ff9f2f !important;
}

.deliveryIcon {
  max-width: 40px;
  height: auto;
}

.dateDoc {
  display: flex;
  flex-direction: column;
  font-size: 15px;
  line-height: 15px;

  &Text {
    color: #7E7E7E;
  }

  &SubText {
    color: #9DCCFF;
    line-height: 1.5;
  }
}

.greenLabel {
  color: #4ECA80;
}

.statusLabel {
  border-radius: 5px;
  font-weight: 600;
  font-size: 11px;
  line-height: 15px;
  padding: 6px 10px;
  min-width: 85px;
  display: inline-block;
  text-align: center;
  text-transform: uppercase;

  &.new {
    color: #07A2D3;
    background: #B3EDFF;
  }

  &.received {
    color: #1E8216;
    background: #A7FD9F;
  }

  &.inWork {
    color: #0964D0;
    background: #C0DDFF;
  }

  &.cancel {
    color: #DE1313;
    background: #FFCBCB;
  }

  &.return {
    color: #FF35A3;
    background: #FFD6EA;
  }
}

.suppliers .v-data-table.table > .v-data-table__wrapper > table > tbody > tr > td {
  padding: 20px 30px;
}
</style>
