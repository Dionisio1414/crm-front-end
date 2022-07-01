<template>
    <div class="wrap">
        <ContractorsTable
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="individuals_documents" 
            ref="table"
            @copy="copy"
            @clickRow="onChangeItem"
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
import { pick } from "@/helper/pick"

export default {
    name: "IndividualDocuments",
    components: {
        ContractorsTable,
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
            return this.$store.getters.getClassifiersItems('individuals_documents')
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
        copy(val) {
            console.log('copy val', val)
            this.$emit('copy', val)
            this.$refs.table.clearChecked()
        },
        onchangeDefaultItem(id) {
            this.changeDefault({id, resources: 'cities'})
        },
        onChangeItem(itemData) {
            console.log('ITEMDATA', itemData)
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            console.log('itemDataitemData', itemData)
            this.$emit('viewItem', itemData)
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'individuals_documents' })
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
            const items = pick(itemsToDelete)('id')
            const data = { data: [...items] }

            if (await this.$refs.confirmDelete
                .open({directory:'directories.contractorsDocumentsContract', items: itemText })) {
                this.delItems({itemsToDelete: data, resources: 'individuals_documents'}).then(() => {
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        }
    },
    created() {
        this.fetchItems('individuals_documents')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>