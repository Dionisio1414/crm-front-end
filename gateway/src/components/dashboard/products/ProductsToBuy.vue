<template>
  <div class="card">
    <div class="card-title">
      <div class="title">товары к закупке</div></div>
    <div class="card-content card-tabs">
      <v-tabs :touchless="false" v-model="tab">
        <v-tab href="#tab-1">
          С учетом продаж
        </v-tab>
        <v-tab href="#tab-2">
          Без учета продаж
        </v-tab>
      </v-tabs>
      <v-tabs-items :touchless="false" v-model="tab" class="tabs-content">
        <v-tab-item class="tab-item" :value="'tab-' + 1">
          <div class="tab-content">
            <product-item
                v-for="(item, index) in 7" :key="index"
                :data="item"
            ></product-item>
          </div>
        </v-tab-item>
        <v-tab-item class="tab-item" :value="'tab-' + 2">
          <div class="tab-content">
            <product-item v-for="(item, index) in 5" :key="index"></product-item>
          </div>
        </v-tab-item>
      </v-tabs-items>
      <div class="see-all">
        <router-link to="/" class="see-all-link">
          <span>Смотреть все</span>
          <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.83709 5.07078L1.29221 0.17573C1.1871 0.0624223 1.04677 0 0.897152 0C0.74753 0 0.607207 0.0624223 0.50209 0.17573L0.167391 0.536134C-0.0503999 0.770977 -0.0503999 1.15267 0.167391 1.38715L3.98384 5.49772L0.163156 9.61285C0.0580385 9.72616 -3.12529e-07 9.8772 -3.17625e-07 10.0383C-3.22727e-07 10.1995 0.0580385 10.3506 0.163156 10.464L0.497855 10.8243C0.603055 10.9376 0.743295 11 0.892917 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z" fill="#FF9F2F"/>
          </svg>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import ProductItem from "@/components/dashboard/products/ProductItem";

export default {
  name: "ProductsToBuy",
  components: {
    ProductItem
  },
  data() {
    return {
      tab: null
    }
  }
}
</script>

<style scoped>

</style>