<template>
    <div class="forms-wrapper-item">
        <div class="item-form">
            <v-row>
                <v-col cols="6">
                    <div class="item-form-column">
                        <v-row>
                            <v-col cols="12">
                                <div class="field-wrapper">
                                    <div class="field-actions">
                                        <div class="form-field">
                                            <div class="label-title">
                                                <span class="label-title-text">{{ $t('page.suppliers.modal.createSupplier.thirdForm.phoneNumber') }}</span>
                                                <span class="label-title-success" v-show="isSavePhone">{{ $t('page.savedTitle') }}</span>
                                            </div>
                                            <!-- <vue-phone
                                                v-bind="phoneSettings"
                                                :disabled="phoneSettings.disabledField"
                                                :valueField="auth.phone"
                                                @input="inputHandler"
                                            >
                                            </vue-phone> -->
                                            <!-- <phone-mask-input
                                                v-model="auth.phone"
                                                autoDetectCountry
                                                showFlag
                                                defaultCountry="ua"
                                                @onValidate="onValidate"
                                                wrapperClass="wrraper-example"
                                                inputClass="input-example"
                                                flagClass="flag-example"
                                            /> -->
                                            <vue-single-phone
                                                :phone="auth.phone"
                                                :defaultCountry="phoneSettings.defaultCountry"
                                                :disabledField="mode_phone === 'edit'"
                                                inputClass="wrapper"
                                                errorClass="wrapper-error-field"
                                                placeholderInput="Не привязан"
                                                @input="inputHandler"
                                                @updateInput="updateInput"
                                                @onFocus="resetErrors('phone')"
                                            >
                                            </vue-single-phone>
                                            <!-- <input
                                                class="field"
                                                type="tel"
                                                name="tel"
                                                placeholder="Не привязан"
                                                :readonly="mode_phone === 'edit'"
                                                v-model="auth.phone"
                                                :class="{'error-on-input': !validatePhone || phoneError}"
                                                v-mask="'+38 (###) ### ## ##'"
                                                @focus="resetErrors('phone')"
                                                @change="changeField"
                                                @input="changeFieldFlag('phone')"
                                                ref="telInput"
                                            > -->
                                            <div class="field-error-text" v-if="phoneError">{{ phoneErrorText }}</div>
                                        </div>
                                        <button
                                            type="button"
                                            class="btn-action edit"
                                            v-if="mode_phone === 'edit' && auth.phone !== null && auth.phone"
                                            @click="editPhone"
                                            :disabled="showEmailMessage || showPhoneMessage"
                                        >
                                            <SvgSprite
                                                :width="30"
                                                :height="30"
                                                iconId="edit_pencil"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-action save"
                                            v-if="mode_phone === 'save'"
                                            :disabled="$v.auth.phone.$invalid || phoneError || compareValuesPhone || showEmailMessage"
                                            @click="savePhone"
                                        >
                                            <SvgSprite
                                                :width="30"
                                                :height="30"
                                                iconId="save_mark"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-action join"
                                            v-if="auth.phone === null && mode_phone !== 'save' || !auth.phone && mode_phone !== 'save'"
                                            @click="addPhone"
                                        >
                                            <SvgSprite
                                                :width="30"
                                                :height="30"
                                                iconId="join_plus"
                                            />
                                        </button>
                                    </div>
                                    <div class="field-messages" v-if="showPhoneMessage">
                                        <div class="message">
                                            <span class="message-text">
                                                По этому телефону <strong>отправлено сообщение для подтверждения</strong> изменения номера телефона. Действует <strong>48</strong> часов (до подтверждения для входа в аккаунт используется старый номер телефона).
                                                <button
                                                    type="button"
                                                    class="btn btn-change"
                                                    v-if="!resendPhoneFlag"
                                                    @click="resendPhone"
                                                >
                                                    Отправить заново
                                                </button>
                                                <countdown-timer
                                                    v-else 
                                                    @end="endTimer('phone')"
                                                >
                                                    Возможность отправить код повторно будет через:
                                                </countdown-timer>
                                            </span>
                                        </div>
                                        <div class="field-actions">
                                            <div class="form-field">
                                                <div class="label-title">Код подтверждения</div>
                                                <input
                                                    class="field"
                                                    type="text"
                                                    name="code_email"
                                                    placeholder="Введите код"
                                                    v-model.number="auth.phone_code"
                                                    v-mask="'####'"
                                                    :class="{'error-on-input': $v.auth.phone_code.$invalid || auth.phone_code === null || auth.phone_code === '' || phoneVerifyError}"
                                                    @focus="resetErrors('phone')"
                                                >
                                            </div>
                                            <button
                                                type="button"
                                                class="btn-action save"
                                                :disabled="$v.auth.phone_code.$invalid || auth.phone_code === null || auth.phone_code === '' || phoneVerifyError"
                                                @click="verifyPhone"
                                            >
                                                <SvgSprite
                                                    :width="30"
                                                    :height="30"
                                                    iconId="save_mark"
                                                />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <div class="field-actions">
                                        <div class="form-field">
                                            <div class="label-title">
                                                <span class="label-title-text">E-mail</span>
                                                <span class="label-title-success" v-show="isSaveEmail">{{ $t('page.savedTitle') }}</span>
                                            </div>
                                            <input
                                                class="field"
                                                type="email"
                                                name="email"
                                                placeholder="emailexample@gmail.com"
                                                :readonly="mode_email === 'edit'"
                                                v-model="auth.email"
                                                :class="{'error-on-input': $v.auth.email.$invalid || emailError}"
                                                @focus="resetErrors('email')"
                                                @change="changeField"
                                                @input="changeFieldFlag('email')"
                                            >
                                            <div class="field-error-text" v-if="emailError">{{ emailErrorText }}</div>
                                        </div>
                                        <button
                                            type="button"
                                            class="btn-action edit"
                                            v-if="mode_email === 'edit' && auth.email !== null && auth.email"
                                            :disabled="showPhoneMessage || showEmailMessage"
                                            @click="editEmail"
                                        >
                                            <SvgSprite
                                                :width="30"
                                                :height="30"
                                                iconId="edit_pencil"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-action save"
                                            v-if="mode_email === 'save'"
                                            :disabled="$v.auth.email.$invalid || emailError || compareValuesEmail || showPhoneMessage"
                                            @click="saveEmail"
                                        >
                                            <SvgSprite
                                                :width="30"
                                                :height="30"
                                                iconId="save_mark"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-action join"
                                            v-if="auth.email === null && mode_email !== 'save' || !auth.email && mode_email !== 'save'"
                                            @click="addEmail"
                                        >
                                            <SvgSprite
                                                :width="30"
                                                :height="30"
                                                iconId="join_plus"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-change-pass"
                                            @click="changePassword"
                                            v-if="!passwordFlag"
                                        >
                                            {{ $t('page.changePasswordTitle') }}
                                        </button>
                                    </div>
                                    <div class="field-messages" v-if="showEmailMessage">
                                        <div class="message">
                                            <span class="message-text">
                                                По этому адресу <strong>отправлено письмо с <span>кодом</span> для подтверждения</strong> изменения E-mail. Действует <strong>48</strong> часов (до подтверждения для входа в аккаунт используется старый E-mail).
                                                <button
                                                    type="button"
                                                    class="btn btn-change"
                                                    v-if="!resendEmailFlag"
                                                    @click="resendEmail"
                                                >
                                                    Отправить заново
                                                </button>
                                                <countdown-timer
                                                    v-else 
                                                    @end="endTimer('email')"
                                                >
                                                    Возможность отправить письмо повторно будет через:
                                                </countdown-timer>
                                            </span>
                                        </div>
                                        <div class="field-actions">
                                            <div class="form-field">
                                                <div class="label-title">Код подтверждения</div>
                                                <input
                                                    class="field"
                                                    type="text"
                                                    name="code_email"
                                                    placeholder="Введите код"
                                                    v-model.number="auth.email_code"
                                                    v-mask="'####'"
                                                    :class="{'error-on-input': $v.auth.email_code.$invalid || auth.email_code === null || auth.email_code === '' || emailVerifyError}"
                                                    @focus="resetErrors('email')"
                                                >
                                            </div>
                                            <button
                                                type="button"
                                                class="btn-action save"
                                                :disabled="$v.auth.email_code.$invalid || auth.email_code === null || auth.email_code === '' || emailVerifyError"
                                                @click="verifyEmail"
                                            >
                                                <SvgSprite
                                                    :width="30"
                                                    :height="30"
                                                    iconId="save_mark"
                                                />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                        <ChangePassword
                            @updatePassword="updatePassword"
                            @changeField="changeField"
                            v-if="passwordFlag"
                        >
                        </ChangePassword>
                    </div>
                </v-col>
                <v-col cols="6">
                    <div class="item-form-column item-form-column--fill">
                        <div class="item-name">
                            <div class="label-title">{{ $t('page.joinSocialsTitle') }}</div>
                            <div class="socials-list">
                                <div class="socials-list-item" :class="{'socials-list-item--disabled': getSocialGoogle && getSocialGoogle.provider}">
                                    <button
                                        class="btn btn-social"
                                        type="button"
                                        @click="redirectSocial('google')"
                                    >
                                        <SvgSprite
                                            :width="19"
                                            :height="19"
                                            iconId="google_icon"
                                        />
                                        <!-- <g-signin-button
                                            :params="googleSignInParams"
                                            @success="onSignInSuccess"
                                            @error="onSignInError"
                                            ref="google-button"
                                        >
                                            <SvgSprite
                                                :width="19"
                                                :height="19"
                                                iconId="google_icon"
                                            />
                                        </g-signin-button> -->
                                    </button>
                                </div>
                                <div class="socials-list-item" :class="{'socials-list-item--disabled': getSocialFacebook && getSocialFacebook.provider}">
                                    <!-- @click="logInWithFacebook" -->
                                    <button
                                        class="btn btn-social"
                                        type="button"
                                        ref="facebook-button"
                                        @click="redirectSocial('facebook')"
                                    >
                                        <SvgSprite
                                            :width="35"
                                            :height="35"
                                            iconId="facebook_icon"
                                        />
                                    </button>
                                </div>
                                <div class="socials-list-item socials-list-item--disabled">
                                    <button class="btn btn-social" type="button">
                                        <SvgSprite
                                            :width="17"
                                            :height="21"
                                            iconId="apple_icon"
                                        />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item-form-column item-form-column--transparent">
                        <div class="item-name" v-if="user.social">
                            <div class="label-title">{{ $t('page.connectedSocialTitle') }}</div>
                            <div class="socials-list">

                                <div
                                    class="socials-list-item"
                                    v-for="(val, index) in user.social"
                                    :key="index"
                                >
                                    <button class="btn btn-social" type="button">
                                        <template v-if="val.provider === 'google'">
                                            <SvgSprite
                                                :width="19"
                                                :height="19"
                                                :iconId="`${val.provider}_icon`"
                                            />
                                        </template>
                                        <template v-else-if="val.provider === 'facebook'">
                                            <SvgSprite
                                                :width="35"
                                                :height="35"
                                                :iconId="`${val.provider}_icon`"
                                            />
                                        </template>
                                        <template v-else>
                                            <SvgSprite
                                                :width="17"
                                                :height="21"
                                                :iconId="`${val.provider}_icon`"
                                            />
                                        </template>
                                    </button>
                                    <div class="actions">
                                        <button
                                            type="button"
                                            class="btn btn--simple"
                                            @click="redirectSocial(val.provider)"
                                        >
                                            {{ $t('page.changeSocialTitle') }}
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn--accent"
                                            @click="removeSocial(val)"
                                        >
                                            {{ $t('page.contextMenu.remove') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- <div class="socials-list-item">
                                    <button class="btn btn-social" type="button">
                                        <SvgSprite
                                            :width="35"
                                            :height="35"
                                            iconId="facebook_icon"
                                        />
                                    </button>
                                    <div class="actions">
                                        <button type="button" class="btn btn--simple">
                                            {{ $t('page.changeSocialTitle') }}
                                        </button>
                                        <button type="button" class="btn btn--accent">
                                            {{ $t('page.contextMenu.remove') }}
                                        </button>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import { validationMixin } from 'vuelidate'
import { email, required, minLength } from 'vuelidate/lib/validators'
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import ChangePassword from '@/components/app/settings-profile/steps/AuthData/components/ChangePassword'
import { MAIN_DOMAIN } from '@/domain'
import { PROTOCOL } from '@/domain'
import CountdownTimer from '@/components/ui/CountdownTimer'
import ClickOutside from 'vue-click-outside'
// import VuePhone from '@/components/ui/VuePhone/VuePhone'
import VueSinglePhone from '@/components/ui/VueSinglePhone/VueSinglePhone'

export default {
    name: "AuthData",
    mixins: [validationMixin],
    components: { 
        SvgSprite, 
        ChangePassword, 
        CountdownTimer,
        // VuePhone,
        VueSinglePhone 
    },
    validations: {
        auth: {
            email: { email, required },
            phone: { required },
            email_code: {
                minLength: minLength(4)
            },
            phone_code: {
                minLength: minLength(4)
            }
        }
    },
    data: () => ({
        mode_email: 'edit',
        mode_phone: 'edit',

        emailFlag: false,
        phoneFlag: false,
        passwordFlag: false,

        isSaveEmail: false,
        isSavePhone: false,

        showEmailMessage: false,
        showPhoneMessage: false,

        emailError: false,
        phoneError: false,

        emailErrorText: null,
        phoneErrorText: null,

        phoneVerifyError: false,
        emailVerifyError: false,

        resendEmailFlag: false,
        resendPhoneFlag: false,

        auth: {
            email: null,
            phone: null,
            email_code: null,
            phone_code: null,
            old_email: null,
            old_phone: null
        },

        googleSignInParams: {
            client_id: '1020732052275-fupkglh2fbf8c44dno9gtlsn8fbg8oej.apps.googleusercontent.com'
        },

        fbEmail: '',

        phoneSettings: {
            defaultCountry: 'ua',
            preferredCountries: ['UA', 'RU'],
            customPhoneClass: ['phone-default', 'without-select'],
            disabledField: false,
            isRepeater: false
        }
    }),
    computed: {
        ...mapGetters(['user', 'getAuthEmailState']),
        validatePhone() { return this.auth.phone !== null && this.auth.phone !== '' && this.auth.phone.length },
        getSocialGoogle() {
            if(this.user.social) return this.user.social.find(item => item.provider === 'google')
            return []
        },
        getSocialFacebook() {
            if(this.user.social) return this.user.social.find(item => item.provider === 'facebook')
            return []
        },
        compareValuesPhone() {
            return this.auth.phone === this.auth.old_phone
        },
        compareValuesEmail() {
            return this.auth.email === this.auth.old_email
        },
        getUserPhone() {
            return this.auth.phone
        }
    },
    methods: {
        ...mapActions(['changePassword', 'userSend', 'userChange', 'connectedSocial', 'getUserAuth', 'removeConnectedSocial']),
        async resendEmail() {
            this.resendEmailFlag = true
            await this.userSend({
                data: {
                    email: this.auth.email
                },
                type:'checkSuccessEmail'
            })
        },
        async resendPhone() {
            const transformPhone = this.auth.phone.replace(/[{()}]/g, '').replace(/\s/g, '')
            this.resendPhoneFlag = true
            await this.userSend({
                data: {
                    phone: transformPhone,
                },
                type: 'checkSuccessPhone'
            })
        },
        async saveEmail() {
            this.mode_email = 'edit'
            this.emailFlag = false
            this.passwordFlag = false
            await this.userSend({
                data: { email: this.auth.email },
                type: 'checkSuccessEmail'
            }).then(data => {
                console.log(data)
                if(!data.message) {
                    this.showEmailMessage = true
                } else {
                    this.mode_email = 'save'
                    this.emailErrorText = data.message.email[0]
                    this.emailError = true
                }
            })
        },
        async savePhone() {
            // const transformPhone = this.auth.phone.replace(/[{()}]/g, '').replace(/\s/g, '')
            this.mode_phone = 'edit'
            this.phoneFlag = false
            this.passwordFlag = false
            await this.userSend({
                data: { phone: this.auth.phone },
                type: 'checkSuccessPhone'
            }).then(data => {
                console.log(data)
                if(!data.message) {
                    this.showPhoneMessage = true
                } else {
                    this.mode_phone = 'save'
                    this.phoneErrorText = data.message.phone[0]
                    this.phoneError = true
                }

            })
        },
        editEmail() {
            this.emailFlag = false
            this.auth.old_email = this.auth.email
            this.mode_email = 'save'
            this.passwordFlag = false
        },
        editPhone() {
            this.phoneFlag = false
            this.auth.old_phone = this.auth.phone
            this.mode_phone = 'save'
            this.passwordFlag = false
        },
        addEmail() {
            this.mode_email = 'save'
        },
        addPhone() {
            this.mode_phone = 'save'
        },
        resetValueEmail() {
            if(this.mode_email === 'save') {
                this.mode_email = 'edit'
                this.auth.email = this.user.email
            }
        },
        resetValuePhone() {
            if(this.mode_phone === 'save') {
                this.mode_phone = 'edit'
                this.auth.phone = this.user.phone
            }
        },
        resetEmailErrors() {
            this.emailError = false
            this.emailErrorText = null
        },
        resetErrors(type) {
            this[`${type}Error`] = false
            this[`${type}ErrorText`] = false
        },
        changeFieldFlag(type) {
            console.log(type)
            if(type === 'email' && !this.compareValuesEmail) {
                console.log('EMAIL')
                this.mode_phone = 'edit'
                this.auth.phone = this.user.phone.replace('+', '')
            } else if(type === 'phone' && !this.compareValuesPhone) {
                console.log('PHONE')
                this.mode_email = 'edit'
                this.auth.email = this.user.email
            } else {
                return false
            }
        },
        changePassword() { 
            this.passwordFlag = !this.passwordFlag
            this.mode_email = 'edit'
            this.mode_phone = 'edit' 
        },
        updatePassword(value) {
            this.$emit('updatePassword', value)
        },
        changeField() { this.$emit('changeField') },
        async verifyEmail() {
            await this.userChange({
                data: {
                    verification_token: this.auth.email_code,
                    email: this.auth.email
                }
            }).then(data => {
                console.log(data)
                if(!data.success) {
                    this.emailVerifyError = true
                } else {
                    this.emailVerifyError = false
                    this.isSaveEmail = true
                    this.showEmailMessage = false
                    this.getUserAuth()
                    this.$emit('isSave')
                }
            })
        },
        async verifyPhone() {
            const transformPhone = this.auth.phone.replace(/[{()}]/g, '').replace(/\s/g, '')
            await this.userChange({
                data: {
                    verification_token: this.auth.phone_code,
                    phone: transformPhone
                }
            }).then(data => {
                console.log(data)
                if(!data.success) {
                    this.phoneVerifyError = true
                } else {
                    this.phoneVerifyError = false
                    this.isSavePhone = true
                    this.showPhoneMessage = false
                    this.getUserAuth()
                    this.$emit('isSave')
                }
            })
        },
        initFacebook() {
            window.fbAsyncInit = function() {
                window.FB.init({
                    appId: "364617481388061", //You will need to change this
                    cookie: true, // This is important, it's not enabled by default
                    version: 'v2.6'
                });
            }
        },
        loadFacebookSDK(d, s, id) {
            let js,
                fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/ru_RU/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        },
        onSignInError (error) {
            console.log('OH NOES', error)
        },
        async onSignInSuccess (googleUser) {
            const profile = googleUser.getBasicProfile()
            const email = profile.getEmail()
            const response = await this.connectedSocial({
                data: {
                    email,
                    provider: 'google'
                }
            })
            console.log(response)
            console.log(email, profile)
            await this.getUserAuth()
        },
        async logInWithFacebook() {
            let th = this
            console.log('FB', window.FB, th)
            await window.FB.login(function(response) {
                console.log('response', response)
                if (response.authResponse) {
                    console.log(response)
                    console.log("You are logged in &amp; cookie set!")
                    window.FB.api('/me', 'GET', {'fields': 'email'}, async function(response) {
                        console.log(response)
                        th.fbEmail = response.email
                        await th.connectedSocial({
                            data: {
                                email: th.fbEmail,
                                provider: 'facebook'
                            }
                        })
                        await th.getUserAuth()
                        console.log('Successful login for: ' + response.email);
                        console.log('Successful login for: ' + th.fbEmail);
                    });
                } else {
                console.log("User cancelled login or did not fully authorize.")
                }
            }, {scope: 'public_profile, email'})
            return false
        },
        async changeSocial(val) {
            const providerTitle = `${val.provider}-button`
            if(val.provider === 'google') {
                this.$refs[providerTitle].$el.click()
                return
            } else {
                this.$refs[providerTitle].click()
                return
            }
        },
        async removeSocial(val) {
            console.log('removeSocial', val.provider)
            const response = await this.removeConnectedSocial({
                data: {
                    provider: val.provider
                }
            })
            console.log(response)
            await this.getUserAuth()
            this.$emit('isSave')
        },
        redirectSocial(provider) {
            console.log(provider, MAIN_DOMAIN, this.$route)
            // const checkRoute = this.$route.fullPath === '/' ? '' : this.$route.fullPath
            // localStorage.setItem('fullPath', JSON.stringify(`${checkRoute}?social=true`))
            localStorage.setItem('isOpenSettings', true)
            // this.$router.push({ path: `social?provider=${provider}&t=${JSON.parse(localStorage.getItem('token'))}&domain=${JSON.parse(localStorage.getItem('domain'))}` })
            // console.log(`https://${MAIN_DOMAIN}/social?provider=${provider}&t=${JSON.parse(localStorage.getItem('token'))}`)
            window.location.href = `${PROTOCOL}://${MAIN_DOMAIN}/social?provider=${provider}&t=${JSON.parse(localStorage.getItem('token'))}&domain=${JSON.parse(localStorage.getItem('domain'))}`
        
        },
        endTimer(value) {
            switch(value) {
                case 'phone':
                    this.resendPhoneFlag = false
                    break
                case 'email':
                    this.resendEmailFlag = false
                    break
                default: 
                    return false
            }
        },
        inputHandler({ phone, code }) {
            console.log(phone, code)
            this.auth.phone = phone
            this.phoneSettings.defaultCountry = code
            this.changeFieldFlag('phone')
        },
        updateInput(val) {
            this.auth.phone = val
            this.changeFieldFlag('phone')
        },
        onValidate(val) {
            console.log(val)
        }
    },
    async created() {
        await this.loadFacebookSDK(document, "script", "facebook-jssdk")
        await this.initFacebook()
    },
    mounted() {
        const { phone, email } = this.user
        console.log(phone)
        this.auth.email = email
        this.auth.phone = phone
        console.log(this.$refs)
    },

    directives: { ClickOutside }

}
</script>
<style lang="sass">
    .item-form
        input
            &:read-only
                border-bottom: 0px solid transparent

    .dialog-content
        .item-form
            min-height: 430px
            .item-name
                .label-title
                    line-height: 1
                    margin-bottom: 7px

    .item-form
        &-column
            border: 1px solid #E3F0FF
            padding: 20px
            border-radius: 10px
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.03)
            &--fill
                background: #F4F9FF
                box-shadow: none
                border: none
                .label-title
                    margin-bottom: 20px!important
            &--transparent
                background: transparent
                box-shadow: none
                border: none
                .label-title
                    margin-bottom: 20px!important
            .field-wrapper
                position: relative
                &:not(:last-child)
                    margin-bottom: 25px
                .form-actions
                    &-password
                        display: flex
                        align-items: flex-end
                        .error-icon
                            line-height: 1
                            margin-left: 15px
                .form-field
                    &-password
                        width: 100%
                        max-width: 200px
                        .label-title
                            margin-bottom: 0
                    .field-error-text
                        position: absolute
                        top: calc(100% + 5px)
                        font-size: 13px
                        font-weight: 500
                        word-break: break-word
                        line-height: 1
                        color: #FF9F2F
                .field-actions
                    display: flex
                    align-items: flex-end
                    + .field-messages
                        margin-top: 15px
                    button
                        line-height: 1
                        &:disabled
                            opacity: .5
                    .form-field
                        margin-right: 20px
                        .label-title
                            display: flex
                            justify-content: space-between
                            margin-bottom: 0
                            &-success
                                font-size: 13px
                                font-weight: 500
                                text-transform: capitalize
                                color: #4ECA80
                        .field
                            min-width: 200px
                .field-messages
                    .message
                        margin-bottom: 20px
                        &-text
                            display: inline-block
                            font-weight: 300
                            font-size: 13px
                            line-height: 17px
                            color: #7E7E7E
                            strong
                                font-weight: 700
                                span
                                    text-transform: uppercase
                            .countdown-wrapper
                                display: inline-flex
                                .countdown-label
                                    margin-right: 2px
                                .countdown
                                    display: flex
                                    .countdown-item
                                        color: #9DCCFF
                                        &:not(:last-child)
                                            margin-right: 2px
                                        .digit
                                            font-weight: bold
                                            margin-right: 2px
            .socials-list
                display: flex
                align-items: center
                &-item
                    display: flex
                    align-items: center
                    &:not(:last-child)
                        margin-right: 25px
                    &--disabled
                        pointer-events: none
                        opacity: .5
                    > .btn
                        display: flex
                        align-items: center
                        justify-content: center
                        background: #fff
                        border-radius: 50%
                        box-shadow: 0px 3px 7px rgba(0, 0, 0, 0.1)
                        width: 35px
                        height: 35px
                        .g-signin-button
                            line-height: 1
                            height: 19px
                    .actions
                        display: flex
                        align-items: center
                        margin-left: 15px
                        .btn
                            font-weight: 500
                            font-size: 13px
                            line-height: 1
                            text-decoration: underline
                            margin-right: 15px
                            &--simple
                               color: #9DCCFF
                            &--accent
                                color: #5893D4
.btn
    &-change
        font-size: 13px
        line-height: 1
        font-weight: 400
        text-decoration: underline
        color: #5893D4!important
        &-pass
            font-size: 13px
            line-height: 1
            font-weight: 400
            text-decoration: underline
            margin-left: auto
            align-self: center
            color: #9DCCFF!important
    &-toggle
        position: absolute
        bottom: 0
        right: 0
        .icon
            height: 17px
        &:disabled
            opacity: .5

</style>
