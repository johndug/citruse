<template>
    <div>
        <h1>Vendors</h1>
        <div>
            <button @click="openModal('add')">Add Vendor</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Sales Contact</th>
                    <th>Logistics Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="vendor in vendorStore.vendors">
                    <td>{{ vendor.name }}</td>
                    <td>{{ vendor.type }}</td>
                    <td>{{ vendor.sales_contact.name }} {{ vendor.sales_contact.phone }}</td>
                    <td>{{ vendor.logistics_contact.name }} {{ vendor.logistics_contact.phone }}</td>
                    <td>
                        <button @click="openModal('edit', vendor)">Edit</button>
                        <button @click="deleteVendor(vendor.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <VendorModal v-if="selectedVendor" v-bind="selectedVendor" @close="closeModal" />
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