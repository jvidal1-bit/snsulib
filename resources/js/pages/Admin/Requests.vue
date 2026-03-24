<template>
  <div class="min-h-screen bg-[#e8e8e8]">

    <!-- Navbar -->
    <nav
      class="bg-gradient-to-r from-[#a5d6a7] to-[#c8e6c9]
             px-[30px] py-[15px] flex items-center justify-between
             shadow-[0_2px_10px_rgba(0,0,0,0.10)] sticky top-0 z-[1000]"
    >
      <div class="text-[20px] font-black tracking-[0.5px]">SNSU LIBRARY E-REQUEST</div>
      <div class="relative">
        <button
          @click.stop="menuOpen = !menuOpen"
          class="flex items-center gap-[8px] px-[16px] py-[8px] rounded-[8px]
                 text-[16px] font-semibold text-[#333] bg-transparent
                 hover:bg-white/30 transition-colors"
        >
          <span>{{ authName }}</span>
          <span class="text-[12px]">▼</span>
        </button>
        <div
          v-if="menuOpen" @click.stop
          class="absolute right-0 mt-[10px] bg-white rounded-[10px]
                 shadow-[0_5px_20px_rgba(0,0,0,0.15)] min-w-[180px] overflow-hidden z-[1001]"
        >
          <button type="button" @click="logout"
            class="w-full flex items-center gap-[10px] px-[20px] py-[12px]
                   text-[#333] font-medium text-[14px] hover:bg-[#f5f5f5] transition-colors">
            <span class="text-[16px]">🚪</span><span>Logout</span>
          </button>
        </div>
      </div>
    </nav>

    <div class="flex min-h-[calc(100vh-60px)]">

      <!-- Sidebar -->
      <aside class="w-[200px] bg-gradient-to-b from-[#4caf50] to-[#388e3c]
                    py-[20px] shadow-[2px_0_10px_rgba(0,0,0,0.10)]">
        <nav class="flex flex-col gap-[8px] px-[15px] text-[14px] font-semibold">
          <a v-for="item in navItems" :key="item.label" :href="route(item.route)"
             class="px-[20px] py-[12px] rounded-[10px] transition-all"
             :class="item.active
               ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
               : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]'">
            {{ item.label }}
          </a>
        </nav>
      </aside>

      <!-- Main -->
      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="mb-[30px]">
          <h1 class="text-[32px] text-[#333] font-semibold">Manage Request</h1>
        </div>

        <!-- Search -->
        <div class="mb-[30px]">
          <div class="max-w-[600px] relative flex items-center bg-white rounded-full
                      px-[20px] py-[5px] shadow-[0_3px_10px_rgba(0,0,0,0.10)]">
            <form method="GET" :action="route('admin.requests.index')"
                  class="flex items-center w-full">
              <button type="submit"
                      class="bg-transparent border-0 text-[20px] mr-[10px] cursor-pointer">🔍</button>
              <input type="text" name="q" :value="q"
                     placeholder="Search requests..."
                     class="flex-1 border-none outline-none py-[10px] text-[16px] bg-transparent" />
            </form>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
          <h2 class="text-[24px] mb-[20px] text-[#333]">Manage Request</h2>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse text-[14px]">
              <thead>
                <tr class="bg-[#e0e0e0]">
                  <th class="px-[15px] py-[15px] text-left font-semibold">Student</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Cover</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Title</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Author</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Category</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Year-Pub</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="requests.data.length === 0">
                  <td colspan="7" class="px-[15px] py-[15px] text-center text-gray-500">
                    No requests found.
                  </td>
                </tr>
                <tr v-for="req in requests.data" :key="req.id"
                    class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                  <td class="px-[15px] py-[15px]">{{ req.student }}</td>
                  <td class="px-[15px] py-[15px]">
                    <div class="w-[40px] h-[55px] rounded overflow-hidden bg-gray-100 shadow-sm
                                flex items-center justify-center">
                      <img v-if="req.cover_url" :src="req.cover_url"
                           class="w-full h-full object-cover" alt="Cover" />
                      <span v-else class="text-xs text-gray-400">📚</span>
                    </div>
                  </td>
                  <td class="px-[15px] py-[15px]">{{ req.title }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.author }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.category }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.year_published }}</td>
                  <td class="px-[15px] py-[15px]">
                    <a v-if="req.has_book" :href="route('admin.requests.show', req.id)"
                       class="inline-flex items-center justify-center text-[20px]
                              w-8 h-8 rounded-full transition-transform hover:scale-110">⚙️</a>
                    <span v-else class="text-xs text-gray-400 italic">Book removed</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="requests.last_page > 1" class="mt-4 flex gap-2 flex-wrap">
            <a v-for="page in requests.last_page" :key="page"
               :href="`${route('admin.requests.index')}?page=${page}${q ? '&q=' + q : ''}`"
               class="px-3 py-1 rounded-md text-sm border"
               :class="page === requests.current_page
                 ? 'bg-[#2e7d32] text-white border-[#2e7d32]'
                 : 'bg-white text-gray-700 hover:bg-gray-100'">
              {{ page }}
            </a>
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
  requests: { type: Object, default: () => ({ data: [], last_page: 1, current_page: 1 }) },
  q:        { type: String, default: '' },
  authName: { type: String, default: 'Admin' },
})

const menuOpen = ref(false)
const route = window.route
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard',      active: false },
  { label: 'Requests',  route: 'admin.requests.index', active: true  },
  { label: 'Books',     route: 'admin.books.index',    active: false },
  { label: 'Users',     route: 'admin.users.index',    active: false },
  { label: 'Settings',  route: 'admin.settings.index', active: false },
]
</script>