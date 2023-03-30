<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-gray-100">
    <body class="h-full">
    ```
  -->
  <div class="min-h-full">
    <Disclosure as="nav" class="bg-[#253B80]" v-slot="{ open }">
      <div class="max-w-screen 2xl:mx-20 xl:mx-8 lg:mx-4">
        <div class="flex items-center justify-between h-14">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <a href="https://bicol-u.edu.ph" target="_blank">
                <img class="h-8 w-30" src="/src/assets/bu-logo-2.png" alt="Bicol University" />
              </a>
            </div>
            <div class="ml-4 hidden lg2:block">
              <div class="flex space-x-1 items-baseline font-roboto">
                <router-link v-for="item in navigation" :key="item.name" :to="item.to" active-class="bg-[#1C2D60] text-white" :class="[this.$route.name === item.to.name ? '' : 'text-white hover:bg-[#213470] hover:text-slate-200 focus:outline-none focus:ring-2 focus:ring-white', 'px-2 py-2.5 rounded-md text-sm']" >{{ item.name }}</router-link>
              </div>
            </div>
          </div>
          <div class="hidden lg2:block">
            <div class="ml-4 flex items-right">

              <!-- Profile dropdown -->
              <Menu as="div" class="ml-3 relative">
                <div>
                  <MenuButton class="w-40 bg-[#1C2D60] hover:bg-[#213470] rounded-lg flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#3453B3] focus:ring-white">
                    <span class="sr-only">Open user menu</span>

                    <!-- <div class="mx-3">
                      <div
                        class="text-center text-base font-medium text-white"
                      >
                        {{ user.name }}
                      </div>
                    </div> -->

                    <div class="flex items-center pl-1.5 py-1">
                      <div class="flex-shrink-0">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-8 w-8"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="white"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          />
                        </svg>
                      </div>
                      <div class="ml-1 font-roboto leading-none">
                        <div :class="(userType == 'dept') ? 'text-xs' : 'text-base'" class="text-base text-white">
                          {{ user.name }}
                        </div>
                        <div class="text-xs text-gray-400">
                          {{ user.username }}
                        </div>
                      </div>
                    </div>
                    
                  </MenuButton>
                </div>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems
                    class="origin-top-right absolute right-0 mt-1 w-24 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <MenuItem v-slot="{}">
                      <a
                        @click="logout"
                        :class="[
                          'block py-2 rounded-md text-xs font-roboto text-center text-gray-500 cursor-pointer hover:bg-[#A4B5E6] hover:text-white',
                        ]"
                        >Sign out</a
                      >
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div class="-mr-2 flex lg2:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton class="bg-[#192855] inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-[#1E3066] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#1E3066] focus:ring-white">
              <span class="sr-only">Open main menu</span>
              <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
              <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="lg2:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <router-link v-for="item in navigation" :key="item.name" :to="item.to" active-class="bg-[#121E40] text-white" :class="[this.$route.name === item.to.name ? '' : 'text-slate-200 hover:bg-[#1E3066] hover:text-white', 'block px-3 py-2 rounded-md text-base font-roboto']">{{ item.name }}</router-link>
        </div>
        <div class="pt-4 pb-3 border-t border-[#1E3066]">
          <div class="flex items-center px-5">
            <div class="flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="white"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div class="ml-3 font-roboto leading-none">
              <div class="text-base text-white">
                {{ user.name }}
              </div>
              <div class="text-sm text-gray-400">
                {{ user.username }}
              </div>
            </div>
          </div>
          <div class="mt-3 px-2 space-y-1">
            <DisclosureButton
              as="a"
              @click="logout"
              class="block px-3 py-2 rounded-md text-base font-roboto text-gray-400 hover:text-white hover:bg-[#1E3066] cursor-pointer"
              >Sign out
            </DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <router-view> </router-view>
  </div>
</template>

<script>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import store from '../store'
import { computed, ref } from "vue";
import { useRouter } from "vue-router";

import APIController from '../controllers/api'

const navigation = [
  { name: 'Class Scheduler', to: { name: "Class Scheduler" } },
  { name: 'College Departments', to: { name: "College Departments" } },
  { name: 'Degree Programs', to: { name: "Courses" } },
  { name: 'Courses', to: { name: "Subjects" } },
  { name: 'Faculty', to: { name: "Faculty" } },
  { name: 'Classrooms', to: { name: "Classrooms" } },
  { name: 'Curriculum', to: { name: "Curriculum" } }
];

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    BellIcon,
    MenuIcon,
    XIcon,
  },
  setup() {
    // const store = useStore();
    const router = useRouter();

    function logout() {
      store.dispatch("logout").then(() => {
        router.push({
          name: "Login",
        });
      });
    }

    const college = ref({});
    const userId = ref({});
    const user = computed(() => store.state.user.data);

    const userType = ref('');
    

    localStorage.setItem('username', user.value.username);

    const GetCollege = async () => {
            college.value = await APIController.FetchCollege(localStorage.getItem('username'));
            userId.value = await APIController.FetchUser(localStorage.getItem('username'));
            localStorage.setItem('userID', userId.value.id);
            if(Object.keys(college.value).length == 3){
              localStorage.setItem('userType', 'reg');
              localStorage.setItem('collegeId', college.value.id);
            } else {
              localStorage.setItem('userType', 'dept');
              localStorage.setItem('collegeId', college.value.college_id);
            }
            userType.value = localStorage.getItem('userType');
    }

    return {
      user,
      navigation,
      logout,
      GetCollege,
      college,
      userType,
      userId
    }
  },
  mounted (){
    this.GetCollege();
    store.dispatch("getUser");
  }
}
</script>