import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import useAuthUser from './useAuthUser'
import type { Vendor, VendorRequest } from '../types/types'

export default defineStore('vendorStore', () => {
    const vendors = ref<Vendor[]>([])
    const authUser = useAuthUser()

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
                console.error(error)
            })
    }

    const updateVendor = async (id: number, vendor: VendorRequest) => {
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
                console.error(error)
            })
    }

    const deleteVendor = async (id: number) => {
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
                console.error(error)
            })
    }

    return {
        vendors,
        fetchVendors,
        createVendor,
        updateVendor,
        deleteVendor,
    }
})