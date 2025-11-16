<template>
  <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 flex items-center justify-center p-4">
    <!-- Background pattern -->
    <div class="absolute inset-0 overflow-hidden opacity-5">
      <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-orange-500 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-orange-300 rounded-full blur-3xl"></div>
    </div>

    <!-- Login Card -->
    <div class="card w-full max-w-md bg-base-100 shadow-2xl relative z-10">
      <div class="card-body p-8 sm:p-10">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
          <div class="flex justify-center mb-4">
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center text-white font-bold text-4xl shadow-lg transform hover:scale-105 transition-transform">
              R
            </div>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">
            Gestionale Ristorante
          </h1>
          <p class="text-base-content/60 mt-2">
            Accedi al sistema di gestione prenotazioni
          </p>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Email Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Indirizzo Email</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
              </div>
              <input
                v-model="form.email"
                type="email"
                placeholder="nome@esempio.it"
                class="input input-bordered w-full pl-12 focus:input-primary focus:border-orange-500"
                :class="{ 'input-error': form.errors.email }"
                autocomplete="email"
                autofocus
              />
            </div>
            <label v-if="form.errors.email" class="label">
              <span class="label-text-alt text-error">{{ form.errors.email }}</span>
            </label>
          </div>

          <!-- Password Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Password</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Inserisci la tua password"
                class="input input-bordered w-full pl-12 pr-12 focus:input-primary focus:border-orange-500"
                :class="{ 'input-error': form.errors.password }"
                autocomplete="current-password"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-base-content/40 hover:text-base-content"
              >
                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>
            </div>
            <label v-if="form.errors.password" class="label">
              <span class="label-text-alt text-error">{{ form.errors.password }}</span>
            </label>
          </div>

          <!-- Remember Me Checkbox -->
          <div class="form-control">
            <label class="label cursor-pointer justify-start gap-3">
              <input
                v-model="form.remember"
                type="checkbox"
                class="checkbox checkbox-primary checkbox-sm border-2"
              />
              <span class="label-text">Ricordami</span>
            </label>
          </div>

          <!-- Error Message (if any general error) -->
          <div v-if="form.errors.general || page.props.errors?.message" class="alert alert-error shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ form.errors.general || page.props.errors?.message }}</span>
          </div>

          <!-- Submit Button -->
          <div class="form-control mt-8">
            <button
              type="submit"
              class="btn btn-primary w-full bg-gradient-to-r from-orange-500 to-orange-600 border-0 text-white hover:from-orange-600 hover:to-orange-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all"
              :disabled="form.processing"
            >
              <span v-if="!form.processing" class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Accedi
              </span>
              <span v-else class="loading loading-spinner loading-sm"></span>
            </button>
          </div>
        </form>

        <!-- Footer Links -->
        <div class="text-center mt-6 space-y-2">
          <a href="#" class="link link-hover text-sm text-base-content/60 hover:text-orange-500">
            Password dimenticata?
          </a>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="absolute bottom-4 left-0 right-0 text-center text-sm text-base-content/60">
      <p>&copy; {{ new Date().getFullYear() }} Sistema di Gestione Prenotazioni. Tutti i diritti riservati.</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

// Show/hide password state
const showPassword = ref(false);

// Initialize form with Inertia form helper
const form = useForm({
  email: '',
  password: '',
  remember: false,
});

// Submit login form
const submit = () => {
  form.post(route('login'), {
    onFinish: () => {
      // Clear password on finish (success or error)
      form.password = '';
    },
  });
};
</script>

<style scoped>
/* Custom focus styles for inputs */
.input:focus {
  outline: none;
  border-color: rgb(249 115 22);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

.checkbox-primary:checked {
  background-color: rgb(249 115 22);
  border-color: rgb(249 115 22);
}

/* Animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card {
  animation: slideInUp 0.5s ease-out;
}
</style>
