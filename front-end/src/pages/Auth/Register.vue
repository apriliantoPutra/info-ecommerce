<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 to-white flex items-center justify-center p-4">
    <!-- Logo/Back to Home -->
    <RouterLink to="/" class="absolute top-6 left-6 flex items-center space-x-2 text-purple-600 hover:text-purple-700 transition-colors">
      <ShoppingBag class="h-6 w-6" />
      <span class="font-bold text-lg">InfoEcommerce</span>
    </RouterLink>

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
      <!-- Header dengan gradient -->
      <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6 text-center">
        <div class="flex justify-center mb-4">
          <UserPlus class="h-12 w-12 text-white" />
        </div>
        <h1 class="text-2xl font-bold text-white">Buat Akun Baru</h1>
        <p class="text-purple-100 mt-2">Mulai pengalaman belanja Anda bersama kami</p>
      </div>

      <!-- Form -->
      <div class="p-8">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <!-- Error Message -->
          <div v-if="error" class="p-3 bg-red-50 text-red-700 rounded-lg text-sm text-center border border-red-100">
            {{ error }}
          </div>

          <!-- Username -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <User class="h-4 w-4 mr-2 text-gray-400" />
                Username
              </div>
            </label>
            <input
              type="text"
              v-model="username"
              placeholder="Pilih username Anda"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <Mail class="h-4 w-4 mr-2 text-gray-400" />
                Email
              </div>
            </label>
            <input
              type="email"
              v-model="email"
              placeholder="Masukkan email Anda"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <Lock class="h-4 w-4 mr-2 text-gray-400" />
                Password
              </div>
            </label>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="password"
                placeholder="Buat password yang kuat"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
              />
              <button
                type="button"
                @click="togglePassword"
                class="absolute right-3 top-3.5 text-gray-400 hover:text-purple-600 transition-colors"
              >
                <component :is="showPassword ? EyeOff : Eye" class="h-5 w-5" />
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">Minimal 6 karakter</p>
          </div>

          <!-- Konfirmasi Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <LockKeyhole class="h-4 w-4 mr-2 text-gray-400" />
                Konfirmasi Password
              </div>
            </label>
            <div class="relative">
              <input
                :type="showConfirmPassword ? 'text' : 'password'"
                v-model="konfirmasiPassword"
                placeholder="Ulangi password Anda"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
              />
              <button
                type="button"
                @click="toggleConfirmPassword"
                class="absolute right-3 top-3.5 text-gray-400 hover:text-purple-600 transition-colors"
              >
                <component :is="showConfirmPassword ? EyeOff : Eye" class="h-5 w-5" />
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <Loader v-if="loading" class="h-5 w-5 inline mr-2 animate-spin" />
            <span v-else>Daftar Sekarang</span>
          </button>
        </form>

        <!-- Link ke Login -->
        <div class="mt-8 pt-6 border-t border-gray-100">
          <p class="text-center text-gray-600">
            Sudah punya akun?
            <RouterLink 
              :to="{name: 'Login'}" 
              class="text-purple-600 font-semibold hover:text-purple-700 hover:underline transition-colors ml-1"
            >
              Masuk di sini
            </RouterLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios"
import { ref } from "vue"
import { useRouter } from "vue-router"
import { 
  ShoppingBag, 
  UserPlus, 
  User, 
  Mail, 
  Lock, 
  LockKeyhole, 
  Eye, 
  EyeOff,
  Loader
} from 'lucide-vue-next'

const API = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'
const router = useRouter()

// Form Data
const username = ref('')
const email = ref('')
const password = ref('')
const konfirmasiPassword = ref('')

// UI State
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

// Toggle password visibility
const togglePassword = () => { showPassword.value = !showPassword.value }
const toggleConfirmPassword = () => { showConfirmPassword.value = !showConfirmPassword.value }

// Handle Register
const handleRegister = async () => {
  error.value = ''
  
  // Validasi client-side
  if (password.value !== konfirmasiPassword.value) {
    error.value = 'Password dan konfirmasi password tidak cocok'
    return
  }

  if (password.value.length < 6) {
    error.value = 'Password minimal 6 karakter'
    return
  }

  loading.value = true

  try {
    const response = await axios.post(`${API}/register`, {
      username: username.value,
      email: email.value,
      password: password.value,
      password_confirmation: konfirmasiPassword.value
    })

    if (response.data.success) {
      // Simpan token dan user ke localStorage
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.data))

      // Redirect ke Home
      router.push({ name: 'Home' })
    } else {
      error.value = response.data.message || 'Terjadi kesalahan saat pendaftaran'
    }
  } catch (err) {
    console.error('Register error:', err)
    
    if (err.response) {
      if (err.response.status === 422) {
        const errors = err.response.data.errors
        if (errors?.email) error.value = errors.email[0]
        else if (errors?.username) error.value = errors.username[0]
        else if (errors?.password) error.value = errors.password[0]
        else error.value = 'Data yang dimasukkan tidak valid'
      } else if (err.response.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = 'Terjadi kesalahan pada server'
      }
    } else {
      error.value = 'Tidak dapat terhubung ke server'
    }
  } finally {
    loading.value = false
  }
}
</script>
