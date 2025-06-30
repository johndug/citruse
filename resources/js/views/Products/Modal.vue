<template>
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1>{{ props.action === 'edit' ? 'Edit Product' : 'Add Product' }}</h1>
                <button @click="closeModal">Close</button>
            </div>
            <form @submit.prevent="saveProduct">
                <div>
                    <label for="code">Code</label>
                    <input
                        type="text"
                        id="code"
                        v-model="formData.code"
                        :readonly="props.action === 'edit'"
                    />
                </div>
                <div>
                    <label for="description">Description</label>
                    <input type="text" id="description" v-model="formData.description" />
                </div>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import type { ProductRequest } from '../../types/types'
import useProductStore from '../../store/useProductStore'
import useContactStore from '../../store/useContactStore'

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