<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Pengguna</h1>
        <p class="text-gray-600 mt-2">Lihat dan kelana semua pengguna terdaftar di sistem</p>
      </div>
      <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <div class="px-4 py-2 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
          <p class="text-sm text-gray-600">Total Pengguna</p>
          <p class="font-semibold text-purple-700">{{ users.length }}</p>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
      <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-50 to-purple-100 rounded-full mb-4">
          <Users class="h-8 w-8 text-purple-600 animate-spin" />
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Memuat data pengguna...</h3>
        <p class="text-gray-600">Sedang mengambil data dari server</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
      <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-red-50 to-red-100 rounded-full mb-4">
          <AlertCircle class="h-8 w-8 text-red-600" />
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Gagal memuat data</h3>
        <p class="text-gray-600 mb-4">{{ error }}</p>
        <button
          @click="fetchUsers"
          class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
        >
          <RefreshCw class="h-4 w-4 mr-2" />
          Coba Lagi
        </button>
      </div>
    </div>

    <!-- Users Table -->
    <div v-else class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <!-- Table Header -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900">Daftar Pengguna</h2>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search class="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari pengguna..."
              class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Table Content -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Hash class="h-4 w-4 mr-2" />
                  No
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <User class="h-4 w-4 mr-2" />
                  Username
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Mail class="h-4 w-4 mr-2" />
                  Email
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Calendar class="h-4 w-4 mr-2" />
                  Bergabung
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Empty State -->
            <tr v-if="filteredUsers.length === 0">
              <td colspan="6" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <Users class="h-16 w-16 text-gray-300 mx-auto mb-4" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada data pengguna</h3>
                  <p class="text-gray-600">
                    Belum ada pengguna yang terdaftar di sistem.
                  </p>
                </div>
              </td>
            </tr>

            <!-- User Rows -->
            <tr
              v-for="(user, index) in filteredUsers"
              :key="user.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="py-4 px-6 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-8 h-8 flex items-center justify-center bg-purple-100 text-purple-600 rounded-lg font-medium">
                    {{ index + 1 }}
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center">
                  <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg mr-3">
                    <User class="h-5 w-5 text-purple-600" />
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">{{ user.username }}</p>
                    <p class="text-sm text-gray-500">ID: USR-{{ user.id.toString().padStart(3, '0') }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center">
                  <Mail class="h-4 w-4 text-gray-400 mr-2" />
                  <p class="text-gray-600">{{ user.email }}</p>
                </div>
              </td>
              <td class="py-4 px-6">
                <div class="text-gray-600">
                  <div class="text-sm">{{ formatDate(user.created_at) }}</div>
                  <div class="text-xs text-gray-500">{{ formatTime(user.created_at) }}</div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="filteredUsers.length > 0" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <p class="text-sm text-gray-700">
            Menampilkan <span class="font-medium">{{ filteredUsers.length }}</span> dari <span class="font-medium">{{ users.length }}</span> pengguna
          </p>
          <div class="flex space-x-2">
            <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
              Sebelumnya
            </button>
            <button class="px-3 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700 transition-colors">
              1
            </button>
            <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
              Selanjutnya
            </button>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import {
  Users,
  User,
  Mail,
  Hash,
  Search,
  Calendar,
  Info,
  AlertCircle,
  RefreshCw
} from 'lucide-vue-next'

const API = import.meta.env.VITE_API_URL

// State
const users = ref([])
const loading = ref(false)
const error = ref('')
const searchQuery = ref('')

// Fetch users from API
const fetchUsers = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Anda perlu login sebagai admin')
    }
    
    const response = await axios.get(`${API}/user`, {
      headers: { 
        Authorization: `Bearer ${token}` 
      }
    })
    
    if (response.data.success) {
      users.value = response.data.data || []
    } else {
      error.value = response.data.message || 'Gagal mengambil data pengguna'
    }
  } catch (err) {
    console.error('Error fetching users:', err)
    
    if (err.response?.status === 401) {
      error.value = 'Sesi telah berakhir. Silakan login kembali.'
    } else if (err.response?.status === 403) {
      error.value = 'Anda tidak memiliki akses admin untuk melihat data pengguna'
    } else if (err.response?.status === 404) {
      error.value = 'Endpoint API tidak ditemukan'
    } else if (err.message === 'Anda perlu login sebagai admin') {
      error.value = err.message
    } else {
      error.value = 'Terjadi kesalahan saat mengambil data pengguna'
    }
  } finally {
    loading.value = false
  }
}

// Format tanggal
const formatDate = (dateString) => {
  if (!dateString) return '-'
  
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    })
  } catch (err) {
    console.error('Error formatting date:', err)
    return '-'
  }
}

// Format waktu
const formatTime = (dateString) => {
  if (!dateString) return '-'
  
  try {
    const date = new Date(dateString)
    return date.toLocaleTimeString('id-ID', {
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch (err) {
    console.error('Error formatting time:', err)
    return '-'
  }
}

// Filter pengguna berdasarkan pencarian
const filteredUsers = computed(() => {
  if (!searchQuery.value.trim()) return users.value
  
  const query = searchQuery.value.toLowerCase()
  return users.value.filter(user =>
    user.username.toLowerCase().includes(query) ||
    user.email.toLowerCase().includes(query)
  )
})

onMounted(() => {
  fetchUsers()
})
</script>