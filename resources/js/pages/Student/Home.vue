<template>
  <div class="min-h-screen bg-gray-100 pb-24">

    <!-- Navbar (matches student/partials/navbar.blade.php exactly) -->
    <nav class="w-full bg-[#c8e6c9] shadow-md">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">

          <!-- Brand -->
          <div class="font-semibold text-lg tracking-wide text-[#1b5e20]">
            SNSU LIBRARY E-REQUEST
          </div>

          <!-- Links + dropdown -->
          <div class="flex items-center gap-6 text-sm font-semibold text-gray-800">

            <!-- Home -->
            <a :href="route('student.home')"
               class="px-2 py-1 border-b-2 border-[#1b5e20] text-[#1b5e20]">
              Home
            </a>

            <!-- Catalog -->
            <a :href="route('student.catalog')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">
              Catalog
            </a>

            <!-- My Request -->
            <a :href="route('student.requests.index')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">
              My Request
            </a>

            <!-- User dropdown -->
            <div class="relative">
              <button
                type="button"
                class="flex items-center gap-1 px-3 py-1 rounded-md hover:bg-white/60"
                @click.stop="menuOpen = !menuOpen"
              >
                <span>{{ authName }}</span>
                <span class="text-xs">▼</span>
              </button>

              <div
                v-if="menuOpen"
                class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border text-sm z-50"
                @click.stop="() => {}"
              >
                <a :href="route('student.profile')"
                   class="flex items-center px-3 py-2 hover:bg-gray-100">
                  <span class="mr-2">👤</span> Profile
                </a>
                <form method="POST" :action="route('logout')">
                  <input type="hidden" name="_token" :value="csrf" />
                  <button
                    type="button"
                    class="w-full text-left flex items-center px-3 py-2 text-red-600 hover:bg-gray-100"
                    @click="logout"
                  >
                    <span class="mr-2">🚪</span> Logout
                  </button>
                </form>
              </div>
            </div>

          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8">

      <!-- Status Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <a
          v-for="card in statCards"
          :key="card.label"
          :href="route(card.route)"
          class="block rounded-2xl px-6 py-6 text-center shadow-lg transform transition
                 hover:-translate-y-1 hover:shadow-2xl"
          :class="[card.bg, card.textColor]"
        >
          <h3 class="text-lg font-semibold mb-2">{{ card.label }}</h3>
          <p class="text-5xl font-bold leading-none">{{ card.value }}</p>
        </a>
      </div>

      <!-- Recent Requests Table -->
      <div class="bg-white rounded-2xl shadow-xl px-6 py-6">
        <h2 class="text-2xl font-bold mb-4">Recent Requests</h2>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-300">
                <th class="px-4 py-3 text-left font-semibold rounded-l-lg">ISBN</th>
                <th class="px-4 py-3 text-left font-semibold">Title</th>
                <th class="px-4 py-3 text-left font-semibold">Chapter</th>
                <th class="px-4 py-3 text-left font-semibold">Status</th>
                <th class="px-4 py-3 text-left font-semibold">Date</th>
                <th class="px-4 py-3 text-center font-semibold rounded-r-lg">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="recentRequests.length === 0">
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                  No recent requests found.
                </td>
              </tr>
              <tr
                v-for="req in recentRequests"
                :key="req.id"
                class="border-b border-gray-200 hover:bg-gray-100 transition"
              >
                <td class="px-4 py-3 text-gray-800">{{ req.isbn }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.title }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.chapter }}</td>
                <td class="px-4 py-3">
                  <span
                    class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                    :class="statusColors[req.status] ?? 'bg-gray-100 text-gray-800'"
                  >
                    {{ capitalize(req.status) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ req.date }}</td>
                <td class="px-4 py-3 text-center">
                  <a
                    :href="route('student.requests.index')"
                    class="inline-flex items-center justify-center px-3 py-1 text-lg
                           font-medium rounded-md border border-gray-300
                           hover:bg-gray-200 transition"
                  >⚙️</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="fixed bottom-0 w-full bg-white border-t px-6 py-3 flex justify-between items-center text-xs text-gray-500">
      <span>For Nation's Greater High</span>
      <div class="flex gap-2">
        <img :src="'/assets/images/snsu-logo.png'" alt="SNSU Logo" class="h-8 w-8 rounded-full" />
        <img :src="'/assets/images/library-logo.png'" alt="Library Logo" class="h-8 w-8 rounded-full" />
      </div>
    </footer>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post(route('logout'))
}

const props = defineProps({
  stats:          { type: Object, default: () => ({ total: 0, pending: 0, processing: 0, completed: 0 }) },
  recentRequests: { type: Array,  default: () => [] },
  authName:       { type: String, default: 'Student' },
})

const menuOpen = ref(false)
const csrf = document.querySelector('meta[name="csrf-token"]')?.content
const route = window.route

document.addEventListener('click', () => { menuOpen.value = false })

const statusColors = {
  pending:    'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  completed:  'bg-green-100 text-green-800',
}

const capitalize = (str) => str ? str.charAt(0).toUpperCase() + str.slice(1) : ''

const statCards = [
  {
    label: 'Total Request:',
    value: props.stats.total,
    route: 'student.requests.total',
    bg: 'bg-[#66bb6a]',
    textColor: 'text-white',
  },
  {
    label: 'Pending:',
    value: props.stats.pending,
    route: 'student.requests.pending',
    bg: 'bg-[#81c784]',
    textColor: 'text-white',
  },
  {
    label: 'Processing:',
    value: props.stats.processing,
    route: 'student.requests.processing',
    bg: 'bg-[#a5d6a7]',
    textColor: 'text-black',
  },
  {
    label: 'Completed:',
    value: props.stats.completed,
    route: 'student.requests.completed',
    bg: 'bg-[#00897b]',
    textColor: 'text-white',
  },
]
</script>