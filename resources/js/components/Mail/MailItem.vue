<template>
  <div class="flex py-4 px-4 sm:px-8 gap-4 items-center">
    <div class="rounded-full flex h-8 text-white w-8 items-center justify-center" :class="color">
      <i-uil-check v-if="color === 'bg-green-500'" />
      <i-uil-times v-else-if="color === 'bg-red-500'" />
      <i-uil-exclamation v-else-if="color === 'bg-yellow-500'" />
      <i-uil-minus v-else />
    </div>

    <div class="flex-1">
      <p class="font-semibold line-clamp-1">{{ mail.subject }}</p>
      <p class="text-gray-600 line-clamp-1">{{ recipients }}</p>
    </div>
    <div>{{ sentAt }}</div>
  </div>
</template>

<script lang="ts">
import { isToday, format } from 'date-fns';
import { defineComponent, computed } from 'vue';
import type { PropType } from 'vue';

export interface MailRecipient {
  id: number;
  created_at: string;
  email: string;
  status: string;
  resolved_at?: string;
}

export interface Mail {
  id: number;
  created_at: string;
  message_id: string;
  subject: string;
  source_email: string;
  sent_at: string;
  recipients: MailRecipient[];
}

export default defineComponent({
  props: {
    mail: {
      type: Object as PropType<Mail>,
      required: true,
    },
  },
  setup(props) {
    const recipients = props.mail.recipients.map((v) => v.email).join(', ');

    const color = computed(() => {
      const someBounced = props.mail.recipients.some((v) => v.status === 'bounce');
      const someDelivered = props.mail.recipients.some((v) => v.status === 'delivery');
      const someUnknown = props.mail.recipients.some((v) => v.status === 'unknown');
      if (someBounced && !someDelivered && !someUnknown) {
        return 'bg-red-500';
      }

      if (someDelivered && !someBounced && !someUnknown) {
        return 'bg-green-500';
      }

      if (someBounced) {
        return 'bg-yellow-500';
      }

      return 'bg-gray-500';
    });

    const sentAt = computed(() => {
      const date = new Date(props.mail.sent_at);
      if (isToday(date)) {
        return format(date, 'HH:mm');
      }

      return format(date, 'd MMM');
    });

    return { recipients, color, sentAt };
  },
});
</script>
