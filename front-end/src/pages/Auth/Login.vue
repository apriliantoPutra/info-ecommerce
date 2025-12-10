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
          <LogIn class="h-12 w-12 text-white" />
        </div>
        <h1 class="text-2xl font-bold text-white">Masuk ke Akun</h1>
        <p class="text-purple-100 mt-2">Lanjutkan pengalaman belanja Anda</p>
      </div>

      <!-- Form -->
      <div class="p-8">
        <form class="space-y-6" @submit.prevent="handleLogin">
          <!-- Notifikasi -->
          <div v-if="message" class="p-3 bg-green-50 text-green-700 rounded-lg text-sm text-center border border-green-100">
            {{ message }}
          </div>
          <div v-if="error" class="p-3 bg-red-50 text-red-700 rounded-lg text-sm text-center border border-red-100">
            {{ error }}
          </div>

          <!-- EMAIL (bukan username) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <Mail class="h-4 w-4 mr-2 text-gray-400" />
                Email
              </div>
            </label>
            <div class="relative">
              <input
                type="email" 
                v-model="email"
                placeholder="Masukkan email Anda"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
              />
            </div>
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
                placeholder="Masukkan password Anda"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
              />
              <button
                type="button"
                @click="togglePassword"
                class="absolute right-3 top-3.5 text-gray-400 hover:text-purple-600 transition-colors"
              >
                <component :is="showPassword ? EyeOff : Eye" class="h-5 w-5" />
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
            <span v-else>Masuk Sekarang</span>
          </button>
        </form>

        <!-- Link ke Register -->
        <div class="mt-8 pt-6 border-t border-gray-100">
          <p class="text-center text-gray-600">
            Belum punya akun?
            <RouterLink 
              :to="{name: 'Register'}" 
              class="text-purple-600 font-semibold hover:text-purple-700 hover:underline transition-colors ml-1"
            >
              Daftar di sini
            </RouterLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { 
  ShoppingBag, 
  LogIn, 
  Mail, 
  Lock, 
  Eye, 
  EyeOff,
  Loader 
} from 'lucide-vue-next'

const router = useRouter()
const API = import.meta.env.VITE_API_URL

// GANTI username menjadi email
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const error = ref('')
const message = ref('')
const loading = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const handleLogin = async () => { 
  error.value = ""
  loading.value = true
  
  try {
    const response = await axios.post(`${API}/login`, {
      email: email.value,
      password: password.value
    })

    const { token, data: user } = response.data

    // Simpan token & user ke localStorage
    localStorage.setItem("token", token)
    localStorage.setItem("user", JSON.stringify(user))

    // Redirect berdasarkan role
    if (user.role === "admin") {
      router.push({ name: "AdminDashboard" })
    } else {
      router.push({ name: "Home" })
    }

  } catch (err) {
    if (err.response && err.response.status === 422) {
      error.value = "Email dan password salah."
      return
    }
    
    error.value = "Terjadi kesalahan pada server. Silakan coba lagi."
    
  } finally {
    loading.value = false
  }
}

</script>