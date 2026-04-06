<template>
  <div class="min-h-screen bg-gray-100 pb-24">
    <StudentNavbar :auth-name="authName" active="Home" />

    <main class="max-w-6xl mx-auto px-4 py-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <Link v-for="card in statCards" :key="card.label" :href="route(card.route)"
           class="block rounded-2xl px-6 py-6 text-center shadow-lg transform transition hover:-translate-y-1 hover:shadow-2xl"
           :class="[card.bg, card.textColor]">
          <h3 class="text-lg font-semibold mb-2">{{ card.label }}</h3>
          <p class="text-5xl font-bold leading-none">{{ card.value }}</p>
        </Link>
      </div>

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
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No recent requests found.</td>
              </tr>
              <tr v-for="req in recentRequests" :key="req.id"
                  class="border-b border-gray-200 hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-gray-800">{{ req.isbn }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.title }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.chapter }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                        :class="statusColors[req.status] ?? 'bg-gray-100 text-gray-800'">
                    {{ capitalize(req.status) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ req.date }}</td>
                <td class="px-4 py-3 text-center">
                  <Link :href="route('student.requests.index')"
                     class="inline-flex items-center justify-center px-3 py-1 text-lg font-medium
                            rounded-md border border-gray-300 hover:bg-gray-200 transition">⚙️</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <StudentFooter />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import StudentNavbar from '@/pages/Student/Partials/Navbar.vue'
import StudentFooter from '@/pages/Student/Partials/Footer.vue'

const props = defineProps({
  stats:          { type: Object, default: () => ({ total: 0, pending: 0, processing: 0, completed: 0 }) },
  recentRequests: { type: Array,  default: () => [] },
  authName:       { type: String, default: 'Student' },
})

const route = window.route

const statusColors = {
  pending:    'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  completed:  'bg-green-100 text-green-800',
}

const capitalize = (str) => str ? str.charAt(0).toUpperCase() + str.slice(1) : ''

const statCards = [
  { label: 'Total Request:', value: props.stats.total,      route: 'student.requests.total',      bg: 'bg-[#66bb6a]', textColor: 'text-white' },
  { label: 'Pending:',       value: props.stats.pending,    route: 'student.requests.pending',    bg: 'bg-[#81c784]', textColor: 'text-white' },
  { label: 'Processing:',    value: props.stats.processing, route: 'student.requests.processing', bg: 'bg-[#a5d6a7]', textColor: 'text-black' },
  { label: 'Completed:',     value: props.stats.completed,  route: 'student.requests.completed',  bg: 'bg-[#00897b]', textColor: 'text-white' },
]
</script>