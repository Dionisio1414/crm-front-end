<template>
  <li class="groupButtonsItem">
    <button
        class="groupButtonsBtn"
        :disabled="isDisabled"
        @click="onClick"
    >
      <slot></slot>
      <span>{{ title }}</span>
    </button>
  </li>
</template>

<script>
export default {
  name: "groupButton",
  props: {
    title: String,
    isDisabled: Boolean
  },
  methods: {
    onClick() {
      this.$emit('updateBtn')
    }
  }
}
</script>

<style scoped lang="scss">
.groupButtonsItem {
  border-right: 1px solid #E3F0FF;
}

.groupButtonsItem:first-child .groupButtonsBtn {
  padding-left: 0;
}

.groupButtonsItem:last-child {
  border-right: none;
}

.groupButtonsBtn {
  font-size: 14px;
  line-height: 14px;
  color: #5893D4;
  display: flex;
  align-items: center;
  min-height: 40px;
  padding: 0 15px;

  &[disabled] {
    opacity: .5;
  }

  span {
    display: inline-block;
    margin-left: 10px;
  }
}
</style>