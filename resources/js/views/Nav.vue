<template>
	<div class="min-h-screen bg-gray-50">
		<nav class="bg-white shadow-lg border-b border-gray-200">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16">
					<div class="flex items-center space-x-8">
						<div class="flex-shrink-0">
							<h1 class="text-xl font-bold text-gray-900">Citrus Management</h1>
						</div>
						<div class="hidden md:block">
							<div class="ml-10 flex items-baseline space-x-4">
								<router-link
									to="/"
									v-if="authStore.hasAuth"
									class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
									active-class="text-blue-600 bg-blue-50"
								>
									Purchase Orders
								</router-link>
								<router-link
									to="/products"
									v-if="authStore.hasAuth && authStore.role === 'admin'"
									class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
									active-class="text-blue-600 bg-blue-50"
								>
									Products
								</router-link>
								<router-link
									to="/vendors"
									v-if="authStore.hasAuth && authStore.role === 'admin'"
									class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
									active-class="text-blue-600 bg-blue-50"
								>
									Vendors
								</router-link>
							</div>
						</div>
					</div>
					<div class="flex items-center space-x-4">
						<router-link
							to="/login"
							v-if="!authStore.hasAuth"
							class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
						>
							Login
						</router-link>
						<button
							@click="logout"
							v-if="authStore.hasAuth"
							class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
						>
							Logout
						</button>
					</div>
				</div>
			</div>
		</nav>
		<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
			<router-view></router-view>
		</main>
	</div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import useAuthUser from '../store/useAuthUser'

const router = useRouter()
const authStore = useAuthUser()

const logout = async () => {
	await authStore.logout()
	router.push('/login')
}
</script>
