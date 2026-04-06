<template>
  <nav class="w-full bg-[#c8e6c9] shadow-md">
    <div class="max-w-6xl mx-auto px-6">
      <div class="flex items-center justify-between h-16">
        <div class="font-semibold text-lg tracking-wide text-[#1b5e20]">SNSU LIBRARY E-REQUEST</div>
        <div class="flex items-center gap-6 text-sm font-semibold text-gray-800">
          <Link :href="route('student.home')"
             class="px-2 py-1 border-b-2"
             :class="active === 'Home' ? 'border-[#1b5e20] text-[#1b5e20]' : 'border-transparent hover:border-[#81c784]'">
            Home
          </Link>
          <Link :href="route('student.catalog')"
             class="px-2 py-1 border-b-2"
             :class="active === 'Catalog' ? 'border-[#1b5e20] text-[#1b5e20]' : 'border-transparent hover:border-[#81c784]'">
            Catalog
          </Link>
          <Link :href="route('student.requests.index')"
             class="px-2 py-1 border-b-2"
             :class="active === 'Requests' ? 'border-[#1b5e20] text-[#1b5e20]' : 'border-transparent hover:border-[#81c784]'">
            My Request
          </Link>
          <div class="relative">
            <button type="button" @click.stop="menuOpen = !menuOpen"
              class="flex items-center gap-1 px-3 py-1 rounded-md hover:bg-white/60">
              <span>{{ authName }}</span><span class="text-xs">▼</span>
            </button>
            <div v-if="menuOpen" @click.stop
              class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border text-sm z-50">
              <Link :href="route('student.profile')" class="flex items-center px-3 py-2 hover:bg-gray-100">
                <span class="mr-2">👤</span> Profile
              </Link>
              <button type="button" @click="logout"
                class="w-full text-left flex items-center px-3 py-2 text-red-600 hover:bg-gray-100">
                <span class="mr-2">🚪</span> Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

defineProps({
  authName: { type: String, default: 'Student' },
  active:   { type: String, default: '' },
})

const menuOpen = ref(false)
const route = window.route
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })
</script>