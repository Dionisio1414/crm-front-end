<template>
  <draggable
    v-bind="dragOptions"
    tag="div"
    class="item-container"
    ghost-class="ghost"
    :list="list"
    :value="value"
    @start="dragging = true"
    @end="dragging = false"
    @input="emitter"
  >
    <div class="item-group" :key="el.id" v-for="el in realValue">
      <div @click="getCat(el)" class="item">{{ el.name }}</div>
      <nested-test class="item-sub 111" :list="el.elements" />
    </div>
  </draggable>
</template>

<script>
export default {
  name: "nested-test",
  data: () => ({
    drag: false
  }),
  methods: {
    emitter(value) {
      this.$emit("input", value);
    },
    getCat(cat) {
      console.log(cat.name)
    }
  },
  computed: {
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    },
    realValue() {
      return this.value ? this.value : this.list;
    }
  },
  props: {
    value: {
      required: false,
      type: Array,
      default: null
    },
    list: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

<style lang="sass" scoped>
.item-container
  max-width: 20rem
  margin: 0

.item
  padding: 1rem
  border-bottom: 1px solid #E3F0FF
  background-color: #fff

.item-sub
  margin: 0 0 0 1rem

.ghost
  opacity: 0.5
  background: #c8ebfb
</style>