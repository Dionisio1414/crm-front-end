<template>
  <div class="product-item" v-click-outside="hide">
    <div class="product-number">1.</div>
    <div class="product-content">
      <div class="product-name">Куртка красная XL</div>
      <div class="product-id">000000000001</div>
    </div>
    <div class="product-count">> 130 шт</div>
    <div class="product-action" @click="openContextMenu(data)">
      <svg width="5" height="19" viewBox="0 0 5 19" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="2.5" cy="2.5" r="2.5" fill="#E3F0FF"/>
        <circle cx="2.5" cy="9.5" r="2.5" fill="#E3F0FF"/>
        <circle cx="2.5" cy="16.5" r="2.5" fill="#E3F0FF"/>
      </svg>
      <div class="context-menu" v-if="showMenu">
        <div class="context-menu-item">
          <div class="item-icon">
            <svg width="13" height="9" viewBox="0 0 13 9" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M13 4.5C13 4.5 10.5625 0 6.5 0C2.4375 0 0 4.5 0 4.5C0 4.5 2.4375 9 6.5 9C10.5625 9 13 4.5 13 4.5ZM0.953062 4.5C1.34592 5.10126 1.79777 5.66125 2.30181 6.17155C3.3475 7.22618 4.7775 8.18182 6.5 8.18182C8.2225 8.18182 9.65169 7.22618 10.699 6.17155C11.203 5.66125 11.6549 5.10126 12.0478 4.5C11.6549 3.89874 11.203 3.33874 10.699 2.82845C9.65169 1.77382 8.22169 0.818182 6.5 0.818182C4.7775 0.818182 3.34831 1.77382 2.301 2.82845C1.79696 3.33874 1.34511 3.89874 0.95225 4.5H0.953062Z" fill="white"/>
              <path fill-rule="evenodd" clip-rule="evenodd" d="M6.5 2.45441C5.96128 2.45441 5.44462 2.66991 5.06369 3.05351C4.68276 3.43711 4.46875 3.95738 4.46875 4.49987C4.46875 5.04236 4.68276 5.56262 5.06369 5.94622C5.44462 6.32982 5.96128 6.54532 6.5 6.54532C7.03872 6.54532 7.55538 6.32982 7.93631 5.94622C8.31724 5.56262 8.53125 5.04236 8.53125 4.49987C8.53125 3.95738 8.31724 3.43711 7.93631 3.05351C7.55538 2.66991 7.03872 2.45441 6.5 2.45441ZM3.65625 4.49987C3.65625 3.74038 3.95586 3.01201 4.48917 2.47497C5.02247 1.93793 5.74579 1.63623 6.5 1.63623C7.25421 1.63623 7.97753 1.93793 8.51083 2.47497C9.04414 3.01201 9.34375 3.74038 9.34375 4.49987C9.34375 5.25935 9.04414 5.98773 8.51083 6.52476C7.97753 7.0618 7.25421 7.3635 6.5 7.3635C5.74579 7.3635 5.02247 7.0618 4.48917 6.52476C3.95586 5.98773 3.65625 5.25935 3.65625 4.49987Z" fill="white"/>
            </svg>
          </div>
          <div class="item-text">Просмотреть</div>
        </div>
        <div class="context-menu-item">
          <div class="item-icon">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12.252 3.03704L10.2149 1L2.06677 9.14815L1.25195 12L4.1038 11.1852L12.252 3.03704ZM8.58529 2.62963L10.6223 4.66667L8.58529 2.62963ZM2.06677 9.14815L4.1038 11.1852L2.06677 9.14815Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="item-text">Редактировать</div>
        </div>
        <div class="context-menu-item">
          <div class="item-icon">
            <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3.14258 3.92822H3.92829V8.64251H3.14258V3.92822Z" fill="white"/>
              <path d="M5.5 3.92822H6.28571V8.64251H5.5V3.92822Z" fill="white"/>
              <path d="M0 1.57153V2.35725H0.785714V10.2144C0.785714 10.4228 0.868495 10.6226 1.01584 10.77C1.16319 10.9173 1.36304 11.0001 1.57143 11.0001H7.85714C8.06553 11.0001 8.26538 10.9173 8.41273 10.77C8.56008 10.6226 8.64286 10.4228 8.64286 10.2144V2.35725H9.42857V1.57153H0ZM1.57143 10.2144V2.35725H7.85714V10.2144H1.57143Z" fill="white"/>
              <path d="M3.14258 0H6.28543V0.785714H3.14258V0Z" fill="white"/>
            </svg>
          </div>
          <div class="item-text">Удалить</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import ClickOutside from 'vue-click-outside'

export default {
  props: {
    data: Number
  },
  name: "ProductItem",
  data(){
    return {
      showMenu: false
    }
  },
  methods: {
    openContextMenu(data) {
      this.showMenu = !this.showMenu
      console.log(data)
    },
    hide () {
      this.showMenu = false
    }
  },

  directives: {
    ClickOutside
  }
}
</script>

<style scoped>

</style>