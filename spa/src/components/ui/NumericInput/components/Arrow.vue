<template>
  <v-icon
      class="icon-tooltip"
      @mousedown="onmousedown"
      @touchstart="ontouchstart"
      @touchend="onTouchend"
      color="#9DCCFF"
      dark
  >
   {{ text }}
  </v-icon>
</template>

<script>
export default {
  name: "Arrow",
  props: {
    text: String
  },
  methods: {
    onmousedown() {
      this.$emit('updateMousedown')
    },
    ontouchstart() {
      this.$emit('updateTouchstart')
    },
    onTouchend() {
      this.$emit('updateTouchend')
    }
  }
}
</script>

<style scoped>

</style>