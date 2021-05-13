<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="divide-y bg-white flex flex-col divide-gray-300 shadow overflow-hidden sm:rounded-lg md:(divide-y-0 divide-x flex-row items-center)"
        >
          <StatItem text="Total Mails Sent" :value="stats.total" />
          <StatItem text="Delivery Rate" :value="`${deliveryRate}%`" />
          <StatItem text="Bounce Rate" :value="`${bounceRate}%`" />
        </div>

        <h3 class="mt-8 mb-4 px-2 text-2xl">Latest Mails</h3>

        <MailList :mails="mails" />
      </div>
    </div>
  </AppLayout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import type { PropType } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import MailList from '@/components/Mail/MailList.vue';
import { Mail } from '@/components/Mail/MailItem.vue';
import StatItem from '@/components/Stat/StatItem.vue';

interface Stat {
  total: number;
  bounced: number;
  delivered: number;
}

export default defineComponent({
  components: {
    AppLayout,
    MailList,
    StatItem,
  },

  props: {
    mails: {
      type: Array as PropType<Mail[]>,
      required: true,
    },
    stats: {
      type: Object as PropType<Stat>,
      required: true,
    },
  },

  setup(props) {
    const toPercent = (num: number) => {
      return Math.round(num * 10000) / 100;
    };

    const deliveryRate = toPercent(props.stats.delivered / props.stats.total);
    const bounceRate = toPercent(props.stats.bounced / props.stats.total);

    return { deliveryRate, bounceRate };
  },
});
</script>
