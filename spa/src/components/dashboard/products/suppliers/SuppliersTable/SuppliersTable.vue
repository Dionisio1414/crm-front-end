<template>
  <div
      v-if="suppliersDataTable"
      class="card card-table"
      :class="{'disabled': loading}"
  >
    <d-table
        :total="suppliersDataTable.total"
        :header="suppliersDataTable.headers"
        :body="suppliersDataTable.body"
        @deleteList="onDeleteList"
        @copyList="onCopyList"
        @editList="onEditList"
        @moveList="onMoveList"
        @scrolledToEnd="onScrollPagination"
        @updatePagination="onPagination"
        @clickRow="onClickRow"
        @updateIdRow="onIdRow"
        @updateContactInfo="onContactInfo"
        :actions="actions"
        :rowId="rowId"
        :paginationCount="suppliersPaginationCount"
        resource="suppliers/headers"
        isContact
        isCheckItem
        ref="table"
    >
    </d-table>
    <contact-info
        v-if="contactInfoData && !isContacts"
       :contactInfoData="contactInfoData"
    ></contact-info>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import DTable from "@/components/ui/DTable/DTable";
import ContactInfo from "@/components/ui/ContactInfo/ContactInfo";

export default {
  name: "SuppliersTable",
  props: ['loading'],
  components: {
    'd-table': DTable,
    ContactInfo
  },
  data: () => ({
    contactInfoData: null,
    rowId: null,
    actions: [
      { type: 'edit' },
      { type: 'copy' },
      { type: 'move' },
      { type: 'delete' }
    ]
  }),
  computed: {
    ...mapGetters(['suppliersDataTable', 'suppliersPaginationCount']),
    isContacts() {
      return this.contactInfoData && Object.values(this.contactInfoData.contacts).every(item => !item)
    }
  },
  methods: {
    ...mapActions(['getSuppliersTable', 'getSupplierPaginationCount']),
    onContactInfo(values) {
      this.contactInfoData = values;
    },
    onDeleteList(values) {
      this.$emit('updateDeleteItems', values)
    },
    onPagination() {
      this.getSupplierPaginationCount();
    },
    onCopyList(values) {
      this.$emit('copyList', values)
    },
    onEditList(values) {
      this.$emit('editList', values)
    },
    onMoveList(values) {
      this.$emit('moveList', values)
    },
    onClickRow(itemData) {
      this.$emit('updateRow', itemData)
    },
    async onScrollPagination(page) {
      const values = {page};
      await this.getSuppliersTable(values);
    },
    onIdRow(id) {
      this.rowId = id;
    }
  },
  async mounted() {
    await this.getSuppliersTable();
  }
}
</script>

<style scoped lang="scss">
  .card {
    overflow-y: visible;
  }

  .card.disabled {
    opacity: .8;
    pointer-events: none;
  }
</style>
