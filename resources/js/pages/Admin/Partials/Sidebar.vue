<template>
  <aside class="w-[200px] bg-gradient-to-b from-[#4caf50] to-[#388e3c]
                py-[20px] shadow-[2px_0_10px_rgba(0,0,0,0.10)]">
    <nav class="flex flex-col gap-[8px] px-[15px] text-[14px] font-semibold">
      <Link v-for="item in navItems" :key="item.label" :href="route(item.route)"
         class="px-[20px] py-[12px] rounded-[10px] transition-all"
         :class="item.label === active
           ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
           : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]'">
        {{ item.label }}
      </Link>
    </nav>
  </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({ active: { type: String, default: '' } })

const route = window.route

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard'      },
  { label: 'Requests',  route: 'admin.requests.index' },
  { label: 'Books',     route: 'admin.books.index'    },
  { label: 'Users',     route: 'admin.users.index'    },
  { label: 'Settings',  route: 'admin.settings.index' },
]
</script>