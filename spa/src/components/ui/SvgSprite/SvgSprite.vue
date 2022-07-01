<template>
  <svg :width="width" :height="height">
    <use :href="getUrl"></use>
  </svg>
</template>

<script>
import myIcon from "@/assets/sprite.svg";

export default {
  name: "SvgSprite",
  props: {
    width: [String, Number],
    height: [String, Number],
    iconId: String
  },
  computed: {
    getUrl() {
      return `${myIcon}#${this.iconId}`;
    }
  }
}
</script>