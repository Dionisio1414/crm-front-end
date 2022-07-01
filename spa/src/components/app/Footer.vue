<template>
  <div class="app-footer" v-click-outside="hide">
    <div class="bookmarks">
      <Bookmarks 
        v-if="isOpenMenu"
        @exit="closeBookmarks"
      >
      </Bookmarks>
      <div class="bookmark-btn" @click="toggleMenu">
        <img src="@/assets/icons/bookmark.svg" alt="">
      </div>
      <div class="bookmarks-list" v-if="getAllBookmarks.length">
        <router-link v-for="(link, index) in getAllBookmarks" :key="index" :to="link.url">
          {{ link.title }}
        </router-link>
      </div>
    </div>
    <div class="footer-actions">

      <div class="footer-actions-item">
        <div class="icon-wrapper" @click="toggleStatics">
          <SvgSprite
            v-if="!isOpenStatics"
            class="icon" 
            :width="25"
            :height="29"
            iconId="reports"
          />
          <SvgSprite
            v-else
            class="icon" 
            :width="14"
            :height="14"
            iconId="footer-close"
          />
        </div>
      </div>
      <div class="footer-actions-item">
        <div class="icon-wrapper" @click="toggleCalendar">
          <SvgSprite
            v-if="!isOpenCalendar"
            class="icon" 
            :width="25"
            :height="29"
            iconId="calendar"
          />
          <SvgSprite
            v-else
            class="icon" 
            :width="14"
            :height="14"
            iconId="footer-close"
          />
        </div>
        <v-date-picker
          class="footer-calendar"
          v-if="isOpenCalendar"
          v-model="datePicker"
          color="orange lighten-1"
          locale="ru-Ru"
        ></v-date-picker>
      </div>
      <div class="footer-actions-item">
        <div class="icon-wrapper" @click.stop="toggleCalculator">
          <SvgSprite
            v-if="!isOpenCalculator"
            class="icon" 
            :width="20"
            :height="20"
            iconId="calculator"
          />
          <SvgSprite
            v-else
            class="icon" 
            :width="14"
            :height="14"
            iconId="footer-close"
          />
        </div>
        <Calculator 
          :isOpenCalculator="isOpenCalculator" 
          v-if="isOpenCalculator"
          @close="closeCalculator"
        >
        </Calculator>
      </div>
      <div class="footer-actions-item">
        <div class="lang-switch">
          <div class="lang-btn"
              v-for="(lang, index) in languages"
              :class="{ active: lang.code === currentLanguage }"
              @click="setLocale(lang.code)"
              :key="index"
          >
            {{ lang.name }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Calculator from "@/components/ui/calculator/Calculator"
import ClickOutside from 'vue-click-outside'
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import Bookmarks from "@/components/ui/Bookmarks"
import { mapGetters } from 'vuex'

export default {
  name: "Footer",
  components: {
    Calculator,
    SvgSprite,
    Bookmarks
  },
  data: () => ({
    isOpenMenu: false,
    isOpenCalendar: false,
    isOpenCalculator: false,
    isOpenStatics: false,
    orange: '#FF9F2F',
    datePicker: new Date().toISOString().substr(0, 7),
    languages: [
      {
        code: 'ru',
        name: 'Рус',
      },
      {
        code: 'ua',
        name: 'Укр',
      },
    ]
  }),
  beforeCreate() {
    this.$store.commit('initialiseMenu')
  },
  methods: {
    setLocale(locale){
      this.$i18n.locale = locale
      console.log(this.$i18n.locale)
      localStorage.setItem('locale', this.$i18n.locale )
    },
    toggleMenu() {
      this.isOpenMenu = !this.isOpenMenu
    },
    toggleCalendar() {
      this.isOpenCalculator = false
      this.isOpenStatics = false
      this.isOpenCalendar = !this.isOpenCalendar
      console.log('toggleCalendar')
    },
    toggleCalculator() {
      this.isOpenCalendar = false
      this.isOpenStatics = false
      this.isOpenCalculator = !this.isOpenCalculator
    },
    toggleStatics() {
      this.isOpenCalculator = false
      this.isOpenCalendar = false
      this.isOpenStatics = !this.isOpenStatics
    },
    hide() {
      this.isOpenMenu = false
      this.isOpenCalculator = false
      this.isOpenCalendar = false
      this.isOpenStatics = false
    },
    closeBookmarks() { this.isOpenMenu = false },
    closeCalculator() { this.isOpenCalculator = false }
  },
  computed: {
    ...mapGetters([
      'selectedBookmarks',
    ]),
    getAllBookmarks() {
      let bookmarks = this.selectedBookmarks
      let arr = []
      for(let key in bookmarks) {
          const items = this.selectedBookmarks[key] 
          items.forEach(item => arr.push(item))
      } 
      return arr
    },
    currentLanguage(){
      return this.$i18n.locale
    },
  },

  directives: {
    ClickOutside
  }

}
</script>

<style scoped>

</style>