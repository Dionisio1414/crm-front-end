<template>
  <div
      :style="{paddingLeft: `${depth + 10}px`}"
      class="last-groups-level"
  >
    <span @click="addGroup">
          <svg-sprite width="17" height="17" icon-id="plusRound"></svg-sprite>
    </span>
  </div>
</template>

<script>
export default {
 name: "AddGroupButton",
  props: {
    depth: Number
  },
  methods: {
    addGroup() {
      this.$emit('updateAddGroup')
    }
  }
}
</script>

<style scoped>

</style>
