<template>
        <div class="wrap">
        <DataTable 
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="counterparties_returns" 
            ref="table"
            @copy="copy"
            @clickRow="clickRow"
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
import { pick } from "@/helper/pick"

export default {
    name: "ReasonReturningGoods",
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
            tableItems: 'getCounterpartiesReturns',
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
        clickRow(val) {
            console.log(val)
            this.$emit('changeItem', val)
        },
        copy(val) {
            console.log('copy val&&', val)
            this.$emit('copy', val)
            this.$refs.table.clearChecked()
        },
        onchangeDefaultItem(value) {
            // this.changeDefault({id, resources: 'counterparties_returns'})
            this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'counterparties_returns' })
        },
        onChangeItem(itemData) {
            console.log('change item ', itemData)
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            this.$emit('viewItem', itemData)
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'counterparties_cancellations' })
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
            const items = pick(itemsToDelete)('id')
            const data = { data: [...items] }

            if (await this.$refs.confirmDelete
                .open({directory:'directories.contactorsReasonGoods', items: itemText })) {
                this.delItems({itemsToDelete: data, resources: 'counterparties_returns'}).then( () => {
                this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        }
    },
    created() {
        this.fetchItems('counterparties_returns')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>