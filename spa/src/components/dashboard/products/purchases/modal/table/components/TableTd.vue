<template>
  <td>
    <v-select
        v-if="isSelect"
        class="unitsSelect"
        :class="{'disabled': !isPacking}"
        height="32"
        item-value="id"
        item-text="title"
        :value="companyValue"
        :menu-props="{contentClass: 'unitsSelectDropDown'}"
        return-object
        :items="getUnits"
        @change="onSelect"
        :disabled="!isPacking"
    ></v-select>
    <div class="count" v-else-if="isCount">
      <numeric-input customClass="stockInput" :min="0" :max="999000" isInputNumber @input="onInput" :value="count" reverse></numeric-input>
      <span v-if="packing">{{ amountOfPack }} {{ typeOfPack }}</span>
    </div>
    <div v-else-if="isAmount">
        {{fullPrice}}
    </div>
    <span v-else>{{ title }}</span>
  </td>
</template>

<script>
// component
import NumericInput from '@/components/ui/NumericInput/NumericInput';
// constant
import {TYPE_OF_PACK} from "@/constants/constants";

export default {
  name: "TableTd",
  components: {
    'numeric-input': NumericInput
  },
  props: {
    isSelect: Boolean,
    isCount: Boolean,
    isAmount: Boolean,
    isPrice: Boolean,
    units: Array,
    packing: String,
    typeOfPack: String,
    unit: String,
    countType: Number,
    title: [String, Number],
    dataValue: Object,
    fullPrice: [String, Number],
    id: Number,
  },
  computed: {
    isPacking() {
      return !!this.packing;
    },
    getUnits() {
      if(!this.packing) {
        return this.units.filter(item => item.id !== 4)
      } else {
        return this.units.filter(item => item.id === 4 || item.title === this.unit);
      }
    },
  },
  watch: {
    typeOfPack(val, oldVal) {
      if (val !== oldVal) {
        let fullPrice = null;
        if (this.packing && this.isCount) {
          const formatCount = Number.isNaN(this.countType) ? 0 : this.countType;
          const isPack = this.typeOfPack === TYPE_OF_PACK.PACK;

          if (isPack) {
            this.amountOfPack = (this.countType/this.packing).toFixed(1);

            fullPrice = (this.price*formatCount);
          } else {
            this.amountOfPack = this.countType*this.packing;

            fullPrice = this.price*(formatCount*this.packing);
          }

          const amount = {
            price: fullPrice,
            count: formatCount,
            isPack,
            id: this.id
          }

          this.$emit('updateCountValue', amount)
        }
      }
    }
  },
  data: () => ({
    price: null,
    count: 0,
    companyValue: null,
    amountOfPack: 0,
    isInputPrice: false
  }),
  methods: {
    onInput(value, point) {
      if (point) {
        this.count = value;
      } else {
        this.price = value;
      }

      console.log(this.count);
      console.log(this.price);
      // this.countPack();
    },
    onHover(value) {
      this.isInputPrice = value
    },
  },
  destroyed() {
    this.onInput();
  }
}
</script>

<style scoped lang="scss">
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