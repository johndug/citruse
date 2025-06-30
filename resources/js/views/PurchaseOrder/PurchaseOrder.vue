<template>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Purchase Orders</h1>
                <button
                    v-if="authStore.role === 'admin' || authStore.role === 'manager'"
                    @click="openModal('add', null)"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                    Add Purchase Order
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ORDER #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VENDOR</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRODUCT CODE</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QUANTITY</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TOTAL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                            <th v-if="authStore.role === 'admin'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="purchaseOrder in purchaseOrderStore.purchaseOrders" :key="purchaseOrder.po_id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ purchaseOrder.po_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ purchaseOrder.vendor_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ purchaseOrder.product_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ purchaseOrder.quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ formatCurrency(purchaseOrder.total) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                      :class="getStatusColor(purchaseOrder.status)">
                                    {{ purchaseOrder.status }}
                                </span>
                            </td>
                            <td v-if="authStore.role === 'admin'" class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button
                                    @click="openModal('edit', purchaseOrder)"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deletePurchaseOrder(purchaseOrder.id)"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <POModal v-if="selectedPurchaseOrder" v-bind="selectedPurchaseOrder" @close="closeModal" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import useAuthUser from '../../store/useAuthUser'
import usePurchaseOrderStore from '../../store/usePurchaseOrderStore'
import { formatCurrency } from '../../utils/currency'
import type { PurchaseOrderRequest } from '../../types/types'
import POModal from './POModal.vue'
import type { PurchaseOrder } from '../../types/types'

const purchaseOrderStore = usePurchaseOrderStore()
const authStore = useAuthUser()

const selectedPurchaseOrder = ref<PurchaseOrderRequest | null>(null)

const getStatusColor = (status: string) => {
    switch (status) {
        case 'new': return 'bg-gray-100 text-gray-800'
        case 'accepted_by_supplier': return 'bg-green-100 text-green-800'
        case 'in_delivery': return 'bg-blue-100 text-blue-800'
        case 'delivered': return 'bg-green-100 text-green-800'
        case 'rejected_by_supplier': return 'bg-red-100 text-red-800'
        case 'rejected_by_distributor': return 'bg-red-100 text-red-800'
        case 'cancelled': return 'bg-gray-100 text-gray-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

const openModal = (action: 'add' | 'edit', purchaseOrder: PurchaseOrder | null) => {
    selectedPurchaseOrder.value = {
        po_id: purchaseOrder?.po_id || null,
        id: purchaseOrder?.id || null,
        vendor_id: purchaseOrder?.vendor_id || null,
        product_code: purchaseOrder?.product_code || '',
        quantity: purchaseOrder?.quantity || 0,
        delivery_date: purchaseOrder?.delivery_date || '',
        status: purchaseOrder?.status || 'new',
        action: action || 'add'
    }
}

const deletePurchaseOrder = (id: number) => {
    if (confirm('Are you sure you want to delete this purchase order?')) {
        purchaseOrderStore.deletePurchaseOrder(id)
    }
}

const closeModal = () => {
    selectedPurchaseOrder.value = null
}

onMounted(async () => {
    await purchaseOrderStore.fetchPurchaseOrders()
    console.log(purchaseOrderStore.purchaseOrders)
})
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
