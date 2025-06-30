import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import type { Price, Product } from '../types/types'
import useAuthUser from './useAuthUser'

export default defineStore('productStore', () => {
    const authUser = useAuthUser()
    const products = ref<Product[]>([])
    const isLoading = ref<boolean>(false)
    const numberYears = ref<number[]>([])
    const error = ref<string | null>(null)

    const fetchProducts = async () => {
        isLoading.value = true
        await axios.get('/products', {
                headers: {
                    'Authorization': `Bearer ${authUser.user?.token}`
                }
            })
            .then(res => res.data)
            .then(data => {
                products.value = data.data

                numberYears.value = [...new Set(products.value.flatMap((product: Product) => product.prices.map((price: Price) => price.year)))] as number[]
            })
            .catch(err => {
                error.value = err.response.data.message || 'An error occurred while fetching products'
            })
            .finally(() => {
                isLoading.value = false
            })
    }

    const updateProduct = async (code: string, description: string) => {
        isLoading.value = true
        await axios.put(`/products/${code}`, {
            description: description,
        }, {
            headers: {
                'Authorization': `Bearer ${authUser.user?.token}`
            }
        })
        .then(res => res.data)
        .then(data => {
            products.value = products.value.map((product: Product) => product.code === code ? data : product)
        })
        .catch(err => {
            error.value = err.response.data.message || 'An error occurred while updating product'
        })
        .finally(() => {
            isLoading.value = false
        })
    }

    const createProduct = async (code: string, description: string) => {
        isLoading.value = true
        await axios.post('/products', {
            code: code,
            description: description,
        }, {
            headers: {
                'Authorization': `Bearer ${authUser.user?.token}`
            }
        })
        .then(res => res.data)
        .then(data => {
            products.value.push(data)
        })
        .catch(err => {
            error.value = err.response.data.message || 'An error occurred while creating product'
        })
        .finally(() => {
            isLoading.value = false
        })
    }

    const deleteProduct = async (code: string) => {
        isLoading.value = true
        await axios.delete(`/products/${code}`, {
            headers: {
                'Authorization': `Bearer ${authUser.user?.token}`
            }
        })
        .then(res => res.data)
        .then(data => {
            products.value = products.value.filter((product: Product) => product.code !== code)
        })
        .catch(err => {
            error.value = err.response.data.message || 'An error occurred while deleting product'
        })
        .finally(() => {
            isLoading.value = false
        })
    }

    return {
        products,
        fetchProducts,
        numberYears,
        updateProduct,
        createProduct,
        deleteProduct,
        isLoading,
        error,
    }
})
