<template>
  <div class="page with-tabs">
    <div class="page-header">
      <h2 class="page-title">Настройки</h2>
    </div>
    <div class="card-grid">
      <div class="card list-item">
        <ul class="tabs-list">
          <li class="tabs-item " :class="{ 'active': index === currentTab.index }"
              v-for="(tab, index) in tabs"
              @click="checkTab(index)" :key="index">
            {{ $t(tab.title) }}
          </li>
        </ul>
      </div>
      <div class="card detail-item" >
        <keep-alive>
          <component :is="currentComponent"
                     @change="onChangeData"
                     @save="onSaveData"
                     @reset="onReset"
          ></component>
        </keep-alive>
      </div>
    </div>
    <redirect-modal ref="redirectModal"></redirect-modal>
    <DialogSave ref="confirmSave">
      <div class="header-text" slot="dialog-header">
        <span>{{ $t('page.saveButton') }}</span>
      </div>
      <div class="dialog-text" slot="dialog-text">
        {{ $t('page.saveChanges') }}
      </div>
    </DialogSave>
  </div>
</template>

<script>
import userSettings from "@/components/dashboard/systemManagemant/settings/UserSettings";
import ProgramSettings from "@/components/dashboard/systemManagemant/settings/ProgramSettings";
import accessSettings from "@/components/dashboard/systemManagemant/settings/AccessSettings";
import DialogSave from "@/components/ui/dialog/DialogSave";
import {mapActions, mapGetters} from 'vuex';
import {eventBus} from "@/main";
import RedirectModal from "./modals/RedirectModal";


export default {
  name: "settings",
  components: {
    RedirectModal,
    userSettings,
    ProgramSettings,
    DialogSave,
    accessSettings
  },
  computed: {
    ...mapGetters({
      user: 'user',
      lists: 'getLists',
    }),
    currentComponent() {
      return this.tabsComponents[this.currentTab.index]
    }
  },
  beforeRouteLeave(to, from, next) {
    if (this.currentTab.isEdited && !this.currentTab.isSaved) {
      this.saveConfirmOnChangeRoute(next)
    } else {
      next()
    }
  },
  data() {
    return {
      isLoading: false,
      tabs: [
        {title: "programSettings.programSettings"},
        // {title: "programSettings.accessSettings" },
        // {title: "programSettings.UserSettings" },
      ],
      tabsComponents: ['ProgramSettings', 'accessSettings', 'userSettings'],
      currentTab: {
        index: 0,
        name: '',
        isEdited: false,
        isSaved: false,
        formTitle: '',
        formData: {}
      },
    }
  },

  methods: {
    ...mapActions({
      changeSettings: 'changeCompany',
    }),
    checkTab(index) {
      if (this.currentTab.isEdited && !this.currentTab.isSaved) {
        this.saveConfirmOnChangeTab(index)
      } else {
        this.changeTab(index)
      }
    },
    async saveConfirmOnChangeTab(index) {
      if (await this.$refs.confirmSave.open('Save', 'Are you sure?')) {
        await this.onSaveData()
        this.changeTab(index)
      } else {
        this.changeTab(index)
        eventBus.$emit("setting-reset-form");
      }
    },
    async saveConfirmOnChangeRoute(callback) {
      if (await this.$refs.confirmSave.open('Save', 'Are you sure?')) {
        await this.onSaveData()
        callback()
      } else {
        callback()
        eventBus.$emit("setting-reset-form");
      }
    },
    changeTab(index) {
      this.currentTab.index = index
      this.currentTab.isEdited = false
      this.currentTab.isSaved = false
    },
    onChangeData(data) {
      this.currentTab.isEdited = true
      this.currentTab.formData = data
    },
    async onSaveData(val) {
      const domain = JSON.parse(window.localStorage.getItem('domain')).split('.')[0]
      const condition = domain === this.currentTab.formData.domain
      if (this.currentTab.isEdited && !this.currentTab.index && !condition) {
        if (await this.$refs.redirectModal.open()) {
          await this.changeSettings(this.currentTab.formData)
          localStorage.clear();
          window.location.replace(`https://${this.user.company.user_main_domain}/?user_id=${this.user.id}`)
        }
      } else {
        console.log('val', val)
        // console.log('formData', this.currentTab.formData)
        await this.changeSettings({...val})
        const dataAlert = {startText: 'Изменения сохранены', text: true}
        this.$store.dispatch('alertShow', dataAlert)
      }
      this.currentTab.isSaved = true
    },
    onReset() {
      this.currentTab.isEdited = false
      this.currentTab.isSaved = false
    }
  }

}
</script>