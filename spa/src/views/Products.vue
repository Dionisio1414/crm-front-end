<template>
  <div class="page product-page">
    <div class="card-grid">
      <ProductsToBuy />
      <ProcurementActivity />
      <OrdersShipment />
    </div>
  </div>
</template>

<script>

import OrdersShipment from '@/components/dashboard/products/OrdersShipment'
import ProcurementActivity from '@/components/dashboard/products/ProcurementActivity'
import ProductsToBuy from "@/components/dashboard/products/ProductsToBuy"

export default {
  name: 'home',
  components: {
    OrdersShipment,
    ProcurementActivity,
    ProductsToBuy
  },
}
</script>