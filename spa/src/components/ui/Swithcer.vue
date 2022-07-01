<template>
  <div class="switcher" :class="{'active' : value}"
       @click.stop="toggleVisible()">
    <div class="switcher-caret"></div>
  </div>
</template>

<script>
export default {
  name: "Switcher",
  props: {
    value: Boolean
  },
  methods: {
    toggleVisible () {
      this.$emit('toggle', {value: !this.value})
    }
  }
}
</script>

<style scoped lang="sass">
  .switcher
    width: 33px
    height: 18px
    border: 1px solid #9DCCFF
    box-sizing: border-box
    border-radius: 50px
    position: relative
    cursor: pointer

    &-caret
      width: 12px
      height: 12px
      background: #9DCCFF
      //background: #4ECA80
      border-radius: 50%
      position: absolute
      left: 5%
      top: 50%
      transform: translateY(-50%)
      transition: all .5s


    &.active
      border: 1px solid #7CE1A4

      .switcher-caret
        transform: translateY(-50%) translateX(16px)
        background: #4ECA80
        border: 1px solid #7CE1A4
</style>
