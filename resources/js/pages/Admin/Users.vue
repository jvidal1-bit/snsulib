<template>
  <div class="min-h-screen bg-[#e8e8e8]">
    <AdminNavbar :auth-name="authName" />

    <div class="flex min-h-[calc(100vh-60px)]">
      <AdminSidebar active="Users" />

      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">

        <div v-if="$page.props.flash && $page.props.flash.success"
             class="mb-4 rounded-[10px] px-4 py-3 bg-green-100 text-green-700 text-sm">
          {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash && $page.props.flash.error"
             class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
          {{ $page.props.flash.error }}
        </div>

        <!-- Search + Role Filter -->
        <div class="mb-[30px]">
          <div class="max-w-[800px] bg-white rounded-full px-[20px] py-[5px]
                      shadow-[0_3px_10px_rgba(0,0,0,0.10)] flex items-center gap-3">
            <button type="button" @click="search"
                    class="bg-transparent border-0 text-[20px] cursor-pointer">🔍</button>
            <input v-model="searchQ" type="text" placeholder="Search users..."
                   class="flex-1 border-none outline-none py-[10px] text-[16px] bg-transparent"
                   @keyup.enter="search" />
            <label class="text-[14px] text-gray-700 hidden md:block">Role:</label>
            <select v-model="searchRole" @change="search"
                    class="px-3 py-2 text-[14px] border border-gray-300 rounded-full outline-none bg-white">
              <option value="">All roles</option>
              <option value="admin">Admin</option>
              <option value="student">Student</option>
            </select>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
          <h2 class="text-[24px] mb-[20px] text-[#333]">Admin User</h2>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse text-[14px]">
              <thead>
                <tr class="bg-[#e0e0e0]">
                  <th class="px-[15px] py-[15px] text-left font-semibold">ID</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Name</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Email</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Role</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Course</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Year Level</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Status</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="users.data.length === 0">
                  <td colspan="8" class="px-[15px] py-[15px] text-center text-gray-500">No users found.</td>
                </tr>
                <tr v-for="user in users.data" :key="user.id"
                    class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                  <td class="px-[15px] py-[15px]">{{ user.id }}</td>
                  <td class="px-[15px] py-[15px]">
                    <span>{{ user.display_name }}</span>
                    <div v-if="user.username_note" class="text-xs text-gray-500">{{ user.username_note }}</div>
                  </td>
                  <td class="px-[15px] py-[15px]">{{ user.email }}</td>
                  <td class="px-[15px] py-[15px] capitalize">{{ user.role }}</td>
                  <td class="px-[15px] py-[15px]">{{ user.course }}</td>
                  <td class="px-[15px] py-[15px]">{{ user.year_level }}</td>
                  <td class="px-[15px] py-[15px] capitalize">{{ user.status }}</td>
                  <td class="px-[15px] py-[15px] flex items-center gap-[8px]">
                    <button type="button" @click="openView(user)"
                      class="bg-transparent border-0 text-[20px] w-8 h-8 rounded-full cursor-pointer
                             transition-transform hover:scale-110">👁️</button>
                    <Link :href="route('admin.users.edit', user.id)"
                       class="inline-flex items-center justify-center w-8 h-8 text-[16px]
                              rounded-full bg-[#2e7d32] text-white hover:bg-[#1b5e20]
                              transition-transform hover:scale-110">✏️</Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- Pagination -->
          <div v-if="users.last_page > 1" class="mt-[20px] flex gap-2 flex-wrap">
            <Link v-for="page in users.last_page" :key="page"
               :href="pageUrl(page)"
               class="px-3 py-1 rounded-md text-sm border"
               :class="page === users.current_page
                 ? 'bg-[#2e7d32] text-white border-[#2e7d32]'
                 : 'bg-white text-gray-700 hover:bg-gray-100'">
              {{ page }}
            </Link>
          </div>
        </div>
      </main>
    </div>

    <AdminFooter />

    <!-- User View Modal -->
    <div v-if="userViewOpen" @click.self="userViewOpen = false"
         class="fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-[20px] overflow-y-auto">
      <div @click.stop class="bg-white rounded-[20px] p-[40px] w-full max-w-[600px]
                              max-h-[90vh] overflow-y-auto relative shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
        <button type="button" @click="userViewOpen = false"
          class="absolute top-[20px] right-[20px] bg-transparent border-0 text-[32px] text-[#666]
                 w-[40px] h-[40px] flex items-center justify-center rounded-full cursor-pointer
                 transition-all hover:bg-[#f5f5f5] hover:text-[#333]">×</button>
        <div v-if="selectedUser">
          <div class="text-center p-[30px] bg-[#f9f9f9] rounded-[15px] mb-[30px]">
            <div class="w-[100px] h-[100px] rounded-full mx-auto mb-[20px] flex items-center justify-center
                        bg-gradient-to-br from-[#6b7c93] to-[#5a6a7f]">
              <span class="text-[50px] text-white">👤</span>
            </div>
            <h3 class="text-[22px] font-bold mb-[5px] text-[#333]">{{ selectedUser.display_name }}</h3>
            <p class="text-[14px] text-[#666] mb-[3px]">ID: {{ selectedUser.id }}</p>
            <p class="text-[14px] text-[#666] capitalize">{{ selectedUser.role }} • {{ selectedUser.status }}</p>
          </div>
          <div class="mb-[25px]">
            <h3 class="text-[18px] font-bold mb-[15px] text-[#333]">Account & Profile Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-[15px]">
              <div class="flex flex-col gap-[5px]">
                <strong class="text-[14px] text-[#333]">Email:</strong>
                <span class="text-[14px] text-[#666]">{{ selectedUser.email }}</span>
              </div>
              <div class="flex flex-col gap-[5px]">
                <strong class="text-[14px] text-[#333]">Student ID:</strong>
                <span class="text-[14px] text-[#666]">{{ selectedUser.student_id || '-' }}</span>
              </div>
              <div class="flex flex-col gap-[5px]">
                <strong class="text-[14px] text-[#333]">Course:</strong>
                <span class="text-[14px] text-[#666]">{{ selectedUser.course || '-' }}</span>
              </div>
              <div class="flex flex-col gap-[5px]">
                <strong class="text-[14px] text-[#333]">Year Level:</strong>
                <span class="text-[14px] text-[#666]">{{ selectedUser.year_level || '-' }}</span>
              </div>
              <div class="flex flex-col gap-[5px]">
                <strong class="text-[14px] text-[#333]">Phone:</strong>
                <span class="text-[14px] text-[#666]">{{ selectedUser.phone || '-' }}</span>
              </div>
              <div class="flex flex-col gap-[5px] md:col-span-2">
                <strong class="text-[14px] text-[#333]">Address:</strong>
                <span class="text-[14px] text-[#666]">{{ selectedUser.address || '-' }}</span>
              </div>
            </div>
          </div>
          <button type="button" @click="userViewOpen = false"
            class="w-full px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                   bg-[#2e7d32] text-white cursor-pointer transition-colors hover:bg-[#1b5e20]">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminNavbar  from '@/pages/Admin/Partials/Navbar.vue'
import AdminSidebar from '@/pages/Admin/Partials/Sidebar.vue'
import AdminFooter  from '@/pages/Admin/Partials/FooterLogos.vue'

const props = defineProps({
  users:    { type: Object, default: () => ({ data: [], last_page: 1, current_page: 1 }) },
  q:        { type: String, default: '' },
  role:     { type: String, default: '' },
  authName: { type: String, default: 'Admin' },
})

const userViewOpen = ref(false)
const selectedUser = ref(null)
const searchQ      = ref(props.q)
const searchRole   = ref(props.role)
const route = window.route

const search = () => {
  router.get(route('admin.users.index'), {
    q:    searchQ.value,
    role: searchRole.value,
  }, { preserveState: true, replace: true })
}

const pageUrl = (page) => {
  const params = new URLSearchParams()
  params.set('page', page)
  if (searchQ.value)    params.set('q', searchQ.value)
  if (searchRole.value) params.set('role', searchRole.value)
  return `${route('admin.users.index')}?${params.toString()}`
}

const openView = (user) => { selectedUser.value = user; userViewOpen.value = true }
</script>