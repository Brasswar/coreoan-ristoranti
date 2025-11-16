# 🍽️ Gestionale Ristorante - MVP Prenotazioni

Sistema moderno e semplice per gestire le prenotazioni del ristorante con interfaccia web elegante.

![Stack](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)
![Stack](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat&logo=vue.js)
![Stack](https://img.shields.io/badge/Inertia.js-1.0-9553E9?style=flat)
![Stack](https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?style=flat&logo=tailwind-css)
![Stack](https://img.shields.io/badge/DaisyUI-4-5A0EF8?style=flat)

## 📋 Indice

- [Caratteristiche](#caratteristiche)
- [Stack Tecnologico](#stack-tecnologico)
- [Requisiti](#requisiti)
- [Setup Locale](#setup-locale)
- [Setup Docker (Produzione)](#setup-docker-produzione)
- [Accesso Demo](#accesso-demo)
- [Funzionalità](#funzionalità)
- [Struttura Database](#struttura-database)
- [Roadmap](#roadmap)
- [Supporto](#supporto)

---

## ✨ Caratteristiche

- ✅ **Dashboard moderna** con calendario mensile interattivo
- ✅ **Gestione prenotazioni completa** (CRUD)
- ✅ **Filtri avanzati** per data, turno, stato, ricerca
- ✅ **Responsive design** perfetto per mobile, tablet, desktop
- ✅ **Interfaccia in italiano** con design intuitivo
- ✅ **Validazione real-time** dei form
- ✅ **Toast notifications** per feedback utente
- ✅ **Autenticazione sicura** con Laravel Breeze
- ✅ **Performance ottimizzate** con Vite + SSR ready

---

## 🛠️ Stack Tecnologico

### Backend
- **Laravel 11.x** - Framework PHP moderno e potente
- **MySQL 8.0** - Database relazionale
- **PHP 8.2+** - Linguaggio server-side

### Frontend
- **Vue.js 3** - Framework JavaScript reattivo (Composition API)
- **Inertia.js** - SPA senza API separata (SSR-ready)
- **Tailwind CSS 3** - Utility-first CSS framework
- **DaisyUI** - Componenti UI bellissimi e pronti all'uso
- **Heroicons** - Icone SVG moderne
- **Vite** - Build tool velocissimo

### DevOps
- **Docker** - Containerizzazione
- **Nginx** - Web server ad alte prestazioni
- **Supervisor** - Process manager
- **Traefik** - Reverse proxy con HTTPS automatico

---

## 📦 Requisiti

### Sviluppo Locale
- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 20.x
- MySQL >= 8.0
- NPM o Yarn

### Produzione Docker
- Docker >= 24.x
- Docker Compose >= 2.x

---

## 🚀 Setup Locale

### 1. Clona il repository e vai nella cartella frontend

```bash
cd frontend
```

### 2. Installa le dipendenze PHP

```bash
composer install
```

### 3. Installa le dipendenze Node.js

```bash
npm install
```

### 4. Configura l'ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Modifica `.env` con le tue credenziali database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ristorante_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Esegui le migrazioni e i seeder

```bash
php artisan migrate --seed
```

Questo creerà:
- 2 utenti demo (admin e staff)
- 15 tavoli (varie capacità)
- ~10 prenotazioni demo

### 6. Compila gli asset

```bash
npm run dev
```

### 7. Avvia il server locale

In un altro terminale:

```bash
php artisan serve
```

### 8. Accedi all'applicazione

Apri il browser e vai su: **http://localhost:8000**

---

## 🐳 Setup Docker (Produzione)

### 1. Copia e configura le variabili d'ambiente

Le variabili sono già configurate nel file `.env` principale del progetto:

```env
DOMAIN_NAME=coreaon.org
FRONTEND_SUBDOMAIN=app
RISTORANTE_DB_NAME=ristorante_db
RISTORANTE_DB_USER=ristorante_user
RISTORANTE_DB_PASSWORD=ristorante_password_CHANGE_ME
```

**⚠️ IMPORTANTE:** Cambia le password prima di andare in produzione!

### 2. Build dell'immagine Docker

Dalla root del progetto:

```bash
docker-compose build frontend
```

Questo processo:
- Installa le dipendenze PHP (Composer)
- Installa le dipendenze Node (NPM)
- Compila gli asset con Vite (production build)
- Crea un container con PHP-FPM + Nginx + Supervisor

### 3. Avvia il container

```bash
docker-compose up -d frontend
```

### 4. Genera la chiave dell'applicazione

```bash
docker exec ristorante-frontend php artisan key:generate
```

### 5. Esegui le migrazioni e i seeder

```bash
docker exec ristorante-frontend php artisan migrate --seed
```

### 6. Verifica lo stato

```bash
docker-compose ps
docker-compose logs -f frontend
```

### 7. Accedi all'applicazione

L'applicazione sarà disponibile su: **https://app.coreaon.org**

---

## 🔑 Accesso Demo

Dopo aver eseguito i seeder, puoi accedere con queste credenziali:

| Ruolo | Email | Password |
|-------|-------|----------|
| **Admin** | admin@test.com | password |
| **Staff** | staff@test.com | password |

> **Nota:** In produzione, cambia immediatamente queste password!

---

## 📱 Funzionalità

### 🏠 Dashboard

- **Statistiche rapide**
  - Prenotazioni oggi
  - Prenotazioni domani
  - Totale settimana corrente

- **Calendario mensile**
  - Visualizzazione giorni con prenotazioni
  - Badge con numero prenotazioni per giorno
  - Navigazione mese precedente/successivo
  - Click su giorno per filtrare prenotazioni

- **Prossime prenotazioni**
  - Lista delle prossime 5 prenotazioni
  - Dettagli cliente, orario, numero persone

### 📋 Lista Prenotazioni

- **Filtri potenti**
  - Data (date picker)
  - Turno (Pranzo/Cena/Tutti)
  - Stato (Pending/Confermata/Cancellata/Completata/Tutti)
  - Ricerca per nome cliente, telefono, email

- **Vista desktop** - Tabella completa con tutte le informazioni
- **Vista mobile** - Card responsive e touch-friendly
- **Paginazione** - 20 prenotazioni per pagina
- **Azioni rapide** - Visualizza, Modifica, Elimina

### ➕ Nuova Prenotazione / Modifica

- **Form completo** con validazione in tempo reale:
  - Nome cliente (obbligatorio)
  - Telefono (obbligatorio)
  - Email (opzionale)
  - Data e ora (obbligatori)
  - Numero persone (1-50)
  - Assegnazione tavolo (opzionale)
  - Stato prenotazione
  - Note speciali (opzionale)

- **Auto-detection** turno (Pranzo se ore < 16:00, altrimenti Cena)
- **Feedback visivo** - Toast notification su successo/errore
- **Loading states** - Spinner durante il salvataggio

---

## 🗄️ Struttura Database

### Tabella `users`
```
- id
- name
- email (unique)
- password (hashed)
- role (admin, staff)
- created_at, updated_at
```

### Tabella `tables`
```
- id
- number (unique)
- seats (capacità posti)
- created_at, updated_at
```

### Tabella `reservations`
```
- id
- customer_name
- customer_phone
- customer_email (nullable)
- reservation_date (indexed)
- reservation_time
- guests_count
- table_id (FK → tables, nullable)
- shift (lunch, dinner) (indexed)
- status (pending, confirmed, cancelled, completed) (indexed)
- notes (nullable)
- created_by (FK → users)
- created_at, updated_at
```

---

## 🎨 Design System

### Colori
- **Primary**: Orange #f97316 (orange-500)
- **Secondary**: Slate #64748b (slate-600)
- **Success**: Green #10b981 (green-500)
- **Warning**: Amber #f59e0b (amber-500)
- **Error**: Red #ef4444 (red-500)

### Tipografia
- **Font**: Inter (Google Fonts alternative: Bunny Fonts)
- **Heading**: font-bold text-2xl
- **Body**: font-normal text-base

### Componenti DaisyUI
- Cards con shadow e hover effects
- Buttons con stati di loading
- Badges colorati per stati
- Form inputs con validazione
- Modals responsive
- Toast notifications

---

## 🗺️ Roadmap

### Versione Attuale: MVP v1.0
- ✅ Autenticazione (Login/Logout)
- ✅ Dashboard con calendario
- ✅ CRUD Prenotazioni completo
- ✅ Filtri e ricerca avanzata
- ✅ Responsive design

### Prossimi Sviluppi
- ⏳ **Gestione Tavoli** - Visualizzazione mappa tavoli, disponibilità real-time
- ⏳ **Gestione Ordini** - Sistema completo ordini e comande
- ⏳ **Gestione Menu** - CRUD menu con categorie e prezzi
- ⏳ **Report e Statistiche** - Dashboard analytics avanzata
- ⏳ **Integrazioni n8n** - Automazioni e workflow
- ⏳ **Notifiche** - Email/SMS conferma prenotazione
- ⏳ **Multi-lingua** - Supporto EN/IT
- ⏳ **Dark Mode** - Tema scuro
- ⏳ **Export** - PDF/Excel per report

---

## 📂 Struttura Progetto

```
frontend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── ReservationController.php
│   │   │   └── Auth/
│   │   │       └── AuthenticatedSessionController.php
│   │   ├── Middleware/
│   │   │   └── HandleInertiaRequests.php
│   │   └── Requests/
│   │       └── ReservationRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Table.php
│   │   └── Reservation.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── *_create_users_table.php
│   │   ├── *_create_tables_table.php
│   │   └── *_create_reservations_table.php
│   └── seeders/
│       ├── UserSeeder.php
│       ├── TableSeeder.php
│       └── ReservationSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   ├── ssr.js
│   │   ├── Components/
│   │   │   ├── Calendar.vue
│   │   │   ├── ReservationCard.vue
│   │   │   └── StatCard.vue
│   │   ├── Layouts/
│   │   │   └── AppLayout.vue
│   │   └── Pages/
│   │       ├── Auth/
│   │       │   └── Login.vue
│   │       ├── Dashboard.vue
│   │       └── Reservations/
│   │           └── Index.vue
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── web.php
│   └── auth.php
├── docker/
│   ├── Dockerfile
│   ├── nginx.conf
│   ├── supervisord.conf
│   └── php.ini
├── .env.example
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

## 🛡️ Sicurezza

- ✅ Password hashate con bcrypt
- ✅ CSRF protection su tutti i form
- ✅ Validazione input lato server e client
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Vue.js escaping)
- ✅ Sessioni sicure
- ✅ HTTPS in produzione (Traefik)

---

## 🐛 Troubleshooting

### Errore: "APP_KEY not set"
```bash
php artisan key:generate
```

### Asset non compilati
```bash
npm run build
# oppure per sviluppo
npm run dev
```

### Permessi storage
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Container non si avvia
```bash
docker-compose logs -f frontend
docker-compose restart frontend
```

### Database connection refused
Verifica che il servizio MySQL sia avviato:
```bash
docker-compose ps mysql
docker-compose up -d mysql
```

---

## 📞 Supporto

Per problemi, bug o richieste di funzionalità, apri una issue su GitHub.

---

## 📄 Licenza

Questo progetto è distribuito sotto licenza MIT.

---

## 👨‍💻 Sviluppato con ❤️

Creato con passione usando le migliori tecnologie moderne per offrire un'esperienza utente eccezionale.

**Tech Stack:**
Laravel 11 + Vue 3 + Inertia.js + Tailwind CSS + DaisyUI + Docker

---

**Ultima aggiornamento:** Novembre 2025
**Versione:** 1.0.0 MVP
