<template>
  <v-menu
      :content-class="`calendarWrapper custom-calendar-wrapper ${customWrapperClass} wrapper-${customClass}`"
      v-model="menu"
      :close-on-content-click="isCloseOnContentClick"
      transition="scale-transition"
      offset-y
      max-width="364px"
      min-width="364px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
          :class="[`time custom-calendar-trigger trigger-${customClass}`, {'icon': isIcon}]"
          :value="dateToFormat | dateForDatePicker"
          :placeholder="placeholder"
          :disabled="isDisabled"
          persistent-hint
          readonly
          :clearable="isClear"
          v-bind="attrs"
          @focus="$emit('focus')"
          @input="$emit('blur')"
          v-on="on"
          @click:clear="date = null"
      >
        <template v-if="isIcon" v-slot:append>
          <svg-sprite :style="`color: ${iconColor}`" width="16" height="15" icon-id="calendar"></svg-sprite>
        </template>
      </v-text-field>
    </template>
    <v-date-picker
        v-model="date"
        @change="onChange"
        :allowed-dates="onAllowDates"
        first-day-of-week="1"
        color="#FF9F2F"
        locale="ru"
        width="304px"
        no-title
    >
      <through-time
          v-if="isThroughTime"
          @updateValue="onSetTime"
          :throughTimeObj="throughTimeObj"
          :stateOfDate="date"
      />
      <choose-time
          v-if="isChooseTime"
          @updateHours="onHours"
          @updateMinutes="onMinutes"
          :stateOfTime="stateOfTime"
          :time-is-default="timeIsDefault"
      />
      <v-col v-if="isBottomBtn" class="bottom">
        <button class="reset" @click="onReset">Сбросить</button>
        <button class="save" @click="onHandler">Сохранить</button>
      </v-col>
    </v-date-picker>
  </v-menu>
</template>

<script>
import ThroughTime from './components/ThroughTime';
import ChooseTime from './components/ChooseTime';
import moment from 'moment'

export default {
  name: "Calendar",
  components: {
    'through-time': ThroughTime,
    'choose-time': ChooseTime
  },
  created() {
    this.splitDate()
  },
  props: {
    isIcon: Boolean,
    isClear: Boolean,
    iconColor: {
      type: String,
      default: "rgb(255, 255, 255)"
    },
    isChange: Boolean,
    customWrapperClass: String,
    customClass: String,
    throughTimeObj: Array,
    isThroughTime: Boolean,
    isChooseTime: Boolean,
    isBottomBtn: Boolean,
    isTime: Boolean,
    dateValue: String,
    allowDate: String,
    isAllowDates: Boolean,
    placeholder: String,
    isDisabled: Boolean,
    dateKey: {
      type: String,
      default: null
    },
    isCloseOnContentClick: {
      type: Boolean,
      default: false
    },
    format: {
      type: String,
      default: 'DD.MM.YYYY'
    }
  },
  data: () => ({
    date: null,
    value: '',
    stateOfDate: true,
    menu: false,
    timeIsDefault: true,
    dateIsDefault: true,
    hours: 0,
    minutes: 0
  }),
  computed: {
    dateToFormat() {
      return { value: this.dateValue, format: this.format }
    },
    stateOfTime() {
      return `${this.hours} ${this.minutes}`
    }
  },
  watch: {
    date() {
      if (!this.isBottomBtn) {
        this.emitUpdateDate()
      }
    },
  },
  methods: {
    onAllowDates(value) {
      if (this.isAllowDates) {
        const allowDateFormatted = this.allowDate.split(' ').shift();
        return moment(allowDateFormatted).isSameOrBefore(value)
      }

      return value
    },
    emitUpdateDate() {
      this.menu = false;
      const date = `${this.date} ${this.hours}:${this.minutes}`
      const key = this.dateKey
      const params = key ? {date, key} : date

      this.$emit('updateDate', params);
      this.$emit('blur');

      // if (this.isTime) this.$emit('updateTime', this.defaultTime);
    },
    splitDate() {
      if (this.dateValue) {
        const [date, time] = this.dateValue.split(' ')
        const [hours, minutes] = time.split(':')
        this.date = date
        this.hours = hours
        this.minutes = minutes
      }
    },
    changeStateOfDate() {
      const date = new Date()
      return date.getTime();
    },
    onChange() {
      this.stateOfDate = this.changeStateOfDate();
      if (this.isChange) {
        this.$emit('updateChange', this.value);
        this.value = this.date;
      }
    },
    onHours(value) {
      this.timeIsDefault = false
      this.hours = value < 10 ? `0${value}` : value;
    },
    formattedTime(value) {
      const [hours, minutes] = value.split(':');
      const checkMinutes = minutes < 10 ? `0${minutes}` : minutes;

      return `${hours}:${checkMinutes}`
    },
    onMinutes(value) {
      this.timeIsDefault = false
      this.minutes = value < 10 ? `0${value}` : value;
    },
    onHandler() {
      this.emitUpdateDate()
    },
    onReset() {
      this.splitDate()
      this.timeIsDefault = true
    },
    getFormatDate(key, getKey, value) {
      const date = new Date();
      const currentDay = date[getKey]();
      return new Date(date[key](currentDay + value)).toISOString().substr(0, 10);
    },


    onSetTime(value) {
      switch (value) {
        case 1:
          this.date = this.getFormatDate('setDate', 'getDate', 1);
          break;
        case 2:
          this.date = this.getFormatDate('setDate', 'getDate', 7);
          break;
        case 3:
          this.date = this.getFormatDate('setDate', 'getDate', 2);
          break;
        case 4:
          this.date = this.getFormatDate('setMonth', 'getMonth', 1);
          break;
        default:
          console.log("Нет значений");
      }
    }
  },
}
</script>

<style lang="scss">
.v-input--is-disabled {
  &.custom-calendar-trigger .v-input__slot {
    border-bottom: none;
  }
  input {
    cursor: default !important;
  }
}

.custom-calendar-trigger {
  &.icon.v-text-field .v-input__append-inner {
    margin-top: 0;
  }

  .v-input__slot {
    border-bottom: 1px solid #9DCCFF;
    margin-bottom: 0;

    &:before, &:after {
      content: none !important;
    }
  }

  &.trigger-head-calendar {
    margin-top: 0;
    font-size: 15px;
    width: 120px;
    margin-right: 30px;

    .v-text-field__slot {
      input {
        line-height: 1;
        text-decoration: underline;
      }
    }

    .v-input__slot {

      width: auto;
      border-bottom: none;
    }
  }
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

.time.v-text-field {
  padding-top: 0;

  input {
    padding: 0;
  }
}

.generalStyleRadio {
  .v-input--selection-controls__input {
    display: none !important;
  }

  .v-input--radio-group.v-input--radio-group--row {
    .v-radio {
      margin-right: 0;
      margin-bottom: 10px;
      flex-basis: 50%;

      .v-label {
        flex: 0 1 auto;
        font-size: 13px;
        color: #9DCCFF;
        padding: 5px 15px;
      }

      &.v-item--active {
        .v-label {
          background: #E3F0FF;
          border-radius: 30px;
          color: #5893D4;
        }
      }
    }
  }
}

.calendarWrapper {
  margin-top: 0 !important;

  .theme--light.v-btn.v-btn--disabled {
    color: rgba(0, 0, 0, 0.26) !important;
  }

  &.wrapper-default-calendar {
    background-color: #fff;
    padding-bottom: 20px;
    min-width: 290px !important;

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

.priceDatePicker .v-picker__body {
  padding-bottom: 20px;
}
</style>