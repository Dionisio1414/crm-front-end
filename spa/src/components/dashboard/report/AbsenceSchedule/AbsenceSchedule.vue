<template>
  <div class="absenceSchedule">
    <div class="absenceSchedule_top">
      <div class="header">
        <div class="employee">
          {{$t('report.absenceSchedule.employee')}}
        </div>
      </div>
      <slider
          ref="firstAbsenceScheduleSlider"
          custom-class="absenceScheduleSlider"
          controller="firstSlider"
          :swiperOption="swiperOption"
          :items="items"
          isBtn
      ></slider>
    </div>
    <div class="absenceSchedule_body" :style="'height:' + heightBlock +'px'">
      <div class="items" ref="items">
        <div class="item" :key="i" v-for="(i) in 3">
          <div class="employee">
            <div class="image">
              <svg-sprite width="40" height="40" icon-id="noImg"></svg-sprite>
            </div>
            <div>
              <p class="title">Долгова Анна Владимировна</p>
              <p class="subTitle">Главный менеджер</p>
            </div>
            <div >
              <work-time-calendar
                  :typeOfAbsenceObj="typeOfAbsenceObj"
                  is-date-picker-top
                  is-block-time
                  is-type-of-absence
                  isPlus
              ></work-time-calendar>
            </div>
          </div>
        </div>
      </div>
      <slider
          ref="secondAbsenceScheduleSlider"
          customClass="tableCell absenceScheduleSlider"
          controller="secondSwiper"
          :swiperOption="swiperOption"
          :items="sliderItems"
          is-table-slider
      ></slider>
    </div>
  </div>
</template>

<script>
// components
import Slider from "@/components/ui/Slider/Slider"
import WorkTimeCalendar from "@/components/ui/WorkTimeCalendar/WorkTimeCalendar";

export default {
  name: "AbsenceSchedule",
  components: {
    'slider': Slider,
    'work-time-calendar': WorkTimeCalendar
  },
  props: {
    typeOfAbsenceObj: Array
  },
  data: () => ({
    heightBlock: null,
    items: [
      '21.12', '22.12', '23.12', '24.12', '25.12', '26.12', '27.12', '21.12', '22.12', '23.12', '24.12', '25.12', '26.12', '27.12',
      '21.12', '22.12', '23.12', '24.12', '25.12', '26.12', '27.12', '21.12', '22.12', '23.12', '24.12', '25.12', '26.12', '27.12'
    ],
    sliderItems: [
      {
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '',
        date: ''
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '',
        date: ''
      },{
        time: '',
        date: ''
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '',
        date: ''
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '',
        date: ''
      },
      {
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '-',
        date: '-'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '',
        date: ''
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '',
        date: ''
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      },{
        time: '19.12',
        date: '09:00-18:10'
      }
    ],
    firstSwiper: null,
    secondSwiper: null,
    swiperOption: {
      slidesPerView: 12,
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
  mounted() {
    this.heightBlock = this.$refs.items.getBoundingClientRect().height + 40;
    const firstAbsenceScheduleSlider = this.$refs.firstAbsenceScheduleSlider.swiper;
    const secondAbsenceScheduleSlider = this.$refs.secondAbsenceScheduleSlider.swiper;

    firstAbsenceScheduleSlider.controller.control = secondAbsenceScheduleSlider;
    secondAbsenceScheduleSlider.controller.control = firstAbsenceScheduleSlider;
  }
}
</script>

<style scoped lang="scss">
.absenceSchedule {
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
      max-width: 340px;
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
  }

  &_body {
    padding: 20px 0;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    height: 100px;
    position: relative;
    z-index: 2;

    .items {
      width: 100%;
      max-width: 340px;
      position: relative;
      z-index: 2;

      .item {
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        position: relative;

        &:before {
          content: '';
          position: absolute;
          top: 0;
          bottom: 0;
          width: 30px;
          right: -23px;
          background: #f4f9ff;
        }

        &:last-child {
          margin-bottom: 0;
        }

        .employee {
          margin-right: 5px;
          background: #E3F0FF;
          border-radius: 10px 0 0 10px;
          display: flex;
          align-items: center;
          height: 56px;
          padding: 0 40px 0 25px;
          width: 100%;
          max-width: 340px;

          .image {
            margin-right: 15px;
            display: flex;
            align-items: center;
          }

          .title {
            font-weight: 550;
            font-size: 15px;
            line-height: 130%;
            color: #317CCE;
            text-transform: none;
          }

          .subTitle {
            font-size: 14px;
            line-height: 1.5;
            color: #5893D4;
          }

          p {
            margin-bottom: 0;
          }
        }
      }
    }

  }
}
</style>