<template>
    <div :style="{opacity: loader ? '.5' : '1', pointerEvents: loader ? 'none' : 'visible'}">
        <v-row>
            <v-col cols="4" class="pt-0">
                <div class="item-name">
                    <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.secondForm.physicalAddress') }}</div>
                    <!-- <v-autocomplete
                        v-model="fact_adress.country_id"
                        :items="countriesList"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.country')"
                        @change="onChangeFactAddress($event, 'country_id')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        :modeTypes="mode_type"
                        :list="countriesList"
                        :id="settingsAutocomplete.country_id"
                        :readonly="mode === 'view'" 
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
            <v-col cols="4" class="pt-0">
                <div class="item-name">
                    <!-- <v-autocomplete
                        v-model="fact_adress.region_id"
                        :items="citiesList.regiones"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.area')"
                        @change="onChangeFactAddress($event, 'region_id')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        :modeTypes="mode_type"
                        :list="regionsList"
                        :id="settingsAutocomplete.region_id"
                        :readonly="mode === 'view'" 
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
            <v-col cols="4" class="pt-0">
                <div class="item-name">
                    <!-- <v-autocomplete
                        v-model="fact_adress.city_id"
                        :items="citiesList.cities"
                        item-text="title"
                        item-value="id"
                        hide-selected
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.city')"
                        @change="onChangeFactAddress($event, 'city_id')"
                        return-object
                    ></v-autocomplete> -->
                    <Autocomplete
                        customClass="autocomplete"
                        :modeTypes="mode_type"
                        :list="citiesList"
                        :id="settingsAutocomplete.city_id"
                        :readonly="mode === 'view'" 
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
            <v-col cols="8" class="pb-0">
                <div class="item-name">
                    <input 
                        type="text" 
                        name="organization_street" 
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.street')" 
                        v-model="fact_adress.city">
                </div>
            </v-col>
            <v-col cols="4" class="pb-0">
                <div class="item-name">
                    <input 
                        type="text" 
                        name="organization_house" 
                        :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.numberOfBuild')" 
                        v-model="fact_adress.number_house">
                </div>
            </v-col>
        </v-row>
        <div class="form-actions pt-0">
            <v-row align="start">
                <v-col cols="8">
                    <ValuesList
                        v-if="copy"
                        @edit="editValue"
                        @delete="deleteValue"
                        :list="copy"
                    />
                </v-col>
                <v-col cols="4">
                    <button
                        type="button"
                        v-if="mode === 'add'"
                        class="base-button base-button--lighten--transparent"
                        :disabled="!checkFields"
                        @click="addValue" 
                    >
                        {{ $t('page.addButton') }}
                    </button>
                    <button
                        type="button"
                        v-if="mode === 'edit'"
                        class="base-button base-button--lighten--transparent" 
                        :disabled="!checkFields"
                        @click="saveValue" 
                    >
                        {{ $t('page.saveButton') }}
                    </button>
                </v-col>
            </v-row>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex"
import ValuesList from "@/components/dashboard/directoties/company/address/ValuesList"
import Autocomplete from '@/components/ui/Autocomplete/Autocomplete'
import getUniqueId from '@/helper/getUniqueId' 
import { COUNTRIES, REGIONS, CITIES } from '@/components/ui/Autocomplete/constants'

export default {
    name: "ActualAddress",
    props: {
        addressData: Array,
        modeType: String,
        doubleDataObject: Object
    },
    components: {
        ValuesList,
        Autocomplete,
    },
    data: () => ({
        mode: "add",
        copyData: [],
        transformObject: [],
        copy: null,
        componentKey: 1,
        loader: false,
        mode_type: null,
        editFlag: false,
        fact_adress: {
            phone: null,
            email: null,
            country_id: null,
            region_id: null,
            city_id: null,
            city: null,
            number_house: null,
            city_title: null,
            country_title: null,
            region_title: null
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
    computed: {
        ...mapGetters([
            'getLists',
            'getAddress'
        ]),
        countriesList() { return this.getAddress('organizations', 'actual')['countries']  },
        regionsList() { return this.getAddress('organizations', 'actual')['regions'] },
        citiesList() { return this.getAddress('organizations', 'actual')['cities'] },
        checkValidate() {
            if(Object.keys(this.copyData).length) return this.copyData.country !== null && this.copyData.region !== null && this.copyData.cities !== null && this.copyData.city !== null && this.copyData.number_house !== null
            return false
        },
        getCopyData() {
            return this.addressData
        },
        checkFields() {
            return Object.values(this.fact_adress).some(item => !!item)
        }
    },
    methods: {
        ...mapActions([
            'getAddressListWithPaginate',
            'getAddressList'
        ]),
        onChangeFactAddress(obj, value) { this.fact_adress[value] = obj.id },
        addValue() {
            this.mode_type = 'change'
            this.$emit('addValue', this.fact_adress)
            this.settingsAutocomplete.country_id = null
            this.settingsAutocomplete.region_id = null
            this.settingsAutocomplete.city_id = null
            this.fact_adress = {
                phone: null,
                email: null,
                country_id: null,
                region_id: null,
                city_id: null,
                city: null,
                number_house: null,
                city_title: null,
                country_title: null,
                region_title: null
            }
        },
        deleteValue(index) { 
            this.$emit('deleteValue', index)
            this.mode = 'add'
            this.editableElemIndex = null
            this.settingsAutocomplete.country_id = null
            this.settingsAutocomplete.region_id = null
            this.settingsAutocomplete.city_id = null
            this.fact_adress = {
                phone: null,
                email: null,
                country_id: null,
                region_id: null,
                city_id: null,
                city: null, 
                number_house: null
            }
        },
        editValue(index) {
            this.editableElemIndex = index
            const newObj = { 
                ...this.settingsAutocomplete, 
                country_id: this.addressData[this.editableElemIndex].country_id, 
                region_id: this.addressData[this.editableElemIndex].region_id, 
                city_id: this.addressData[this.editableElemIndex].city_id
            }
            this.mode = "edit"
            this.fact_adress = { ...this.addressData[this.editableElemIndex ] }
            this.settingsAutocomplete = newObj
            this.editFlag = true
        },
        saveValue() {
            console.log('save')
            this.addressData[this.editableElemIndex] = { ...this.fact_adress }
            this.copyData[this.editableElemIndex] = { ...this.fact_adress }
            this.$emit('saveValues', this.addressData)
            this.settingsAutocomplete.country_id = null
            this.settingsAutocomplete.region_id = null
            this.settingsAutocomplete.city_id = null
            this.mode = 'add'
            this.fact_adress = {
                phone: null,
                email: null,
                country_id: null,
                region_id: null,
                city_id: null,
                city: null, 
                number_house: null
            }

            this.copy = [ ...this.addressData ]
        }, 
        transformData(obj) {
            if(Array.isArray(obj)) {

                return obj.map(element => {
                    console.log('el', element)
                    if(!element.email && !element.phone) {
                        element.country_title = this.countriesList.find(item => item.id === element.country_id)?.title
                        element.region_title = this.regionsList.find(item => item.id === element.region_id)?.title
                        element.city_title = this.citiesList.find(item => item.id === element.city_id)?.title 
                        console.log('element', element)
                        return element
                    } else {
                        return element
                    }
                })
            } else {
                for(const key in obj) {
                    console.log(key, obj)
                    if(!obj['email'] && !obj['phone']) {
                        obj['country_title'] = this.countriesList.find(item => item.id === obj['country_id'])?.title
                        obj['region_title'] = this.regionsList.find(item => item.id === obj['region_id'])?.title
                        obj['city_title'] = this.citiesList.find(item => item.id === obj['city_id'])?.title
                        return [obj]
                    } else {
                        return [obj]
                    }
                }
            }
        },
        async onCountryValue(val) {
            console.log('onCountryValue', val)
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'regions'
            }
            this.settingsAutocomplete.uniq = { key: COUNTRIES, id: getUniqueId() }
            this.settingsAutocomplete.country_id = val.id
            this.settingsAutocomplete.region_id = val.id
            this.fact_adress.country_id = val.id
            this.fact_adress.country_title = val.title
            this.settingsAutocomplete.loading_regions = true
            await this.getAddressList({ types, query: `?country_id=${val.id}` })
            this.settingsAutocomplete.loading_regions = false
            console.log(val)
        },
        async onRegionValue(val) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'cities'
            }
            this.settingsAutocomplete.uniq = { key: REGIONS, id: getUniqueId() }
            this.settingsAutocomplete.city_id = val.id
            this.fact_adress.region_id = val.id
            this.fact_adress.region_title = val.title
            this.settingsAutocomplete.loading_cities = true
            await this.getAddressList({ types, query: `?region_id=${val.id}` })
            this.settingsAutocomplete.loading_cities = false
            console.log(val)
        },
        onCityValue(val) {
            this.settingsAutocomplete.uniq = { key: CITIES, id: getUniqueId() }
            this.settingsAutocomplete.city_id = val.id
            this.fact_adress.city_id = val.id
            this.fact_adress.city_title = val.title
            console.log(val)
        },
        async updateCountryPaginate(page) {
            console.log(page)
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
            await this.getAddressListWithPaginate({types, query: `?page=${page}&country_id=${this.settingsAutocomplete.country_id}`, paginate: true, page})
        },
        async updateCityPaginate(page) {
            const types = {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'cities'
            }
            await this.getAddressListWithPaginate({types, query: `?page=${page}&region_id=${this.settingsAutocomplete.region_id}`, paginate: true, page})
        },
    },
    watch: {
        async editFlag(val) {
            console.log('val val val', val)
            this.mode_type = 'change'
            this.loader = true
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'countries'
                },
                query: `?country_id=${this.settingsAutocomplete.country_id}&selected_id=${this.settingsAutocomplete.country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'regions'
                },
                query: `?country_id=${this.settingsAutocomplete.country_id}&selected_id=${this.settingsAutocomplete.country_id}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'cities'
                },
                query: `?region_id=${this.settingsAutocomplete.region_id}&selected_id=${this.settingsAutocomplete.region_id}`
            })
            this.editFlag = false
            this.loader = false
        },
        async addressData(val) {
            console.log('watcher', val)
            // this.loader = true
            // await this.getAddressList({
            //     types: {
            //         type: 'organizations',
            //         type_address: 'actual',
            //         type_list: 'countries'
            //     },
            //     query: `?country_id=${val.country}&selected_id=${val.country}`
            // })
            // await this.getAddressList({
            //     types: {
            //         type: 'organizations',
            //         type_address: 'actual',
            //         type_list: 'regions'
            //     },
            //     query: `?country_id=${val.country}&selected_id=${val.country}`
            // })
            // await this.getAddressList({
            //     types: {
            //         type: 'organizations',
            //         type_address: 'actual',
            //         type_list: 'cities'
            //     },
            //     query: `?region_id=${val.region}&selected_id=${val.region}`
            // })
            // this.loader = false
            this.copy = val
        },
        async doubleDataObject(val) {
            this.mode_type = 'change'
            this.loader = true
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'countries'
                },
                query: `?country_id=${val.country}&selected_id=${val.country}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'regions'
                },
                query: `?country_id=${val.country}&selected_id=${val.country}`
            })
            await this.getAddressList({
                types: {
                    type: 'organizations',
                    type_address: 'actual',
                    type_list: 'cities'
                },
                query: `?region_id=${val.region}&selected_id=${val.region}`
            })
            this.loader = false
            const newObj = { ...this.settingsAutocomplete, country_id: val.country, region_id: val.region, city_id: val.cities }
            this.settingsAutocomplete = { ...newObj }
            this.fact_adress = {
                phone: null,
                email: null,
                country_id: val.country,
                region_id: val.region,
                city_id: val.cities,
                city: val.city,
                number_house: val.number_house,
                country_title: null,
                region_title: null,
                city_title: null
            }

            this.transformData(this.fact_adress)
        },
    },
    created() {
        this.copy = [ ...this.addressData ]
        if(Object.keys(this.doubleDataObject).length) {
            this.fact_adress = { 
                phone: this.doubleDataObject.phone,
                email: this.doubleDataObject.email,
                city: this.doubleDataObject.city,
                country_id: this.doubleDataObject.country.id,
                region_id: this.doubleDataObject.region.id,
                city_id: this.doubleDataObject.cities.id,
                number_house: this.doubleDataObject.number_house
            }
            this.settingsAutocomplete.country_id = this.doubleDataObject.country.id
            this.settingsAutocomplete.region_id = this.doubleDataObject.region.id
            this.settingsAutocomplete.city_id = this.doubleDataObject.cities.id
        }
    },
    async mounted() {
        const values = {
            types: {
                type: 'organizations',
                type_address: 'actual',
                type_list: 'countries'
            },
            query: ''
        }
        this.mode_type = this.modeType
        await this.getAddressList({ ...values })
    }
}
</script>    
<style scoped lang="sass">
    .form-actions 
        padding: 0
</style>