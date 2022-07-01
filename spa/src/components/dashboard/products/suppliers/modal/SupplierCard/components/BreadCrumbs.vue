<template>
  <div class="pathway">
    <ul>
      <li :key="item.title" v-for="item in getBreadCrumbs">{{ item.title }}</li>
      <svg-sprite width="11" height="12" icon-id="sluggishArrow"></svg-sprite>
    </ul>
  </div>
</template>

<script>

export default {
  name: "BreadCrumbs",
  props: {
    getBreadCrumbs: Array
  }
}
</script>

<style scoped lang="scss">
.pathway {
  margin-bottom: 20px;

  ul {
    display: flex;
    align-items: center;
    list-style-type: none;
    padding: 0 30px;

    li {
      font-size: 15px;
      line-height: 15px;
      color: #7E7E7E;
      margin-right: 10px;
      position: relative;
      display: flex;

      &:after {
        content: '\203A';
        margin-left: 10px;
        font-size: 24px;
      }

      &:last-of-type {
        padding-right: 0;
        font-weight: 550;

        &:after {
          display: none;
        }
      }
    }
  }
}
</style>
