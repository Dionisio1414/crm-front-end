<template>
  <div class="typeOfAbsence">
    <span class="typeOfAbsenceTitle">
      {{ $t('report.absenceTypeSelect') }}
    </span>
    <div class="typeOfAbsenceBlock">
      <v-radio-group
          class="typeOfAbsenceGroup"
          v-model="radioValue"
          @change="onHandler"
          hide-details
          dense
          row
      >
        <template  v-for="(item, idx) in typeOfAbsenceObj">
          <v-radio
              :style="'color:' + item.color"
              color="#4ECA80"
              :key="item.title"
              :label="item.title"
              :value="idx + 1"
          ></v-radio>
        </template>
      </v-radio-group>
    </div>
  </div>
</template>

<script>
export default {
  name: "TypeOfAbsence",
  props: {
    typeOfAbsenceObj: Array
  },
  data: () => ({
    radioValue: null
  }),
  methods: {
    onHandler(value) {
      this.$emit('updateTypeOfAbsence', value)
    }
  }
}
</script>

<style scoped lang="scss">
.typeOfAbsence {
  margin: 20px 30px 0;
  font-size: 0;

  &Title {
    font-weight: 500;
    font-size: 16px;
    line-height: 16px;
    text-align: center;
    color: #5893D4;
  }
}
</style>