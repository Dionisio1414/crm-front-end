<template>
  <div class="category-tree">
    <div v-for="(category, index) in reverseCategories" :key="category.id" class="category-tree-item">
      <span v-if="editable && index !== categories.length - 1" class="category-tree-name " :class="{'editable':editable}"
            @click.stop="changeCategory(category)">
        {{ category.title }}
      </span>

      <span v-else class="category-tree-name">
        {{ category.title }}
      </span>

      <svg v-if="index !== categories.length - 1" width="6" height="11" viewBox="0 0 6 11" fill="none"
           xmlns="http://www.w3.org/2000/svg">
        <path
            d="M5.83709 5.07078L1.29221 0.17573C1.1871 0.0624223 1.04677 0 0.897152 0C0.74753 0 0.607207 0.0624223 0.50209 0.17573L0.167391 0.536134C-0.0503999 0.770977 -0.0503999 1.15267 0.167391 1.38715L3.98384 5.49772L0.163156 9.61285C0.0580385 9.72616 -3.12529e-07 9.8772 -3.17625e-07 10.0383C-3.22727e-07 10.1995 0.0580385 10.3506 0.163156 10.464L0.497855 10.8243C0.603055 10.9376 0.743295 11 0.892917 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z"
            fill="#9DCCFF"/>
      </svg>
    </div>
  </div>
</template>

<script>

export default {
  name: "CategoryTree",
  props: {
    categories: Array,
    editable: Boolean
  },
  computed: {
    reverseCategories() {
      if (this.categories) {
        return [...this.categories].reverse()
      }
      return []
    }
  },
  methods: {
    changeCategory(category) {
      this.$emit('changeCategory', category)
    }
  }
}
</script>

<style scoped lang="scss">
.category-tree {
  display: flex;

  &-item {
    margin-right: 12px;
    &:last-child {
      .category-tree-name{
        font-weight: 600;
      }
    }
  }

  &-name {
    margin-right: 16px;
    font-weight: 400;
    font-size: 15px;
    line-height: 15px;
    color: #7E7E7E;

    &.editable {
      cursor: pointer;
      &:hover {
        color: #FF9D2B;
        text-decoration: underline;
      }
    }
  }
}
</style>