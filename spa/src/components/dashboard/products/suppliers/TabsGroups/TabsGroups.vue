<template>
  <div class="tabsGroups">
    <tabs-groups-item
        customClass="not-active"
        :title="$t('page.suppliers.groups')"
        :keyTabItem="getTabsGroup.BY_GROUPS"
        :active="activeTab === getTabsGroup.BY_GROUPS"
        @updateTab="onHandler"
    ></tabs-groups-item>
    <tabs-groups-item
        :title="$t('page.suppliers.allSuppliers')"
        :keyTabItem="getTabsGroup.ALL"
        :active="activeTab === getTabsGroup.ALL"
        @updateTab="onHandler"
    ></tabs-groups-item>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import TabsGroupsItem from "./components/TabsGroupsItem";
import {TABS_GROUPS} from "@/constants/constants";
import {eventBus} from "@/main";

export default {
 name: "TabsGroups",
  components: {
    TabsGroupsItem
  },
  computed: {
   getTabsGroup() {
     return TABS_GROUPS
   }
  },
  data() {
   return {
     activeTab: TABS_GROUPS.ALL,
   }
  },
  mounted() {
    eventBus.$on('switchTab', () => {
      this.activeTab = TABS_GROUPS.BY_GROUPS;
    })
  },
  methods: {
    ...mapActions(['getSuppliersTable']),
   async onHandler(value) {
     if (value === TABS_GROUPS.ALL) {
       eventBus.$emit("minimizeCategories");
       await this.getSuppliersTable();
       this.activeTab = value;
     }
   }
  }
}
</script>

<style scoped lang="scss">
.tabsGroups {
  padding: 0 12px;
  margin-right: 0;
  max-width: 260px;
  width: 100%;
  display: flex;
  justify-content: flex-start;

  &-tab {
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
    line-height: 1;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #317CCE;
    margin-right: 20px;
    opacity: 0.4;

    &.active {
      opacity: 1;
    }
  }
}
</style>
