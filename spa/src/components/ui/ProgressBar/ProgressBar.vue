<template>
  <div class="progress-bar-wrapper">
    <v-tooltip content-class="category-tooltip" bottom>
      <template v-slot:activator="{ on, attrs }">
        <div class="progress-item" v-bind="attrs" v-on="on">
          <div class="progress-number">{{ progressValue }}%</div>
          <v-progress-linear :value="progressValue"></v-progress-linear>
        </div>
      </template>
      <span>{{ title }}</span>
    </v-tooltip>
    <v-tooltip v-if="isShowBtn" color="white" class="progress-bar-tooltip" bottom>
      <template v-slot:activator="{ on, attrs }">
        <div v-bind="attrs" v-on="on">
          <svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite>
        </div>
      </template>
      <ul class="progress-bar-items">
        <li v-for="item in items" :class="{'exist' : item.exist}" :key="item.title">
          {{ $t(`nomenclature.${item.title}`) }} - {{ item.value }}%
        </li>
      </ul>
    </v-tooltip>
  </div>
</template>

<script>
export default {
  name: "ProgressBar",
  props: {
    title: String,
    items: Array,
    isShowBtn: Boolean
  },
  computed: {
    progressValue() {
      const existItem = this.items.filter(item => item.exist)
      const progressValue = existItem.reduce((accumulator, currentValue) => {
        return accumulator + currentValue.value
      }, 0)

      return progressValue || 0;
    }
  }
}
</script>

<style scoped lang="scss">
.progress-bar-wrapper {
  display: flex;

  .progress-item {
    margin-right: 10px;
  }

  svg {
    position: relative;
    top: 15px;
  }
}

.progress-bar-items {
  list-style: none;
  padding: 20px !important;
  color: #7E7E7E;
  font-size: 13px;
  line-height: 130%;
  min-width: 290px;

  li {
    opacity: .5;
    margin-bottom: 10px;

    &.title {
      color: #5893D4;
      opacity: 1;
    }

    &:last-child {
      margin-bottom: 0;
    }

    &.exist {
      opacity: 1;
    }
  }
}

.progress-bar-tooltip {
  background-color: black !important;
}

</style>
