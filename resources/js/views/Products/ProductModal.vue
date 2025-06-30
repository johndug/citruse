<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ props.action === 'edit' ? 'Edit Product' : 'Add Product' }}
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

                <form @submit.prevent="saveProduct" class="space-y-4" :disabled="productStore.isLoading">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <input
                            type="text"
                            id="code"
                            v-model="formData.code"
                            :readonly="props.action === 'edit'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            :class="{ 'bg-gray-100': props.action === 'edit' }"
                        />
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input
                            type="text"
                            id="description"
                            v-model="formData.description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
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
                <ErrorAlert :error-message="productStore.error" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import type { ProductRequest } from '../../types/types'
import useProductStore from '../../store/useProductStore'
import useContactStore from '../../store/useContactStore'
import ErrorAlert from '../../components/ErrorAlert.vue'

const productStore = useProductStore()
const contactStore = useContactStore()

const modal = ref<HTMLElement | null>(null)

const emit = defineEmits<{
    (e: 'close'): void
}>()

const props = defineProps<ProductRequest>()

const formData = ref({
    code: '',
    description: ''
})

// Watch for prop changes and update form data
watch(() => props.code, (newCode) => {
    formData.value.code = newCode || ''
}, { immediate: true })

watch(() => props.description, (newDescription) => {
    formData.value.description = newDescription || ''
}, { immediate: true })

const saveProduct = () => {
    if (props.action === 'edit') {
        productStore.updateProduct(formData.value.code, formData.value.description)
    } else {
        productStore.createProduct(formData.value.code, formData.value.description)
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
