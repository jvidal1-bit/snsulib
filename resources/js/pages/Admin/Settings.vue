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

        <!-- Flash -->
        <div v-if="$page.props.flash && $page.props.flash.success"
             class="mb-4 rounded-[10px] px-4 py-3 bg-green-100 text-green-700 text-sm">
          {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.errors && Object.keys($page.props.errors).length"
             class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
          <ul class="list-disc list-inside space-y-1">
            <li v-for="(msg, key) in $page.props.errors" :key="key">{{ msg }}</li>
          </ul>
        </div>

        <div class="max-w-[600px] mx-auto bg-white rounded-[20px] p-[40px]
                    shadow-[0_5px_20px_rgba(0,0,0,0.10)]">

          <!-- Request Settings Accordion -->
          <div class="mb-[15px]">
            <button type="button" @click="requestOpen = !requestOpen"
              class="w-full flex items-center gap-[15px] px-[25px] py-[18px]
                     bg-gradient-to-br from-[#c8c8c8] to-[#b0b0b0] rounded-[12px]
                     border-0 cursor-pointer text-[16px] font-semibold text-[#333]
                     transition-all hover:-translate-y-[2px] hover:shadow-[0_5px_15px_rgba(0,0,0,0.20)]">
              <span class="text-[20px]">⚙️</span>
              <span class="flex-1 text-left">Request Settings</span>
              <span class="text-[14px] transition-transform" :class="requestOpen ? 'rotate-180' : ''">▼</span>
            </button>
            <div v-if="requestOpen" class="bg-[#f9f9f9] rounded-b-[12px] -mt-[10px] pt-[20px] pb-[20px] px-[25px]">
              <div class="flex items-center gap-[15px] py-[12px] border-b border-[#e0e0e0] text-[15px] text-[#555]">
                <span class="text-[18px]">📖</span>
                <span class="flex-1">Maximum chapters per request & expiry</span>
                <button type="button" @click="requestModalOpen = true"
                  class="px-[16px] py-[6px] bg-[#4caf50] text-white rounded-[6px] text-[13px] font-semibold
                         border-0 cursor-pointer transition-colors hover:bg-[#45a049]">Edit</button>
              </div>
              <div class="flex items-center gap-[15px] py-[12px] border-b border-[#e0e0e0] text-[15px] text-[#555]">
                <span class="text-[18px]">📅</span>
                <span class="flex-1">Request expiry and lifetime rules</span>
              </div>
              <div class="flex items-center gap-[15px] py-[12px] text-[15px] text-[#555]">
                <span class="text-[18px]">👤</span>
                <span class="flex-1">Email notifications for admins & students</span>
              </div>
            </div>
          </div>

          <!-- Library Information Accordion -->
          <div class="mb-[15px]">
            <button type="button" @click="libraryOpen = !libraryOpen"
              class="w-full flex items-center gap-[15px] px-[25px] py-[18px]
                     bg-gradient-to-br from-[#c8c8c8] to-[#b0b0b0] rounded-[12px]
                     border-0 cursor-pointer text-[16px] font-semibold text-[#333]
                     transition-all hover:-translate-y-[2px] hover:shadow-[0_5px_15px_rgba(0,0,0,0.20)]">
              <span class="text-[20px]">🏛️</span>
              <span class="flex-1 text-left">Library Information</span>
              <span class="text-[14px] transition-transform" :class="libraryOpen ? 'rotate-180' : ''">▼</span>
            </button>
            <div v-if="libraryOpen" class="bg-[#f9f9f9] rounded-b-[12px] -mt-[10px] pt-[20px] pb-[20px] px-[25px]">
              <div class="flex items-center gap-[15px] py-[12px] border-b border-[#e0e0e0] text-[15px] text-[#555]">
                <span class="flex-1">Library Name</span>
                <button type="button" @click="libraryModalOpen = true"
                  class="px-[16px] py-[6px] bg-[#4caf50] text-white rounded-[6px] text-[13px] font-semibold
                         border-0 cursor-pointer transition-colors hover:bg-[#45a049]">Edit</button>
              </div>
              <div class="flex items-center gap-[15px] py-[12px] border-b border-[#e0e0e0] text-[15px] text-[#555]">
                <span class="flex-1">Operation Hours</span>
              </div>
              <div class="flex items-center gap-[15px] py-[12px] border-b border-[#e0e0e0] text-[15px] text-[#555]">
                <span class="flex-1">Contact & Email</span>
              </div>
              <div class="flex items-center gap-[15px] py-[12px] text-[15px] text-[#555]">
                <span class="flex-1">Library Address</span>
              </div>
            </div>
          </div>

          <!-- My Account -->
          <div class="mb-[15px]">
            <button type="button" @click="accountModalOpen = true"
              class="w-full flex items-center gap-[15px] px-[25px] py-[18px]
                     bg-gradient-to-br from-[#c8c8c8] to-[#b0b0b0] rounded-[12px]
                     border-0 cursor-pointer text-[16px] font-semibold text-[#333]
                     transition-all hover:-translate-y-[2px] hover:shadow-[0_5px_15px_rgba(0,0,0,0.20)]">
              <span class="text-[20px]">👤</span>
              <span class="flex-1 text-left">My Account</span>
              <span class="text-[14px]">▼</span>
            </button>
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

    <!-- Request Settings Modal -->
    <div v-if="requestModalOpen" @click.self="requestModalOpen = false"
         class="fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-[20px] overflow-y-auto">
      <div @click.stop class="bg-white rounded-[20px] p-[40px] w-full max-w-[700px]
                              max-h-[90vh] overflow-y-auto relative shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
        <button type="button" @click="requestModalOpen = false"
          class="absolute top-[20px] right-[20px] bg-transparent border-0 text-[32px] text-[#666]
                 w-[40px] h-[40px] flex items-center justify-center rounded-full cursor-pointer
                 transition-all hover:bg-[#f5f5f5] hover:text-[#333]">×</button>
        <h2 class="text-[24px] mb-[25px] text-[#333]">Request Settings</h2>

        <form method="POST" :action="route('admin.settings.update')" class="flex flex-col gap-[20px]">
          <input type="hidden" name="_token" :value="csrf" />

          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">
              <span class="text-[18px]">📖</span> Maximum Chapters per Request:
            </label>
            <select name="max_chapters_per_request"
                    class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]">
              <option v-for="n in [1,2,3,5,10]" :key="n" :value="n" :selected="settings.max_chapters_per_request == n">{{ n }} Chapter{{ n > 1 ? 's' : '' }}</option>
            </select>
          </div>

          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">
              <span class="text-[18px]">📅</span> Request Expiry (Days):
            </label>
            <input type="number" name="request_expiry_days" min="1" max="30"
                   :value="settings.request_expiry_days ?? 7"
                   placeholder="Enter days (1-30)"
                   class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
            <small class="text-[13px] text-[#666] italic">Request will expire after this many days</small>
          </div>

          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">
              <span class="text-[18px]">👤</span> Email Notification:
            </label>
            <div class="flex flex-col gap-[12px] p-[15px] bg-[#f9f9f9] rounded-[8px]">
              <label class="flex items-center gap-[10px] text-[14px] cursor-pointer">
                <input type="checkbox" name="notify_on_request" value="1"
                       :checked="settings.notify_on_request"
                       class="w-[18px] h-[18px] cursor-pointer accent-[#4caf50]" />
                <span class="text-[#555]">Notify admin on new request</span>
              </label>
              <label class="flex items-center gap-[10px] text-[14px] cursor-pointer">
                <input type="checkbox" name="notify_on_complete" value="1"
                       :checked="settings.notify_on_complete"
                       class="w-[18px] h-[18px] cursor-pointer accent-[#4caf50]" />
                <span class="text-[#555]">Notify student on completion</span>
              </label>
              <label class="flex items-center gap-[10px] text-[14px] cursor-pointer">
                <input type="checkbox" name="notify_on_expiry" value="1"
                       :checked="settings.notify_on_expiry"
                       class="w-[18px] h-[18px] cursor-pointer accent-[#4caf50]" />
                <span class="text-[#555]">Notify on request expiry</span>
              </label>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
            <button type="button" @click="requestModalOpen = false"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#a5d6a7] text-black cursor-pointer transition-colors hover:bg-[#81c784]">Cancel</button>
            <button type="submit"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#2e7d32] text-white cursor-pointer transition-colors hover:bg-[#1b5e20]">Save Settings</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Library Info Modal -->
    <div v-if="libraryModalOpen" @click.self="libraryModalOpen = false"
         class="fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-[20px] overflow-y-auto">
      <div @click.stop class="bg-white rounded-[20px] p-[40px] w-full max-w-[700px]
                              max-h-[90vh] overflow-y-auto relative shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
        <button type="button" @click="libraryModalOpen = false"
          class="absolute top-[20px] right-[20px] bg-transparent border-0 text-[32px] text-[#666]
                 w-[40px] h-[40px] flex items-center justify-center rounded-full cursor-pointer
                 transition-all hover:bg-[#f5f5f5] hover:text-[#333]">×</button>
        <h2 class="text-[24px] mb-[25px] text-[#333]">Library Information</h2>

        <form method="POST" :action="route('admin.settings.update')" class="flex flex-col gap-[20px]">
          <input type="hidden" name="_token" :value="csrf" />
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">🏛️ Library Name:</label>
            <input type="text" name="library_name" :value="settings.library_name" placeholder="Enter library name"
                   class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
          </div>
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">🕐 Operation Hours:</label>
            <input type="text" name="operation_hours" :value="settings.operation_hours" placeholder="e.g., Mon-Fri: 8:00 AM - 5:00 PM"
                   class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
          </div>
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">📞 Contact Number:</label>
            <input type="tel" name="library_contact" :value="settings.library_contact" placeholder="Enter contact number"
                   class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
          </div>
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">📧 Email Address:</label>
            <input type="email" name="library_email" :value="settings.library_email" placeholder="Enter email address"
                   class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
          </div>
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">📍 Library Address:</label>
            <textarea name="library_address" rows="3" placeholder="Enter complete library address"
                      class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] resize-y outline-none focus:border-[#66bb6a]">{{ settings.library_address }}</textarea>
          </div>
          <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
            <button type="button" @click="libraryModalOpen = false"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#a5d6a7] text-black cursor-pointer transition-colors hover:bg-[#81c784]">Cancel</button>
            <button type="submit"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#2e7d32] text-white cursor-pointer transition-colors hover:bg-[#1b5e20]">Save Information</button>
          </div>
        </form>
      </div>
    </div>

    <!-- My Account Modal -->
    <div v-if="accountModalOpen" @click.self="accountModalOpen = false"
         class="fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-[20px] overflow-y-auto">
      <div @click.stop class="bg-white rounded-[20px] p-[40px] w-full max-w-[700px]
                              max-h-[90vh] overflow-y-auto relative shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
        <button type="button" @click="accountModalOpen = false"
          class="absolute top-[20px] right-[20px] bg-transparent border-0 text-[32px] text-[#666]
                 w-[40px] h-[40px] flex items-center justify-center rounded-full cursor-pointer
                 transition-all hover:bg-[#f5f5f5] hover:text-[#333]">×</button>

        <!-- Avatar -->
        <div class="text-center p-[20px] mb-[30px]">
          <div class="w-[100px] h-[100px] rounded-full overflow-hidden mx-auto mb-[15px]">
            <img v-if="authAvatar" :src="authAvatar" class="w-full h-full object-cover" alt="Profile" />
            <div v-else class="w-full h-full flex items-center justify-center bg-gray-300 text-4xl">👤</div>
          </div>
          <h3 class="text-[20px] font-bold text-[#333]">{{ authName }}</h3>
        </div>

        <!-- Account Form -->
        <form method="POST" :action="route('admin.account.update')" enctype="multipart/form-data" class="mb-[30px]">
          <input type="hidden" name="_token" :value="csrf" />
          <h3 class="text-[18px] font-bold mb-[20px] text-[#333]">Personal Information:</h3>
          <div class="flex flex-col gap-[8px] mb-[15px]">
            <label class="font-semibold text-[14px] text-[#333]">Profile Picture:</label>
            <input type="file" name="avatar" accept="image/*"
                   class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-[15px] mb-[15px]">
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Full Name:</label>
              <input type="text" name="name" :value="authName"
                     class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Email address:</label>
              <input type="email" name="email" :value="authEmail"
                     class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
          </div>
          <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
            <button type="button" @click="accountModalOpen = false"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#a5d6a7] text-black cursor-pointer transition-colors hover:bg-[#81c784]">Cancel</button>
            <button type="submit"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#2e7d32] text-white cursor-pointer transition-colors hover:bg-[#1b5e20]">Save Changes</button>
          </div>
        </form>

        <!-- Change Password -->
        <div class="pt-[30px] border-t-2 border-[#e0e0e0]">
          <h3 class="text-[18px] font-bold mb-[20px] text-[#333]">Change Password:</h3>
          <form method="POST" :action="route('admin.account.password')" class="flex flex-col gap-[15px]">
            <input type="hidden" name="_token" :value="csrf" />
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Current Password:</label>
              <input type="password" name="current_password"
                     class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">New Password:</label>
              <input type="password" name="password" minlength="8"
                     class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Confirm Password:</label>
              <input type="password" name="password_confirmation"
                     class="px-[12px] py-[12px] border-2 border-[#e0e0e0] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <button type="submit"
              class="w-full px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#2e7d32] text-white cursor-pointer mt-[15px] transition-colors hover:bg-[#1b5e20]">
              Change Password
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  settings:   { type: Object, default: () => ({}) },
  authName:   { type: String, default: 'Admin' },
  authEmail:  { type: String, default: '' },
  authAvatar: { type: String, default: null },
})

const menuOpen        = ref(false)
const requestOpen     = ref(true)
const libraryOpen     = ref(false)
const requestModalOpen = ref(false)
const libraryModalOpen = ref(false)
const accountModalOpen = ref(false)
const route  = window.route
const csrf   = document.querySelector('meta[name="csrf-token"]')?.content
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard',      active: false },
  { label: 'Requests',  route: 'admin.requests.index', active: false },
  { label: 'Books',     route: 'admin.books.index',    active: false },
  { label: 'Users',     route: 'admin.users.index',    active: false },
  { label: 'Settings',  route: 'admin.settings.index', active: true  },
]
</script>