<template>
  <span class="switch-text" :class="{'active': active}">{{ title }}</span>
</template>

<script>
export default {
  name: "SwitchText",
  props: {
    title: String,
    active: Boolean
  }
}
</script>
