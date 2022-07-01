<template>
  <div id="app">
    <component :is="layout">
      <router-view/>
    </component>
  </div>
</template>

<script>
import EmptyLayout from '@/layouts/EmptyLayout'
import MainLayout from '@/layouts/MainLayout'
import HomeLayout from '@/layouts/HomeLayout'
import { mapActions } from 'vuex'

export default {
  computed: {
    layout() {
      return (this.$route.meta.layout || 'empty') + '-layout'
    }
  },
  created() {
    // this.fetchSettings()
    console.log('App init')
  },
  mounted() {
    console.log('App init')
    // this.fetchSettings()
  },
  methods: {
    ...mapActions({
      fetchSettings: 'fetchSettings'
    })
  },
  components: {
    EmptyLayout, MainLayout, HomeLayout
  }
}
</script>

<style lang="sass">
@import "sass/main"
</style>