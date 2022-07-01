<template>
  <div class="main-layout view-item-main-info">
    <div class="main-left" v-if="!isService">
      <div class="img">
        <img src="@/assets/img/default_product-img.png" alt="default-img">
      </div>
    </div>
    <div class="main-right" :class="{'service' : isService}">
      <div class="main-item">
        <div class="main-item-label">
          {{ $t('nomenclature.category') }}
        </div>
        <category-tree :categories="categoryTree"
                       :editable="false"/>
      </div>
      <div class="main-item main-item">
        {{ form.short_title }}
      </div>
      <div class="main-item">
        <div class="main-item">
          <div class="main-item-label">
            {{ $t('nomenclature.full_title') }}({{ $t('nomenclature.in_document') }})
          </div>
          <span class="main-item-value">
            {{ form.dock_title }}
          </span>
        </div>
      </div>
      <div v-if="selectedOptions.length && !isService" >
        <div class="main-item characteristic-item" v-for="  selectedOption in selectedOptions"
             :key="selectedOption.id">
          <div class="main-item-label">{{ selectedOption.title }}</div>
          <div class="values-list">
            <div class="values-list-item" :id="selectedOption.id" v-for="value in selectedOption.values"
                 :key="`${selectedOption.id}-${value.id}`">
              {{ value.title }}
              <span v-if="value.code">({{ value.code }})</span>
              <div v-if="selectedOption.id === 1">
                <span v-if="value.color" class="color-preview" :style="{background: value.color }"></span>
                <span v-else class="color-preview without-color"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="main-item">
        <div class="main-item-label">
          {{ $t('nomenclature.price') }}
        </div>
        <div class="prices-item">
          <div class="prices-item-label">Продажа</div>
          <div class="values-list">
            <div class="values-item min-price-item" v-if="form.min_price">
              <span>Минимальная</span> — <span>{{ form.min_price }}</span> <span>₴</span>
            </div>
            <div class="values-item" v-for="price in isSellsPrices" :key="price.id">
              <span>{{ price.title }} — </span><span>{{ price.value }}</span> <span>{{ price.symbol_currency }}</span>
            </div>
          </div>
        </div>
        <div class="prices-item">
          <div class="prices-item-label">Покупка</div>
          <div class="values-list">
            <div class="values-item" v-for="price in isPurshacePrices" :key="price.id">
              <span>{{ price.title }} — </span><span>{{ price.value }}</span> <span>{{ price.symbol_currency }}</span>
            </div>
          </div>
        </div>
      </div>
      <v-expansion-panels
          v-model="panel"
          :readonly="readonly"
          multiple
      >
        <v-expansion-panel>
          <v-expansion-panel-header>
            <span class="acc">{{ $t('nomenclature.additional_info') }}</span>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row>
              <v-col cols="4" class="info-table">
                <div class="main-item">
                  <div class="main-item-label">{{ $t('nomenclature.sku') }}</div>
                  <div class="main-item-value">{{ form.sku }}</div>
                </div>
              </v-col>
              <v-col cols="4" class="info-table">
                <div class="main-item">
                  <div class="main-item-label">{{ $t('nomenclature.unit') }}</div>
                  <div class="main-item-value">{{ unit.title }}</div>
                </div>
              </v-col>
              <v-col cols="4" class="info-table" v-if="!isService">
                <div class="main-item">
                  <div class="main-item-label">{{ $t('nomenclature.packing') }}</div>
                  <div class="main-item-value">{{ form.packing }}</div>
                </div>
              </v-col>
              <v-col cols="4" class="info-table" v-if="!isService">
                <div class="main-item">
                  <div class="main-item-label" >{{ $t('nomenclature.lower_limit') }}</div>
                  <div class="main-item-value">{{ form.lower_limit }}</div>
                </div>
              </v-col>
              <v-col cols="4" class="info-table">
                <div class="main-item">
                  <div class="main-item-label">{{ $t('nomenclature.supplier') }}</div>
                  <div class="main-item-value">{{ form.supplier }}</div>
                </div>
              </v-col>
            </v-row>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
      <div class="main-item">
        <div class="main-item">
          <div class="main-item-label">
            <span>{{ $t('nomenclature.additional_category') }} ({{ $t('nomenclature.on_site') }})</span>
          </div>
          <div class="values-list">
            <div class="value-item" v-for="(item) in form.categories" :key="item.id">
              <span>{{ item.title }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="main-item" v-if="!isService">
        <div class="main-item-label">
          {{ $t('nomenclature.files') }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CategoryTree from "../../froms/CategoryTree";
// import {TABLE_ACTIONS} from "../../../../../../../constants/constants";
import {NOMENCLATURE_RESOURCES} from "@/constants/constants";
// import {Hooper, Slide} from 'hooper';
import {mapGetters} from 'vuex'
import 'hooper/dist/hooper.css';


export default {
  name: "MainInfo",
  components: {
    CategoryTree,
    // Hooper,
    // Slide,
  },

  data() {
    return {
      panel: [0],
      disabled: false,
      readonly: false,
      sliderItems: [
        'https://intertop.ua/load/MK103/MAIN.jpg',
        'https://intertop.ua/load/MK103/MAIN.jpg',
        'https://intertop.ua/load/MK103/MAIN.jpg',
        'https://intertop.ua/load/MK103/2.jpg',
        'https://intertop.ua/load/MK103/3.jpg',
        'https://intertop.ua/load/MK103/4.jpg'
      ]
    }
  },
  props: {
    form: Object,
    categoryTree: Array,
    units: Array,
    resource: Object
  },
  computed: {
    ...mapGetters({
      characteristicsArr: 'characteristics',
      colorData: 'colorData',
      sizeData: 'sizeData',
    }),
    unit() {
      return this.units.find(unit => unit.id === this.form.unit_id)
    },
    options() {
      return [].concat(this.characteristicsArr, this.colorData, this.sizeData)
    },
    isPurshacePrices() {
      return this.form.prices.filter(price => price.is_buy)
    },
    isSellsPrices() {
      return this.form.prices.filter(price => !price.is_buy)
    },
    isService () {
      return this.resource.SINGLE_VALUE === NOMENCLATURE_RESOURCES.SERVICES.SINGLE_VALUE
    },
    isProduct () {
      return this.resource.SINGLE_VALUE === NOMENCLATURE_RESOURCES.PRODUCTS.SINGLE_VALUE
    },
    // baseOption() {
    //     if (this.form.base_characteristic_value) {
    //         const baseOption = this.form.base_characteristic_value
    //         const fullOption = this.options
    //             .find(fullStat => fullStat.id === baseOption.id)
    //         const fullOptionValues = fullOption.characteristic_value || fullOption.characteristic_color_value
    //         return {
    //             title: fullOption.title,
    //             values: baseOption.values.map(value => fullOptionValues.find(item => item.id === value.id))
    //         }
    //     }
    //     return null
    // },
    selectedOptions() {
      if (!this.isService) {
        return [...this.form.characteristic_value].map((stat) => {
          const fullOption = this.options.find(fullStat => fullStat.id === stat.id)
          const fullOptionValues = fullOption.values || fullOption.value ||
              fullOption.characteristic_value || fullOption.characteristic_color_value
          const selectedOption = {
            id: fullOption.id,
            title: fullOption.title,
            values: stat.values ?
                stat.values.map(value => fullOptionValues.find(item => item.id === value.id)) :
                [{title: '--'}]
          }
          return selectedOption
        }).sort((a, b) => a.id - b.id)
      }
      return []
    }
  },
  methods: {
    // updateCarousel (payload) {
    //     this.$refs.slider.slideTo(payload.currentSlide);
    // },
    goToSlide(index) {
      this.$refs.slider.slideTo(index);
    }
  }
}
</script>

<style scoped lang="scss">
.img {
  img {
    width: 100%;
  }
}
.thumb-example {
  height: 480px;
  background-color: #000;
}

.prices-item {
  margin-bottom: 10px;
  display: flex;
  align-items: flex-start;
  &-label {
    padding-top: 10px;
  }
  .values-list {
    margin-bottom: 0;
  }

  .values-item {
    margin-bottom: 10px;
    left: 784px;
    top: 571px;
    background: #F4F9FF;
    border-radius: 10px;
    font-size: 13px;
    line-height: 13px;
    color: #5893D4;
    flex: none;
    order: 0;
    flex-grow: 0;
    margin-right: 15px;
    padding: 8px 15px;

    &:last-child {
      margin-right: 0;
    }
  }

  &-label {
    font-weight: 550;
    font-size: 13px;
    line-height: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #9DCCFF;
    min-width: 100px;
  }

  &:last-child {
    margin-bottom: 0;
  }
}

.main-left {
  padding-right: 30px;
  padding-left: 50px;

  .pagination-slider {
    .hooper {
      outline: none !important;
    }

    position: absolute;
    width: 60px;

    img {
      width: 100%;
      height: 100%;
    }

    left: 0;
    top: 0;
  }


  .head-slider {
    .hooper {
      outline: none !important;
    }

    &-img {
      padding: 0 15px;
      max-height: 380px;
    }

    img {
      height: 100%;
      width: 100%;
    }
  }
}

.view-item-main-info {

  .main-item {
    margin-bottom: 20px;

    &.main-item {
      font-size: 20px;
      line-height: 20px;
      letter-spacing: 0.03em;
      color: #5893D4;
      margin-bottom: 20px;

      .main-item-value {

      }
    }

    &-label {
      font-size: 13px;
      line-height: 13px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: #BFBFBF;
      margin-bottom: 15px;
    }

    &-value {
      font-weight: 550;
      font-size: 16px;
      line-height: 16px;
      color: #7E7E7E;
    }
  }

  .values-list {
    display: flex;

    &-item {
      background: #F4F9FF;
      margin-right: 10px;
      padding: 8px 15px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: normal;
      line-height: 1;
      color: #317CCE;

      &:last-child {
        margin-right: 0;
      }
    }

    .min-price-item {
      font-size: 13px;
      line-height: 13px;
      color: #7E7E7E;
      background: #F2F2F2;
      border-radius: 10px;
    }
  }

  &.main-layout {
    padding: 0 30px;
    display: flex;

    .main-left {
      width: 40%;
      height: 100%;
    }

    .main-right {
      width: 60%;
      &.service {
        width: 100%;
      }

      .v-expansion-panel-header {
        padding: 0;
        margin-bottom: 20px;
        min-height: 0;
        font-size: 13px;
        line-height: 13px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: #5893D4;

        .acc {
          flex: 0 0 auto;
          background-color: white;
          padding-right: 10px;
          z-index: 2;
        }

        &--active {
          color: #FF9F2F;
        }

        &:after {
          content: "";
          display: block;
          position: absolute;
          left: 10px;
          right: 30px;
          height: 1px;
          z-index: 1;
          top: 50%;
          transform: translateY(-50%);
          background-color: #E3F0FF;

        }

      }

      .v-expansion-panel::before {
        box-shadow: none;
      }

      .v-expansion-panel-content {
        border-bottom: 1px solid #E3F0FF;
        margin-bottom: 10px;
      }

      .v-expansion-panel-content {
        padding: 0;

        .main-item {
          margin: 0 -24px 16px;

          &:last-child {
            margin-bottom: 0;
          }
        }
      }
    }
  }

  .values-list-item {
    margin-bottom: 10px;
    padding: 15px;
    background: #F4F9FF;
    border-radius: 10px;
    display: flex;
    align-items: center;

  }

  .color-preview {
    float: right;
    display: inline-block;
    width: 36px;
    height: 26px;
    border: 2px solid #5893D4;
    box-sizing: border-box;
    border-radius: 10px;
    margin-left: 15px;
    &.without-color {
      background-color: #fff;
      position: relative;
      overflow: hidden;
      &:after {
        width: 35.23px;
        height: 0;
        border: 1px solid #FD3F3F;
        transform: translate(-50%) rotate(145.41deg);
        display: block;
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;

      }
    }
  }
}

</style>
