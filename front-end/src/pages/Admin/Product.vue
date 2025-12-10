<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Produk</h1>
        <p class="text-gray-600 mt-2">Kelola semua produk yang tersedia di MiniEcommerce</p>
      </div>
      <button
        @click="openCreateModal"
        :disabled="loading"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <Plus class="h-5 w-5 mr-2" />
        <span v-if="loading && modalMode === 'create'">Menyimpan...</span>
        <span v-else>Tambah Produk</span>
      </button>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <!-- Table Header -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900">Daftar Produk</h2>
          <div class="flex items-center space-x-3">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Search class="h-5 w-5 text-gray-400" />
              </div>
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari produk..."
                class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              />
            </div>
            <select
              v-model="selectedCategory"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            >
              <option value="">Semua Kategori</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
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
                  <Image class="h-4 w-4 mr-2" />
                  Gambar
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Package class="h-4 w-4 mr-2" />
                  Nama Produk
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <DollarSign class="h-4 w-4 mr-2" />
                  Harga
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Layers class="h-4 w-4 mr-2" />
                  Stok
                </div>
              </th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center">
                  <Tag class="h-4 w-4 mr-2" />
                  Kategori
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
              <td colspan="6" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <Loader class="h-12 w-12 text-purple-600 mx-auto mb-4 animate-spin" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Memuat data...</h3>
                  <p class="text-gray-600">Sedang mengambil data produk dari server.</p>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-else-if="!loading && products.length === 0">
              <td colspan="6" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <Package class="h-16 w-16 text-gray-300 mx-auto mb-4" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada data produk</h3>
                  <p class="text-gray-600 mb-6">
                    Mulai dengan menambahkan produk pertama Anda untuk dijual.
                  </p>
                  <button
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
                  >
                    <Plus class="h-4 w-4 mr-2" />
                    Tambah Produk Pertama
                  </button>
                </div>
              </td>
            </tr>

            <!-- No Search Results -->
            <tr v-else-if="!loading && products.length > 0 && filteredProducts.length === 0">
              <td colspan="6" class="py-16 text-center">
                <div class="max-w-sm mx-auto">
                  <Search class="h-16 w-16 text-gray-300 mx-auto mb-4" />
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Produk tidak ditemukan</h3>
                  <p class="text-gray-600 mb-6">
                    Tidak ada produk yang sesuai dengan pencarian "{{ searchQuery }}"
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

            <!-- Product Rows -->
            <tr
              v-for="product in filteredProducts"
              :key="product.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="py-4 px-6">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg overflow-hidden flex items-center justify-center">
                  <Image v-if="!product.image" class="h-8 w-8 text-purple-300" />
                  <img v-else :src="product.image" :alt="product.name" class="w-full h-full object-cover" />
                </div>
              </td>
              <td class="py-4 px-6">
                <div>
                  <p class="font-medium text-gray-900">{{ product.name }}</p>
                  <p class="text-sm text-gray-500 truncate max-w-xs">{{ product.description || 'Tidak ada deskripsi' }}</p>
                </div>
              </td>
              <td class="py-4 px-6 whitespace-nowrap">
                <div class="font-semibold text-gray-900">Rp {{ formatPrice(product.price) }}</div>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center">
                  <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                    <div
                      :class="[
                        'h-2 rounded-full',
                        product.stock > 20 ? 'bg-green-500' : 
                        product.stock > 5 ? 'bg-yellow-500' : 
                        'bg-red-500'
                      ]"
                      :style="{ width: Math.min((product.stock / 50) * 100, 100) + '%' }"
                    ></div>
                  </div>
                  <span
                    :class="[
                      'font-medium',
                      product.stock > 20 ? 'text-green-700' : 
                      product.stock > 5 ? 'text-yellow-700' : 
                      'text-red-700'
                    ]"
                  >
                    {{ product.stock }}
                  </span>
                </div>
              </td>
              <td class="py-4 px-6">
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                  {{ product.category?.name || 'Tidak ada kategori' }}
                </span>
              </td>
              <td class="py-4 px-6 whitespace-nowrap">
                <div class="flex items-center space-x-2">
                  <button
                    @click="openEditModal(product)"
                    :disabled="loading"
                    class="flex items-center px-3 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <Edit2 class="h-4 w-4 mr-1" />
                    Edit
                  </button>
                  <button
                    @click="confirmDelete(product)"
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

      <!-- Pagination -->
      <div v-if="filteredProducts.length > 0" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <p class="text-sm text-gray-700">
            Menampilkan <span class="font-medium">{{ filteredProducts.length }}</span> dari <span class="font-medium">{{ products.length }}</span> produk
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
        <div
          class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle bg-white rounded-2xl shadow-xl transform transition-all"
          :class="modalAnimation"
        >
          <!-- Modal Header -->
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
              <div class="p-2 bg-gradient-to-r from-purple-100 to-purple-50 rounded-lg mr-3">
                <component :is="modalMode === 'create' ? Plus : Edit2" class="h-6 w-6 text-purple-600" />
              </div>
              <h3 class="text-xl font-bold text-gray-900">
                {{ modalMode === 'create' ? 'Tambah Produk Baru' : 'Edit Produk' }}
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Nama Produk -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Produk *
                </label>
                <input
                  type="text"
                  v-model="formData.name"
                  placeholder="Masukkan nama produk"
                  required
                  :disabled="loading"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors disabled:opacity-50"
                />
              </div>

              <!-- Deskripsi -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Deskripsi
                </label>
                <textarea
                  v-model="formData.description"
                  rows="3"
                  placeholder="Deskripsi lengkap tentang produk..."
                  :disabled="loading"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors resize-none disabled:opacity-50"
                ></textarea>
              </div>

              <!-- Harga -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Harga (Rp) *
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <DollarSign class="h-5 w-5 text-gray-400" />
                  </div>
                  <input
                    type="number"
                    v-model="formData.price"
                    placeholder="0"
                    required
                    min="0"
                    :disabled="loading"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors disabled:opacity-50"
                  />
                </div>
              </div>

              <!-- Stok -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Stok *
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <Layers class="h-5 w-5 text-gray-400" />
                  </div>
                  <input
                    type="number"
                    v-model="formData.stock"
                    placeholder="0"
                    required
                    min="0"
                    :disabled="loading"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors disabled:opacity-50"
                  />
                </div>
              </div>

              <!-- Kategori -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Kategori *
                </label>
                <select
                  v-model="formData.category_id"
                  required
                  :disabled="loading"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors disabled:opacity-50"
                >
                  <option value="">Pilih Kategori</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
              </div>

              <!-- Gambar -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Gambar Produk
                </label>
                <div class="flex items-center justify-center w-full">
                  <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <Upload class="h-8 w-8 text-gray-400 mb-2" />
                      <p class="text-sm text-gray-500">{{ selectedImageFile ? selectedImageFile.name : 'Klik untuk upload' }}</p>
                      <p v-if="!selectedImageFile" class="text-xs text-gray-400 mt-1">Format: JPG, PNG, maks 2MB</p>
                    </div>
                    <input 
                      type="file" 
                      class="hidden" 
                      @change="handleImageUpload" 
                      accept="image/*" 
                      :disabled="loading"
                    />
                  </label>
                </div>
                <div v-if="selectedImageFile" class="mt-2 flex items-center justify-between">
                  <span class="text-sm text-gray-600">{{ selectedImageFile.name }}</span>
                  <button
                    type="button"
                    @click="removeImage"
                    class="text-red-600 hover:text-red-800 text-sm"
                  >
                    Hapus
                  </button>
                </div>
              </div>

              <!-- Image Preview -->
              <div v-if="formData.image || imagePreview" class="flex items-center justify-center">
                <div class="relative">
                  <img 
                    :src="imagePreview || formData.image" 
                    alt="Preview" 
                    class="w-32 h-32 object-cover rounded-lg border border-gray-200" 
                  />
                  <button
                    v-if="imagePreview || formData.image"
                    type="button"
                    @click="removeImage"
                    class="absolute -top-2 -right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
                  >
                    <X class="h-3 w-3" />
                  </button>
                </div>
              </div>
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
                <span v-else>{{ modalMode === 'create' ? 'Simpan Produk' : 'Perbarui Produk' }}</span>
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
            Hapus Produk?
          </h3>
          <p class="text-gray-600 text-center mb-6">
            Apakah Anda yakin ingin menghapus produk
            <span class="font-semibold text-red-600">"{{ productToDelete?.name }}"</span>?
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
                Gambar produk juga akan dihapus dari server.
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
  Package, Plus, Search, Edit2, Trash2,
  Image, DollarSign, Layers, Tag, Settings,
  X, AlertTriangle, Upload, AlertCircle, Loader
} from 'lucide-vue-next'

const API = import.meta.env.VITE_API_URL
const searchQuery = ref('')
const selectedCategory = ref('')
const showModal = ref(false)
const showDeleteModal = ref(false)
const modalMode = ref('create')
const modalAnimation = ref('scale-100')
const productToDelete = ref(null)
const products = ref([])
const categories = ref([])
const loading = ref(false)
const error = ref('')
const selectedImageFile = ref(null)
const imagePreview = ref(null)

// Form data
const formData = ref({
  id: null,
  name: '',
  description: '',
  price: '',
  stock: '',
  category_id: '',
  image: ''
})

// Filter produk berdasarkan pencarian dan kategori
const filteredProducts = computed(() => {
  let filtered = products.value
  
  // Filter berdasarkan pencarian
  if (searchQuery.value.trim()) {
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (product.description && product.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    )
  }
  
  // Filter berdasarkan kategori
  if (selectedCategory.value) {
    filtered = filtered.filter(product => 
      product.category?.id === parseInt(selectedCategory.value)
    )
  }
  
  return filtered
})

// Get auth token
const getAuthToken = () => {
  const token = localStorage.getItem('token')
  return token ? `Bearer ${token}` : ''
}

// Format harga
const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

// Fetch categories from API
const fetchCategories = async () => {
  try {
    const response = await axios.get(`${API}/category`)
    
    if (response.data.success) {
      categories.value = response.data.data || []
    } else {
      console.error('Failed to fetch categories:', response.data.message)
    }
  } catch (err) {
    console.error('Error fetching categories:', err)
  }
}

// Fetch products from API
const fetchProducts = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await axios.get(`${API}/product`)
    
    if (response.data.success) {
      products.value = response.data.data || []
    } else {
      error.value = response.data.message || 'Gagal mengambil data produk'
    }
  } catch (err) {
    console.error('Error fetching products:', err)
    if (err.response?.status === 401) {
      error.value = 'Sesi telah berakhir. Silakan login kembali.'
    } else {
      error.value = 'Terjadi kesalahan saat mengambil data produk'
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
const openEditModal = (product) => {
  formData.value = {
    id: product.id,
    name: product.name,
    description: product.description || '',
    price: product.price,
    stock: product.stock,
    category_id: product.category?.id || '',
    image: product.image || ''
  }
  selectedImageFile.value = null
  imagePreview.value = null
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
    selectedImageFile.value = null
    imagePreview.value = null
  }, 150)
}

// Reset form
const resetForm = () => {
  formData.value = {
    id: null,
    name: '',
    description: '',
    price: '',
    stock: '',
    category_id: '',
    image: ''
  }
}

// Handle image upload
const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validasi ukuran file (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
      error.value = 'Ukuran file terlalu besar. Maksimal 2MB.'
      return
    }
    
    // Validasi tipe file
    if (!file.type.match('image.*')) {
      error.value = 'File harus berupa gambar (JPG, PNG)'
      return
    }
    
    selectedImageFile.value = file
    imagePreview.value = URL.createObjectURL(file)
    error.value = ''
  }
}

// Remove image
const removeImage = () => {
  selectedImageFile.value = null
  imagePreview.value = null
  formData.value.image = ''
  error.value = ''
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
    
    // Prepare form data for file upload
    const formDataToSend = new FormData()
    
    // Add form fields
    formDataToSend.append('name', formData.value.name)
    formDataToSend.append('description', formData.value.description || '')
    formDataToSend.append('price', formData.value.price)
    formDataToSend.append('stock', formData.value.stock)
    formDataToSend.append('category_id', formData.value.category_id)
    
    // Add image if selected
    if (selectedImageFile.value) {
      formDataToSend.append('image', selectedImageFile.value)
    }
    
    const config = {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json', 
        'Content-Type': 'multipart/form-data'
      }
    }
    
    if (modalMode.value === 'create') {
      // Create product
      const response = await axios.post(`${API}/product`, formDataToSend, config)
      
      if (response.data.success) {
        await fetchProducts() // Refresh data
        closeModal()
      } else {
        error.value = response.data.message || 'Gagal menambahkan produk'
      }
    } else {
      // Update product - jika tidak ada gambar baru, kirim null untuk image
      if (!selectedImageFile.value && !formData.value.image) {
        formDataToSend.append('image', '') // Kirim string kosong untuk hapus image
      }
      
      formDataToSend.append('_method', 'PUT')
      const response = await axios.post(`${API}/product/${formData.value.id}`, formDataToSend, config)
      
      if (response.data.success) {
        await fetchProducts() // Refresh data
        closeModal()
      } else {
        error.value = response.data.message || 'Gagal mengupdate produk'
      }
    }
  } catch (err) {
    console.error('Error saving product:', err)
    
    if (err.response) {
      if (err.response.status === 401) {
        error.value = 'Sesi telah berakhir. Silakan login kembali.'
      } else if (err.response.status === 403) {
        error.value = 'Anda tidak memiliki izin untuk melakukan aksi ini'
      } else if (err.response.status === 422) {
        const errors = err.response.data.errors
        if (errors?.name) {
          error.value = errors.name[0]
        } else if (errors?.price) {
          error.value = errors.price[0]
        } else if (errors?.stock) {
          error.value = errors.stock[0]
        } else if (errors?.category_id) {
          error.value = errors.category_id[0]
        } else if (errors?.image) {
          error.value = errors.image[0]
        } else {
          error.value = 'Data yang dimasukkan tidak valid'
        }
      } else if (err.response.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = 'Terjadi kesalahan saat menyimpan produk'
      }
    } else {
      error.value = 'Terjadi kesalahan pada server'
    }
  } finally {
    loading.value = false
  }
}

// Konfirmasi hapus
const confirmDelete = (product) => {
  productToDelete.value = product
  showDeleteModal.value = true
}

// Batalkan hapus
const cancelDelete = () => {
  if (loading.value) return
  
  showDeleteModal.value = false
  productToDelete.value = null
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
    
    const response = await axios.delete(`${API}/product/${productToDelete.value.id}`, config)
    
    if (response.data.success) {
      await fetchProducts() // Refresh data
      showDeleteModal.value = false
      productToDelete.value = null
    } else {
      error.value = response.data.message || 'Gagal menghapus produk'
    }
  } catch (err) {
    console.error('Error deleting product:', err)
    
    if (err.response) {
      if (err.response.status === 401) {
        error.value = 'Sesi telah berakhir. Silakan login kembali.'
      } else if (err.response.status === 403) {
        error.value = 'Anda tidak memiliki izin untuk menghapus produk'
      } else if (err.response.status === 404) {
        error.value = 'Produk tidak ditemukan'
        await fetchProducts() // Refresh karena mungkin sudah dihapus
      } else if (err.response.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = 'Terjadi kesalahan saat menghapus produk'
      }
    } else {
      error.value = 'Terjadi kesalahan pada server'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>