<template>
  <AppLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-base-content mb-2">Prenotazioni</h1>
          <p class="text-base-content/60">
            Gestisci tutte le prenotazioni del ristorante
          </p>
        </div>
        <button
          @click="openCreateModal"
          class="btn btn-primary bg-gradient-to-r from-orange-500 to-orange-600 border-0 text-white hover:from-orange-600 hover:to-orange-700 shadow-lg hover:shadow-xl gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nuova Prenotazione
        </button>
      </div>
    </div>

    <!-- Filters Bar -->
    <div class="card bg-base-100 shadow-lg mb-6">
      <div class="card-body p-6">
        <form @submit.prevent="applyFilters" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Date Filter -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Data</span>
              </label>
              <input
                v-model="filterForm.date"
                type="date"
                class="input input-bordered focus:input-primary focus:border-orange-500"
              />
            </div>

            <!-- Shift Filter -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Turno</span>
              </label>
              <select
                v-model="filterForm.shift"
                class="select select-bordered focus:select-primary focus:border-orange-500"
              >
                <option value="">Tutti i turni</option>
                <option value="lunch">Pranzo</option>
                <option value="dinner">Cena</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Stato</span>
              </label>
              <select
                v-model="filterForm.status"
                class="select select-bordered focus:select-primary focus:border-orange-500"
              >
                <option value="">Tutti gli stati</option>
                <option value="pending">In Attesa</option>
                <option value="confirmed">Confermata</option>
                <option value="seated">Seduti</option>
                <option value="completed">Completata</option>
                <option value="cancelled">Annullata</option>
                <option value="no_show">Non Presentato</option>
              </select>
            </div>

            <!-- Search Filter -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Cerca</span>
              </label>
              <input
                v-model="filterForm.search"
                type="text"
                placeholder="Nome, telefono, email..."
                class="input input-bordered focus:input-primary focus:border-orange-500"
              />
            </div>
          </div>

          <!-- Filter Actions -->
          <div class="flex flex-wrap gap-2 justify-end">
            <button
              type="button"
              @click="resetFilters"
              class="btn btn-ghost btn-sm gap-2"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Resetta Filtri
            </button>
            <button
              type="submit"
              class="btn btn-primary btn-sm gap-2 bg-orange-500 hover:bg-orange-600 border-0"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Applica Filtri
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block card bg-base-100 shadow-lg mb-6">
      <div class="overflow-x-auto">
        <table class="table table-zebra">
          <thead>
            <tr class="bg-base-200">
              <th>Ora</th>
              <th>Cliente</th>
              <th>Persone</th>
              <th>Tavolo</th>
              <th>Stato</th>
              <th class="text-right">Azioni</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="reservations.data && reservations.data.length > 0">
              <tr v-for="reservation in reservations.data" :key="reservation.id" class="hover">
                <td>
                  <div class="flex flex-col">
                    <span class="font-semibold text-orange-500">{{ reservation.reservation_time }}</span>
                    <span class="text-xs text-base-content/60">{{ formatDate(reservation.reservation_date) }}</span>
                  </div>
                </td>
                <td>
                  <div class="flex flex-col">
                    <span class="font-semibold">{{ reservation.customer_name }}</span>
                    <span class="text-xs text-base-content/60">{{ reservation.customer_phone }}</span>
                    <span v-if="reservation.customer_email" class="text-xs text-base-content/60">{{ reservation.customer_email }}</span>
                  </div>
                </td>
                <td>
                  <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-semibold">{{ reservation.number_of_people }}</span>
                  </div>
                </td>
                <td>
                  <span v-if="reservation.table_number" class="badge badge-outline badge-lg">
                    Tavolo {{ reservation.table_number }}
                  </span>
                  <span v-else class="text-base-content/40">-</span>
                </td>
                <td>
                  <div class="badge badge-lg gap-2" :class="getStatusBadgeClass(reservation.status)">
                    <div class="w-2 h-2 rounded-full" :class="getStatusDotClass(reservation.status)"></div>
                    {{ getStatusLabel(reservation.status) }}
                  </div>
                </td>
                <td>
                  <div class="flex gap-2 justify-end">
                    <button
                      @click="openEditModal(reservation)"
                      class="btn btn-sm btn-outline btn-primary gap-2"
                      title="Modifica"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                      Modifica
                    </button>
                    <button
                      @click="openDeleteConfirmation(reservation)"
                      class="btn btn-sm btn-outline btn-error gap-2"
                      title="Elimina"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Elimina
                    </button>
                  </div>
                </td>
              </tr>
            </template>
            <tr v-else>
              <td colspan="6" class="text-center py-12">
                <div class="flex flex-col items-center gap-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p class="text-base-content/60">Nessuna prenotazione trovata</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden space-y-4 mb-6">
      <template v-if="reservations.data && reservations.data.length > 0">
        <ReservationCard
          v-for="reservation in reservations.data"
          :key="reservation.id"
          :reservation="reservation"
          @edit="openEditModal"
          @delete="openDeleteConfirmation"
        />
      </template>
      <div v-else class="card bg-base-100 shadow-md">
        <div class="card-body p-8 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-base-content/60">Nessuna prenotazione trovata</p>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="reservations.data && reservations.data.length > 0" class="flex justify-center">
      <div class="btn-group">
        <Link
          v-for="link in reservations.links"
          :key="link.label"
          :href="link.url || '#'"
          :class="[
            'btn',
            {
              'btn-active bg-orange-500 text-white border-orange-500': link.active,
              'btn-disabled': !link.url,
            }
          ]"
          v-html="link.label"
        />
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <dialog ref="reservationModal" class="modal modal-bottom sm:modal-middle">
      <div class="modal-box max-w-2xl">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 class="font-bold text-2xl mb-6">
          {{ isEditing ? 'Modifica Prenotazione' : 'Nuova Prenotazione' }}
        </h3>

        <form @submit.prevent="submitReservation" class="space-y-4">
          <!-- Customer Name -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Nome Cliente *</span>
            </label>
            <input
              v-model="reservationForm.customer_name"
              type="text"
              placeholder="Mario Rossi"
              class="input input-bordered focus:input-primary focus:border-orange-500"
              :class="{ 'input-error': reservationForm.errors.customer_name }"
              required
            />
            <label v-if="reservationForm.errors.customer_name" class="label">
              <span class="label-text-alt text-error">{{ reservationForm.errors.customer_name }}</span>
            </label>
          </div>

          <!-- Customer Phone -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Telefono *</span>
            </label>
            <input
              v-model="reservationForm.customer_phone"
              type="tel"
              placeholder="+39 123 456 7890"
              class="input input-bordered focus:input-primary focus:border-orange-500"
              :class="{ 'input-error': reservationForm.errors.customer_phone }"
              required
            />
            <label v-if="reservationForm.errors.customer_phone" class="label">
              <span class="label-text-alt text-error">{{ reservationForm.errors.customer_phone }}</span>
            </label>
          </div>

          <!-- Customer Email -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Email</span>
            </label>
            <input
              v-model="reservationForm.customer_email"
              type="email"
              placeholder="mario.rossi@esempio.it"
              class="input input-bordered focus:input-primary focus:border-orange-500"
              :class="{ 'input-error': reservationForm.errors.customer_email }"
            />
            <label v-if="reservationForm.errors.customer_email" class="label">
              <span class="label-text-alt text-error">{{ reservationForm.errors.customer_email }}</span>
            </label>
          </div>

          <!-- Date and Time Row -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Reservation Date -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Data *</span>
              </label>
              <input
                v-model="reservationForm.reservation_date"
                type="date"
                class="input input-bordered focus:input-primary focus:border-orange-500"
                :class="{ 'input-error': reservationForm.errors.reservation_date }"
                required
              />
              <label v-if="reservationForm.errors.reservation_date" class="label">
                <span class="label-text-alt text-error">{{ reservationForm.errors.reservation_date }}</span>
              </label>
            </div>

            <!-- Reservation Time -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Ora *</span>
              </label>
              <input
                v-model="reservationForm.reservation_time"
                type="time"
                class="input input-bordered focus:input-primary focus:border-orange-500"
                :class="{ 'input-error': reservationForm.errors.reservation_time }"
                required
              />
              <label v-if="reservationForm.errors.reservation_time" class="label">
                <span class="label-text-alt text-error">{{ reservationForm.errors.reservation_time }}</span>
              </label>
            </div>
          </div>

          <!-- Number of People and Table Row -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Number of People -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Numero Persone *</span>
              </label>
              <input
                v-model.number="reservationForm.number_of_people"
                type="number"
                min="1"
                max="50"
                placeholder="2"
                class="input input-bordered focus:input-primary focus:border-orange-500"
                :class="{ 'input-error': reservationForm.errors.number_of_people }"
                required
              />
              <label v-if="reservationForm.errors.number_of_people" class="label">
                <span class="label-text-alt text-error">{{ reservationForm.errors.number_of_people }}</span>
              </label>
            </div>

            <!-- Table Number -->
            <div class="form-control">
              <label class="label">
                <span class="label-text font-semibold">Tavolo</span>
              </label>
              <select
                v-model="reservationForm.table_number"
                class="select select-bordered focus:select-primary focus:border-orange-500"
                :class="{ 'select-error': reservationForm.errors.table_number }"
              >
                <option :value="null">Nessun tavolo assegnato</option>
                <option v-for="table in tables" :key="table.id" :value="table.number">
                  Tavolo {{ table.number }} ({{ table.capacity }} posti)
                </option>
              </select>
              <label v-if="reservationForm.errors.table_number" class="label">
                <span class="label-text-alt text-error">{{ reservationForm.errors.table_number }}</span>
              </label>
            </div>
          </div>

          <!-- Status -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Stato *</span>
            </label>
            <select
              v-model="reservationForm.status"
              class="select select-bordered focus:select-primary focus:border-orange-500"
              :class="{ 'select-error': reservationForm.errors.status }"
              required
            >
              <option value="pending">In Attesa</option>
              <option value="confirmed">Confermata</option>
              <option value="seated">Seduti</option>
              <option value="completed">Completata</option>
              <option value="cancelled">Annullata</option>
              <option value="no_show">Non Presentato</option>
            </select>
            <label v-if="reservationForm.errors.status" class="label">
              <span class="label-text-alt text-error">{{ reservationForm.errors.status }}</span>
            </label>
          </div>

          <!-- Special Requests -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Richieste Speciali</span>
            </label>
            <textarea
              v-model="reservationForm.special_requests"
              class="textarea textarea-bordered h-24 focus:textarea-primary focus:border-orange-500"
              :class="{ 'textarea-error': reservationForm.errors.special_requests }"
              placeholder="Es: Allergie, preferenze di tavolo, occasioni speciali..."
            ></textarea>
            <label v-if="reservationForm.errors.special_requests" class="label">
              <span class="label-text-alt text-error">{{ reservationForm.errors.special_requests }}</span>
            </label>
          </div>

          <!-- Form Actions -->
          <div class="modal-action">
            <button
              type="button"
              @click="closeModal"
              class="btn btn-ghost"
              :disabled="reservationForm.processing"
            >
              Annulla
            </button>
            <button
              type="submit"
              class="btn btn-primary bg-orange-500 hover:bg-orange-600 border-0 text-white"
              :disabled="reservationForm.processing"
            >
              <span v-if="!reservationForm.processing">
                {{ isEditing ? 'Aggiorna' : 'Crea' }} Prenotazione
              </span>
              <span v-else class="loading loading-spinner loading-sm"></span>
            </button>
          </div>
        </form>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog ref="deleteModal" class="modal modal-bottom sm:modal-middle">
      <div class="modal-box">
        <h3 class="font-bold text-xl mb-4">Conferma Eliminazione</h3>
        <p class="py-4">
          Sei sicuro di voler eliminare la prenotazione di
          <span class="font-bold">{{ reservationToDelete?.customer_name }}</span>?
          <br>
          <span class="text-sm text-base-content/60">
            {{ reservationToDelete?.reservation_date }} alle {{ reservationToDelete?.reservation_time }}
          </span>
        </p>
        <p class="text-warning text-sm">
          Questa azione non può essere annullata.
        </p>
        <div class="modal-action">
          <button
            type="button"
            @click="closeDeleteModal"
            class="btn btn-ghost"
            :disabled="deleteForm.processing"
          >
            Annulla
          </button>
          <button
            type="button"
            @click="confirmDelete"
            class="btn btn-error"
            :disabled="deleteForm.processing"
          >
            <span v-if="!deleteForm.processing">Elimina</span>
            <span v-else class="loading loading-spinner loading-sm"></span>
          </button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ReservationCard from '@/Components/ReservationCard.vue';

const props = defineProps({
  reservations: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  tables: {
    type: Array,
    default: () => [],
  },
});

// Modal refs
const reservationModal = ref(null);
const deleteModal = ref(null);

// State
const isEditing = ref(false);
const reservationToDelete = ref(null);

// Filter form (reactive for preserving query params)
const filterForm = reactive({
  date: props.filters.date || '',
  shift: props.filters.shift || '',
  status: props.filters.status || '',
  search: props.filters.search || '',
});

// Reservation form
const reservationForm = useForm({
  customer_name: '',
  customer_phone: '',
  customer_email: '',
  reservation_date: '',
  reservation_time: '',
  number_of_people: 2,
  table_number: null,
  status: 'pending',
  special_requests: '',
});

// Delete form
const deleteForm = useForm({});

// Apply filters
const applyFilters = () => {
  router.get(route('reservations.index'), filterForm, {
    preserveState: true,
    preserveScroll: true,
  });
};

// Reset filters
const resetFilters = () => {
  filterForm.date = '';
  filterForm.shift = '';
  filterForm.status = '';
  filterForm.search = '';
  applyFilters();
};

// Open create modal
const openCreateModal = () => {
  isEditing.value = false;
  reservationForm.reset();
  reservationForm.clearErrors();

  // Set default date to today
  const today = new Date().toISOString().split('T')[0];
  reservationForm.reservation_date = today;

  reservationModal.value?.showModal();
};

// Open edit modal
const openEditModal = (reservation) => {
  isEditing.value = true;
  reservationForm.clearErrors();

  // Populate form with reservation data
  reservationForm.customer_name = reservation.customer_name;
  reservationForm.customer_phone = reservation.customer_phone;
  reservationForm.customer_email = reservation.customer_email || '';
  reservationForm.reservation_date = reservation.reservation_date;
  reservationForm.reservation_time = reservation.reservation_time;
  reservationForm.number_of_people = reservation.number_of_people;
  reservationForm.table_number = reservation.table_number;
  reservationForm.status = reservation.status;
  reservationForm.special_requests = reservation.special_requests || '';

  // Store the ID for update
  reservationForm.id = reservation.id;

  reservationModal.value?.showModal();
};

// Close modal
const closeModal = () => {
  reservationModal.value?.close();
};

// Submit reservation (create or update)
const submitReservation = () => {
  if (isEditing.value) {
    // Update existing reservation
    reservationForm.put(route('reservations.update', reservationForm.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        reservationForm.reset();
      },
    });
  } else {
    // Create new reservation
    reservationForm.post(route('reservations.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        reservationForm.reset();
      },
    });
  }
};

// Open delete confirmation
const openDeleteConfirmation = (reservation) => {
  reservationToDelete.value = reservation;
  deleteModal.value?.showModal();
};

// Close delete modal
const closeDeleteModal = () => {
  deleteModal.value?.close();
  reservationToDelete.value = null;
};

// Confirm delete
const confirmDelete = () => {
  if (!reservationToDelete.value) return;

  deleteForm.delete(route('reservations.destroy', reservationToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      closeDeleteModal();
    },
  });
};

// Format date in Italian
const formatDate = (dateString) => {
  const date = new Date(dateString);
  const options = {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric',
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

// Get status dot class
const getStatusDotClass = (status) => {
  const classMap = {
    pending: 'bg-warning',
    confirmed: 'bg-success',
    seated: 'bg-info',
    completed: 'bg-neutral',
    cancelled: 'bg-error',
    no_show: 'bg-error',
  };
  return classMap[status] || 'bg-base-300';
};
</script>

<style scoped>
.btn-primary {
  @apply border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white hover:border-orange-500;
}

.input:focus,
.select:focus,
.textarea:focus {
  outline: none;
  border-color: rgb(249 115 22);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

/* Table hover effect */
.table tbody tr:hover {
  background-color: rgba(249, 115, 22, 0.05);
}
</style>
