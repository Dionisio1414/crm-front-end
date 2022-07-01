<template>
    <div class="lists-item" :class="{'lists-item--loader': isLoader, 'lists-item--disabled': filesLength >= limit}" :style="`background-image: url(${url})`">
        <div class="lists-actions">
            <label class="lists-label">
                <input 
                    :type="type" 
                    name="radio" 
                    class="field"
                    @click="selectItem($event, id, url)"
                    :checked="checked"
                    :disabled="filesLength >= limit"
                >
                <div :class="{'lists-radio': type === 'radio', 'lists-checkbox': type === 'checkbox'}" v-if="!isLoader"></div>
            </label>
            <button type="button" class="lists-remove" @click.stop="removeItem(id)" v-if="!isLoader">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
                </svg>
            </button>
        </div>
        <div class="lists-title">
            <v-tooltip bottom :disabled="title.length < 25">
                <template v-slot:activator="{ on, attrs }">
                    <span v-bind="attrs" v-on="on">{{ title }}</span>
                </template>
                <span>{{ title }}</span>
            </v-tooltip> 
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex"
import getBase64FromUrl from "@/helper/getBase64FromUrl"

export default {
    name: "ListItem",
    props: {
        type: String,
        index: Number,
        url: String, 
        title: String,
        id: Number,
        limit: Number,
        filesLength: Number
    },
    data: () => ({
        isLoader: false,
        checked: false
    }),
    methods: {
        ...mapActions(['deleteFile']),
        async removeItem(index) {
            this.isLoader = true
            await this.deleteFile({
                data: [
                    { id: index }
                ]
            })
            this.isLoader = false
            this.$emit('removeItem')
        },
        selectItem({ target }, id, url) { 
            const title = this.title.split('.').shift()
            if(this.type === 'radio') {
                if(target.checked) getBase64FromUrl(url).then(response => this.$emit('selectItem', { id, response, title, url } ))
            } else {
                if(target.checked) getBase64FromUrl(url).then(response => this.$emit('selectItems', { id, response, title, url } ))
                else this.$emit('deleteItem', this.id)
            }
        }
    }
}
</script>
<style scoped>
</style>