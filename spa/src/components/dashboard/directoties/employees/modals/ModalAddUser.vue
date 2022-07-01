<template>
    <modal-container
        v-if="isOpen" 
        :dialog="isOpen" 
        :modalWidth="920" 
        @clickOutside="onCloseModal"
        :customDialogClass="['modal-container-general']"
    >
        <template v-slot:header>
            <div class="dialog-header">
                <div class="header-text">
                    {{ $t('directories.addUserText') }}
                </div>
                <div class="dialog-header-actions">
                    <v-btn icon color="#5893D4" @click="addTab('add-user', 'directories.addUserText')" :disabled="checkTabs">
                        <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                                fill="#BBDBFE"/>
                        </svg>
                    </v-btn>
                    <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="onCloseModal">
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
                <form class="form">
                    <div class="form-content form-content--singular">
                        <v-row>
                            <v-col cols="7">
                                <div class="label-title">
                                    {{ $t('directories.send_an_invitation') }}
                                </div>
                                <div class="form-row">
                                    <div class="form-field">
                                        <input 
                                            type="text" 
                                            v-model="inviteUrl" 
                                            style="display: none;"
                                        >
                                        <input 
                                            ref="email"
                                            type="text" 
                                            class="field"
                                            :class="{'field-error': $v.email.$invalid}"
                                            @input="$v.email.$touch()"
                                            @blur="$v.email.$touch()"
                                            placeholder="Введите E-mail"
                                            v-model="email"
                                        >
                                        <div class="form-field-footer">
                                            <button 
                                                type="button" 
                                                class="form-field-link" 
                                                v-clipboard:copy="inviteUrl !== '' ? inviteUrl : getInviteUrl"
                                                v-clipboard:success="onCopy"
                                                v-clipboard:error="onError"
                                            >
                                                <SvgSprite
                                                    class="icon" 
                                                    :width="20"
                                                    :height="20"
                                                    iconId="link"
                                                />
                                                <span class="text">
                                                    {{ $t('directories.copyLink') }}
                                                </span>
                                            </button>
                                            <div class="form-field-helper form-field-helper--default" v-show="isCopy">
                                                {{ $t('directories.copyLinkTitle') }}
                                            </div>
                                            <div class="form-field-helper form-field-helper--default" v-show="isSend">
                                                {{ $t('directories.sendEmails') }}
                                            </div>
                                        </div>
                                    </div>
                                    <button 
                                        type="button" 
                                        class="base-button base-button--lighten--transparent" 
                                        :disabled="$v.email.$invalid || !email.length"
                                        @click="sendEmail"
                                    >
                                        {{ $t('auth.send') }}
                                    </button>
                                </div>
                            </v-col>
                        </v-row>
                    </div>
                </form>
            </div>
        </template>
        <template v-slot:footer>
            <div class="dialog-footer">
                <div class="form-actions">
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
                        @click="onCloseModal"
                    >
                        {{ $t('page.addButton') }}
                    </button>
                </div>
            </div>
        </template>
    </modal-container>
</template>
<script>
import { mapActions, mapGetters } from "vuex"
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import { validationMixin } from 'vuelidate'
import { email } from 'vuelidate/lib/validators'
import mixin from '@/mixins/mixinTabs'

export default {
    name: "ModalAddUser",
    props: {
        isOpen: Boolean
    },
    mixins: [validationMixin, mixin],
    components: {
        ModalContainer,
        SvgSprite
    },
    validations: {
        email: { email }
    },
    data() {
        return {
            email: '',
            inviteUrl: localStorage.getItem('invite_url') !== null ? localStorage.getItem('invite_url') : '',
            isCopy: false,
            isSend: false
        }
    },
    computed: {
        ...mapGetters([
            'tabsLength'
        ]),
        getInviteUrl() {
            return localStorage.getItem('invite_url') !== null ? localStorage.getItem('invite_url') : ''
        }
    },
    methods: {
        ...mapActions([
            'updateTabs',
            'userSendInvite'
        ]),
        onCloseModal() { 
            if(localStorage.getItem('invite_url') !== null) localStorage.removeItem('invite_url')
            this.$emit('onCloseModal') 
        },
        onCopy(e) {
            console.log('You just copied: ' + e.text)
            this.isCopy = true
            this.isSend = false
        },
        onError(e) {
            console.log(e)
        },
        sendEmail() {
            const data = {
                email: this.email,
                invite_url: this.inviteUrl !== '' ? this.inviteUrl : this.getInviteUrl
            }
            this.userSendInvite(data).then(response => { 
                if(response.data.success) {
                    this.isSend = true
                    this.isCopy = false
                }
            })
        }
    },
}
</script>
<style lang="sass" scoped>
    .modal-container
        &-general
            .dialog
                &-body
                    padding-bottom: 0
                &-footer
                    padding-bottom: 50px
    .form-row
        display: flex
        align-items: flex-start
        justify-content: space-between
        .form-field
            flex: 0 1 calc(80% - 15px)
        .base-button
            flex: 0 1 calc(20% - 15px)
</style>