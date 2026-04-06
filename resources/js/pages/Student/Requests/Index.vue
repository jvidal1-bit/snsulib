<template>
  <div class="min-h-screen bg-gray-100">
    <StudentNavbar :auth-name="authName" active="Requests" />

    <main class="max-w-6xl mx-auto px-4 py-8 pb-24">

      <div v-if="$page.props.flash && $page.props.flash.status"
           class="mb-4 bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
        {{ $page.props.flash.status }}
      </div>

      <!-- Filter pills -->
      <div class="mb-4 flex flex-wrap gap-2">
        <Link :href="route('student.requests.index')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-blue-600 text-white border-blue-600">All</Link>
        <Link :href="route('student.requests.pending')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">Pending</Link>
        <Link :href="route('student.requests.processing')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">Processing</Link>
        <Link :href="route('student.requests.completed')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">Completed</Link>
      </div>

      <div class="bg-white rounded-2xl shadow-xl px-6 py-6">
        <h2 class="text-2xl font-bold mb-4">My Requests</h2>
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
              <tr v-if="requests.length === 0">
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">You have no requests yet.</td>
              </tr>
              <tr v-for="req in requests" :key="req.id"
                  class="border-b border-gray-200 hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-gray-800">{{ req.isbn }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.title }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.chapter }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex px-4 py-1 rounded-full text-xs font-semibold"
                        :class="statusStyles[req.status] ?? 'bg-gray-100 text-gray-800'">
                    {{ capitalize(req.status) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ req.date }}</td>
                <td class="px-4 py-3 text-center">
                  <button type="button" @click="openModal(req)"
                    class="text-xl leading-none bg-transparent border-0 cursor-pointer transform transition hover:scale-110">⚙️</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <StudentFooter />

    <!-- Modal -->
    <div v-if="modalOpen" class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
         @click.self="closeModal">
      <div class="bg-white rounded-2xl shadow-xl max-w-xl w-full mx-4 p-8">
        <h2 class="text-2xl font-bold mb-6">Request Details</h2>
        <div v-if="selected">
          <div class="bg-gray-100 rounded-xl p-4 mb-6">
            <p class="text-sm mb-2"><strong>Title:</strong> {{ selected.title }}</p>
            <p class="text-sm mb-2"><strong>ISBN:</strong> {{ selected.isbn }}</p>
            <p class="text-sm mb-2"><strong>Chapter:</strong> {{ selected.chapter }}</p>
            <p class="text-sm mb-2"><strong>Status:</strong> {{ selected.status }}</p>
            <p class="text-sm mb-2"><strong>Requested On:</strong> {{ selected.created_at }}</p>
            <p class="text-sm mb-2"><strong>Needed By:</strong> {{ selected.needed_by }}</p>
          </div>
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Purpose</h3>
            <p class="text-sm text-gray-700">{{ selected.purpose || '—' }}</p>
          </div>
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Note</h3>
            <p class="text-sm text-gray-700">{{ selected.note || 'No additional notes.' }}</p>
          </div>
          <div v-if="selected.status === 'completed'" class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Completion Details</h3>
            <p class="text-sm text-gray-700 mb-1"><strong>Prepared By:</strong> {{ selected.prepared_by || '—' }}</p>
            <p class="text-sm text-gray-700 mb-3"><strong>Expires At:</strong> {{ selected.expiration_at || '—' }}</p>
            <a v-if="selected.completed_file && !selected.expired" :href="selected.completed_file" target="_blank"
               class="inline-flex px-4 py-2 rounded-full bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">Download File</a>
            <p v-if="selected.expired" class="text-sm text-red-600 font-semibold mt-2">This request has expired. The file is no longer available.</p>
          </div>
        </div>
        <div class="flex gap-3 mt-4">
          <button type="button" @click="closeModal"
            class="flex-1 py-3 px-4 rounded-full text-sm font-semibold bg-[#a5d6a7] text-black hover:bg-[#81c784] transition">Back</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import StudentNavbar from '@/pages/Student/Partials/Navbar.vue'
import StudentFooter from '@/pages/Student/Partials/Footer.vue'

const props = defineProps({
  requests: { type: Array,  default: () => [] },
  authName: { type: String, default: 'Student' },
})

const modalOpen = ref(false)
const selected  = ref(null)
const route = window.route

const statusStyles = {
  pending:    'bg-[#fff3cd] text-[#856404]',
  processing: 'bg-[#cfe2ff] text-[#084298]',
  completed:  'bg-[#d1e7dd] text-[#0f5132]',
}

const capitalize = (str) => str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
const openModal  = (req) => { selected.value = req; modalOpen.value = true }
const closeModal = () => { modalOpen.value = false; selected.value = null }
</script>