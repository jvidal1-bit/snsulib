<template>
  <div class="min-h-screen bg-gray-100 pb-20">

    <!-- Navbar -->
    <nav class="w-full bg-[#c8e6c9] shadow-md">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">
          <div class="font-semibold text-lg tracking-wide text-[#1b5e20]">
            SNSU LIBRARY E-REQUEST
          </div>
          <div class="flex items-center gap-6 text-sm font-semibold text-gray-800">
            <a :href="route('student.home')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">
              Home
            </a>
            <a :href="route('student.catalog')"
               class="px-2 py-1 border-b-2 border-[#1b5e20] text-[#1b5e20]">
              Catalog
            </a>
            <a :href="route('student.requests.index')"
               class="px-2 py-1 border-b-2 border-transparent hover:border-[#81c784]">
              My Request
            </a>
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
                @click.stop
                class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border text-sm z-50"
              >
                <a :href="route('student.profile')"
                   class="flex items-center px-3 py-2 hover:bg-gray-100">
                  <span class="mr-2">👤</span> Profile
                </a>
                <button
                  type="button"
                  @click="logout"
                  class="w-full text-left flex items-center px-3 py-2 text-red-600 hover:bg-gray-100"
                >
                  <span class="mr-2">🚪</span> Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8">

      <!-- Flash status -->
      <div v-if="$page.props.flash && $page.props.flash.status"
           class="mb-4 bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
        {{ $page.props.flash.status }}
      </div>

      <!-- Errors -->
      <div v-if="$page.props.errors && Object.keys($page.props.errors).length"
           class="mb-4 bg-red-100 text-red-800 text-sm px-4 py-3 rounded-lg">
        <p v-for="(msg, key) in $page.props.errors" :key="key">{{ msg }}</p>
      </div>

      <!-- Search Bar -->
      <div class="mb-8">
        <form method="GET" :action="route('student.catalog')" class="max-w-[500px] relative">
          <input
            type="text"
            name="q"
            :value="q"
            placeholder="Search books..."
            class="w-full py-[15px] pl-5 pr-12 border-2 border-gray-300 rounded-full
                   text-base outline-none focus:border-[#2e7d32] transition"
          />
          <button type="submit"
                  class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-0 text-2xl cursor-pointer px-2">
            🔍
          </button>
        </form>
      </div>

      <!-- Books Grid -->
      <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5">
        <div v-if="books.data.length === 0"
             class="col-span-4 bg-white rounded-xl shadow p-6 text-center text-gray-500">
          No books found{{ q ? ` for "${q}"` : '' }}.
        </div>

        <div
          v-for="book in books.data"
          :key="book.id"
          class="bg-white rounded-xl p-5 shadow-md transition transform
                 hover:-translate-y-1 hover:shadow-2xl flex flex-col justify-between"
        >
          <div>
            <!-- Cover -->
            <div class="mb-3 w-full aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden
                        flex items-center justify-center">
              <img
                v-if="book.cover_url"
                :src="book.cover_url"
                alt="Book Cover"
                class="w-full h-full object-cover"
              />
              <div v-else class="flex flex-col items-center justify-center text-gray-400 text-xs">
                <span class="text-3xl mb-1">📚</span>
                <span>No cover</span>
              </div>
            </div>

            <h3 class="text-sm font-semibold mb-1 min-h-[40px]">{{ book.title }}</h3>
            <p class="text-xs text-gray-600 mb-3">Author: {{ book.author ?? 'Unknown' }}</p>
            <p class="text-[11px] text-gray-500">ISBN: {{ book.isbn ?? '—' }}</p>
            <p class="text-[11px] text-gray-500">Category: {{ book.category ?? 'Uncategorized' }}</p>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex items-center justify-between gap-2">
            <span
              class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold"
              :class="book.is_unavailable
                ? 'bg-red-100 text-red-700'
                : 'bg-green-100 text-green-700'"
            >
              {{ book.status }}
            </span>
            <button
              type="button"
              class="flex-1 px-3 py-2 bg-white border border-gray-300 rounded-md text-xs font-medium
                     hover:bg-gray-100 hover:border-[#2e7d32] transition"
              :class="book.is_unavailable ? 'opacity-60 cursor-not-allowed' : ''"
              :disabled="book.is_unavailable"
              @click="!book.is_unavailable && openBook(book)"
            >
              View
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="books.last_page > 1" class="mt-8 flex justify-center gap-2">
        <a
          v-for="page in books.last_page"
          :key="page"
          :href="`${route('student.catalog')}?page=${page}${q ? '&q=' + q : ''}`"
          class="px-3 py-1 rounded-md text-sm border"
          :class="page === books.current_page
            ? 'bg-[#2e7d32] text-white border-[#2e7d32]'
            : 'bg-white text-gray-700 hover:bg-gray-100'"
        >
          {{ page }}
        </a>
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

    <!-- Book View Modal -->
    <div
      v-if="bookModalOpen"
      class="fixed inset-0 flex items-center justify-center bg-black/40 z-40"
      @click.self="closeBook"
    >
      <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full mx-4 p-6 relative">
        <div class="flex flex-col md:flex-row gap-6">
          <!-- Cover -->
          <div class="w-full md:w-1/3">
            <div class="border rounded-xl h-52 flex items-center justify-center
                        bg-gray-50 overflow-hidden">
              <img
                v-if="selectedBook && selectedBook.cover_url"
                :src="selectedBook.cover_url"
                alt="Book Cover"
                class="w-full h-full object-cover"
              />
              <div v-else class="flex flex-col items-center justify-center text-gray-400 text-xs">
                <span class="text-4xl mb-1">📚</span>
                <span>No cover uploaded</span>
              </div>
            </div>
          </div>

          <!-- Book Info -->
          <div class="w-full md:w-2/3" v-if="selectedBook">
            <h2 class="text-xl font-semibold mb-2">Book Title: {{ selectedBook.title }}</h2>
            <p class="text-sm"><strong>Author:</strong> {{ selectedBook.author || 'Unknown' }}</p>
            <p class="text-sm"><strong>Publisher:</strong> {{ selectedBook.publisher || '—' }}</p>
            <p class="text-sm"><strong>Year Published:</strong> {{ selectedBook.year_published || '—' }}</p>
            <p class="text-sm"><strong>ISBN:</strong> {{ selectedBook.isbn || '—' }}</p>
            <p class="text-sm"><strong>Category:</strong> {{ selectedBook.category || '—' }}</p>
            <p class="text-sm"><strong>Total pages:</strong> {{ selectedBook.total_pages || '—' }}</p>
            <p class="text-sm mb-2"><strong>Status:</strong> {{ selectedBook.status || 'Available' }}</p>

            <h3 class="font-semibold mt-3 mb-1">Description:</h3>
            <p class="text-sm text-gray-700 max-h-32 overflow-y-auto">
              {{ selectedBook.description || 'No description available.' }}
            </p>

            <h3 class="font-semibold mt-3 mb-1">Table of Contents:</h3>
            <div class="text-sm text-gray-700 max-h-32 overflow-y-auto whitespace-pre-line">
              {{ selectedBook.table_of_contents || 'No table of contents available.' }}
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button
            type="button"
            class="px-4 py-2 rounded-lg border text-sm font-medium hover:bg-gray-100"
            @click="closeBook"
          >Back</button>
          <button
            type="button"
            class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white
                   hover:bg-blue-700 disabled:opacity-50"
            :disabled="!selectedBook || selectedBook.is_unavailable"
            @click="openRequest"
          >Request</button>
        </div>
      </div>
    </div>

    <!-- Request Form Modal -->
    <div
      v-if="requestModalOpen"
      class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
      @click.self="closeRequest"
    >
      <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
        <h2 class="text-lg font-semibold mb-4">Request Form:</h2>

        <form method="POST" :action="route('student.requests.store')">
          <input type="hidden" name="_token" :value="csrf" />
          <input type="hidden" name="book_id" :value="selectedBook?.id" />

          <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Book Title:</label>
            <input
              type="text"
              class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100"
              :value="selectedBook?.title"
              readonly
            />
          </div>

          <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Chapter/Section:</label>
            <input
              type="text"
              name="chapter"
              class="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Chapter/Section:"
              required
            />
          </div>

          <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Purpose:</label>
            <input
              type="text"
              name="purpose"
              class="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Purpose:"
              required
            />
          </div>

          <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Needed By:</label>
            <input
              type="date"
              name="needed_by"
              class="w-full border rounded-lg px-3 py-2 text-sm"
              required
            />
          </div>

          <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Note:</label>
            <input
              type="text"
              name="note"
              class="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Note (optional):"
            />
          </div>

          <div class="flex gap-3 mt-4">
            <button
              type="button"
              class="flex-1 py-3 px-4 rounded-full text-sm font-semibold
                     bg-[#a5d6a7] text-black hover:bg-[#81c784] transition"
              @click="closeRequest"
            >Back</button>
            <button
              type="submit"
              class="flex-1 py-3 px-4 rounded-full text-sm font-semibold
                     bg-[#2e7d32] text-white hover:bg-[#1b5e20] transition"
            >Submit Request</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  books:    { type: Object, default: () => ({ data: [], last_page: 1, current_page: 1 }) },
  q:        { type: String, default: '' },
  authName: { type: String, default: 'Student' },
})

const menuOpen       = ref(false)
const bookModalOpen  = ref(false)
const requestModalOpen = ref(false)
const selectedBook   = ref(null)
const route = window.route
const csrf  = document.querySelector('meta[name="csrf-token"]')?.content

const logout = () => router.post(route('logout'))

document.addEventListener('click', () => { menuOpen.value = false })

const openBook = (book) => {
  selectedBook.value = book
  bookModalOpen.value = true
  requestModalOpen.value = false
}

const closeBook = () => {
  bookModalOpen.value = false
}

const openRequest = () => {
  if (!selectedBook.value) return
  if (selectedBook.value.is_unavailable) {
    alert('This book is currently unavailable for requests.')
    return
  }
  requestModalOpen.value = true
}

const closeRequest = () => {
  requestModalOpen.value = false
}
</script>