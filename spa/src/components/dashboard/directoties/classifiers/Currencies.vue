<template>
  <div class="wrap">
    <Table
        :total="tableItems.total"
        :header=" tableItems.headers"
        :body=" tableItems.body"
        :actions="actions"
        ref="table"
        resource="directories/currencies" 
        @changeDefaultItem="onchangeDefaultItem"
        @changeItem="onChangeItem"
        @viewItem="onViewItem"
        @deleteSelected="deleteItems"
        @scrolledToEnd="onScrollPagination"
    ></Table>
    <delete-modal ref="confirmDelete">
      <div class="header-text" slot="dialog-header">
        <span>{{ $t('page.saveButton') }}</span>
      </div>
      <div class="dialog-text" slot="dialog-text">
        {{ $t('page.saveChanges') }}
      </div>
    </delete-modal>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import Table from "@/components/ui/DataTable";
import DeleteModal from "@/components/dashboard/directoties/deleteModal";
import { pick } from "@/helper/pick"

export default {
  name: "Currencies",
  components: {Table, DeleteModal},
  data: () => ({
    actions: [
        { type: 'edit' },
        { type: 'delete' }
    ]
  }),
  computed: {
    ...mapGetters({
      tableItems: 'getCurrency',
    })
  },
  methods: {
    ...mapActions({
      changeDefault: 'changeDefaultItem',
      changeClassifiersItem: "changeClassifiersItem",
      fetchItems: 'fetchClassifiersItems',
      delItems: 'deleteClassifiersItems',
      getPaginationCounter: 'getPaginationCounter',
      getPaginationItems: 'getPaginationItems'
    }),
    async onScrollPagination(page) {
      await this.getPaginationItems({ page, resources: 'currencies' })
    },
    onchangeDefaultItem(value) {
      console.log(value)
      this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'currencies' })
    },
    onChangeItem(itemData) {
      this.$emit('changeItem', itemData)
    },
    onViewItem(itemData) {
      this.$emit('viewItem', itemData)
    },
    async deleteItems(itemsToDelete) {
      const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
      const items = pick(itemsToDelete)('id')
      const data = { data: [...items] }
      
      // const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : this.tableItems.body.find(item => item.id === itemsToDelete[0]).cells.title_full
      console.log(itemsToDelete)
      if (await this.$refs.confirmDelete
          .open({directory: 'directories.currencies', items: itemText})) {
        this.delItems({itemsToDelete: data, resources: 'currencies'}).then(() => {
          this.$refs.table.clearChecked()
        })
      } else {
        console.log('no')
      }
    }
  },
  async created() {
    this.fetchItems('currencies')
  },
  mounted() {
    this.getPaginationCounter('clear')
  }
}
</script>
<style scoped>
.table-wrapper {
  height: 100%;
}
</style>