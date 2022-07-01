<template>
  <div class="table-wrapper">
    <div class="table-responsive">
      <v-data-table
          :headers="headers"
          :items="[formatProductsToGenerate, headers]"
          class="table"
          hide-default-header
          hide-default-footer
          :items-per-page="9999"
          height="2500"
      >
        <template v-slot:header="{props}">
          <thead>
          <tr>
            <template
                v-for="(header, idx) in props.headers"
            >
              <th
                  :key="header.title"
                  :ref="header.value"
                  :colspan="props.headers.length-1 === idx ? 2 : null"
              >

                <div class="th-block">
                  <span class="header-title">{{ header.title }}</span>
                  <span
                      class="sortable-icon"
                      v-if="header.is_sortable"
                  >
                    <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
                  </span>
                </div>
              </th>
            </template>
          </tr>
          </thead>
        </template>
        <template v-slot:body="{items}">
          <tbody>
            <tr v-for="(item, idx) in items[0]" :key="item.id">
              <td v-for="(header, key) in items[1]" :key="key" :ref="key">
                {{ getTableValue(item[header.value], header.value) }}
              </td>
              <td class="remove" @click="onRemove(idx)">
                <svg-sprite width="12" height="13" icon-id="crossSmall"></svg-sprite>
              </td>
            </tr>
          </tbody>
        </template>
      </v-data-table>
    </div>
  </div>
</template>

<script>
export default {
  name: "GenerateTable",
  props: {
    headers: Array,
    items: Array,
    allCharacteristics: Array,
    getTableValue: Function
  },
  computed: {
    formatProductsToGenerate() {
      console.log(this.items)
      return this.items.map(product => {
            const newProduct = {
              name: product.name,
              sku: null //product.sku
            }

            const productStats = product.characteristic_value || null;

            if (productStats) {
              productStats.forEach(char => {
                newProduct[`char_${char.id}`] = char['value_id'];
              })
            }

            return newProduct
          })
    }
  },
  methods: {
    onRemove(idx) {
      this.$emit('updateRemove', idx)
    }
  }
}
</script>

<style scoped lang="scss">
.table-wrapper {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;

    .table-responsive {
      td {
        padding: 16px 30px !important;
      }
    }
  }

  .remove {
    text-align: right;
    cursor: pointer;
  }
</style>
