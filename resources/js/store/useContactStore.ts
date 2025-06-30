import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import useAuthUser from './useAuthUser'
import { Contact } from '../types/types'

export default defineStore('contactStore', () => {
    const contacts = ref<Contact[]>([])
    const authUser = useAuthUser()

    const fetchUsers = async () => {
        await axios.get('/users', {
            headers: {
                'Authorization': `Bearer ${authUser.user?.token}`
            }
        })
        .then(res => res.data)
        .then(data => {
            contacts.value = data.data.filter((user: any) => user.role === 'sales' || user.role === 'logistics').map((user: any) => ({
                id: user.id,
                name: user.name,
                email: user.email,
                phone: user.phone,
                role: user.role,
            }))
        })
        .catch(error => {
            console.error(error)
        })
    }

    return {
        contacts,
        fetchUsers,
    }
})