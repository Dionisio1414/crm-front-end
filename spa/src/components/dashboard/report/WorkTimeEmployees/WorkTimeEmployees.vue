<template>
  <div class="items">
    <div class="item" :key="item['fullname'] + idx" v-for="(item, idx) in items">
      <div class="employee">
        <div class="image">
          <img v-if="item.thumbnail" :src="item.thumbnail" alt="photo">
          <svg-sprite v-else width="40" height="40" icon-id="noImg"></svg-sprite>
        </div>
        <div>
          <p class="title">{{ item['fullname'] }}</p>
          <p class="subTitle">{{ item['position'] }}</p>
        </div>
      </div>
      <div class="infoBlock">
        <div class="infoBlock_left">
          {{ item['work_day'] }}
        </div>
        <div class="infoBlock_right">
          <span>{{ item['work_hour_actual'] }}</span> / <span>{{ item['work_planned_hour'] }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'workTimeEmployees',
    props: {
      items: Array
    }
  }
</script>
<style scoped lang="scss">
  .items {
  width: 100%;
  max-width: 580px;
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
      padding: 0 25px;
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

    .infoBlock {
      background: #E3F0FF;
      font-weight: 550;
      font-size: 15px;
      line-height: 130%;
      text-align: center;
      color: #317CCE;
      height: 56px;
      display: flex;
      align-items: center;
      width: 100%;
      max-width: 235px;
      border-radius: 0 10px 10px 0;
      position: relative;

      &_right {
        width: 50%;
        font-weight: 550;
        font-size: 15px;
        line-height: 130%;
        text-align: center;
        color: #317CCE;
        span ~ span {
          color: #9DCCFF;
        }
      }

      &_left {
        width: 50%;
      }
    }
  }
}
</style>