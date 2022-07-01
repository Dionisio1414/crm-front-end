<template>
    <div class="wrap">
        <DataTable
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="users" 
            ref="table"
            @clickRow="clickRow"
            @changeDefaultItem="onchangeDefaultItem"
            @changeItem="onChangeItem"
            @viewItem="onViewItem"
            @deleteSelected="deleteModal"
            @scrolledToEnd="onScrollPagination"
        />  
        <!-- <delete-modal ref="confirmDelete">
            <div class="header-text" slot="dialog-header">
                <span>{{ $t('page.saveButton') }}</span>
            </div>
            <div class="dialog-text" slot="dialog-text">
                {{ $t('page.saveChanges') }}
            </div>
        </delete-modal> -->
        <modal-delete
            @successDelete="successDelete"
            ref="modalDelete"
        >
        </modal-delete>
    </div>      
</template>
<script>
import DataTable from "@/components/ui/EmployeesTable"
import { mapActions, mapGetters } from "vuex"
// import DeleteModal from "@/components/dashboard/directoties/deleteModal"
import ModalDelete from "@/components/ui/ModalDelete"

export default {
    name: "Users",
    components: {
        DataTable,
        ModalDelete
        // DeleteModal
    },
    data: () => ({
        actions: [
            { type: 'edit' },
            { type: 'delete' }
        ]
    }),
    computed: {
        ...mapGetters({
            tableItems: 'getUsers',
        })
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
        clickRow(val) {
            console.log(val)
            this.$emit('clickRow', val, 'ModalEditUsers')
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'users' })
        },
        async successDelete(data) {
            const arrayId = data.map(item => item.id)
            const itemsToDelete = { data: [] }
            arrayId.forEach(item => itemsToDelete.data.push({ id: item }))
            await this.delItems({ itemsToDelete, resources: 'users' })
            console.log('table ref', this.$refs.table)
            this.$refs.table.clearChecked()
            console.log('successDelete')
        },
        deleteModal(itemsToDelete) {
            console.log('itemsToDelete', itemsToDelete)
            const directory = 'directories.employeesText'
            const items = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.full_name 
            this.$refs.modalDelete.data = itemsToDelete
            this.$refs.modalDelete.open = true
            this.$refs.modalDelete.options = { directory, items }
        },
    },
    created() {
        this.fetchItems('users')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>