# 🍽️ Coreaon Ristoranti - Sistema Gestionale Completo

Sistema di gestione completo per ristorante con automazioni n8n e applicazione web moderna.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)
![Vue](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat&logo=vue.js)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker)
![License](https://img.shields.io/badge/License-MIT-green.svg)

---

## 📦 Panoramica Progetto

Questo progetto contiene l'intera infrastruttura per gestire un ristorante moderno:

```
coreoan-ristoranti/
├── frontend/          → 🖥️  Applicazione Web Laravel + Vue 3 (Gestione Prenotazioni)
├── database/          → 🗄️  Script inizializzazione database MySQL
├── docker-compose.yml → 🐳  Orchestrazione servizi Docker
├── deploy.sh          → ⚡  Script deployment automatico
└── docs/              → 📚  Documentazione
```

---

## 🌐 Servizi Disponibili

| Servizio | Dominio | Descrizione | Porta |
|----------|---------|-------------|-------|
| **Frontend App** | app.coreaon.org | Gestionale prenotazioni | 3000 |
| **n8n** | n8n.coreaon.org | Automazioni workflow | 5678 |
| **phpMyAdmin** | db.coreaon.org | Gestione database | 80 |
| **Dashboard** | www.coreaon.org | Dashboard principale | 80 |
| **Traefik** | traefik.coreaon.org | Reverse proxy | 8080 |

Tutti i servizi sono accessibili via **HTTPS** tramite Traefik (o Cloudflare Tunnel).

---

## ⚡ Quick Start

### Opzione 1: Deployment Completo (Consigliato)

```bash
# 1. Clona il repository
git clone <repository-url> coreoan-ristoranti
cd coreoan-ristoranti

# 2. Configura le password nel file .env
nano .env

# 3. Deploy con un solo comando
./deploy.sh setup
```

⏱️ **Tempo:** ~10 minuti per il primo deploy

### Opzione 2: Sviluppo Locale

```bash
cd frontend
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve  # Terminale 1
npm run dev        # Terminale 2
```

🌐 Accedi a: **http://localhost:8000**

---

## 🎯 Funzionalità Applicazione Frontend

### ✨ MVP v1.0 - Gestione Prenotazioni

- ✅ **Login/Autenticazione** sicura (Laravel Breeze)
- ✅ **Dashboard** con calendario mensile interattivo e statistiche
- ✅ **CRUD Prenotazioni** completo
  - Crea, modifica, elimina prenotazioni
  - Assegnazione tavoli
  - Gestione stati (Pending, Confermata, Cancellata, Completata)
- ✅ **Filtri Avanzati**
  - Per data, turno (pranzo/cena), stato
  - Ricerca per nome/telefono cliente
- ✅ **Design Responsive** - Mobile, Tablet, Desktop
- ✅ **UI Moderna** - Tailwind CSS + DaisyUI
- ✅ **Performance** - SSR-ready con Inertia.js

**Credenziali Demo:**
- Admin: `admin@test.com` / `password`
- Staff: `staff@test.com` / `password`

---

## 🛠️ Stack Tecnologico

### Backend
- **Laravel 11** - Framework PHP
- **MySQL 8.0** - Database
- **PHP 8.2** - Runtime

### Frontend
- **Vue 3** - Framework JavaScript (Composition API)
- **Inertia.js** - SPA senza API separata
- **Tailwind CSS** - Styling
- **DaisyUI** - Componenti UI
- **Vite** - Build tool

### Infrastructure
- **Docker** - Containerizzazione
- **Traefik 3.6** - Reverse proxy con HTTPS
- **Nginx** - Web server
- **Supervisor** - Process manager
- **n8n** - Automazioni workflow

---

## 📋 Requisiti

- Docker >= 24.x
- Docker Compose >= 2.x
- Linux server (Ubuntu/Debian consigliati)
- Cloudflare account (opzionale per DNS)

---

## 📚 Documentazione

- **[QUICK_START.md](QUICK_START.md)** - Guida rapida 5 minuti
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Deployment dettagliato passo-passo
- **[frontend/README.md](frontend/README.md)** - Documentazione applicazione completa

---

## 🚀 Comandi Deploy Script

Il progetto include uno script `deploy.sh` per semplificare le operazioni:

```bash
./deploy.sh setup      # Setup iniziale completo
./deploy.sh build      # Build immagine Docker
./deploy.sh start      # Avvia containers
./deploy.sh stop       # Ferma containers
./deploy.sh restart    # Riavvia containers
./deploy.sh logs       # Visualizza logs
./deploy.sh migrate    # Esegui migrations
./deploy.sh seed       # Popola database
./deploy.sh shell      # Apri shell nel container
./deploy.sh backup     # Backup database
./deploy.sh status     # Stato containers
./deploy.sh optimize   # Ottimizza Laravel (cache)
./deploy.sh clear      # Pulisci cache
```

---

## 🗄️ Database

Il database MySQL include:

### Tabelle Principali
- **users** - Utenti del sistema (admin/staff)
- **tables** - Tavoli del ristorante (15 tavoli di varie capacità)
- **reservations** - Prenotazioni clienti

### Dati Demo
- 2 utenti (admin, staff)
- 15 tavoli (2, 4, 6, 8 posti)
- ~10 prenotazioni di esempio

---

## 🔒 Sicurezza

### ⚠️ IMPORTANTE - Prima di andare in produzione:

1. **Cambia tutte le password** nel file `.env`:
   - `MYSQL_ROOT_PASSWORD`
   - `MYSQL_PASSWORD`
   - `RISTORANTE_DB_PASSWORD`
   - `N8N_BASIC_AUTH_PASSWORD`

2. **Cambia le password degli utenti demo**:
   ```bash
   ./deploy.sh artisan tinker
   # Poi cambia le password (vedi DEPLOYMENT.md)
   ```

3. **Configura backup automatici** (vedi DEPLOYMENT.md)

4. **Abilita HTTPS** via Traefik o Cloudflare

---

## 🗺️ Roadmap

### ✅ Completato (v1.0)
- Gestione prenotazioni completa
- Dashboard con calendario
- Autenticazione
- Design responsive

### 🔜 Prossimi sviluppi
- Gestione tavoli visuale (mappa sala)
- Sistema ordini e comande
- Gestione menu con prezzi
- Report e analytics
- Notifiche email/SMS
- Integrazioni n8n avanzate
- Multi-lingua (EN/IT)
- Dark mode
- App mobile (PWA)

---

## 🐛 Troubleshooting

### Container non si avvia
```bash
docker-compose logs -f frontend
docker-compose restart frontend
```

### Errore database
```bash
./deploy.sh artisan db:show
./deploy.sh migrate
```

### Problemi permessi
```bash
sudo chown -R 82:82 frontend/storage frontend/bootstrap/cache
```

### Cache problems
```bash
./deploy.sh clear
./deploy.sh optimize
```

---

## 📞 Supporto

Per problemi o domande:
1. Controlla la documentazione in `DEPLOYMENT.md`
2. Verifica i logs: `./deploy.sh logs`
3. Apri una issue su GitHub

---

## 📄 Licenza

Questo progetto è distribuito sotto licenza MIT.

---

## 👨‍💻 Credits

**Sviluppato con:**
- Laravel 11
- Vue 3
- Inertia.js
- Tailwind CSS + DaisyUI
- Docker

**Ultima versione:** 1.0.0 MVP
**Data:** Novembre 2025

---

**🎉 Pronto per il deployment!**

Segui la [Guida Quick Start](QUICK_START.md) per iniziare subito.