<template>
  <div class="min-h-screen bg-[#e8e8e8]">
    <AdminNavbar :auth-name="authName" />

    <div class="flex min-h-[calc(100vh-60px)]">
      <AdminSidebar active="Books" />

      <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
        <div class="mb-[20px] flex items-center justify-between">
          <h1 class="text-[28px] font-semibold text-[#333]">Edit Book</h1>
          <Link :href="route('admin.books.index')"
             class="px-[16px] py-[8px] rounded-[8px] bg-[#a5d6a7] text-[#1b5e20]
                    text-[14px] font-semibold hover:bg-[#81c784] transition-colors">
            ← Back to Books
          </Link>
        </div>

        <div v-if="$page.props.flash && $page.props.flash.status"
             class="mb-[15px] px-4 py-2 rounded-[10px] bg-green-100 text-green-800 text-sm">
          {{ $page.props.flash.status }}
        </div>
        <div v-if="$page.props.errors && Object.keys($page.props.errors).length"
             class="mb-[15px] px-4 py-3 rounded-[10px] bg-red-100 text-red-700 text-sm">
          <ul class="list-disc list-inside space-y-1">
            <li v-for="(msg, key) in $page.props.errors" :key="key">{{ msg }}</li>
          </ul>
        </div>

        <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
          <form method="POST" :action="route('admin.books.update', book.id)" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="_token" :value="csrf" />
            <input type="hidden" name="_method" value="PUT" />
            <div class="grid grid-cols-1 md:grid-cols-[auto,1fr] gap-[24px] mb-[20px]">
              <div class="flex justify-center md:justify-start">
                <div class="w-[150px] h-[200px] bg-[#f5f5f5] border border-gray-300 rounded-[12px] overflow-hidden flex items-center justify-center">
                  <img v-if="book.cover_url" :src="book.cover_url" class="w-full h-full object-cover" alt="Book Cover" />
                  <span v-else class="text-3xl">📚</span>
                </div>
              </div>
              <div class="flex flex-col gap-[8px]">
                <label class="font-semibold text-[14px] text-[#333]">Book Cover:</label>
                <input type="file" name="cover" accept="image/*" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[15px] outline-none" />
                <p class="text-xs text-gray-500">Optional. Leave empty to keep current cover.</p>
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
              <textarea name="table_of_contents" rows="4" class="px-[12px] py-[12px] border-2 border-[#a5d6a7] rounded-[8px] text-[14px] resize-y outline-none focus:border-[#66bb6a]">{{ book.table_of_contents }}</textarea>
            </div>
            <div class="flex flex-col sm:flex-row gap-[12px] mt-[20px]">
              <Link :href="route('admin.books.index')"
                 class="flex-1 text-center px-[20px] py-[14px] rounded-[8px] bg-[#a5d6a7] text-[#1b5e20]
                        text-[15px] font-semibold hover:bg-[#81c784] transition-colors">Cancel</Link>
              <button type="submit"
                class="flex-1 px-[20px] py-[14px] rounded-[8px] border-0 text-[15px] font-semibold
                       bg-[#2e7d32] text-white hover:bg-[#1b5e20] transition-colors">Save Changes</button>
            </div>
          </form>
        </div>
      </main>
    </div>

    <AdminFooter />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminNavbar  from '@/pages/Admin/Partials/Navbar.vue'
import AdminSidebar from '@/pages/Admin/Partials/Sidebar.vue'
import AdminFooter  from '@/pages/Admin/Partials/FooterLogos.vue'

const props = defineProps({
  book:       { type: Object, required: true },
  categories: { type: Array,  default: () => [] },
  authName:   { type: String, default: 'Admin' },
})

const route = window.route
const csrf  = document.querySelector('meta[name="csrf-token"]')?.content
</script>