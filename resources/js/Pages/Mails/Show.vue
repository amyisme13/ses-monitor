<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight text-gray-800">Mails #{{ mail.id }}</h2>
    </template>

    <div class="mx-auto max-w-7xl py-12 sm:px-6 lg:px-8">
      <inertia-link
        class="bg-white rounded-md font-semibold shadow text-xs mb-2 ml-4 tracking-widest py-2 px-4 text-gray-700 inline-flex items-center uppercase sm:ml-0 hover:text-gray-500"
        :href="route('mails.index')"
      >
        <i-uil-arrow-left class="h-4 w-4" />
        Back
      </inertia-link>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="py-5 px-4 sm:px-6">
          <h3 class="font-medium text-lg text-gray-900 leading-6">Mail Details</h3>
        </div>
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-white py-5 px-4 sm:grid sm:px-6 sm:gap-4 sm:grid-cols-3">
              <dt class="font-medium text-sm text-gray-500">Subject</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ mail.subject }}</dd>
            </div>

            <div class="bg-white py-5 px-4 sm:grid sm:px-6 sm:gap-4 sm:grid-cols-3">
              <dt class="font-medium text-sm text-gray-500">From</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ mail.source_email }}
              </dd>
            </div>

            <div class="bg-white py-5 px-4 sm:grid sm:px-6 sm:gap-4 sm:grid-cols-3">
              <dt class="font-medium text-sm text-gray-500">Sent At</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ formatDate(mail.sent_at) }}
              </dd>
            </div>

            <div class="bg-white py-5 px-4 sm:grid sm:px-6 sm:gap-4 sm:grid-cols-3">
              <dt class="font-medium text-sm text-gray-500">Recipients</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                <ul class="space-y-2">
                  <li
                    v-for="recipient in mail.recipients"
                    :key="recipient.id"
                    class="flex text-sm items-center"
                  >
                    <i-uil-check
                      v-if="recipient.status === 'delivery'"
                      class="border rounded-full bg-green-500 flex-shrink-0 h-6 text-white p-0.5 w-6"
                    />
                    <i-uil-times
                      v-else-if="recipient.status === 'bounce'"
                      class="border rounded-full bg-red-500 flex-shrink-0 h-6 text-white p-0.5 w-6"
                    />
                    <i-uil-minus
                      v-else
                      class="border rounded-full bg-gray-500 flex-shrink-0 h-6 text-white p-0.5 w-6"
                    />

                    <span class="flex-1 ml-2 truncate">{{ recipient.email }}</span>
                  </li>
                </ul>
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script lang="ts">
import { format } from 'date-fns';
import { defineComponent } from 'vue';
import type { PropType } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import { Mail } from '@/components/Mail/MailItem.vue';

export default defineComponent({
  components: {
    AppLayout,
  },

  props: {
    mail: {
      type: Object as PropType<Mail>,
      required: true,
    },
  },

  setup() {
    const formatDate = (date: string) => format(new Date(date), 'd MMM yyyy HH:mm');

    return { formatDate, route: window.route };
  },
});
</script>
