<template>
  <div class="login-actions-list">
    <div class="login-action login-facebook" @click="logInWithFacebook"></div>
    <div class="login-action login-google">
      <g-signin-button
          :params="googleSignInParams"
          @success="onSignInSuccess"
          @error="onSignInError">
      </g-signin-button>
    </div>
    <div class="login-action login-apple"></div>
    <div class="login-or">{{$t('auth.or')}}</div>
  </div>
</template>

<script>
export default {
  name: "SocialAuth",
  props: {
    type: String
  },
  data: () => ({
    fbEmail: '',
    googleSignInParams: {
      client_id: '1020732052275-fupkglh2fbf8c44dno9gtlsn8fbg8oej.apps.googleusercontent.com'
    }
  }),
  methods: {

    /* Sign in from google */

    onSignInSuccess (googleUser) {
      const profile = googleUser.getBasicProfile()
      const email = profile.getEmail()
      // this.registerSocial(profile.$t, 'google')
      if(this.type !== 'login') this.registerSocial(email, 'google')
      else this.loginSocial(email, 'google')
    },
    onSignInError (error) {
      console.log('OH NOES', error)
    },

    /* Sign in from facebook */

    async logInWithFacebook() {
      let th = this
      console.log('FB', window.FB, th)
      await window.FB.login(function(response) {
        console.log('response', response)
        if (response.authResponse) {
          console.log(response)
          console.log("You are logged in &amp; cookie set!")
          window.FB.api('/me', 'GET', {'fields': 'email'}, function(response) {
            console.log(response)
            th.fbEmail = response.email
            if(this.type !== 'login') th.registerSocial(response.email, 'facebook')
            else this.loginSocial(response.email, 'facebook')
            console.log('Successful login for: ' + response.email);
            console.log('Successful login for: ' + th.fbEmail);
          });
        } else {
          console.log("User cancelled login or did not fully authorize.")
        }
      }, {scope: 'public_profile,email'});
      return false;
    },

    initFacebook() {
      window.fbAsyncInit = function() {
        window.FB.init({
          appId: "364617481388061", //You will need to change this
          cookie: true, // This is important, it's not enabled by default
          version: 'v2.6'
        });
      };
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

    async registerSocial(email, provider) {
      console.log('email', email)
      const socialData = {
        email: email,
        provider: provider
      }
      try {
        this.loading = true
        this.$store.dispatch('registerSocial', socialData).then(data => {
          const { data:response } = data
          console.log('response data', response) 
          if(response.message) {
            this.$emit('socialError', response)
          } else {
            window.location.href = `https://${response.user.company.user_main_domain}?user_id=${response.user.id}`
          }
        })
        this.loading = false
        // await this.$router.push('/')
      } catch (e) {
        this.loading = false
        console.log(e)
      }
    },

    async loginSocial(email, provider) {
      const socialData = {
        email: email,
        provider: provider
      }
      try {
        this.loading = true
        this.$store.dispatch('loginSocial', socialData).then(data => {
          const { data:response } = data
          console.log('response data', response) 
          if(response.message) this.$emit('socialError', response)
          if(this.type === 'login' && !response.message) this.$emit('success', response)
        })
        this.loading = false
      } catch (e) {
        this.loading = false
        console.log(e)
      }
    }
  },

  async mounted() {
    await this.loadFacebookSDK(document, "script", "facebook-jssdk")
    await this.initFacebook()
  }
}
</script>

<style scoped>

</style>