<template>
  <div class="calculator">
    <div class="calculator__display">
      <textarea
          :style="checkFontSize"
          name="numbers"
          v-model="output"
          v-if="!checkResult"
      >
      </textarea>
      <textarea
          v-else
          :style="checkFontSize"
          name="numbers"
          v-model="calculate"
      >
      </textarea>
    </div>
    <div class="calculator__buttons-container">
      <button
          v-for="(value, index) in listButtons"
          :key="index"
          @click="onClickButton"
          @keydown="onKeypressButton"
          class="calculator__button"
      >
          <span :data-value="value" v-if="value !== 'CE'">{{ value }}</span>
          <span :data-value="value" v-else class="btn-backspace"></span>
      </button>
    </div>
  </div>
</template>
<script>
// import ClickOutside from 'vue-click-outside'
// import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import { evaluate } from 'mathjs'

export default {
  name: 'calculator',
  props: {
    isOpenCalculator: Boolean
  },
  components: {
    // SvgSprite,
  },
  data() {
    return {
      visible: false,
      value: '',
      message: '',
      equation: '',
      calculate: 0,
      output: 0,

    }
  },
  computed: {
    listButtons() {
      return 'AC CE % / 7 8 9 * 4 5 6 - 1 2 3 + 0 . ='.split(' ');
    },
    checkFontSize() {
      if(this.output.length >= 9) {
        return 'font-size: 17px'
      }
      return ''
    },
    checkResult() {
      const output = this.output
      if(typeof output === 'number') {
        return true
      } else {
        return false
      }
    },
  },
  methods: {
    toggleClick() {
      this.visible = !this.visible
    },

    onClickButton(e) {
      if (this.output == '0' && e.target.dataset.value !== '.' && e.target.dataset.value !== '*' && e.target.dataset.value !== '/' && e.target.dataset.value !== '%' && e.target.dataset.value !== '-' && e.target.dataset.value !== '+') {
        this.output = ''
        // this.calc(e.target.dataset.value)
      }
      this.calc(e.target.dataset.value)
    },
    onKeypressButton(e) {
      if ((e.key).match(/[0-9%/*\-+/(/)=]|Backspace|Enter/)) {
        this.calc(e.key)
      }
    },
    calc(value) {
      console.log('value', value)
      // this.output = this.replaceAllDuplicateChars(this.output)
      if (value.match(/=|Enter/)) {

        try {
          if (this.output !== '') {
            this.output = this.parseStrPercent(this.output)
            this.output = evaluate(this.output)
            this.calculate = new Intl.NumberFormat('ru-RU', { maximumFractionDigits: 15 }).format(this.output).replace(',', '.')
          }
        } catch {
          let oldValue = this.output
          let newValue = 'недопустимое выражение'

          this.output = newValue
          const timer = setTimeout(() => {
            this.output = oldValue
            clearTimeout(timer)
          }, 1500)
        }
      } else if (value === 'AC') {
        this.output = '0'
      } else if (value.match(/CE|Backspace/)) {
        if(typeof this.output === 'number' || this.output === '0') this.output = '0'
        if(this.output.length && this.output !== '0') {
          this.output = this.output.substring(0, this.output.length - 1)
        }
      }else if (value === '%') {
        this.output += value
        this.output = this.replaceAllDuplicateChars(this.output)
        const res = this.parseStrPercent(this.replaceAllDuplicateChars(this.output))
        this.calculate = res
        this.output = res
      } else {
        this.output += value
        this.output = this.replaceAllDuplicateChars(this.output)
        this.calculate = new Intl.NumberFormat('ru-RU', { maximumFractionDigits: 15 }).format(this.output).replace(',', '.')
      }

    },
    parseStrPercent(str) {
      return this.checkIndexValue(str, ['.',',','+','*','-','/'])
    },
    checkIndexValue(str, target) {
      let oldStr = str,
          newStr = str,
          searchDigits = this.checkIndexValueNumber(str);

      for(let i = 1; i < target.length; i++){
        newStr = newStr.split(target[i]).join(' ');
      }
      newStr = newStr.split(' ');
      let strNew = newStr.filter(item => item.includes("%")).map(item => {
        item = item.slice(0, -1)

        item = (searchDigits * item) / 100
        return item
      })

      let text = oldStr
      strNew.forEach(item => {
        text = text.replace(/\d+(%|\s\bpercent\b)/, item)
      })

      return text
    },
    checkIndexValueNumber(str) {
      let test1 = ['+','*','-','/']

      for(let i = 0; i <= test1.length; i++) {
        let test = str.split(test1[i]);
        if(typeof test[1] != 'undefined'){
          return test[0];
        }
      }
    },
    replaceAllDuplicateChars(str) {
      let res = str
      let matchRes = str.match(/\W{2}/g)
      if(matchRes) res = str.replace(/\W{2}/g, matchRes[0].charAt(1))
      return res
    },
    closeCalculator() {
      this.$emit('close')
    }
  },
  mounted() {
    // this.$refs.field.focus()
    // console.log(this.parseStrPercent('5+6%*3+19%*10%'))
    // console.log(this.replaceAllDuplicateChars('33+++333///333*333---333-66'))
  },
  directives: {
    // ClickOutside
  }
}
</script>

<style lang="scss">

.calculator {
  position: absolute;
  bottom: 0;
  left: 0;
  max-width: 294px;
  min-height: 378px;
  width: 100%;
  background-color: #5893D4;
  border-radius: 10px;
  padding: 20px;

  &__display {
    margin: 0 0 15px;

    textarea {
      width: 100%;
      height: 102px;
      background-color: #fff;
      border-radius: 7px;
      outline: none;
      font-size: 39px;
      color: #5893D4;
      padding: 10px 31px;
      overflow: hidden;
      text-align: right;
      line-height: 120px;
      pointer-events: none;
    }
  }

  &__buttons-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
  }

  &__button {
    font-size: 23px;
    font-weight: 300;
    padding: 4px;
    border-right: 1px solid rgba(#9DCCFF, .5);
    border-bottom: 1px solid rgba(#9DCCFF, .5);
    position: relative;

    &:focus {
      background-color: #ff9f2f;
    }

    span {
      display: grid;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100%;
    }

    &:nth-child(17) {
      border-right: 1px solid rgba(#9DCCFF, .5);
      border-bottom: 0;
      justify-content: flex-start;
      grid-column: span 2;
      padding: 4px 4px 4px 24px;

      span {
        justify-content: flex-start;
      }
    }

    &:nth-child(18),
    &:nth-child(19) {
      border-bottom: 0;
    }
    &:nth-child(4),
    &:nth-child(8),
    &:nth-child(12),
    &:nth-child(16),
    &:nth-child(19) {
      border-right: 0;
      font-weight: 500;
    }
  }

  .btn-backspace {
    &:after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-repeat: no-repeat;
      background-size: cover;
      background-position: 50% 50%;
      background-image: url("../../../assets/icons/icon-backspace.svg");
      width: 20px;
      height: 14px;
    }
  }
}
</style>
