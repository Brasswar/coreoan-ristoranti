<template>
  <div class="card bg-base-100 shadow-lg">
    <div class="card-body p-4 sm:p-6">
      <!-- Calendar header -->
      <div class="flex items-center justify-between mb-6">
        <button
          @click="previousMonth"
          class="btn btn-sm btn-circle btn-ghost hover:bg-orange-50 hover:text-orange-500"
          aria-label="Mese precedente"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <h2 class="text-xl font-bold text-center">
          {{ currentMonthName }} {{ currentYear }}
        </h2>

        <button
          @click="nextMonth"
          class="btn btn-sm btn-circle btn-ghost hover:bg-orange-50 hover:text-orange-500"
          aria-label="Mese successivo"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <!-- Weekday headers -->
      <div class="grid grid-cols-7 gap-1 mb-2">
        <div
          v-for="day in weekDays"
          :key="day"
          class="text-center text-xs font-semibold text-base-content/70 py-2"
        >
          {{ day }}
        </div>
      </div>

      <!-- Calendar grid -->
      <div class="grid grid-cols-7 gap-1">
        <button
          v-for="day in calendarDays"
          :key="day.dateString"
          @click="selectDate(day)"
          :disabled="!day.currentMonth"
          :class="[
            'relative aspect-square p-1 rounded-lg transition-all duration-200',
            'flex flex-col items-center justify-center',
            'hover:bg-orange-50 hover:scale-105',
            {
              'text-base-content/30': !day.currentMonth,
              'bg-orange-500 text-white hover:bg-orange-600': day.isSelected,
              'ring-2 ring-orange-500 ring-offset-2': day.isToday && !day.isSelected,
              'font-bold': day.isToday,
              'cursor-not-allowed opacity-50': !day.currentMonth,
              'cursor-pointer': day.currentMonth,
            }
          ]"
        >
          <span class="text-sm sm:text-base">{{ day.day }}</span>

          <!-- Reservation count badge -->
          <span
            v-if="day.reservationCount > 0 && day.currentMonth"
            :class="[
              'absolute bottom-1 right-1 text-[10px] font-bold',
              'w-5 h-5 rounded-full flex items-center justify-center',
              day.isSelected
                ? 'bg-white text-orange-500'
                : 'bg-orange-500 text-white'
            ]"
          >
            {{ day.reservationCount }}
          </span>
        </button>
      </div>

      <!-- Legend -->
      <div class="flex flex-wrap gap-4 mt-6 pt-4 border-t border-base-300 text-xs">
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 rounded border-2 border-orange-500"></div>
          <span>Oggi</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 rounded bg-orange-500"></div>
          <span>Selezionato</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 rounded bg-orange-500 text-white text-[8px] flex items-center justify-center font-bold">
            3
          </div>
          <span>Prenotazioni</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  reservations: {
    type: Array,
    default: () => [],
  },
  selectedDate: {
    type: [String, Date],
    default: null,
  },
  modelValue: {
    type: [String, Date],
    default: null,
  },
});

const emit = defineEmits(['dateSelected', 'update:modelValue']);

// Italian month and day names
const monthNames = [
  'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
  'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'
];

const weekDays = ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'];

// Current view state
const currentDate = ref(new Date());
const currentMonth = ref(currentDate.value.getMonth());
const currentYear = ref(currentDate.value.getFullYear());

// Selected date state
const selectedDateValue = ref(props.selectedDate || props.modelValue);

// Watch for external changes to selectedDate or modelValue
watch(() => props.selectedDate, (newVal) => {
  if (newVal) selectedDateValue.value = newVal;
});

watch(() => props.modelValue, (newVal) => {
  if (newVal) selectedDateValue.value = newVal;
});

// Computed properties
const currentMonthName = computed(() => monthNames[currentMonth.value]);

const today = computed(() => {
  const now = new Date();
  return new Date(now.getFullYear(), now.getMonth(), now.getDate());
});

// Get reservation count for a specific date
const getReservationCount = (dateString) => {
  return props.reservations.filter(reservation => {
    const resDate = new Date(reservation.reservation_date);
    return resDate.toISOString().split('T')[0] === dateString;
  }).length;
};

// Generate calendar days
const calendarDays = computed(() => {
  const days = [];
  const firstDay = new Date(currentYear.value, currentMonth.value, 1);
  const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0);

  // Adjust for Monday as first day of week (getDay returns 0 for Sunday)
  let firstDayOfWeek = firstDay.getDay();
  firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;

  // Previous month days
  const prevMonthLastDay = new Date(currentYear.value, currentMonth.value, 0);
  for (let i = firstDayOfWeek - 1; i >= 0; i--) {
    const day = prevMonthLastDay.getDate() - i;
    const date = new Date(currentYear.value, currentMonth.value - 1, day);
    days.push({
      day,
      date,
      dateString: date.toISOString().split('T')[0],
      currentMonth: false,
      isToday: false,
      isSelected: false,
      reservationCount: 0,
    });
  }

  // Current month days
  for (let day = 1; day <= lastDay.getDate(); day++) {
    const date = new Date(currentYear.value, currentMonth.value, day);
    const dateString = date.toISOString().split('T')[0];
    const isToday = date.getTime() === today.value.getTime();
    const isSelected = selectedDateValue.value &&
      new Date(selectedDateValue.value).toISOString().split('T')[0] === dateString;

    days.push({
      day,
      date,
      dateString,
      currentMonth: true,
      isToday,
      isSelected,
      reservationCount: getReservationCount(dateString),
    });
  }

  // Next month days to fill the grid
  const remainingDays = 42 - days.length; // 6 weeks * 7 days
  for (let day = 1; day <= remainingDays; day++) {
    const date = new Date(currentYear.value, currentMonth.value + 1, day);
    days.push({
      day,
      date,
      dateString: date.toISOString().split('T')[0],
      currentMonth: false,
      isToday: false,
      isSelected: false,
      reservationCount: 0,
    });
  }

  return days;
});

// Methods
const previousMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value--;
  } else {
    currentMonth.value--;
  }
};

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value++;
  } else {
    currentMonth.value++;
  }
};

const selectDate = (day) => {
  if (!day.currentMonth) return;

  selectedDateValue.value = day.dateString;
  emit('dateSelected', day.date);
  emit('update:modelValue', day.dateString);
};
</script>

<style scoped>
/* Custom scrollbar for mobile if needed */
@media (max-width: 640px) {
  .card-body {
    overflow-x: auto;
  }
}
</style>
