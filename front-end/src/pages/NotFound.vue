<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-purple-50 text-center p-6">
    <div class="bg-white shadow-xl rounded-2xl p-10 max-w-md w-full border border-purple-200">
      <Ghost class="w-24 h-24 text-purple-600 mx-auto mb-6" />
      <h1 class="text-4xl font-bold text-purple-700 mb-3">404</h1>
      <p class="text-lg text-purple-500 mb-8">Halaman yang Anda cari tidak ditemukan.</p>
      <RouterLink
        to="/"
        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-medium px-5 py-3 rounded-xl shadow-md transition-all"
      >
        <Home class="w-5 h-5" />
        Kembali ke Beranda
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { Ghost, Home } from "lucide-vue-next";
</script>

