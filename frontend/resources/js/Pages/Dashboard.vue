<template>
  <AppLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-base-content mb-2">Dashboard</h1>
          <p class="text-base-content/60">
            Panoramica delle prenotazioni e gestione del ristorante
          </p>
        </div>
        <Link
          :href="route('reservations.index')"
          class="btn btn-primary bg-gradient-to-r from-orange-500 to-orange-600 border-0 text-white hover:from-orange-600 hover:to-orange-700 shadow-lg hover:shadow-xl gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nuova Prenotazione
        </Link>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Today's Reservations -->
      <StatCard
        title="Prenotazioni Oggi"
        :value="stats.today || 0"
        subtitle="prenotazioni"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </template>
        <template #footer>
          <div class="flex items-center gap-2 text-sm">
            <div class="flex items-center gap-1 text-success">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
              <span class="font-medium">{{ currentDayName }}</span>
            </div>
          </div>
        </template>
      </StatCard>

      <!-- Tomorrow's Reservations -->
      <StatCard
        title="Prenotazioni Domani"
        :value="stats.tomorrow || 0"
        subtitle="prenotazioni"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </template>
        <template #footer>
          <div class="flex items-center gap-2 text-sm">
            <div class="flex items-center gap-1 text-info">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
              <span class="font-medium">{{ tomorrowDayName }}</span>
            </div>
          </div>
        </template>
      </StatCard>

      <!-- This Week's Reservations -->
      <StatCard
        title="Questa Settimana"
        :value="stats.thisWeek || 0"
        subtitle="prenotazioni"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </template>
        <template #footer>
          <div class="flex items-center gap-2 text-sm">
            <div class="flex items-center gap-1 text-warning">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <span class="font-medium">7 giorni</span>
            </div>
          </div>
        </template>
      </StatCard>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Calendar - Takes 2 columns on large screens -->
      <div class="lg:col-span-2">
        <div class="mb-4">
          <h2 class="text-xl font-bold text-base-content mb-2">Calendario Prenotazioni</h2>
          <p class="text-sm text-base-content/60">
            Visualizza e gestisci le prenotazioni per data
          </p>
        </div>
        <Calendar
          :reservations="calendarData"
          @dateSelected="handleDateSelected"
        />
      </div>

      <!-- Upcoming Reservations - Takes 1 column on large screens -->
      <div class="lg:col-span-1">
        <div class="mb-4">
          <h2 class="text-xl font-bold text-base-content mb-2">Prossime Prenotazioni</h2>
          <p class="text-sm text-base-content/60">
            Le prenotazioni più vicine
          </p>
        </div>

        <!-- Upcoming reservations list -->
        <div class="space-y-4">
          <template v-if="upcomingReservations.length > 0">
            <div
              v-for="reservation in upcomingReservations"
              :key="reservation.id"
              class="card bg-base-100 shadow-md hover:shadow-lg transition-all border border-base-300 hover:border-orange-200"
            >
              <div class="card-body p-4">
                <div class="flex items-start justify-between gap-2 mb-3">
                  <div class="flex-1">
                    <h3 class="font-bold text-base mb-1 flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      {{ reservation.customer_name }}
                    </h3>
                    <p class="text-xs text-base-content/60">
                      {{ formatDate(reservation.reservation_date) }}
                    </p>
                  </div>
                  <div class="badge badge-sm" :class="getStatusBadgeClass(reservation.status)">
                    {{ getStatusLabel(reservation.status) }}
                  </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                  <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 text-orange-500 font-medium">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ reservation.reservation_time }}
                    </div>
                    <div class="flex items-center gap-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      {{ reservation.number_of_people }}
                    </div>
                  </div>
                  <Link
                    :href="route('reservations.index')"
                    class="link link-hover text-orange-500 text-xs flex items-center gap-1"
                  >
                    Dettagli
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </Link>
                </div>
              </div>
            </div>
          </template>

          <!-- Empty state -->
          <div v-else class="card bg-base-100 shadow-md">
            <div class="card-body p-8 text-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-base-content/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-base-content/60 text-sm">
                Nessuna prenotazione in programma
              </p>
              <Link
                :href="route('reservations.index')"
                class="btn btn-sm btn-outline btn-primary mt-4"
              >
                Crea prenotazione
              </Link>
            </div>
          </div>
        </div>

        <!-- View all link -->
        <div v-if="upcomingReservations.length > 0" class="mt-4 text-center">
          <Link
            :href="route('reservations.index')"
            class="btn btn-outline btn-primary btn-sm w-full"
          >
            Visualizza tutte le prenotazioni
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Calendar from '@/Components/Calendar.vue';

const props = defineProps({
  stats: {
    type: Object,
    required: true,
    default: () => ({
      today: 0,
      tomorrow: 0,
      thisWeek: 0,
    }),
  },
  calendarData: {
    type: Array,
    default: () => [],
  },
});

// Get upcoming reservations (sorted by date/time, limit to 5)
const upcomingReservations = computed(() => {
  const now = new Date();
  return props.calendarData
    .filter(reservation => {
      const resDateTime = new Date(`${reservation.reservation_date}T${reservation.reservation_time}`);
      return resDateTime >= now;
    })
    .sort((a, b) => {
      const dateA = new Date(`${a.reservation_date}T${a.reservation_time}`);
      const dateB = new Date(`${b.reservation_date}T${b.reservation_time}`);
      return dateA - dateB;
    })
    .slice(0, 5);
});

// Current day name
const currentDayName = computed(() => {
  const options = { weekday: 'long' };
  return new Date().toLocaleDateString('it-IT', options);
});

// Tomorrow day name
const tomorrowDayName = computed(() => {
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  const options = { weekday: 'long' };
  return tomorrow.toLocaleDateString('it-IT', options);
});

// Format date in Italian
const formatDate = (dateString) => {
  const date = new Date(dateString);
  const options = {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
  };
  return date.toLocaleDateString('it-IT', options);
};

// Get status label
const getStatusLabel = (status) => {
  const statusMap = {
    pending: 'In Attesa',
    confirmed: 'Confermata',
    seated: 'Seduti',
    completed: 'Completata',
    cancelled: 'Annullata',
    no_show: 'Non Presentato',
  };
  return statusMap[status] || status;
};

// Get status badge class
const getStatusBadgeClass = (status) => {
  const classMap = {
    pending: 'badge-warning',
    confirmed: 'badge-success',
    seated: 'badge-info',
    completed: 'badge-neutral',
    cancelled: 'badge-error',
    no_show: 'badge-error badge-outline',
  };
  return classMap[status] || 'badge-ghost';
};

// Handle date selection from calendar
const handleDateSelected = (date) => {
  // Navigate to reservations page with selected date filter
  router.visit(route('reservations.index', {
    date: date.toISOString().split('T')[0]
  }));
};
</script>

<style scoped>
.btn-primary {
  @apply border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white hover:border-orange-500;
}
</style>
