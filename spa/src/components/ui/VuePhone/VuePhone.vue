<template>
    <div v-frag>
        <div class="vue-phone-wrapper" :class="customPhoneClass">
            <VuePhoneNumberInput 
                v-model="value"
                :default-country-code="currentCountry"
                :preferred-countries="preferredCountries"
                :loader="loader"
                :no-flags="isFlags"
                :error="checkError"
                :border-radius="0"
                :no-example="true"
                :show-code-on-list="true"
                :disabled="disabled"
                :no-validator-state="false"
                :translations="{
                    phoneNumberLabel: phoneLabel,
                }"
                @input="inputHandler"
                @update="inputUpdateHandler"
                @phone-number-blur="inputBlurHandler"
            >   
                <template v-slot:arrow>
                    <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55709e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z" fill="#9DCCFF"/>
                    </svg>
                </template>
            </VuePhoneNumberInput>
            <div class="vue-phone-actions" v-if="isRepeater">
                <button 
                    class="btn btn-edit"
                    type="button" 
                    @click="addPhone" 
                    :disabled="!isValid || value === null || value.length < 1"
                    v-if="mode === 'edit'"
                >
                    <SvgSprite
                        :width="30"
                        :height="30"
                        iconId="join_plus"
                    />
                </button>
                <button 
                    class="btn btn-save"
                    v-else
                    type="button" 
                    @click="savePhone"
                    :disabled="!isValid"
                >
                    <SvgSprite
                        :width="30"
                        :height="30"
                        iconId="save_mark"
                    />
                </button>
            </div>
        </div>
        <RepeaterList 
            v-if="isRepeater"
            :valuesArray="repeaterValues"
            @edit="editHandler"
            @remove="removeHandler"
        >
        </RepeaterList>
    </div>
</template>
<script>
import RepeaterList from "./RepeaterList.vue"
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import VuePhoneNumberInput from 'vue-phone-number-input'
import debounce from 'lodash.debounce'
import httpClient from "@/services/http-client/http-client"

export default {
    name: "VuePhone",
    components: {
        SvgSprite,
        VuePhoneNumberInput,
        RepeaterList
    },
    props: {
        isRepeater: Boolean,
        defaultCountry: String,
        preferredCountries: Array,
        customPhoneClass: [Array, String],
        isFlags: Boolean,
        disabled: Boolean,
        valueField: String,
        repeaterValuesArray: Array,
        isValidFlag: Boolean,
        phoneLabel: String
    },
    data: () => ({
        value: '',
        notFormattedValue: null,
        isValid: null,
        mode: 'edit',
        repeaterValues: [],
        currentCountry: null,
        currentElementIndex: null,
        loader: false,
        phoneData: {
            phone: null,
        },
        initField: false
    }),
    computed: {
        checkError() {
            if(this.value) {
                return !this.isValid && this.value !== null && this.value.length > 0
            } else {
                return false
            }
        }
    },
    watch: {
        isValidFlag(val) { this.isValid = val },
        valueField(val) { this.value = val },
        repeaterValuesArray(val) { this.repeaterValues = val || [] }
    },
    methods: {
        inputHandler: debounce(async function() {
            this.loader = true
            const { data } = await httpClient.post('/user/phone/validate', { data: { phone: this.notFormattedValue } })

            if(data.success || data.success === false) {
                console.log('1')
                this.loader = false
                this.isValid = false
            }

            if(data.phone || data.code) {
                console.log('2')
                this.currentCountry = data.code
                this.loader = false
                this.isValid = true
            }
        }, 300),
        inputUpdateHandler(val) {
            console.log('inputUpdateHandler', val)
            const { isValid, formattedNumber, formatNational, countryCode } = val
            console.log('formattedNumber', formattedNumber, val)
            this.phoneData.phone = formattedNumber ? formattedNumber : val.phoneNumber
            this.currentCountry = countryCode
            this.isValid = isValid
            if(formatNational) {
                this.notFormattedValue = formatNational.match(/[\s{()}]/g) ? formatNational.replace(/\s/g, '').replace(/[{()}]/g, '') : formatNational
            }
            this.$emit('updateInput', this.phoneData.phone, this.isValid)
        },
        addPhone() {
            if(this.isValid) {
                this.repeaterValues.push({ field: this.phoneData.phone, code: this.currentCountry, formatField: this.notFormattedValue })
                this.value = null
                this.isValid = false
                this.notFormattedValue = null
            }
            this.$emit('add', this.repeaterValues)
        },
        savePhone() {
            this.repeaterValues[this.currentElementIndex] = {
                code: this.currentCountry,
                field: this.phoneData.phone,
                formatField: this.notFormattedValue
            }
            this.repeaterValues = this.repeaterValues.slice()
            this.mode = 'edit'
            this.value = null
            this.$emit('save', this.repeaterValues)
        },
        editHandler({ code, formatField, field }, index) {
            console.log(code, formatField, field)
            this.currentCountry = code
            this.value = formatField || field
            this.isValid = true
            this.currentElementIndex = index
            this.mode = 'save'
        },
        removeHandler(arr, index) {
            this.repeaterValues = arr
            this.value = null
            this.mode = 'edit'
            this.$emit('remove', this.repeaterValues, index)
        },
        inputBlurHandler() {
            this.$emit('blurInput', this.notFormattedValue)
        }
    },
    created() { this.currentCountry = this.defaultCountry },
    mounted() {
        console.log('mounted', this.repeaterValuesArray)
        this.repeaterValues = this.repeaterValuesArray || []
        this.value = this.valueField
    }
}
</script>
<style lang="sass">
    .vue-phone
        &-wrapper
            display: flex
            align-items: center
            &.without-select
                .select-country-container
                    display: none
            &.phone
                &-step
                    position: relative
                    z-index: 99
                    .vue-phone-number-input
                        .select-country-container
                            margin-right: 1px
                            .country-selector
                                margin-right: 0
                                height: auto
                                min-height: 0
                                &.is-focused
                                    .country-selector__input
                                        box-shadow: none!important
                                &.is-disabled
                                    opacity: .5
                                    pointer-events: none
                                &__label
                                    display: none
                                &__input
                                    background-color: #F4F9FF
                                    font-size: 20px
                                    padding-top: 0!important
                                    padding-bottom: 0!important
                                    height: 50px
                                    min-height: 0
                                    border: 1px solid transparent
                                    border-right: none!important
                                    border-radius: 10px 0px 0px 10px!important
                                    color: #7E7E7E
                                &__list
                                    min-width: 0
                                    border-radius: 0 0 10px 10px!important
                                    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1)!important
                                    max-height: 125px!important
                                    &::-webkit-scrollbar
                                        width: 2px
                                    &::-webkit-scrollbar-track
                                        background: transparent
                                    &::-webkit-scrollbar-thumb
                                        background: #9DCCFF
                                    &__item
                                        &__flag
                                            &-container
                                                margin-right: 5px
                                        &__calling-code
                                            order: 1
                                        &__flag-container
                                            order: 3
                                        .dots-text
                                            order: 2
                                &__toggle
                                    right: 12px
                                &.has-list-open
                                    .country-selector__input
                                        border-radius: 10px 0px 0px 0px!important
                        .input-tel 
                            height: auto
                            min-height: 0
                            &::before
                                content: ""
                                background-image: url('~@/assets/img/invalid.svg')
                                position: absolute
                                top: 50%
                                right: 15px
                                transform: translateY(-50%)
                                width: 16px
                                height: 16px
                                opacity: 0
                                transition: opacity .4s ease-in-out
                                z-index: 1
                            &::after
                                content: ""
                                background-image: url('~@/assets/img/gal.svg')
                                position: absolute
                                top: 50%
                                right: 15px
                                transform: translateY(-50%)
                                width: 16px
                                height: 16px
                                opacity: 0
                                transition: opacity .4s ease-in-out
                                z-index: 1
                            &.has-error
                                .input-tel__input
                                    border-color: #ff8900!important
                                    padding-right: 35px
                                &::before
                                    opacity: 1
                            &.is-valid
                                &::after
                                    opacity: 1
                            &.is-focused
                                .input-tel__input
                                    box-shadow: none!important
                            &.is-disabled
                                opacity: .5
                                pointer-events: none  
                                .input-tel__input
                                    background-color: #F4F9FF
                                    border-color: #F4F9FF                     
                            &__label
                                display: none
                            &__input
                                background-color: #F4F9FF
                                font-size: 20px
                                border: 1px solid transparent
                                border-left: none
                                border-radius: 0 10px 10px 0!important
                                padding-top: 0!important
                                min-height: 0
                                height: 50px
                                color: #5893D4
                                &::placeholder
                                    font-size: 18px
                                    font-weight: normal
                                    line-height: 1
                                    color: #9DCCFF
                &-default
                    .vue-phone-number-input
                        .input-tel
                            height: 20px
                            min-height: 20px
                            &.is-focused
                                .input-tel__input
                                    box-shadow: none!important
                            &.is-valid
                                .input-tel__input
                                    border-color: #9DCCFF!important
                            &.has-error
                                .input-tel__input
                                    border-color: #FF9D2B!important
                            &.is-disabled
                                .input-tel__input
                                    background-color: transparent
                                    border: none
                                    color: #7E7E7E!important
                            &__input
                                font-size: 14px
                                line-height: 1
                                background-color: transparent
                                border-top: none
                                border-left: none
                                border-right: none
                                border-color: #9DCCFF
                                padding: 0 0 5px 0!important
                                height: 25px
                                min-height: 25px
                                color: #7e7e7e
                                &::placeholder
                                    font-size: 14px
                                    font-weight: normal
                                    line-height: 1
                                    color: rgba(#7E7E7E, .5)
                            &__loader
                                height: 1px
                                bottom: -4px
                            &__label
                                display: none
                        .select-country-container
                            flex: 0 1 30%
                            width: auto
                            min-width: 40px
                            max-width: 100%
                            margin-right: 15px
                            .country-selector
                                min-height: 20px
                                height: 20px
                                &.is-valid
                                    .country-selector__input
                                        border-color: #9DCCFF!important
                                &__list
                                    &__item
                                        .dots-text
                                            display: none
                                            
                                &__input
                                    font-size: 14px
                                    line-height: 1
                                    background-color: transparent
                                    border-top: none
                                    border-left: none
                                    border-right: none
                                    border-bottom: 1px solid #9DCCFF
                                    height: 25px
                                    min-height: 25px
                                    padding: 0 0 5px 0
                                    box-shadow: none!important
                                    color: #7e7e7e
                                &__toggle
                                    right: -5px
                                    &__arrow
                                        .arrow
                                            fill: #9DCCFF
                                &__label
                                    display: none
                                &__list
                                    min-width: 0
                                    border-radius: 0 0 10px 10px!important
                                    &::-webkit-scrollbar
                                        width: 2px
                                    &::-webkit-scrollbar-track
                                        background: transparent
                                    &::-webkit-scrollbar-thumb
                                        background: #9DCCFF
        &-actions
            display: flex
            align-items: center
            margin-left: 15px
            .btn
                line-height: 1
                &:disabled
                    opacity: .5
                    pointer-events: none

</style>