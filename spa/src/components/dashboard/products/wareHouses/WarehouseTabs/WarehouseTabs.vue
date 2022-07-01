<template>
  <div class="tabs" :class="{'smallTabs': isSmall}">
    <tab :val="val" :tabId="tabId" :isSmall="isSmall" @updateTabs="onTab" :key="val.id" v-for="val in tabs"></tab>
  </div>
</template>

<script>
import Tab from "./components/WarehouseTab";
export default {
  name: "WarehouseTabs",
  components: { Tab },
  props: {
    tabs: Array,
    isSmall: Boolean,
    defaultTab: String
  },
  watch: {
    defaultTab(val, oldVal) {
      if (val !== oldVal) {
        this.tabId = 1;
      }
    }
  },
  data: () => ({
    tabId: 1
  }),
  methods: {
    onTab({id, status}) {
      if (status) return false;

      this.tabId = id;
      this.$emit('updateTabValue', id)
    }
  }
}
</script>

<style scoped lang="scss">
.tabs {
  display: flex;
  align-items: center;
  margin: 0 10px -10px;

  &.smallTabs {
    margin: 0 -10px;
  }
}
</style>