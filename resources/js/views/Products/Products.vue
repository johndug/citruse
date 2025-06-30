<template>
    <div>
        <h1>Products</h1>
        <div>
            <button @click="openModal('add')">Add Product</button>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th v-for="year in productStore.numberYears" :key="year">{{ year }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in productStore.products" :key="product.code">
                        <td>{{ product.code }}</td>
                        <td>{{ product.description }}</td>
                        <td v-for="year in productStore.numberYears" :key="year">{{ formatCurrency(product.prices.find(price => price.year === year)?.price || 0) }}</td>
                        <td>
                            <button @click="openModal('edit', product.code, product.description)">Edit</button>
                            <button @click="deleteProduct(product.code)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Modal v-if="selectedProduct" :action="selectedProduct.action" :code="selectedProduct.code" :description="selectedProduct.description" @close="closeModal" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import useProductStore from '../../store/useProductStore'
import { formatCurrency } from '../../utils/currency'
import type { ProductRequest } from '../../types/types'
import Modal from './Modal.vue'

const productStore = useProductStore()
const selectedProduct = ref<ProductRequest | null>(null)

onMounted(() => {
    productStore.fetchProducts()
})

const openModal = (action: 'add' | 'edit', code?: string, description?: string) => {
    selectedProduct.value = {
        code: code || '',
        description: description || '',
        action: action || 'add'
    }
}

const closeModal = () => {
    selectedProduct.value = null
}

const deleteProduct = (code: string) => {
    if (confirm('Are you sure you want to delete this product?')) {
        productStore.deleteProduct(code)
    }
}
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
}
</style>