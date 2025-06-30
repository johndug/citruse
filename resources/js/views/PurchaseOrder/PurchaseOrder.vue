<template>
    <div>
        <h1>Purchase Order</h1>
        <button @click="openModal('add', null)">Add Purchase Order</button>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>ORDER #</th>
                        <th>VENDOR</th>
                        <th>PRODUCT CODE</th>
                        <th>QUANTITY</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th v-if="authStore.role === 'admin'">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="purchaseOrder in purchaseOrderStore.purchaseOrders" :key="purchaseOrder.po_id">
                        <td>{{ purchaseOrder.po_id }}</td>
                        <td>{{ purchaseOrder.vendor_id }}</td>
                        <td>{{ purchaseOrder.product_code }}</td>
                        <td>{{ purchaseOrder.quantity }}</td>
                        <td>{{ formatCurrency(purchaseOrder.total) }}</td>
                        <td>{{ purchaseOrder.status }}</td>
                        <td v-if="authStore.role === 'admin'">
                            <button @click="openModal('edit', purchaseOrder)">Edit</button>
                            <button @click="deletePurchaseOrder(purchaseOrder.po_id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
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

const openModal = (action: 'add' | 'edit', purchaseOrder: PurchaseOrder | null) => {

    selectedPurchaseOrder.value = {
        po_id: purchaseOrder?.po_id || null,
        vendor_id: purchaseOrder?.vendor_id || null,
        product_code: purchaseOrder?.product_code || '',
        quantity: purchaseOrder?.quantity || 0,
        delivery_date: purchaseOrder?.delivery_date || '',
        status: purchaseOrder?.status || 'new',
        action: action || 'add'
    }

}

const deletePurchaseOrder = (po_id: string) => {
    if (confirm('Are you sure you want to delete this purchase order?')) {
        purchaseOrderStore.deletePurchaseOrder(po_id)
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