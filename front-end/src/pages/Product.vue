<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <header class="bg-gradient-to-r from-purple-600 to-purple-800 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
          <div class="mb-6 md:mb-0">
            <div class="flex items-center space-x-3 mb-4">
              <Package class="h-8 w-8 text-purple-200" />
              <h1 class="text-3xl md:text-4xl font-bold">Katalog Produk</h1>
            </div>
            <p class="text-lg text-purple-100 max-w-2xl">
              Temukan berbagai produk berkualitas dengan harga terbaik. 
              Pilihan tepat untuk kebutuhan sehari-hari Anda.
            </p>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search Bar -->
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Search class="h-5 w-5 text-gray-400" />
              </div>
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari produk..."
                :disabled="loading"
                class="pl-10 pr-4 py-3 w-full sm:w-64 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent disabled:opacity-50"
                @keyup.enter="handleSearch"
              />
            </div>
            
            <!-- Filter Dropdown -->
            <div class="relative">
              <button
                @click="toggleFilter"
                :disabled="loading"
                class="flex items-center justify-between px-4 py-3 bg-white text-gray-800 rounded-lg hover:bg-gray-50 transition-colors w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50"
              >
                <Filter class="h-5 w-5 mr-2 text-purple-600" />
                <span class="font-medium">Filter</span>
                <ChevronDown class="h-4 w-4 ml-2" :class="showFilter ? 'rotate-180' : ''" />
              </button>
              
              <!-- Filter Dropdown Menu -->
              <div
                v-if="showFilter"
                class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-10"
              >
                <div class="p-4">
                  <h3 class="font-semibold text-gray-900 mb-3">Kategori</h3>
                  <div class="space-y-2" v-if="!loadingCategories">
                    <div v-if="categories.length > 0">
                      <label class="flex items-center space-x-2 cursor-pointer">
                        <input
                          type="checkbox"
                          :value="'all'"
                          v-model="selectedCategories"
                          class="rounded text-purple-600 focus:ring-purple-500"
                          @change="toggleAllCategories"
                        />
                        <span class="text-gray-700">Semua Kategori</span>
                      </label>
                      <hr class="my-2 border-gray-200">
                      <label
                        v-for="category in categories"
                        :key="category.id"
                        class="flex items-center space-x-2 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          :value="category.id"
                          v-model="selectedCategories"
                          class="rounded text-purple-600 focus:ring-purple-500"
                          @change="handleCategoryChange"
                        />
                        <span class="text-gray-700">{{ category.name }}</span>
                      </label>
                    </div>
                    <div v-else class="text-center py-4">
                      <Loader class="h-5 w-5 text-purple-600 animate-spin mx-auto" />
                    </div>
                  </div>
                  
                  <div class="mt-6 flex justify-between">
                    <button
                      @click="resetFilter"
                      class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                      Reset
                    </button>
                    <button
                      @click="applyFilter"
                      class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
                    >
                      Terapkan
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Stats Bar -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4"> <!-- Ubah dari 3 menjadi 2 kolom -->
          <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
            <div class="flex items-center space-x-3">
              <Package class="h-6 w-6 text-white" />
              <div>
                <p class="text-sm text-purple-100">Total Produk</p>
                <p class="text-xl font-bold">{{ filteredProducts.length }}</p>
              </div>
            </div>
          </div>
          <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
            <div class="flex items-center space-x-3">
              <Tag class="h-6 w-6 text-white" />
              <div>
                <p class="text-sm text-purple-100">Kategori</p>
                <p class="text-xl font-bold">{{ categories.length }}</p>
              </div>
            </div>
          </div>
          <!-- Hapus bagian cart dari stats -->
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Loading State -->
      <div v-if="loading && !products.length" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <Loader class="h-16 w-16 text-purple-600 mx-auto mb-6 animate-spin" />
          <h3 class="text-xl font-semibold text-gray-900 mb-3">Memuat Produk</h3>
          <p class="text-gray-600">
            Sedang mengambil data produk dari server...
          </p>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <AlertCircle class="h-16 w-16 text-red-500 mx-auto mb-6" />
          <h3 class="text-xl font-semibold text-gray-900 mb-3">Gagal Memuat Data</h3>
          <p class="text-gray-600 mb-6">
            {{ error }}
          </p>
          <button
            @click="fetchProducts"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
          >
            <RefreshCw class="h-4 w-4 mr-2" />
            Coba Lagi
          </button>
        </div>
      </div>

      <!-- Active Filters -->
      <div v-if="hasActiveFilters" class="mb-6">
        <div class="flex flex-wrap items-center gap-2">
          <span class="text-sm text-gray-600">Filter aktif:</span>
          <span v-if="searchQuery" class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm">
            Pencarian: "{{ searchQuery }}"
            <button @click="searchQuery = ''" class="ml-1 text-blue-600 hover:text-blue-800">
              <X class="h-3 w-3" />
            </button>
          </span>
          <span 
            v-for="categoryId in selectedCategories" 
            :key="categoryId"
            class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm"
          >
            {{ getCategoryName(categoryId) }}
            <button @click="removeCategory(categoryId)" class="ml-1 text-purple-600 hover:text-purple-800">
              <X class="h-3 w-3" />
            </button>
          </span>
          <button 
            @click="clearAllFilters"
            class="text-sm text-purple-600 hover:text-purple-800 font-medium"
          >
            Hapus Semua Filter
          </button>
        </div>
      </div>

      <!-- Product Grid -->
      <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="product in filteredProducts"
          :key="product.id"
          class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden group"
        >
          <!-- Product Image -->
          <div class="relative h-48 bg-gradient-to-br from-purple-50 to-blue-50 overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
              <Image v-if="!product.image" class="h-24 w-24 text-purple-200 group-hover:text-purple-300 transition-colors" />
              <img v-else :src="product.image" :alt="product.name" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
            </div>
            <!-- Category Badge -->
            <div class="absolute top-4 left-4">
              <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-purple-600 text-sm font-medium rounded-full">
                {{ product.category?.name || 'Tidak ada kategori' }}
              </span>
            </div>
            <!-- Stock Badge -->
            <div class="absolute top-4 right-4">
              <span
                :class="[
                  'px-3 py-1 text-sm font-medium rounded-full',
                  product.stock > 10 ? 'bg-green-100 text-green-800' : 
                  product.stock > 0 ? 'bg-yellow-100 text-yellow-800' : 
                  'bg-red-100 text-red-800'
                ]"
              >
                {{ product.stock > 0 ? `${product.stock} tersedia` : 'Habis' }}
              </span>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors line-clamp-1">
              {{ product.name }}
            </h3>
            
            <p class="text-gray-600 mb-4 line-clamp-2 min-h-[3rem]">
              {{ product.description || 'Tidak ada deskripsi' }}
            </p>
            
            <!-- Price -->
            <div class="flex items-center justify-between mb-6">
              <div>
                <span class="text-2xl font-bold text-gray-900">Rp {{ formatPrice(product.price) }}</span>
                <p class="text-sm text-gray-500">per unit</p>
              </div>
              <div class="text-right">
                <div class="flex items-center text-gray-500">
                  <Star class="h-4 w-4 text-yellow-400 fill-current" />
                  <Star class="h-4 w-4 text-yellow-400 fill-current" />
                  <Star class="h-4 w-4 text-yellow-400 fill-current" />
                  <Star class="h-4 w-4 text-yellow-400 fill-current" />
                  <Star class="h-4 w-4 text-gray-300" />
                  <span class="ml-1 text-sm">4.0</span>
                </div>
                <p class="text-sm text-gray-500">0 ulasan</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!loading && filteredProducts.length === 0" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <Package class="h-24 w-24 text-gray-300 mx-auto mb-6" />
          <h3 class="text-xl font-semibold text-gray-900 mb-3">Produk tidak ditemukan</h3>
          <p class="text-gray-600 mb-6">
            {{ searchQuery || selectedCategories.length > 0 
              ? 'Coba gunakan kata kunci pencarian yang berbeda atau atur ulang filter.' 
              : 'Belum ada produk yang tersedia.' 
            }}
          </p>
          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button
              @click="clearAllFilters"
              class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium"
            >
              <RefreshCw class="h-4 w-4 mr-2" />
              Reset Filter
            </button>
            <button
              @click="fetchProducts"
              class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg border border-purple-200 hover:bg-purple-50 transition-colors font-medium"
            >
              <Package class="h-4 w-4 mr-2" />
              Refresh Produk
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination (if needed) -->
      <div v-if="filteredProducts.length > 0 && products.length > 9" class="mt-12 text-center">
        <div class="flex items-center justify-center space-x-2">
          <button
            :disabled="currentPage === 1"
            @click="prevPage"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Sebelumnya
          </button>
          <span class="px-4 py-2 text-gray-700">
            Halaman {{ currentPage }} dari {{ totalPages }}
          </span>
          <button
            :disabled="currentPage === totalPages"
            @click="nextPage"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import {
  Package,
  Search,
  Filter,
  ChevronDown,
  Tag,
  Star,
  RefreshCw,
  Loader,
  Image,
  AlertCircle,
  X
} from 'lucide-vue-next'

const API = import.meta.env.VITE_API_URL
const router = useRouter()

// State
const searchQuery = ref('')
const showFilter = ref(false)
const selectedCategories = ref([])
const loading = ref(false)
const loadingCategories = ref(false)
const error = ref('')

// Pagination
const currentPage = ref(1)
const itemsPerPage = ref(9)

// Data from API
const products = ref([])
const categories = ref([])

// Computed Properties
const user = computed(() => {
  const u = localStorage.getItem('user')
  return u ? JSON.parse(u) : null
})

const hasActiveFilters = computed(() => {
  return searchQuery.value.trim() !== '' || selectedCategories.value.length > 0
})

const filteredProducts = computed(() => {
  let filtered = products.value
  
  // Filter berdasarkan pencarian
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(query) ||
      (product.description && product.description.toLowerCase().includes(query)) ||
      (product.category?.name && product.category.name.toLowerCase().includes(query))
    )
  }
  
  // Filter berdasarkan kategori
  if (selectedCategories.value.length > 0 && !selectedCategories.value.includes('all')) {
    filtered = filtered.filter(product => 
      product.category && selectedCategories.value.includes(product.category.id)
    )
  }
  
  // Pagination
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filtered.slice(start, end)
})

const totalPages = computed(() => {
  let filtered = products.value
  
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(query) ||
      (product.description && product.description.toLowerCase().includes(query))
    )
  }
  
  if (selectedCategories.value.length > 0 && !selectedCategories.value.includes('all')) {
    filtered = filtered.filter(product => 
      product.category && selectedCategories.value.includes(product.category.id)
    )
  }
  
  return Math.ceil(filtered.length / itemsPerPage.value)
})

// Methods
const fetchProducts = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await axios.get(`${API}/product`)
    
    if (response.data.success) {
      products.value = response.data.data || []
      // Reset pagination ketika data berubah
      currentPage.value = 1
    } else {
      error.value = response.data.message || 'Gagal mengambil data produk'
    }
  } catch (err) {
    console.error('Error fetching products:', err)
    if (err.response?.status === 401) {
      error.value = 'Sesi telah berakhir. Silakan login kembali.'
    } else if (err.response?.status === 403) {
      error.value = 'Anda tidak memiliki akses ke data produk'
    } else {
      error.value = 'Terjadi kesalahan saat mengambil data produk'
    }
  } finally {
    loading.value = false
  }
}

const fetchCategories = async () => {
  loadingCategories.value = true
  
  try {
    const response = await axios.get(`${API}/category`)
    
    if (response.data.success) {
      categories.value = response.data.data || []
    } else {
      console.error('Failed to fetch categories:', response.data.message)
    }
  } catch (err) {
    console.error('Error fetching categories:', err)
  } finally {
    loadingCategories.value = false
  }
}

const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

const getCategoryName = (categoryId) => {
  if (categoryId === 'all') return 'Semua Kategori'
  const category = categories.value.find(cat => cat.id == categoryId)
  return category ? category.name : 'Kategori Tidak Dikenal'
}

const toggleFilter = () => {
  if (loading.value || loadingCategories.value) return
  showFilter.value = !showFilter.value
}

const applyFilter = () => {
  showFilter.value = false
  currentPage.value = 1 // Reset ke halaman 1 saat apply filter
}

const handleSearch = () => {
  currentPage.value = 1 // Reset ke halaman 1 saat search
}

const resetFilter = () => {
  selectedCategories.value = []
  showFilter.value = false
  currentPage.value = 1
}

const clearAllFilters = () => {
  searchQuery.value = ''
  selectedCategories.value = []
  currentPage.value = 1
}

const removeCategory = (categoryId) => {
  selectedCategories.value = selectedCategories.value.filter(id => id !== categoryId)
  currentPage.value = 1
}

const toggleAllCategories = () => {
  if (selectedCategories.value.includes('all')) {
    selectedCategories.value = ['all']
  } else {
    selectedCategories.value = []
  }
}

const handleCategoryChange = () => {
  // Hapus 'all' jika memilih kategori spesifik
  if (selectedCategories.value.includes('all')) {
    selectedCategories.value = selectedCategories.value.filter(id => id !== 'all')
  }
  // Jika semua kategori terpilih, ganti dengan 'all'
  else if (selectedCategories.value.length === categories.value.length) {
    selectedCategories.value = ['all']
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Lifecycle Hooks
onMounted(() => {
  // Load initial data
  fetchProducts()
  fetchCategories()
  
  // Close filter dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative') && showFilter.value) {
      showFilter.value = false
    }
  })
})


</script>