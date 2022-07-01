<template>
  <div class="main-container">
    <div class="main-content">
      <card-preview-lead isLeadCard></card-preview-lead>
      <my-orders-table>sdsd</my-orders-table>
    </div>
    <sidebar class="sidebar sidebar-crm"></sidebar>
  </div>
</template>

<script>
import CardPreviewLead from "@/components/dashboard/crm/leads/components/CardPreviewLead";
import Sidebar from "@/components/dashboard/crm/leads/components/Sidebar";
import MyOrdersTable from "@/components/dashboard/crm/leads/components/MyOrdersTable";

export default {
  name: "pageCrm",
  components: {
    CardPreviewLead,
    Sidebar,
    MyOrdersTable
  }
}
</script>

<style scoped lang="scss">
.main-container {
  display: flex;
  flex-wrap: wrap;
}

.main-content {
  flex-grow: 1;
}

</style>