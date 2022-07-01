<template>
  <modal-container
      v-if="isModalOpen"
      @clickOutside="onCloseModal"
      :dialog="isModalOpen"
      :modalWidth="1200"
      :customDialogClass="['modal-container-default']"
  >
    <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('directories.city') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" @click="addTab('locality', 'directories.city')" :disabled="checkTabs" v-if="mode === 'create'">
              <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                      fill="#BBDBFE"/>
              </svg>
            </v-btn>
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="cancel">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                    fill="#BBDBFE"/>
              </svg>
            </v-btn>
          </div>
        </div>
    </template>
    <template v-slot:main>
      <div class="dialog-body">
        <div class="dialog-body-content">
          <form class="form">
            <div class="form-content">
              <v-row>
                <v-col cols="3">
                  <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.addressForm.country') }}</div>

                  <div class="form-field">
                    <Autocomplete
                      customClass="autocomplete"
                      :modeTypes="modeType"
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
                <v-col cols="3">
                  <div class="label-title label-title--required">{{ $t('directories.area') }}</div>
                  <div class="form-field">
                    <Autocomplete
                      customClass="autocomplete"
                      :modeTypes="modeType"
                      isAddValue
                      :list="regionsList"
                      :id="settingsAutocomplete.region_id"
                      :readonly="mode === 'view'"
                      itemText="title"
                      itemValue="id"
                      placeholder="Выберите регион"
                      :menuProps="{ contentClass: `${settingsAutocomplete.type_region} autocomplete-dropdown autocomplete-dropdown--repeater`}"
                      :loading="settingsAutocomplete.loading_regions"
                      :type="settingsAutocomplete.type_region"
                      :uniq="uniq"
                      @updateValue="onRegionValue"
                      @updatePagePaginate="updateRegionPaginate"
                      @addListItem="addListItem"
                    >
                    </Autocomplete>
                  </div>
                </v-col>
                <v-col cols="3">
                  <div class="label-title label-title--required label-title--singular">
                    <div class="label-title-text">
                      {{ $t('directories.city') }}
                    </div>
                    <span class="default-icon" @click="changeDefaultState">
                      <SvgSprite
                        v-if="localFormData.is_default"
                        class="icon"
                        :width="16"
                        :height="16"
                        iconId="default_star"
                      />
                      <SvgSprite
                        v-else
                        class="icon"
                        :width="16"
                        :height="16"
                        iconId="default_star_transparent"
                      />
                    </span>
                  </div>
                  <div class="form-field">
                    <input
                      class="field"
                      type="text"
                      :readonly="mode === 'view'"
                      placeholder="Введите город/населенный пункт"
                      :disabled="localFormData.region_id === null"
                      v-model="localFormData.title"
                    >

                    <!-- <Autocomplete
                      customClass="autocomplete"
                      :modeTypes="modeType"
                      :list="cityList"
                      :id="settingsAutocomplete.city_id"
                      :readonly="mode === 'view'"
                      itemText="title"
                      itemValue="id"
                      placeholder="Выберите город"
                      :menuProps="{ contentClass: `${settingsAutocomplete.type_city} autocomplete-dropdown`}"
                      :loading="false"
                      :type="settingsAutocomplete.type_city"
                      :uniq="uniq"
                      @updateValue="onCityValue"
                      @updatePagePaginate="updateCityPaginate"
                    >
                    </Autocomplete> -->
                  </div>
                </v-col>
                <v-col cols="3">
                  <div class="label-title">{{ $t('directories.indexTitle') }}</div>
                  <div class="form-field">
                    <input
                      class="field"
                      type="text"
                      :readonly="mode === 'view'"
                      placeholder="Введите индекс"
                      v-model="localFormData.zip_code"
                    >
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                </v-col>
              </v-row>
            </div>
          </form>
        </div>
      </div>
    </template>
    <template v-slot:footer>
      <div class="dialog-footer">
        <div class="form-actions">
          <button
            v-if="mode !== 'view'"
            type="button"
            class="base-button base-button--transparent"
            :disabled="loader"
            @click="cancel"
          >
            Назад
          </button>
          <button
            v-if="mode === 'view'"
            type="button"
            class="base-button base-button--transparent"
            :disabled="loader"
            @click="cancel"
          >
            {{ $t('page.close') }}
          </button>
          <button
            v-if="mode !== 'view'"
            type="button"
            class="base-button base-button--blue"
            :disabled="$v.$invalid || loader"
            @click="onSave"
          >
            {{ $t('page.saveButton') }}
          </button>
        </div>
      </div>
    </template>
  </modal-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { required } from "vuelidate/lib/validators"
import mixin from '@/mixins/mixinTabs'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import Autocomplete from '@/components/ui/Autocomplete/Autocomplete'
import { COUNTRIES, REGIONS, CITIES } from '@/components/ui/Autocomplete/constants'
import getUniqueId from '@/helper/getUniqueId'
import _cloneDeep from 'lodash.clonedeep'

export default {
  name: "ModalLocality",
  mixins: [mixin],
  props: {
    itemData: Object,
    isModalOpen: Boolean,
    modeType: String
  },
  components: {
    ModalContainer,
    SvgSprite,
    Autocomplete
  },
  validations: {
    localFormData: {
      country_id: { required },
      region_id: { required },
      title: { required }
    }
  },
  data: () => ({
    mode: 'create',
    isListPopup: false,
    listTitle: null,
    uniq: null,
    loader: false,
    localFormData: {
      id: null,
      region_id: null,
      country_id: null,
      zip_code: null,
      is_default: false,
      is_editable: true,
      title: null
    },
    settingsAutocomplete: {
      country_id: null,
      region_id: null,
      city_id: null,
      type_region: REGIONS,
      type_country: COUNTRIES,
      type_city: CITIES,
      uniq: null,
      loading_regions: false
    }
  }),
  computed: {
    ...mapGetters(['getLists']),
    countriesList() { return this.getLists('lists')['countries']  },
    regionsList() { return this.getLists('lists')['regions'] },
  },
  methods: {
    ...mapActions(['fetchLists', 'fetchList', 'createClassifiersItem']),
    async updateCountryPaginate(page) {
      await this.fetchList({ type: '', resources: 'countries', directory: 'directories', query: `?page=${page}`, paginate: true, page })
    },
    async updateRegionPaginate(page) {
      await this.fetchList({ type: '', resources: 'regions', directory: 'directories', query: `?country_id=${this.localFormData.country_id}&page=${page}`, paginate: true, page })
    },
    updateCityPaginate(page) {
      console.log(page)
    },
    async onCountryValue(val) {
      this.uniq = { key: COUNTRIES, id: getUniqueId() }
      this.settingsAutocomplete.country_id = val.id
      this.settingsAutocomplete.region_id = val.id
      this.localFormData.country_id = val.id
      this.settingsAutocomplete.loading_regions = true
      await this.fetchList({ type: '', resources: 'regions', directory: 'directories', query: `?country_id=${val.id}`, paginate: false })
      this.settingsAutocomplete.loading_regions = false
      console.log(val)
    },
    onRegionValue(val) {
      this.uniq = { key: REGIONS, id: getUniqueId() }
      this.settingsAutocomplete.region_id = val.id
      this.localFormData.region_id = val.id
      console.log(val)
    },
    onCityValue(val) {
      this.uniq = { key: CITIES, id: getUniqueId() }
      this.settingsAutocomplete.city_id = val.id
      console.log(val)
    },
    toggleListPopup() {
      this.isListPopup = !this.isListPopup
    },
    onCloseModal() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(params) {
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    async addListItem(title) {
      const regionData = {
        action: 'create',
        resources: 'regions',
        data: {
          is_editable: true,
          is_default: false,
          country_id: this.localFormData.country_id,
          title
        }
      }
      await this.createClassifiersItem({...regionData})
      await this.fetchList({ type: '', resources: 'regions', directory: 'directories', query: `?country_id=${this.localFormData.country_id}`, paginate: false })
    },
    onSave() {
      const { params } = this.$route.params
      if(params) {
        this.$emit('save', {
          action: this.mode,
          resources: 'cities',
          data: { ...this.localFormData },
          alertItem: 'directories.locality'
        })
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.$router.go(-1)
        this.loader = true
      } else {
        this.$emit('save', {
          action: this.mode,
          resources: 'cities',
          data: { ...this.localFormData },
          alertItem: 'directories.locality'
        })
        this.loader = true
      }
    },
    changeDefaultState() { this.localFormData.is_default = !this.localFormData.is_default },
    cancel() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(params)  {
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
  },
  async created() {
    await this.fetchLists({ type: '', resources: 'countries' })
  },
  async mounted() {
    this.mode = this.modeType
    if(this.mode !== 'create') {
      const query = this.modeType !== 'create' ? `?country_id=${this.itemData.country_id}&selected_id=${this.itemData.country_id}` : `?country_id=${this.itemData.country_id}`;
      let {
        country_id,
        id,
        is_default,
        is_editable,
        region_id,
        cells: {
          zip_code,
          city:title
        }
      } = _cloneDeep(this.itemData)
      this.localFormData = {
        country_id,
        id,
        is_default,
        is_editable,
        region_id,
        zip_code,
        title
      }
      this.settingsAutocomplete.country_id = country_id
      this.settingsAutocomplete.region_id = region_id
      await this.fetchList({ type: '', resources: 'countries', directory: 'directories', query, paginate: true })
      await this.fetchList({ type: '', resources: 'regions', directory: 'directories', query, paginate: true })
    } else {
      await this.fetchLists({ type: '', resources: 'countries' })
    }
  },
}
</script>

<style lang="sass" scoped>
  .select-switcher
    .v-input
      &__control
        min-height: 26px!important
    .v-label
      font-size: 14px
      line-height: 1
      color: rgba(#7E7E7E, .5)
    .v-select
      &__selections
        line-height: 1
        input
          padding: 0
          line-height: 1
      &__selection
        font-size: 14px
        line-height: 1
        color: #7e7e7e
  .base-select
    &--repeater
      .v-list
        padding-top: 0
        &-button
          background: #F4F9FF
          line-height: 1
          text-align: left
          padding: 10px 15px
          box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.05)
          width: 100%
          .icon
            margin-right: 9px
            path
              fill: red!important
          .text
            font-size: 15px
            line-height: 1
            color: #5893D4
        &-popup
          background: #fff
          border-radius: 10px
          padding: 30px 20px 20px 20px
          box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.15)
          .form
            &-field
              margin-bottom: 20px
            &-actions
              text-align: center
              padding-top: 0
              .base-button
                min-width: 160px
</style>
