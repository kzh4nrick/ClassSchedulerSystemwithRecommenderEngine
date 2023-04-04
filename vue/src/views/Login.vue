<!--
  This example requires Tailwind CSS v2.0+ 
  
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
<template>
    <div class="border-2 rounded-2xl">
      <div class="bg-[#253B80] px-5 py-5 rounded-t-2xl">
        <img class="mx-auto h-12 w-auto" src="/src/assets/bu-logo-2.png" alt="Bicol University" />
        
      </div><h2 class="mt-6 text-4xl text-center drop-shadow-[0_1px_1px_rgba(0,155,222,0.6)] tracking-wider font-bebas font-bold uppercase text-[#253B80]">Class Scheduling System</h2>
      <form class="space-y-6 m-7" @submit="login">
        <Alert v-if="errorMsg">
          {{ errorMsg }}
          <span
            @click="errorMsg = ''"
            class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </span>
        </Alert>
        <input type="hidden" name="remember" value="true" />
        <div class="shadow-sm -space-y-px">
          <div>
            <label for="username" class="sr-only">Username</label>
            <input id="username" name="username" type="text" autocomplete="username" required="" v-model.trim="user.username"
                    class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 text-center font-roboto rounded-t-xl focus:outline-none focus:ring-[#3B5FCC] focus:border-[#3B5FCC] focus:z-10 text-sm" placeholder="Username" />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required="" v-model.trim="user.password"
                    class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 text-center font-roboto rounded-b-xl focus:outline-none focus:ring-[#3B5FCC] focus:border-[#3B5FCC] focus:z-10 text-sm" placeholder="Password" />
          </div>
        </div>
        <div>
          <button
                  type="submit"
                  :disabled="loading"
                  class="group relative w-full flex justify-center py-0.5 border border-transparent text-lg tracking-widest font-bebas rounded-xl text-white bg-[#253B80] hover:bg-[#3B5FCC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3453B3]"
                  :class="{
                    'cursor-not-allowed': loading,
                    'hover:bg-sky-500': loading,
                  }"
                >            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <LockClosedIcon class="h-5 w-5 text-[#3B5FCC] group-hover:text-[#253B80]" aria-hidden="true" />
            </span>
            LOG IN
          </button>
        </div>
      </form>
    </div>
</template>

<script setup>
import { LockClosedIcon } from "@heroicons/vue/solid";
import store from "../store";
import { useRouter } from "vue-router";
import { ref } from "vue";
import Alert from "../components/Alert.vue";

const router = useRouter();

const user = {
  username: "",
  password: "",
};
let loading = ref(false);
let errorMsg = ref('');

function login(ev) {
  ev.preventDefault();
  loading.value = true;
  store.dispatch("login", user)
    .then(() => {
      loading.value = false;
      router.push({
        name: 'Class Scheduler',
      });
    })
    .catch(err => {
      loading.value = false;
      errorMsg.value = err.data.error;
      // console.log(err);
    });
}
</script>