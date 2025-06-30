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
                console.error(error)
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const fetchPurchaseOrderStatuses = async () => {
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
                console.error(error)
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
                console.error(error)
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const updatePurchaseOrder = async (purchaseOrder: PurchaseOrderRequest) => {
        isLoading.value = true
        await axios.put(`/orders/${purchaseOrder.po_id}`, purchaseOrder, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                purchaseOrders.value = purchaseOrders.value.map(order => order.po_id === purchaseOrder.po_id ? data.data : order)
            })
            .catch(error => {
                console.error(error)
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const deletePurchaseOrder = async (po_id: string) => {
        isLoading.value = true
        await axios.delete(`/orders/${po_id}`, {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                purchaseOrders.value = purchaseOrders.value.filter(order => order.po_id !== po_id)
            })
            .catch(error => {
                console.error(error)
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
        isLoading
    }
})