<template>
	<div>
		<h1>Login</h1>
	</div>
    <form @submit.prevent="handleSubmit">
        <input type="email" v-model="email" placeholder="Email" />
        <input type="password" v-model="password" placeholder="Password" />
        <button type="submit">Login</button>
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import useAuthUser from '../../store/useAuthUser'

const router = useRouter()
const authUser = useAuthUser()

const email = ref('')
const password = ref('')
const errorMessage = ref('')

const handleSubmit = async () => {
    errorMessage.value = ''

    try {
        await authUser.login(email.value, password.value)
        router.push('/')
    } catch (error: any) {
        if (error.response?.data?.message) {
            errorMessage.value = error.response.data.message
        } else {
            errorMessage.value = 'Login failed. Please try again.'
        }
    }
}
</script>

<style scoped>
.error {
    color: red;
    margin-top: 10px;
}
</style>
