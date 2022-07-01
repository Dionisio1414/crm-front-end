<template>
  <v-btn
      class="customBtn"
      :class="customClass"
      :color="color"
      @click="onHandler"
      v-bind="attrs"
      v-on="on"
      :text="text"
      :disabled="isDisabled"
      dark
  >
    <svg-sprite v-if="iconOption" :width="iconOption.w" :height="iconOption.h" :icon-id="iconOption.iconId"></svg-sprite>
    {{ title }}
  </v-btn>
</template>

<script>
export default {
  name: "CustomBtn",
  props: {
    title: String,
    color: String,
    customClass: String,
    text: Boolean,
    attrs: Object,
    on: Object,
    isDisabled: Boolean,
    iconOption: Object
  },
  methods: {
    onHandler() {
      this.$emit('updateEvent')
    }
  }
}
</script>

<style scoped lang="scss">
  .icon svg {
    margin-right: 15px;
  }

  .customBtn {
    max-width: 190px;
    width: 100%;
    border-radius: 50px;
    font-size: 13px;
    text-transform: none;
    letter-spacing: normal;
    height: 40px !important;
    margin: 0 15px;
    box-shadow: none;
    transition: background .3s ease;

    &.w-156 {
      max-width: 156px;
    }

    &.reduceHeight {
      &--40 {
        height: 40px !important;
      }

      &--36 {
        height: 36px !important;
      }
    }

    &.b-radius {
      &--10 {
        border-radius: 10px;
      }
    }

    &:before {
      transition: all .3s ease !important;
    }

    &.orangeBtn {
      transition: background-color .3s ease !important;

      &:before {
        display: none !important;
      }
    }

    &.cancel {
      border: 1px solid #5893D4;
    }

    &.save {
      background: #5893D4 !important;
      color: #fff !important;

      &:hover {
        background: #FF951A !important;
        opacity: 1;
      }
    }

    &.save.theme--dark.v-btn.v-btn--disabled {
      background-color: rgba(255, 255, 255, 0.12) !important;
      color: #9DCCFF !important;
    }

    &.smallText {
      font-weight: bold;
      font-size: 13px;
      height: 40px;
      max-width: 196px;
    }

    &.choice {
      border-radius: 20px;
      width: 100%;
      max-width: 170px;
      font-size: 13px !important;
      font-weight: bold;
      text-transform: none;
      height: 36px !important;
      padding: 0 16px;
      margin: 0;

      &:hover:before {
        background: #FF951A;
        opacity: 1;
      }
    }

    &.table-btn {
      @extend .choice;
      max-width: 160px;
      margin-left: 10px;
    }

    &.orangeBtn:hover {
      background-color: #FF951A !important;
      opacity: 1;
    }

    &.secondary {

    }


    &.theme--dark.v-btn.v-btn--disabled:not(.v-btn--flat):not(.v-btn--text):not(.v-btn--outlined) {
      background-color: rgba(88, 147, 212, .3) !important;
      color: #fff !important;

      &.orangeBtn {
        background-color: #FF951A !important;
        opacity: .6;
      }
    }
  }
</style>
