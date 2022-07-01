<template>
  <div class="table-wrapper">
    <div class="grid-table">
      <div class="thead">
        <div
            class="tr"
            :style="{
             gridTemplateColumns: gridTemplateColumnsHeader,
             gridColumn: `span ${notPricesHeaders.length + 1 }`
           }"
        >
          <div class="checkbox" v-if="withCheckbox">
            <label class="checkbox-label">
              <input :checked="isAllSelected" type="checkbox" @click="toggleAll">
              <div class="checkbox-text"/>
            </label>
          </div>
          <div v-else class="empty-th"></div>
          <div class="th" v-for="header in notPricesHeaders" :key="header.title">
            {{ header.title }}
          </div>
          <div class="th prices">
            <div @click.prevent="slidePrev" class="prices-controls prices-controls__prev">
              <svg width="6" height="11" viewBox="0 0 6 11" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0.162909 5.07078L4.70779 0.17573C4.8129 0.0624223 4.95323 0 5.10285 0C5.25247 0 5.39279 0.0624223 5.49791 0.17573L5.83261 0.536134C6.0504 0.770977 6.0504 1.15267 5.83261 1.38715L2.01616 5.49772L5.83684 9.61285C5.94196 9.72616 6 9.8772 6 10.0383C6 10.1995 5.94196 10.3506 5.83684 10.464L5.50215 10.8243C5.39694 10.9376 5.25671 11 5.10708 11C4.95746 11 4.81714 10.9376 4.71202 10.8243L0.162909 5.92475C0.057542 5.81108 -0.000330269 5.65932 1.60448e-06 5.49799C-0.000330279 5.33603 0.057542 5.18436 0.162909 5.07078Z"
                    fill="#5893D4"/>
              </svg>
            </div>
            <div @click.prevent="slideNext" class="prices-controls prices-controls__next">
              <svg width="6" height="11" viewBox="0 0 6 11" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0.162909 5.07078L4.70779 0.17573C4.8129 0.0624223 4.95323 0 5.10285 0C5.25247 0 5.39279 0.0624223 5.49791 0.17573L5.83261 0.536134C6.0504 0.770977 6.0504 1.15267 5.83261 1.38715L2.01616 5.49772L5.83684 9.61285C5.94196 9.72616 6 9.8772 6 10.0383C6 10.1995 5.94196 10.3506 5.83684 10.464L5.50215 10.8243C5.39694 10.9376 5.25671 11 5.10708 11C4.95746 11 4.81714 10.9376 4.71202 10.8243L0.162909 5.92475C0.057542 5.81108 -0.000330269 5.65932 1.60448e-06 5.49799C-0.000330279 5.33603 0.057542 5.18436 0.162909 5.07078Z"
                    fill="#5893D4"/>
              </svg>
            </div>
            <hooper group="prices" :itemsToShow="3" ref="priceSlider">
              <slide v-for="item in pricesHeaders"
                     :key="item.title">
                <div class="prices-item">
                  {{ item.title }}
                </div>
              </slide>
            </hooper>
          </div>
        </div>
      </div>
      <simplebar class="tbody">
        <div class="search-tr" v-if="searchable">
          <div class="search-td">
            <async-input
                :body="body"
                :searchList="searchList"
                :loading="loading"
                @updateEventValue="onChange">
            </async-input>
          </div>
        </div>
        <div
            v-for="(item, index) in body"
            :key="item.convert_id"
            class="tr"
            :style="{
               gridTemplateColumns: gridTemplateColumnsBody,
               gridColumn: `span ${notPricesHeaders.length + 1 }`
             }">
          <div class="td" v-if="!withCheckbox">
            {{ index + 1 }}.
          </div>
          <div class="td" v-if="withCheckbox">
            <label class="checkbox-label">
              <input type="checkbox" :checked="getItemSelected(item.id)" @click="toggleItem(item.id)">
              <div class="checkbox-text"/>
            </label>
          </div>
          <div
              v-for="header in notPricesHeaders"
              :key="header.title"
              class="td"
              :class="{'photo': header.value === 'photo'}"
          >
            <span v-if="header.value !== 'photo'">
              {{ item.cells[header.value] || '--' }}
            </span>
            <span v-else>
              <img v-if="item.cells[header.value]" width="46" height="36" :src="item.cells[header.value]" :alt="header.value">
              <svg-sprite v-else width="46" height="36" icon-id="defaultPhoto"></svg-sprite>
            </span>
          </div>
          <div class="td">
            <hooper disabled="true" group="prices" :itemsToShow="3">
              <slide v-for="header in pricesHeaders"
                     :key="header.title">
                <div>
                  {{ item.cells[header.value] || '--' }}
                </div>
              </slide>
            </hooper>
          </div>
          <div class="td cross" @click="delSelectedItem(item.id)" v-if="!withCheckbox">
            <svg-sprite width="12" height="12" icon-id="removes"></svg-sprite>
          </div>
        </div>
      </simplebar>
    </div>
  </div>
</template>

<script>
import {Hooper, Slide} from 'hooper';
import 'hooper/dist/hooper.css';
import AsyncInput from "./AsyncSearchInput/AsyncSearchInput";
import {mapGetters} from "vuex";
import httpClient from "../../services/http-client/http-client";


export default {
  name: "TableWithPrices",
  components: {
    AsyncInput,
    Hooper,
    Slide,
  },
  props: {
    headers: Array,
    body: {
      type: Array,
      default: () => [{"id":1,"title":"ID","is_sortable":1,"is_visible":1,"order":0,"value":"convert_id"},{"id":2,"title":"Артикул","is_sortable":1,"is_visible":1,"order":1,"value":"sku"},{"id":3,"title":"Фото","is_sortable":1,"is_visible":1,"order":0,"value":"photo"},{"id":4,"title":"Наименование","is_sortable":1,"is_visible":1,"order":0,"value":"short_title"},{"id":5,"title":"Ед.изм","is_sortable":1,"is_visible":1,"order":0,"value":"units"},{"id":6,"title":"Остаток","is_sortable":1,"is_visible":1,"order":0,"value":"balance"},{"id":7,"title":"Цена розничная","is_sortable":1,"is_visible":1,"order":0,"value":"price_2"},{"id":8,"title":"Цена оптовая","is_sortable":1,"is_visible":1,"order":0,"value":"price_3"},{"id":9,"title":"Цена закупочная","is_sortable":1,"is_visible":1,"order":0,"value":"price_1"}]
    },
    selectedItems: Array,
    searchable: {
      type: Boolean
    },
    withCheckbox: {
      type: Boolean,
      default: false
    }

  },
  methods: {
    toggleAll() {
      this.$emit('toggle-all', this.isAllSelected)
    },
    toggleItem(id) {
      this.$emit('toggle-item', id)
    },
    slidePrev() {
      this.$refs.priceSlider.slidePrev();
    },
    slideNext() {
      this.$refs.priceSlider.slideNext();
    },
    getItemSelected (id) {
      const index = this.selectedItems.findIndex(item => item.id === id)
      return index !== -1
    },
    delSelectedItem(id) {
      this.$emit('del-select-item', id)
    },
    async onChange({nomenclature_id}) {
      const url = `/products/nomenclatures/related/table-product/${nomenclature_id}`
      const { data } = await httpClient.get(url)
      this.$emit('select-item', data.data.body)
    },
  },
  computed: {
    ...mapGetters(['getLists', 'searchList', 'loading']),
    isAllSelected ( ) {
      if (this.selectedItems) {
        return this.selectedItems.length === this.body.length
      }
      return false
    },
    gridTemplateColumnsHeader() {
      if (this.withCheckbox) {
        return '3% 7% 7% 15% 9% 10% 50%';
      }

      return '3% 7% 7% 17% 7% 10% 50%';
    },
    gridTemplateColumnsBody() {
      if (this.withCheckbox) {
        return '3% 7% 7% 15% 9% 13% 45%';
      }

      return '3% 8% 7% 16% 7% 13% 40% 5%';
    },
    pricesHeaders() {
      if (this.headers) {
        return this.headers.filter(header => header.value.includes('price') && header.value !== 'sku')
      }

      return []
    },
    notPricesHeaders() {
      if (this.headers) {
        return this.headers.filter(header => !header.value.includes('price') && header.value !== 'sku')
      }

      return []
    },
  }
}
</script>

<style scoped lang="scss">
.search-tr {
  height: 50px;
  padding: 8px 15px;

  .search-td {
    height: 100%;
    width: 440px;
  }
}

.grid-table {
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  border-radius: 10px;
  height: 100vh;
  max-height: 380px;

  .checkbox {
    display: flex;
    align-items: center;
  }

  .checkbox-text {
    &:after {
      content: "";
      background: #7CE1A4;
      position: absolute;
      top: 50%;
      left: 4px;
      transform: translateY(-50%);
      width: 8px;
      height: 8px;
      opacity: 0;
      transition: opacity 0.25s ease-in-out;
    }

    &:before {
      content: "";
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      border: 1px solid #7CE1A4;
      width: 16px;
      height: 16px;
    }
  }


  .thead {
    .checkbox {
      padding-left: 6px;
    }

    .tr {
      padding: 5px 15px;
      border-bottom: 1px solid #9DCCFF;

      .th {
        padding-top: 14px;
        padding-bottom: 10px;

        &.prices {
          position: relative;
          background: #F4F9FF;
          border-radius: 10px;
          font-size: 14px;
          line-height: 14px;
          font-weight: 400;
          padding: 0 30px;
          overflow: hidden;
          color: #5893D4;

          .prices-item {
            height: 100%;
            display: flex;
            align-items: center;
          }

          .prices-controls {
            top: 0;
            bottom: 0;
            position: absolute;
            width: 23px;
            height: 38px;
            background: rgba(157, 204, 255, 0.99);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;

            &__prev {
              left: 0;

            }

            &__next {
              right: 0;

              svg {
                transform: rotate(-180deg);
              }
            }
          }
        }
      }
    }
  }

  .tbody {
    max-height: 330px;
    overflow: auto;

    .tr {
      padding: 5px 15px;
      align-items: center;
      border-bottom: 1px solid #E3F0FF;

      .td {
        font-size: 15px;
        line-height: 15px;
        color: #7E7E7E;

        &.photo {
          padding: 0 5px !important;
        }

        &.cross {
          text-align: right;
        }
      }
    }
  }

  .tr {
    display: grid;

    .th {
      font-weight: 500;
      font-size: 15px;
      line-height: 15px;
      text-transform: capitalize;
      color: #5893D4;

    }

    .td, .th {
      overflow: hidden;
      padding: 14px 5px 10px;

    }
  }
}

.hooper {
  height: 100% !important;
  /*width: 1000px !important;*/

  .hooper-slide {
    opacity: 0;

    &.is-active {
      opacity: 1;
    }
  }
}

//.table-wrapper{
//  max-height: au;
//}

</style>
