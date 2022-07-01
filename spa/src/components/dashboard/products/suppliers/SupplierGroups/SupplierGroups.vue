<template>
  <div
      :style="indent"
      class="groups-menu"
      :class="[customClass, {'activeChildren': showChildren}]"
  >
    <div
        :class="{
          'active': activeCategory,
          'with-open-children': showChildren,
          'groups-menu-header': getGroupLength ,
          'groups-menu-link': !getGroupLength ,
          }"
        @click.stop="changeActiveCategory"
        @contextmenu.prevent="onContextMenu($event)"
        class="groups-menu-item">
      <button class="btn-groupOpen" @click.stop="toggleChildren" v-if="getGroupLength">
        <svg-sprite width="6" height="9" icon-id="arrowDownGr"></svg-sprite>
      </button>
      {{ label }}
    </div>
    <supplier-groups
        v-show="showChildren"
        v-for="group in getList"
        :key="group.id"
        :groups="group"
        :id="group.id"
        :label="group.title"
        :depth="depth + 10"
        @updateTableCurrentItem="onTableCurrentItem"
        @createChildrenCategory="onCreateChildrenCategory"
    >
    </supplier-groups>
    <add-group-button
        v-if="isLastLevel"
        :depth="depth"
        @updateAddGroup="addGroup"
    ></add-group-button>
  </div>
</template>
<script>
import { eventBus } from "@/main";
import {mapActions, mapGetters} from "vuex";
// components
import AddGroupButton from './components/AddGroupButton'

export default {
  name: 'SupplierGroups',
  components: {
    'add-group-button': AddGroupButton
  },
  props: {
    label: String,
    depth: Number,
    id: Number,
    customClass: String,
    groups: {
      type: Object,
      default() {
        return {};
      }
    }
  },
  data() {
    return {
      showChildren: false,
      activeCategory: false,
      isCloseClick: false,
      parentData: null
    }
  },
  computed: {
    ...mapGetters(['refContextMenu']),
    getParentData() {
      const { id, label } = this.$parent;
      return { id , label }
    },
    getList() {
      return this.groups.children
    },
    getGroupLength() {
      return this.groups.children && this.groups.children.length
    },
    indent() {
      return {
        marginLeft: `${this.depth}px`
      }
    },
    isLastLevel() {
      return !this.groups.parent_id;
    }
  },
  mounted() {
    eventBus.$on('minimizeCategories', () => {
      this.activeCategory = false;
      this.showChildren = false;
    });

    eventBus.$on('resetSwitchedCategory', data => {
      this.activeCategory = this.id === data.id;
    });

    this.parentData = this.getParentData;
  },
  methods: {
    ...mapActions(['onCurrentId', 'getEditData', 'getSuppliersTable']),
    onTableCurrentItem(id) {
      this.$emit('updateTableCurrentItem', id);
      this.onCurrentId(id);
    },
    toggleChildren() {
      this.onCurrentId(this.id);
      this.showChildren = !this.showChildren;
    },
    changeActiveCategory() {
      this.$emit('updateTableCurrentItem', this.id);
      this.onCurrentId(this.id);

      eventBus.$emit("switchTab");
      eventBus.$emit('resetSwitchedCategory', { id: this.id });
    },
    onCreateChildrenCategory(id) {
      this.$emit('createChildrenCategory', { id, isChild: true })
    },
    addGroup() {
      this.$emit('createChildrenCategory', {id: this.id, isChild: true})
    },
    onContextMenu(evt) {
      const editData = {
        currentId: this.id,
        currentLabel: this.label,
        parentData: this.parentData,
      }

      this.onCurrentId(this.id);
      this.getEditData(editData);

      this.refContextMenu.open(evt)
    }
  }
}
</script>
<style lang="scss">
.groups-menu-item {
  color: #7E7E7E;
}

.btn-groupOpen {
  padding: 2px;
  width: 20px;
}

.last-groups-level {
  transition: all .3s ease;
}

.groups-menu {
  border-bottom: 1px solid #E3F0FF;

  .groups-menu {
    border: none;
    padding-bottom: 0;

    .groups-menu-item {
      padding: 8px 0 8px 15px;
    }
  }

  &-item {
    padding-top: 15px;
    padding-bottom: 15px;
    font-size: 14px;
    cursor: pointer;
    line-height: 130%;
  }

  .last-groups-level {
    align-items: center;
    height: 0;
    overflow: hidden;
    opacity: 0;
    display: flex;

    svg {
      cursor: pointer;
      width: 17px;
      height: 17px;
      pointer-events: none;
      transition: .5s;

      &:hover {
        transform: scale(1.2);
      }
    }

    &:before, &:after {
      content: "";
      display: block;
      width: 50%;

      height: 0;
      border-top: 1px solid #E3F0FF;
    }

    &:before {
      margin-right: 3px;
    }

    &:after {
      margin-left: 3px;
    }
  }

  &.activeChildren {
    .with-open-children {
      color: #5893D4;
      font-weight: bold;

      svg {
        transform: rotate(90deg);
      }

      &.active {
        color: #FF9F2F;
      }
    }

    &:hover {
      .last-groups-level {
        opacity: 1;
        height: auto;
        padding-top: 10px;
      }

      svg {
        pointer-events: auto;
      }
    }

    padding-bottom: 30px;

    .groups-menu {
      border: none;
      padding-bottom: 0;
    }
  }
}

.groups-menu-link {
  &.active {
    font-weight: bold;
    font-size: 14px;
    color: #FF9F2F;
  }
}

.groups-menu-header {
  svg {
    width: 8px;
  }

  &.with-open-children {
    border-top: none;
    border-bottom: 1px solid #E3F0FF;
    padding-bottom: 15px;
    font-weight: bold;
    font-size: 15px;
    line-height: 15px;
    color: #5893D4;

    svg {
      transform: rotate(90deg);
    }
  }

  &.active {
    font-weight: bold;
    font-size: 14px;
    color: #FF9F2F;
  }
}
</style>
