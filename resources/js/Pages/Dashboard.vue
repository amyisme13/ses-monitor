<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="mx-auto max-w-7xl py-12 sm:px-6 lg:px-8">
      <h3 class="mb-4 px-2 text-2xl">{{ title }}</h3>
      <div
        class="divide-y bg-white flex flex-col divide-gray-300 shadow overflow-hidden sm:rounded-lg md:(divide-y-0 divide-x flex-row items-center)"
      >
        <StatItem text="Total Mails Sent" :value="stats.mails" :past-value="pastStats.mails" />

        <StatItem
          as-percentage
          text="Delivery Rate"
          :value="deliveryRate"
          :past-value="pastDeliveryRate"
        />

        <StatItem
          as-percentage
          reversed
          text="Bounce Rate"
          :value="bounceRate"
          :past-value="pastBounceRate"
        />
      </div>

      <h3 class="mt-8 mb-4 px-2 text-2xl">Latest Mails</h3>
      <MailList :mails="mails" />
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
  mails: number;
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
    pastStats: {
      type: Object as PropType<Stat>,
      required: true,
    },
    title: {
      type: String,
      default: 'Today',
    },
  },

  setup(props) {
    const toPercent = (num: number) => {
      return Math.round(num * 10000) / 100;
    };

    const deliveryRate = toPercent(props.stats.delivered / props.stats.total);
    const bounceRate = toPercent(props.stats.bounced / props.stats.total);
    const pastDeliveryRate = toPercent(props.pastStats.delivered / props.pastStats.total);
    const pastBounceRate = toPercent(props.pastStats.bounced / props.pastStats.total);

    return {
      deliveryRate: isNaN(deliveryRate) ? 0 : deliveryRate,
      bounceRate: isNaN(bounceRate) ? 0 : bounceRate,
      pastDeliveryRate: isNaN(pastDeliveryRate) ? 0 : pastDeliveryRate,
      pastBounceRate: isNaN(pastBounceRate) ? 0 : pastBounceRate,
    };
  },
});
</script>
