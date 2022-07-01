<template>
  <swiper
      :class="customClass"
      class="swiper"
      ref="mySwiper"
      :controller="getController"
      :options="swiperOption"
      @swiper="onSwiper"
      @slideChange="onSlideChange"
      @reachEnd="beforeSlideChangeStart"
  >
    <template v-for="(item, index) in items">
      <SliderItem :isTableSlider="isTableSlider" :index="index" :key="getUniqueId(item)" :item="item"></SliderItem>
    </template>

    <slider-btn v-if="isBtn" btn-class="next" svg-id="sliderRight" slot="button-next"></slider-btn>
    <slider-btn v-if="isBtn" btn-class="prev" svg-id="sliderLeft" slot="button-prev"></slider-btn>
  </swiper>
</template>

<script>
// swiper components
import { Swiper, directive } from 'vue-awesome-swiper';

// components
import SliderBtn from "./components/SliderBtn";
import SliderItem from "./components/SliderItem";
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "Slider",
  components: {
    Swiper,
    SliderBtn,
    SliderItem,
  },
  props: {
    items: Array,
    swiperOption: Object,
    isBtn: Boolean,
    controller: String,
    customClass: String,
    isTableSlider: Boolean
  },
  directives: {
    swiper: directive
  },
  computed: {
    swiper() {
      return this.$refs.mySwiper.$swiper
    },
    getController() {
      return { control: this.controller }
    }
  },
  methods: {
    onSwiper(swiper) {
      this.$emit('updateSwiper', swiper)
    },
    onSlideChange() {
      console.log('slide change');
    },
    beforeSlideChangeStart() {
      console.log('slide beforeSlideChangeStart');
    },
    getUniqueId(srt) {
      return srt + getUniqueId();
    }
  },
}
</script>

<style lang="scss">
.swiper {
  height: 100%;
  width: 100%;
  max-width: 717px;
  padding: 0 23px;
  background: #fff;

  &.absenceScheduleSlider {
    max-width: initial;
  }

  &.tableCell {
    border-radius: 0 10px 10px 0;
    .swiper-slide {
      background-color: initial;
    }

    &:after {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      width: 23px;
      background: #F4F9FF;
      z-index: 2;
      right: 0;
    }

    .item {
      border-right: none;

      .cell {

      }
    }
  }

  .sw-button {
    font-size: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 23px;
    z-index: 1;
    background: #fff;

    &.button-next {
      left: initial;
      right: 0;
    }
  }
}
</style>