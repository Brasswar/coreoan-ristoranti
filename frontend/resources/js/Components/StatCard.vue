<template>
  <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
    <div class="card-body">
      <div class="flex items-start justify-between">
        <div class="flex-1">
          <h3 class="card-title text-sm font-medium text-base-content/70 mb-2">
            {{ title }}
          </h3>
          <div class="flex items-baseline gap-2">
            <p class="text-4xl font-bold text-orange-500">
              {{ formattedValue }}
            </p>
            <span v-if="subtitle" class="text-sm text-base-content/60">
              {{ subtitle }}
            </span>
          </div>
        </div>

        <!-- Icon -->
        <div v-if="icon || $slots.icon" class="flex-shrink-0">
          <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
            <slot name="icon">
              <component
                v-if="icon"
                :is="icon"
                class="w-6 h-6 text-orange-500"
              />
            </slot>
          </div>
        </div>
      </div>

      <!-- Optional footer slot -->
      <div v-if="$slots.footer" class="mt-4 pt-4 border-t border-base-300">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  value: {
    type: [Number, String],
    required: true,
  },
  subtitle: {
    type: String,
    default: null,
  },
  icon: {
    type: [Object, String],
    default: null,
  },
  format: {
    type: String,
    default: 'number', // 'number', 'currency', 'percentage'
  },
});

const formattedValue = computed(() => {
  const val = props.value;

  switch (props.format) {
    case 'currency':
      return new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR',
      }).format(val);

    case 'percentage':
      return `${val}%`;

    case 'number':
    default:
      return new Intl.NumberFormat('it-IT').format(val);
  }
});
</script>

<style scoped>
.card {
  border: 1px solid transparent;
}

.card:hover {
  border-color: rgba(249, 115, 22, 0.1);
}
</style>
