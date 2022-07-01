<template>
  <v-col offset="4" cols="8">
    <div class="table-rest">
      <div class="table-rest-title">
        склад
      </div>
      <div class="table-wrapper">
        <div class="table-responsive">
          <v-simple-table>
            <template v-slot:default>
              <tbody>
              <tr
                  v-for="item in rest"
                  :key="item.warehouse"
              >
                <td :class="{'firstTd':!idx}" v-for="(child,name, idx) in item" :key="idx">{{child}}</td>
              </tr>
              </tbody>
            </template>
          </v-simple-table>
        </div>
      </div>
    </div>
  </v-col>
</template>

<script>
export default {
  name: "TableRest",
  props: {
    rest: Array
  }
}
</script>

<style lang="scss" scoped>
  .firstTd {
    width: 295px;

    & ~ td {
      text-align: center;
    }
  }
</style>
<style lang="scss">
.table-rest {

  .theme--light.v-data-table {
    background: #F4F9FF;
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;
    font-size: 15px;
    line-height: 15px;
    color: #7E7E7E;
  }

  .theme--light.v-data-table > .v-data-table__wrapper > table > tbody > tr:not(:last-child) > td:not(.v-data-table__mobile-row), .theme--light.v-data-table > .v-data-table__wrapper > table > tbody > tr:not(:last-child) > th:not(.v-data-table__mobile-row) {
    border-bottom: 1px solid #E3F0FF;
  }

  .theme--light.v-data-table > .v-data-table__wrapper > table > tbody > tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
    background: #F4F9FF;
  }

  &-title {
    font-weight: 550;
    font-size: 15px;
    line-height: 15px;
    text-transform: capitalize;
    color: #5893D4;
    margin-bottom: 5px;
  }
}
</style>