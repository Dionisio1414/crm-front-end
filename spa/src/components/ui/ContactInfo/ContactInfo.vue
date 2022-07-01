<template>
  <div @click="onHandler" v-click-outside="onClickOutside" class="contactInfo" :class="{'active': isOpen}" :style="{ top: getTop() }">
    <div class="top">
      <svg-sprite v-if="!isOpen" width="6" height="11" icon-id="contactInfoArrow"></svg-sprite>
      <svg-sprite width="20" height="14" icon-id="contactInfo"></svg-sprite>
      <span v-if="isOpen" class="topText">Контактное лицо</span>
    </div>
    <div v-if="isOpen" class="body">
      <div v-if="getContact['full_name']" class="name">{{ getContact['full_name'] }}</div>
      <div v-if="getContact['position']" class="position">{{ getContact['position'] }}</div>
      <div v-if="getContact['phone']" class="tel bodyItem">
        <span class="icon">
          <svg-sprite width="16" height="16" icon-id="tel"></svg-sprite>
        </span>
        <a :href="'tel:' + getContact['phone']">{{ getContact['phone'] }}</a>
      </div>
      <div v-if="getContact['email']" class="email bodyItem">
        <span class="icon">
          <svg-sprite width="17" height="12" icon-id="email"></svg-sprite>
        </span>
        <a :href="'mailto:' + getContact['email']">{{ getContact['email'] }}</a>
      </div>
      <div v-if="getAddress" class="address bodyItem">
        <span class="icon">
          <svg-sprite width="13" height="17" icon-id="point"></svg-sprite>
        </span>
        <span>{{ getAddress }} </span>
      </div>
      </div>
    </div>
</template>

<script>
import { eventBus } from "@/main";

export default {
  name: "ContactInfo",
  props: {
    contactInfoData: Object
  },
  computed: {
    getContact() {
      const { contacts } = this.contactInfoData;
     return  contacts;
    },
    getAddress() {
      const { contacts } = this.contactInfoData;
      if (contacts) {
        const { legal_address_country_title, legal_address_number_housing, legal_address_region_title, legal_address_street, legal_city_title } = contacts;
        const isAddress = Object.values({ legal_address_country_title, legal_address_number_housing, legal_address_region_title, legal_address_street, legal_city_title }).some(item => !!item)

        if (isAddress) {
          return `${legal_address_country_title ?? ''} ${legal_address_number_housing ?? ''} ${ legal_address_region_title ?? ''} ${legal_address_street ?? ''} ${legal_city_title ?? ''}`
        }
      }

      return '';
    }
  },
  data: () => ({
    isOpen: false,
    topValue: null
  }),
  methods: {
    onHandler() {
      if (!this.isOpen) this.isOpen = true;
      eventBus.$emit("updateContact", true);
    },
    onClickOutside() {
      this.isOpen = false;
      eventBus.$emit("updateContact", false);
    },
    getTop() {
      if(!this.isOpen) {
        this.topValue = this.contactInfoData?.top;
        return this.topValue + 'px';
      } else {
        return this.topValue + 'px';
      }

    }
  }
}
</script>

<style scoped lang="scss">
.contactInfo {
  cursor: pointer;
  display: flex;
  align-items: center;
  top: 0;
  height: 60px;
  width: 68px;
  position: absolute;
  right: -20px;
  z-index: 100;
  background: #F4F9FF;
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
  border-radius: 32px 0 0 32px;
  padding: 5px 15px;
  animation: moving .3s ease-in-out;

  &.active {
    width: 270px;
    height: initial;
    padding: 15px 30px;
    flex-direction: column;
    align-items: flex-start;
    cursor: auto;

    .top {
      justify-content: flex-start;
      border-bottom: 1px solid #9DCCFF;
      padding-bottom: 7px;
      margin-bottom: 12px;
    }
  }

  .body {
    &Item {
      display: flex;
      align-items: center;
      font-size: 13px;
      line-height: 13px;
      color: #5893D4;
      margin-bottom: 15px;

      a {
        text-decoration: none;
        font-size: 13px;
        line-height: 13px;
        color: #5893D4;
      }

      .icon {
        display: inline-block;
        margin-right: 14px;
      }
    }
    &Item:last-child {
      margin-bottom: 0;
    }


    .name {
      font-size: 14px;
      line-height: 14px;
      color: #5893D4;
      margin-bottom: 10px;
    }

    .position {
      font-size: 13px;
      line-height: 13px;
      color: #9DCCFF;
      margin-bottom: 10px;
    }
  }

  .top {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;

    .topText {
      margin-left: 15px;
      font-weight: bold;
      font-size: 14px;
      line-height: 14px;
      color: #5893D4;
    }
  }
}

@keyframes moving {
  from {
    right: -90px;
  }
  to {
    right: -20px;
  }
}
</style>
