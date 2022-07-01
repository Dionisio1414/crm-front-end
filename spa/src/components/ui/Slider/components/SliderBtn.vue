<template>
  <div class="sw-button" :class="'button-' + btnClass">
    <svg-sprite width="23" height="41" :icon-id="svgId"></svg-sprite>
  </div>
</template>

<script>
export default {
  name: "SliderBtn",
  props: {
    btnClass: String,
    svgId: String
  }
}
</script>

<style scoped lang="scss">
.sw-button {
  font-size: 0;
  position: absolute;
  top: 0;
  left: 0;
  width: 23px;
  z-index: 1;
  background: #fff;

  &.swiper-button-disabled {
    opacity: .5;
  }

  &.button-next {
    left: initial;
    right: 0;
  }
}
</style>