<template>
  <div class="flex-1 py-4 px-8 relative">
    <p class="font-semibold text-lg text-gray-700">{{ text }}</p>
    <p class="text-2xl text-indigo-500">
      <span v-if="asPercentage">{{ value }}%</span>
      <span v-else>{{ value }}</span>

      <span v-if="pastValue !== undefined" class="text-sm text-gray-500">
        from
        <span v-if="asPercentage">{{ pastValue }}%</span>
        <span v-else>{{ pastValue }}</span>
      </span>
    </p>

    <span
      v-if="value !== undefined && pastValue !== undefined"
      class="rounded-full flex text-sm pr-2 pl-1 right-8 bottom-4 absolute items-center justify-center"
      :class="color"
    >
      <i-uil-arrow-up v-if="changes > 0" />
      <i-uil-minus v-else-if="changes === 0" />
      <i-uil-arrow-down v-else />

      <span v-if="asPercentage">{{ changes }}%</span>
      <span v-else>{{ changes }}</span>
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';

export default defineComponent({
  props: {
    asPercentage: Boolean,
    reversed: Boolean,
    text: String,
    value: Number,
    pastValue: Number,
  },
  setup(props) {
    const changes = computed(() => {
      if (props.value !== undefined && props.pastValue !== undefined) {
        return props.value - props.pastValue;
      }

      return 0;
    });

    const color = computed(() => {
      if (changes.value === 0) {
        return 'bg-gray-200 text-gray-600';
      }

      const isGreen = props.reversed ? changes.value < 0 : changes.value > 0;
      return isGreen ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600';
    });

    return { changes, color };
  },
});
</script>
