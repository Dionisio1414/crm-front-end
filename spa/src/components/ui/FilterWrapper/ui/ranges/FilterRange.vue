<template>
    <div>
        <div class="label-title">{{ $t(label) }}</div>
        <div class="form-field form-field-range">
            <input 
                type="text" 
                class="field-number" 
                v-model="range[0]"
            >
            <div class="divider"></div>
            <input 
                type="text" 
                class="field-number"
                v-model="range[1]"
            >
        </div>
        <div class="slider-wrapper">
            <v-range-slider
                class="range"
                hide-details
                :max="list.maxValue"
                :min="list.minValue"
                :color="'#4ECA80'"
                :trackColor="'#E3F0FF'"
                @change="onRange"
                v-model="range"
            >
            </v-range-slider>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
    name: "FilterRange",
    props: {
        label: String,
        list: Object
    },
    data() { 
        return {
            range: Object.values(this.list.data)
        }
    },
    methods: {
        ...mapActions([
            'changeDataFilter'
        ]),
        onRange(value) {
            const payload = {
                ranges: {
                   [this.list.type]: {
                       from: value[0],
                       to: value[1]
                   } 
                },
                resource: this.list.resource
            }
            this.changeDataFilter(payload)
        }
    },
}
</script>
<style scoped lang="sass">
    .slider-wrapper 
        position: relative
        .v-slider
            &--horizontal 
                margin: 0
            &__thumb 
                transform: translateY(-50%) !important
                height: 13px
                width: 13px
                z-index: 1
                border: 2px solid  #E3F0FF!important
            &__thumb-label 
                background: none !important
                border: none !important
                color: #5893D4
                transform: translateX(-50%) !important
                font-size: 14px
                line-height: 14px
            div 
                transform: none
</style>