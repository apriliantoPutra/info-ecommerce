<template>
  <div class="min-h-screen flex flex-col bg-gray-100 text-gray-800">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <RouterLink to="/" class="flex items-center space-x-2 group">
            <div class="bg-white p-2 rounded-lg shadow-md group-hover:shadow-lg transition-shadow">
              <ShoppingBag class="h-6 w-6 text-purple-600" />
            </div>
            <span class="text-xl font-bold tracking-tight">
              <span class="text-white">Info</span>
              <span class="text-purple-200">Ecommerce</span>
            </span>
          </RouterLink>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-1">
            <RouterLink
              to="/"
              class="flex items-center px-4 py-2 rounded-lg hover:bg-purple-500/30 transition-all duration-200 group"
              active-class="bg-purple-500/20 text-white font-medium"
            >
              <Home class="h-4 w-4 mr-2" />
              Home
            </RouterLink>
            <RouterLink
              to="/products"
              class="flex items-center px-4 py-2 rounded-lg hover:bg-purple-500/30 transition-all duration-200 group"
              active-class="bg-purple-500/20 text-white font-medium"
            >
              <Package class="h-4 w-4 mr-2" />
              Products
            </RouterLink>

            <!-- Hapus tombol Cart dari navbar -->

            <!-- Auth Buttons / User Info -->
            <div class="flex items-center ml-4 space-x-2 border-l border-purple-500 pl-4">
              <template v-if="user">
                <!-- Hapus tombol Cart mobile dari auth section -->
                
                <span class="px-4 py-2 rounded-lg bg-purple-500/20">{{ user.username }}</span>
                <button
                  @click="handleLogout"
                  class="flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200"
                >
                  <LogOut class="h-4 w-4 mr-2" />
                  Logout
                </button>
              </template>
              <template v-else>
                <RouterLink
                  to="/login"
                  class="flex items-center px-4 py-2 rounded-lg hover:bg-purple-500/30 transition-all duration-200 group"
                >
                  <LogIn class="h-4 w-4 mr-2" />
                  Login
                </RouterLink>
                <RouterLink
                  to="/register"
                  class="flex items-center bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-100 transition-all duration-200 font-medium shadow-sm hover:shadow-md"
                >
                  <UserPlus class="h-4 w-4 mr-2" />
                  Register
                </RouterLink>
              </template>
            </div>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMenu"
            class="md:hidden p-2 rounded-lg hover:bg-purple-500/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-300"
          >
            <Menu v-if="!isOpen" class="h-6 w-6" />
            <X v-else class="h-6 w-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div
        v-show="isOpen"
        class="md:hidden bg-gradient-to-b from-purple-700 to-purple-800 border-t border-purple-500/50 animate-slide-down"
      >
        <div class="px-4 py-3 space-y-1">
          <RouterLink
            to="/"
            class="flex items-center px-4 py-3 rounded-lg hover:bg-purple-600 transition-all duration-200"
            @click="closeMenu"
            active-class="bg-purple-600"
          >
            <Home class="h-5 w-5 mr-3" />
            Home
          </RouterLink>
          <RouterLink
            to="/products"
            class="flex items-center px-4 py-3 rounded-lg hover:bg-purple-600 transition-all duration-200"
            @click="closeMenu"
            active-class="bg-purple-600"
          >
            <Package class="h-5 w-5 mr-3" />
            Products
          </RouterLink>

          <!-- Hapus tombol Cart dari mobile menu -->

          <!-- Auth Buttons / User Info Mobile -->
          <div class="pt-2 mt-2 border-t border-purple-500/30 space-y-1">
            <template v-if="user">
              <span class="block px-4 py-3 rounded-lg bg-purple-500/20">{{ user.username }}</span>
              <button
                @click="handleLogout"
                class="w-full text-left flex items-center px-4 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200"
              >
                <LogOut class="h-5 w-5 mr-3" />
                Logout
              </button>
            </template>
            <template v-else>
              <RouterLink
                to="/login"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-purple-600 transition-all duration-200"
                @click="closeMenu"
              >
                <LogIn class="h-5 w-5 mr-3" />
                Login
              </RouterLink>
              <RouterLink
                to="/register"
                class="flex items-center px-4 py-3 rounded-lg bg-purple-500 hover:bg-purple-600 transition-all duration-200 font-medium"
                @click="closeMenu"
              >
                <UserPlus class="h-5 w-5 mr-3" />
                Register
              </RouterLink>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl mx-auto w-full p-4 sm:p-6">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-purple-700 to-purple-800 text-white py-6 mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
          <div class="flex items-center space-x-2 mb-4 md:mb-0">
            <ShoppingBag class="h-6 w-6 text-purple-200" />
            <span class="text-lg font-bold">InfoEcommerce</span>
          </div>
          <p class="text-sm text-purple-200">&copy; 2025 InfoEcommerce. Semua hak cipta dilindungi.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { 
  ShoppingBag, Home, Package, LogIn, UserPlus, 
  LogOut, Menu, X
} from 'lucide-vue-next'
import axios from 'axios'

const API = import.meta.env.VITE_API_URL

const router = useRouter()
const isOpen = ref(false)
const toggleMenu = () => { isOpen.value = !isOpen.value }
const closeMenu = () => { isOpen.value = false }

// Ambil user dari localStorage
const user = computed(() => {
  const u = localStorage.getItem('user')
  return u ? JSON.parse(u) : null
})

// Handle Logout
const handleLogout = async () => {
  try {
    const token = localStorage.getItem('token')
    if (token) {
      await axios.post(`${API}/logout`, {}, {
        headers: { Authorization: `Bearer ${token}` }
      })
    }
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    
    await router.push({ 
      name: 'Login', 
      query: { message: 'Anda berhasil logout!' } 
    })
  }
}
</script>