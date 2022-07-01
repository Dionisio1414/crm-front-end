<template>
  <div class="table-wrapper tablePrice" :class="customClass">
    <div class="table-responsive">
      <v-data-table
          :headers="formatHeader"
          :items="[body, formatHeader]"
          :sort-desc.sync="sortDesc"
          :sort-by.sync="sortBy"
          :items-per-page="10"
          hide-default-header
          hide-default-footer
          sortable
          ref="table"
          class="table"
      >
        <template v-slot:header="{props}">
          <thead>
           <tr>
            <th v-if="isCheckItem">
              <div class="checkbox">
                <label class="checkbox-label">
                  <input
                      type="checkbox"
                      @click="toggleAll"
                      v-model="selectedAll"
                  >
                  <span class="checkbox-text"/>
                </label>
              </div>
            </th>
            <template
                v-for="header in props.headers"
            >
              <th
                  :key="header.title"
                  :ref="header.value"
                  v-show="header.is_visible"
                  @click.prevent="toggleOrder($event, header.value)"
              >
                <div class="th-block">
                  <span class="header-title">{{ header.title }}</span>
                  <span class="sortable-icon" v-if="header.is_sortable">
                    <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
                  </span>
                </div>
              </th>
            </template>
          </tr>
          </thead>
        </template>
        <template v-slot:body="{items}">
          <tbody>
            <price-tr
                v-for="(val) in items[0]" :key="val.id"
                :isPercent="val['margin_percent']"
                :dataVal="val"
                isCheckItem
            ></price-tr>
          </tbody>
        </template>
      </v-data-table>
    </div>
  </div>
</template>

<script>
// vuex
import { mapActions, mapGetters } from "vuex";
// components
import ClickOutside from 'vue-click-outside';
import PriceTr from './PriceTr';

export default {
  name: "PriceTable",
  directives: { ClickOutside },
  components: {
    PriceTr
  },
  props: {
    header: Array,
    body: Array,
    isCheckItem: Boolean,
    isSearchInput: Boolean,
    isCrossDelete: Boolean,
    isStock: Boolean,
    getFullTime: String,
    customClass: String,
    randomId: String,
  },
  computed: {
    ...mapGetters(['getLists', 'searchList', 'loading']),
    getDefaultUnits() {
      return this.units.find(item => item.is_default);
    },
    units() {
      const unitsArray = this.getLists('lists')['units'];
      return [...unitsArray, {id: 4, title: "уп"}]
    },
    computedHeader() {
      return this.header.slice()
    },
    formatHeader() {
      if (this.header) {
        return [...this.header].filter(item => item.is_visible).sort((a, b) => a.order - b.order)
      }

      return []
    },

  },
  watch: {
    isSearchInput(val) {
      if(val) {
        this.scrollToTop();
      }
    },
    body(val) {
      if (val) {
        this.selectedItems = [];
        this.sendDeleteItems(this.selectedItems);

        this.scrollToBottom();
        this.selectedAll = false;
        this.$emit('updateSearchInput');
      }
    }
  },
  data: () => ({
    selectedItems: [],
    selectedAll: false,
    sortDesc: false,
    sortBy: null,
    settingsFlag: false,
    drag: false,
    unitsValue: null,
    amountCount: 0,
    amountSum: 0.00,
    settingsTable: {
      copyHeader: null,
      header: null,
      body: null,
      transformHeaders: null,
    }
  }),
  methods: {
    ...mapActions(['getSelectedProduct']),
    async onChange({ nomenclature_id }) {
      const query = {
        id: nomenclature_id,
        price_id: 1,
        date: this.getFullTime
      }

      await this.getSelectedProduct(query);
    },
    getAmount(array, key) {
      return array.reduce((acc, item) => {
        if (item[key]) {
          acc = acc + item[key];
        }

        return acc;
      },0);
    },
    onAmountCount(amount) {
      const { fullPrice, id, count, isPack, price } = amount;
      const array = [...this.body];

      array.forEach((item) => {
        if (item.id === id) {
          item.fullPrice = parseFloat(fullPrice);
          item.price = price;
          item.count = count;
          item.isPack = isPack;
        }
      });

      const amountSum = this.getAmount(array, 'fullPrice');
      this.amountCount = this.getAmount(array, 'count');

      this.amountSum = parseFloat(amountSum).toFixed(2);
      this.$emit('updateAmountSum', this.amountSum);
      this.$emit('updateAmountCount', this.amountCount);

      const test = array.map(item => item)

      this.$emit('updateItems', test);
    },
    toggleAll() {
      this.selectedItems = [];
      if (!this.selectedAll) {
        this.body.forEach(item => this.selectedItems.push(item.id))
      }

      this.sendDeleteItems(this.selectedItems);
    },
    selectItem(id) {
      const index = this.selectedItems.findIndex((item) => item === id);
      if (index === -1) {
        this.selectedItems.push(id)
      } else {
        this.selectedItems.splice(index, 1)
      }

      this.sendDeleteItems(this.selectedItems);
    },
    toggleOrder(e, column) {
      this.sortDesc = !this.sortDesc
      this.sortBy = column;
    },
    sendDeleteItems(deleteItems) {
      this.$emit('updateGetListIds', deleteItems);
    },
    scrollToBottom() {
      const element = this.$refs.table.$el.querySelector('.v-data-table__wrapper');
      element.scrollTop = element.scrollHeight;
    },
    scrollToTop() {
      const element = this.$refs.table.$el.querySelector('.v-data-table__wrapper');
      element && element.scrollTo({
        top: 0, left: 0, behavior: 'smooth'
      });
    },
    onDelete(idx) {
      this.$emit('updateDelete', idx);
    }
  }
}
</script>
<style lang="scss" scoped>
  .tablePrice {
    width: 100%;
    max-width: 730px;
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;
  }

</style>
<style lang="scss">
.tablePrice .v-data-table__wrapper {
  min-height: 300px;
}

.totalWrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #E3F0FF;
  border-radius: 10px;
  font-weight: bold;
  font-size: 15px;
  line-height: 1.5;
  letter-spacing: 0.03em;
  color: #5893D4;
  padding: 10px 15px;
  margin-top: 10px;

  .count {
    width: 420px;
    display: flex;
    justify-content: space-between;
    padding: 0 15px;
  }

  .text {
    text-transform: uppercase;
  }
}

.firstEl {
  padding: 8px 15px !important;
  font-size: 15px;
  line-height: 15px;
  color: #7E7E7E;
  height: 50px !important;
}

.dots {
  width: 20px;
  padding: 0 5px !important;
}

.tableGoods {
  .table-responsive {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;

    .v-data-table__wrapper {
      max-height: 355px;
    }

  }

  .v-data-table__wrapper table tbody tr.noHover {
    td {
      border-bottom: none !important;
    }

    &:hover{
      background: none !important;
    }
  }

  .v-data-table.table > .v-data-table__wrapper > table > tbody > tr > td {
    padding: 0 30px;
  }

  .count > span {
    white-space: nowrap;
  }
}
</style>