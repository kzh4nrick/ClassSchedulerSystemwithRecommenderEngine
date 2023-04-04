<template>
  <div class="h-screen flex items-center justify-center">
      <router-view></router-view>
  </div>
</template>

<script>
import { LockClosedIcon } from '@heroicons/vue/solid'
export default {
  components: {
    LockClosedIcon,
  },
}
</script>