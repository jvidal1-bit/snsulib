<template>
  <div class="fixed bottom-[20px] right-[20px] flex items-center gap-[10px] z-[100]">
    <span class="text-[14px] font-semibold italic mr-[10px]">
      For Nation's Greater High
    </span>
    <div class="w-[40px] h-[40px] rounded-full bg-cover bg-center bg-no-repeat shadow-sm"
         :style="{ backgroundImage: `url('/assets/images/snsu-logo.png')` }"></div>
    <div class="w-[40px] h-[40px] rounded-full bg-cover bg-center bg-no-repeat shadow-sm"
         :style="{ backgroundImage: `url('/assets/images/library-logo.png')` }"></div>
  </div>
</template>

<script setup>
// No logic needed
</script>