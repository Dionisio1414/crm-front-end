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

export default {
    name: 'SimpleField'
}
</script>
<style scoped lang="sass">

</style>