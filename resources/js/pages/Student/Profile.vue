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
        <div v-if="profileForm.errors && Object.keys(profileForm.errors).length"
             class="mb-4 bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg">
          <p v-for="(msg, key) in profileForm.errors" :key="key">{{ msg }}</p>
        </div>
        <div v-if="passwordForm.errors && Object.keys(passwordForm.errors).length"
             class="mb-4 bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg">
          <p v-for="(msg, key) in passwordForm.errors" :key="key">{{ msg }}</p>
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
            <input type="file" id="profile_picture" class="hidden" accept="image/*"
                   @change="e => profileForm.profile_picture = e.target.files[0]" />
          </div>

          <!-- ✅ Profile form using Inertia useForm -->
          <div class="flex flex-col gap-6">
            <h2 class="text-xl font-semibold text-[#2e7d32] mb-2">Personal Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Student ID:</label>
                <input type="text" v-model="profileForm.student_id"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a] bg-gray-50" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Email Address:</label>
                <input type="email" v-model="profileForm.email" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">First Name:</label>
                <input type="text" v-model="profileForm.first_name" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Last Name:</label>
                <input type="text" v-model="profileForm.last_name" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Phone Number:</label>
                <input type="tel" v-model="profileForm.phone" placeholder="09XXXXXXXXX"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Course/Program:</label>
                <input type="text" v-model="profileForm.course"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Year Level:</label>
                <select v-model="profileForm.year_level"
                        class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]">
                  <option value="">Select Year Level</option>
                  <option value="1">1st Year</option>
                  <option value="2">2nd Year</option>
                  <option value="3">3rd Year</option>
                  <option value="4">4th Year</option>
                </select>
              </div>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-sm font-semibold text-gray-800">Address:</label>
              <textarea v-model="profileForm.address" rows="3"
                        placeholder="Enter your complete address"
                        class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a] resize-y"></textarea>
            </div>

            <div class="flex gap-3 mt-2">
              <button type="button" @click="profileForm.reset()"
                class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                Cancel
              </button>
              <button type="button" @click="submitProfile"
                :disabled="profileForm.processing"
                class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold bg-[#4caf50] text-white hover:bg-[#45a049] transition disabled:opacity-60">
                {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </div>

          <!-- ✅ Change Password using Inertia useForm -->
          <div class="mt-10 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-[#2e7d32] mb-4">Change Password</h2>
            <div class="flex flex-col gap-4 max-w-md">
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Current Password:</label>
                <input type="password" v-model="passwordForm.current_password" required
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">New Password:</label>
                <input type="password" v-model="passwordForm.new_password" required minlength="8"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Confirm New Password:</label>
                <input type="password" v-model="passwordForm.new_password_confirmation" required minlength="8"
                       class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]" />
              </div>
              <button type="button" @click="submitPassword"
                :disabled="passwordForm.processing"
                class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#4caf50] text-white hover:bg-[#45a049] transition mt-2 disabled:opacity-60">
                {{ passwordForm.processing ? 'Changing...' : 'Change Password' }}
              </button>
            </div>
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
import { router, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  profile:  { type: Object, required: true },
  authName: { type: String, default: 'Student' },
})

const menuOpen = ref(false)
const route    = window.route
const logout   = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const initial = computed(() => (props.authName || 'S').charAt(0).toUpperCase())

// ✅ Profile form - Inertia handles CSRF automatically
const profileForm = useForm({
  student_id:      props.profile.student_id  || '',
  email:           props.profile.email       || '',
  first_name:      props.profile.first_name  || '',
  last_name:       props.profile.last_name   || '',
  phone:           props.profile.phone       || '',
  course:          props.profile.course      || '',
  year_level:      props.profile.year_level  || '',
  address:         props.profile.address     || '',
  profile_picture: null,
})

const submitProfile = () => {
  profileForm.post(route('student.profile.update'), {
    forceFormData: true,
  })
}

// ✅ Password form - Inertia handles CSRF automatically
const passwordForm = useForm({
  current_password:      '',
  new_password:          '',
  new_password_confirmation: '',
})

const submitPassword = () => {
  passwordForm.post(route('student.profile.password'), {
    onSuccess: () => passwordForm.reset(),
  })
}
</script>