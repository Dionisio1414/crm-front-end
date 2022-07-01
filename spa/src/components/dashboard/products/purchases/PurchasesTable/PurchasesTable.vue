<template>
  <div class="card card-table" :class="{'disabled': loading}">
    <d-table
        v-if="purchasesTable"
        :total="purchasesTable.total"
        :header="purchasesTable.headers"
        :body="purchasesTable.body"
        @deleteList="onDeleteList"
        @copyList="onCopyList"
        @editList="onEditList"
        @scrolledToEnd="onScrollPagination"
        @updateIdRow="onIdRow"
        @updatePagination="onPagination"
        :paginationCount="purPaginationCount"
        :actions="actions"
        :rowId="rowId"
        resource="documents/purchases/headers"
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
  name: "PurchasesTable",
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
  computed: mapGetters(['purchasesTable', 'purPaginationCount']),
  methods: {
    ...mapActions(['fetchPurchases', 'getPurPaginationCount']),
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
      this.getPurPaginationCount();
    },
    async onScrollPagination(page) {
      await this.fetchPurchases(page);
    },
    onIdRow(id) {
      this.rowId = id;
    }
  },
  async mounted() {
    await this.fetchPurchases();
  }
}
</script>

<style scoped lang="scss">
  .card.disabled {
    pointer-events: none;
    opacity: .8;
  }
</style>
