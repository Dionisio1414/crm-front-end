<template>
    <div class="wrap">
        <ContractorsTable
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="counterparties" 
            ref="table"
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
import ContractorsTable from "@/components/ui/ContractorsTable"
import { mapActions } from "vuex"
import DeleteModal from "@/components/dashboard/directoties/deleteModal"

export default {
    name: "Contractor",
    components: {
        ContractorsTable,
        DeleteModal
    },
    data: () => ({
        actions: []
    }),
    computed: {
        tableItems() {
            return this.$store.getters.getClassifiersItems('counterparties')
        }
    },
    methods: {
        ...mapActions({
            changeDefault: 'changeDefaultItem',
            fetchItems: 'fetchClassifiersItems',
            delItems: 'deleteClassifiersItems',
            getPaginationItems: 'getPaginationItems',
            getPaginationCounter: 'getPaginationCounter'
        }),
        onchangeDefaultItem(id) {
            this.changeDefault({id, resources: 'cities'})
        },
        onChangeItem(itemData) {
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            console.log('itemDataitemData', itemData)
            this.$emit('viewItem', itemData)
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'counterparties' })
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : this.tableItems.body.find(item =>item.id === itemsToDelete[0]).cells.title_city
            console.log(itemsToDelete, itemText)
            if (await this.$refs.confirmDelete
                .open({directory:'directories.citiesText', items: itemText })) {
                this.delItems({itemsToDelete, resources: 'cities'}).then(() => {
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        }
    },
    created() {
        this.fetchItems('counterparties')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>