<!-- Dashboard.vue -->
<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-2">Selamat datang kembali, Anda dapat mengelola seluruh sistem dari sini</p>
      </div>
      <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <div class="px-4 py-2 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
          <p class="text-sm text-gray-600">Role</p>
          <p class="font-semibold text-purple-700">Super Administrator</p>
        </div>
      </div>
    </div>

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl p-6 md:p-8 text-white">
      <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div class="mb-6 md:mb-0">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
              <LayoutDashboard class="h-6 w-6" />
            </div>
            <h2 class="text-xl font-bold">Selamat Datang di Panel Admin</h2>
          </div>
          <p class="text-purple-100 max-w-2xl">
            Anda memiliki akses penuh untuk mengelola pengguna, produk, kategori, dan seluruh konten pada platform InfoEcommerce.
            Gunakan menu di samping untuk navigasi.
          </p>
        </div>
        <div class="flex-shrink-0">
          <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20">
            <div class="text-center">
              <p class="text-sm text-purple-200">Status Sistem</p>
              <div class="flex items-center justify-center mt-2">
                <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                <span class="font-bold">Online</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import {
  LayoutDashboard,
  Users,
  Package,
  Tag,
  ShoppingCart,
  User,
  Zap,
  Plus,
  Settings,
  FileText,
  BarChart3
} from 'lucide-vue-next'

const user = ref({ username: 'Admin Web' })
</script>