<template>
  <v-tooltip :class="customClass" right>
    <template v-slot:activator="{ on, attrs }">
        <span
            v-bind="attrs"
            v-on="on"
        >
          <slot></slot>
        </span>
    </template>
    <span>{{ title }}</span>
  </v-tooltip>
</template>

<script>
export default {
  name: 'toolTip',
  props: {
    title: String,
    customClass: String
  }
}
</script>

<style lang="scss">
.tool-tip-step {
  margin-left: 10px;
  display: inline-block;
}
</style>