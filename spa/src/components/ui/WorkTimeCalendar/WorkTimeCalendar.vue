<template>
  <calendar-range
      :throughTimeObj="throughTimeObj"
      @updateDate="onDate"
      :isPlus="isPlus"
      :tabIndex="tabIndex"
      :typeOfAbsenceObj="getFormattedTypeOfAbsenceObj"
      :isDatePickerTop="isDatePickerTop"
      :isTypeOfAbsence="isTypeOfAbsence"
      :isThroughTime="isThroughTime"
      :isBlockTime="isBlockTime"
      :initValue="initValue"
  ></calendar-range>
</template>

<script>
// constants
import CalendarRange from "@/components/ui/CalendarRange/CalendarRange";
// MOCK data
import throughTimeObj from "@/components/ui/WorkTimeCalendar/throughTimeObj";

export default {
  name: "WorkTimeCalendar",
  components: {
    'calendar-range': CalendarRange
  },
  props: {
    isPlus: Boolean,
    isDatePickerTop: Boolean,
    isTypeOfAbsence: Boolean,
    isBlockTime: Boolean,
    isThroughTime: Boolean,
    tabIndex: Number,
    typeOfAbsenceObj: Array,
    initValue: Array
  },
  computed: {
    getFormattedTypeOfAbsenceObj() {
      return this.typeOfAbsenceObj.filter((item, idx) => idx)
    }
  },
  data: () => ({
    date: null,
    array: null,
    throughTimeObj: throughTimeObj(),
    range: {
      start: null,
      end: null,
    }
  }),
  methods: {
    onDate(date) {
      this.date = date;
      this.$emit('updateDate', date);
    },
  }
}
</script>