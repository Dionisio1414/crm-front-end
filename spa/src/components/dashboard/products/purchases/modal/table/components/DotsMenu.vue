<template>
  <v-menu
      content-class="dotsWrapper"
      nudge-top="40"
      bottom
      left
      offset-x
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
          class="dotsBtn"
          icon
          color="#E3F0FF"
          v-bind="attrs"
          v-on="on"
      >
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </template>
    <ul class="dotsDrop">
      <li>
            <span class="dotsIcon">
              <svg-sprite width="25" height="25" icon-id="analogs"></svg-sprite>
            </span>
        <span>Аналоги</span>
      </li>
      <li>
            <span class="dotsIcon">
              <svg-sprite width="25" height="25" icon-id="accompanying"></svg-sprite>
            </span>
        <span>Сопутствующие</span>
      </li>
    </ul>
  </v-menu>
</template>

<script>
export default {
  name: "DotsMenu"
}
</script>

<style lang="scss">
.dotsBtn {
  &:hover {
    color: #5893d4 !important;
  }

  &[aria-expanded="true"] {
    color: #5893d4 !important;
  }
}

.dotsWrapper {
  background: none;
  box-shadow: none;

  .dotsDrop {
    background: #F4F9FF;
    overflow: hidden;
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    list-style-type: none;
    padding: 10px;
    min-width: 160px;

    li {
      font-size: 13px;
      line-height: 13px;
      color: #5893D4;
      display: flex;
      align-items: center;
      cursor: pointer;

      svg circle {
        transition: all .3s ease;
      }

      &:first-child {
        margin-bottom: 10px;
      }

      .dotsIcon {
        margin-right: 10px;
        font-size: 0;
        line-height: 1;
      }

      &:hover {
        span {
          color: #5893D4;
          font-weight: 550;
        }

        .dotsIcon {
          svg circle {
            fill: #5893D4;
          }
        }
      }
    }
  }
}
</style>