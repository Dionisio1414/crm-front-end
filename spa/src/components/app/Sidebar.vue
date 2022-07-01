<template>
  <div>
    <div class="drawer-top">
      <router-link to="/" class="logo">
        <img alt="Easysell" src="@/assets/img/logo.svg">
      </router-link>
      <div class="organization">{{ user.company.name }}</div>
    </div>

    <ul class="main-nav">
      <router-link tag="li" class="nav-item" active-class="active" to="/products">
        <a class="nav-link">
          <span class="nav-icon">
            <svg-sprite width="18" height="23" icon-id="goods"></svg-sprite>
        </span>
          <span>Товары</span>
          <span class="nav-arrow">
            <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
          </span>
        </a>
        <ul class="sub-nav">
          <router-link
              tag="li"
              class="nav-item"
              active-class="active"
              v-for="({ url, icon, title }) in menu.products"
              :key="url"
              :to="url"
          >
            <a href="/">
              <span class="nav-icon">
                <svg-sprite :width="icon.w" :height="icon.h" :icon-id="icon.id"></svg-sprite>
              </span>
              <span>{{ title }}</span>
            </a>
          </router-link>
        </ul>
      </router-link>
      <router-link tag="li" class="nav-item" active-class="active" to="/crm">
        <a class="nav-link">
          <span class="nav-icon">
            <svg-sprite width="21" height="21" icon-id="crm"></svg-sprite>
        </span>
          <span>CRM</span>
          <span class="nav-arrow">
            <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
          </span>
        </a>
        <ul class="sub-nav">
          <router-link
              tag="li"
              class="nav-item"
              active-class="active"
              v-for="({ url, icon, title }) in menu.crm"
              :key="url"
              :to="url"
          >

            <a href="/">
              <span class="nav-icon">
                <svg-sprite :width="icon.w" :height="icon.h" :icon-id="icon.id"></svg-sprite>
              </span>
              <span>{{ $t(title) }}</span>
            </a>
          </router-link>
        </ul>
      </router-link>
      <router-link 
        tag="li" 
        class="nav-item" 
        active-class="active" 
        to="/directories"
      >
        <a class="nav-link">
          <span class="nav-icon">
            <svg-sprite width="20" height="20" icon-id="directory"></svg-sprite>
          </span>
          <span>Справочники</span>
          <span class="nav-arrow">
           <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
          </span>
        </a>
        <ul class="sub-nav">
          <router-link
              tag="li"
              class="nav-item"
              active-class="active"
              v-for="link in menu.directories"
              :key="link.url"
              exact-path
              :to="link.url"
          >
            <a href="/">
              <span class="nav-icon" v-html="link.icon">
              {{ link.icon }}
            </span>
              <span>{{ link.title }}</span>
            </a>
          </router-link>
        </ul>
      </router-link>
      <router-link tag="li" class="nav-item" active-class="active" to="/report">
        <a class="nav-link">
          <span class="nav-icon">
            <svg-sprite width="22" height="22" icon-id="report"></svg-sprite>
          </span>
          <span>Отчеты</span>
          <span class="nav-arrow">
            <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
          </span>
        </a>
      </router-link>
      <router-link
          tag="li" class="nav-item"
          active-class="active"
          to="/system_management"
      >
        <a class="nav-link">
          <span class="nav-icon">
            <svg-sprite width="20" height="20" icon-id="manageSystem"></svg-sprite>
        </span>
          <span>Управление системой</span>
          <span class="nav-arrow">
            <svg-sprite width="6" height="11" icon-id="sidebarArrowRight"></svg-sprite>
          </span>
        </a>
        <ul class="sub-nav">
          <router-link
              tag="li"
              class="nav-item"
              active-class="active"
              v-for="link in menu.systemManagement"
              :key="link.url"
              :to="link.url"
          >
            <a href="/">
              <span class="nav-icon" v-html="link.icon">
              {{ link.icon }}
            </span>
              <span>{{ link.title }}</span>
            </a>
          </router-link>
        </ul>
      </router-link>
    </ul>

    <div class="main-controls">
      <button type="button" class="logout-btn" @click="logout">
        <svg-sprite width="21" height="22" icon-id="logout"></svg-sprite>
      </button>
    </div>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
  name: "Sidebar",
  data: () => ({
    companyName: null,
  }),

  computed: {
    ...mapGetters([
      'menu',
      'user'
    ]),
  },

  methods: {
    logout() {
      this.$store.dispatch('logout')
    },
  },

  created() {
    // const name = this.user.company
    // if (name !== null) {
    //   console.log(name)
    //   this.companyName = name
    // }
  },

  mounted() {
    console.log('sidebar user', this.user)
  }
}
</script>
