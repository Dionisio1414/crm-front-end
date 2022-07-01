<template>
  <div class="left-header">
    <search-block></search-block>
    <v-btn icon class="add-new" @click="onCreateGroups" color="#5893D4">
      <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M30 15C30 23.2843 23.2843 30 15 30C6.71573 30 3.18607e-06 23.2843 4.76346e-06 15C6.34085e-06 6.71573 6.71574 -9.34605e-07 15 -6.02084e-07C23.2843 -2.69562e-07 30 6.71573 30 15Z"
            fill="#5893D4"/>
        <path
            d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z"
            fill="#F4F9FF"/>
      </svg>
    </v-btn>
  </div>
</template>

<script>
import SearchBlock from "@/components/dashboard/products/suppliers/leftheader/components/SearchBlock";

export default {
 name: "LeftHeader",
  components: {
   'search-block': SearchBlock
  },
  methods: {
    onCreateGroups() {
      this.$emit('openCreateGroups');
    },
  }
}
</script>

<style scoped lang="scss">
.left-header {
  padding: 20px;
  display: flex;
  justify-content: space-between;

  .search-block {
    margin-right: 10px;
    border: 1px solid #E3F0FF;
    border-radius: 100px;
  }
  .category-menu .category-menu .category-menu-item {
    padding: 8px 0 8px 20px;
  }
}
</style>