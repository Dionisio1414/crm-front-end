<template>
  <div class="card card-table" :class="{'disabled': loading}">
    <d-table
        v-if="ordersTable"
        :total="ordersTable.total"
        :header="ordersTable.headers"
        :body="ordersTable.body"
        @deleteList="onDeleteList"
        @copyList="onCopyList"
        @editList="onEditList"
        @scrolledToEnd="onScrollPagination"
        @updateIdRow="onIdRow"
        @updatePagination="onPagination"
        :paginationCount="ordersPaginationCount"
        :actions="actions"
        :rowId="rowId"
        resource="documents/orders/headers"
        isCheckItem
        isHideMove
        isPurchases
    >
    </d-table>
  </div>

</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import DTable from "@/components/ui/DTable/DTable";

export default {
  name: "OrderTable",
  props: ['loading'],
  components: {
    'd-table': DTable,
  },
  data: () => ({
    rowId: null,
    actions: [
      { type: 'edit' },
      { type: 'copy' },
      { type: 'delete' }
    ]
  }),
  computed: mapGetters(['ordersTable', 'ordersPaginationCount']),
  methods: {
    ...mapActions(['fetchOrders', 'getOrdersPaginationCount']),
    onDeleteList(values) {
      this.$emit('updateDeleteItems', values)
    },
    onCopyList(values) {
      this.$emit('copyList', values)
    },
    onEditList(values) {
      this.$emit('editList', values)
    },
    onPagination() {
      this.getOrdersPaginationCount();
    },
    async onScrollPagination(page) {
      await this.fetchOrders(page);
    },
    onIdRow(id) {
      this.rowId = id;
    }
  },
  async mounted() {
    await this.fetchOrders();
  }
}
</script>

<style scoped lang="scss">
  .card.disabled {
    pointer-events: none;
    opacity: .8;
  }
</style>
