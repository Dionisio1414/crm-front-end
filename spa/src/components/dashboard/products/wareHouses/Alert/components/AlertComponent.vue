<template>
  <div>
    {{ getFullTitle }}
    <button @click="onHandler" v-if="getExistWarehouse || isDocument" class="openInfo">
      {{$t('page.wareHouses.alert.open')}}
    </button>
  </div>
</template>

<script>
export default {
  name: "AlertComponent",
  props: {
    getFullTitle: String,
    getExistWarehouse: Boolean,
    isDocument: Boolean
  },
  methods: {
    onHandler() {
      this.$emit('updateAlertValue')
    }
  }
}
</script>

<style scoped lang="scss">
.openInfo {
  margin-left: 10px;
  font-size: 15px;
  line-height: 15px;
  text-align: center;
  text-decoration: underline;
  color: #5893D4;
  text-transform: capitalize;
}
</style>
