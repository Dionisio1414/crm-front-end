<template>
  <div class="typeWithTooltip">
    <div class="typeWithTooltipTitle">{{ title }}</div>
    <div class="typeWithTooltipText">
      <v-tooltip v-if="tooltipText" bottom>
        <template v-slot:activator="{ on, attrs }">
              <span
                  v-bind="attrs"
                  v-on="on"
              >{{ text }}
              </span>
        </template>
        <span>{{ tooltipText }}</span>
      </v-tooltip>
      <span v-else>{{ text }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "TypeWithTooltip",
  props: {
    title: String,
    text: String,
    tooltipText: String
  }
}
</script>

<style scoped lang="scss">
.typeWithTooltip {
  margin-left: 50px;

  &Title {
    font-weight: bold;
    font-size: 13px;
    line-height: 1.5;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #317CCE;
    margin-bottom: 10px;
  }

  &Text {
    font-size: 14px;
    color: #7E7E7E;
  }
}
</style>