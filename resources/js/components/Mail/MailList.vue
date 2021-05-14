<template>
  <div class="divide-y bg-white divide-gray-300 shadow overflow-hidden sm:rounded-lg">
    <MailItem v-for="mail in mails" :key="mail.id" :mail="mail" />
    <div v-if="mails.length === 0" class="p-8 text-center text-lg text-gray-600">
      <p>No Data</p>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import type { PropType } from 'vue';

import MailItem, { Mail } from './MailItem.vue';

export default defineComponent({
  components: { MailItem },

  props: {
    mails: {
      type: Array as PropType<Mail[]>,
      required: true,
    },
  },
});
</script>
