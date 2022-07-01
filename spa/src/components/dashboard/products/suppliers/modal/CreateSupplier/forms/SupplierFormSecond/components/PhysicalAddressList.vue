<template>
    <v-row no-gutters>
      <v-col cols="8">
        <div class="physicalAddressList" :class="{'active': getItemId}">
          <div class="physicalAddressListItem">
            <span>{{ getFullName }}</span>
          </div>
          <div class="edit-btn">
            <button v-if="isDataSafe" class="btn" @click="onEdit">
              <svg-sprite width="15" height="15" icon-id="editSmall"></svg-sprite>
            </button>
            <button v-if="isDataSafe" class="btn" @click="onRemoveItem">
              <svg-sprite width="15" height="15" icon-id="crossSmall"></svg-sprite>
            </button>
          </div>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "PhysicalAddressList",
  props: {
    isDataSafe: Boolean,
    activeId: [String, Number],
    countryValue: {
      type: String,
      default: ''
    },
    regionValue: {
      type: String,
      default: ''
    },
    cityValue: {
      type: String,
      default: ''
    },
    street: {
      type: String,
      default: ''
    },
    house: {
      type: String,
      default: ''
    },
    idx: Number,
    id: [String, Number]
  },
  computed: {
    getFullName() {
      return `${this.countryValue ?? ''} ${this.regionValue ?? ''} ${this.cityValue ?? ''} ${this.street ?? ''} ${this.house ?? ''}`;
    },
    getItemId() {
      return this.id === this.activeId && !this.isDataSafe;
    }
  },
  methods: {
    onRemoveItem() {
      this.$emit('updateRemoveItem', this.idx)
    },
    onEdit() {
      this.$emit('updateEdit', this.id)
    }
  }
}
</script>

<style scoped lang="scss">
  .physicalAddressList {
    display: inline-flex;
    align-items: center;
    background: #E3F0FF;
    border-radius: 10px;
    padding-right: 10px;
    margin-bottom: 10px;
    min-height: 30px;
    font-size: 0;

    svg {
      color: #BBDBFE;
    }

    &.active {
      background: #5893D4;

      svg {
        color: #fff;
      }

      .physicalAddressListItem {
        color: #fff;
      }
    }

    &Item {
      font-size: 0;
      color: #317CCE;
      padding: 8px 0 8px 15px;

      span {
        font-size: 13px;
        line-height: 110%;
      }
    }

    .edit-btn {
      margin-left: 10px;
      display: flex;
      align-items: center;

      button:first-child {
        margin-right: 10px;
      }
    }
  }
</style>
