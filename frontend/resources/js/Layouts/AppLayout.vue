<template>
  <div class="min-h-screen bg-base-200">
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
      <div class="navbar-start">
        <!-- Mobile menu button -->
        <div class="dropdown">
          <label tabindex="0" class="btn btn-ghost lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
            </svg>
          </label>
          <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
            <li>
              <Link :href="route('dashboard')" :class="{ 'active bg-orange-500 text-white': route().current('dashboard') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
              </Link>
            </li>
            <li>
              <Link :href="route('reservations.index')" :class="{ 'active bg-orange-500 text-white': route().current('reservations.*') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Prenotazioni
              </Link>
            </li>
          </ul>
        </div>

        <!-- Logo -->
        <Link :href="route('dashboard')" class="btn btn-ghost normal-case text-xl">
          <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">
              R
            </div>
            <span class="hidden sm:inline font-bold bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">
              Ristorante
            </span>
          </div>
        </Link>
      </div>

      <!-- Desktop menu -->
      <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-2">
          <li>
            <Link
              :href="route('dashboard')"
              :class="[
                'flex items-center gap-2 transition-all',
                route().current('dashboard') ? 'bg-orange-500 text-white hover:bg-orange-600' : 'hover:bg-orange-50'
              ]"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Dashboard
            </Link>
          </li>
          <li>
            <Link
              :href="route('reservations.index')"
              :class="[
                'flex items-center gap-2 transition-all',
                route().current('reservations.*') ? 'bg-orange-500 text-white hover:bg-orange-600' : 'hover:bg-orange-50'
              ]"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Prenotazioni
            </Link>
          </li>
        </ul>
      </div>

      <!-- Right side -->
      <div class="navbar-end gap-4">
        <!-- Current date -->
        <div class="hidden md:flex items-center gap-2 text-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span class="font-medium">{{ currentDate }}</span>
        </div>

        <!-- User dropdown -->
        <div class="dropdown dropdown-end">
          <label tabindex="0" class="btn btn-ghost gap-2">
            <div class="avatar placeholder">
              <div class="bg-orange-500 text-white rounded-full w-10">
                <span class="text-lg">{{ userInitials }}</span>
              </div>
            </div>
            <span class="hidden sm:inline">{{ userName }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </label>
          <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-box w-52 mt-4">
            <li class="menu-title">
              <span>{{ userEmail }}</span>
            </li>
            <li>
              <a class="gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profilo
              </a>
            </li>
            <li>
              <a class="gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Impostazioni
              </a>
            </li>
            <div class="divider my-0"></div>
            <li>
              <Link :href="route('logout')" method="post" as="button" class="gap-2 text-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Disconnetti
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <main class="container mx-auto px-4 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="footer footer-center p-10 bg-base-100 text-base-content mt-auto">
      <aside>
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-2xl shadow-lg">
          R
        </div>
        <p class="font-bold">
          Sistema di Gestione Prenotazioni
        </p>
        <p>Copyright © {{ new Date().getFullYear() }} - Tutti i diritti riservati</p>
      </aside>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// User information
const userName = computed(() => page.props.auth?.user?.name || 'Utente');
const userEmail = computed(() => page.props.auth?.user?.email || '');
const userInitials = computed(() => {
  const name = userName.value;
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .substring(0, 2);
});

// Current date in Italian format
const currentDate = computed(() => {
  const date = new Date();
  const options = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };
  return date.toLocaleDateString('it-IT', options);
});
</script>

<style scoped>
/* Additional custom styles if needed */
.menu li > *:not(ul):not(.menu-title):not(details).active {
  @apply bg-orange-500 text-white;
}

.menu li > *:not(ul):not(.menu-title):not(details):hover {
  @apply bg-orange-50;
}

.menu li > *:not(ul):not(.menu-title):not(details).active:hover {
  @apply bg-orange-600;
}
</style>
