<template>
  <d-table
      v-if="items"
      :header="items.headers"
      :body="items.body"
      @deleteList="onDeleteList"
      @editList="onEditList"
      @scrolledToEnd="onScrollPagination"
      @updateIdRow="onIdRow"
      :actions="actions"
      :rowId="rowId"
      resource="documents/document-warehouses-headers"
      isCheckItem
      isHideMove
      isHideCopy
  >
  </d-table>
</template>

<script>
// components
import DTable from "@/components/ui/DTable/DTable";

export default {
  name: "PriceDiscountTable",
  components: {
    DTable,
  },
  props: {
    items: Object,
  },
  data: () => ({
    rowId: null,
    actions: [
      { type: 'edit' },
      { type: 'delete' }
    ]
  }),
  methods: {
    onDeleteList(values) {
      this.$emit('updateDeleteItems', values)
    },
    onEditList(values) {
      this.$emit('editList', values)
    },
    async onScrollPagination(page) {
      await this.getPriceDiscountList(page);
    },
    onIdRow(id) {
      this.rowId = id;
    }
  }
}
</script>

<style scoped>

</style>