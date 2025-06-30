<template>
    <div>
        <h1>Purchase Orders</h1>
    </div>
    <PurchaseOrder v-if="purchaseOrderStore.isAllowed" />
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import PurchaseOrder from '../views/PurchaseOrder/PurchaseOrder.vue'
import usePurchaseOrderStore from '../store/usePurchaseOrderStore'

const purchaseOrderStore = usePurchaseOrderStore()

onMounted(() => {
    if (purchaseOrderStore.isAllowed) {
        purchaseOrderStore.fetchPurchaseOrders()
    }
})
</script>