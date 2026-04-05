<template>
  <div class="min-h-screen bg-gray-100">

    <!-- Navbar -->
    <nav class="w-full bg-[#c8e6c9] shadow-md">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">
          <div class="font-semibold text-lg tracking-wide text-[#1b5e20]">SNSU LIBRARY E-REQUEST</div>
          <div class="flex items-center gap-6 text-sm font-semibold text-gray-800">
            <Link :href="route('student.home')" class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">Home</Link>
            <Link :href="route('student.catalog')" class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">Catalog</Link>
            <Link :href="route('student.requests.index')" class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">My Request</Link>
            <div class="relative">
              <button type="button" @click.stop="menuOpen = !menuOpen"
                class="flex items-center gap-1 px-3 py-1 rounded-md hover:bg-white/60">
                <span>{{ authName }}</span><span class="text-xs">▼</span>
              </button>
              <div v-if="menuOpen" @click.stop
                class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border text-sm z-50">
                <Link :href="route('student.profile')" class="flex items-center px-3 py-2 hover:bg-gray-100">
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

    <main class="max-w-6xl mx-auto px-4 py-8">

      <!-- Flash -->
      <div v-if="$page.props.flash && $page.props.flash.status"
           class="mb-4 bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
        {{ $page.props.flash.status }}
      </div>

      <!-- Filter pills -->
      <div class="mb-4 flex flex-wrap gap-2">
        <Link :href="route('student.requests.index')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">All</Link>
        <Link :href="route('student.requests.pending')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">Pending</Link>
        <Link :href="route('student.requests.processing')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">Processing</Link>
        <Link :href="route('student.requests.completed')"
           class="px-3 py-1 text-xs font-semibold rounded-full border bg-blue-600 text-white border-blue-600">Completed</Link>
      </div>

      <div class="bg-white rounded-2xl shadow-xl px-6 py-6">
        <h2 class="text-2xl font-bold mb-4">Completed:</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm border-collapse">
            <thead>
              <tr class="bg-gray-300">
                <th class="px-4 py-3 text-left font-semibold rounded-l-lg">ISBN</th>
                <th class="px-4 py-3 text-left font-semibold">Title</th>
                <th class="px-4 py-3 text-left font-semibold">Chapter</th>
                <th class="px-4 py-3 text-left font-semibold">Status</th>
                <th class="px-4 py-3 text-left font-semibold">Expires</th>
                <th class="px-4 py-3 text-center font-semibold rounded-r-lg">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="requests.length === 0">
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No completed requests found.</td>
              </tr>
              <tr v-for="req in requests" :key="req.id"
                  class="border-b border-gray-200 hover:bg-gray-100 transition">
                <td class="px-4 py-3 text-gray-800">{{ req.isbn }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.title }}</td>
                <td class="px-4 py-3 text-gray-800">{{ req.chapter }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex px-4 py-1 rounded-full text-xs font-semibold bg-[#d1e7dd] text-[#0f5132]">Completed</span>
                </td>
                <td class="px-4 py-3 text-gray-700">
                  <span v-if="req.expiration_at" :class="req.expired ? 'text-red-600 font-semibold' : ''">
                    {{ req.expiration_at }}{{ req.expired ? ' (Expired)' : '' }}
                  </span>
                  <span v-else>—</span>
                </td>
                <td class="px-4 py-3 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button type="button" @click="openModal(req)"
                      class="text-xl leading-none bg-transparent border-0 cursor-pointer transform transition hover:scale-110">⚙️</button>
                    <a v-if="req.completed_file && !req.expired"
                       :href="req.completed_file" target="_blank"
                       class="inline-flex px-3 py-1 rounded-full bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                      Download
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Completion Details</h3>
            <p class="text-sm text-gray-700 mb-1"><strong>Prepared By:</strong> {{ selected.prepared_by || '—' }}</p>
            <p class="text-sm text-gray-700 mb-3"><strong>Expires At:</strong> {{ selected.expiration_at || '—' }}</p>
            <a v-if="selected.completed_file && !selected.expired"
               :href="selected.completed_file" target="_blank"
               class="inline-flex px-4 py-2 rounded-full bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
              Download File
            </a>
            <p v-if="selected.expired" class="text-sm text-red-600 font-semibold mt-2">
              This request has expired. The file is no longer available.
            </p>
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
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  requests: { type: Array,  default: () => [] },
  authName: { type: String, default: 'Student' },
})

const menuOpen  = ref(false)
const modalOpen = ref(false)
const selected  = ref(null)
const route  = window.route
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const openModal  = (req) => { selected.value = req; modalOpen.value = true }
const closeModal = () => { modalOpen.value = false; selected.value = null }
</script>