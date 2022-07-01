<template>
  <div
      class="progress"
      :class="{'progress-category': isToolTip}"
  >
    <v-tooltip content-class="category-tooltip" bottom>
      <template v-slot:activator="{ on, attrs }">
        <div class="progress-item" v-bind="attrs" v-on="on">
          <div class="progress-number">{{ value }}%</div>
          <v-progress-linear :value="value"></v-progress-linear>
        </div>
      </template>
      <span>{{ $t('category.progressBar') }}</span>
    </v-tooltip>
    <v-tooltip v-if="isToolTip" color="white" class="progress-bar-tooltip" bottom>
      <template v-slot:activator="{ on, attrs }">
        <div v-bind="attrs" v-on="on">
          <svg-sprite width="16" height="16" icon-id="tooltip"></svg-sprite>
        </div>
      </template>
      <ul class="progress-bar-items">
        <li
            v-for="item in productProgressBar"
            :class="{ 'exist' : item.exist }"
            :key="item.title"
        >
          {{ $t(`category.progressList.${item.title}`) }} - {{ item.value }}%
        </li>
      </ul>
    </v-tooltip>
  </div>
</template>

<script>
export default {
  name: "ProgressBar",
  props: {
    progressList: Array,
    isToolTip: Boolean
  },
  watch: {
    progressList(val) {
      if (val) this.onProgress();
    }
  },
  data: () => ({
    value: 0,
    productProgressBar: [
      {
        title: 'photo',
        value: 20,
        exist: false
      },
      {
        title: 'unit',
        value: 20,
        exist: false
      },
      {
        title: 'description',
        value: 20,
        exist: false
      },
      {
        title: 'options',
        value: 20,
        exist: false
      },
      {
        title: 'characteristics',
        value: 20,
        exist: false
      }
    ]
  }),
  methods: {
    onProgress() {
      this.productProgressBar.forEach(item => item.exist = false);
      this.value = this.progressList.reduce((acc, item, idx) => {
        if (Array.isArray(item) && item.length) {
          this.productProgressBar[idx].exist = true;
          acc += 20;
        }
        if (!Array.isArray(item) && item) {
          this.productProgressBar[idx].exist = true;
          acc += 20;
        }

        return acc;
      }, 0)
    },
  },
  mounted() {
    this.onProgress();
  }
}
</script>

<style scoped lang="scss">
  .progress.progress-category {
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
</style>
