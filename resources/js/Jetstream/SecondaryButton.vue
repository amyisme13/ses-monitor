<template>
  <button
    :type="type"
    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition"
  >
    <slot></slot>
  </button>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import type { PropType, ButtonHTMLAttributes } from 'vue';

export default defineComponent({
  props: {
    type: {
      type: String as PropType<ButtonHTMLAttributes['type']>,
      default: 'button',
    },
  },
});
</script>
