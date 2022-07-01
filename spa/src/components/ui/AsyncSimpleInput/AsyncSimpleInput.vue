<template>
  <div class="search-block">
    <div class="filtered-block" :class="{'filtered-block--active': isFilter}" v-if="isFiltered" @click="onFilter" >
      <span class="filtered-icon">
        <svg-sprite width="14" height="10" icon-id="filter"></svg-sprite>
          <span class="filtered-cirlce-icon"></span>
      </span>
      <button type="button" class="filtered-btn" @click.stop="resetFilter">
          {{ $t('filters.resetFilters') }}
      </button>
    </div>
    <input type="text" :placeholder="$t('page.search')" ref="searchInput" v-model="search" @keyup="onSearch">
    <span @click="onClean" class="clearBtn" v-if="search">
      х
    </span>
    <div v-else class="search-icon">
      <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
    </div>
  </div>
</template>

<script>
import debounce from 'lodash.debounce'
import { mapGetters, mapActions } from 'vuex'

export default {
  name: "AsyncSimpleInput",
  props: {
    isFiltered: Boolean,
    resource: String
  },
  data() {
    return {
      search: '',
    }
  },
  computed: {
    ...mapGetters([
      'getFilterData'
    ]),
    isFilter() {
      return this.getFilterData(this.resource).is_active_filter
    }
  },
  methods: {
    ...mapActions([
      'resetFilters'
    ]),
    onSearch: debounce(function() {
      const query = this.search.toLowerCase().trim();
      this.$emit('updateInput', query)
    }, 300),
    onClean() {
      this.search = '';
      this.onSearch();
    },
    onFilter() {
      this.$emit('onFilter')
    },
    resetFilter() {
      this.resetFilters(this.resource)
    }
  }
}
</script>

<style scoped lang="scss">
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
