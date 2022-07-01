<template>
    <div>
        <div class="label-title">{{ $t(label) }}</div>
        <div class="list">
            <div class="list-header">
                <div class="list-header-item">
                    <div class="checkbox">
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                @change="onAllSelected" 
                                v-model="checkAllSelected"
                            >
                            <div class="checkbox-text">
                                <div class="text">{{ $t('filters.allUsers') }}</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="list-body">
                <div class="list-body-item" v-for="(val, index) in copyList" :key="index">
                    <div class="checkbox">
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                v-model="val.is_selected"
                                @change="onChangeItem($event, val.id)"
                            >
                            <div class="checkbox-text">
                                <div class="text">{{ val.title }}</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
    name: "FilterList",
    props: {
        label: String,
        list: Object
    },
    data() {
        return {
            copyList: this.list.lists.slice()
        }
    },
    computed: {
        checkAllSelected: {
            get() {
                return this.copyList.every(item => item.is_selected)
            },
            set(value) {
                return value
            }
        }
    },
    methods: {
        ...mapActions([
            'changeDataFilter'
        ]),
        onAllSelected({ target }) {
            if(!target.checked) { 
                this.copyList.forEach(element => element.is_selected = false)
                const payload = {
                    lists: {
                        [this.list.type]: this.copyList 
                    },
                    resource: this.list.resource
                }
                this.changeDataFilter(payload)
            } else { 
                this.copyList.forEach(element => element.is_selected = true)
                const payload = {
                    lists: {
                        [this.list.type]: this.copyList 
                    },
                    resource: this.list.resource
                }
                this.changeDataFilter(payload)
            }
        },
        onChangeItem({ target }, id) {
            this.copyList.forEach(item => {
                if(item.id === id && target.checked) {
                    item.is_selected = true
                    const payload = {
                        lists: {
                            [this.list.type]: this.copyList 
                        },
                        resource: this.list.resource
                    }
                    this.changeDataFilter(payload)
                } else if(item.id === id && !target.checked) {
                    item.is_selected = false
                    const payload = {
                        lists: {
                            [this.list.type]: this.copyList 
                        },
                        resource: this.list.resource
                    }
                    this.changeDataFilter(payload)
                } else {
                    return false
                }
            })
        }
    }
}
</script>
<style scoped>

</style>