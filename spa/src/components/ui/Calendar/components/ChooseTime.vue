<template>
  <v-row dense class="chooseTime generalStyleRadio">
    <div class="chooseTimeWrapper">
      <div class="text">
        Время
      </div>
      <div class="timeBlock">
        <numeric-input
            isArrows
            @change="onHours"
            :min="0"
            :max="23"
            readonly
            :value="hours"></numeric-input>
        <span class="divider">:</span>
        <numeric-input
            isMinutes isArrows
            @change="onMinutes" reverse
            :min="0" readonly
            :max="59"
            :value="minutes"
        ></numeric-input>
      </div>
    </div>
    <v-col cols="12">
      <v-radio-group
          v-model="row"
          @change="onChange"
          dense
          row
      >
        <v-radio
            label="Утро 08:00"
            :value="1"
        ></v-radio>
        <v-radio
            label="Обед 12:00"
            :value="2"
        ></v-radio>
        <v-radio
            label="Вечер 16:00"
            :value="3"
        ></v-radio>
      </v-radio-group>
    </v-col>
  </v-row>
</template>

<script>
import NumericInput from '@/components/ui/NumericInput/NumericInput';

export default {
  name: "ChooseTime",
  components: {
    'numeric-input': NumericInput
  },
  props: {
    stateOfTime: [String, Number],
    timeIsDefault: Boolean,
  },
  data: () => ({
    row: null,
    hours: 0,
    minutes: 0,
    status: false,

  }),
  watch: {
    timeIsDefault:function (val) {
      if (val) {

        this.resetTime()
      }
    }
  },
  methods: {
    onChange(value) {
      switch (value) {
        case 1:
          this.hours = 8
          this.minutes = 1;
          this.emitUpdateTime()
          this.$emit('updateHours', 8);
          this.$emit('updateMinutes', 0);
          break;
        case 2:
          this.hours = 12
          this.minutes = 2;
          this.$emit('updateHours', 12);
          this.$emit('updateMinutes', 0);
          this.emitUpdateTime()
          break;
        case 3:
          this.hours = 16
          this.minutes = 3;
          this.emitUpdateTime()
          this.$emit('updateHours', 16);
          this.$emit('updateMinutes', 0);
          break;
        default:
          console.log("Нет значений");
      }
    },
    onHours(value) {
      this.hours = value
      this.row = null;
      this.$emit('updateHours', value);
      this.emitUpdateTime()
    },
    onMinutes(value) {
      this.minutes = value
      this.row = null;
      this.$emit('updateMinutes', value);
      this.emitUpdateTime()
    },
    resetTime() {
      this.minutes = 0
      this.hours = 0
      this.row = null
    },
    emitUpdateTime() {
      const time = [this.hours, this.minutes]
      console.log(time);
      this.$emit('updateTime', time)
    }
  }
}
</script>

<style scoped lang="scss">
.chooseTimeWrapper {
  display: flex;
  align-items: center;
  max-width: 304px;
  width: 100%;
  margin: 20px auto 0;

  .text {
    font-weight: 550;
    font-size: 16px;
    line-height: 16px;
    text-align: center;
    color: #5893D4;
    margin-right: 20px;
  }

  .timeBlock {
    display: flex;
    align-items: center;

    .divider {
      margin: 0 4px;
      color: #5893D4;
    }

    .hours {
      display: flex;
    }
  }
}

.chooseTime {
  border-top: 1px solid #E3F0FF;
  border-bottom: 1px solid #E3F0FF;

  &.generalStyleRadio {
    .v-input--radio-group.v-input--radio-group--row {
      .v-radio {
        flex-basis: 33.333%;
      }
    }
  }
}
</style>