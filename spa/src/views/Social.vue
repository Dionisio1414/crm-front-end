<template>
    <div class="social-container">
        <div class="social-header">
            <v-container>
                <v-row>
                    <v-col cols="3">
                        <div class="social-header-logo">
                            <div class="logo">
                                <img src="@/assets/img/logo2.svg" alt="">
                            </div>
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col col="4">
                        <div class="back" @click="back">
                            <svg width="16" height="28" viewBox="0 0 16 28" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 4L6 14L16 24L14 28L0 14L14 0L16 4Z" fill="#9DCCFF"></path></svg>
                            <span>Вернуться назад</span>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <div class="social-wrapper">
            <div class="social-dialog">
                <div class="social-dialog-wrapper">
                    <div class="social-dialog-icon">
                        <img 
                            class="icon" 
                            :src="require(`@/assets/icons/${currentProvider}.svg`)" 
                            alt=""
                        >
                        <div class="social-actions">
                            <g-signin-button
                                :params="googleSignInParams"
                                @success="onSignInSuccess"
                                @error="onSignInError"
                                ref="google"
                            >
                            </g-signin-button>
                        </div>
                    </div>
                    <div class="social-dialog-title">
                        {{ $t('page.confirmAuthorizationTitle') }}
                    </div>
                    <div class="social-dialog-actions">
                        <button 
                            type="button" 
                            class="base-button base-button--accent" 
                            @click="socialHandler"
                        >
                            {{ $t('page.confirmTitle') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex'
// import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
// import { MAIN_DOMAIN } from '@/constants/constants'

export default {
    name: "Social",
    components: {
        // SvgSprite
    },
    data: () => ({
        currentProvider: null,
        currentToken: window.location.search.split('&')[1].split('=')[1],
        googleSignInParams: {
            client_id: '1020732052275-fupkglh2fbf8c44dno9gtlsn8fbg8oej.apps.googleusercontent.com'
        },
    }),
    methods: {
        ...mapActions(['connectedSocial', 'updateToken']),
        async onSignInSuccess(googleUser) {
            // const { company } = JSON.parse(localStorage.getItem('user'))
            // const nameCompany = company.user_main_domain.split('.').shift()
            // let checkPath = localStorage.getItem('fullPath').startsWith('/') ? JSON.parse(localStorage.getItem('fullPath')) : `/${JSON.parse(localStorage.getItem('fullPath'))}` 
            const profile = googleUser.getBasicProfile()
            const email = profile.getEmail()
            const response = await this.connectedSocial({
                data: {
                    email,
                    provider: 'google'
                }
            })
            console.log(response)
            // window.location.href = `https://${nameCompany}.${MAIN_DOMAIN}?social=true`
            // for localhost
            // await this.$router.push({ path: JSON.parse(localStorage.getItem('fullPath')) })
            // window.location.href = `https://${nameCompany}.${MAIN_DOMAIN}${checkPath}`
            window.history.back()
            console.log(email, profile)
        },
        onSignInError(error) {
            if(error.error !== 'popup_blocked_by_browser') window.history.back()
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
        async logInWithFacebook() {
            let th = this
            // let { company } = JSON.parse(localStorage.getItem('user'))
            // let nameCompany = company.user_main_domain.split('.').shift()
            // let checkPath = localStorage.getItem('fullPath').startsWith('/') ? JSON.parse(localStorage.getItem('fullPath')) : `/${JSON.parse(localStorage.getItem('fullPath'))}` 
            console.log('FB', window.FB, th)
            await window.FB.login(function(response) {
                console.log('response', response)
                if (response.authResponse) {
                    console.log(response)
                    console.log("You are logged in &amp; cookie set!")
                    window.FB.api('/me', 'GET', {'fields': 'email'}, async function(response) {
                        console.log(response)
                        await th.connectedSocial({
                            data: {
                                email: response.email,
                                provider: 'facebook'
                            }
                        })
                        // for localhost
                        // await th.$router.push({ path: JSON.parse(localStorage.getItem('fullPath')) })
                        // window.location.href = `https://${nameCompany}.${MAIN_DOMAIN}${checkPath}`
                        // localStorage.removeItem('fullPath')
                        window.history.back()
                        console.log('Successful login for: ' + response.email);
                        console.log('Successful login for: ' + th.fbEmail);
                    });
                } else {
                console.log("User cancelled login or did not fully authorize.")
                }
            }, {scope: 'public_profile, email'})
            return false
        },
        back() {
            window.history.back()
        },
        socialHandler() {
            if(this.currentProvider === 'google') {
                this.$refs['google'].$el.click()
                console.log('this is google')
            } else if(this.currentProvider === 'facebook') {
                this.logInWithFacebook()
                console.log('this is facebook')
            } else {
                return false
            }
        }
    },
    beforeCreate() {
        const domain = window.location.search.split('&')[2].split('=')[1]
        console.log(domain)
        localStorage.setItem('domain', JSON.stringify(domain))
    },
    async created() {
        console.log('created', this.currentToken)
        await this.loadFacebookSDK(document, "script", "facebook-jssdk")
        await this.initFacebook()
        await this.updateToken(this.currentToken)
    },
    mounted() {
        // setTimeout(async () => await this.checkProvider(this.currentProvider), 500)
        this.currentProvider = window.location.search.split('&')[0].split('=')[1]
    }
}
</script>
<style lang="sass">
    .social
        &-container
            width: 100%
            .back
                position: static
        &-header
            &-logo
                margin-bottom: 15px
        &-wrapper
            display: flex
            justify-content: center
            align-items: center
            height: calc(100vh - 320px)
        &-dialog
            display: flex
            align-items: center
            justify-content: center
            background: rgba(235, 247, 255, 0.1)
            border-radius: 20px
            backdrop-filter: blur(20px)
            border: 1px solid #9DCCFF
            width: 100%
            height: 100%
            max-width: 645px
            max-height: 316px
            &-icon
                text-align: center
                margin-bottom: 25px
                .icon
                    width: 100%
                    height: 100%
                    object-fit: cover
                    max-width: 65px
                    max-height: 65px
                .social-actions
                    position: absolute
                    top: 0
                    visibility: hidden
            &-title
                font-size: 30px
                font-weight: 500
                margin-bottom: 40px
                color: #fff
            &-actions
                text-align: center
                .base-button
                    width: 100%
                    max-width: 250px
                    min-height: 45px

</style>