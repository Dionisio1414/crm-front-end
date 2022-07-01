<template>
  <swiper-slide
      :virtualIndex="index"
  >
    <div class="item" :class="{'topSlider': !isTableSlider}">
      <template v-if="isTableSlider">
        <div class="cell" :key="child.total + idx" v-for="(child, idx) in item">
          <span :class="getStatusOfTime(child)">{{child.total ? child.total : '-'}}</span>
          <span>{{ formattedTime(child) }}</span>
        </div>
      </template>
       <div class="border" v-else>
        {{ item }}
      </div>
  </div>
  </swiper-slide>
</template>

<script>
import { SwiperSlide } from 'vue-awesome-swiper';

export default {
  name: "SliderItem",
  components: {
    SwiperSlide
  },
  props: {
    item: [String, Array],
    index: Number,
    isTableSlider: Boolean
  },
  methods: {
    formattedTime({from, to}) {
      if (from && to) {
        return `${from} ${to}`
      } else {
        return '-'
      }
    },
    getStatusOfTime({ is_worked_out, total }) {
      if (!total) return '';
      return is_worked_out ? 'greenItem' : 'redItem';

    }
  }
}
</script>

<style scoped lang="scss">
.greenItem {
  color: #4ECA80;
}

.redItem {
  color: #E72323;
}

.swiper-slide {
  box-sizing: border-box;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  text-align: center;
  background-color: white;
  color: #5893D4;

  &:last-child .item {
    border-right: none;
  }

  .item {
    font-size: 14px;
    vertical-align: center;
    background: #F4F9FF;
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;

    &.topSlider {
      height: 100%;
    }

    div {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .border {
      width: 100%;
      border-right: 1px solid #E3F0FF;
    }
  }

  .cell {
    display: flex;
    height: 56px;
    flex-direction: column;
    justify-content: center;
    width: 100%;
    border-bottom: 5px solid white;
    box-sizing: content-box;
    background: #F4F9FF;
    position: relative;

    &::after {
      content: '';
      background: white;
      position: absolute;
      height: 5px;
      left: 0;
      right: 0;
      bottom: -5px;
      z-index: 1;
    }

    span {
      font-weight: 550;
      font-size: 14px;
      line-height: 130%;
      text-align: center;

      & + span {
        font-weight: normal;
        font-size: 12px;
        line-height: 130%;
        text-align: center;
        color: #9DCCFF;
      }
    }
  }
}
</style>