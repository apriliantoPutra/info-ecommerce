<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Kategori</h1>
        <p class="text-gray-600 mt-2">Kelola kategori produk untuk mengorganisir koleksi Anda</p>
      </div>
      <button
        @click="openCreateModal"
        :disabled="loading"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <Plus class="h-5 w-5 mr-2" />
        <span v-if="loading && modalMode === 'create'">Menyimpan...</span>
        <span v-else>Tambah Kategori</span>
      </button>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <!-- Table Header -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900">Daftar Kategori</h2>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search class="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari kategori..."
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
                  <Tag class="h-4 w-4 mr-2" />
                  Nama Kategori
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <FileText class="h-4 w-4 mr-2" />
                  Deskripsi
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Settings class="h-4 w-4 mr-2" />
                  Aksi
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Loading State -->
            <tr v-if="loading && !showModal">
              <td colspan="4" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <Loader class="h-12 w-12 text-purple-600 mx-auto mb-4 animate-spin" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Memuat data...</h3>
                  <p class="text-gray-600">Sedang mengambil data kategori dari server.</p>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-else-if="!loading && categories.length === 0">
              <td colspan="4" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <FolderOpen class="h-16 w-16 text-gray-300 mx-auto mb-4" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada data kategori</h3>
                  <p class="text-gray-600 mb-6">
                    Mulai dengan menambahkan kategori pertama Anda untuk mengorganisir produk.
                  </p>
                  <button
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
                  >
                    <Plus class="h-4 w-4 mr-2" />
                    Tambah Kategori Pertama
                  </button>
                </div>
              </td>
            </tr>

            <!-- No Search Results -->
            <tr v-else-if="!loading && categories.length > 0 && filteredCategories.length === 0">
              <td colspan="4" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <Search class="h-16 w-16 text-gray-300 mx-auto mb-4" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Kategori tidak ditemukan</h3>
                  <p class="text-gray-600 mb-6">
                    Tidak ada kategori yang sesuai dengan pencarian "{{ searchQuery }}"
                  </p>
                  <button
                    @click="searchQuery = ''"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
                  >
                    <X class="h-4 w-4 mr-2" />
                    Reset Pencarian
                  </button>
                </div>
              </td>
            </tr>

            <!-- Category Rows -->
            <tr
              v-for="(category, index) in filteredCategories"
              :key="category.id"
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
                    <Tag class="h-5 w-5 text-purple-600" />
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">{{ category.name }}</p>
                    <p class="text-sm text-gray-500">ID: CAT-{{ category.id.toString().padStart(3, '0') }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <p class="text-gray-600 max-w-md truncate">{{ category.description || 'Tidak ada deskripsi' }}</p>
              </td>
              <td class="py-4 px-6 whitespace-nowrap">
                <div class="flex items-center space-x-2">
                  <button
                    @click="openEditModal(category)"
                    :disabled="loading"
                    class="flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <Edit2 class="h-4 w-4 mr-1" />
                    Edit
                  </button>
                  <button
                    @click="confirmDelete(category)"
                    :disabled="loading"
                    class="flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <Trash2 class="h-4 w-4 mr-1" />
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination (if needed) -->
      <div v-if="filteredCategories.length > 0" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <p class="text-sm text-gray-700">
            Menampilkan <span class="font-medium">{{ filteredCategories.length }}</span> dari <span class="font-medium">{{ categories.length }}</span> kategori
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

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm bg-black/50"
      @click.self="closeModal"
    >
      <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <!-- Modal Content -->
        <div
          class="inline-block w-full max-w-md p-6 my-8 text-left align-middle bg-white rounded-2xl shadow-xl transform transition-all"
          :class="modalAnimation"
        >
          <!-- Modal Header -->
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
              <div class="p-2 bg-gradient-to-r from-purple-100 to-purple-50 rounded-lg mr-3">
                <component :is="modalMode === 'create' ? Plus : Edit2" class="h-6 w-6 text-purple-600" />
              </div>
              <h3 class="text-xl font-bold text-gray-900">
                {{ modalMode === 'create' ? 'Tambah Kategori Baru' : 'Edit Kategori' }}
              </h3>
            </div>
            <button
              @click="closeModal"
              class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              :disabled="loading"
            >
              <X class="h-5 w-5 text-gray-500" />
            </button>
          </div>

          <!-- Modal Body -->
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Error Message -->
            <div v-if="error" class="p-3 bg-red-50 text-red-700 rounded-lg text-sm text-center border border-red-100">
              {{ error }}
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Nama Kategori *
              </label>
              <input
                type="text"
                v-model="formData.name"
                placeholder="Contoh: Elektronik, Fashion, dll."
                required
                :disabled="loading"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors disabled:opacity-50"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
              </label>
              <textarea
                v-model="formData.description"
                rows="3"
                placeholder="Deskripsi singkat tentang kategori ini..."
                :disabled="loading"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors resize-none disabled:opacity-50"
              ></textarea>
              <p class="text-xs text-gray-500 mt-2">Opsional, maksimal 200 karakter</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                :disabled="loading"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-md hover:shadow-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Loader v-if="loading" class="h-4 w-4 inline mr-2 animate-spin" />
                <span v-else>{{ modalMode === 'create' ? 'Simpan Kategori' : 'Perbarui Kategori' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm bg-black/50"
      @click.self="cancelDelete"
    >
      <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle bg-white rounded-2xl shadow-xl transform transition-all">
          <!-- Warning Icon -->
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
              <AlertTriangle class="h-8 w-8 text-red-600" />
            </div>
          </div>

          <!-- Delete Message -->
          <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
            Hapus Kategori?
          </h3>
          <p class="text-gray-600 text-center mb-6">
            Apakah Anda yakin ingin menghapus kategori
            <span class="font-semibold text-red-600">"{{ categoryToDelete?.name }}"</span>?
            Tindakan ini tidak dapat dibatalkan.
          </p>

          <!-- Delete Actions -->
          <div class="flex items-center justify-center space-x-3">
            <button
              @click="cancelDelete"
              :disabled="loading"
              class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Batal
            </button>
            <button
              @click="handleDelete"
              :disabled="loading"
              class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Loader v-if="loading" class="h-4 w-4 inline mr-2 animate-spin" />
              <template v-else>
                <Trash2 class="h-4 w-4 inline mr-2" />
                Ya, Hapus
              </template>
            </button>
          </div>

          <!-- Warning Note -->
          <div class="mt-6 p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
            <div class="flex items-start">
              <AlertCircle class="h-5 w-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" />
              <p class="text-sm text-yellow-800">
                Produk yang terkait dengan kategori ini akan dipindahkan ke kategori "Lainnya".
              </p>
            </div>
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
  Tag, Plus, Search, Edit2, Trash2, Hash,
  FileText, Settings, FolderOpen, X,
  AlertTriangle, AlertCircle, Loader
} from 'lucide-vue-next'

const API = import.meta.env.VITE_API_URL
const searchQuery = ref('')
const showModal = ref(false)
const showDeleteModal = ref(false)
const modalMode = ref('create')
const modalAnimation = ref('scale-100')
const categoryToDelete = ref(null)
const categories = ref([])
const loading = ref(false)
const error = ref('')

// Form data (tanpa status)
const formData = ref({
  id: null,
  name: '',
  description: ''
})

// Filter kategori berdasarkan pencarian
const filteredCategories = computed(() => {
  if (!searchQuery.value.trim()) return categories.value
  
  return categories.value.filter(category =>
    category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (category.description && category.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
  )
})

// Get auth token
const getAuthToken = () => {
  const token = localStorage.getItem('token')
  return token ? `Bearer ${token}` : ''
}

// Fetch categories from API
const fetchCategories = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await axios.get(`${API}/category`)
    
    if (response.data.success) {
      categories.value = response.data.data || []
    } else {
      error.value = response.data.message || 'Gagal mengambil data kategori'
    }
  } catch (err) {
    console.error('Error fetching categories:', err)
    if (err.response?.status === 401) {
      error.value = 'Sesi telah berakhir. Silakan login kembali.'
      // Redirect ke login jika diperlukan
    } else {
      error.value = 'Terjadi kesalahan saat mengambil data kategori'
    }
  } finally {
    loading.value = false
  }
}

// Buka modal tambah
const openCreateModal = () => {
  resetForm()
  modalMode.value = 'create'
  showModal.value = true
  modalAnimation.value = 'scale-95'
  setTimeout(() => {
    modalAnimation.value = 'scale-100'
  }, 10)
}

// Buka modal edit
const openEditModal = (category) => {
  formData.value = {
    id: category.id,
    name: category.name,
    description: category.description || ''
  }
  modalMode.value = 'edit'
  showModal.value = true
  modalAnimation.value = 'scale-95'
  setTimeout(() => {
    modalAnimation.value = 'scale-100'
  }, 10)
}

// Tutup modal
const closeModal = () => {
  if (loading.value) return
  
  modalAnimation.value = 'scale-95'
  setTimeout(() => {
    showModal.value = false
    resetForm()
    error.value = ''
  }, 150)
}

// Reset form
const resetForm = () => {
  formData.value = {
    id: null,
    name: '',
    description: ''
  }
}

// Handle submit form (Create/Update)
const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const token = getAuthToken()
    if (!token) {
      error.value = 'Anda harus login terlebih dahulu'
      loading.value = false
      return
    }
    
    const config = {
      headers: {
        'Authorization': token,
        'Content-Type': 'application/json'
      }
    }
    
    if (modalMode.value === 'create') {
      // Create category
      const response = await axios.post(`${API}/category`, formData.value, config)
      
      if (response.data.success) {
        await fetchCategories() // Refresh data
        closeModal()
      } else {
        error.value = response.data.message || 'Gagal menambahkan kategori'
      }
    } else {
      // Update category
      const response = await axios.put(`${API}/category/${formData.value.id}`, formData.value, config)
      
      if (response.data.success) {
        await fetchCategories() // Refresh data
        closeModal()
      } else {
        error.value = response.data.message || 'Gagal mengupdate kategori'
      }
    }
  } catch (err) {
    console.error('Error saving category:', err)
    
    if (err.response) {
      if (err.response.status === 401) {
        error.value = 'Sesi telah berakhir. Silakan login kembali.'
      } else if (err.response.status === 403) {
        error.value = 'Anda tidak memiliki izin untuk melakukan aksi ini'
      } else if (err.response.status === 422) {
        const errors = err.response.data.errors
        if (errors?.name) {
          error.value = errors.name[0]
        } else if (errors?.description) {
          error.value = errors.description[0]
        } else {
          error.value = 'Data yang dimasukkan tidak valid'
        }
      } else if (err.response.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = 'Terjadi kesalahan saat menyimpan kategori'
      }
    } else {
      error.value = 'Terjadi kesalahan pada server'
    }
  } finally {
    loading.value = false
  }
}

// Konfirmasi hapus
const confirmDelete = (category) => {
  categoryToDelete.value = category
  showDeleteModal.value = true
}

// Batalkan hapus
const cancelDelete = () => {
  if (loading.value) return
  
  showDeleteModal.value = false
  categoryToDelete.value = null
}

// Handle hapus
const handleDelete = async () => {
  loading.value = true
  
  try {
    const token = getAuthToken()
    if (!token) {
      error.value = 'Anda harus login terlebih dahulu'
      loading.value = false
      return
    }
    
    const config = {
      headers: {
        'Authorization': token
      }
    }
    
    const response = await axios.delete(`${API}/category/${categoryToDelete.value.id}`, config)
    
    if (response.data.success) {
      await fetchCategories() // Refresh data
      showDeleteModal.value = false
      categoryToDelete.value = null
    } else {
      error.value = response.data.message || 'Gagal menghapus kategori'
    }
  } catch (err) {
    console.error('Error deleting category:', err)
    
    if (err.response) {
      if (err.response.status === 401) {
        error.value = 'Sesi telah berakhir. Silakan login kembali.'
      } else if (err.response.status === 403) {
        error.value = 'Anda tidak memiliki izin untuk menghapus kategori'
      } else if (err.response.status === 404) {
        error.value = 'Kategori tidak ditemukan'
        await fetchCategories() // Refresh karena mungkin sudah dihapus
      } else if (err.response.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = 'Terjadi kesalahan saat menghapus kategori'
      }
    } else {
      error.value = 'Terjadi kesalahan pada server'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCategories()
})
</script>