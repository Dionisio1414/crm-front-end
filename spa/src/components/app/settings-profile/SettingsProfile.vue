<template>
    <modal-container 
        v-if="isModalOpen" 
        @clickOutside="onCloseModal"
        :dialog="isModalOpen" 
        :modalWidth="1200"
        :customDialogClass="['modal-container-settings']"
    >
        <template v-slot:header>
            <div class="dialog-header">
                <div class="header-text">
                    <span>{{ $t('user.settingsProfileTitle') }}</span>
                </div>
                <div class="dialog-header-actions">
                    <v-btn 
                        icon 
                        color="#5893D4" 
                        class="action-btn sortable-btn" 
                        @click="onCloseModal"
                    >
                        <SvgSprite
                            style="color: rgb(157,204,255)"
                            :width="15"
                            :height="15"
                            iconId="closeWhite"
                        />
                    </v-btn>
                </div>
            </div>
        </template>
        <template v-slot:main>
            <div class="dialog-body">
                <div class="dialog-body-content">
                    <div class="step-tabs">
                        <div class="step-tabs-item" :class="{'step-tabs-item--active': index === currentStep}" v-for="(val, index) in stepTabs" :key="index">
                            <button type="button" class="base-btn" @click="goTo(index)" :disabled="val.disabled">
                            {{ $t(val.title) }}
                            </button>
                        </div>
                    </div>
                    <div class="step-wrapper">
                        <keep-alive>
                            <component 
                                :is="currentTabComponent"
                                @updatePassword="updatePassword"
                                @changeField="changeField"
                                @isSave="isSave"
                            >
                            </component>
                        </keep-alive>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <div class="dialog-footer">
                <div class="form-actions" v-if="currentTabComponent === 'AuthData'">
                    <button 
                        type="button"   
                        class="base-button base-button--transparent" 
                        @click="onCloseModal"
                    >
                        {{ $t('page.cancelButton') }}
                    </button>
                    <button 
                        type="button" 
                        class="base-button base-button--blue" 
                        @click="saveSettings"
                        :disabled="authData.disabledAuthBtn"
                    >
                        {{ $t('report.save') }}
                    </button>
                </div>
            </div>
        </template>
    </modal-container>
</template>
<script>
// Steps
import AuthData from '@/components/app/settings-profile/steps/AuthData/AuthData'
import Security from '@/components/app/settings-profile/steps/Security/Security'
import SettingsNotifications from '@/components/app/settings-profile/steps/SettingsNotifications/SettingsNotifications'
import Tariffs from '@/components/app/settings-profile/steps/Tariffs/Tariffs'

import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import { mapActions } from 'vuex'

export default {
    name: "SettingsProfile",
    props: {
        isModalOpen: Boolean
    },
    components: {
        AuthData,
        ModalContainer,
        SvgSprite,
        Security,
        SettingsNotifications,
        Tariffs
    },
    data: () => ({
        currentStep: 0,
        authData: {
            disabledAuthBtn: true,
            passwordData: null
        },
        stepTabs: [
            { title: 'user.settingsUser', name: "AuthData", disabled: false },
            { title: 'user.security', name: "Security", disabled: false },
            { title: 'user.tariffPlan',  name: "Tariffs", disabled: false },
            { title: 'user.settingsNotifications', name: "SettingsNotifications", disabled: false },
        ],
    }),
    computed: {
        currentTabComponent() { return this.stepTabs[this.currentStep].name }
    },
    methods: {
        ...mapActions(['changePassword', 'getUserAuth']),
        onCloseModal() { 
            this.$emit('close') 
        },
        goTo(index) { this.currentStep = index },
        updatePassword(value) {
            console.log('value pass', value)
            this.authData.passwordData = value
        },
        changeField() {
            this.authData.disabledAuthBtn = false
        },
        isSave() {
            this.authData.disabledAuthBtn = false
        },
        async changePasswordHandler() {
            const response = await this.changePassword({ ...this.authData.passwordData })
            if(response.success) {
                this.authData.disabledAuthBtn = false
                this.onCloseModal()
            } else {
                this.authData.disabledAuthBtn = true
            }
        },
        saveSettings() {
            this.authData.disabledAuthBtn = true 
            if(this.authData.passwordData !== null) {
                this.changePasswordHandler()
            }
            this.authData.disabledAuthBtn = false
            this.onCloseModal()
        }
    },
    async created() {
        await this.getUserAuth()
    },
}
</script>
<style lang="sass" scoped>

.step
    &-tabs
        display: flex
        margin-bottom: 18px
        &-item
            &:not(:last-child)
                margin-right: 20px
            .base-btn
                background: transparent
                border: 1px solid #9DCCFF
                border-radius: 25px
                font-size: 14px
                line-height: 1
                min-height: 36px
                min-width: 220px
                color: #9DCCFF
                transition: .25s ease-in-out
                &:disabled
                    opacity: .5
            &--active
                .base-btn
                    background: #5893D4
                    border-color: #5893D4
                    color: #fff
    &-wrapper
        .item-form
            .row
                .col
                    padding-top: 0
                    padding-bottom: 0

.item-form
    min-height: 0
    &-column
        height: 100%
        .label-title
            margin-bottom: 3px

</style>