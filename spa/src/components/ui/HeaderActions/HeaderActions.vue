<template>
  <div class="dialog-header-actions">
    <v-btn v-if="!isHideDots" icon color="#5893D4">
      <svg-sprite width="5" height="19" icon-id="dotsWhite"></svg-sprite>
    </v-btn>
    <v-btn @click="addTab(id, title)" :disabled="checkTabs" v-if="!isHideAttach" icon color="#5893D4">
      <svg-sprite width="15" height="20" icon-id="attach"></svg-sprite>
    </v-btn>
    <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="onClose">
      <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
    </v-btn>
  </div>
</template>

<script>
// mixin
import mixin from '@/mixins/mixinTabs';

export default {
  name: "HeaderActions",
  mixins: [mixin],
  props: {
    isHideDots: Boolean,
    isHideAttach: Boolean,
    id: String,
    title: String
  },
  methods: {
    onClose() {
      const { params } = this.$route.params;

      if (params) {
        this.$router.go(-1);
      }
      this.$emit('updateClose');
    }
  }
}
</script>

<style scoped>

</style>
