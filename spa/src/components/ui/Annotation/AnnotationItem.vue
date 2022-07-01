<template>
  <v-col cols="12">
    <span class="validation-rules"><slot></slot></span>
  </v-col>
</template>

<script>
export default {
  name: "AnnotationItem",
  props: {
    title: String
  }
}
</script>

<style scoped>

</style>