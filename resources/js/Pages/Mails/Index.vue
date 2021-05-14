<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight text-gray-800">Mails</h2>
    </template>

    <div class="mx-auto max-w-7xl py-12 sm:px-6 lg:px-8">
      <div class="flex max-w-md mb-4 px-4 sm:px-0">
        <select class="border-none rounded-l shadow pr-8 focus:z-10" v-model="form.field">
          <option value="subject">Subject</option>
          <option value="recipient">Recipient</option>
        </select>

        <input
          class="border-none rounded-r shadow w-full"
          type="search"
          placeholder="Search..."
          v-model="form.search"
        />
      </div>

      <MailList :mails="mails.data" />

      <Pagination
        class="px-4 sm:px-0 mt-4 justify-end"
        :next="mails.next_page_url"
        :prev="mails.prev_page_url"
      />
    </div>
  </AppLayout>
</template>

<script lang="ts">
import { Inertia } from '@inertiajs/inertia';
import { throttle, pickBy } from 'lodash';
import { defineComponent, reactive, watch } from 'vue';
import type { PropType } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import MailList from '@/components/Mail/MailList.vue';
import { Mail } from '@/components/Mail/MailItem.vue';
import Pagination from '@/components/Pagination.vue';

export default defineComponent({
  components: {
    AppLayout,
    MailList,
    Pagination,
  },

  props: {
    mails: {
      type: Object as PropType<CursorPaginated<Mail>>,
      required: true,
    },
    filters: {
      type: Object as PropType<{ field: string; search: string }>,
      required: true,
    },
  },

  setup(props) {
    const form = reactive({
      field: props.filters.field ?? 'subject',
      search: props.filters.search,
    });

    watch(
      form,
      throttle((form) => {
        Inertia.get(window.route('mails.index'), pickBy(form), { preserveState: true });
      }, 150),
      { deep: true }
    );

    return { form };
  },
});
</script>
