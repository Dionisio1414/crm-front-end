<template>
  <li class="infoBlockItem" :class="{'halfBlock': isHalf, 'wide': isWide}">
    <div class="infoBlockItemTitle">{{ title }}</div>
    <p :class="{'phone': isPhone}">{{ description }}</p>
  </li>
</template>

<script>
export default {
  name: "InfoBlockItem",
  props: {
    title: String,
    description: [String, Number],
    isHalf: Boolean,
    isWide: Boolean,
    isPhone: Boolean
  }
}
</script>

<style scoped lang="scss">
.infoBlockItem {
  width: 25%;
  padding: 0 15px;
  border-left: 1px solid #9DCCFF;
  margin-bottom: 20px;

  &.wide {
    width: 100%;
  }

  &.halfBlock {
    width: 50%;
  }

  &Title {
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #317CCE;
    margin-bottom: 20px;
  }

  p {
    font-size: 14px;
    line-height: 14px;
    color: #7E7E7E;
    margin-bottom: 0;

    &.phone {
      display: inline-block;
    }
  }
}
</style>