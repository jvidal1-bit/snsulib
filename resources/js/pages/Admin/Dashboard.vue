<template>
  <div class="min-h-screen bg-[#e8e8e8]">

    <!-- Navbar -->
    <nav
      class="bg-gradient-to-r from-[#a5d6a7] to-[#c8e6c9]
             px-[30px] py-[15px]
             flex items-center justify-between
             shadow-[0_2px_10px_rgba(0,0,0,0.10)]
             sticky top-0 z-[1000]"
    >
      <div class="text-[20px] font-black tracking-[0.5px]">
        SNSU LIBRARY E-REQUEST
      </div>

      <div class="relative">
        <button
          @click.stop="menuOpen = !menuOpen"
          class="flex items-center gap-[8px] px-[16px] py-[8px]
                 rounded-[8px] text-[16px] font-semibold text-[#333]
                 bg-transparent hover:bg-white/30 transition-colors"
        >
          <span>{{ authName }}</span>
          <span class="text-[12px]">▼</span>
        </button>

        <div
          v-if="menuOpen"
          @click.stop
          class="absolute right-0 mt-[10px] bg-white rounded-[10px]
                 shadow-[0_5px_20px_rgba(0,0,0,0.15)]
                 min-w-[180px] overflow-hidden z-[1001]"
        >
          <button
            type="button"
            @click="logout"
            class="w-full flex items-center gap-[10px]
                   px-[20px] py-[12px]
                   text-[#333] font-medium text-[14px]
                   hover:bg-[#f5f5f5] transition-colors"
          >
            <span class="text-[16px]">🚪</span>
            <span>Logout</span>
          </button>
        </div>
      </div>
    </nav>

    <!-- Layout: sidebar + main -->
    <div class="flex min-h-[calc(100vh-60px)]">

      <!-- Sidebar -->
      <aside
        class="w-[200px] bg-gradient-to-b from-[#4caf50] to-[#388e3c]
               py-[20px] shadow-[2px_0_10px_rgba(0,0,0,0.10)]"
      >
        <nav class="flex flex-col gap-[8px] px-[15px] text-[14px] font-semibold">
          <a
            v-for="item in navItems"
            :key="item.label"
            :href="route(item.route)"
            class="px-[20px] py-[12px] rounded-[10px] transition-all"
            :class="item.active
              ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
              : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]'"
          >
            {{ item.label }}
          </a>
        </nav>
      </aside>

      <!-- Main content -->
      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="mb-[30px]">
          <h1 class="text-[32px] text-[#333] font-semibold">Dashboard</h1>
        </div>

        <!-- Status cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[40px]">
          <a
            v-for="card in statCards"
            :key="card.label"
            :href="route(card.route)"
            class="rounded-[20px] p-[30px] text-center no-underline cursor-pointer
                   shadow-[0_8px_20px_rgba(0,0,0,0.15)] transition-transform duration-300
                   hover:-translate-y-[5px] hover:shadow-[0_12px_30px_rgba(0,0,0,0.20)]"
            :class="[card.bg, card.textColor]"
          >
            <h3 class="text-[20px] font-bold mb-[15px]">{{ card.label }}</h3>
            <p class="text-[56px] font-bold">{{ card.value }}</p>
          </a>
        </div>

        <!-- Recent Requests Table -->
        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
          <h2 class="text-[24px] mb-[20px] text-[#333]">Resent Request</h2>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse text-[14px]">
              <thead>
                <tr class="bg-[#e0e0e0]">
                  <th class="px-[15px] py-[15px] text-left font-semibold">Student</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Title</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Author</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Category</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Year-Pub</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="recentRequests.length === 0">
                  <td colspan="6" class="px-[15px] py-[15px] text-center text-gray-500">
                    No recent requests.
                  </td>
                </tr>
                <tr
                  v-for="req in recentRequests"
                  :key="req.id"
                  class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]"
                >
                  <td class="px-[15px] py-[15px]">{{ req.student }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.title }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.author }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.category }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.year_published }}</td>
                  <td class="px-[15px] py-[15px]">
                    <a
                      :href="route('admin.requests.show', req.id)"
                      class="inline-flex items-center justify-center text-[20px]
                             w-8 h-8 rounded-full transition-transform hover:scale-110"
                    >⚙️</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <!-- Footer -->
    <div class="flex justify-end items-center gap-[10px] px-[30px] py-[10px]">
      <span class="text-[12px] text-gray-500 mr-auto">For Nation's Greater High</span>
      <img :src="'/assets/images/snsu-logo.png'" class="h-[40px] w-[40px] rounded-full" />
      <img :src="'/assets/images/library-logo.png'" class="h-[40px] w-[40px] rounded-full" />
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  totalRequests:      { type: Number, default: 0 },
  pendingRequests:    { type: Number, default: 0 },
  processingRequests: { type: Number, default: 0 },
  completedRequests:  { type: Number, default: 0 },
  recentRequests:     { type: Array,  default: () => [] },
  authName:           { type: String, default: 'Admin' },
})

const menuOpen = ref(false)
const route = window.route

const logout = () => router.post(route('logout'))

document.addEventListener('click', () => { menuOpen.value = false })

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard',      active: true  },
  { label: 'Requests',  route: 'admin.requests.index', active: false },
  { label: 'Books',     route: 'admin.books.index',    active: false },
  { label: 'Users',     route: 'admin.users.index',    active: false },
  { label: 'Settings',  route: 'admin.settings.index', active: false },
]

const statCards = [
  { label: 'Pending:',       value: props.pendingRequests,    route: 'admin.requests.pending',    bg: 'bg-[#81c784]', textColor: 'text-white' },
  { label: 'Total Request:', value: props.totalRequests,      route: 'admin.requests.index',      bg: 'bg-[#a5d6a7]', textColor: 'text-black' },
  { label: 'Processing:',    value: props.processingRequests, route: 'admin.requests.processing', bg: 'bg-[#00897b]', textColor: 'text-white' },
  { label: 'Completed:',     value: props.completedRequests,  route: 'admin.requests.completed',  bg: 'bg-[#66bb6a]', textColor: 'text-white' },
]
</script>