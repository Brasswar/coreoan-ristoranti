<template>
  <div class="card bg-base-100 shadow-md hover:shadow-xl transition-all duration-300 border border-base-300">
    <div class="card-body p-4 sm:p-6">
      <!-- Header with status -->
      <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex-1">
          <h3 class="font-bold text-lg mb-1 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ reservation.customer_name }}
          </h3>
          <p v-if="reservation.customer_phone" class="text-sm text-base-content/70">
            {{ reservation.customer_phone }}
          </p>
          <p v-if="reservation.customer_email" class="text-sm text-base-content/70">
            {{ reservation.customer_email }}
          </p>
        </div>

        <!-- Status badge -->
        <div class="badge badge-lg gap-2" :class="statusClass">
          <div class="w-2 h-2 rounded-full" :class="statusDotClass"></div>
          {{ statusLabel }}
        </div>
      </div>

      <!-- Reservation details grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Date and time -->
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0 w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <p class="text-xs text-base-content/60 mb-1">Data e Ora</p>
            <p class="font-semibold">{{ formattedDate }}</p>
            <p class="text-sm text-orange-500 font-medium">{{ reservation.reservation_time }}</p>
          </div>
        </div>

        <!-- Number of people -->
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0 w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div>
            <p class="text-xs text-base-content/60 mb-1">Numero Persone</p>
            <p class="font-semibold">{{ reservation.number_of_people }} {{ reservation.number_of_people === 1 ? 'persona' : 'persone' }}</p>
          </div>
        </div>

        <!-- Table number (if assigned) -->
        <div v-if="reservation.table_number" class="flex items-start gap-3">
          <div class="flex-shrink-0 w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <p class="text-xs text-base-content/60 mb-1">Tavolo</p>
            <p class="font-semibold">Tavolo {{ reservation.table_number }}</p>
          </div>
        </div>

        <!-- Special requests -->
        <div v-if="reservation.special_requests" class="flex items-start gap-3 sm:col-span-2">
          <div class="flex-shrink-0 w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-xs text-base-content/60 mb-1">Richieste Speciali</p>
            <p class="text-sm">{{ reservation.special_requests }}</p>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="card-actions justify-end mt-6 pt-4 border-t border-base-300">
        <button
          v-if="showView"
          @click="$emit('view', reservation)"
          class="btn btn-sm btn-ghost gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          Visualizza
        </button>

        <button
          v-if="showEdit"
          @click="$emit('edit', reservation)"
          class="btn btn-sm btn-outline btn-primary gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          Modifica
        </button>

        <button
          v-if="showDelete"
          @click="$emit('delete', reservation)"
          class="btn btn-sm btn-outline btn-error gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          Elimina
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  reservation: {
    type: Object,
    required: true,
  },
  showView: {
    type: Boolean,
    default: true,
  },
  showEdit: {
    type: Boolean,
    default: true,
  },
  showDelete: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(['view', 'edit', 'delete']);

// Format date in Italian
const formattedDate = computed(() => {
  const date = new Date(props.reservation.reservation_date);
  return date.toLocaleDateString('it-IT', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
});

// Status mapping
const statusConfig = {
  pending: {
    label: 'In Attesa',
    class: 'badge-warning',
    dotClass: 'bg-warning',
  },
  confirmed: {
    label: 'Confermata',
    class: 'badge-success',
    dotClass: 'bg-success',
  },
  seated: {
    label: 'Seduti',
    class: 'badge-info',
    dotClass: 'bg-info',
  },
  completed: {
    label: 'Completata',
    class: 'badge-neutral',
    dotClass: 'bg-neutral',
  },
  cancelled: {
    label: 'Annullata',
    class: 'badge-error',
    dotClass: 'bg-error',
  },
  no_show: {
    label: 'Non Presentato',
    class: 'badge-error badge-outline',
    dotClass: 'bg-error',
  },
};

const statusLabel = computed(() => {
  return statusConfig[props.reservation.status]?.label || props.reservation.status;
});

const statusClass = computed(() => {
  return statusConfig[props.reservation.status]?.class || 'badge-ghost';
});

const statusDotClass = computed(() => {
  return statusConfig[props.reservation.status]?.dotClass || 'bg-base-300';
});
</script>

<style scoped>
.btn-primary {
  @apply border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white hover:border-orange-500;
}
</style>
