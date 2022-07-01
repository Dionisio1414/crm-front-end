<template>
    <div class="page with-tabs" :class="{'with-small-nav': navIsSmall}">
        <div class="page-header">
            <div class="page-header-caption">
                <h2 class="page-title">{{ $t('directories.addressDataTitle') }}</h2>
            </div>
            <div class="page-header-actions">
                <AsyncSimpleInput
                    @updateInput="updateInput"
                >
                </AsyncSimpleInput>
                <div class="actions-buttons">
                    <v-btn class="orange-btn" @click="createModal" v-if="currentComponent !== 'Countries'">
                        <span>
                          <svg-sprite width="14" height="14" icon-id="plus"></svg-sprite>
                          <span>{{ $t('page.createBtn') }}</span>
                        </span>
                    </v-btn>
                </div>
            </div>
        </div>

    <div class="card-grid tabs-grid">
      <div class="card list-item">
        <span class="resize-btn" @click="navIsSmall = !navIsSmall">
          <svg-sprite v-if="!navIsSmall" width="12" height="30" icon-id="reduce"></svg-sprite>
          <svg-sprite v-else width="42" height="44" icon-id="reduceSecond"></svg-sprite>
        </span>
        <ul class="tabs-list">
            <li
                class="tabs-item"
                v-for="(item, index) in tabs"
                :key="index"
                @click="changeTab(index)"
            >
                <router-link
                    tag="span"
                    :to="item.url"
                >
                    {{ $t(item.title) }}
                  <svg-sprite width="18" height="23" icon-id="goods"></svg-sprite>
                </router-link>
            </li>
        </ul>
      </div>
      <div class="card card-table">
        <router-view
            @changeItem="onChangeItem"
            @viewItem="onViewItem"
        >
        </router-view>
        <modal-locality
            v-if="modalToggles.ModalLocality"
            :isModalOpen="modalToggles.ModalLocality"
            :itemData="itemData"
            :modeType="mode"
            ref="ModalLocality"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveData"
            @changeDefault="onChangeDefault"
        >
        </modal-locality>
        <modal-regions
            v-if="modalToggles.ModalRegions"
            :isModalOpen="modalToggles.ModalRegions"
            :itemData="itemData"
            :modeType="mode"
            ref="ModalRegions"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveData"
        >
        </modal-regions>
        <modal-branch
            :itemData="itemData"
            ref="ModalBranch"
            @save="onSaveData"
            @changeDefault="onChangeDefault"
        >
        </modal-branch>
        <modal-countries
            v-if="modalToggles.ModalCountries"
            :isModalOpen="modalToggles.ModalCountries"
            :modeType="mode"
            :itemData="itemData"
            ref="ModalCountries"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveData"
        >
        </modal-countries>
        <Snackbar 
            v-if="alert.alertFlag"
            :modeType="alert.modeType"
            :typeTitle="alert.title"
            @closeSnackbar="closeSnackbar"
        >
        </Snackbar>
      </div>
    </div>
    </div>
</template>
<script>
import { mapActions } from "vuex"
import ModalLocality from "@/components/dashboard/directoties/address/modals/ModalLocality"
import ModalBranch from "@/components/dashboard/directoties/address/modals/ModalBranch"
import ModalCountries from "@/components/dashboard/directoties/address/modals/ModalCountries"
import ModalRegions from "@/components/dashboard/directoties/address/modals/ModalRegions"
// import Alert from "@/components/ui/Alert"
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput"
import Snackbar from "@/components/dashboard/directoties/snackbar/Snackbar"

export default {
    name: "AddressData",
    components: {
        ModalLocality,
        ModalBranch,
        ModalCountries,
        ModalRegions,
        // Alert,
        AsyncSimpleInput,
        Snackbar
    },
    data: () => ({
        itemData: {},
        mode: '',
        navIsSmall: false,
        tabs: [
            { title: "directories.countries", url: "/directories/address/countries" },
            { title: "directories.locality", url: '/directories/address/cities' },
            { title: "directories.regiones", url: '/directories/address/regiones' },
        ],
        tabsComponents: ['Countries', 'Locality', 'Regiones'],
        modalsComponents: ['ModalCountries', 'ModalLocality', 'ModalRegions'],
        currentTab: {
            index: 0,
            name: 'Countries',
        },
        modalToggles: {
            ModalCountries: false,
            ModalLocality: false,
            ModalRegions: false,
        },
        alert: {
            alertFlag: false,
            title: null,
            modeType: null
        }
    }),
    computed: {
        currentComponent() {
            return this.tabsComponents[this.currentTab.index]
        },
        currentModal() {
            return this.modalsComponents[this.currentTab.index]
        },
    },
    methods: {
        ...mapActions({
            create: 'createClassifiersItem',
            change: 'changeClassifiersItem',
            changeDefault: 'changeDefaultItem'
        }),
        closeModal() {
            this.modalToggles[this.currentModal] = false
        },
        createModal() {
            console.log('create')
            this.mode = 'create'
            this.modalToggles[this.currentModal] = true
        },
        createNewItem() {
            this.mode = 'create'
            this.$refs[this.currentModal].open(this.mode)
        },
        changeTab(index) {
            this.currentTab.index = index
        },
        onChangeDefault(params) {
            console.log('change params', params)
            this.changeDefault({id: params.id, resources: params.resources } )
        },
        async onSaveData(params) {
            console.log('params', params)
            await this[params.action]({...params})
            this.modalToggles[`${this.currentModal}`] = false
            this.alert.alertFlag = true
            this.alert.modeType = params.action
            this.alert.title = params.alertItem
        },
        closeSnackbar() {
            this.alert.alertFlag = false
            this.alert.modeType = null
            this.alert.title = null
        },
        showAlert(item) {
            const dataAlert = {startText: 'Создан новый элемент справочника', item, text: true}
            this.$store.dispatch('alertShow', dataAlert)
        },
        onChangeItem(itemData) {
            this.mode = 'change'
            this.itemData = itemData
            this.modalToggles[this.currentModal] = true
        },
        onViewItem(itemData) {
            this.mode = 'view',
            this.itemData = itemData
            this.modalToggles[this.currentModal] = true
        },
        updateInput(value) {
            console.log(value)
        }
    },
    watch: {
        $route(to) {
            if(to.params.params) this.createModal()
        }
    },
    created() {
        const { name } = this.$route
        console.log('address data name', name)
        if(name === 'cities') {
            this.currentTab.index = 1
            this.currentTab.name = 'Locality'
        } else if(name === 'countries') {
            this.currentTab.index = 0
            this.currentTab.name = 'Branch'
        } else {
            this.currentTab.index = 2
            this.currentTab.name = 'Regiones'
        }
    },
    mounted() {
        const { params } = this.$route.params
        console.log(this.$route)
        params && this.createModal()
    }
}
</script>
<style lang="sass" scoped>

</style>
