<template>
  <v-col v-click-outside="onUnActive" :cols="cols" :class="[{'wareHouses': isChips, 'bind': getSelectValues, 'moveStock': isMoveStock}, customClass]">
    <div class="label-title">
      {{ title }}
      <template v-if="requestErrorWarehouse">
        <span class="error-block" :key="item.title" v-for="item in requestErrorWarehouse.title">{{ item }}</span>
      </template>
    </div>
    <v-select
        class="select-switcher"
        :no-data-text="isGroups ? $t('page.wareHouses.createGroupsWareHouses.needCreateGroup') : $t('page.wareHouses.createGroupsWareHouses.needCreateWarehouse')"
        :menu-props="{ contentClass: 'wareHouseDropDown', closeOnContentClick: true }"
        :placeholder="isChips ? $t('page.wareHouses.createGroupsWareHouses.chooseWarehouse') : $t('page.wareHouses.createGroupsWareHouses.labelGroup')"
        :hide-selected="true"
        item-text="title"
        item-value="id"
        v-model="selectValues"
        :items="getItems"
        :deletable-chips="isChips"
        :multiple="isChips"
        :chips="isChips"
        @click="onActive"
        @change="onChange"
        return-object
        dense
    >
      <template v-if="!isMoveStock" v-slot:prepend-item>
        <v-list-item class="addWareHouseItem" @click.stop="addQuickWareHouse">
          <button class="addWareHouse" :disabled="loading">
            <span v-if="!isWareHousePopup" class="plusText">+</span>
            {{ isWareHousePopup
              ? $t('page.wareHouses.createGroupsWareHouses.hide')
              : $t('page.wareHouses.createGroupsWareHouses.add') }}
            <v-progress-circular
                v-if="loading"
                indeterminate
                color="#5893D4"
                size="20"
                width="2"
            ></v-progress-circular>
          </button>
        </v-list-item>
      </template>
      <template v-if="!isMoveStock" v-slot:append-item>
        <transition name="fade">
          <div v-show="isWareHousePopup" @click.stop="()=> false" class="addWareHousesPopup"
               :class="{'reducePopup': isReducePopup}">
            <input
                class="field"
                type="text"
                v-model="value"
                :placeholder="$t('page.wareHouses.createGroupsWareHouses.fillTheName')"
            >
            <button @click="omHandler" :disabled="!value || loading" class="addBtn">{{$t('page.wareHouses.createGroupsWareHouses.add')}}</button>
          </div>
        </transition>
      </template>
    </v-select>
    <span v-if="isWarehouseBind && getSelectValues && !isMoveStock" class="btnEit" @click="onEdit">
      <svg-sprite width="30" height="30" icon-id="edit"></svg-sprite>
    </span>
  </v-col>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// helper
import getFlatMap from "@/helper/getFlatMap";
// components
import getAddressItem from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/helper/getAddressItem";

export default {
  name: "SelectGroups",
  props: {
    isDefaultValue: Boolean,
    wareHouses: Array,
    isGroups: Boolean,
    isMoveStock: Boolean,
    isChips: Boolean,
    favorite: Object,
    title: String,
    customClass: String,
    getEditData: Object,
    parentGroup: Object,
    editGroupData: Object,
    isEdit: Boolean,
    isWarehouseBind: Boolean,
    requestErrorWarehouse: Object,
    isClear: String,
    isReducePopup: Boolean,
    cols: {
      type: Number,
      default: 6
    }
  },
  watch: {
    isClear(val, oldVal) {
      if (val !== oldVal) {
        this.selectValues = [];
      }
    }
  },
  computed: {
    ...mapGetters(['loading', 'requestError']),
    getSelectValues() {
      return Object.keys(this.selectValues).length;
    },
    getItems() {
      if (this.editGroupData) {
        const transformGroupData = [this.editGroupData];
        return [...this.wareHouses, ...transformGroupData];
      }

      if (this.parentGroup) {
        const parentItem = this.getParentItem(this.parentGroup);

        return [...this.wareHouses, parentItem];
      }

      if (this.getEditData) {
        const {children} = this.getEditData;
        const formatChildren = children.filter(item => !Object.prototype.hasOwnProperty.call(item, "children"));

        return [...this.wareHouses, ...formatChildren];
      }

      return this.wareHouses;
    }
  },
  data: () => ({
    value: '',
    isWareHousePopup: false,
    selectValues: []
  }),
  methods: {
    ...mapActions(['addWareHouse', 'addWareHouseGroup']),
    onActive() {
      this.$emit('updateActive');
    },
    onUnActive() {
      this.$emit('updateUnActive');
    },
    omHandler() {
      if (!this.isGroups && !this.isWarehouseBind || this.isGroups && this.isWarehouseBind) {
        this.addWareHouse({ title: this.value, isSingle: true });
      }

      if (this.isGroups && !this.isWarehouseBind) {
        this.addWareHouseGroup({ title: this.value, isSingle: true })
      }

      this.reset();
    },
    onEdit() {
      this.$emit('updateWarehouse', this.selectValues)
    },
    getWHIds() {
      let iDs;
      if (Array.isArray(this.selectValues)) {
        iDs = this.selectValues.map(item => ({ id: item.id }));
      } else {
        iDs = { warehouse_group_id: this.selectValues['id'] };
      }

      this.$emit('updateSelectIds', iDs)
    },
    reset() {
      this.value = '';
      this.isWareHousePopup = false;
    },
    addQuickWareHouse() {
      this.isWareHousePopup = !this.isWareHousePopup;
    },
    onChange(value) {
      const { id } = value;
      this.$emit('updateWareHouseId', id);
    },
    getSelectElement(id) {
      return this.getItems.find(item => item.id === id);
    },
    getParentItem(parent) {
      const { parent_id } = parent;
      if (!parent_id) return [];

      const formatWareHouses = getFlatMap(this.wareHouses);
      return getAddressItem(formatWareHouses, parent_id);
    }
  },
  mounted() {
    if (this.isDefaultValue) {
      this.selectValues = this.getItems[0] || {};
    }

    if (this.isWarehouseBind && this.favorite) {
      this.selectValues = this.favorite;
      this.selectValues = this.getSelectElement(this.favorite.id);
      this.$emit('updateWareHouseId', this.favorite.id);
    }

    if (this.getEditData) {
      const {children} = this.getEditData;
      this.selectValues = Array.isArray(children) ? children : [this.getEditData];
    }

    if (this.editGroupData) {
      this.selectValues = this.editGroupData;
    }

    if (this.parentGroup) {
      this.selectValues = this.getParentItem(this.parentGroup);
    }
  },
  beforeUpdate() {
    this.getWHIds();
  }
}
</script>

<style lang="scss">
.error-block {
  font-size: 14px;
  margin-top: 10px;
  color: #FF9F2F;
  text-transform: lowercase;
  margin-left: 10px;
}

.addWareHousesPopup {
  position: absolute;
  padding: 20px;
  width: 300px;
  height: 125px;
  background: #FFFFFF;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  border-radius: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1;
  box-sizing: border-box;
  top: 30px;
  left: 10px;

  &.reducePopup {
    width: 250px;
    left: 5px;
  }

  .field {
    border-bottom: 1px solid #9DCCFF;
    width: 100%;
    font-size: 14px;
    line-height: 1.5;
    color: #7E7E7E;
  }

  .addBtn {
    border: 1px solid #5893D4;
    box-sizing: border-box;
    border-radius: 32.5652px;
    width: 100%;
    max-width: 160px;
    height: 36px;
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #fff;
    margin-top: 20px;
    background: #5893D4;

    &[disabled] {
      border: 1px solid #9DCCFF;
      background: #fff;
      color: #9DCCFF;
    }
  }
}

.addWareHousesPopup .field:focus {
  outline: none;
}

.addWareHouse {
  font-size: 15px;
  line-height: 15px;
  color: #5893D4;
}

.addWareHouseItem {
  margin: 0 !important;
  background: #F4F9FF;
  position: relative;

  & ~ .v-list-item {
    font-weight: 550;
    font-size: 15px;
    line-height: 15px;
    color: #7E7E7E;
  }

  &:before, .v-ripple__container {
    display: none !important;
  }
}

.theme--light.v-text-field > .v-input__control > .v-input__slot {
  margin-bottom: 0;

  &:before {
    border-color: #9DCCFF !important;
    bottom: -2px;
  }
}

.wareHouses {
  .v-input--is-label-active.v-input--is-dirty.v-input--dense {
    .v-select__selections {
      width: inherit;
      top: 40px;
      pointer-events: none;
    }
  }

  .v-select__selections {
    top: -10px;
    position: absolute;
    width: 100%;
  }



  .label-title {
    margin-bottom: 6px;
  }

  .theme--light.v-chip {
    background: #E3F0FF;
    border-radius: 10px;
    font-size: 13px;
    line-height: 13px;
    color: #317CCE;
    pointer-events: auto;
    padding: 0 15px;
  }

  .v-icon.v-icon:after {
    display: none;
  }

  .mdi-close-circle {
    font-size: 0 !important;
    margin-left: 15px !important;
    &::before {
      content: "⨉";
      margin-bottom: 6px;
      color: #BBDBFE;
      font-weight: 600;
      font-size: 20px;
    }
  }
}

.wareHouseDropDown {
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  border-radius: 0 0 10px 10px;
  box-shadow: none;

  .plusText {
    font-size: 20px;
    display: inline-block;
    margin-right: 5px;
  }

  .v-list {
    padding-top: 0;
    min-height: 180px;
  }

  .v-list-item__action {
    display: none;
  }

  .v-list-item {
    border-bottom: 1px solid #E3F0FF;
    min-height: 37px !important;
    .v-list-item__content {
      margin: 0 15px;
    }

    .v-list-item__title {
      font-weight: 550;
      font-size: 15px !important;
      line-height: 15px;
    }

    &:not(.addWareHouseItem) {
      padding: 0;
      &:hover {
        background: #F4F9FF;

        &:before {
          display: none;
        }
      }
    }
  }

  .v-list-item:last-child {
    border-bottom: none;
  }
}

.warehouseBinding {
  max-width: 310px;
  padding: 20px;

  .label-title {
    letter-spacing: 0.02em;
    margin-bottom: 0;
    line-height: 1.1;

    &:after {
      content: '*';
      font-size: 16px;
      color: #5893D4;
      vertical-align: top;
    }
  }

  .v-input .v-select__selection--comma {
    font-size: 14px;
    color: #7E7E7E;
  }

  .v-input__slot:before {
    border-color: #5893d4 !important;
  }

  &.moveStock {
    &.bind .v-select {
      width: 100% !important;
      padding-right: 10px !important;
    }
  }

  .v-select__selection {
    margin: 0 !important;
  }

  .v-select {
    width: 100%;
    display: inline-block;
  }

  .btnEit {
    width: 30px;
    height: 30px;
    vertical-align: middle;
    cursor: pointer;
    display: none;
    transition: all .3s ease-in;
  }

  &:hover {
    &.bind .v-select {
      width: calc(100% - 30px);
      padding-right: 10px;
    }
  }

  &:hover .btnEit {
    display: inline-block;
  }
}

.theme--light.v-text-field > .v-input__control > .v-input__slot:before {
  display: none;
}
</style>
