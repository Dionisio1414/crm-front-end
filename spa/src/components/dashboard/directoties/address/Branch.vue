<template>
    <div class="wrap">
        <DataTable 
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="directories/departments" 
            ref="table"
            @changeDefaultItem="onchangeDefaultItem"
            @changeItem="onChangeItem"
            @clickRow="onViewItem"
            @deleteSelected="deleteItems"
            @scrolledToEnd="onScrollToEnd"
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
    name: "Branch",
    components: {
        DataTable,
        DeleteModal
    },
    data: () => ({
        actions: [
            { type: 'edit' },
            { type: 'delete' }
        ]
    }),
    computed: {
        ...mapGetters({
            tableItems: 'getDepartments',
        })
    },
    methods: {
        ...mapActions({
            changeDefault: 'changeDefaultItem',
            fetchItems: 'fetchDepartments',
            delItems: 'deleteClassifiersItems',
        }),
        onchangeDefaultItem(id) {
            this.changeDefault({id, resources: 'departments'})
        },
        onChangeItem(itemData) {
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            this.$emit('viewItem', itemData)
        },
        createDelString({ city, department_number, house_number, street }) {
            return `№${department_number} г.${city}, ул.${street} ${house_number}`
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length :  this.createDelString(this.tableItems.body.find(item =>item.id === itemsToDelete[0]).cells)
            if (await this.$refs.confirmDelete
                .open({directory:'directories.branchText', items: itemText })) {
                this.delItems({itemsToDelete, resources: 'departments'}).then(() => {
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        },
        onScrollToEnd() {
            console.log('onScrollToEnd')
            // if (this.pagination.page < this.tableItems.total_page) {
            //     this.loadMoreItems({resources: this.resources, page: ++this.pagination.page })
            // }
        },
    },
    created() {
        this.fetchItems('departments')
    },
    mounted() {
        console.log(this.tableItems)
    }
}
</script>
<style lang="sass">

</style>