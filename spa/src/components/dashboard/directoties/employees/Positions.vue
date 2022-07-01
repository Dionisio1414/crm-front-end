<template>
    <div class="wrap">
        <DataTable
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="positions" 
            ref="table"
            @clickRow="clickRow"
            @copy="copy"
            @changeDefaultItem="onchangeDefaultItem"
            @changeItem="onChangeItem"
            @viewItem="onViewItem"
            @deleteSelected="deleteItems"
            @scrolledToEnd="onScrollPagination"
        />  
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
    name: "Positions",
    components: {
        DataTable,
        DeleteModal
    },
    data: () => ({
        actions: [
            { type: 'copy' },
            { type: 'edit' },
            { type: 'delete' }
        ]
    }),
    computed: {
        ...mapGetters({
            tableItems: 'getPositions',
        })
    },
    methods: {
        ...mapActions({
            changeDefault: 'changeDefaultItem',
            fetchItems: 'fetchClassifiersItems',
            delItems: 'deleteClassifiersItems',
            changeClassifiersItem: "changeClassifiersItem",
            getPaginationItems: 'getPaginationItems',
            getPaginationCounter: 'getPaginationCounter'
        }),
        copy(val) {
            console.log('copy val', val)
            this.$emit('copy', val)
            this.$refs.table.clearChecked()
        },
        onchangeDefaultItem(value) {
            // this.changeDefault({id, resources: 'positions'})
            this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'positions' })
        },
        onChangeItem(itemData) {
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            console.log('itemDataitemData', itemData)
            this.$emit('viewItem', itemData)
        },
        clickRow(val) {
            console.log(val)
            this.$emit('changeItem', val)
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'positions' })
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
            const arrayId = itemsToDelete.map(item => item.id)
            const items = { data: [] }
            arrayId.forEach(item => items.data.push({ id: item }))

            if (await this.$refs.confirmDelete
                .open({directory:'directories.positionsText', items: itemText })) {
                this.delItems({itemsToDelete: items, resources: 'positions'}).then(() => {
                    this.$emit('delete', itemText)
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        }
    },
    created() {
        this.fetchItems('positions')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>