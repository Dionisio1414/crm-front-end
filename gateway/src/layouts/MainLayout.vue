<template>
  <v-app class="app-main-layout">

    <v-app-bar app>
      <Navbar @click="isOpen = !isOpen" />
    </v-app-bar>

    <v-navigation-drawer app>
      <Sidebar v-model="isOpen" />
    </v-navigation-drawer>

    <v-main>
      <v-container fluid>
          <router-view />
          <div class="app-footer">
            <Footer />
          </div>
      </v-container>
    </v-main>

  </v-app>
</template>

<script>
import Navbar from '@/components/app/Navbar'
import Sidebar from '@/components/app/Sidebar'
import Footer from '@/components/app/Footer'

export default {
  name: 'main-layout',
  data: () => ({
    isOpen: true
  }),
  components: {
    Navbar, Sidebar, Footer
  }
}
</script>