<template>
  <v-menu
      content-class="calendarWrapper"
      v-model="datePicker
"
      :close-on-content-click="false"
      transition="scale-transition"
      offset-y
      max-width="364px"
      min-width="364px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
          v-model="date"
          hint="DD.MM.YYYY format"
          :return-value.sync="date"
          v-bind="attrs"
          v-on="on"
      ></v-text-field>
    </template>
    <v-date-picker
        v-model="date"
        @change="onChange"
        first-day-of-week="1"
        color="#FF9F2F"
        locale="ru"
        width="304px"
        no-title
    ></v-date-picker>
  </v-menu>
</template>

<script>
export default {
  name: "DatePicker",
  props: {
    model: String
  },
  data() {
    return {
      datePicker: false,
      date: new Date().toISOString().substr(0, 10),
    }
  },
  methods : {
    onChange () {

    }
  }
}
</script>

<style lang="scss">
.custom-date-picker {
  position: relative;
  .v-picker .v-card .v-picker--date .theme--light {
    position: static !important;
    min-width: inherit;
  }
}
</style>