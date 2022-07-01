<template>
  <v-app-bar app>
    <v-container>
      <img src="@/assets/img/logo2.svg" alt="Easysell">
      <v-select class="lang-switch select-switcher"
          v-model="langActive"
          :items="languages"
          item-text="name"
          item-value="code"
          solo
          dense
          @change="setLocale"
      >
      </v-select>
    </v-container>
  </v-app-bar>
</template>

<script>
export default {
  name: "NavbarEmpty",
  data: () => ({
    langActive: localStorage.getItem('locale') || 'ru',
    languages: [
      {
        code: 'ru',
        name: 'Русский',
      },
      {
        code: 'ua',
        name: 'Українська',
      },
    ]
  }),

  methods: {
    setLocale(locale){
      this.$i18n.locale = locale
      console.log(this.$i18n.locale)
      localStorage.setItem('locale', this.$i18n.locale )
    },
  }
}
</script>

<style scoped>

</style>