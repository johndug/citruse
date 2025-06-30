import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import type { AuthUser } from '../types/types'
import { useRouter } from 'vue-router'

export default defineStore('userStore', () => {
    // Initialize user from localStorage or undefined
    const stored = localStorage.getItem('authUser')
    const user = ref<AuthUser | undefined>(stored ? JSON.parse(stored) : undefined)
    const router = useRouter()

    const hasAuth = computed<boolean>(() => Boolean(user.value));

    const role = computed<string>(() => user.value?.role || '')

    // Save user to localStorage
    const saveUser = (userData: AuthUser | undefined) => {
        user.value = userData
        if (userData) {
            localStorage.setItem('authUser', JSON.stringify(userData))
        } else {
            localStorage.removeItem('authUser')
        }
    }

    const login = async (email: string, password: string) => {
        await axios.post('/login', { email, password })
            .then(res => res.data)
            .then(data => {
                saveUser(data)
                return user.value
            })
            .catch(error => {
                if (error.response?.status === 401) {
                    saveUser(undefined)
                    return;
                }
                throw error
            })
    }

    const fetchUser = async () => {
        await axios.get('/user/me')
            .then(res => res.data)
            .then(data => {
                saveUser(data)
                return user.value
            })
            .catch(error => {
                if (error.response?.status === 401) {
                    saveUser(undefined)
                    return;
                }
                throw error
            })
    }

    const logout = async () => {
        try {
            await axios.post('/logout', {}, {
                headers: {
                    'Authorization': `Bearer ${user.value?.token}`
                }
            })
            saveUser(undefined)
        } catch (error) {
            console.error(error)
            saveUser(undefined)
            router.push('/login')
            throw error
        }
    }

    return {
        user,
        hasAuth,
        role,
        login,
        fetchUser,
        logout
    }
})
