<template>
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1>{{ props.action === 'edit' ? 'Edit Purchase Order' : 'Add Purchase Order' }}</h1>
                <button @click="closeModal">Close</button>
            </div>
            <form @submit.prevent="savePurchaseOrder" v-if="!purchaseOrderStore.isLoading">
                <div>
                    <label for="vendor_id">Vendor</label>
                    <select id="vendor_id" v-model="formData.vendor_id">
                        <option v-for="vendor in vendorStore.vendors" :key="vendor.id" :value="vendor.id">{{ vendor.name }}</option>
                    </select>
                </div>
                <div>
                    <label for="product_code">Product</label>
                    <select id="product_code" v-model="formData.product_code">
                        <option v-for="product in productStore.products" :key="product.code" :value="product.code">{{ product.description }}</option>
                    </select>
                </div>
                <div>
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" v-model="formData.quantity" />
                </div>
                <div>
                    <label for="delivery_date">Delivery Date</label>
                    <input type="date" id="delivery_date" v-model="formData.delivery_date" />
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" v-model="formData.status">
                        <option v-for="status in purchaseOrderStatuses" :key="status" :value="status">{{ status }}</option>
                    </select>
                </div>
                <button type="submit">{{ props.action === 'edit' ? 'Update' : 'Create' }}</button>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import type { PurchaseOrderRequest, PurchaseOrderStatus } from '../../types/types'
import useVendorStore from '../../store/useVendorStore'
import useProductStore from '../../store/useProductStore'
import usePurchaseOrderStore from '../../store/usePurchaseOrderStore'
import useAuthUser from '../../store/useAuthUser'

const authStore = useAuthUser()
// only show statuses that are allowed for the user
const purchaseOrderStatuses: PurchaseOrderStatus[] = authStore.role === 'admin' ? ['new', 'accepted_by_supplier', 'in_delivery', 'delivered', 'rejected_by_supplier', 'rejected_by_distributor', 'cancelled'] : ['new']

const vendorStore = useVendorStore()
const productStore = useProductStore()
const purchaseOrderStore = usePurchaseOrderStore()
const emit = defineEmits(['close'])
const props = defineProps<PurchaseOrderRequest>()

const formData = ref({
    vendor_id: props.vendor_id || null,
    product_code: props.product_code || '',
    quantity: props.quantity || 0,
    delivery_date: props.delivery_date || '',
    status: props.status || 'new',
    po_id: props.po_id || null,
    action: props.action
})

const savePurchaseOrder = () => {
    purchaseOrderStore.createPurchaseOrder(formData.value)
    closeModal()
}

const closeModal = () => {
    emit('close')
}

onMounted(() => {
    vendorStore.fetchVendors()
    productStore.fetchProducts()
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
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
}
</style>