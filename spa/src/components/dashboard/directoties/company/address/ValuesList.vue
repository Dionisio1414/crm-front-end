<template>
    <div class="values-list">
        <template v-for="(item, index) in list">
            <div class="value-item" 
                :key="index" 
                :class="{'value-item--editable': editableElemIndex === index}" 
                v-if="item.phone === null && item.email === null"
            >
                <span>
                    {{ item.country_title }},
                    {{ item.region_title }},
                    {{ item.city_title }}, 
                    {{ item.city }}
                    {{ item.number_house }}
                </span>
                <svg @click="editValue(index)" class="edit" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 3.77778L13.2222 1L2.11111 12.1111L1 16L4.88889 14.8889L16 3.77778ZM11 3.22222L13.7778 6L11 3.22222ZM2.11111 12.1111L4.88889 14.8889L2.11111 12.1111Z" stroke="#BBDBFE" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg @click="deleteValue(index)" class="close" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"/>
                </svg>
            </div>
        </template>
    </div>
</template>
<script>
export default {
    name: "ValuesList",
    props: {
        list: Array
    },
    data: () => ({
        editableElemIndex: null,
    }),
    methods: {
        deleteValue(index) { 
            console.log(index)
            this.editableElemIndex = null
            this.$emit('delete', index)
        },
        editValue(index) {
            this.editableElemIndex = index
            this.$emit('edit', index)
        }
    },
    watch: {

        list(val) {
            console.log('list watch')
            if(val) this.editableElemIndex = null
        }

    }
}
</script>
<style lang="sass">

</style>