<template>
  <v-col cols="6" :class="customClass">
    <div>
      <div class="label-title">{{ title }}</div>
      <v-row>
        <v-col cols="4">
          <Autocomplete
              customClass="autocomplete"
              :modeTypes="modeType"
              :list="countriesList"
              :id="address.country && address.country.id"
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
        </v-col>
        <v-col cols="4">
          <Autocomplete
              customClass="autocomplete"
              :modeTypes="modeType"
              :list="regionsList"
              :id="address.region && address.region.id"
              itemText="title"
              itemValue="id"
              placeholder="Выберите регион"
              :menuProps="{ contentClass: `${settingsAutocomplete.type_region} autocomplete-dropdown autocomplete-dropdown--repeater`}"
              :loading="settingsAutocomplete.loading_regions"
              :type="settingsAutocomplete.type_region"
              :uniq="uniq"
              @updateValue="onRegionValue"
              @updatePagePaginate="updateRegionPaginate"
          >
          </Autocomplete>
        </v-col>
        <v-col cols="4">
          <Autocomplete
              customClass="autocomplete"
              :modeTypes="modeType"
              :list="citiesList"
              :id="address.city && address.city.id"
              itemText="title"
              itemValue="id"
              placeholder="Выберите город"
              :menuProps="{ contentClass: `${settingsAutocomplete.type_city} autocomplete-dropdown autocomplete-dropdown--repeater`}"
              :loading="settingsAutocomplete.loading_cities"
              :type="settingsAutocomplete.type_city"
              :uniq="uniq"
              @updateValue="onCityValue"
              @updatePagePaginate="updateCitiesPaginate"
          >
          </Autocomplete>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          <div class="input-name">
            <input
                type="text"
                v-model="address.street"
                @change="onSetData"
                :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.street')"
            >
          </div>
        </v-col>
        <v-col cols="6">
          <div class="input-name">
            <input
                type="text"
                v-model="address.house"
                @change="onSetData"
                :placeholder="$t('page.suppliers.modal.createSupplier.addressForm.numberOfBuild')"
            >
          </div>
        </v-col>
      </v-row>
      <v-row v-if="isCheckBox">
        <v-col cols="12">
          <div class="checkbox-wrapper foreignCompany">
            <div class="checkbox">
              <label class="checkbox-label">
                <input v-model="foreignCompany" @change="onChangeCheckBox" type="checkbox" value="">
                <span class="checkbox-text"></span>
                <span class="text">{{ checkBoxText }}</span>
              </label>
            </div>
          </div>
        </v-col>
      </v-row>
      <slot></slot>
    </div>
  </v-col>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import Autocomplete from '@/components/ui/Autocomplete/Autocomplete';
// constants
import { COUNTRIES, REGIONS, CITIES } from '@/components/ui/Autocomplete/constants';
// helpers
import getUniqueId from "@/helper/getUniqueId";
import getAddressItem from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/helper/getAddressItem";
import {MODE_TYPES} from "@/constants/constants";

export default {
 name: "AddressForm",
  components: {
    Autocomplete
  },
  props: {
    //title: String,
    title: String,
    countries: Array,
    isCheckBox: Boolean,
    doubleData: Object,
    isPhysicalAddress: Boolean,
    physicalAddress: Object,
    getListLength: Number,
    isDataSafe: Boolean,
    checkBoxText: String,
    getCopyData: Object,
    defaultCountries: Object,
    editData: Object,
    customClass: String,
    types: Object
  },
  watch: {
    async physicalAddress(val) {
      this.modeType = MODE_TYPES.VIEW
      if (!this.isDataSafe) {
        const { country, region, city } = val;
        if (country) {
          this.types.type_list = 'regions';
          await this.getAddressList({types: this.types, query: `?country_id=${country.id}`})
        }
        if (region) {
          this.types.type_list = 'cities';
          await this.getAddressList({types: this.types, query: `?region_id=${region.id}`})
        }

        this.settingsAutocomplete.country_id = country
        this.settingsAutocomplete.region_id = region
        this.settingsAutocomplete.city_id = city
        this.address = {...val};
      }
    },
    getListLength(val, oldVal) {
      if (val !== oldVal) {
        this.address = {
          country: null,
          region: null,
          city: null,
          street: '',
          house: '',
        }

        this.modeType = '';
        this.onSetData()
      }
    },
    async doubleData() {
      const { country, region, city } = this.doubleData;
      this.modeType = MODE_TYPES.VIEW
      if (country) {
        this.types.type_list = 'regions';
        await this.getAddressList({types: this.types, query: `?country_id=${country.id}`})
      }
      if (region) {
        this.types.type_list = 'cities';
        await this.getAddressList({types: this.types, query: `?region_id=${region.id}`})
      }

      this.settingsAutocomplete.country_id = country
      this.settingsAutocomplete.region_id = region
      this.settingsAutocomplete.city_id = city
      this.address = this.doubleData;

      this.onSetData()
    },
  },
  computed: {
    ...mapGetters(['getAddress']),
    countriesList() { return this.getAddress(this.types.type, this.types.type_address)['countries'] },
    regionsList() { return this.getAddress(this.types.type, this.types.type_address)['regions'] },
    citiesList() { return this.getAddress(this.types.type, this.types.type_address)['cities'] }
  },
  data: () => ({
    foreignCompany: false,
    address: {
      country: null,
      region: null,
      city: null,
      street: '',
      house: '',
    },
    dialog: false,
    id: 'locality',
    isListPopup: false,
    listTitle: null,
    uniq: null,
    localFormData: {
      is_editable: true,
      is_default: false,
      cells: {
        country: "",
        title_city: "",
        title_region: ""
      }
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
      loading_cities: false
    }
  }),
  methods: {
    ...mapActions(['fetchLists', 'fetchList', 'createClassifiersItem', 'getAddressListWithPaginate', 'getAddressList']),
    async updateCountryPaginate(page) {
      this.types.type_list = 'countries';
      await this.getAddressListWithPaginate({types: this.types, query: `?page=${page}`, paginate: true, page})
    },
    async updateRegionPaginate(page) {
      this.types.type_list = 'regions';
      await this.getAddressListWithPaginate({types: this.types, query: `?country_id=${this.address.country.id}&page=${page}`, paginate: true, page})
    },
    async updateCitiesPaginate(page) {
      this.types.type_list = 'cities';
      await this.getAddressListWithPaginate({types: this.types, query: `?city_id=${this.address.region.id}&page=${page}`, paginate: true, page})
    },
   onSetData() {
     this.$emit('updateAddressForm', this.address);
   },
    onChangeCheckBox() {
      if(this.foreignCompany) {
        this.$emit('updateDoubleAddress', this.address);
      }
    },
    async onCountryValue(val) {
      this.uniq = { key: COUNTRIES, id: getUniqueId() }
      this.modeType = '';
      this.settingsAutocomplete.country_id = val.id
      this.address.country = val
      this.address.region = null
      this.address.city = null
      this.settingsAutocomplete.loading_regions = true
      this.types.type_list = 'regions';
      await this.getAddressList({types: this.types, query: `?country_id=${val.id}`})
      this.settingsAutocomplete.loading_regions = false
      this.onSetData();
    },
    async onRegionValue(val) {
      this.uniq = { key: REGIONS, id: getUniqueId() }
      this.modeType = '';
      this.settingsAutocomplete.region_id = val.id
      this.address.region = val
      this.address.city = null
      this.settingsAutocomplete.loading_cities = true
      this.types.type_list = 'cities';
      await this.getAddressList({types: this.types, query: `?region_id=${val.id}`})
      this.settingsAutocomplete.loading_cities = false
      this.onSetData();
    },
    onCityValue(val) {
      this.uniq = { key: CITIES, id: getUniqueId() }
      this.modeType = '';
      this.settingsAutocomplete.city_id = val.id
      this.address.city = val
      this.onSetData();
    }
  },
  async mounted() {
   await this.getAddressList({ types: this.types });

    if (!this.editData) {
      this.address.country = this.defaultCountries;
    }

    if (this.getCopyData) {
      const { isDataSafe, country, region, cities, house, street } = this.getCopyData;
      this.modeType = MODE_TYPES.VIEW
      if (country) {
        this.types.type_list = 'regions';
        await this.getAddressList({types: this.types, query: `?country_id=${country.id}`})
      }

      if (region) {
        this.types.type_list = 'cities';
        await this.getAddressList({types: this.types, query: `?region_id=${region.id}`})
      }

      this.settingsAutocomplete.country_id = getAddressItem(this.countriesList, country.id);
      this.settingsAutocomplete.region_id = getAddressItem(this.regionsList, region.id);
      this.settingsAutocomplete.city_id = getAddressItem(this.citiesList, cities.id);

      this.address = {
        isDataSafe,
        country: { id: country.id, title: country.title },
        region: { id: region.id, title: region.title },
        city: { id: cities.id, title: cities.title },
        house,
        street
      };

      this.foreignCompany = isDataSafe;
      this.$emit('updateAddressForm', this.address);
    }
  }
}
</script>

<style scoped lang="scss">
  .pt0 .label-title {
    & ~ .row .col {
      padding-top: 0;
    }

    & ~ .row:nth-last-of-type(1) .col {
      padding-top: 10px;
    }
  }

  .input-name input {
    color: #7E7E7E;
  }
</style>

<style lang="scss">
.createWareHousesBody {
  .v-input__icon.v-input__icon--append i {
    position: relative;
    left: 5px;
  }
}

.theme--light.v-input input, .theme--light.v-input textarea {
  color: #7E7E7E;
}
</style>
