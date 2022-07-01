<template>
  <v-treeview
      :hoverable="isHoverable"
      :items="items"
      item-children="children"
      item-disabled="disabled"
      item-text="title"
      expand-icon="$expand"
      activatable
      selection-type="independent"
  >
    <template slot="label" slot-scope="{ item }">
      <span
          class="wareHouseItem"
          :class="{'groupChild': !isExistGroup(item), 'active': (currentItem && (item.id === currentItem.id && item.title === currentItem.title)), 'move': isMove}"
          @click="onHandler(item)"
          @contextmenu.prevent="onContextHandler($event, item)"
      >
        <favorite-item :isFavorite="favorite && (favorite.id === item.id)" :item="item" v-if="!isExistGroup(item)" @updateFavorite="onFavorite"></favorite-item>
        <span class="text">{{ item.title }}</span>
      </span>
    </template>
  </v-treeview>
</template>

<script>
// vuex
import {mapActions} from "vuex";
// components
import FavoriteItem from "@/components/dashboard/products/wareHouses/FavoriteItem/FavoriteItem";
import {eventBus} from "@/main";

export default {
  name: "TreeView",
  components: {
    'favorite-item': FavoriteItem
  },
  props: {
    items: Array,
    isHoverable: Boolean,
    customClass: String,
    isMove: Boolean,
    favorite: Object,
    isSwitchTable: Boolean
  },
  data: () => ({
    currentItem: null
  }),
  methods: {
    ...mapActions(['getWarehouseDocuments', 'getDocPaginationCount', 'getWarehouseProducts']),
    async onHandler(item) {
      eventBus.$emit('updateGroupId');
      eventBus.$on('updateGroupId', () => {
        this.currentItem = null;
      })
      this.currentItem = item;
      const isExist = this.isExistGroup(item);

      if (isExist && this.isMove) this.$emit('updateBelongItem', item);
      if (!isExist) {
        const { id } = item;
        if (this.isSwitchTable) {
          await this.getWarehouseProducts(id);
        } else {
          await this.getWarehouseDocuments(id);
        }
        this.getDocPaginationCount('clear');
      }
    },
    onContextHandler(evt, item) {
      if (!this.isMove) this.$emit('updateContextMenu', evt, item);
    },
    isExistGroup(item) {
      return Object.keys(item).includes('children');
    },
    onFavorite(item) {
      this.$emit('updateFavorite', item);
    }
  }
}
</script>

<style scoped lang="scss">
  .wareHouseItem {
    width: calc(100% - 15px);
    cursor: pointer;
    transition: .3s ease-in;
    color: #7E7E7E;
    height: 45px;
    display: inline-flex;
    align-items: center;
    border-bottom: 1px solid #e3f0ff;
    margin-right: 15px;

    &.active {
      color: #FF9F2F;
    }
  }

  .move {
    &:not(.groupChild) {
      font-weight: 500;
    }

    &:not(.groupChild):hover {
      color: #FF9D2B;
    }

    &.groupChild {
      cursor: text;
      color: grey;
      pointer-events: none;
    }
  }

</style>
