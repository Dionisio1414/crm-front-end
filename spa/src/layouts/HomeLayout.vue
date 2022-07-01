<template>
  <v-app class="app-home-layout">
    <v-app-bar app>
      <Navbar @click="isOpen = !isOpen" />
    </v-app-bar>

    <v-navigation-drawer app class="home-sidebar">
      <Sidebar v-model="isOpen" />
    </v-navigation-drawer>

    <v-main>
      <v-container fluid>
          <router-view />
      </v-container>
    </v-main>

  </v-app>
</template>

<script>
import Navbar from '@/components/app/Navbar'
import Sidebar from '@/components/app/Sidebar'

export default {
  name: 'home-layout',
  data: () => ({
    isOpen: false
  }),
  components: {
    Navbar, Sidebar
  }
}
</script>

<style lang="sass">

</style>