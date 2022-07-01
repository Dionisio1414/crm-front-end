<template>
  <ul class="tabs-list">
    <li class="tabs-item" :class="{ 'active': index === idx }"
        v-for="(tab, index) in tabs"
        @click="onHandler(index, tab.title)" :key="index">
      {{ $t(tab.title) }}
    </li>
  </ul>
</template>

<script>
export default {
  name: "TabsList",
  props: {
    tabs: Array,
    idx: Number
  },
  methods: {
    onHandler(idx, title) {
      this.$emit('updateTabs', idx, title)
    }
  }
}
</script>

<style scoped>

</style>