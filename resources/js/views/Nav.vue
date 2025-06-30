<template>
	<div>
		<nav>
			<router-link to="/" v-if="authStore.hasAuth">Purchase Orders</router-link> |
			<router-link to="/products" v-if="authStore.hasAuth && authStore.role === 'admin'">Products</router-link> |
			<router-link to="/vendors" v-if="authStore.hasAuth && authStore.role === 'admin'">Vendors</router-link> |
			<router-link to="/login" v-if="!authStore.hasAuth">Login</router-link> |
			<a href="#" @click="logout" v-if="authStore.hasAuth">Logout</a>
		</nav>
		<router-view></router-view>
	</div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import useAuthUser from '../store/useAuthUser'

const router = useRouter()
const authStore = useAuthUser()

const logout = async () => {
	await authStore.logout()
	router.push('/login')
}
</script>
