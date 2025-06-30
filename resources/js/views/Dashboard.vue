<template>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Purchase Orders</h1>
            <p class="text-gray-600">Manage your purchase orders and track their status.</p>
        </div>
        <PurchaseOrder v-if="purchaseOrderStore.isAllowed" />
    </div>
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
