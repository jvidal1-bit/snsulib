<template>
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 -z-10"
         style="background-image: url('/assets/images/library-bg.jpg');
                background-size: cover; background-position: center; opacity: 0.25;"></div>

    <div class="w-full max-w-[950px] bg-gradient-to-br from-green-200 to-green-300
                rounded-[32px] shadow-2xl p-10 flex flex-col md:flex-row gap-10">

      <div class="flex-1">
        <h1 class="text-3xl md:text-4xl font-extrabold text-center mb-8">
          SNSU LIBRARY E-REQUEST
        </h1>

        <div v-if="form.errors.login"
             class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded-full text-center text-sm font-medium">
          {{ form.errors.login }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <input v-model="form.login" type="text" placeholder="Username or Email" required
                   class="w-full px-5 py-3 rounded-full border-2 border-gray-300
                          focus:border-green-700 focus:ring-0 focus:outline-none" />
          </div>
          <div>
            <input v-model="form.password" type="password" placeholder="Password" required
                   class="w-full px-5 py-3 rounded-full border-2 border-gray-300
                          focus:border-green-700 focus:ring-0 focus:outline-none" />
          </div>
          <button type="submit" :disabled="form.processing"
                  class="w-full py-3 bg-green-700 text-white rounded-full font-bold
                         shadow hover:bg-green-800 transition disabled:opacity-50">
            <span v-if="form.processing">Signing in...</span>
            <span v-else>Sign in</span>
          </button>
          <div class="text-center text-gray-600 font-medium">or</div>
          <button type="button" disabled
                  class="w-full py-3 bg-white border border-gray-300 rounded-full font-semibold
                         flex items-center justify-center gap-2 opacity-70 cursor-not-allowed">
            <span class="font-bold text-lg text-red-500">G</span> Sign in with Google
          </button>
        </form>
      </div>

      <div class="flex-1 text-right flex flex-col justify-center select-none">
        <h2 class="text-3xl md:text-4xl font-bold mb-2">Request</h2>
        <h2 class="text-6xl md:text-7xl italic font-bold text-gray-800 mb-2">Read</h2>
        <h2 class="text-4xl md:text-5xl font-bold">Learn</h2>
      </div>
    </div>

    <div class="fixed bottom-5 right-5 flex items-center gap-3">
      <span class="italic font-semibold text-sm">For Nation's Greater Heights</span>
      <div class="w-12 h-12 rounded-full bg-white border border-gray-300 bg-center bg-cover"
           :style="{ backgroundImage: 'url(/assets/images/snsu-logo.png)' }"></div>
      <div class="w-12 h-12 rounded-full bg-white border border-gray-300 bg-center bg-cover"
           :style="{ backgroundImage: 'url(/assets/images/library-logo.png)' }"></div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  old_login: { type: String, default: '' },
})

const form = useForm({
  login: props.old_login,
  password: '',
})

const submit = () => {
  form.post(route('login.attempt'), {
    onFinish: () => form.reset('password'),
  })
}
</script>