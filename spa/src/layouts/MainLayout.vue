<template>
  <v-app class="app-main-layout">
    <v-app-bar app>
      <Navbar @click="isOpen = !isOpen" />
    </v-app-bar>

    <v-navigation-drawer app class="main-sidebar">
      <Sidebar v-model="isOpen" />
    </v-navigation-drawer>

    <v-main>
      <v-container fluid>
          <router-view />
          <!-- <div class="app-footer"> -->
          <Footer />
          <!-- </div> -->
      </v-container>
    </v-main>

   <!-- <portal to="destination" target-class="class1 class2" slim>
      <p>This slot content will be rendered wherever th with name 'destination' is located.</p>
    </portal> -->
<!-- 
    <portal-target name="destination" slim>

    </portal-target>  -->

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

<style lang="sass">

</style>