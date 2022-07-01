<template>
  <v-autocomplete
      class="searchGoods"
      :class="customClass"
      :placeholder="$t('page.wareHouses.asyncSearchText')"
      no-filter
      autofocus
      :item-text="getValue"
      item-value="nomenclature_id"
      v-model="value"
      :items="getComputedItems"
      no-data-text="Нет значений"
      @keydown="onSearch"
      @change="onChange"
      :loading="loading"
      :menu-props="{maxHeight: 304 ,contentClass: 'searchGoodsList'}"
      return-object
  ></v-autocomplete>
</template>

<script>
// vuex
import { mapActions } from "vuex";
// debounce
import debounce from 'lodash.debounce';

export default {
  name: "AsyncSearchInput",
  props: {
    body: Array,
    searchList: Array,
    loading: Boolean,
    customClass: String,
    resource: String
  },
  data: () => ({
    value: ''
  }),
  computed: {
    getComputedItems() {
      return this.getDisabledItems(this.body);
    }
  },
  methods: {
    ...mapActions(['getSearchGoods', 'clearSearchInput']),
    getValue(value) {
      const { sku, convert_id, short_title } = value;
      return `${convert_id || ''} ${sku || ''} ${short_title || ''}`;
    },
    getDisabledItems(body) {
      return this.searchList.reduce((acc, bodyItem) => {
        body.forEach(item => {
          if (item.id === bodyItem['nomenclature_id']) {
            bodyItem.disabled = true;
          }
        })

        acc.push(bodyItem);

        return acc;
      }, [])
    },
    onChange(obj) {
      this.$emit('updateEventValue', obj)
    },
    onSearch: debounce(function(evt) {
      const value = evt.target.value.trim();

      this.getSearchGoods(value.toLowerCase());
    }, 500),
  },
  destroyed() {
    this.clearSearchInput('');
  }
}
</script>

<style lang="scss">
@import "../../../sass/mixins";

  .searchGoodsList {
    &.v-autocomplete__content.v-menu__content .v-select-list {
      outline: none !important;
      border: none !important;
      box-shadow: none;
    }

    .v-list-item {
      min-height: 30px !important;

      &:hover {
        background: #F4F9FF;
        opacity: 1 !important;

        .v-list-item__title {
          color: #5893D4 !important;
        }

        &:before {
          display: none !important;
        }
      }

      .v-list-item__content {
        padding: 7px 0;
      }

      &.v-list-item--disabled .v-list-item__title {
        color: #BDBDBD !important;
      }
    }
  }

  .searchGoods {
    background: #FFFFFF;
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 100px;
    padding: 0 15px;
    margin: 0;

    .v-list-item:hover:before {
      opacity: 1 !important;
    }

    .v-input__append-inner {
      display: none;
    }

    .v-input__slot {
      font-size: 15px;
      line-height: 1.5;
      color: rgba(88, 147, 212,.5);
      margin-bottom: 0;

      @include placeholder {
        font-size: 15px;
        line-height: 1.5;
        color: #5893D4;
        opacity: 0.5;
      }

      &:before {
        display: none !important;
      }
    }
  }
</style>
