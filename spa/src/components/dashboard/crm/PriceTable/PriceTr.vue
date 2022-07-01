<template>
  <tr :class="{'isPercent': isPercent}">
    <td v-if="isCheckItem">
      <div v-show="!isPercent" class="checkbox" @click.stop="(() => false)">
        <label class="checkbox-label">
          <input
              v-if="!dataVal.is_editable"
              type="checkbox"
              @change="selectItem(dataVal.id, selected)"
              v-model="selected"
          >
          <span class="checkbox-text" :class="{'disabled': !isCheckItem && !dataVal.is_editable}"></span>
        </label>
      </div>
    </td>
    <td v-for="cell in dataVal.cells" :key="cell">
      {{cell}}
    </td>
  </tr>
</template>

<script>
export default {
name: "PriceTr",
  props: {
    isCheckItem: Boolean,
    dataVal: Object,
    isPercent: String
  },
  data: () => ({
    selected: false
  }),
  methods: {
    selectItem(id) {
      this.$emit('updateSelectItem', id)
    },
  }
}
</script>

<style scoped lang="scss">
  .isPercent {
    opacity: .5;
    pointer-events: none;
  }
</style>