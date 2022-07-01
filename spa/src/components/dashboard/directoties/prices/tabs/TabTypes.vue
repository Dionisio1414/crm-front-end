<template>
  <div class="wrap">
    <DataTable
        :total="tableItems.total"
        :header=" tableItems.headers"
        :body=" tableItems.body"
        :actions="actions"
        resource="prices_types"
        ref="table"
        @changeDefaultItem="onchangeDefaultItem"
        @changeItem="onChangeItem"
        @viewItem="onViewItem"
        @deleteSelected="deleteItems"
        @scrolledToEnd="onScrollPagination"
    ></DataTable>
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
import DataTable from "@/components/ui/DataTable"
import {mapActions, mapGetters} from "vuex"
import DeleteModal from "@/components/dashboard/directoties/deleteModal"
import { pick } from "@/helper/pick"

export default {
  name: "TabTypes",
  components: { 
    DataTable, 
    DeleteModal 
  },
  data: () => ({
    actions: [ { type: 'delete' } ]
  }),
  computed: {
    ...mapGetters({
      tableItems: 'getTypeOfPrices',
    })
  },
  async created() {
    await this.fetchItems('prices_types')
  },
  methods: {
    ...mapActions({
      changeDefault: 'changeDefaultItem',
      fetchItems: 'fetchClassifiersItems',
      delItems: 'deleteClassifiersItems',
      changeClassifiersItem: "changeClassifiersItem",
      getPaginationCounter: 'getPaginationCounter',
      getPaginationItems: 'getPaginationItems'
    }),
    async onchangeDefaultItem(value) {
      await this.changeClassifiersItem({ data: { id: value.id, is_default: true, is_cells: true }, resources: 'prices_types' })
      await this.getPaginationCounter('clear')
    },
    onChangeItem(itemData) {
      this.$emit('changeItem', itemData)
    },
    onViewItem(itemData) {
      this.$emit('viewItem', itemData)
    },
    onSaveData(params) {
      this[params.action]({...params})
      this.$refs[this.currentModal].close()
      if (params.action === 'create') {
        this.showAlert(params.alertItem)
      }
      if (params.data.is_default) {
        this.changeDefault({id: params.data.id, resources: params.resources } )
      }
    },
    async onScrollPagination(page) {
        await this.getPaginationItems({ page, resources: 'prices_types' })
    },
    async deleteItems(itemsToDelete) {
      console.log('itemsToDelete', itemsToDelete)
      const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
      const items = pick(itemsToDelete)('id')
      const data = { data: [...items] }

      if (await this.$refs.confirmDelete
          .open({directory:'directories.type_of_price', items: itemText })) {
          this.delItems({resources: 'prices_types', itemsToDelete: data,}).then(() => {
              this.$emit('delete', itemText)
              this.$refs.table.clearChecked()
          })
      } else {
          console.log('no')
      }

    }
  },
  mounted() {
    this.getPaginationCounter('clear')
  }
}
</script>

<style scoped>

</style>