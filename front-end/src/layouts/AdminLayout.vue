<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-white flex flex-col">
    <!-- Navbar Admin -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg sticky top-0 z-50">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Left: Logo & Brand -->
          <div class="flex items-center">
            <RouterLink to="/" class="flex items-center space-x-3 group">
              <div class="bg-white p-2 rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                <ShoppingBag class="h-6 w-6 text-purple-600" />
              </div>
              <div class="hidden md:block">
                <span class="text-xl font-bold">InfoEcommerce</span>
                <span class="block text-xs text-purple-200 font-medium">Admin Panel</span>
              </div>
            </RouterLink>
          </div>

          <!-- Right: User Profile & Mobile Menu -->
          <div class="flex items-center space-x-4">
            <!-- User Profile -->
            <div class="hidden md:flex items-center space-x-3">
              <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-purple-300 rounded-full flex items-center justify-center">
                <User class="h-4 w-4 text-white" />
              </div>
              <div>
                <p class="text-sm font-medium">{{ user?.username || 'Administrator' }}</p>
                <p class="text-xs text-purple-200">Super Admin</p>
              </div>
            </div>

            <!-- Desktop Logout -->
            <button
              @click="handleLogout"
              class="hidden md:flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200"
            >
              <LogOut class="h-4 w-4 mr-2" />
              Logout
            </button>

            <!-- Mobile Menu Button -->
            <button
              @click="toggleSidebar"
              class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors"
            >
              <Menu class="h-6 w-6" />
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Layout Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Desktop Sidebar -->
      <aside class="hidden lg:block w-64 bg-white border-r border-gray-200 flex-shrink-0 overflow-y-auto">
        <div class="p-6 sticky top-0">
          <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <LayoutDashboard class="h-5 w-5 mr-2 text-purple-600" />
            Admin Menu
          </h2>
          
          <nav class="space-y-1">
            <RouterLink
              v-for="item in menuItems"
              :key="item.name"
              :to="{name: item.to}"
              @click="setActiveMenu(item.name)"
              class="flex items-center px-4 py-3 rounded-lg transition-all duration-200"
              :class="[
                activeMenu === item.name
                  ? 'bg-gradient-to-r from-purple-50 to-purple-100 text-purple-700 border-l-4 border-purple-600'
                  : 'text-gray-700 hover:bg-gray-50 hover:text-purple-600'
              ]"
            >
              <component :is="item.icon" class="h-5 w-5 mr-3" />
              {{ item.label }}
              <ChevronRight
                v-if="activeMenu === item.name"
                class="h-4 w-4 ml-auto text-purple-500"
              />
            </RouterLink>
          </nav>
        </div>
      </aside>

      <!-- Mobile Sidebar Overlay -->
      <div
        v-if="showSidebar"
        @click="toggleSidebar"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
      ></div>

      <!-- Mobile Sidebar -->
      <aside
        :class="[
          'fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-40 flex flex-col transform transition-transform duration-300 lg:hidden',
          showSidebar ? 'translate-x-0' : '-translate-x-full'
        ]"
      >
        <div class="p-6 flex-1 overflow-y-auto">
          <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-2">
              <div class="bg-gradient-to-r from-purple-600 to-purple-500 p-2 rounded-lg">
                <ShoppingBag class="h-6 w-6 text-white" />
              </div>
              <div>
                <h2 class="font-bold text-gray-900">Admin Panel</h2>
                <p class="text-xs text-gray-500">InfoEcommerce</p>
              </div>
            </div>
            <button
              @click="toggleSidebar"
              class="p-2 hover:bg-gray-100 rounded-lg"
            >
              <X class="h-5 w-5 text-gray-500" />
            </button>
          </div>

          <nav class="space-y-1 mb-8">
            <RouterLink
              v-for="item in menuItems"
              :key="item.name"
              :to="{name: item.to}"
              @click="toggleSidebar"
              class="flex items-center px-4 py-3 rounded-lg transition-colors"
              :class="[
                activeMenu === item.name
                  ? 'bg-purple-50 text-purple-700'
                  : 'text-gray-700 hover:bg-gray-50'
              ]"
            >
              <component :is="item.icon" class="h-5 w-5 mr-3" />
              {{ item.label }}
            </RouterLink>
          </nav>

          <!-- User Info Mobile -->
          <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-400 rounded-full flex items-center justify-center">
                <User class="h-6 w-6 text-white" />
              </div>
              <div>
                <p class="font-medium text-gray-900">{{ user?.username || 'Administrator' }}</p>
                <p class="text-sm text-gray-600">Super Admin</p>
              </div>
            </div>
            <button
              @click="handleLogout"
              class="w-full mt-4 flex items-center justify-center px-4 py-2 bg-white text-purple-600 rounded-lg border border-purple-200 hover:bg-purple-50 transition-colors font-medium"
            >
              <LogOut class="h-4 w-4 mr-2" />
              Logout
            </button>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="max-w-full">
          <RouterView />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import {
  ShoppingBag,
  User,
  LogOut,
  Menu,
  X,
  LayoutDashboard,
  Users,
  Tag,
  Package,
  ChevronRight
} from 'lucide-vue-next'
const API= import.meta.env.VITE_API_URL

const showSidebar = ref(false)
const activeMenu = ref('dashboard')
const user = ref(null)

const menuItems = [
  { name: 'dashboard', label: 'Dashboard', icon: LayoutDashboard, to: 'AdminDashboard' },
  { name: 'user', label: 'Kelola Pengguna', icon: Users, to: 'AdminUsers' },
  { name: 'category', label: 'Kategori Produk', icon: Tag, to: 'AdminCategories' },
  { name: 'product', label: 'Kelola Produk', icon: Package, to: 'AdminProducts' },
]

const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value
}

const setActiveMenu = (menuName) => {
  activeMenu.value = menuName
}

onMounted(()=> {
  const savedUser = localStorage.getItem("user")
  if (savedUser) {
    user.value = JSON.parse(savedUser)
  } else {
    // Kalau tidak ada user, kembalikan ke login
    router.push({
      name: "Login",
      query: { message: "Silakan login terlebih dahulu untuk mengakses dashboard." },
    })
  }
})

const handleLogout = async () => {
    try {
        const token = localStorage.getItem("token")
        await axios.post(`${API}/logout`, {}, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
    } catch (error) {
        console.error("Logout error:", error)
    } finally {
        localStorage.removeItem("token")
        localStorage.removeItem("user")

        router.push({ 
            name: "Login", 
            query: { message: "Anda berhasil logout!" } 
        })
    }
}

</script>