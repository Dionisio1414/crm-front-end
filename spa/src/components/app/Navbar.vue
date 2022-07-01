<template>
  <div class="navbar-wrapper" :class="{'navbar-wrapper-home': $route.name === 'home'}">
    <transition name="fade">
      <UserDetails 
        v-if="toggleUser" 
        @close="close"
      >
      </UserDetails>
    </transition>
    <div class="navbar-tabs">
      <transition-group name="space" class="tabs-list" tag="div">
      <!-- <div class="tabs-list"> -->
        
        <!-- <router-link 
          tag="div" 
          class="tabs-list-item" 
          v-for="(val, index) in computedTabs"
          :to="val.url" 
          :key="index">
            <span class="tab-text">{{ $t(val.title) }}</span>
            <button type="button" class="btn btn-delete" @click="deleteCurrentTab">
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
              </svg>
            </button>
        </router-link> -->
          <div 
            class="tabs-list-item" 
            v-for="(val, index) in computedTabs" 
            :key="index" 
            :class="{
              'tabs-list-item--medium': computedTabs.length > 5 && index < 5 ? true : false, 
              'tabs-list-item--small': computedTabs.length >= 5 && index >= 5 ? true : false
            }" 
            :style="{'z-index': index}"
            >
            <router-link  
              tag="span"
              :to="val.url"
              class="tab-text"
            >
              <v-tooltip bottom :disabled="computedTabs.length < 6">
                <template v-slot:activator="{ on, attrs }">
                  <span class="tooltip-icon" v-bind="attrs" v-on="on">
                    {{ $t(val.title) }}
                  </span>
                </template>
                <span>
                  {{ $t(val.title) }}
                </span>
              </v-tooltip>
            </router-link>
            <button type="button" class="btn btn-delete" @click="deleteCurrentTab(index)">
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
              </svg>
            </button>
          </div>

        <!-- <router-link v-for="(link, index) in selectedBookmarks" :key="index" :to="link.url">
          {{ link.title }}
        </router-link> -->

        <!-- <div class="tabs-list-item">
          <span class="tab-text">Куртка красная XL</span>
          <button type="button" class="btn btn-delete">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
            </svg>
          </button>
        </div>
        <div class="tabs-list-item">
          <span class="tab-text">Куртка красная XL</span>
          <button type="button" class="btn btn-delete">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
            </svg>
          </button>
        </div>
        <div class="tabs-list-item">
          <span class="tab-text">Куртка красная XL</span>
          <button type="button" class="btn btn-delete">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
            </svg>
          </button>
        </div>
        <div class="tabs-list-item">
          <span class="tab-text">Куртка красная XL</span>
          <button type="button" class="btn btn-delete">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
            </svg>
          </button>
        </div> -->

      <!-- </div> -->
      </transition-group>
    </div>
    <div class="navbar-actions">
      <div class="messages">
        <v-badge
            color="#FF9F2F"
            content="6"
        >
          <svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M25.2428 8.19706C24.7622 8.44181 24.2508 8.6204 23.7224 8.72798C23.9893 9.54633 24.1277 10.4011 24.1327 11.2619C24.1143 12.4967 23.8396 13.7143 23.3258 14.8374C22.8121 15.9604 22.0705 16.9644 21.1482 17.7858C21.0674 17.8608 21.0028 17.9516 20.9585 18.0526C20.9142 18.1536 20.8912 18.2627 20.8908 18.373V22.6686L17.3675 20.4243C17.2692 20.3621 17.1586 20.3221 17.0433 20.3068C16.9281 20.2915 16.8109 20.3014 16.6998 20.3358C15.46 20.7209 14.169 20.9161 12.8707 20.915C6.66061 20.915 1.60884 16.5872 1.60884 11.2619C1.60884 5.93663 6.66061 1.60884 12.8707 1.60884C14.11 1.60869 15.3427 1.78751 16.5309 2.13976C16.581 1.6057 16.7028 1.0808 16.8929 0.579184C15.586 0.198018 14.232 0.00303665 12.8707 0C5.77575 0 0 5.05177 0 11.2619C0 17.472 5.77575 22.5238 12.8707 22.5238C14.1982 22.5223 15.5191 22.3382 16.7963 21.9768L21.2448 24.8084C21.3662 24.8863 21.5064 24.9301 21.6506 24.9352C21.7948 24.9404 21.9377 24.9066 22.0644 24.8375C22.191 24.7684 22.2968 24.6665 22.3705 24.5425C22.4443 24.4185 22.4833 24.2769 22.4836 24.1327V18.7189C23.4901 17.7533 24.2933 16.5962 24.8459 15.3155C25.3985 14.0349 25.6893 12.6566 25.7013 11.2619C25.7101 10.2226 25.5554 9.18831 25.2428 8.19706Z" fill="#F4F9FF"/>
          </svg>
        </v-badge>
      </div>
      <div class="notifications">
        <v-badge
            color="#FF9F2F"
        >
          <svg width="19" height="25" viewBox="0 0 19 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18.1015 20.0678C18.0328 20.0217 17.5161 19.6473 16.9986 18.5148C16.0483 16.4352 15.8489 13.5055 15.8489 11.4141C15.8489 11.405 15.8487 11.396 15.8484 11.3869C15.838 8.62287 14.2375 6.24013 11.947 5.17105V3.53602C11.947 2.05229 10.7816 0.845215 9.34918 0.845215H9.13389C7.70144 0.845215 6.53606 2.05229 6.53606 3.53602V5.17095C4.23796 6.24348 2.63419 8.63839 2.63419 11.4141C2.63419 13.5055 2.43473 16.4351 1.48446 18.5148C0.967053 19.6473 0.450287 20.0216 0.38158 20.0678C0.0922421 20.2062 -0.0504196 20.5261 0.0161436 20.8503C0.0833456 21.1776 0.379253 21.4033 0.702397 21.4033H5.71845C5.74647 23.3914 7.31561 25 9.24156 25C11.1675 25 12.7367 23.3914 12.7647 21.4033H17.7807C18.1038 21.4033 18.3998 21.1776 18.467 20.8503C18.5335 20.5261 18.3908 20.2062 18.1015 20.0678ZM7.90478 3.53602C7.90478 2.83398 8.45617 2.26285 9.13394 2.26285H9.34923C10.027 2.26285 10.5784 2.83398 10.5784 3.53602V4.71154C10.1464 4.61921 9.69929 4.57062 9.24138 4.57062C8.7836 4.57062 8.33659 4.61917 7.90482 4.7114V3.53602H7.90478ZM9.24156 23.5824C8.07029 23.5824 7.11491 22.6097 7.08713 21.4034H11.3959C11.3682 22.6097 10.4128 23.5824 9.24156 23.5824ZM11.9972 19.9856C11.997 19.9856 2.262 19.9856 2.262 19.9856C2.38039 19.7948 2.50088 19.5788 2.62064 19.3347C3.53784 17.4644 4.00291 14.7996 4.00291 11.4141C4.00291 8.42227 6.35288 5.98825 9.24133 5.98825C12.1298 5.98825 14.4798 8.42227 14.4798 11.4165C14.4798 11.4252 14.4799 11.4339 14.4802 11.4426C14.4828 14.8144 14.9478 17.4697 15.8625 19.3347C15.9822 19.5789 16.1028 19.7948 16.2211 19.9856H11.9972Z" fill="#F4F9FF"/>
          </svg>
        </v-badge>
      </div>
      <div class="user" @click="toggleUserInfo">
        <div class="user-info" v-if="userInfoOpen">
          <div class="user-info-header" v-if="user">
            <div class="user-name" @click="showUserSettings">{{ user.first_name }} {{user.last_name}}</div>
            <div class="user-position">{{ defaultPositionTitle.title }}</div>
          </div>
          <div class="user-info-content">
            <div class="user-actions">
              <div class="user-action">{{$t('user.cabinet')}}</div>
              <div class="user-action" @click="initModal">{{$t('user.settings')}}</div>
              <div class="user-action">{{$t('user.tasks')}}</div>
              <!-- <div class="user-action">{{$t('user.events')}}</div>
              <div class="user-action">{{$t('user.reminders')}}</div> -->
            </div>
            <div class="user-exit" @click="logout">{{$t('user.exit')}}</div>
          </div>
        </div>
        <div class="user-avatar">
          <div class="avatar-wrap">
            <img src="@/assets/img/avatar.png" alt="" v-if="isAvatar">
            <svg class="default-icon" v-if="!isAvatar && !userInfoOpen" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 0C9.158 0 0 9.158 0 20C0 30.842 9.158 40 20 40C30.842 40 40 30.842 40 20C40 9.158 30.842 0 20 0ZM20 10C23.454 10 26 12.544 26 16C26 19.456 23.454 22 20 22C16.548 22 14 19.456 14 16C14 12.544 16.548 10 20 10ZM9.788 29.544C11.582 26.904 14.574 25.144 18 25.144H22C25.428 25.144 28.418 26.904 30.212 29.544C27.656 32.28 24.03 34 20 34C15.97 34 12.344 32.28 9.788 29.544Z" fill="#9DCCFF" />
            </svg>
            <svg class="default-icon default-icon--active" v-if="!isAvatar && userInfoOpen" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 0C9.158 0 0 9.158 0 20C0 30.842 9.158 40 20 40C30.842 40 40 30.842 40 20C40 9.158 30.842 0 20 0ZM20 10C23.454 10 26 12.544 26 16C26 19.456 23.454 22 20 22C16.548 22 14 19.456 14 16C14 12.544 16.548 10 20 10ZM9.788 29.544C11.582 26.904 14.574 25.144 18 25.144H22C25.428 25.144 28.418 26.904 30.212 29.544C27.656 32.28 24.03 34 20 34C15.97 34 12.344 32.28 9.788 29.544Z" fill="#9DCCFF"/>
            </svg>
          </div>
          <svg class="avatar-icon" :class="{rotate: userInfoOpen}" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="7.5" cy="7.5" r="7.5" fill="#FF9F2F"/>
            <path d="M5.13576 8.46642L8.92315 12.3602C9.01075 12.4503 9.12769 12.5 9.25237 12.5C9.37706 12.5 9.49399 12.4503 9.58159 12.3602L9.86051 12.0735C10.042 11.8867 10.042 11.5831 9.86051 11.3966L6.68014 8.12681L9.86404 4.85341C9.95163 4.76328 10 4.64313 10 4.51501C10 4.38675 9.95163 4.2666 9.86404 4.1764L9.58512 3.88978C9.49745 3.79965 9.38059 3.75 9.2559 3.75C9.13122 3.75 9.01428 3.79965 8.92668 3.88978L5.13576 7.78713C5.04795 7.87755 4.99972 7.99827 5 8.1266C4.99972 8.25543 5.04795 8.37608 5.13576 8.46642Z" fill="white"/>
          </svg>
        </div>
      </div>
    </div>
    <settings-profile
      v-if="isModalOpen"
      :isModalOpen="isModalOpen"
      @save="saveSettings"
      @close="closeSettings"
    >
    </settings-profile>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import ClickOutside from 'vue-click-outside'
import SettingsProfile from '@/components/app/settings-profile/SettingsProfile'
import UserDetails from '@/components/ui/UserDetails'

export default {
  name: "Navbar",
  components: {
    SettingsProfile,
    UserDetails
  },
  data: () => ({
    isAvatar: false,
    userInfoOpen: false,
    toggleUser: false,
    isModalOpen: false
  }),

  computed: {
    ...mapGetters([
        'user',
        'tabs',
        'getLists'
    ]),
    computedTabs() {
      return this.tabs
    },
    positionsList() {
      return this.getLists('core_lists')['positions']
    },
    defaultPositionTitle() {
      const idx = +this.user.position_id
      return this.positionsList.find(({ directory_id }) => directory_id === idx)
    }
  },
  methods: {
    ...mapActions([
      'deleteTab',
      'fetchLists',
      'initialTabs',
      'getUserAuth'
    ]),
    logout() {
      this.$store.dispatch('logout')
    },
    closeSettings() {
      this.isModalOpen = false
    },
    async toggleUserInfo() {
      if(!this.userInfoOpen) {
        await this.fetchLists({ type: 'core', resources: 'positions' })
        this.userInfoOpen = true
      } else {
        this.userInfoOpen = false
      }
    },
    initModal() {
      this.isModalOpen = true
    },
    saveSettings() {
      console.log('save')
    },
    deleteCurrentTab(index) {
      console.log('delete')
      this.deleteTab(index)
    },
    showUserSettings() {
      this.toggleUser = true
    },
    close() {
      this.toggleUser = false
    },
    async checkParams() {
      // const queryParams = window.location.search 
      // const queryParamsKey = queryParams ? queryParams.split('=')[0].slice(1) : ''
      // const queryParamsValue = queryParams ? queryParams.split('=')[1] : ''
      const isOpenSettings = localStorage.getItem('isOpenSettings')
      // if(queryParamsKey === 'social' && queryParamsValue === "true") this.isModalOpen = true
      if(isOpenSettings) {
        await this.getUserAuth()
        this.isModalOpen = true
        localStorage.removeItem('isOpenSettings')
        
      }
    }
  },
  created() {
    this.initialTabs()
  },
  mounted() {
    this.checkParams()
  },
  directives: {
    ClickOutside
  }
}
</script>

<style scoped lang="sass">

.fade-enter-active, 
.fade-leave-active
  transition: opacity .25s

.fade-enter, 
.fade-leave-to
  opacity: 0

.space-enter-active
  animation: spaceInDown .35s ease-in-out 

.space-leave-active 
  animation: spaceOutLeft .3s ease-in-out 

@keyframes spaceInDown
  0%
    opacity: 0
    transform-origin: 50% 100% 50px
    transform: scale(0.25) translate(0%, 400%)

  100% 
    opacity: 1
    transform-origin: 50% 100% 50px
    transform: scale(1) translate(0%, 0%)

@keyframes spaceOutLeft
  0%
    opacity: 1
    transform-origin: 0% 50%
    transform: scale(1) translate(0%, 0%)

  100%
    opacity: 0
    transform-origin: 0% 50%
    transform: scale(0.2) translate(-200%, 0%)

</style>