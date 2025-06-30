<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ props.action === 'edit' ? 'Edit Vendor' : 'Add Vendor' }}
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

                <form @submit.prevent="saveVendor" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input
                            type="text"
                            id="name"
                            v-model="formData.name"
                            :readonly="props.action === 'edit'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'bg-gray-100': props.action === 'edit' }"
                        />
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input
                            type="text"
                            id="address"
                            v-model="formData.address"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input
                            type="text"
                            id="country"
                            v-model="formData.country"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label for="vat_number" class="block text-sm font-medium text-gray-700 mb-1">VAT Number</label>
                        <input
                            type="text"
                            id="vat_number"
                            v-model="formData.vat_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select
                            id="type"
                            v-model="formData.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                            <option value="">Select type</option>
                            <option value="supplier">Supplier</option>
                            <option value="distributor">Distributor</option>
                        </select>
                    </div>
                    <div>
                        <label for="sales_contact_id" class="block text-sm font-medium text-gray-700 mb-1">Sales Contact</label>
                        <select
                            id="sales_contact_id"
                            v-model="formData.sales_contact_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                            <option value="">Select sales contact</option>
                            <option v-for="contact in contactStore.contacts.filter((contact: any) => contact.role === 'sales')" :key="contact.id" :value="contact.id">{{ contact.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="logistics_contact_id" class="block text-sm font-medium text-gray-700 mb-1">Logistics Contact</label>
                        <select
                            id="logistics_contact_id"
                            v-model="formData.logistics_contact_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                            <option value="">Select logistics contact</option>
                            <option v-for="contact in contactStore.contacts.filter((contact: any) => contact.role === 'logistics')" :key="contact.id" :value="contact.id">{{ contact.name }}</option>
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
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import type { VendorRequest, VendorType } from '../../types/types'
import useVendorStore from '../../store/useVendorStore'
import useContactStore from '../../store/useContactStore'

const vendorStore = useVendorStore()
const contactStore = useContactStore()

const modal = ref<HTMLElement | null>(null)

const emit = defineEmits<{
    (e: 'close'): void
}>()

const props = defineProps<VendorRequest>()

const formData = ref({
    id: null as number | null,
    name: '',
    address: '',
    country: '',
    vat_number: '',
    type: '',
    sales_contact_id: null as number | null,
    logistics_contact_id: null as number | null,
    action: 'add' as 'add' | 'edit',
})

// Watch for prop changes and update form data
watch(() => props, (newProps) => {
    formData.value.id = newProps.id || null
    formData.value.name = newProps.name || ''
    formData.value.address = newProps.address || ''
    formData.value.country = newProps.country || ''
    formData.value.vat_number = newProps.vat_number || ''
    formData.value.type = newProps.type || ''
    formData.value.sales_contact_id = newProps.sales_contact_id || null
    formData.value.logistics_contact_id = newProps.logistics_contact_id || null
    formData.value.action = newProps.action || 'add'
}, { immediate: true })

const saveVendor = () => {
    if (formData.value.sales_contact_id === null || formData.value.logistics_contact_id === null) {
        return
    }

    const vendorRequest: VendorRequest = {
        id: formData.value.id,
        name: formData.value.name,
        address: formData.value.address,
        country: formData.value.country,
        vat_number: formData.value.vat_number,
        type: formData.value.type as VendorType,
        sales_contact_id: formData.value.sales_contact_id,
        logistics_contact_id: formData.value.logistics_contact_id,
        action: props.action,
    }

    if (formData.value.id) {
        vendorStore.updateVendor(formData.value.id, vendorRequest)
    } else {
        vendorStore.createVendor(vendorRequest)
    }
    closeModal()
}

const closeModal = () => {
    emit('close')
}

onMounted(() => {
    contactStore.fetchUsers()
})
</script>
