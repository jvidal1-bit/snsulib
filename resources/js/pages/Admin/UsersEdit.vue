<template>
  <div class="min-h-screen bg-[#e8e8e8]">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-[#a5d6a7] to-[#c8e6c9] px-[30px] py-[15px]
                flex items-center justify-between shadow-[0_2px_10px_rgba(0,0,0,0.10)]
                sticky top-0 z-[1000]">
      <div class="text-[20px] font-black tracking-[0.5px]">SNSU LIBRARY E-REQUEST</div>
      <div class="relative">
        <button @click.stop="menuOpen = !menuOpen"
          class="flex items-center gap-[8px] px-[16px] py-[8px] rounded-[8px]
                 text-[16px] font-semibold text-[#333] bg-transparent hover:bg-white/30 transition-colors">
          <span>{{ authName }}</span><span class="text-[12px]">▼</span>
        </button>
        <div v-if="menuOpen" @click.stop
          class="absolute right-0 mt-[10px] bg-white rounded-[10px]
                 shadow-[0_5px_20px_rgba(0,0,0,0.15)] min-w-[180px] overflow-hidden z-[1001]">
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
          <Link v-for="item in navItems" :key="item.label" :href="route(item.route)"
             class="px-[20px] py-[12px] rounded-[10px] transition-all"
             :class="item.active
               ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
               : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]'">
            {{ item.label }}
          </Link>
        </nav>
      </aside>

      <!-- Main -->
      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="max-w-[800px] mx-auto bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">

          <h1 class="text-[24px] mb-[20px] text-[#333] font-semibold">Edit User</h1>

          <!-- Errors -->
          <div v-if="$page.props.errors && Object.keys($page.props.errors).length"
               class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
              <li v-for="(msg, key) in $page.props.errors" :key="key">{{ msg }}</li>
            </ul>
          </div>

          <!-- Main Form -->
          <form method="POST" :action="route('admin.users.update', user.id)" class="space-y-[24px]">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="_method" value="PUT" />

            <!-- Account Info -->
            <div class="border-b border-gray-200 pb-[16px] mb-[10px]">
              <h2 class="text-[18px] font-semibold text-[#2e7d32] mb-[12px]">Account Information</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Full Name</label>
                  <input type="text" name="name" :value="user.name" required
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Email Address</label>
                  <input type="email" name="email" :value="user.email" required
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[16px]">
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Role</label>
                  <select name="role" required
                          class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]">
                    <option value="admin"   :selected="user.role === 'admin'">Admin</option>
                    <option value="student" :selected="user.role === 'student'">Student</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Status</label>
                  <select name="status"
                          class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]">
                    <option value="active"   :selected="user.status === 'active'">Active</option>
                    <option value="inactive" :selected="user.status === 'inactive'">Inactive</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Student Profile -->
            <div>
              <h2 class="text-[18px] font-semibold text-[#2e7d32] mb-[12px]">Student Profile (optional)</h2>
              <p class="text-xs text-gray-500 mb-[14px]">These fields are mainly used when the user's role is <strong>student</strong>.</p>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Student ID</label>
                  <input type="text" name="student_id" :value="user.student_id"
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Phone Number</label>
                  <input type="text" name="phone" :value="user.phone" placeholder="09XXXXXXXXX"
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[16px]">
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">First Name</label>
                  <input type="text" name="first_name" :value="user.first_name"
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Last Name</label>
                  <input type="text" name="last_name" :value="user.last_name"
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[16px]">
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Course / Program</label>
                  <input type="text" name="course" :value="user.course"
                         class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]" />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-1 text-[#333]">Year Level</label>
                  <select name="year_level"
                          class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]">
                    <option value="">Select Year Level</option>
                    <option value="1" :selected="user.year_level == '1'">1st Year</option>
                    <option value="2" :selected="user.year_level == '2'">2nd Year</option>
                    <option value="3" :selected="user.year_level == '3'">3rd Year</option>
                    <option value="4" :selected="user.year_level == '4'">4th Year</option>
                  </select>
                </div>
              </div>

              <div class="mt-[16px]">
                <label class="block text-sm font-semibold mb-1 text-[#333]">Address</label>
                <textarea name="address" rows="3" placeholder="Full residential address"
                          class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2] resize-y">{{ user.address }}</textarea>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-[10px] mt-[10px]">
              <Link :href="route('admin.users.index')"
                 class="inline-flex justify-center items-center px-[20px] py-[10px]
                        rounded-[8px] text-[14px] font-semibold bg-[#9e9e9e] text-white hover:bg-[#757575]">
                Cancel
              </Link>
              <button type="submit"
                class="inline-flex justify-center items-center px-[20px] py-[10px]
                       rounded-[8px] text-[14px] font-semibold bg-[#2e7d32] text-white hover:bg-[#1b5e20]">
                Save Changes
              </button>
            </div>
          </form>

          <!-- Delete Form -->
          <form method="POST" :action="route('admin.users.destroy', user.id)"
                class="mt-[20px] border-t border-[#eee] pt-[20px]"
                @submit.prevent="confirmDelete">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="_method" value="DELETE" />
            <button type="submit"
              class="inline-flex justify-center items-center px-[20px] py-[10px]
                     rounded-[8px] text-[14px] font-semibold bg-red-600 text-white hover:bg-red-700">
              Delete User
            </button>
          </form>

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
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  user:     { type: Object, required: true },
  authName: { type: String, default: 'Admin' },
})

const menuOpen = ref(false)
const route  = window.route
const csrf   = document.querySelector('meta[name="csrf-token"]')?.content
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const confirmDelete = (event) => {
  if (confirm('Are you sure you want to delete this user?')) event.target.submit()
}

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard',      active: false },
  { label: 'Requests',  route: 'admin.requests.index', active: false },
  { label: 'Books',     route: 'admin.books.index',    active: false },
  { label: 'Users',     route: 'admin.users.index',    active: true  },
  { label: 'Settings',  route: 'admin.settings.index', active: false },
]
</script>