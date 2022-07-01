<template>
  <div class="item-container" :class="{ hide: !visible, 'notClickable': moveLoading }">
    <div @click="toggleCategoriesState" class="all" :class="{'checked': isOpenedAll}">
      <div class="all-left">
        <input type="checkbox" v-model="isCheckedAll" @click.stop="selectAll">
        <span>Все</span>
      </div>
      <span class="all-arrow">
        <svg-sprite width="8" height="6" icon-id="secondArrow"></svg-sprite>
      </span>
    </div>
    <draggable-tree
        v-if="isOpenedAll"
        ref="druggableList"
        :data="categories"
        draggable="draggable"
        @drop="onDropCategory"
        @drag="onDragCategory"
        @nodeOpenChanged="onOpenNode"
        cross-tree
    >
      <div slot-scope="{data, store}">
        <div
            class="item"
            :class="{'active': activeId === data.id}"
            @contextmenu.prevent="$refs.menu.open($event, { data, categories })"
            @click="store.toggleOpen(data)"
        >
          <div class="drug-icon">
            <svg-sprite width="10" height="16" icon-id="drugIcon"></svg-sprite>
          </div>
          <input
              type="checkbox"
              @click.stop="onSelectCategory($event, data)"
              :checked="selectedCategories.some(item => item.id === data.id)"
          >
          <template v-if="!data.isDragPlaceHolder">
            <i class="icon" :class="{open: data.open}" v-if="data.children && data.children.length">
              <svg-sprite width="8" height="5" icon-id="secondArrowBlue"></svg-sprite>
            </i>
          </template>
          <span @mousedown.stop @click.stop="$router.push({path: `/products/categories/${data.id}`})"
                class="item-link" :class="{'active': activeId === data.id}">
            {{ data.title }}
         </span>
        </div>
      </div>
    </draggable-tree>

    <vue-context class="context-menu" ref="menu" v-slot="{ data }">
      <li class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="13" height="13" icon-id="contextMenuCopy"></svg-sprite>
        </div>
        <a @click.prevent="onClickCopy($event.target, data)">{{ $t(`context_menu.copy`) }}</a>
      </li>
      <li class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="13" height="13" icon-id="contextMenuEdit"></svg-sprite>
        </div>
        <a @click.prevent="onClickEdit($event.target, data)">{{ $t(`context_menu.update`) }}</a>
      </li>
      <li class="context-menu-item">
        <div class="item-icon">
          <svg-sprite width="10" height="11" icon-id="contextMenuRemove"></svg-sprite>
        </div>
        <a @click.prevent="onClickDelete($event.target, data)">{{ $t(`context_menu.delete`) }}</a>
      </li>
    <li class="context-menu-item" v-if="categories.length > 1">
        <div class="item-icon">
          <svg-sprite width="10" height="11" icon-id="contextMenuRemove"></svg-sprite>
        </div>
        <a @click.prevent="onClickMove($event.target, data)">{{ $t(`context_menu.move`) }}</a>
      </li>
    </vue-context>
    <category-edit
        :units="units"
        @updateClose="onClose"
        @show-alert="onShowAlert"
        :categories="categories"
        :category="category"
        ref="editCategory"
    ></category-edit>
  </div>
</template>

<script>
// vuex
import {mapGetters} from 'vuex';
// components
import CategoryEdit from "@/components/dashboard/categories/CategoryEdit";
// services
import httpClient from "@/services/http-client/http-client";
import getFlatMap from "@/helper/getFlatMap";

export default {
  name: "categories-list",
  components: {
    CategoryEdit
  },
  props: {
    moveLoading: Boolean,
    search: {
      type: String,
    },
    units: Array,
    selectedCategories: Array,
    activeId: Number,
    nested: Boolean,
    isEditCategory: Boolean
  },
  computed: {
    ...mapGetters({
      getCategories: 'categories'
    }),
    getFlatCategories() {
     return getFlatMap(this.categories);
    },
    categories() {
      if (this.getCategories) {
        const categoriesWithNesting = this.getCategories.map(item => this.getParents(item, 1), null);

        return categoriesWithNesting.map(item => this.getChildLength(item, 1))
      }

      return []
    }
  },
  data: () => ({
    currentId: null,
    visible: true,
    category: null,
    isOpenedAll: true,
    dragCategory: null,
    isPopup: false,
    isCheckedAll: false
  }),
  watch: {
    nested(val) {
      const openFunction = node => {
        node.open = val
        node.children.forEach(
            treeItem => {
              treeItem.open = val
            }
        )
      }
      let data = this.$refs.druggableList.data
      data.forEach(item => openFunction(item))
    },
    async isEditCategory(val) {
      if (val) {
        const categoryId = this.selectedCategories[0].id
        const url = `/products/categories/${categoryId}`
        const {data} =  await httpClient.get(url)
        this.onClickEdit(null, data);
      }
    }
  },
  methods: {
    onClose() {
      this.$emit('updateClose')
    },
    onShowAlert (alertParams) {
      this.$emit('reset-edit-category')
      this.$emit('show-alert', alertParams)
    },
    editCategory(category) {
      this.$refs.editCategory.open(category)
    },
    onOpenNode() {
      this.$emit('open-node')
    },
    onSelectCategory(evt, data) {
      this.isCheckedAll = false;
      if (evt) {
        this.getFlatCategories.forEach(category => {
          if (category.id === data.id) {
            category.isChecked = evt.target.checked;
          }
        })
      }

      this.$emit('selectCategory', { id: data.id })
    },
    selectAll() {
      let values = [];
      if(!this.isCheckedAll) {
        values = this.getFlatCategories.filter(item => !item.isChecked).map(body => body.id);
      } else {
        values = this.getFlatCategories.map(body => body.id);

      }
      this.$emit('selectCategory', values);
      this.getFlatCategories.forEach(category => {
        category.isChecked = !this.isCheckedAll;
      })
    },
    onDropCategory({ id, parent }) {
      const sortedCategories = [];
      const recursive = (category) => {
        sortedCategories.push({id: category.id, title: category.title})
        category.children.forEach(
            child => recursive(child)
        )
      };

      const parent_id = !parent.isRoot ? parent.id : 0;
      const data = this.$refs.druggableList.data;
      data.forEach(category => {
        recursive(category)
      })
      this.$emit('sortCategories', {
        moveParams: {id, parent_id},
        sortParams: sortedCategories
      })
    },
    onDragCategory(data) {
      this.dragCategory = data
    },
    /* Methods for context menu */
    async onClickCopy(text, data) {
      this.$emit('copyCategory', {id: data.data.id})
    },
    toggleCategoriesState() {
      this.isOpenedAll = !this.isOpenedAll
    },
    onClickEdit(text, data) {
      this.category = null;
      this.editCategory(data.data)
    },
    onClickDelete(text, data) {
      this.$emit('deleteCategory', [{id: data.data.id}])
    },
    onClickMove (text, data) {
      this.$emit('moveCategory', data)
    },
    getChildLength(category, childNesting) {
      if (category.categoryParent) {
        this.getChildLength(category.parent, childNesting + 1)
      }

      if (!category.children.length) {
        category.children = category.children.map(item => this.getChildLength(item, childNesting + 1, category))
      }

      category.childNesting = childNesting;

      return category
    },
    getParents(category, parentsNesting, parent) {
      if (category.children && category.children.length) {
        category.children = category.children.map(item => this.getParents(item, parentsNesting + 1, category))
      }

      category.categoryParent = parent;
      category.nesting = parentsNesting;
      category.droppable = parentsNesting < 4;

      return category
    }
  }
};
</script>

<style lang="sass">
.item.active
  background-color: #F4F9FF !important
.item-container
  .all
    width: auto
    padding-left: 15px

    &.checked
      .all-arrow
        transform: rotate(180deg)


  .drug-icon
    display: flex
    width: 15px

    svg
      display: none

  .item
    &:hover
      .drug-icon
        cursor: grab

        svg
          display: block

  .item-link
    &.active
      color: #FF9F2F !important

    .draggable-placeholder-inner
      border: 1px dashed #0088f8
      box-sizing: border-box
      background: rgba(0, 136, 249, 0.09)
      background: #c8ebfb
      color: #0088f9
      text-align: center
      padding: 0
      display: flex
      align-items: center
.icon
  cursor: pointer
.notClickable
  pointer-events: none
</style>
