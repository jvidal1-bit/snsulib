<template>
  <div class="min-h-screen bg-gray-100 pb-24">

    <!-- Navbar -->
    <nav class="w-full bg-[#c8e6c9] shadow-md">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">
          <div class="font-semibold text-lg tracking-wide text-[#1b5e20]">SNSU LIBRARY E-REQUEST</div>
          <div class="flex items-center gap-6 text-sm font-semibold text-gray-800">
            <Link :href="route('student.home')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">Home</Link>
            <Link :href="route('student.catalog')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">Catalog</Link>
            <Link :href="route('student.requests.index')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">My Request</Link>
            <div class="relative">
              <button type="button" @click.stop="menuOpen = !menuOpen"
                class="flex items-center gap-1 px-3 py-1 rounded-md hover:bg-white/60">
                <span>{{ authName }}</span><span class="text-xs">▼</span>
              </button>
              <div v-if="menuOpen" @click.stop
                class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border text-sm z-50">
                <Link :href="route('student.profile')"
                   class="flex items-center px-3 py-2 hover:bg-gray-100 border-b-2 border-[#1b5e20] text-[#1b5e20]">
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

    <main class="px-4 py-8">
      <div class="max-w-3xl mx-auto">

        <!-- Flash & errors -->
        <div v-if="$page.props.flash && $page.props.flash.status"
             class="mb-4 bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
          {{ $page.props.flash.status }}
        </div>
        <div v-if="$page.props.errors && Object.keys($page.props.errors).length"
             class="mb-4 bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg">
          <p v-for="(msg, key) in $page.props.errors" :key="key">{{ msg }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xl">
          <h1 class="text-2xl font-bold text-[#1b5e20] text-center mb-8">Student Profile</h1>

          <!-- Profile picture -->
          <div class="flex flex-col items-center mb-8 pb-6 border-b border-gray-200">
            <div class="w-32 h-32 rounded-full bg-[#a5d6a7] flex items-center justify-center mb-3 overflow-hidden shadow-md">
              <img v-if="profile.avatar_url" :src="profile.avatar_url" class="w-full h-full object-cover" alt="Profile" />
              <div v-else class="text-4xl font-bold text-[#1b5e20]">{{ initial }}</div>
            </div>
            <label for="profile_picture"
                   class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white cursor-pointer bg-[#4caf50] hover:bg-[#45a049] transition">
              Change Picture
            </label>
          </div>

          <!-- Profile form -->
          <form method="POST" :action="route('student.profile.update')" enctype="multipart/form-data" class="flex flex-col gap-6">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" />

            <h2 class="text-xl font-semibold text-[#2e7d32] mb-2">Personal Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Student ID:</label>
                <input type="text" name="student_id" :value="profile.student_id"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a] bg-gray-50" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Email Address:</label>
                <input type="email" name="email" :value="profile.email" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">First Name:</label>
                <input type="text" name="first_name" :value="profile.first_name" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Last Name:</label>
                <input type="text" name="last_name" :value="profile.last_name" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Phone Number:</label>
                <input type="tel" name="phone" :value="profile.phone" placeholder="09XXXXXXXXX"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Course/Program:</label>
                <input type="text" name="course" :value="profile.course"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Year Level:</label>
                <select name="year_level"
                        class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]">
                  <option value="">Select Year Level</option>
                  <option value="1" :selected="profile.year_level == '1'">1st Year</option>
                  <option value="2" :selected="profile.year_level == '2'">2nd Year</option>
                  <option value="3" :selected="profile.year_level == '3'">3rd Year</option>
                  <option value="4" :selected="profile.year_level == '4'">4th Year</option>
                </select>
              </div>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-sm font-semibold text-gray-800">Address:</label>
              <textarea name="address" rows="3" placeholder="Enter your complete address"
                        class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a] resize-y">{{ profile.address }}</textarea>
            </div>

            <div class="flex gap-3 mt-2">
              <button type="reset"
                class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-200 text-gray-800 hover:bg-gray-300 transition">Cancel</button>
              <button type="submit"
                class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold bg-[#4caf50] text-white hover:bg-[#45a049] transition">Save Changes</button>
            </div>
          </form>

          <!-- Change Password -->
          <div class="mt-10 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-[#2e7d32] mb-4">Change Password</h2>
            <form method="POST" :action="route('student.profile.password')" class="flex flex-col gap-4 max-w-md">
              <input type="hidden" name="_token" :value="csrf" />
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Current Password:</label>
                <input type="password" name="current_password" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">New Password:</label>
                <input type="password" name="new_password" required minlength="8"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Confirm New Password:</label>
                <input type="password" name="new_password_confirmation" required minlength="8"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <button type="submit"
                class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#4caf50] text-white hover:bg-[#45a049] transition mt-2">
                Change Password
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="fixed bottom-0 w-full bg-white border-t px-6 py-3
                   flex justify-between items-center text-xs text-gray-500">
      <span>For Nation's Greater High</span>
      <div class="flex gap-2">
        <img :src="'/assets/images/snsu-logo.png'" class="h-8 w-8 rounded-full" />
        <img :src="'/assets/images/library-logo.png'" class="h-8 w-8 rounded-full" />
      </div>
    </footer>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  profile:  { type: Object, required: true },
  authName: { type: String, default: 'Student' },
})

const menuOpen = ref(false)
const route  = window.route
const csrf   = document.querySelector('meta[name="csrf-token"]')?.content
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const initial = computed(() => (props.authName || 'S').charAt(0).toUpperCase())
</script>