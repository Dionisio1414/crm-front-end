<template>
  <v-tooltip bottom>
    <template v-slot:activator="{ on, attrs }">
      <v-btn
          class="btn-icon"
          :class="customClass"
          dark
          v-bind="attrs"
          :disabled="loading"
          @click="onHandler"
          v-on="on"
      >
        <svg-sprite :width="iconOption.width" :height="iconOption.height" :icon-id="iconOption.iconId"></svg-sprite>
      </v-btn>
    </template>
    <span>{{ tooltipText }}</span>
  </v-tooltip>
</template>

<script>
export default {
  name: "TooltipWithIcon",
  props: {
    color: String,
    tooltipText: String,
    customClass: String,
    iconOption: Object,
    loading: Boolean
  },
  methods: {
    onHandler() {
      this.$emit('updateClickValue')
    }
  }
}
</script>

<style scoped lang="scss">
.btn-icon {
  border-radius: 50%;
  width: 36px;
  height: 36px;
  min-width: inherit !important;
  background: #5893D4 !important;
  margin-left: 15px;
  cursor: pointer;
  padding: 0 !important;

  &.theme--dark.v-btn.v-btn--disabled:not(.v-btn--flat):not(.v-btn--text):not(.v-btn--outlined) {
    background-color: rgb(157 204 255) !important;
  }

  &:before {
    display: none;
  }

  &:hover {
    background: #FF951A !important;
  }

  &.delete {
    background: #9DCCFF !important;

    &:hover {
      background: #5893D4 !important;
    }
  }

  &:before {
    font-size: 20px;
  }
}
</style>