<template>
   <v-dialog
       v-model="dialogSuccessGroup"
       content-class="success-alert"
       hide-overlay
 >
   <div class="body">
     <div>
      <span> {{ isGroupSuccess }} </span>
       <button @click="onInfoSupplier" class="addOpen" v-if="isAddModal">
         {{ $t('page.suppliers.open') }}
       </button>
     </div>
     <button
         class="close"
         @click="onClose"
     >
       <svg-sprite width="12" height="12" icon-id="closeWhite"></svg-sprite>
     </button>
   </div>
 </v-dialog>
</template>

<script>
import {mapActions} from "vuex";
import {MODE_TYPES} from "@/constants/constants";

export default {
name: "SuccessGroup",
  props: {
    isGroupSuccess: String,
    groupTitle: String
  },
  computed: {
    isAddModal() {
      return this.groupTitle === MODE_TYPES.ADD
    }
  },
  data() {
    return {
      dialogSuccessGroup: false
    }
  },
  methods: {
    ...mapActions(['onGroupSuccess']),
    onClose() {
      this.onGroupSuccess(null);
    },
    onInfoSupplier() {
      this.$emit('updateInfoSupplier');
      this.onClose();
    }
  },
  mounted() {
    this.dialogSuccessGroup = !!this.isGroupSuccess
    setTimeout(() => {
      this.dialogSuccessGroup = false;
      this.onClose();
    }, 5000)
  }
}
</script>

<style scoped lang="scss">
  .close {
    position: absolute;
    right: 15px;
    font-size: 0;

    svg {
      vertical-align: middle;
    }
  }

  .addOpen {
    text-decoration: underline;
  }

  .body {
    position: relative;
    display: inline-flex;
    align-items: center;
    padding: 5px 40px 5px 15px;
    background: #E3F0FF;
    border-radius: 30px;
    overflow-y: hidden;
    font-size: 15px;
    line-height: 15px;
    color: #5893D4;
    min-height: 35px;
    width: initial;

    span {
      flex-grow: 1;
      text-align: center;
    }
  }
</style>

<style lang="scss">
.success-alert {
  position: absolute;
  bottom: 130px;
  width: initial;
  border-radius: 30px;
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.05);
}
</style>
