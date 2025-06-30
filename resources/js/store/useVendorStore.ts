import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import useAuthUser from './useAuthUser'
import type { Vendor, VendorRequest } from '../types/types'

export default defineStore('vendorStore', () => {
    const vendors = ref<Vendor[]>([])
    const authUser = useAuthUser()
    const isLoading = ref<boolean>(false)
    const error = ref<string | null>(null)

    const fetchVendors = async () => {
        await axios.get('/vendors', {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                vendors.value = data.data
            })
    }

    const createVendor = async (vendor: VendorRequest) => {
        isLoading.value = true
        await axios.post('/vendors', vendor, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                vendors.value.push(data)
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while creating vendor'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const updateVendor = async (id: number, vendor: VendorRequest) => {
        isLoading.value = true
        await axios.put(`/vendors/${id}`, vendor, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                vendors.value = vendors.value.map(vendor => vendor.id === id ? data : vendor)
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while updating vendor'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const deleteVendor = async (id: number) => {
        isLoading.value = true
        await axios.delete(`/vendors/${id}`, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                vendors.value = vendors.value.filter(vendor => vendor.id !== id)
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while deleting vendor'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    return {
        vendors,
        fetchVendors,
        createVendor,
        updateVendor,
        deleteVendor,
        isLoading,
        error,
    }
})