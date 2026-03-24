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
          <a v-for="item in navItems" :key="item.label" :href="route(item.route)"
             class="px-[20px] py-[12px] rounded-[10px] transition-all"
             :class="item.active
               ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
               : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]'">
            {{ item.label }}
          </a>
        </nav>
      </aside>

      <!-- Main -->
      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">

        <!-- Header -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center
                    justify-between gap-[20px] mb-[30px]">
          <!-- Search -->
          <div class="flex-1 max-w-[600px] relative flex items-center bg-white
                      rounded-full px-[20px] py-[5px] shadow-[0_3px_10px_rgba(0,0,0,0.10)]">
            <form method="GET" :action="route('admin.books.index')" class="flex items-center w-full">
              <button type="submit" class="bg-transparent border-0 text-[20px] mr-[10px] cursor-pointer">🔍</button>
              <input type="text" name="q" :value="q" placeholder="Search books..."
                     class="flex-1 border-none outline-none py-[10px] text-[16px] bg-transparent" />
            </form>
          </div>

          <!-- Add New Book -->
          <button type="button" @click="addBookOpen = true"
            class="px-[30px] py-[12px] bg-[#4caf50] text-white rounded-[8px]
                   text-[16px] font-semibold whitespace-nowrap border-0 cursor-pointer
                   transition-colors hover:bg-[#45a049]">
            Add New Book
          </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
          <h2 class="text-[24px] mb-[20px] text-[#333]">Manage Books</h2>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse text-[14px]">
              <thead>
                <tr class="bg-[#e0e0e0]">
                  <th class="px-[15px] py-[15px] text-left font-semibold">Book No./ISBN</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Title</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Author</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Category</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Year-Pub</th>
                  <th class="px-[15px] py-[15px] text-left font-semibold">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="books.data.length === 0">
                  <td colspan="6" class="px-[15px] py-[15px] text-center text-gray-500">No books found.</td>
                </tr>
                <tr v-for="book in books.data" :key="book.id"
                    class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                  <td class="px-[15px] py-[15px]">{{ book.isbn ?? '-' }}</td>
                  <td class="px-[15px] py-[15px]">{{ book.title }}</td>
                  <td class="px-[15px] py-[15px]">{{ book.author ?? '-' }}</td>
                  <td class="px-[15px] py-[15px]">{{ book.category ?? '-' }}</td>
                  <td class="px-[15px] py-[15px]">{{ book.year_published ?? '-' }}</td>
                  <td class="px-[15px] py-[15px] space-x-2">
                    <button type="button" @click="openView(book)"
                      class="inline-flex items-center justify-center text-[20px]
                             w-8 h-8 rounded-full bg-transparent border-0 cursor-pointer
                             transition-transform hover:scale-110" title="View Book">👁️</button>
                    <a :href="route('admin.books.edit', book.id)"
                       class="inline-flex items-center justify-center text-[20px]
                              w-8 h-8 rounded-full cursor-pointer
                              transition-transform hover:scale-110" title="Edit Book">✏️</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="books.last_page > 1" class="mt-[20px] flex gap-2 flex-wrap">
            <a v-for="page in books.last_page" :key="page"
               :href="`${route('admin.books.index')}?page=${page}${q ? '&q=' + q : ''}`"
               class="px-3 py-1 rounded-md text-sm border"
               :class="page === books.current_page
                 ? 'bg-[#2e7d32] text-white border-[#2e7d32]'
                 : 'bg-white text-gray-700 hover:bg-gray-100'">
              {{ page }}
            </a>
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

    <!-- Book View Modal -->
    <div v-if="bookViewOpen" @click.self="bookViewOpen = false"
         class="fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-[20px] overflow-y-auto">
      <div @click.stop class="bg-white rounded-[20px] p-[40px] w-full max-w-[900px]
                              max-h-[90vh] overflow-y-auto relative
                              shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
        <button type="button" @click="bookViewOpen = false"
          class="absolute top-[20px] right-[20px] bg-transparent border-0 text-[32px] text-[#666]
                 w-[40px] h-[40px] flex items-center justify-center rounded-full cursor-pointer
                 transition-all hover:bg-[#f5f5f5] hover:text-[#333]">×</button>

        <div v-if="selectedBook">
          <div class="flex flex-col md:flex-row gap-[30px] mb-[30px]">
            <!-- Cover -->
            <div class="flex-shrink-0 flex justify-center md:justify-start">
              <div class="w-[200px] h-[280px] bg-[#f5f5f5] rounded-[12px] overflow-hidden
                          shadow-[0_5px_15px_rgba(0,0,0,0.20)] flex items-center justify-center">
                <img v-if="selectedBook.cover_url" :src="selectedBook.cover_url"
                     class="w-full h-full object-cover" alt="Book Cover" />
                <span v-else class="text-[40px]">📚</span>
              </div>
            </div>

            <!-- Details -->
            <div class="flex-1">
              <h2 class="text-[22px] mb-[15px]">
                <span class="font-semibold">Book Title:</span> {{ selectedBook.title }}
              </h2>
              <p class="mb-[8px] text-[14px]"><strong>Author:</strong> {{ selectedBook.author || '-' }}</p>
              <p class="mb-[8px] text-[14px]"><strong>Publisher:</strong> {{ selectedBook.publisher || '-' }}</p>
              <p class="mb-[8px] text-[14px]"><strong>Year Published:</strong> {{ selectedBook.year_published || '-' }}</p>
              <p class="mb-[8px] text-[14px]"><strong>ISBN:</strong> {{ selectedBook.isbn || '-' }}</p>
              <p class="mb-[8px] text-[14px]"><strong>Category:</strong> {{ selectedBook.category || '-' }}</p>
              <p class="mb-[8px] text-[14px]"><strong>Total pages:</strong> {{ selectedBook.total_pages || '-' }}</p>
              <p class="mb-[8px] text-[14px]"><strong>Status:</strong> {{ selectedBook.status || 'available' }}</p>

              <h3 class="text-[16px] font-bold mt-[20px] mb-[10px] text-[#2e7d32]">Description:</h3>
              <p class="text-[14px] text-[#555] leading-relaxed">
                {{ selectedBook.description || 'No description available.' }}
              </p>

              <h3 class="text-[16px] font-bold mt-[20px] mb-[10px] text-[#2e7d32]">Table of Contents:</h3>
              <pre class="text-[14px] text-[#555] whitespace-pre-wrap">{{ selectedBook.table_of_contents || 'No table of contents available.' }}</pre>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
            <button type="button" @click="bookViewOpen = false"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#a5d6a7] text-black cursor-pointer transition-colors hover:bg-[#81c784]">
              Close
            </button>
            <form :action="`${baseUrl}/admin/books/${selectedBook.id}`" method="POST" class="flex-1"
                  @submit.prevent="confirmDelete">
              <input type="hidden" name="_token" :value="csrf" />
              <input type="hidden" name="_method" value="DELETE" />
              <button type="submit"
                class="w-full px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                       bg-red-600 text-white cursor-pointer transition-colors hover:bg-red-700">
                Delete Book
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Add New Book Modal -->
    <div v-if="addBookOpen" @click.self="addBookOpen = false"
         class="fixed inset-0 bg-black/50 z-[2000] flex items-center justify-center p-[20px] overflow-y-auto">
      <div @click.stop class="bg-white rounded-[20px] p-[40px] w-full max-w-[700px]
                              max-h-[90vh] overflow-y-auto relative
                              shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
        <button type="button" @click="addBookOpen = false"
          class="absolute top-[20px] right-[20px] bg-transparent border-0 text-[32px] text-[#666]
                 w-[40px] h-[40px] flex items-center justify-center rounded-full cursor-pointer
                 transition-all hover:bg-[#f5f5f5] hover:text-[#333]">×</button>

        <h2 class="text-[24px] mb-[25px] text-[#333]">Add New Book</h2>

        <form method="POST" :action="route('admin.books.store')"
              enctype="multipart/form-data" class="flex flex-col gap-[20px]">
          <input type="hidden" name="_token" :value="csrf" />

          <!-- Cover upload -->
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[14px] text-[#333]">Book Cover:</label>
            <div class="flex flex-col items-center gap-[15px]">
              <div class="w-[150px] h-[200px] bg-[#f5f5f5] border-2 border-dashed border-[#ccc]
                          rounded-[12px] flex flex-col items-center justify-center gap-[10px] overflow-hidden">
                <img v-if="coverPreview" :src="coverPreview" class="w-full h-full object-cover" alt="Preview" />
                <div v-else class="flex flex-col items-center gap-[10px]">
                  <span class="text-[48px]">📚</span>
                  <p class="text-[12px] text-[#666]">Upload Book Cover</p>
                </div>
              </div>
              <input type="file" name="cover" accept="image/*" class="hidden" ref="coverInput"
                     @change="handleCoverChange" />
              <button type="button" @click="$refs.coverInput.click()"
                class="px-[25px] py-[10px] bg-[#4caf50] text-white rounded-[8px]
                       text-[14px] font-semibold border-0 cursor-pointer
                       transition-colors hover:bg-[#45a049]">
                Choose Image
              </button>
            </div>
          </div>

          <!-- ISBN + Title -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">ISBN:</label>
              <input type="text" name="isbn" required
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Title:</label>
              <input type="text" name="title" required
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
          </div>

          <!-- Author + Publisher -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Author:</label>
              <input type="text" name="author"
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Publisher:</label>
              <input type="text" name="publisher"
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
          </div>

          <!-- Year + Category -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Year Published:</label>
              <input type="text" name="year_published"
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Category:</label>
              <select name="category_id"
                      class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                             text-[15px] outline-none focus:border-[#66bb6a]">
                <option value="">Choose category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
              <p class="mt-1 text-xs text-gray-500">Or add a new category:</p>
              <input type="text" name="new_category" placeholder="e.g. Web Development"
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
          </div>

          <!-- Total Pages + Status -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Total Pages:</label>
              <input type="number" name="total_pages"
                     class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                            text-[15px] outline-none focus:border-[#66bb6a]" />
            </div>
            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Status:</label>
              <select name="status" required
                      class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                             text-[15px] outline-none focus:border-[#66bb6a]">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
              </select>
            </div>
          </div>

          <!-- Description -->
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[14px] text-[#333]">Description:</label>
            <textarea name="description" rows="4"
                      class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                             text-[14px] resize-y outline-none focus:border-[#66bb6a]"></textarea>
          </div>

          <!-- Table of Contents -->
          <div class="flex flex-col gap-[8px]">
            <label class="font-semibold text-[14px] text-[#333]">Table of Contents:</label>
            <textarea name="table_of_contents" rows="4" placeholder="Chapter 1 - Introduction"
                      class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px]
                             text-[14px] resize-y outline-none focus:border-[#66bb6a]"></textarea>
          </div>

          <!-- Actions -->
          <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
            <button type="button" @click="addBookOpen = false"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#a5d6a7] text-black cursor-pointer transition-colors hover:bg-[#81c784]">
              Cancel
            </button>
            <button type="submit"
              class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                     bg-[#2e7d32] text-white cursor-pointer transition-colors hover:bg-[#1b5e20]">
              Save Book
            </button>
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
  books:      { type: Object, default: () => ({ data: [], last_page: 1, current_page: 1 }) },
  categories: { type: Array,  default: () => [] },
  q:          { type: String, default: '' },
  authName:   { type: String, default: 'Admin' },
})

const menuOpen      = ref(false)
const addBookOpen   = ref(false)
const bookViewOpen  = ref(false)
const selectedBook  = ref(null)
const coverPreview  = ref(null)
const coverInput    = ref(null)
const route   = window.route
const csrf    = document.querySelector('meta[name="csrf-token"]')?.content
const baseUrl = window.location.origin

const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const openView = (book) => {
  selectedBook.value = book
  bookViewOpen.value = true
}

const handleCoverChange = (event) => {
  const file = event.target.files[0]
  if (!file) { coverPreview.value = null; return }
  const reader = new FileReader()
  reader.onload = (e) => { coverPreview.value = e.target.result }
  reader.readAsDataURL(file)
}

const confirmDelete = (event) => {
  if (confirm('Delete this book? This cannot be undone.')) {
    event.target.submit()
  }
}

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard',      active: false },
  { label: 'Requests',  route: 'admin.requests.index', active: false },
  { label: 'Books',     route: 'admin.books.index',    active: true  },
  { label: 'Users',     route: 'admin.users.index',    active: false },
  { label: 'Settings',  route: 'admin.settings.index', active: false },
]
</script>