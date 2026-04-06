<template>
  <div class="min-h-screen bg-[#e8e8e8]">
    <AdminNavbar :auth-name="authName" />
    <div class="flex min-h-[calc(100vh-60px)]">
      <AdminSidebar active="Requests" />
      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="mb-[30px]"><h1 class="text-[32px] text-[#333] font-semibold">Dashboard</h1></div>
        <div class="rounded-[30px] p-[40px] min-h-[500px] shadow-[0_10px_30px_rgba(0,0,0,0.15)] bg-gradient-to-br from-[#66bb6a] to-[#4caf50]">
          <h2 class="text-[32px] font-bold mb-[25px] text-white">Completed:</h2>
          <div class="bg-white rounded-[20px] p-[25px] shadow-[0_5px_15px_rgba(0,0,0,0.10)]">
            <div class="overflow-x-auto">
              <table class="w-full border-collapse text-[14px]">
                <thead>
                  <tr class="bg-[#e0e0e0]">
                    <th class="px-[15px] py-[15px] text-left font-semibold">ISBN</th>
                    <th class="px-[15px] py-[15px] text-left font-semibold">Title</th>
                    <th class="px-[15px] py-[15px] text-left font-semibold">Chapter</th>
                    <th class="px-[15px] py-[15px] text-left font-semibold">Status</th>
                    <th class="px-[15px] py-[15px] text-left font-semibold">Date</th>
                    <th class="px-[15px] py-[15px] text-left font-semibold">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="requests.data.length === 0">
                    <td colspan="6" class="px-[15px] py-[15px] text-center text-gray-500">No requests found.</td>
                  </tr>
                  <tr v-for="req in requests.data" :key="req.id"
                      class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                    <td class="px-[15px] py-[15px]">{{ req.isbn }}</td>
                    <td class="px-[15px] py-[15px]">{{ req.title }}</td>
                    <td class="px-[15px] py-[15px]">{{ req.chapter }}</td>
                    <td class="px-[15px] py-[15px] capitalize">{{ req.status }}</td>
                    <td class="px-[15px] py-[15px]">{{ req.date }}</td>
                    <td class="px-[15px] py-[15px]">
                      <Link :href="route('admin.requests.show', req.id)"
                         class="inline-flex items-center justify-center text-[20px] w-8 h-8 rounded-full transition-transform hover:scale-110">
                        ⚙️
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-if="requests.last_page > 1" class="mt-[20px] flex gap-2 flex-wrap">
              <Link v-for="page in requests.last_page" :key="page"
                 :href="`${route('admin.requests.completed')}?page=${page}`"
                 class="px-3 py-1 rounded-md text-sm border"
                 :class="page === requests.current_page ? 'bg-[#2e7d32] text-white border-[#2e7d32]' : 'bg-white text-gray-700 hover:bg-gray-100'">
                {{ page }}
              </Link>
            </div>
          </div>
        </div>
      </main>
    </div>
    <AdminFooter />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminNavbar  from '@/Pages/Admin/Partials/Navbar.vue'
import AdminSidebar from '@/Pages/Admin/Partials/Sidebar.vue'
import AdminFooter  from '@/Pages/Admin/Partials/FooterLogos.vue'

defineProps({
  requests: { type: Object, default: () => ({ data: [], last_page: 1, current_page: 1 }) },
  authName: { type: String, default: 'Admin' },
})

const route = window.route
</script>