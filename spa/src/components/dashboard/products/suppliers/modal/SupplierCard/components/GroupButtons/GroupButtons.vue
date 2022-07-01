<template>
  <ul class="groupButtons">
    <group-button
        title="Продать"
        @updateBtn="onHandler(0)"
        isDisabled
    >
      <svg-sprite width="17" height="17" icon-id="shopIcon"></svg-sprite>
    </group-button>
    <group-button
        title="Купить"
        @updateBtn="onHandler(1)"
    >
      <svg-sprite width="17" height="17" icon-id="shopIcon"></svg-sprite>
    </group-button>
  </ul>
</template>

<script>
import GroupButton from "./components/GroupButton";

export default {
name: "GroupButtons",
  components: {
    'group-button': GroupButton
  },
  methods: {
    onHandler(key) {
      if (key) {
        this.$emit('updateBuy')
      } else {
        console.log('sell')
      }

    }
  }
}
</script>

<style scoped lang="scss">
.groupButtons {
  padding: 10px 30px;
  display: flex;
  list-style-type: none;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
  margin-bottom: 30px;
}
</style>
