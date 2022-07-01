<template>
    <div class="wrap">
        <DataTable
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="counterparties_contracts" 
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
import { mapActions } from "vuex"
import DeleteModal from "@/components/dashboard/directoties/deleteModal"
import { pick } from "@/helper/pick"

export default {
    name: "ContractsСontractors",
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
        tableItems() {
            return this.$store.getters.getClassifiersItems('counterparties_contracts')
        }
    },
    methods: {
        ...mapActions({
            changeDefault: 'changeDefaultItem',
            fetchItems: 'fetchClassifiersItems',
            delItems: 'deleteClassifiersItems',
            fetchLists: 'fetchLists',
            getPaginationItems: 'getPaginationItems',
            getPaginationCounter: 'getPaginationCounter'
        }),
        copy(val) {
            console.log('copy val', val)
            this.$emit('copy', val)
            this.$refs.table.clearChecked()
        },
        onchangeDefaultItem(id) {
            this.changeDefault({id, resources: 'counterparties_contracts'})
        },
        onChangeItem(itemData) {
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            console.log('itemDataitemData', itemData)
            this.$emit('changeItem', itemData)
        },
        clickRow(val) {
            console.log(val)
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'counterparties_contracts' })
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
            const items = pick(itemsToDelete)('id')
            const data = { data: [...items] }

            if (await this.$refs.confirmDelete
                .open({directory:'directories.contractrosText', items: itemText })) {
                this.delItems({itemsToDelete: data, resources: 'counterparties_contracts'}).then(() => {
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        }
    },
    created() {
        this.fetchItems('counterparties_contracts')
    },
    async mounted() {
        await this.getPaginationCounter('clear')
        await this.fetchLists({ type: '', resources: 'prices_types' })
    }
}
</script>
<style lang="sass">

</style>