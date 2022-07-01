<template>
  <ul class="infoBlock">
    <info-block-item
        :key="item.title" v-for="item in infoData"
        :title="item.title || ''"
        :description="item.text || ''"
        :is-half="item.isHalf"
        :is-wide="item.isWide"
        :is-phone="item.isPhone"
    ></info-block-item>
  </ul>
</template>

<script>
// components
import InfoBlockItem from "./components/InfoBlockItem";

export default {
  name: "InfoBlock",
  props: {
    infoData: Array
  },
  components: {
    "info-block-item": InfoBlockItem
  }
}
</script>

<style scoped lang="scss">
.infoBlock {
  display: flex;
  flex-wrap: wrap;
  list-style-type: none;
  padding: 0;
}
</style>
