<template>
  <span
      class="tabsGroups-tab"
      @click="onHandler"
      :class="[{'active': active}, customClass]"
  >
    {{ title }}
  </span>
</template>

<script>

export default {
 name: "TabsGroupsItem",
  props: {
    title: String,
    keyTabItem: String,
    active: Boolean,
    customClass: String
  },
  methods: {
    onHandler() {
      this.$emit('updateTab', this.keyTabItem)
    }
  }
}
</script>

<style scoped lang="scss">
  .not-active {
    pointer-events: none;
  }
</style>