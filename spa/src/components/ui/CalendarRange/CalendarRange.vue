<template>
  <div class="rangeCalendarWrap" :class="{'plus': isPlus}">
    <div v-if="!isPlus" class="rangeTitle">период</div>
    <v-menu
        :absolute="true"
        tag="span"
        :content-class="`calendarWrapper calendarRange ${isDatePickerTop ? 'withTop': ''}`"
        v-model="menu"
        :close-on-content-click="false"
        transition="scale-transition"
        max-width="364px"
        min-width="364px"
        offset-y
    >
      <template v-slot:activator="{ on, attrs }">
        <span
            class="plusIcon"
            v-if="isPlus"
            v-bind="attrs"
            v-on="on"
        >
            <svg-sprite width="12" height="12" icon-id="datePickerPlus"></svg-sprite>
        </span>
          <span
              v-else
              v-bind="attrs"
              v-on="on"
              class="rangeInput"
          >
            <span class="rangeInput_text">{{ computedDateFormatted }}</span>
            <span>
              <svg-sprite width="16" height="15" icon-id="calendar"></svg-sprite>
            </span>
          </span>
      </template>
      <v-date-picker
          v-model="date"
          :events="arrayEvents"
          event-color="green lighten-1"
          @change="onChange"
          @input="onInput"
          first-day-of-week="1"
          color="#FF9F2F"
          locale="ru"
          width="304px"
          range
          no-title
      >
        <div class="rangeTableTop" v-if="isDatePickerTop">
          <span class="rangeTableTop_input">{{ preliminaryData[0] ? preliminaryData[0] : '-' }}</span>
          <span class="rangeTableTop_input">{{ preliminaryData[1] ? preliminaryData[1] : '-' }}</span>
          <button class="reset" @click="onReset">{{$t('report.workTime.reset')}}</button>
        </div>
        <through-time
            v-if="isThroughTime"
            :throughTimeObj="throughTimeObj"
            :stateOfDate="stateOfDate"
            @updateValue="onSetTime"
        ></through-time>
        <div v-if="isBlockTime" class="blockTime">
          <div class="blockTime_title">{{$t('report.workTime.timeAbsence')}}</div>
          <div class="blockTimeWrapper">
            <block-time title="С" @updateHours="onHours" @updateMinutes="onMinutes"></block-time>
            <block-time title="До" @updateHours="onHours" @updateMinutes="onMinutes"></block-time>
          </div>
        </div>
        <type-of-absence
            v-if="isTypeOfAbsence"
            @updateTypeOfAbsence="onTypeOfAbsence"
            :typeOfAbsenceObj="typeOfAbsenceObj"
        ></type-of-absence>
        <v-row>
          <v-col class="bottom">
            <custom-btn
                custom-class="orange reduceHeight--36 b-radius--10"
                :title="$t('report.chose')"
                color="#5893D4"
                @updateEvent="onHandler"
            ></custom-btn>
          </v-col>
        </v-row>
      </v-date-picker>
    </v-menu>
  </div>
</template>

<script>
// components
import ThroughTime from '../Calendar/components/ThroughTime';
import TypeOfAbsence from "./components/TypeOfAbsence";
import BlockTime from "./components/BlockTime";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// helper
import daysInMonth from "@/helper/daysInMonth";
import inverseDate from "@/helper/inverseDate";

export default {
  name: "CalendarRange",
  components: {
    ThroughTime,
    TypeOfAbsence,
    BlockTime,
    CustomBtn
  },
  props: {
    throughTimeObj: Array,
    isThroughTime: Boolean,
    isBlockTime: Boolean,
    isDatePickerTop: Boolean,
    isTypeOfAbsence: Boolean,
    isPlus: Boolean,
    tabIndex: Number,
    typeOfAbsenceObj: Array,
    initValue: Array
  },
  watch: {
    tabIndex(val, oldVal) {
      if (val !== oldVal) {
        this.onReset();
        this.value = [new Date().toISOString().substr(0, 10)]
      }
    },
  },
  data: () => ({
    date: [],
    arrayEvents: null,
    preliminaryData: '',
    value: [new Date().toISOString().substr(0, 10)],
    stateOfDate: null,
    menu: false,
  }),
  computed: {
    computedDateFormatted() {
      const isDotsInStr = this.value.some(item => item.indexOf('.') !== -1);
      const array = isDotsInStr ? this.value : this.formatDate(this.value);

      return array.join(' - ')
    }
  },
  methods: {
    changeStateOfDate() {
      const date = new Date()
      return date.getTime();
    },
    onChange(data) {
      this.preliminaryData = this.formatDate(data).sort();
      this.stateOfDate = this.changeStateOfDate();
    },
    onInput(data) {
      this.arrayEvents = data;
    },
    onHandler() {
      const arrayDate = this.formatDate(this.date).sort();
      if (arrayDate.length) {
        this.value = arrayDate;

        this.$emit('updateDate', arrayDate);
      } else {
        this.value = [new Date().toISOString().substr(0, 10)];
      }
      this.menu = false;
    },
    onReset() {
      this.preliminaryData = '';
      this.date = [];
      this.stateOfDate = this.changeStateOfDate();
      this.value = [new Date().toISOString().substr(0, 10)];
    },
    formatDate(date) {
      return date.map(item => {
        const [year, month, day] = item.split('-')
        return `${day}.${month}.${year}`
      }).sort()
    },
    setDate(date, point) {
      const str = new Date(date.setDate(point)).toLocaleDateString().substr(0, 10);
      return (str.indexOf('.') !== -1) ? inverseDate(str) : str;
    },
    getFormatDate(type, last) {
      const date = new Date();

      if (type) {
        const currentDayOfWeek = date.getDay()-1;
        const currentDay = last ? date.getDate() - 7 : date.getDate();

        const firstDayOfLastWeek = (currentDay - currentDayOfWeek);
        const lastDayOfLastWeek = (currentDay - currentDayOfWeek) + 6;

        return [this.setDate(date, firstDayOfLastWeek), this.setDate(date, lastDayOfLastWeek)]
      } else {
        if (last) {
          const previousDate = new Date(new Date().getFullYear(),new Date().getMonth()-1, new Date().getDate())
          const lastDayOfPreviousMonth = daysInMonth(1);

          return [this.setDate(previousDate, 1), this.setDate(previousDate, lastDayOfPreviousMonth)]
        } else {
          const lastDayOfPreviousMonth = daysInMonth();

          return [this.setDate(date, 1), this.setDate(date, lastDayOfPreviousMonth)]
        }
      }

    },
    async onSetTime(value) {
      this.date = [];
      switch (value) {
        case 1:
          this.date = this.arrayEvents = this.preliminaryData = await this.getFormatDate(1);
          break;
        case 2:
          this.date = this.arrayEvents = this.preliminaryData = await this.getFormatDate(1,1);
          break;
        case 3:
          this.date = this.arrayEvents = this.preliminaryData = await this.getFormatDate(0,0);
          break;
        case 4:
          this.date = this.arrayEvents = this.preliminaryData = await this.getFormatDate(0,1);
          break;
        default:
          console.log("Нет значений");
      }
    },
    onTypeOfAbsence(value) {
      console.log(value)
    },
    onHours(hours) {
      console.log(hours)
    },
    onMinutes(minutes) {
      console.log(minutes)
    }
  },
  created() {
  },
  mounted() {
    console.log('mounted date', this.initValue)
    this.value = this.initValue
    // this.$emit('updateDate', this.value)
  }
}
</script>
<style scoped lang="scss">
.blockTime {
  width: calc(100% + 16px);
  display: flex;
  justify-content: center;
  flex-direction: column;
  border-top: 1px solid #E3F0FF;
  border-bottom: 1px solid #E3F0FF;
  height: 110px;
  margin: 0 -8px;

  &_title {
    font-size: 16px;
    line-height: 16px;
    color: rgba(88, 147, 212, .5);
    margin: 0 0 15px 30px;
  }

  .blockTimeWrapper {
    display: flex;
    align-items: center;
    justify-content: center;
  }
}

.rangeCalendarWrap {
  display: flex;
  align-items: center;

  &.plus {
    position: absolute;
    right: 10px;
    top: 0;
    bottom: 0;
    width: 30px;

    .plusIcon {
      color: #9DCCFF;
      transition: color .3s ease;
      position: absolute;
      right: 0;
      left: 0;
      top: 0;
      bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    &:hover .plusIcon {
      color: #5893D4;
    }
  }
}

.rangeTableTop {
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: space-around;
  top: 20px;
  left: 20px;
  right: 20px;
  height: 36px;

  &_input {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #9DCCFF;
    box-sizing: border-box;
    border-radius: 10px;
    width: 97px;
    height: 36px;
    font-size: 13px;
    line-height: 13px;
    color: #5893D4;
  }
}

.rangeTitle {
  font-weight: bold;
  font-size: 13px;
  line-height: 13px;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #317CCE;
}

.reset {
  background: #5893D4;
  border-radius: 10px;
  width: 92px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-align: center;
  color: #FFFFFF;
}

.bottom {
  text-align: center;

  .reset, .save {
    width: 100%;
    color: #FFFFFF;
    border-radius: 10px;
    font-weight: 550;
    font-size: 13px;
    text-align: center;
    margin: 0 10px;
    padding: 8px 10px;

    &:hover {
      opacity: .8;
    }
  }

  .reset {
    background: #5893D4;
    max-width: 92px;
  }

  .save {
    max-width: 192px;
    background: #FF9F2F;
  }
}
</style>
<style lang="scss">
.typeOfAbsence {
  &Group {
    .v-input--selection-controls__input .v-icon {
      color: #4ECA80;
    }
    .v-radio {
      width: 100%;
      text-transform: uppercase;

      .v-label {
        font-weight: 500;
        font-size: 13px;
        line-height: 13px;
        color: inherit;
      }
    }
  }
}

.time.v-text-field {
  padding-top: 0;
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-align: right;
  color: #5893D4;

  input {
    padding: 0;
  }
}

.rangeInput {
  display: flex;
  align-items: center;
  padding: 0 0 0 20px;
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-align: right;
  text-transform: uppercase;
  color: #5893D4;

  &_text {
    display: inline-block;
    margin-right: 15px;
  }
}

.calendarRange {
  margin-top: 0 !important;
  border: none !important;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  border-radius: 10px;

  .v-date-picker-table__events {
    display: none;
  }

  .v-btn--active {
    background-color: inherit !important;
    border-color: inherit !important;
    font-weight: bold;
    font-size: 13px;
    text-align: center;
    color: #FFFFFF;
    position: relative;

    &:before {
      display: none;
    }

    .v-btn__content {
      position: static;

      &:before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: rgb(255, 159, 47);
        border-color: rgb(255, 159, 47);
        border-radius: 50%;
        height: 100%;
        z-index: -1;
      }

      &:only-child {
        &:before {
          content: '';
          height: 26px;
          top: 3px;
          left: -15px;
          right: -15px;
          border-radius: 0;
          background: #9DCCFF;
          z-index: -2;
        }
      }
    }
  }

  &.withTop {
    .v-picker__body {
      padding-top: calc(40px + 36px);
    }
  }

  .v-date-picker-table--date td {
    padding-top: 5px;
  }

  .v-date-picker-table {
    height: inherit;
  }

  .v-card__actions {
    flex-wrap: wrap;
  }

  .v-picker.v-card.v-picker--date.theme--light {
    position: static !important;
    min-width: inherit;
  }

  .v-date-picker-table table {
    border-collapse: collapse;

    thead th {
      border-bottom: 1px solid #E3F0FF;
    }
  }

  .v-picker__body.theme--light .theme--light.v-btn, .v-picker__body.theme--light .theme--light.v-date-picker-table th {
    color: #5893D4;
  }

  .v-picker__body.theme--light .theme--light.v-btn.v-btn--active {
    color: #fff;
  }

  .v-picker__body.theme--light .theme--light.v-date-picker-header .v-date-picker-header__value:not(.v-date-picker-header__value--disabled) button:not(:hover):not(:focus) {
    color: #5893D4;
  }

  .theme--light.v-picker__body {
    background-color: #fff;
  }
}
</style>