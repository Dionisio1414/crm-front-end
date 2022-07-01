<template>
  <div class="wrap">
    <DataTable
        :total="tableItems.total"
        :header=" tableItems.headers"
        :body=" tableItems.body"
        :actions="actions"
        resource="directories/countries" 
        ref="table"
        @changeDefaultItem="onchangeDefaultItem"
        @changeItem="onChangeItem"
        @viewItem="onViewItem"
        @deleteSelected="deleteItems"
        @scrolledToEnd="onScrollPagination"
    >
    </DataTable>
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
import { mapActions, mapGetters } from "vuex"
import DeleteModal from "@/components/dashboard/directoties/deleteModal"

export default {
  name: "Countries",
  components: { DataTable, DeleteModal },
  data: () => ({
    actions: []
  }),
  computed: {
    ...mapGetters({
      tableItems: 'getCountries',
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
    onchangeDefaultItem(value) {
      console.log(value)
      this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'countries' })
    },
    onChangeItem(itemData) {
      console.log(itemData);
      this.$emit('changeItem', itemData)
    },
    onViewItem(itemData) {
      console.log(itemData);
      this.$emit('viewItem', itemData)
    },
    async onScrollPagination(page) {
      console.log(page)
      await this.getPaginationItems({ page, resources: 'countries' })
    },
    async deleteItems(itemsToDelete) {
      const itemText = itemsToDelete.length> 1 ? itemsToDelete.length : this.tableItems.body.find(item =>item.id === itemsToDelete[0]).cells.title_full
      if (await this.$refs.confirmDelete
          .open({directory:'directories.prices', items: itemText })) {
        this.delItems({itemsToDelete, resources: 'countries'}).then( () => {
          this.$refs.table.clearChecked()
        })
      } else {
        console.log('no');
      }
    }
  },
  async created() {
    this.fetchItems('countries')
  },
  mounted() {
    this.getPaginationCounter('clear')
  }
}
</script>

<style scoped>

</style>