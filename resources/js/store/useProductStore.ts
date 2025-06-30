import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import type { Price, Product } from '../types/types'
import useAuthUser from './useAuthUser'

export default defineStore('productStore', () => {
    const authUser = useAuthUser()
    const products = ref<Product[]>([])

    const numberYears = ref<number[]>([])

    const fetchProducts = async () => {
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
    }

    const updateProduct = async (code: string, description: string) => {
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
    }

    const createProduct = async (code: string, description: string) => {
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
    }

    const deleteProduct = async (code: string) => {

        await axios.delete(`/products/${code}`, {
            headers: {
                'Authorization': `Bearer ${authUser.user?.token}`
            }
        })
        .then(res => res.data)
        .then(data => {
            products.value = products.value.filter((product: Product) => product.code !== code)
        })
    }

    return {
        products,
        fetchProducts,
        numberYears,
        updateProduct,
        createProduct,
        deleteProduct,
    }
})
