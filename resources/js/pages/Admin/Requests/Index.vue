<template>
  <div class="min-h-screen bg-[#e8e8e8]">
    <AdminNavbar :auth-name="authName" />
    <div class="flex min-h-[calc(100vh-60px)]">
      <AdminSidebar active="Requests" />
      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="mb-[30px]">
          <h1 class="text-[32px] text-[#333] font-semibold">Manage Request</h1>
        </div>
        <div class="mb-[30px]">
          <div class="max-w-[600px] flex items-center bg-white rounded-full px-[20px] py-[5px] shadow-[0_3px_10px_rgba(0,0,0,0.10)]">
            <button type="button" @click="search" class="bg-transparent border-0 text-[20px] mr-[10px] cursor-pointer">🔍</button>
            <input v-model="searchQ" type="text" placeholder="Search requests..."
                   class="flex-1 border-none outline-none py-[10px] text-[16px] bg-transparent"
                   @keyup.enter="search" />
          </div>
        </div>
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
                  <td colspan="7" class="px-[15px] py-[15px] text-center text-gray-500">No requests found.</td>
                </tr>
                <tr v-for="req in requests.data" :key="req.id" class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                  <td class="px-[15px] py-[15px]">{{ req.student }}</td>
                  <td class="px-[15px] py-[15px]">
                    <div class="w-[40px] h-[55px] rounded overflow-hidden bg-gray-100 shadow-sm flex items-center justify-center">
                      <img v-if="req.cover_url" :src="req.cover_url" class="w-full h-full object-cover" alt="Cover" />
                      <span v-else class="text-xs text-gray-400">📚</span>
                    </div>
                  </td>
                  <td class="px-[15px] py-[15px]">{{ req.title }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.author }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.category }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.year_published }}</td>
                  <td class="px-[15px] py-[15px]">
                    <Link v-if="req.has_book" :href="route('admin.requests.show', req.id)"
                       class="inline-flex items-center justify-center text-[20px] w-8 h-8 rounded-full transition-transform hover:scale-110">
                      ⚙️
                    </Link>
                    <span v-else class="text-xs text-gray-400 italic">Book removed</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="requests.last_page > 1" class="mt-4 flex gap-2 flex-wrap">
            <Link v-for="page in requests.last_page" :key="page"
               :href="pageUrl(page)"
               class="px-3 py-1 rounded-md text-sm border"
               :class="page === requests.current_page ? 'bg-[#2e7d32] text-white border-[#2e7d32]' : 'bg-white text-gray-700 hover:bg-gray-100'">
              {{ page }}
            </Link>
          </div>
        </div>
      </main>
    </div>
    <AdminFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminNavbar  from '@/Pages/Admin/Partials/Navbar.vue'
import AdminSidebar from '@/Pages/Admin/Partials/Sidebar.vue'
import AdminFooter  from '@/Pages/Admin/Partials/FooterLogos.vue'

const props = defineProps({
  requests: { type: Object, default: () => ({ data: [], last_page: 1, current_page: 1 }) },
  q:        { type: String, default: '' },
  authName: { type: String, default: 'Admin' },
})

const searchQ = ref(props.q)
const route   = window.route

const search = () => {
  router.get(route('admin.requests.index'), { q: searchQ.value }, { preserveState: true, replace: true })
}

const pageUrl = (page) => {
  const params = new URLSearchParams()
  params.set('page', page)
  if (searchQ.value) params.set('q', searchQ.value)
  return `${route('admin.requests.index')}?${params.toString()}`
}
</script>