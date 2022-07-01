<template>
  <tr
      class="groupChild"
      :class="{'disabledTr': groupVal.isDisabled}"
      @click="onRest(groupVal.id)"
  >
    <td>
      <div class="checkbox" @click.stop="(() => false)">
        <label class="checkbox-label">
          <input
              type="checkbox"
              @change="selectItem(groupVal.id)"
              :checked="groupVal.isDisabled || checkSelectedState(groupVal.id)"
              ref="input"
          >
          <span class="checkbox-text"
                :class="{'disabled': !!groupVal.is_editable}"></span>
        </label>
      </div>
    </td>
    <td v-for="(groupItemValue, idx) in groupVal.cells"
        :key="idx"
        :ref="idx"
    >
      {{groupItemValue}}
    </td>
  </tr>
</template>

<script>

export default {
  name: "ItemGroupsChild",
  props: {
    groupVal: Object,
    dataChecked: Object,
    selectedItemsForChoose: Array
  },
  watch: {
    dataChecked(val) {
      if (!val.disabled) {
        if (!this.groupVal.isDisabled) {
          this.$refs.input.checked = val.isChecked;
          this.groupVal.checked = val.isChecked
        }
      }
    }
  },
  methods: {
    checkSelectedState (id) {
      const index = this.selectedItemsForChoose.findIndex(item => item.id === id);
      if (index === -1) this.groupVal.checked = false;
      return index !== -1
    },
    selectItem(id) {
      const value = this.$refs.input.checked;
      const values = {
        value,
        id
      }

      this.$set(this.groupVal, 'checked', value)
      this.groupVal.checked = value;

      this.$emit('updateChecked', values)
    },
    onRest(id) {
      this.$emit('updateRest', id);
    },
  }
}
</script>

<style scoped lang="scss">
.groupChild {
  td {
    text-indent: 10px;
    white-space: nowrap;
  }
}

.checkboxChild {
  text-align: center;
}

.disabledTr {
  opacity: .6;
  pointer-events: none;
}
</style>
