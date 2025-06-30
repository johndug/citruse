<template>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Vendors</h1>
                <button
                    @click="openModal('add')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                    Add Vendor
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logistics Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="vendor in vendorStore.vendors" :key="vendor.id || vendor.name" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ vendor.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                      :class="vendor.type === 'supplier' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                                    {{ vendor.type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>
                                    <div class="font-medium text-gray-900">{{ vendor.sales_contact.name }}</div>
                                    <div class="text-gray-500">{{ vendor.sales_contact.phone }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>
                                    <div class="font-medium text-gray-900">{{ vendor.logistics_contact.name }}</div>
                                    <div class="text-gray-500">{{ vendor.logistics_contact.phone }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button
                                    @click="openModal('edit', vendor)"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteVendor(vendor.id)"
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
        <VendorModal v-if="selectedVendor" v-bind="selectedVendor" @close="closeModal" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import useVendorStore from '../../store/useVendorStore'
import VendorModal from './VendorModal.vue'
import type { VendorRequest, Vendor } from '../../types/types'

const vendorStore = useVendorStore()
const selectedVendor = ref<VendorRequest | null>(null)

const openModal = (action: 'add' | 'edit', vendor?: Vendor) => {
    if (action === 'add') {
        const vendorRequest: VendorRequest = {
            id: null,
            name: '',
            address: '',
            country: '',
            vat_number: '',
            type: 'supplier',
            sales_contact_id: null,
            logistics_contact_id: null,
            action: action,
        }
        selectedVendor.value = vendorRequest
    } else if (vendor) {
        const vendorRequest: VendorRequest = {
            id: vendor.id,
            name: vendor.name,
            address: vendor.address,
            country: vendor.country,
            vat_number: vendor.vat_number,
            type: vendor.type,
            sales_contact_id: vendor.sales_contact.id,
            logistics_contact_id: vendor.logistics_contact.id,
            action: action,
        }
        selectedVendor.value = vendorRequest
    }
}

const deleteVendor = (id: number) => {
    if (confirm('Are you sure you want to delete this vendor?')) {
        vendorStore.deleteVendor(id)
    }
}

const closeModal = () => {
    selectedVendor.value = null
}

onMounted(() => {
    vendorStore.fetchVendors()
})
</script>
