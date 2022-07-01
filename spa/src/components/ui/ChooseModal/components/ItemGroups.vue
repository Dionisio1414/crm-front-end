<template>
  <span v-frag>
    <tr
        @click="onRest(dataVal.id)"
        :class="{
        'activeGroups': isOpen,
        'is-group': dataVal['is_groups'],
        'disabledTr': isDisabledCheckbox,
        'short_title': dataVal.short_title
      }"
    >
      <td class="groupsBlockWrapper">
        <div class="groupsBlock">
          <div class="checkbox" @click.stop="(() => false)">
            <label class="checkbox-label">
              <input
                  type="checkbox"
                  @change="onCheckedAll"
                  v-model="isAllChecked"
                  ref="groupInput"
              >
              <span class="checkbox-text"
                    :class="{'disabled': !!dataVal.is_editable}"></span>
            </label>
          </div>
          <span class="btn-group" @click="onToggle">
          <svg-sprite width="25" height="20" icon-id="btnGroup"></svg-sprite>
        </span>
        </div>
      </td>
      <td v-for="(value, key) in dataVal.cells"
          :key="key"
          :ref="key"
      >
        {{key === 'short_title' ? `${value} (Группа)` : value}}
      </td>
    </tr>
    <template v-for="groupVal in dataValGroups">
      <item-groups-child
          v-show="isOpen"
          :key="groupVal.id"
          :groupVal="groupVal"
          @updateRest="onRest"
          @updateChecked="onChildChecked"
          :dataChecked="dataChecked"
          :selectedItemsForChoose="selectedItemsForChoose"
          :isAllChecked="isAllChecked"
      ></item-groups-child>
    </template>
  </span>
</template>

<script>
import ItemGroupsChild from "./ItemGroupsChild";

export default {
  name: "ItemGroups",
  components: {
    ItemGroupsChild
  },
  props: {
    dataVal: Object,
    dataValGroups: Array,
    isSelectedAll: Boolean,
    selectedItemsForChoose: Array
  },
  watch: {
    isSelectedAll(val) {
      if (!this.isDisabledCheckbox) this.isAllChecked = val;
      this.dataChecked = { isChecked: val, disabled: false };
    },
    isDisabledCheckbox(val) {
      if (val) this.isAllChecked = false;
    }
  },
  computed: {
    isDisabledCheckbox() {
      return this.dataValGroups.every(item => item.isDisabled)
    }
  },
  data: () => ({
    isOpen: false,
    isAllChecked: false,
    dataChecked: { isChecked: false, disabled: false },
    ids: []
  }),
  methods: {
    onToggle() {
      this.isOpen = !this.isOpen;
    },
    onRest(id) {
      this.$emit('updateRest', id);
    },
    onCheckedAll() {
      if (this.isAllChecked) {
        this.dataChecked = { isChecked: true, disabled: false }
        console.log(this.dataVal.groups,'this.dataVal.groups')
        this.ids = this.dataVal.groups.filter(body => !body.isDisabled && (!body.isDisabled && !body.checked)).map(item => item.id)
      } else {
        this.ids = this.dataVal.groups.filter(body => !body.isDisabled).map(item => item.id)
        this.dataChecked = { isChecked: false, disabled: false }
      }

      // console.log(this.ids, 'this.ids');
      this.$emit('updateGroupsChecked', this.ids);
    },
    onChildChecked(values) {
      const { value, id } = values;
      this.dataChecked = { isChecked: value, disabled: true }

      if (!value) {
        this.isAllChecked = false;
      }

      this.$emit('updateGroupsChecked', { id });
    }
  }
}
</script>

<style scoped lang="scss">
.groupsBlockWrapper {
  padding-right: 0 !important;
}

tr.activeGroups {
  filter: drop-shadow(0px 2px 7px rgba(0, 0, 0, 0.1));

  td:nth-of-type(-n+4) {
    color: #5893D4 !important;
  }

  &:hover {
    background: #FBFBFB !important;
  }

  .btn-group svg{
    transform: rotate(180deg);
  }
}

.groupsBlock {
  display: flex;
  align-items: center;

  .btn-group {
    cursor: pointer;
    display: inline-block;
    margin-left: 20px;
  }
}

.disabledTr .checkbox {
  opacity: .6;
  pointer-events: none;
}
</style>
