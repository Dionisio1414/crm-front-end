import Vue from "vue"
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

export const i18n = new VueI18n({
  locale: localStorage.getItem('locale') || 'ru',
  fallbackLocale: 'ua',
  messages: {
    ru: require('../locale/ru.json'),
    ua: require('../locale/ua.json')
  }
})