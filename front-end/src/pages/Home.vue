<template>
  <div class="bg-gradient-to-b from-gray-50 to-white min-h-[calc(100vh-200px)]">
    <!-- Hero Section Minimalis -->
    <section class="pt-12 pb-16 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
      <div class="text-center">
        <div class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-purple-100 to-purple-50 rounded-2xl mb-8">
          <div class="p-3 bg-white rounded-xl shadow-sm">
            <ShoppingBag class="h-8 w-8 text-purple-600" />
          </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
        Selamat Datang di
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-purple-800">
          InfoEcommerce
        </span>
      </h1>

      <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
        Ini adalah platform informasi mengenai barang yang dijual di berbagai ecommerce. 
        Pengguna dapat melihat detail produk, termasuk rentang harga dan informasi lainnya, 
        tanpa melakukan pembelian. <strong>InfoEcommerce bukanlah toko online.</strong>
      </p>

        
        <div class="flex flex-wrap justify-center gap-4">
          <RouterLink 
            to="/products" 
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-md hover:shadow-lg"
          >
            <ShoppingCart class="h-5 w-5 mr-2" />
            Lihat Produk
          </RouterLink>
          <RouterLink 
            to="/register" 
            class="inline-flex items-center px-6 py-3 bg-white text-purple-600 font-medium rounded-lg border border-purple-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300"
          >
            <UserPlus class="h-5 w-5 mr-2" />
            Mulai Sekarang
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { 
  ShoppingBag, ShoppingCart, UserPlus, Eye,
  Store, ShieldCheck, Smartphone, Users, Quote
} from 'lucide-vue-next'
</script>