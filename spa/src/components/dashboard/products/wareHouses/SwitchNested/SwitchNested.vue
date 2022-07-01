<template>
  <div
      class="switch-nesting"
      :class="customClass"
  >
    <switch-text
        :title="firstTitle"
        :active="!switchNesting"
    ></switch-text>
    <v-switch color="#4ECA80" @change="onHandler" v-model="switchNesting" inset></v-switch>
    <switch-text
        :title="secondTitle"
        :active="switchNesting"
    ></switch-text>
  </div>
</template>

<script>
import SwitchText from "./components/SwitchText";
export default {
  name: "SwitchNested",
  components: {
    'switch-text': SwitchText
  },
  props:{
    firstTitle: String,
    secondTitle: String,
    customClass: String
  },
  data() {
    return {
      switchNesting: false,
    }
  },
  methods: {
    onHandler() {
      this.$emit('updateSwitch', this.switchNesting)
    }
  }
}
</script>

<style lang="scss">
  .switch-nesting {
    padding: 5px 20px 5px 0;
  .v-input--switch__track {
    border: 1px solid rgb(157, 204, 255);
    box-sizing: border-box;
    border-radius: 50px;
    background: none;
  }

  .v-input--selection-controls:hover .v-input--switch__thumb {
    box-shadow: 0 0 2px 3px #5893D4 !important;
  }

  .v-input--switch__thumb {
    background: rgb(157, 204, 255);
  }

  .v-input--selection-controls__ripple {
    display: none !important;
  }
}
</style>