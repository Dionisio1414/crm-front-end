<template>
  <d-table
      v-if="documentTableList"
      :total="documentTableList.total"
      :header="documentTableList.headers"
      :body="documentTableList.body"
      @deleteList="onDeleteList"
      @copyList="onCopyList"
      @editList="onEditList"
      @scrolledToEnd="onScrollPagination"
      @updateIdRow="onIdRow"
      @updatePagination="onPagination"
      :paginationCount="docPaginationCount"
      :actions="actions"
      :rowId="rowId"
      resource="documents/document-warehouses-headers"
      isCheckItem
      isHideMove
  >
  </d-table>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import DTable from "@/components/ui/DTable/DTable";

export default {
  name: "DocumentTable",
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
  computed: mapGetters(['documentTableList', 'docPaginationCount']),
  methods: {
    ...mapActions(['getDocumentTable', 'getDocPaginationCount']),
    onDeleteList(values) {
      this.$emit('updateDeleteItems', values)
    },
    onPagination() {
      this.getDocPaginationCount();
    },
    onCopyList(values) {
      this.$emit('copyList', values)
    },
    onEditList(values) {
      this.$emit('editList', values)
    },
    onMoveList() {
      console.log('onMoveList')
    },
    async onScrollPagination(page) {
      await this.getDocumentTable(page);
    },
    onIdRow(id) {
      this.rowId = id;
    }
  },
  async mounted() {
    await this.getDocumentTable();
  }
}
</script>
