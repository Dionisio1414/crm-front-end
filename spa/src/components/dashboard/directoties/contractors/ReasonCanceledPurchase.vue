<template>
    <div class="wrap">
        <DataTable 
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="counterparties_cancellations" 
            ref="table"
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
import { pick } from "@/helper/pick"

export default {
    name: "ReasonCanceledPurchase",
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
            tableItems: 'getCounterpartiesCancellations',
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
            console.log('copy val!!!', val)
            this.$emit('copy', val)
            this.$refs.table.clearChecked()
        },
        onchangeDefaultItem(value) {
            // this.changeDefault({id, resources: 'counterparties_cancellations'})
            this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'counterparties_cancellations' })
        },
        onChangeItem(itemData) {
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
                .open({directory:'directories.contactorsCanceledPurchace', items: itemText })) {
                this.delItems({itemsToDelete: data, resources: 'counterparties_cancellations'}).then( () => {
                this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        }
    },
    created() {
        this.fetchItems('counterparties_cancellations')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>