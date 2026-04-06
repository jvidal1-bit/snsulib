<template>
  <div class="min-h-screen bg-[#e8e8e8]">
    <AdminNavbar :auth-name="authName" />

    <div class="flex min-h-[calc(100vh-60px)]">
      <AdminSidebar active="Requests" />

      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">

        <div class="mb-[30px] flex items-center justify-between gap-4">
          <div>
            <h1 class="text-[32px] text-[#333] font-semibold">Request Details</h1>
            <p class="text-sm text-gray-600 mt-1">Request ID: <span class="font-semibold">{{ bookRequest.id }}</span></p>
          </div>
          <Link :href="route('admin.requests.index')"
             class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                    bg-[#a5d6a7] text-black hover:bg-[#81c784] transition-colors">
            ← Back to Requests
          </Link>
        </div>

        <div v-if="$page.props.flash && $page.props.flash.status"
             class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm font-semibold">
          {{ $page.props.flash.status }}
        </div>

        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
          <div class="flex flex-col md:flex-row gap-[30px]">

            <!-- LEFT column -->
            <div class="flex-1 space-y-5">

              <!-- Student Info -->
              <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                <div class="bg-gradient-to-r from-[#b3e5fc] to-[#81d4fa] px-[15px] py-[15px] rounded-[8px] font-semibold mb-[15px]">
                  Request ID: {{ bookRequest.id }}
                </div>
                <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">Student Information:</h3>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Name:</strong> {{ bookRequest.student_name || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Email:</strong> {{ bookRequest.student_email || 'N/A' }}</p>
              </div>

              <!-- Book Info -->
              <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">Book Information:</h3>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Title:</strong> {{ bookRequest.book_title || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Author:</strong> {{ bookRequest.book_author || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Category:</strong> {{ bookRequest.book_category || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Year Published:</strong> {{ bookRequest.book_year || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">ISBN:</strong> {{ bookRequest.book_isbn || 'N/A' }}</p>
              </div>

              <!-- Request Details -->
              <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">Request Details:</h3>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Chapter:</strong> {{ bookRequest.chapter || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Purpose:</strong> {{ bookRequest.purpose || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Needed By:</strong> {{ bookRequest.needed_by || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Note:</strong> {{ bookRequest.note || '—' }}</p>
              </div>

              <!-- Timeline -->
              <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">Timeline:</h3>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Submitted:</strong> {{ bookRequest.submitted_at || 'N/A' }}</p>
                <p class="mb-[6px] text-[14px] text-[#555]"><strong class="text-[#333]">Current Status:</strong> <span class="capitalize font-semibold">{{ bookRequest.status || 'N/A' }}</span></p>
              </div>

              <!-- Update Form -->
              <form method="POST" :action="route('admin.requests.updateStatus', bookRequest.id)"
                    enctype="multipart/form-data" class="space-y-5 bg-[#f9f9f9] p-[20px] rounded-[12px]">
                <input type="hidden" name="_token" :value="csrf" />
                <h3 class="text-[16px] font-bold mb-[8px] text-[#2e7d32]">Update Request:</h3>

                <div>
                  <label class="block mb-2 text-[14px] font-semibold text-[#333]">Status:</label>
                  <select name="status" class="w-full px-[15px] py-[10px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none cursor-pointer">
                    <option v-for="s in statuses" :key="s" :value="s" :selected="bookRequest.status === s">{{ capitalize(s) }}</option>
                  </select>
                </div>

                <div>
                  <label class="block mb-2 text-[14px] font-semibold text-[#333]">Expiration Date (optional):</label>
                  <input type="date" name="expiration_at" :value="bookRequest.expiration_at_raw"
                         class="w-full px-[12px] py-[10px] border-2 border-[#a5d6a7] rounded-[8px] text-[14px] outline-none focus:border-[#66bb6a]" />
                </div>

                <div>
                  <h3 class="text-[16px] font-bold mb-[8px] text-[#2e7d32]">Upload Digital Copy ❤️:</h3>
                  <div class="px-[20px] py-[20px] border-2 border-dashed border-[#ccc] rounded-[12px] text-center">
                    <input type="file" name="file" ref="fileInput" class="hidden" accept=".pdf,.doc,.docx"
                           @change="uploadFileName = $event.target.files[0]?.name || 'No file selected'" />
                    <button type="button" @click="$refs.fileInput.click()"
                      class="px-[25px] py-[10px] bg-[#4caf50] text-white rounded-[8px] text-[15px] font-semibold
                             border-0 cursor-pointer transition-colors hover:bg-[#45a049]">Choose File</button>
                    <p class="mt-[10px] text-[14px] text-[#666]">{{ uploadFileName }}</p>
                    <div v-if="bookRequest.completed_file" class="mt-[10px]">
                      <a :href="bookRequest.completed_file" target="_blank"
                         class="text-[#2196f3] font-semibold hover:underline">View current file</a>
                    </div>
                  </div>
                </div>

                <div>
                  <h3 class="text-[16px] font-bold mb-[8px] text-[#2e7d32]">Admin Notes:</h3>
                  <textarea name="note" rows="3"
                    class="w-full px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[14px] resize-y outline-none focus:border-[#66bb6a]">{{ bookRequest.note }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-[12px]">
                  <Link :href="route('admin.requests.index')"
                     class="flex-1 inline-flex items-center justify-center px-[20px] py-[14px]
                            rounded-[8px] text-[15px] font-semibold bg-[#a5d6a7] text-black
                            hover:bg-[#81c784] transition-colors">Back</Link>
                  <button type="submit"
                    class="flex-1 px-[20px] py-[14px] rounded-[8px] text-[15px] font-semibold
                           bg-[#2e7d32] text-white hover:bg-[#1b5e20] transition-colors">Save Changes</button>
                </div>
              </form>

            </div>

            <!-- RIGHT: Book Cover -->
            <div class="flex-shrink-0 flex justify-center md:justify-start">
              <div class="w-[250px] h-[350px] bg-[#f5f5f5] rounded-[12px] overflow-hidden
                          shadow-[0_5px_15px_rgba(0,0,0,0.20)] flex items-center justify-center">
                <img v-if="bookRequest.cover_url" :src="bookRequest.cover_url"
                     class="w-full h-full object-cover" alt="Book Cover" />
                <div v-else class="flex flex-col items-center justify-center text-gray-400 text-sm">
                  <span class="text-4xl mb-1">📚</span>
                  <span>No cover uploaded</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </main>
    </div>

    <AdminFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminNavbar  from '@/pages/Admin/Partials/Navbar.vue'
import AdminSidebar from '@/pages/Admin/Partials/Sidebar.vue'
import AdminFooter  from '@/pages/Admin/Partials/FooterLogos.vue'

const props = defineProps({
  bookRequest: { type: Object, required: true },
  authName:    { type: String, default: 'Admin' },
})

const uploadFileName = ref('No file selected')
const fileInput      = ref(null)
const route = window.route
const csrf  = document.querySelector('meta[name="csrf-token"]')?.content

const statuses   = ['pending', 'processing', 'completed', 'expired']
const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : ''
</script>