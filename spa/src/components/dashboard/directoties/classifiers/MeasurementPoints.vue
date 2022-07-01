<template>
  <div class="wrap">
    <DataTable
        :total="tableItems.total"
        :header=" tableItems.headers"
        :body=" tableItems.body"
        :actions="actions"
        ref="table"
        resource="directories/units" 
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
import DataTable from "@/components/ui/DataTable";
import {mapActions, mapGetters} from "vuex";
import DeleteModal from "@/components/dashboard/directoties/deleteModal";
import { pick } from "@/helper/pick"

export default {
  name: "MeasurementPoints",
  components: {DataTable, DeleteModal},
  data: () => ({
    actions: [
        { type: 'edit' },
        { type: 'delete' }
    ]
  }),
  computed: {
    ...mapGetters({
      tableItems: 'getMeasurementsPoint',
    })
  },
  async created() {
    this.fetchItems('units')
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
      this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'units' })
    },
    onChangeItem(itemData) {
      this.$emit('changeItem', itemData)
    },
    onViewItem(itemData) {
      this.$emit('viewItem', itemData)
    },
    async onScrollPagination(page) {
      await this.getPaginationItems({ page, resources: 'units' })
    },
    async deleteItems(itemsToDelete) {
      // const itemText = itemsToDelete.length> 1 ? itemsToDelete.length : itemsToDelete[0].cells.title_full
      const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title_full
      const items = pick(itemsToDelete)('id')
      const data = { data: [...items] }
      if (await this.$refs.confirmDelete
          .open({directory:'directories.units', items: itemText })) {
        this.delItems({itemsToDelete: data, resources: 'units'}).then( () => {
          this.$refs.table.clearChecked()
        })
      } else {
        console.log('no');
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