<template>
  <div class="min-h-screen bg-[#e8e8e8]">
    <AdminNavbar :auth-name="authName" />

    <div class="flex min-h-[calc(100vh-60px)]">
      <AdminSidebar active="Dashboard" />

      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="mb-[30px]">
          <h1 class="text-[32px] text-[#333] font-semibold">Dashboard</h1>
        </div>

        <!-- Status cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[40px]">
          <Link v-for="card in statCards" :key="card.label" :href="route(card.route)"
            class="rounded-[20px] p-[30px] text-center no-underline cursor-pointer
                   shadow-[0_8px_20px_rgba(0,0,0,0.15)] transition-transform duration-300
                   hover:-translate-y-[5px] hover:shadow-[0_12px_30px_rgba(0,0,0,0.20)]"
            :class="[card.bg, card.textColor]">
            <h3 class="text-[20px] font-bold mb-[15px]">{{ card.label }}</h3>
            <p class="text-[56px] font-bold">{{ card.value }}</p>
          </Link>
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
                  <td colspan="6" class="px-[15px] py-[15px] text-center text-gray-500">No recent requests.</td>
                </tr>
                <tr v-for="req in recentRequests" :key="req.id"
                    class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                  <td class="px-[15px] py-[15px]">{{ req.student }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.title }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.author }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.category }}</td>
                  <td class="px-[15px] py-[15px]">{{ req.year_published }}</td>
                  <td class="px-[15px] py-[15px]">
                    <Link :href="route('admin.requests.show', req.id)"
                       class="inline-flex items-center justify-center text-[20px]
                              w-8 h-8 rounded-full transition-transform hover:scale-110">⚙️</Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <AdminFooter />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminNavbar  from '@/pages/Admin/Partials/Navbar.vue'
import AdminSidebar from '@/pages/Admin/Partials/Sidebar.vue'
import AdminFooter  from '@/pages/Admin/Partials/FooterLogos.vue'

const props = defineProps({
  totalRequests:      { type: Number, default: 0 },
  pendingRequests:    { type: Number, default: 0 },
  processingRequests: { type: Number, default: 0 },
  completedRequests:  { type: Number, default: 0 },
  recentRequests:     { type: Array,  default: () => [] },
  authName:           { type: String, default: 'Admin' },
})

const route = window.route

const statCards = [
  { label: 'Pending:',       value: props.pendingRequests,    route: 'admin.requests.pending',    bg: 'bg-[#81c784]', textColor: 'text-white' },
  { label: 'Total Request:', value: props.totalRequests,      route: 'admin.requests.index',      bg: 'bg-[#a5d6a7]', textColor: 'text-black' },
  { label: 'Processing:',    value: props.processingRequests, route: 'admin.requests.processing', bg: 'bg-[#00897b]', textColor: 'text-white' },
  { label: 'Completed:',     value: props.completedRequests,  route: 'admin.requests.completed',  bg: 'bg-[#66bb6a]', textColor: 'text-white' },
]
</script>