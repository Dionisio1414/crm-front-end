<template>
    <div class="wrap">
        <DataTable
            :total="tableItems.total"
            :header="tableItems.headers" 
            :body="tableItems.body"
            :actions="actions"
            resource="cities" 
            ref="table"
            @changeDefaultItem="onchangeDefaultItem"
            @changeItem="onChangeItem"
            @clickRow="onViewItem"
            @deleteSelected="deleteItems"
            @viewItem="onViewItem"
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
    name: "Locality",
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
            tableItems: 'getCities',
        })
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
            console.log('value', value)
            // this.changeDefault({id, resources: 'cities'})
            this.changeClassifiersItem({ data: { ...value, is_default: true }, resources: 'cities' })
        },
        onChangeItem(itemData) {
            this.$emit('changeItem', itemData)
        },
        onViewItem(itemData) {
            this.$emit('viewItem', itemData)
        },
        async deleteItems(itemsToDelete) {
            const itemText = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].cells.title_city
            const items = pick(itemsToDelete)('id')
            const data = { data: [...items] }

            if (await this.$refs.confirmDelete
                .open({directory:'directories.citiesText', items: itemText })) {
                this.delItems({itemsToDelete: data, resources: 'cities'}).then(() => {
                    this.$refs.table.clearChecked()
                })
            } else {
                console.log('no');
            }
        },
        async onScrollPagination(page) {
            await this.getPaginationItems({ page, resources: 'cities' })
        },
    },
    created() {
        this.fetchItems('cities')
    },
    mounted() {
        this.getPaginationCounter('clear')
    }
}
</script>
<style lang="sass">

</style>