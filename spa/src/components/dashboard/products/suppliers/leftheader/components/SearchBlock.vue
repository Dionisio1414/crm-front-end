<template>
  <div class="search-block" :class="customClass">
    <button v-if="isFilter" class="filterBtn">
      <svg-sprite width="14" height="10" icon-id="filter"></svg-sprite>
    </button>
    <input type="text" :placeholder="$t('page.search')" ref="searchInput" v-model="search" @keyup="onSearch">
    <span @click="onClean" class="clearBtn" v-if="search">х</span>
    <div v-else class="search-icon">
      <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
    </div>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// lodash
import debounce from 'lodash.debounce';

export default {
  name: "SearchBlock",
  props: {
    isFilter: Boolean,
    customClass: String
  },
  data() {
    return {
      search: '',
    }
  },
  computed: {
    ...mapGetters({
      categories: 'categories',
    }),
  },
  methods: {
    ...mapActions(['getSearchGroup', 'getSearchTable']),
    onSearch: debounce(function () {
      const query = this.search.trim();

      if (this.isFilter) {
        this.getSearchTable(query.toLowerCase());
      } else {
        this.getSearchGroup(query.toLowerCase());
      }

    }, 300),
    onClean() {
      this.search = '';
      this.onSearch();
    }
  }
}
</script>

<style scoped lang="scss">
.search-block {
  &.searchPurchases {
    width: 400px;
  }
}


.clearBtn {
  width: 15px;
  height: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  background-color: #ffffff;
  right: 12px;
  z-index: 1;
  color: #5893D4;
  cursor: pointer;
  font-weight: 100;
}

.filterBtn {
  height: 35px;
  width: 60px;
  margin-right: -25px;
  padding-right: 20px;
  position: relative;
  border-radius: 20px 0 0 20px;
  background: #5893D4;

  &:after {
    content: "";
    display: block;
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #FF9F2F;
    top: 7px;
    left: 21px;
  }
}
</style>