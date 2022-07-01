<template>
  <li class="context-menu-item">
    <div class="item-icon">
      <slot></slot>
    </div>
    <a @click.prevent="onHandler(itemKey)">{{ $t(`page.contextMenu.${itemKey}`) }}</a>
  </li>
</template>

<script>
export default {
  name: "ContextMenuItem",
  props: {
    itemKey: String
  },
  methods: {
    onHandler(value) {
      this.$emit('updateEvent', value)
    }
  }
}
</script>

<style scoped>

</style>