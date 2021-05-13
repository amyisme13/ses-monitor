<template>
  <div class="px-8 py-4 flex-1">
    <p class="text-lg text-gray-700 font-semibold">{{ text }}</p>
    <p class="text-2xl text-indigo-500">{{ value }}</p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  props: {
    text: String,
    value: [String, Number],
  },
});
</script>
