<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ props.action === 'edit' ? 'Edit Purchase Order' : 'Add Purchase Order' }}
                    </h3>
                    <button
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="savePurchaseOrder" v-if="!purchaseOrderStore.isLoading" class="space-y-4">
                    <div>
                        <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                        <select
                            id="vendor_id"
                            v-model="formData.vendor_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                            <option value="">Select a vendor</option>
                            <option v-for="vendor in vendorStore.vendors" :key="vendor.id" :value="vendor.id">{{ vendor.name }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="product_code" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                        <select
                            id="product_code"
                            v-model="formData.product_code"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                            <option value="">Select a product</option>
                            <option v-for="product in productStore.products" :key="product.code" :value="product.code">{{ product.description }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input
                            type="number"
                            id="quantity"
                            v-model="formData.quantity"
                            min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>

                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                        <input
                            type="date"
                            id="delivery_date"
                            v-model="formData.delivery_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select
                            id="status"
                            v-model="formData.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                            <option v-for="status in purchaseOrderStatuses" :key="status" :value="status">{{ status.replace(/_/g, ' ').replace(/\b\w/g, (l: string) => l.toUpperCase()) }}</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            @click="closeModal"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                        >
                            {{ props.action === 'edit' ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>

                <div v-if="purchaseOrderStore.isLoading" class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            </div>
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
    id: props.id || null,
    vendor_id: props.vendor_id || null,
    product_code: props.product_code || '',
    quantity: props.quantity || 0,
    delivery_date: props.delivery_date || '',
    status: props.status || 'new',
    po_id: props.po_id || null,
    action: props.action
})

const savePurchaseOrder = () => {
    if (props.action === 'edit') {
        purchaseOrderStore.updatePurchaseOrder(formData.value)
    } else {
        purchaseOrderStore.createPurchaseOrder(formData.value)
    }
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
