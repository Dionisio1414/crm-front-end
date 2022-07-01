<template>
    <div v-frag>
        <div class="form-row">
            <v-row>
                <v-col cols="12">
                    <div class="label-title">{{ $t('user.addressRegistration') }}</div>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    <div class="select-wrap">
                    <Autocomplete
                        customClass="autocomplete"
                        :modeTypes="modeType"
                        :list="countriesList"
                        :id="settingsAutocomplete.country_id"
                        itemText="title"
                        itemValue="id"
                        placeholder="Выберите страну"
                        :menuProps="{ contentClass: `${settingsAutocomplete.type_country} autocomplete-dropdown`}"
                        :loading="false"
                        :type="settingsAutocomplete.type_country"
                        @updateValue="onCountryValue"
                        @updatePagePaginate="updateCountryPaginate"
                    >
                    </Autocomplete>
                    </div>
                </v-col>
                <v-col cols="4">
                    <div class="select-wrap">
                        <Autocomplete
                            customClass="autocomplete"
                            :modeTypes="modeType"
                            :list="regionsList"
                            :id="settingsAutocomplete.region_id"
                            itemText="title"
                            itemValue="id"
                            placeholder="Выберите регион"
                            :menuProps="{ contentClass: `${settingsAutocomplete.type_region} autocomplete-dropdown autocomplete-dropdown--repeater`}"
                            :loading="settingsAutocomplete.loading_regions"
                            :type="settingsAutocomplete.type_region"
                            :uniq="settingsAutocomplete.uniq"
                            @updateValue="onRegionValue"
                            @updatePagePaginate="updateRegionPaginate"
                        >
                        </Autocomplete>
                    </div>
                </v-col>
                <v-col cols="4">
                    <div class="select-wrap">
                    <Autocomplete
                        customClass="autocomplete"
                        :modeTypes="modeType"
                        :list="citiesList"
                        :id="settingsAutocomplete.city_id"
                        itemText="title"
                        itemValue="id"
                        placeholder="Выберите город"
                        :menuProps="{ contentClass: `${settingsAutocomplete.type_city} autocomplete-dropdown autocomplete-dropdown--repeater`}"
                        :loading="settingsAutocomplete.loading_cities"
                        :type="settingsAutocomplete.type_city"
                        :uniq="settingsAutocomplete.uniq"
                        @updateValue="onCityValue"
                        @updatePagePaginate="updateCityPaginate"
                    >
                    </Autocomplete>
                    </div>
                </v-col>
            </v-row>
        </div>
        <div class="form-row">
            <v-row>
                <v-col cols="6">
                    <div class="form-field">
                        <input 
                            class="field" 
                            type="text" 
                            placeholder="Улица" 
                            v-model="localData.registration_street"
                            @input="updateField($event, 'registration_street')"
                        >
                    </div>
                </v-col>
                <v-col cols="6">
                    <div class="form-field">
                        <input 
                            class="field" 
                            type="text" 
                            placeholder="Номер дома/корпуса" 
                            v-model="localData.registration_number_house"
                            @input="updateField($event, 'registration_number_house')"
                        >
                    </div>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import { COUNTRIES, REGIONS, CITIES } from '@/components/ui/Autocomplete/constants'
import Autocomplete from '@/components/ui/Autocomplete/Autocomplete'
import getUniqueId from '@/helper/getUniqueId' 
import frag from 'vue-frag'

export default {
    name: "PlaceResidence",
    props: { addressData: Object, modeType: String },
    components: { Autocomplete },
    data: () => ({
        localData: {
            registration_country_id: null,
            registration_region_id: null,
            registration_city_id: null,
            registration_street: null,
            registration_number_house: null
        },
        settingsAutocomplete: {
            country_id: null,
            region_id: null,
            city_id: null,
            type_region: REGIONS,
            type_country: COUNTRIES,
            type_city: CITIES,
            uniq: null,
            loading_regions: false,
            loading_cities: false,
        }
    }),
    watch: {
        async addressData(val) {
            console.log('watcher val', val)
            this.settingsAutocomplete.country_id = val.registration_country_id
            this.settingsAutocomplete.region_id = val.registration_region_id
            this.settingsAutocomplete.city_id = val.registration_city_id
            this.localData.registration_street = val.registration_street
            this.localData.registration_number_house = val.registration_number_house
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'countries'
                },
                query: `?country_id=${val.registration_country_id}&selected_id=${val.registration_country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'regions'
                },
                query: `?country_id=${val.registration_country_id}&selected_id=${val.registration_country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'cities'
                },
                query: `?region_id=${val.registration_region_id}&selected_id=${val.registration_region_id}`
            })
            this.localData = { ...val }
            this.$emit('updateData', { ...this.localData })
        }
    },
    computed: { 
        ...mapGetters(['getAddress']),
        countriesList() { return this.getAddress('organizations', 'actual')['countries'] },
        regionsList() { return this.getAddress('organizations', 'actual')['regions'] },
        citiesList() { return this.getAddress('organizations', 'actual')['cities'] }, 
    },
    methods: {
        ...mapActions(['getAddressList', 'getAddressListWithPaginate']),
        async updateCountryPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'countries'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}`, paginate: true, page})
        },
        async updateRegionPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'regions'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}&country_id=${this.contact.legal_country_id}`, paginate: true, page})
        },
        async updateCityPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'cities'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}&region_id=${this.contact.legal_region_id}`, paginate: true, page})
        },
        async onCountryValue(val) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'regions'
            }
            this.settingsAutocomplete.uniq = { key: COUNTRIES, id: getUniqueId() }
            this.settingsAutocomplete.country_id = val.id
            this.settingsAutocomplete.region_id = val.id
            this.localData.registration_country_id = val.id
            this.settingsAutocomplete.loading_regions = true
            await this.getAddressList({ types, query: `?country_id=${val.id}` })
            this.settingsAutocomplete.loading_regions = false
            this.$emit('updateData', { ...this.localData })
        },
        async onRegionValue(val) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'cities'
            }
            this.settingsAutocomplete.uniq = { key: REGIONS, id: getUniqueId() }
            this.settingsAutocomplete.city_id = val.id
            this.localData.registration_region_id = val.id
            this.settingsAutocomplete.loading_cities = true
            await this.getAddressList({ types, query: `?region_id=${val.id}` })
            this.settingsAutocomplete.loading_cities = false
            this.$emit('updateData', { ...this.localData })
        },
        onCityValue(val) {
            this.settingsAutocomplete.uniq = { key: CITIES, id: getUniqueId() }
            this.settingsAutocomplete.city_id = val.id
            this.localData.registration_city_id = val.id
            this.$emit('updateData', { ...this.localData })
        },
        updateField({ target }, type) { 
            this.localData[type] = target.value
            this.$emit('updateData', { ...this.localData })
        }
    },
    async created() {
        console.log('created', this.addressData)
        if(this.modeType === 'change') {
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'countries'
                },
                query: `?country_id=${this.addressData.registration_country_id}&selected_id=${this.addressData.registration_country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'regions'
                },
                query: `?country_id=${this.addressData.registration_country_id}&selected_id=${this.addressData.registration_country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'cities'
                },
                query: `?region_id=${this.addressData.registration_region_id}&selected_id=${this.addressData.registration_region_id}`
            })
            this.localData = { ...this.addressData }
            this.settingsAutocomplete.country_id = this.addressData.registration_country_id
            this.settingsAutocomplete.region_id = this.addressData.registration_region_id
            this.settingsAutocomplete.city_id = this.addressData.registration_city_id
        }
    },
    async mounted() {
        await this.getAddressList({
            types: {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'countries'
            },
            query: ''
        }) 
    },
    directives: {
        frag
    }

}
</script>

<style>

</style>