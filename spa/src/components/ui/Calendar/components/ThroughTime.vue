<template>
  <v-row class="throughTime generalStyleRadio">
    <v-radio-group
        v-model="row"
        @change="onHandler"
        dense
        row
    >
      <template  v-for="(item, idx) in throughTimeObj">
        <v-radio
            :key="item"
            :label="item"
            :value="idx + 1"
        ></v-radio>
      </template>
    </v-radio-group>
  </v-row>
</template>

<script>
export default {
  name: "ThroughTime",
  props: {
    stateOfDate: [String, Number],
    throughTimeObj: Array
  },
  watch: {
    stateOfDate(val, oldVal) {
      if (val !== oldVal) {
        this.row = null;
      }
    }
  },
  data: () => ({
    row: null
  }),
  methods: {
    onHandler() {
      this.$emit('updateValue', this.row);
    }
  }
}
</script>

<style scoped lang="scss">
.throughTime {
  border-top: 1px solid #E3F0FF;
  max-width: 304px;
  margin: 0 auto;
}
</style>
<style lang="scss">
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
</style>