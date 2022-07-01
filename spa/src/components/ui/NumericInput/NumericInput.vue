<template>
  <div class="numericWrap" :class="[{'reverse': reverse}, customClass]">
    <div v-if="isOrders" class="discountCounterBtnGroups">
        <span
            @mousedown="start(decrement)"
            @touchStart="$event.preventDefault(); start(increment)"
            @touchEnd="$event.preventDefault(); stop($event)"
            class="plus countBtn"
        >
          <svg-sprite height="26" width="26" icon-id="tableMinus"></svg-sprite>
        </span>
      <span
          @mousedown="start(increment)"
          @touchStart="$event.preventDefault(); start(increment)"
          @touchEnd="$event.preventDefault(); stop($event)"
          class="minus countBtn"
      >
          <svg-sprite height="26" width="26" icon-id="tablePlus"></svg-sprite>
        </span>
    </div>
    <div v-if="isArrows && !isOrders" class="timeBlockBtn">
      <arrow
          @updateMousedown="start(increment)"
          @updateTouchstart="$event.preventDefault(); start(increment)"
          @updateTouchend="$event.preventDefault(); stop($event)"
          text="mdi-chevron-up"
          ordersBtn
      ></arrow>
      <arrow
          @updateMousedown="start(decrement)"
          @updateTouchstart="$event.preventDefault(); start(increment)"
          @updateTouchend="$event.preventDefault(); stop($event)"
          text="mdi-chevron-down"
          ordersBtn
      ></arrow>
    </div>
    <div class="timeBlockField">
      <input
          :name="name"
          ref="input"
          type="number"
          :value="getFormatValue"
          :placeholder="placeholder"
          :max="max"
          :min="min"
          @input="inputHandler($event.target.value)"
          :readonly="readonly"
      >
    </div>
  </div>
</template>

<script>
//components
import Arrow from "./components/Arrow";
const timeInterval = 50;

export default {
  name: "NumericInput",
  components: {
    'arrow': Arrow
  },
  props: {
    isMinutes: Boolean,
    name: String,
    isArrows: Boolean,
    isOrders: Boolean,
    isInputNumber: Boolean,
    value: Number,
    reverse: Boolean,
    stateOfTime: [String, Number],
    stateTime: [String, Number],
    placeholder: String,
    customClass: String,
    min: {
      type: Number,
      default: -Infinity
    },
    max: {
      type: Number,
      default: Infinity
    },
    step: {
      type: Number,
      default: 1
    },
    precision: {
      type: Number,
      validator(val) {
        return val >= 0 && Number.isInteger(val)
      }
    },
    readonly: {
      type: Boolean,
      default: false
    },
  },
  computed: {
    getFormatValue() {
      if (this.isInputNumber) {
        return this.numericValue
      }

      return this.numericValue < 10 ? `0${this.numericValue}`: this.numericValue;
    }
  },
  data: () => ({
    numericValue: null,
    interval: null,
    startTime: null,
    handler: Function
  }),
  watch: {
    stateOfTime(val, oldVal) {
      if (val !== oldVal) {

        this.numericValue = 0;
      }
    },
    value: {
      immediate: true,
      handler(val) {
        let newValue = 0;
        if (this.isMinutes && (val >= 1 && val <=3)) {
          newValue = 0;
        } else {
          newValue = val;
        }

        if (newValue) {
          newValue = this.toPrecision(newValue, this.precision)
          if (newValue >= this.max) {
            newValue = this.max
          }
          if (newValue <= this.min) {
            newValue = this.min
          }
          if (newValue !== val) {
            this.$emit('input', newValue)
          }
        }
        this.numericValue = newValue
      }
    }
  },
  methods: {
    toNumber(val) {
      let num = parseFloat(val)
      if (isNaN(val) || !isFinite(val)) {
        num = 0
      }
      return num
    },
    toPrecision(val, precision) {
      return precision !== undefined ? parseFloat(val.toFixed(precision)) : val
    },
    increment() {
      this.updateValue(this.toNumber(this.numericValue) + this.step)
    },
    decrement() {
      this.updateValue(this.toNumber(this.numericValue) - this.step)
    },
    inputHandler(val) {
      this.updateValue(this.toNumber(val), val)
    },
    updateValue(val, strVal = null) {
      const oldVal = this.numericValue
      val = this.toPrecision(val, this.precision)
      if (val >= this.max) {
        val = this.max
      }
      if (val <= this.min) {
        val = this.min
      }
      if (val === oldVal) {
        this.$refs.input.value = strVal && val === this.toNumber(strVal) ? strVal : val
        return
      }
      this.numericValue = val
      this.$emit('input', val)
    },
    start(handler) {
      document.addEventListener('mouseup', this.stop)
      this.startTime = new Date()
      this.handler = handler
      clearInterval(this.interval)
      this.interval = setInterval(handler, timeInterval)
    },
    stop(evt) {
      document.removeEventListener(evt.type, this.stop)
      if (new Date() - this.startTime < timeInterval) {
        this.handler()
      }
      clearInterval(this.interval)
      this.interval = null
      this.handler = null
      this.startTime = null
      if (this.value !== this.numericValue) this.$emit('change', this.numericValue)
    },
  },
  beforeDestroy() {
    clearInterval(this.interval)
    this.interval = null
    this.handler = null
    this.startTime = null
  }
}
</script>

<style scoped lang="scss">
.numericWrap.setPriceInput {
  width: 120px;
  margin: 0 10px;

  .timeBlockField {
    width: 100%;

    input {
      height: 32px;
      width: 100%;
      border: 1px solid #E3F0FF;
      box-sizing: border-box;
      border-radius: 100px;
      text-align: left;
      padding: 0 15px;
      line-height: 32px;
    }
  }
}

.numericWrap {
  display: flex;
  justify-content: flex-end;

  &.ordersCountBtn {
    align-items: center;

    .timeBlockField {
      position: relative;

      &:after {
        content: '%';
        position: absolute;
        right: 5px;
        top: 10px;
        font-size: 15px;
        line-height: 15px;
        color: #9DCCFF;
      }
      input {
        width: 50px;
        height: 32px;
        text-align: left;
        padding: 0 5px;
        font-size: 13px;
        line-height: 13px;
        color: #7E7E7E;
        border: 1px solid #E3F0FF;
        border-radius: 100px;
      }
    }
  }

  &.reportCounter {
    .timeBlockField input {
      color: #9DCCFF;
      border-color: #9DCCFF;
    }
  }

  &.reverse {
    flex-direction: row-reverse;
  }

  &.balanceStock [type="number"] {
    color: #E72323;
  }

  &.stockInput {
    align-items: center;

    .timeBlockField input {
      width: 82px;
      height: 32px;
      background: #fff;
    }
  }

  .timeBlockBtn {
    margin: 0 5px;
    display: flex;
    flex-direction: column;

    i:hover {
      background: #E3F0FF;
      cursor: pointer;
      border-radius: 10px;
    }
  }

  .timeBlockField {
    input {
      width: 47px;
      height: 40px;
      border: 1px solid #9DCCFF;
      box-sizing: border-box;
      border-radius: 10px;
      font-size: 15px;
      line-height: 1.5;
      text-align: center;
      color: #5893D4;
      outline: none !important;
    }
  }

  &.priceInput .timeBlockField input {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 100px;
    width: 100px;
    height: 32px;
    color: #5893D4;
    background: #fff;
  }
}

.discountCounterBtnGroups {
  font-size: 0;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  /* display: none; <- Crashes Chrome on hover */
  -webkit-appearance: none;
  margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}
</style>
