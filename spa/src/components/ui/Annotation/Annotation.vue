<template>
  <v-row dense>
    <annotation-item :key="item + idx" v-for="(item, idx) in items">{{item}}</annotation-item>
  </v-row>
</template>

<script>
import AnnotationItem from "./AnnotationItem";
export default {
  name: "Annotation",
  components: {
    AnnotationItem
  },
  props: {
    items: Array
  }
}
</script>

<style scoped>

</style>