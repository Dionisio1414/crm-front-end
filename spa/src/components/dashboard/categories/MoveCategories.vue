<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialog"
        max-width="1230px"
        class="dialog-large dialog-category "
    >
      <div class="dialog  move-modal">
        <div class="dialog-header">
          <div class="header-text">
            <span>Назначить категорию и перенести </span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="close">
              <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
            </v-btn>
          </div>
        </div>
        <div class="dialog-content dialog-content-large">
          <p class="move-title">
            {{ $t('category.move_info_first_part') }}
            <!--            {{ productsQuantity }}-->
            (0)
            {{ $t('category.move_info_second_part') }}
            ({{ childLength }})
            в :</p>
          <div class="category-tree-wrapper">
            <category-tree
                :editable="true"
                @changeCategory="onChangeActiveCategory"
                :categories="categoryTree"
            ></category-tree>
          </div>
          <simplebar class="category-menu-wrapper" v-if="filteredCategories">
            <category-menu
                v-for="category in filteredCategories"
                @change-category="onChangeActiveCategory"
                :activeCategory="categoryId"
                :isHideBtnAdd="isHideBtnAdd"

                :key="category.id"
                :active-category="activeCategory"
                :label="category.title"
                :nodes="category.children"
                :depth="0"
                :disabled-categories="disabledCategories"
                :id="category.id"
                :mode="'transfer'"
            ></category-menu>
          </simplebar>
          <div class="nomenclature-move-text" v-if="activeCategory">
            {{ $t('category.move_text_first_part') }}
            <b>
              {{ activeCategory.title }}

            </b>
            {{ $t('category.move_text_second_part') }}
            <b>
              {{ title }}
            </b>
          </div>
          <div class="dialog-actions popup-buttons">
            <v-btn
                v-if="mode !== 'view'"
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="close"
            >
              {{ $t('page.close') }}
            </v-btn>
            <v-btn
                v-if="mode !== 'view'"
                class="popup-btn white--text"
                color="#5893D4"
                light
                @click="save"
            >
              Назначить и перенести
            </v-btn>
            <v-btn
                v-if="mode === 'view'"
                class="popup-btn white--text"
                color="#5893D4"
                light
                @click="close"
            >
              {{ $t('page.close') }}
            </v-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
import CategoryMenu from "@/components/dashboard/products/nomenclature/CtegoryMenu";
import {eventBus} from "@/main";
import CategoryTree from "@/components/dashboard/products/nomenclature/modals/froms/CategoryTree";
import {mapActions, mapGetters} from "vuex";

export default {
  name: "MoveCategories",
  components: {CategoryTree, CategoryMenu},
  props: {
    categories: Array,
    isHideBtnAdd: Boolean
  },
  data() {
    return {
      dialog: false,
      items: [],
      categoryId: null,
      categoryTree: [],
      title: null,
      activeCategory: null,
      mode: 'transfer',
      disabledCategories: [],
      childLength: null
    }
  },
  computed: {
    ...mapGetters({
      category: 'category'
    }),
    filteredCategories (){
      return this.categories.map(category => {
        category.children.filter(item => !this.disabledCategories.dontShow || [].includes(item.id))
        return category
      })
    }
  },
  methods: {
    ...mapActions(['fetchCategory']),
    open({id, title, children, parent_id}) {
      this.categoryId = id
      this.title = title;
      this.childLength = children && children.length;
      this.dialog = true
      this.activeCategory = null
      this.disabledCategories = {
        disabled: [parent_id],
        dontShow: [id],
      }
    },
    close() {
      this.dialog = false
    },
    save() {
      const params = {
        data: {id: this.categoryId, parent_id: this.activeCategory.id},
        alertParams: {
          textItems: [
            {text: 'move_category.first_part', style: 'normal', translate: true},
            {text: this.title, style: 'bold', translate: false},
            {text: 'move_category.second_part', style: 'normal', translate: true},
            {text: this.activeCategory.title, style: 'bold', translate: false},

          ],
          link:
              {
                action: 'view-item', text: 'nomenclature.alert_massages.open',
                actionParams: {
                  id: this.activeCategory.id
                }
              }
        }
      }
      this.$emit('save', params)
    },
    onChangeActiveCategory({ id }) {
      this.categoryTree = []
      this.fetchCategory(id).then(() => {
        this.activeCategory = this.category
        this.getCategoryTree(this.category)
      })
      eventBus.$emit('resetSwitchedCategory', id)
    },
    getCategoryTree(category) {
      this.categoryTree.push({title: category.title, id: category.id})
      if (category.parent) {
        this.getCategoryTree(category.parent)
      }
    }

  }
}
</script>

<style scoped lang="scss">
.move-title {
  font-size: 18px;
  line-height: 18px;
  font-weight: 400;
  /* identical to box height */
  span {
    font-weight: 500;
  }

  color: #5893D4;
  padding-top: 20px;
}

.category-menu-wrapper {
  height: 350px;
  padding: 0 50px;
  width: 800px;
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  border-radius: 10px;
  margin-bottom: 30px;
}

.nomenclature-move-text {
  font-size: 16px;
  line-height: 16px;
  text-align: center;
  max-width: 800px;
  color: #5893D4;
  margin-bottom: 40px;
}

.category-tree-wrapper {
  margin-bottom: 30px;
  min-height: 28px;
}
</style>

