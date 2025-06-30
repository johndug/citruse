<template>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <button
                    @click="openModal('add')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                    Add Product
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th v-for="year in productStore.numberYears" :key="year" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ year }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="product in productStore.products" :key="product.code" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.description }}</td>
                            <td v-for="year in productStore.numberYears" :key="year" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatCurrency(product.prices.find((price: any) => price.year === year)?.price || 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button
                                    @click="openModal('edit', product.code, product.description)"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteProduct(product.code)"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal v-if="selectedProduct" :action="selectedProduct.action" :code="selectedProduct.code" :description="selectedProduct.description" @close="closeModal" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import useProductStore from '../../store/useProductStore'
import { formatCurrency } from '../../utils/currency'
import type { ProductRequest } from '../../types/types'
import Modal from './ProductModal.vue'

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
