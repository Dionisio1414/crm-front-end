<template>
    <phone-mask-input
        v-model="value"
        :autoDetectCountry="false"
        :defaultCountry="country"
        @onValidate="onValidate"
        @input="inputHandler"
        @onBlur="onBlur"
        @onFocus="onFocus"
        :wrapperClass="inputClass"
        :inputClass="isValidProperty"
        :placeholder="placeholderInput"
        :disabled="disabledField"
        :key="componentKey"
        flagClass="flag-example"
    />
</template>
<script>
import PhoneMaskInput from  "vue-phone-mask-input"
import httpClient from "@/services/http-client/http-client"
import debounce from 'lodash.debounce'

export default {
    name: "VueSinglePhone",
    components: {
        PhoneMaskInput
    },
    props: {
        phone: String,
        defaultCountry: String,
        inputClass: [Array, String],
        placeholderInput: String,
        errorClass: String,
        disabledField: Boolean
    },
    data: () => ({
        value: null,
        isValid: true,
        country: null,
        initState: false,
        componentKey: 0
    }),
    watch: {
        phone(val) {
            console.log('watch val', val)
            this.value = val
            this.componentKey += 1
        }
    },
    computed: {
        isValidProperty() {
            return !this.isValid ? this.errorClass : ''
        }
    },
    methods: {
        onValidate({ isValidByLibPhoneNumberJs }) {
            this.isValid = isValidByLibPhoneNumberJs
            console.log('on validate', isValidByLibPhoneNumberJs)
        },
        inputHandler: debounce(async function(number) {
            console.log(number)
            if(number.length > 1) this.initState = true
            if(this.initState) {
                console.log('init state true')
                const { data } = await httpClient.post('/user/phone/validate', { data: { phone: number.slice(1) } })
                console.log('data', data)
                if(data.success || data.success === false) {
                    console.log('data hasOwnProperty succes', data, number)
                    this.isValid = false
                    this.$emit('updateInput', number)
                } else {
                    console.log('data hasOwnProperty phone/code', data.phone, data.code)
                    this.isValid = true
                    this.$emit('input', { phone: data.phone, code: data.code.toLowerCase() })
                }
            } else {
                console.log('init state false')
                this.value = this.phone
                this.initState = true
            }
        }, 300),
        onBlur(val) {
            console.log('onBlur', val)
        },
        onFocus(val) {
            console.log('onFocus', val)
            this.isValid = true
        }
    },
    created() {

    },
    mounted() {
        console.log('mounted', this.phone)
        this.value = this.phone
        this.country = this.defaultCountry
    }
}
</script>
<style scoped lang="sass">
    .wrapper
        width: 100%
        input
            min-width: 200px
            &:disabled
                border-bottom: 1px solid transparent!important
                color: #7E7E7E
        &-error
            &-field
                border-bottom: 1px solid #FF9D2B!important
</style>
