import Vue from 'vue';
import App from './App.vue';
import './registerServiceWorker';
import router from './router';
import store from './store';
import vuetify from '@/plugins/vuetify';
import Vuelidate from 'vuelidate';
import VueAnime from 'vue-animejs';
import {i18n} from '@/plugins/i18n';
import draggable from 'vuedraggable';
import simplebar from 'simplebar-vue';
import VueContext from 'vue-context';
import VuetifyDraggableTreeview from 'vuetify-draggable-treeview';
import {DraggableTree} from 'vue-draggable-nested-tree';
import GSignInButton from 'vue-google-signin-button';
import filters from './filters';
import { VueMaskDirective } from 'v-mask';
import VueAwesomeSwiper from 'vue-awesome-swiper';
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite";
import { Plugin } from 'vue-fragment';
import VueClipboard from 'vue-clipboard2'
import frag from 'vue-frag';
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import WsService from '@/services/ws-service/ws-service'

/* socket */
window.io = require('socket.io-client')
Vue.prototype.$echo = WsService

console.log('Vue.prototype.$echo', Vue.prototype.$echo)
// export var echo_instance = new Echo({
//   broadcaster: 'socket.io',
//   host: `${MAIN_DOMAIN}:6001`,
//   transports: ['websocket'],
//   auth: {
//     headers: {
//       Authorization: `Bearer ${getAccessToken()}`
//     }
//   }
// })
//
// Vue.prototype.$echo = echo_instance
//
// export default Vue

// window.Echo = new Echo({
//   broadcaster: 'socket.io',
//   authEndpoint: 'api/broadcasting/auth',
//   host: `${WEBSOCKET_DOMAIN}`,
//   transports: ['websocket'],
//   auth: {
//     headers: {
//       Authorization: `Bearer ${getAccessToken()}`
//     }
//   }
// })

// console.log(window.Echo);

// /* public test channel */
// window.Echo.channel('chat').listen('.public.message', (e) => {
//   console.log(e, 'public');
// });
// /* private test channel */
// window.Echo.private('user.599').listen('.private.message', (e) => {
//   console.log(e, 'private');
// });

// /* private channel company-create */
// window.Echo.private('user.599').listen('.company.create', (e) => {
//   console.log(e, 'private company');
// });

/* end socket */

// import style
Vue.use(VueAwesomeSwiper, /* { default options with global component } */)

import 'simplebar-vue/dist/simplebar.min.css';
import 'swiper/css/swiper.css';
import 'vue-context/src/sass/vue-context.scss';
import VueTelInput from 'vue-tel-input';
import LottieAnimation from 'lottie-web-vue';

filters()

Vue.component('draggable', draggable);
Vue.component('DraggableTree', DraggableTree);
Vue.component('simplebar', simplebar);
Vue.component('VueContext', VueContext);
Vue.component('svg-sprite', SvgSprite);
Vue.component('vue-phone-number-input', VuePhoneNumberInput);

Vue.directive('mask', VueMaskDirective);
Vue.directive('frag', frag);

export const eventBus = new Vue()
Vue.use(VuetifyDraggableTreeview)
Vue.use(VueAnime)
Vue.use(Vuelidate)
Vue.use(GSignInButton)
Vue.use(VueTelInput)
Vue.use(LottieAnimation)
Vue.use(Plugin)
Vue.use(VueClipboard)
Vue.config.productionTip = false
Vue.config.devtools = true
VueClipboard.config.autoSetContainer = true

new Vue({
  i18n,
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
