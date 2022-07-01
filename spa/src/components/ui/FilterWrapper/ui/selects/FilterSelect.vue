<template>
    <div>
        <div class="label-title">{{ $t(label) }}</div>
        <div class="select-wrap">
            <v-select 
                v-model="selectedItem"
                :items="list.lists"
                class="select-switcher"
                solo
                dense
                :menu-props="{ contentClass: 'base-select'}"
                item-text="title"
                item-value="id"
                @change="onChangeMainSelect"
                return-object
            >
            </v-select>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
    name: "FilterSelect",
    props: {
        label: String,
        list: Object,
    },
    data() {
        return {
            copyList: this.list.lists.slice()
        }
    },
    computed: {
        selectedItem: {
            get() {
                return this.copyList.find(item => item.is_selected)
            },
            set({ id }) {
                this.copyList.forEach(item => { 
                    if(item.id === id)
                        item.is_selected = true
                    else
                        item.is_selected = false
                })
            }
        },
    },
    methods: {
        ...mapActions([
            'changeDataFilter'
        ]),
        onChangeMainSelect() {
            const payload = {
                selects: {
                   [this.list.type]: this.copyList 
                },
                resource: this.list.resource
            }
            this.changeDataFilter(payload)
        }
    }
}
</script>
<style scoped>

</style>