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

        <div class="mb-[20px] flex items-center justify-between">
          <h1 class="text-[28px] font-semibold text-[#333]">Edit Book</h1>
          <Link :href="route('admin.books.index')"
             class="px-[16px] py-[8px] rounded-[8px] bg-[#a5d6a7] text-[#1b5e20]
                    text-[14px] font-semibold hover:bg-[#81c784] transition-colors">
            ← Back to Books
          </Link>
        </div>

        <!-- Flash -->
        <div v-if="$page.props.flash && $page.props.flash.status"
             class="mb-[15px] px-4 py-2 rounded-[10px] bg-green-100 text-green-800 text-sm">
          {{ $page.props.flash.status }}
        </div>

        <!-- Errors -->
        <div v-if="$page.props.errors && Object.keys($page.props.errors).length"
             class="mb-[15px] px-4 py-3 rounded-[10px] bg-red-100 text-red-700 text-sm">
          <ul class="list-disc list-inside space-y-1">
            <li v-for="(msg, key) in $page.props.errors" :key="key">{{ msg }}</li>
          </ul>
        </div>

        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
          <form method="POST" :action="route('admin.books.update', book.id)"
                enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="_method" value="PUT" />

            <div class="grid grid-cols-1 md:grid-cols-[auto,1fr] gap-[24px] mb-[20px]">
              <div class="flex justify-center md:justify-start">
                <div class="w-[150px] h-[200px] bg-[#f5f5f5] border border-gray-300
                            rounded-[12px] overflow-hidden flex items-center justify-center">
                  <img v-if="book.cover_url" :src="book.cover_url" class="w-full h-full object-cover" alt="Book Cover" />
                  <span v-else class="text-3xl">📚</span>
                </div>
              </div>
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Book Cover:</label>
                <input type="file" name="cover" accept="image/*"
                       class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
                <p class="text-xs text-gray-500">Optional. JPG, PNG, WEBP up to 2MB. Leave empty to keep current cover.</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">ISBN:</label>
                <input type="text" name="isbn" :value="book.isbn" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Title:</label>
                <input type="text" name="title" :value="book.title" required class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Author:</label>
                <input type="text" name="author" :value="book.author" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Publisher:</label>
                <input type="text" name="publisher" :value="book.publisher" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Year Published:</label>
                <input type="text" name="year_published" :value="book.year_published" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Category:</label>
                <select name="category_id" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]">
                  <option value="">No category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id" :selected="book.category_id === cat.id">{{ cat.name }}</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Or add a new category:</p>
                <input type="text" name="new_category" placeholder="e.g. Web Development" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Total Pages:</label>
                <input type="number" name="total_pages" :value="book.total_pages" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]" />
              </div>
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Status:</label>
                <select name="status" required class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none focus:border-[#66bb6a]">
                  <option value="available" :selected="book.status === 'available'">Available</option>
                  <option value="unavailable" :selected="book.status === 'unavailable'">Unavailable</option>
                </select>
              </div>
            </div>

            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Description:</label>
              <textarea name="description" rows="4" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[14px] resize-y outline-none focus:border-[#66bb6a]">{{ book.description }}</textarea>
            </div>

            <div class="flex flex-col gap-[8px]">
              <label class="font-semibold text-[14px] text-[#333]">Table of Contents:</label>
              <textarea name="table_of_contents" rows="4" placeholder="Chapter 1 - Introduction"
                        class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[14px] resize-y outline-none focus:border-[#66bb6a]">{{ book.table_of_contents }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-[12px] mt-[20px]">
              <Link :href="route('admin.books.index')"
                 class="flex-1 text-center px-[20px] py-[14px] rounded-[8px]
                        bg-[#a5d6a7] text-[#1b5e20] text-[15px] font-semibold
                        hover:bg-[#81c784] transition-colors">Cancel</Link>
              <button type="submit"
                class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                       bg-[#2e7d32] text-white hover:bg-[#1b5e20] transition-colors">Save Changes</button>
            </div>
          </form>
        </div>
      </main>
    </div>

    <!-- Footer -->
    <div class="flex justify-end items-center gap-[10px] px-[30px] py-[10px]">
      <span class="text-[12px] text-gray-500 mr-auto">For Nation's Greater High</span>
      <img :src="'/assets/images/snsu-logo.png'" class="h-[40px] w-[40px] rounded-full" />
      <img :src="'/assets/images/library-logo.png'" class="h-[40px] w-[40px] rounded-full" />
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  book:       { type: Object, required: true },
  categories: { type: Array,  default: () => [] },
  authName:   { type: String, default: 'Admin' },
})

const menuOpen = ref(false)
const route = window.route
const csrf  = document.querySelector('meta[name="csrf-token"]')?.content
const logout = () => router.post(route('logout'))
document.addEventListener('click', () => { menuOpen.value = false })

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard',      active: false },
  { label: 'Requests',  route: 'admin.requests.index', active: false },
  { label: 'Books',     route: 'admin.books.index',    active: true  },
  { label: 'Users',     route: 'admin.users.index',    active: false },
  { label: 'Settings',  route: 'admin.settings.index', active: false },
]
</script>