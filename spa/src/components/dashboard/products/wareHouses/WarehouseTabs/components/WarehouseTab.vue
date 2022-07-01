<template>
  <div class="tab" :class="{'active': tabId === val.id && !val.status, 'small': isSmall}" @click="onTab(val.id, val.status)">{{val.title}}</div>
</template>

<script>
export default {
  name: "WarehouseTab",
  props: {
    val: Object,
    tabId: Number,
    isSmall: Boolean
  },
  methods: {
    onTab(id, status) {
      const values = {
        id, status
      }
      this.$emit('updateTabs', values)
    }
  }
}
</script>

<style scoped lang="scss">
.tab {
  font-size: 15px;
  line-height: 15px;
  color: rgba(49, 124, 206, .6);
  padding: 15px 22px 22px;
  margin-bottom: -10px;
  cursor: pointer;
  box-sizing: border-box;

  &.active {
    background: #F4F9FF;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.03);
    border-radius: 10px 10px 0 0;
    color: #317CCE;
  }

  &.small {
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #9DCCFF;
    border-radius: 0;
    padding: 5px 15px;
  }

  &.small.active {
    background: none;
    box-shadow: none;
    color: #317CCE;
  }
}
</style>