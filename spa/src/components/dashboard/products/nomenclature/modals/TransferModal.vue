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
            <svg-sprite width="20" height="20" icon-id="moveLarge"></svg-sprite>
            <span class="text">Перенести</span>
          </div>

          <header-actions
              id="goods"
              title="Перенести"
              @updateClose="close"
              is-hide-attach
              isHideDots
          ></header-actions>
        </div>
        <div class="dialog-content dialog-content-large">
          <p class="move-title">{{$t('nomenclature.move_nomenclature')}} <span>{{ title }}</span> в :</p>
          <category-tree
              @changeCategory="onChangeActiveCategory"
              :categories="categoryTree"
              editable
          >
          </category-tree>
          <div class="category-tree-wrapper">
          </div>
          <simplebar class="category-menu-wrapper">
            <category-menu
                v-for="category in categories"
                :key="category.id"
                :label="category.title"
                :nodes="category.children"
                :depth="0"
                :id="category.id"
                @change-category="onChangeActiveCategory"
                :mode="'transfer'"
                isHideBtnAdd
            ></category-menu>
          </simplebar>
          <div class="nomenclature-move-text">
            {{ $t('nomenclature.nomenclature_move_text') }}
          </div>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                v-if="mode !== 'view'"
                :title="$t('page.cancelButton')"
                custom-class="cancel"
                color="#5893D4"
                text
                @updateEvent="close"
            ></custom-btn>
            <custom-btn
                :title="(mode === 'view') ? $t('page.close') : $t('page.saveButton')"
                custom-class="save"
                color="#5893D4"
                @updateEvent="(mode === 'view') ? close(): save()"
            ></custom-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
import {eventBus} from "@/main";
// components
import CategoryMenu from "@/components/dashboard/products/nomenclature/CtegoryMenu";
import CategoryTree from "@/components/dashboard/products/nomenclature/modals/froms/CategoryTree";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";

export default {
  name: "TransferModal",
  components: {
    CategoryTree,
    CategoryMenu,
    HeaderActions,
    CustomBtn
  },
  data() {
    return {
      dialog: false,
      mode: false,
      items: [],
      categoryId: null,
      categoryTree: [],
      title: null,
      resource: null
    }
  },
  computed: {
    ...mapGetters({
      category: 'category'
    }),
  },
  props: {
    categories: Array
  },
  methods: {
    ...mapActions({
      fetchCategory: 'fetchCategory',
    }),
    open({items, mode, title, resource}) {
      this.mode = mode
      this.items = items;
      this.title = title
      this.dialog = true
      this.resource = resource
    },
    close() {
      this.dialog = false
    },
    save() {
      const params = {
        items: this.items.filter(item => !item.isChild),
        mode: this.mode,
        categoryId: this.categoryId,
        alertParams: {
          textItems: [
            {text: 'nomenclature', style: 'normal', translate: true},
            // {text: this.resource.SINGLE_VALUE, style: 'normal', translate: true},
            {text: this.title, style: 'bold', translate: false},
            {text: 'in_category', style: 'normal', translate: true},
            {text: this.category?.title, style: 'bold', translate: false}
          ],
          link: false
        }
      }
      this.$emit('save', params)
    },
    onChangeActiveCategory({id}) {
      this.categoryId = id
      this.categoryTree = []
      this.fetchCategory(id).then(() => this.getCategoryTree(this.category))
      eventBus.$emit('resetSwitchedCategory', id)
    },
    getCategoryTree(category) {
      this.categoryTree.push({ title: category.title, id: category.id })
      if (category.parent) {
        this.getCategoryTree(category.parent)
      }
    }

  }
}
</script>

<style scoped lang="scss">
.text {
  margin-left: 15px;
}

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
  color: #5893D4;
  margin-bottom: 40px;
}

.category-tree-wrapper {
  margin-bottom: 30px;
  min-height: 28px;
}
</style>
