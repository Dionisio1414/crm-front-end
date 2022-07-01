<template>
  <tr
      class="tr"
      :class="{'is-group': dataVal['is_groups']}"
  >
    <slot></slot>
    <td v-if="isCheckItem">
      <div class="checkbox" @click.stop="(() => false)">
        <label class="checkbox-label">
          <input
             v-if="!dataVal.is_editable"
             type="checkbox"
             @change="selectItem(dataVal.id, selected)"
             v-model="selected"
          >
          <span class="checkbox-text" :class="{'disabled': !isCheckItem && !dataVal.is_editable}"></span>
        </label>
      </div>
    </td>
    <td>
      <span class="link">{{ dataVal.cells['convert_id'] }}</span>
    </td>
    <td>
      <span class="link">{{ dataVal.cells['short_title'] }}</span>
    </td>
    <td>
      <v-select
          class="unitsSelect"
          :class="{'disabled': !isPacking}"
          :menu-props="{contentClass: 'unitsSelectDropDown'}"
          :value="companyValue"
          :items="getUnits"
          @change="onSelect"
          :disabled="!isPacking"
          item-value="id"
          item-text="title"
          height="32"
          return-object
      ></v-select>
    </td>
    <td>
      <div class="count">
        <numeric-input
            :customClass="`stockInput ${isLimit()}`"
            isArrows
            :min="0"
            :max="isStock ? 999000 : dataVal[isLimitKey]"
            @input="onInput(count, 1)"
            v-model="count"
            isInputNumber
            reverse
        ></numeric-input>
        <span :class="isLimit()" v-if="isPacking">{{ amountOfPack }} {{ typeOfPack }}</span>
        <tool-tip
            v-if="isLimit() && balanceStock"
            custom-class="tool-tip-step"
            :title="balanceStock"
        >
          <svg-sprite width="16" height="16" icon-id="warn"></svg-sprite>
        </tool-tip>
      </div>
    </td>
    <td v-if="isStock">
      <div @mouseenter="onHover(true)" @mouseleave="onHover(false)">
        <numeric-input
            v-if="isInputPrice"
            customClass="priceInput"
            :min="0"
            @input="onInput(price, 0)"
            v-model="price"
            isInputNumber
            reverse
        ></numeric-input>
        <span class="priceText" v-else>{{ price | formatIsNaN }}</span>
      </div>
    </td>
    <td v-if="isStock">
      <div>
        {{fullPrice | formatPrice}}
      </div>
    </td>
    <td v-if="!isStock && !isCrossDelete"></td>
    <td v-if="!isCrossDelete" class="dots"></td>
    <td v-else class="delete" :colspan="isCrossDelete ? 2: 1">
      <span class="deleteBtn" @click="onDelete">
          <svg-sprite style="color: rgb(157,204,255)" width="12" height="12" icon-id="closeWhite"></svg-sprite>
      </span>
    </td>
  </tr>
</template>

<script>
// vuex
import {mapGetters} from "vuex";
// component
import NumericInput from '@/components/ui/NumericInput/NumericInput';
import ToolTip from '@/components/ui/ToolTip/ToolTip';
// constants
import {TYPE_OF_PACK} from "@/constants/constants";

export default {
  name: "TableTr",
  components: {
    'numeric-input': NumericInput,
    ToolTip,
  },
  props: {
    dataVal: Object,
    items: Array,
    units: Array,
    isCheckItem: Boolean,
    selectedAll: Boolean,
    isCrossDelete: Boolean,
    isStock: Boolean,
    body: Array,
    randomId: String
  },
  computed: {
    ...mapGetters(['selectedList', 'list', 'getLists', 'loading']),
    isPacking() {
      return this.dataVal.cells.packing;
    },
    isLimitKey() {
      const isPack = this.typeOfPack === TYPE_OF_PACK.PACK;
      const type = this.isStock ? "balance_base" : "balance_stock";
      return isPack ? type : 'balance_stock_in_package';
    },
    balanceStock() {
      const isPack = this.typeOfPack === TYPE_OF_PACK.PACK;
      const key = !isPack ? 'balance_stock' : 'balance_stock_in_package';
      const sign = isPack ? 'уп' : 'шт';

      return this.dataVal[key] ? `Остаток товара: ${this.dataVal[key]} ${sign}` : null;
    },
    getUnits() {
      if(!this.isPacking) {
        return this.units.filter(item => item.id !== 4)
      } else {
        return this.units.filter(item => item.id === 4 || item.title === this.getUnit || item.title === 'шт');
      }
    },
    getId() {
      return this.dataVal;
    },
    getUnit() {
      return this.dataVal.cells.units;
    }
  },
  watch: {
    selectedAll(val) {
      this.selected = val;
    },
    randomId(val, oldVal) {
      if (val !== oldVal) this.selected = null;
    }
  },
  data: () => ({
    companyValue: null,
    price: null,
    count: 0,
    amountOfPack: 0,
    isInputPrice: false,
    selected: false,
    fullPrice: 0,
    typeOfPack: '',
    countType: 0
  }),
  methods: {
    isLimit() {
     /* if (this.dataVal[this.isLimitKey] < this.count) {
        this.count = this.dataVal[this.isLimitKey];
        this.countPack();
      }*/

      return !this.isStock && (this.dataVal[this.isLimitKey] === this.count) ? 'balanceStock': '';
    },
    selectItem(id) {
      this.$emit('updateSelectItem', id)
    },
    checkPrice(value) {
      const checkPrice = Number.isNaN(value) ? 0 : value;
      return parseFloat(checkPrice).toFixed(2);
    },
    onCountValue(amount) {
      const { fullPrice, count = 0 } = amount;
      this.countType = count;
      this.fullPrice = this.checkPrice(fullPrice);
      this.$emit('updateAmountCount', amount);
    },
    onSelect(value) {
      this.getType(value);

      this.companyValue = value;
      this.countPack();
    },
    getType(data) {
      const { id } = data;
      this.typeOfPack = (id === 4) ? TYPE_OF_PACK.THING: TYPE_OF_PACK.PACK;
    },
    onInput(value, point) {
      if (point) {
        this.count = value;
      } else {
        this.price = value;
      }

      this.countPack();
    },
    countPack() {
      let fullPrice = null;
      const formatCount = Number.isNaN(this.count) ? 0 : this.count;
      const isPack = this.typeOfPack === TYPE_OF_PACK.PACK;

      if (this.isPacking) {
        if (isPack) {
          this.amountOfPack = (formatCount/this.isPacking).toFixed(1);

          fullPrice = (this.price*formatCount);
        } else {
          this.amountOfPack = formatCount*this.isPacking;

          fullPrice = this.price*(formatCount*this.isPacking);
        }
      } else {
        fullPrice = this.price*formatCount;
      }

      const amount = {
        fullPrice: fullPrice,
        count: formatCount,
        price: this.price,
        isPack,
        id: this.getId.id
      }

      this.onCountValue(amount);
    },
    onHover(value) {
      this.isInputPrice = value
    },
    getInitSelectValue() {
      return this.getUnits.find(({title}) => title === this.getUnit);
    },
    setDataItem() {
      const { balance, price, packing, units } = this.getId.cells;

      this.count = (units === TYPE_OF_PACK.PACK) ? (balance/+packing): balance;
      this.price = price;

      this.countPack();
    },
    onDelete() {
      this.$emit('updateDelete')
    }
  },
  mounted() {
    this.companyValue = this.getInitSelectValue();
    this.getType(this.companyValue);
    this.setDataItem();
    this.price = this.dataVal.cells.price || 0;
  },
  destroyed() {
    this.onInput();
  }
}
</script>

<style scoped lang="scss">
  .checkbox-text {
    display: block;
  }

  .delete {
    text-align: right;

    &Btn {
      cursor: pointer;
    }
  }

  .priceText {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100px;
    height: 32px;
  }
  .count {
    display: flex;
    align-items: center;
    input {
      background: #FFFFFF;
      border: 1px solid #E3F0FF;
      box-sizing: border-box;
      border-radius: 100px;
      width: 85px;
      line-height: 32px;
      font-size: 15px;
      text-align: center;
      color: #5893D4;
      outline: none !important;
    }
  }

  .link:hover {
    text-decoration: underline;
    cursor: pointer;
  }

  .tr {
    &:hover {
      background: #F4F9FF !important;
    }
  }

  .balanceStock {
    color: #E72323;
  }
</style>
<style lang="scss">
.unitsSelect {
  width: 74px;
  border: 1px solid #9DCCFF;
  box-sizing: border-box;
  border-radius: 10px;
  padding: 0;
  background: #fff;

  &.disabled {
    border: none;
    background: none;

    .v-input__append-inner {
      opacity: 0;
    }
  }

  .v-input__slot {
    margin-bottom: 0;

    &:before {
      display: none !important;
    }
  }

  .v-select__selections {
    .v-select__selection {
      width: 100%;
      text-align: right;
      font-weight: 600;
      font-size: 12px;
      line-height: 16px;
      text-transform: uppercase;
      color: #5893D4;
    }
    input {
      display: none !important;
    }
  }
}

.unitsSelectDropDown {
  .v-list-item {
    min-height: 30px;
    .v-list-item__content {
      padding: 0 !important;
    }
  }
}
</style>