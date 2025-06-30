<template>
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1>{{ props.action === 'edit' ? 'Edit Vendor' : 'Add Vendor' }}</h1>
                <button @click="closeModal">Close</button>
            </div>
            <form @submit.prevent="saveVendor">
                <div>
                    <label for="name">Name</label>
                    <input
                        type="text"
                        id="name"
                        v-model="formData.name"
                        :readonly="props.action === 'edit'"
                    />
                </div>
                <div>
                    <label for="address">Address</label>
                    <input type="text" id="address" v-model="formData.address" />
                </div>
                <div>
                    <label for="country">Country</label>
                    <input type="text" id="country" v-model="formData.country" />
                </div>
                <div>
                    <label for="vat_number">VAT Number</label>
                    <input type="text" id="vat_number" v-model="formData.vat_number" />
                </div>
                <div>
                    <label for="type">Type</label>
                    <select id="type" v-model="formData.type">
                        <option value="supplier">Supplier</option>
                        <option value="distributor">Distributor</option>
                    </select>
                </div>
                <div>
                    <label for="sales_contact_id">Sales Contact</label>
                    <select id="sales_contact_id" v-model="formData.sales_contact_id">
                        <option v-for="contact in contactStore.contacts.filter(contact => contact.role === 'sales')" :key="contact.id" :value="contact.id">{{ contact.name }}</option>
                    </select>
                </div>
                <div>
                    <label for="logistics_contact_id">Logistics Contact</label>
                    <select id="logistics_contact_id" v-model="formData.logistics_contact_id">
                        <option v-for="contact in contactStore.contacts.filter(contact => contact.role === 'logistics')" :key="contact.id" :value="contact.id">{{ contact.name }}</option>
                    </select>
                </div>
                <button type="submit">Save</button>
            </form>
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