<template>
  <div
      :style="indent"
      @mouseover.stop="onMouseover"
      @mouseout.stop="onMouseout"
      class="category-menu"
      :class="[customClass, {'active': showChildren, 'transfer' : mode === 'transfer'}]"
  >
    <div
        :class="{
          'disabled': isDisabled,
          'active': activeCategory,
          'with-open-children': showChildren,
          'category-menu-header': children.length,
          'category-menu-link': !children.length,
          'with-children' : children.length
          }"
        @click.stop="changeActiveCategory"
        class="category-menu-item"
    >
      <div class="label">
        <button
            class="btn-groupOpen"
            @click.stop="toggleChildren"
            v-if="children.length"
        >
          <svg-sprite width="6" height="9" icon-id="arrowDownGr"></svg-sprite>
        </button>
        {{ label }}
      </div>
      <category-menu
          v-show="showChildren"
          custom-class="nomenclature-menu"
          v-for="child in children" :key="child.id"
          :disabled-categories="disabledCategories"
          :nodes="child.children"
          :id="child.id"
          :label="child.title"
          :depth="depth + 10"
          :mode=" mode"
          :isHideBtnAdd="isHideBtnAdd"
          :active-category="activeCategory"
          @change-category="onChangeActiveCategory"
          @createChildrenCategory="onCreateChildrenCategory"
          @hover="hover = true"
          @out-hover="hover = false"
      >
      </category-menu>

    </div>
    <div v-if="isLastLevel" :style="{paddingLeft: `${depth + 20}px`}" v-show="hover"
         class="last-category-level">
      <span @click="addCategory">
          <svg-sprite  width="17" height="17" icon-id="plusRound"></svg-sprite>
      </span>
    </div>
  </div>
</template>
<script>
import { eventBus } from "@/main";

export default {
  name: 'category-menu',
  props: {
    label: String,
    depth: Number,
    id: Number,
    mode: String,
    customClass: String,
    isHideBtnAdd: Boolean,
    disabledCategories: {
      type: [Object, null],
      default() {
        return null;
      }
    },
    nodes: {
      type: [Array, Object],
      default() {
        return [];
      }
    },
  },
  data() {
    return {
      hover: false,
      showChildren: false,
      activeCategory: null
    }
  },
  computed: {
    isDisabled() {
      if (this.disabledCategories) {
        return this.disabledCategories.dontShow.includes(this.id)
      }

      return false
    },
    children() {
      if (this.disabledCategories) {
        return this.nodes.filter(category => !this.disabledCategories.dontShow.includes(category.id))
      }
      return this.nodes
    },
    indent() {
      return {marginLeft: `${this.depth}px`}
    },
    isLastLevel() {
      if (this.isHideBtnAdd) return false;

      return this.nodes.length > 0 && this.showChildren;
    }
  },
  mounted() {
    eventBus.$on('minimizeCategories', () => {
      this.showChildren = false
      this.activeCategory = null
    })

    eventBus.$on('updateCurrentId', (id) => {
      this.activeCategory = this.id === id;
    })
    eventBus.$on('resetActiveTab', () => (this.activeCategory = null))
  },
  methods: {
    toggleChildren() {
      this.showChildren = !this.showChildren;
    },
    onMouseover () {
      if (this.showChildren) {
        this.hover = true
        this.$emit('out-hover')
      } else  {
        this.$emit('hover')
      }
    },
    onMouseout() {
      this.hover = false
    },
    changeActiveCategory() {
      this.$emit('change-category', {id: this.id})
      eventBus.$emit('updateCurrentId', this.id);
      eventBus.$emit('updateTabCategory');
    },
    onChangeActiveCategory(id) {
      this.$emit('change-category', id)
    },
    onCreateChildrenCategory(id) {
      this.$emit('createChildrenCategory', id)
    },
    addCategory() {
      this.$emit('createChildrenCategory', this.id)
    }
  }
}
</script>
<style lang="scss">
.category-menu-item.disabled {
  pointer-events: none;
  opacity: .6;
}

.category-menu-item .arrow {
  width: 15px;
  height: 15px;
  display: inline-flex;
  align-self: center;
  justify-content: center;
}

.category-menu {
  border-bottom: 1px solid #E3F0FF;

  .category-menu-item {
    padding-left: 15px;
  }

  .category-menu {
    border: none;
    padding-bottom: 0;
    color: #7E7E7E;

    .category-menu-item {
      padding: 8px 0 8px 15px;
    }
  }

  &-item {
    padding-top: 15px;
    padding-bottom: 15px;
    font-size: 14px;
    cursor: pointer;
    line-height: 130%;
    padding-left: 10px;
    position: relative;
    color: #7E7E7E;;

  }

  .last-category-level {
    align-items: center;
    height: 0;
    overflow: hidden;
    display: flex;

    svg {
      cursor: pointer;
      width: 17px;
      height: 17px;
      pointer-events: none;
      transition: .5s;
      position: relative;
      top: 0px;

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

  &.active {
    &:hover {
      .last-category-level {
        height: auto;
        padding-top: 10px;
      }

      svg {
        pointer-events: auto;
      }
    }

    padding-bottom: 30px;

    .category-menu {
      border: none;
      padding-bottom: 0;
    }
  }

  &.transfer {
    padding-bottom: 5px;
  }
}

.category-menu-link {
  &.active {
    font-weight: 500;
    font-size: 14px;
    color: #FF9F2F;
  }
}

.category-menu-header {
  //padding: 15px;

  .arrow {
    position: absolute;
    top: 24px;
    transform: translateY(-50%);
    left: 0;
    width: 8px;
  }


  &.with-open-children {
    border-top: none;
    padding-bottom: 15px;
    font-weight: 500;
    font-size: 14px;
    color: #5893D4;
    .label {
      border-bottom: 1px solid #E3F0FF;
      padding-bottom: 10px;
    }
    .category-menu-item {
      font-weight: 400;
      &:not(.with-open-children) {
        .label {
          border-bottom: none;
          padding-bottom: 0;
        }
      }

      &.active {
        font-weight: 500;
      }
    }

    .arrow svg{
      transform: translateY(-50%) rotate(90deg);
    }

    .category-menu-header {
      .arrow {
        transform: none;
        top: 14px;
      }

      &.with-open-children {
        .arrow svg{
          transform: rotate(90deg);
        }
      }
    }

  }
  &:not(.with-open-children) {
    .label {
      border-bottom: none;
      padding-bottom: 0;
    }
  }

  &.active {
    font-weight: 500 !important;
    color: #FF9F2F !important;
  }
}

.category-menu.active > .category-menu-item.with-open-children > .label .btn-groupOpen svg{
  transform: rotate(90deg);
}

.btn-groupOpen {
  padding: 2px;
  width: 20px;
}
</style>
