<template>
  <div class="alert" :class="{show: alertVisible}" v-if="alert">
    <div class="wrapper">
      <span v-if="alert.text">{{alert.startText}} </span>
      <strong v-if="alert.item">"{{alert.item}}"</strong>
      <span v-if="alert.endText">{{alert.endText}} </span>
      <span v-if="!alert.text" class="text">{{ alert.name }} {{ alert.title }} {{ alert.createdName }}. </span>
      <router-link v-if="!alert.text" class="open" :to="alert.link">Открыть</router-link>
      <svg @click="hideAlert" class="close" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
      </svg>
    </div>
  </div>
</template>

<script>

import {mapGetters, mapActions} from 'vuex'

export default {
  name: "Alert",
  data: () => ({

  }),
  computed: {
    ...mapGetters([
        'alertVisible',
        'alert'
    ])
  },
  methods: {
    ...mapActions([
        'hideAlert'
    ])
  }
}
</script>

<style lang="sass" scoped>
.wrapper
  padding-right: 85px
  padding-left: 30px
  position: relative
.alert
  position: absolute
  bottom: 100px
  left: 50%
  background-color: #E3F0FF
  padding: 10px 0
  border-radius: 50px
  color: #5893D4
  font-size: 15px
  font-weight: normal
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2)
  display: flex
  align-items: center
  justify-content: center
  z-index: 0
  opacity: 0
  transform: translate(-50%, 100px)
  transition: opacity, transform 0.4s ease
  &.show
    z-index: 1000
    opacity: 1
    transform: translate(-50%, 0)
  span
    display: inline-block
    margin-right: 10px
  .open
    color: lighten(#FF9F2F, 20%)
    text-decoration: underline
    &:hover
      cursor: pointer
      color: lighten(#FF9F2F, 10%)
  .close
    width: 12px
    height: 12px
    top: 50%
    transform: translateY(-50%)
    position: absolute
    right: 12px
    margin-left: 10px
    &:hover
      cursor: pointer


</style>