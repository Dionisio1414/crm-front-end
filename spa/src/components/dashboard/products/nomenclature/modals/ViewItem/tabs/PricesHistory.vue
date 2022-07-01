<template>
  <div class="accompanying prices">
    <div class="accompanying-header" v-if="isUpdate">
      <div class="search-block">
        <input type="text" :placeholder="$t('page.search')" v-model="search">
        <div class="search-icon">
          <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
               xmlns="http://www.w3.org/2000/svg">
            <path
                d="M7.685 14.3335C11.377 14.3335 14.37 11.3487 14.37 7.66674C14.37 3.9848 11.377 1 7.685 1C3.99298 1 1 3.9848 1 7.66674C1 11.3487 3.99298 14.3335 7.685 14.3335Z"
                stroke="#5893D4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16.0002 16L12.3652 12.375" stroke="#5893D4" stroke-linecap="round"
                  stroke-linejoin="round"/>
          </svg>
        </div>
      </div>
      <div class="header-right">
        <icon-with-tooltip
            color="#F4F9FF"
            icon="mdi-pencil-outline"
            :loading="selectedItems.length !== 1"
            customClass="delete"
            tooltip-text="Изменить цену"
            @push-btn="onClickChangeRow"
        />
        <icon-with-tooltip
            color="#F4F9FF"
            customClass="delete"
            @push-btn="onClickDelRow"
            icon="mdi-trash-can-outline"
            tooltip-text="Удалить цену"
        />
      </div>
    </div>
    <div class="table-wrapper table-layout-fixed" :class="{'loading': loading}">
      <div class="table-responsive">
        <v-data-table v-if="this.items"
                      :headers="formatHeaders"
                      :items="[items.body, formatHeaders]"
                      :sortable="true"
                      :sort-desc.sync="sortDesc"
                      :sort-by.sync="sortBy"
                      :items-per-page="10"
                      :fixed-header="true"
                      hide-default-header
                      hide-default-footer
                      class="table"
                      ref="table"
        >
          <template v-slot:header="{props}">
            <thead>
            <tr>
              <th class="td-checkbox">
                <div class="checkbox">
                  <label class="checkbox-label">
                    <input type="checkbox" :checked="isAllSelected" @click="toggleAll">
                    <div class="checkbox-text"/>
                  </label>
                </div>
              </th>
              <template
                  v-for="header in props.headers">
                <th :class="header.value"
                    :key="header.title"
                    :ref="header.value"
                    v-show="header.is_visible"
                >
                  <div class="th-block">
                    <span v-if="header.value === 'date'" class="header-title">{{ header.title }}</span>
                    <span v-else class="header-title">{{ header.title }}</span>
                    <span class="sortable-icon" v-if="header.is_sortable">
                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g opacity="0.5">
                      <path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"/>
                      <path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"/>
                      </g>
                    </svg>
                  </span>
                  </div>
                </th>
              </template>
            </tr>
            </thead>
          </template>
          <template v-slot:body="{items}">
            <tbody>
            <tr v-for="val in items[0]"
                :key="val.id">
              <td class="td-checkbox">
                <div class="checkbox">
                  <label class="checkbox-label">
                    <input
                        :checked="selectedItems.find(item => item.id === val.id)"
                        type="checkbox"
                        @change="selectItem(val.id)"
                    >
                    <span class="checkbox-text"></span>
                  </label>
                </div>
              </td>
              <td v-for="(value, key) in items[1]"
                  :key="key"
                  :ref="key">
                <span v-if="value.value === 'date'">{{ val.cells[value.value] | formatDate }} </span>
                <span v-else>{{ val.cells[value.value] }}</span>
              </td>
            </tr>

            </tbody>
          </template>
        </v-data-table>
      </div>
    </div>
    <change-price-modal
        v-if="isOpenChangePriceModal"
        @closeModal="onCloseChangePriceModal"
        @save="onSavePriceChanges"

        :data="editedPrice"
        :is-open="isOpenChangePriceModal"/>
    <change-price-warning-modal
        @continue="onContinueUpdatePrice"
        @closeModal="onCloseChangePriceWarningModal"

        :is-open="isOpenChangePriceWarningModal"/>
  </div>
</template>

<script>
import {TABLE_ACTIONS} from "@/constants/constants";
import httpClient from "../../../../../../../services/http-client/http-client";
import IconWithTooltip from "@/components/ui/IconWithTooltip";
import ChangePriceWarningModal from "@/components/dashboard/products/nomenclature/modals/ChangePriceWarningModal";
import ChangePriceModal from "@/components/dashboard/products/nomenclature/modals/ChangePriceModal";
import moment from "moment";
// import nomenclature from "@/store/modules/products/nomenclature";

export default {
  name: "PricesHistory",
  components: {ChangePriceModal, ChangePriceWarningModal, IconWithTooltip},
  data() {
    return {
      search: '',
      items: null,
      selectedItems: [],
      sortBy: null,
      sortDesc: null,
      loading: false,
      isOpenChangePriceWarningModal: false,
      isOpenChangePriceModal: false,
      editedPrice: null,
      page: 1

    }
  },
  computed: {
    isCreate() {
      return this.mode === TABLE_ACTIONS.CREATE
    },
    isUpdate() {
      return this.mode === TABLE_ACTIONS.UPDATE
    },
    formatHeaders() {
      if (this.items) {
        return this.items.headers.filter(item => item.is_visible).sort((a, b) => a.order - b.order)
      }
      return []
    },
    isAllSelected() {
      if (this.items.body) {
        return this.selectedItems.length === this.items.body.length
      }
      return false
    }
  },

  methods: {
    onCloseChangePriceWarningModal() {
      this.isOpenChangePriceWarningModal = false
    },
    formatDate(value) {
      const newValue = moment(value).format('DD.MM.YYYY')
      return newValue.split('/').join('.')
    },
    onCloseChangePriceModal() {
      this.isOpenChangePriceModal = false
    },
    async onSavePriceChanges(data) {
      const url = '/products/nomenclatures/change-price-nomenclature-history'
      const item = this.items.body.find(item => item.id === data.id)
      const alertParams = {
        textItems: [
          {text: 'prices_history.first_part', style: 'normal', translate: true},
          {text: item.cells.title_price, style: 'normal', translate: false},
          {text: 'prices_history.second_part', style: 'normal', translate: true},
          {text: this.formatDate(item.cells.date), style: 'normal', translate: false},

        ],
        link: false
      }

      await httpClient.put(url, data)
      console.log(item.cells);
      item.cells.price = data.value
      this.$emit('show-alert', alertParams)
      this.isOpenChangePriceModal = false
    },
    onClickChangeRow() {
      this.isOpenChangePriceWarningModal = true
      this.editedPrice = this.selectedItems[0]
    },
    onContinueUpdatePrice() {
      this.isOpenChangePriceWarningModal = false
      this.isOpenChangePriceModal = true
    },
    async onClickDelRow() {
      const url = '/products/prices/nomenclatures/deletePrices'
      await httpClient.post(url, {data: this.selectedItems})
      this.items.body = this.items.body.filter(item => !this.selectedItems.find(nomenclature => nomenclature.id === item.id))
      this.selectedItems = []
    },
    selectItem(id) {
      const index = this.selectedItems.findIndex(price => price.id === id)
      if (index === -1) {
        this.selectedItems.push({id})
      } else {
        this.selectedItems.splice(index, 1)
      }

    },
    toggleAll() {
      if (this.isAllSelected) {
        this.selectedItems = []
      } else {
        this.selectedItems = this.items.body.map(item => ({id: item.id}))
      }

    },
    async onScrollToEnd() {
      if (this.items.total_page > this.page) {
        this.page += 1
        const url = `products/prices/get-nomenclature-price/${this.form.id}?page=${this.page}`
        try {
          const items = await httpClient.get(url)
          this.items.body.push(...items.data.data)
          this.addScrollEvent()

        } catch (e) {
          console.log(e);
        }
      }
    },
    addScrollEvent() {
      // const table = document.querySelector('.prices')
      const table = document.querySelector('.prices .table-responsive')
      console.log(table);
      table.addEventListener('scroll', () => {
            console.log('scroll');
            if (table.scrollTop + table.clientHeight >= table.scrollHeight) {
              this.onScrollToEnd()
            }
          }
      );
    },
  },
  props: {form: Object, mode: String},
  async mounted() {
    const url = `products/prices/get-nomenclature-price/${!this.isCreate ? this.form.id : 'empty'}`
    try {
      const items = await httpClient.get(url)
      this.items = items.data.data
      this.addScrollEvent()

    } catch (e) {
      console.log(e);
    }
  }
}

</script>


<style scoped lang="scss">
.table-responsive {
  max-height: 460px;
}

th {
  position: sticky;
}

.transparent-btn {
  min-height: 36px;
  width: 100%;
  max-width: 160px;
  font-weight: bold;
  font-size: 13px;
  line-height: 13px;
  color: #9DCCFF;
  border: solid 1px #9DCCFF;
  border-radius: 32px;

  &:hover {
    background-color: #9DCCFF;
    color: #fff;
  }

  margin-left: 10px;
  margin-right: 10px;
}

.td-checkbox {
  width: 25px;
  padding-right: 20px !important;
}

.table-wrapper {
  position: relative;
  padding-top: 50px;
  max-height: 400px;

  thead {
    tr {
      position: absolute;
      top: 0;
      right: 0;
      left: 0;
    }
  }
}

.accompanying {
  .table-wrapper {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;
    min-height: 380px;
    overflow: hidden;
  }

  padding: 0 30px;

  &-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;

    .label-title {
      width: auto;
      white-space: nowrap;
    }

    &-right {
      width: 100%;
      display: flex;
      justify-content: flex-end;
    }

    .search-block {
      max-width: 392px;

      input {
        border: 1px solid #9DCCFF;
      }
    }


  }
}

</style>
<style slang="scss">
.prices {

.table-layout-fixed {
  position: relative;

}

}
.table-layout-fixed {

table {
  table-layout: fixed;
}

}
</style>