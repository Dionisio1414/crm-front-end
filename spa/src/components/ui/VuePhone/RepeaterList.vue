<template>
    <div class="vue-list-wrapper">
        <div class="vue-list-item" v-for="(val, index) in valuesArray" :key="index" :class="{'vue-list-item--editable': currentIndex === index}">
            <div class="text">{{ val.field }}</div>
            <div class="actions">
                <button 
                    class="actions-item" 
                    type="button"
                    @click="edit(index)"
                >
                    <SvgSprite
                        :width="17"
                        :height="17"
                        iconId="edit_pencil_icon"
                    />
                </button>
                <button 
                    class="actions-item" 
                    type="button"
                    @click="remove(index)"
                >
                    <SvgSprite
                        :width="12"
                        :height="13"
                        iconId="delete_lines_icon"
                    />
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"

export default {
    name: "RepeaterList",
    props: {
        isValid: Boolean,
        valuesArray: Array 
    },
    components: {
        SvgSprite
    },
    data: () => ({
        repeaterValues: [],
        mode: 'edit',
        currentIndex: null
    }),
    watch: {
        valuesArray() {
            this.currentIndex = null
            this.repeaterValues = this.valuesArray
        },
    },
    methods: {
        edit(index) {
            this.currentIndex = index
            this.$emit('edit', this.repeaterValues[index], index)
        },
        remove(index) {
            this.currentIndex = null
            this.repeaterValues.splice(index, 1)
            this.$emit('remove', this.repeaterValues, index)
        }
    },
    created() {
        this.repeaterValues = this.valuesArray
    }
}
</script>
<style lang="sass" scoped>
    .vue-list
        &-wrapper
            margin: 20px 0
        &-item
            display: flex
            align-items: center
            background: #E3F0FF
            padding: 5px 15px
            border-radius: 10px
            &:not(:last-child)
                margin-bottom: 15px
            .text
                font-size: 13px
                line-height: 1
                margin-right: 15px
                color: #317CCE
            .actions
                display: flex
                align-items: center
                &-item
                    line-height: 1
                    &:not(:last-child)
                        margin-right: 15px
            &--editable
                background: #5893D4
                .text
                    color: #fff
</style>