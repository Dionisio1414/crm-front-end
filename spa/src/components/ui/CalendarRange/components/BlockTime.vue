<template>
  <div class="blockTimeItem">
    <span class="blockTimeItemTitle">{{ title }}:</span>
    <numeric-input custom-class="reportCounter" @input="onHours" :min="0" :max="23" :value="hours"></numeric-input>
    <span class="dot">:</span>
    <numeric-input custom-class="reportCounter" @input="onMinutes" :min="0" :max="59" :value="minutes"></numeric-input>
  </div>
</template>

<script>
import NumericInput from '@/components/ui/NumericInput/NumericInput';

export default {
  name: "BlockTime",
  components: {
    NumericInput
  },
  props: {
    title: String
  },
  data: () => ({
    hours: 0,
    minutes: 0,
    time: null
  }),
  methods: {
    onHours(hours) {
      this.$emit('updateHours', hours);
    },
    onMinutes(minutes) {
      this.$emit('updateMinutes', minutes);
    }
  }
}
</script>

<style scoped lang="scss">
.blockTimeItem {
  display: flex;
  align-items: center;
  margin: 0 14px;

  .dot {
    display: inline-block;
    margin: 0 5px;
    font-size: 15px;
    line-height: 15px;
    text-align: center;
    color: #5893D4;
  }

  .blockTimeItemTitle {
    font-weight: 500;
    font-size: 15px;
    line-height: 15px;
    color: #9DCCFF;
    margin-right: 10px;
  }
}
</style>