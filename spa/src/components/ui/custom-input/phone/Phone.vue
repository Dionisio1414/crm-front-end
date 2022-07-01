<template>
    <vue-tel-input v-model="phone" 
        :key="componentKey"
        :enabledCountryCode="true" 
        :dropdownOptions="{disabledDialCode: true, tabindex: 0}" 
        :enabledFlags="false" 
        mode='national' 
        :defaultCountry='defaultCountry' 
        :dynamicPlaceholder="true" 
        :validCharactersOnly="true"
        wrapperClasses="mobiles">
    </vue-tel-input>
</template>
<script>
import { VueTelInput } from 'vue-tel-input'

export default {
    name: 'Phone'
}
</script>
<style scoped lang="sass">

</style>