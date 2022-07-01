<template>
  <div class="workTime">
    <div class="workTime_top">
      <div class="header">
        <div class="employee">
          {{$t('report.workTime.employee')}}
        </div>
        <div class="infoBlock">
          <div class="infoBlock_left">
            {{$t('report.workTime.workDay')}}
          </div>
          <div class="infoBlock_right">
            <span>{{$t('report.workTime.hours')}}</span> <br>
            <span>{{$t('report.workTime.plan')}}</span>
          </div>
        </div>
      </div>
      <slider ref="firstSlider" controller="firstSlider" isBtn :swiperOption="swiperOption" :items="getWorkTimeDate"></slider>
    </div>
    <div class="workTime_body" :style="'height:' + heightBlock +'px'">
      <work-time-employees  ref="items" :items="workTimeList"></work-time-employees>
      <slider is-table-slider customClass="tableCell" ref="secondSwiper" controller="secondSwiper" :swiperOption="swiperOption" :items="getWorkData"></slider>
    </div>
  </div>
</template>

<script>
// components
import Slider from "@/components/ui/Slider/Slider";
import WorkTimeEmployees from "@/components/dashboard/report/WorkTimeEmployees/WorkTimeEmployees";

export default {
  name: "WorkTime",
  components: {
    Slider,
    WorkTimeEmployees
  },
  props: {
    workTimeList: Array
  },
  computed: {
    getWorkTimeDate() {
      const listItem = this.workTimeList && this.workTimeList.slice().pop();
      return listItem['schedule'].map(item => item.date)
    },
    getWorkData() {
      const list = this.workTimeList && this.workTimeList.slice();
      const schedules = list.map(item => item['schedule']);

      return this.getWorkTimeDate.map(date => {
        return schedules.map(schedule => schedule.find(item => item.date === date));
      })
    }
  },
  data: () => ({
    heightBlock: null,
    firstSwiper: null,
    secondSwiper: null,
    swiperOption: {
      slidesPerView: 7,
      spaceBetween: 0,
      grabCursor: true,
      navigation: {
        nextEl: '.button-next',
        prevEl: '.button-prev',
      }
    }
  }),
  methods: {
    onFirstSwiper(swiper) {
      this.firstSwiper = swiper;
    },
    onSecondSwiper(swiper) {
      this.secondSwiper = swiper;
    }
  },
  async mounted() {
    this.heightBlock = this.$refs.items.$el.getBoundingClientRect().height + 40;
    const first = this.$refs.firstSlider.swiper;
    const second = this.$refs.secondSwiper.swiper;

    first.controller.control = second;
    second.controller.control = first;
  }
}
</script>

<style scoped lang="scss">
  .workTime {
    padding: 5px 20px;

    &_top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #E3F0FF;
      padding-bottom: 5px;
      height: 45px;

      .header {
        width: 100%;
        max-width: 580px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .employee {
        width: 100%;
        max-width: 340px;
        font-size: 14px;
        line-height: 14px;
        color: #5893D4;
      }

      .infoBlock {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 235px;
        font-size: 14px;
        line-height: 14px;
        color: #5893D4;
        text-align: center;

        &_right {
          width: 50%;
          span ~ span {
            font-size: 12px;
            line-height: 12px;
            text-align: center;
            color: #9DCCFF;
          }
        }

        &_left {
          width: 50%;
        }
      }
    }

    &_body {
      padding: 20px 0;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      height: 100px;
      position: relative;
      z-index: 2;
    }
  }
</style>