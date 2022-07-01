<template>
  <vue-context
      class="context-menu"
      ref="menu"
      :close-on-click="isCloseMenu"
  >
    <context-menu-item
        v-if="!isView"
        itemKey="view"
        @updateEvent="onHandler"
    >
      <svg-sprite width="25" height="25" icon-id="contextMenuEye"></svg-sprite>
    </context-menu-item>
    <context-menu-item
        v-if="!isGroup"
        itemKey="copy"
        @updateEvent="onHandler"
    >
      <svg-sprite width="13" height="13" icon-id="contextMenuCopy"></svg-sprite>
    </context-menu-item>
    <context-menu-item
        v-if="!isGroup && isMove"
        itemKey="move"
        @updateEvent="onHandler"
    >
      <svg-sprite width="25" height="25" icon-id="contextMenuMove"></svg-sprite>
    </context-menu-item>
    <context-menu-item
        itemKey="edit"
        @updateEvent="onHandler"
    >
      <svg-sprite width="13" height="13" icon-id="contextMenuEdit"></svg-sprite>
    </context-menu-item>
    <context-menu-item
        itemKey="remove"
        @updateEvent="onHandler"
    >
      <svg-sprite width="10" height="11" icon-id="contextMenuRemove"></svg-sprite>
    </context-menu-item>
  </vue-context>
</template>

<script>
// vuex
import { mapActions } from "vuex";
// components
import ContextMenuItem from "./components/ContextMenuItem";

export default {
 name: "ContextMenu",
  components: {
   'context-menu-item': ContextMenuItem
  },
  props: {
    refModal: Object,
    isGroup: Boolean,
    isView: Boolean,
    isMove: Boolean
  },
  data() {
   return {
     isCloseMenu: false
   }
  },
  methods: {
    ...mapActions(['getRefContextMenu']),
    onHandler(value) {
      this.$emit('updateContext', value)
    }
  },
  mounted() {
    this.getRefContextMenu(this.$refs.menu)
  }
}
</script>

<style scoped>

</style>
