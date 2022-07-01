<template>
    <v-row>
        <v-col cols="4">
            <div class="item-name">
                <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.secondForm.checkBoxTitle') }}</div>
                <template v-if="modeTypes !=='change'">
                    <!-- <v-autocomplete
                        :items="countriesList"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.country')"
                        @change="onChangeMainSelect($event, 'legal_country_id', 'contact', 'country')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        modeTypes="create"
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
                </template>
                <template v-else>
                    <!-- <v-autocomplete
                        v-model="localData.contact.legal_country_id"
                        :items="countriesList"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.country')"
                        @change="onChangeMainSelect($event, 'legal_country_id', 'contact', 'country')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        modeTypes="change"
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
                </template>
            </div>
        </v-col>
        <v-col cols="4">
            <div class="item-name">
                <template v-if="modeTypes !=='change'">
                    <!-- <v-autocomplete
                        :items="citiesList.regiones"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.area')"
                        @change="onChangeMainSelect($event, 'legal_city_id', 'contact', 'region')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        modeTypes="create"
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
                </template>
                <template v-else>
                    <!-- <v-autocomplete
                        v-model="localData.contact.legal_city_id"
                        :items="citiesList.regiones"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.area')"
                        @change="onChangeMainSelect($event, 'legal_city_id', 'contact', 'region')"
                        return-object
                    ></v-autocomplete> -->
                        <Autocomplete
                        customClass="autocomplete"
                        modeTypes="change"
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
                </template>
            </div>
        </v-col>
        <v-col cols="4">
            <div class="item-name">
                <template v-if="modeTypes !=='change'">
                    <!-- <v-autocomplete
                        :items="citiesList.cities"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.city')"
                        @change="onChangeMainSelect($event, 'legal_region_id', 'contact', 'city')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        modeTypes="create"
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
                </template>
                <template v-else>
                    <!-- <v-autocomplete
                        v-model="localData.contact.legal_region_id"
                        :items="citiesList.cities"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.city')"
                        @change="onChangeMainSelect($event, 'legal_region_id', 'contact', 'city')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        modeTypes="change"
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
                </template>
            </div>
        </v-col>
        <v-col cols="8">
            <div class="item-name">
                <input 
                    type="text" 
                    name="legal_city" 
                    :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.street')" 
                    v-model="contact.legal_city"
                    @input="changeField"
                >
            </div>
        </v-col>
        <v-col cols="4">
            <div class="item-name">
                <input 
                    type="text" 
                    name="legal_number_house" 
                    :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.numberOfBuild')" 
                    v-model="contact.legal_number_house"
                    @input="changeField"
                >
            </div>
        </v-col>
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import { COUNTRIES, REGIONS, CITIES } from '@/components/ui/Autocomplete/constants'
import Autocomplete from '@/components/ui/Autocomplete/Autocomplete'
import getUniqueId from '@/helper/getUniqueId' 

export default {
    name: "LegalAddress",
    props: {
        data: Object,
        modeTypes: String
    },
    components: { Autocomplete },
    data: () => ({
        contact: {
            legal_country_id: null,
            legal_region_id: null,
            legal_city_id: null,
            legal_city: null,
            legal_number_house: null,
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
        async data({ legal_country_id, legal_region_id, legal_city_id, legal_city, legal_number_house }) {
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'legal',
                    type_list: 'countries'
                },
                query: `?country_id=${legal_country_id}&selected_id=${legal_country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'legal',
                    type_list: 'regions'
                },
                query: `?country_id=${legal_country_id}&selected_id=${legal_country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'legal',
                    type_list: 'cities'
                },
                query: `?region_id=${legal_region_id}&selected_id=${legal_region_id}`
            })
            this.contact = { legal_country_id, legal_region_id, legal_city_id, legal_city, legal_number_house }
            this.settingsAutocomplete = { 
                ...this.settingsAutocomplete,
                country_id: legal_country_id,
                region_id: legal_region_id,
                city_id: legal_city_id,
            }
        }
    },
    computed: {
        ...mapGetters(['getAddress']),
        countriesList() { return this.getAddress('organizations', 'legal')['countries']  },
        regionsList() { return this.getAddress('organizations', 'legal')['regions'] },
        citiesList() { return this.getAddress('organizations', 'legal')['cities'] },
    },
    methods: {
        ...mapActions(['getAddressList', 'getAddressListWithPaginate']),
        async updateCountryPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'countries'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}`, paginate: true, page})
        },
        async updateRegionPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'regions'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}&country_id=${this.contact.legal_country_id}`, paginate: true, page})
        },
        async updateCityPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'cities'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}&region_id=${this.contact.legal_region_id}`, paginate: true, page})
        },
        async onCountryValue(val) {
            const types = {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'regions'
            }
            this.settingsAutocomplete.uniq = { key: COUNTRIES, id: getUniqueId() }
            this.settingsAutocomplete.country_id = val.id
            this.settingsAutocomplete.region_id = val.id
            this.contact.legal_country_id = val.id
            this.settingsAutocomplete.loading_regions = true
            await this.getAddressList({ types, query: `?country_id=${val.id}` })
            this.settingsAutocomplete.loading_regions = false
            this.$emit('updateData', this.contact)
            console.log(val)
        },
        async onRegionValue(val) {
            const types = {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'cities'
            }
            this.settingsAutocomplete.uniq = { key: REGIONS, id: getUniqueId() }
            this.settingsAutocomplete.city_id = val.id
            this.contact.legal_region_id = val.id
            this.settingsAutocomplete.loading_cities = true
            await this.getAddressList({ types, query: `?region_id=${val.id}` })
            this.settingsAutocomplete.loading_cities = false
            this.$emit('updateData', this.contact)
            console.log(val)
        },
        onCityValue(val) {
            this.settingsAutocomplete.uniq = { key: CITIES, id: getUniqueId() }
            this.settingsAutocomplete.city_id = val.id
            this.contact.legal_city_id = val.id
            this.$emit('updateData', this.contact)
            console.log(val)
        },
        changeField() { this.$emit('updateData', this.contact) }
    },
    created() {
        const {legal_country_id, legal_region_id, legal_city_id, legal_city, legal_number_house} = this.data
        this.contact = { legal_country_id, legal_region_id, legal_city_id, legal_city, legal_number_house }
        this.settingsAutocomplete.country_id = legal_country_id
        this.settingsAutocomplete.region_id = legal_region_id
        this.settingsAutocomplete.city_id = legal_city_id
    },
    async mounted() {
        const values = {
            types: {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'countries'
            },
            query: `?country_id=${this.settingsAutocomplete.country_id}&selected_id=${this.settingsAutocomplete.country_id}`
        }
        const regionsValues = {
            types: {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'regions'
            },
            query: `?country_id=${this.settingsAutocomplete.country_id}&selected_id=${this.settingsAutocomplete.country_id}`
        }
        const cityValues = {
            types: {
                type: 'organizations',
                type_address: 'legal',
                type_list: 'cities'
            },
            query: `?region_id=${this.settingsAutocomplete.region_id}&selected_id=${this.settingsAutocomplete.region_id}`
        }
        if(this.modeTypes !== 'change') {
            await this.getAddressList({ ...values })
        } else {
            await this.getAddressList({ ...values })
            await this.getAddressList({ ...regionsValues })
            await this.getAddressList({ ...cityValues })
        }
    }
}
</script>

<style>

</style>