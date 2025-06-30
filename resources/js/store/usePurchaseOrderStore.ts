import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import axios from 'axios'
import type { PurchaseOrder, PurchaseOrderRequest, PurchaseOrderStatus, Role } from '../types/types'
import useAuthUser from './useAuthUser'

export default defineStore('purchaseOrderStore', () => {
    const authUser = useAuthUser()
    const purchaseOrders = ref<PurchaseOrder[]>([])
    const purchaseOrderStatuses = ref<PurchaseOrderStatus[]>([])

    const allowedRoles: Role[] = ['admin', 'manager']
    const isAllowed = computed(() => allowedRoles.includes(authUser.role as Role))
    const isLoading = ref(false)
    const error = ref<string | null>(null)

    const fetchPurchaseOrders = async () => {
        isLoading.value = true
        await axios.get('/orders', {
            headers: {
                'Authorization': `Bearer ${authUser.user?.token}`
            }
        })
            .then(res => res.data)
            .then(data => {
                purchaseOrders.value = data.data
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while fetching purchase orders'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const fetchPurchaseOrderStatuses = async () => {
        isLoading.value = true
        await axios.get('/orders/statuses', {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                purchaseOrderStatuses.value = data.data
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while fetching purchase order statuses'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const createPurchaseOrder = async (purchaseOrder: PurchaseOrderRequest) => {
        isLoading.value = true
        await axios.post('/orders/pod', purchaseOrder, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                purchaseOrders.value.push(data)
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while creating purchase order'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const updatePurchaseOrder = async (purchaseOrder: PurchaseOrderRequest) => {
        isLoading.value = true
        await axios.put(`/orders/${purchaseOrder.id}`, purchaseOrder, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                purchaseOrders.value = purchaseOrders.value.map(order => order.po_id === purchaseOrder.po_id ? data : order)
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while updating purchase order'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const deletePurchaseOrder = async (id: number) => {
        isLoading.value = true
        await axios.delete(`/orders/${id}`, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                purchaseOrders.value = purchaseOrders.value.filter(order => order.id !== id)
            })
            .catch(error => {
                error.value = error.response.data.message || 'An error occurred while deleting purchase order'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    return {
        purchaseOrders,
        purchaseOrderStatuses,
        fetchPurchaseOrderStatuses,
        fetchPurchaseOrders,
        createPurchaseOrder,
        updatePurchaseOrder,
        deletePurchaseOrder,
        isAllowed,
        isLoading,
        error,
    }
})