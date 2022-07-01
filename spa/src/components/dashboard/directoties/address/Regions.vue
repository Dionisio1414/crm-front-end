<template>
    <div class="wrap">
        <DataTable 
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="regions" 
            ref="table"
            @changeDefaultItem="onchangeDefaultItem"
            @changeItem="onChangeItem"
            @clickRow="onViewItem"
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
    name: "Regiones",
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
            tableItems: 'getRegions',
        })
    },
    methods: {
        ...mapActions({
            changeDefault: 'changeDefaultItem',
            fetchItems: 'fetchClassifiersItems',
            changeClassifiersItem: "changeClassifiersItem",
            delItems: 'deleteClassifiersItems',
            getPaginationCounter: 'getPaginationCounter',
            getPaginationItems: 'getPaginationItems'
        }),
        onchangeDefaultItem(value) {
            console.log(value)
            this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'regions' })
        },
        onChangeItem(itemData) {
            console.log('changeItem', itemData)
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            this.$emit('viewItem', itemData)
        },
        createDelString({ city, department_number, house_number, street }) {
            return `№${department_number} г.${city}, ул.${street} ${house_number}`
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title
            const items = pick(itemsToDelete)('id')
            const data = { data: [...items] }

            if (await this.$refs.confirmDelete
                .open({directory:'directories.regionsDelTitle', items: itemText })) {
                this.delItems({itemsToDelete: data, resources: 'regions'}).then(() => {
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no')
            }
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'regions' })
        },
    },
    created() {
        this.fetchItems('regions')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>