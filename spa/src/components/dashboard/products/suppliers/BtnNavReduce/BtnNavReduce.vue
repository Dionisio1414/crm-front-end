<template>
  <span class="resize-btn" :class="[customClass, {'isVisible': !isNavSmall}]" @click="onToggle">
    <svg-sprite v-if="!isNavSmall" width="12" height="30" icon-id="reduce"></svg-sprite>
    <svg-sprite v-else width="42" height="44" icon-id="reduceSecond"></svg-sprite>
  </span>
</template>

<script>
export default {
  name: "BtnNavReduce",
  props: {
    isNavSmall: Boolean,
    customClass: String
  },
  methods: {
    onToggle() {
      this.$emit('updateBtnValue', !this.isNavSmall)
    }
  }
}
</script>

<style scoped lang="scss">
.resize-btn {
  z-index: 1;
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;

  &.listReduced {
    z-index: 10;
    right: inherit !important;
    left: 5px;

    &.isVisible {
      display: none;
    }
  }
}
</style>