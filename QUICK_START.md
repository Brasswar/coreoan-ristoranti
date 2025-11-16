# ⚡ Quick Start - Gestionale Ristorante

Guida rapida per avviare l'applicazione in **meno di 5 minuti**.

## 🚀 Deployment Produzione (Docker)

### 1. Setup iniziale (SOLO LA PRIMA VOLTA)

```bash
./deploy.sh setup
```

Questo comando:
- ✅ Crea le directory necessarie
- ✅ Build dell'immagine Docker
- ✅ Avvia il container
- ✅ Genera APP_KEY
- ✅ Esegue le migrazioni
- ✅ Popola il database con dati demo
- ✅ Ottimizza Laravel

⏱️ **Tempo stimato:** 5-10 minuti

### 2. Accedi all'applicazione

🌐 **URL:** https://app.coreaon.org

**Credenziali:**
- Email: `admin@test.com`
- Password: `password`

---

## 💻 Sviluppo Locale (senza Docker)

### 1. Installa dipendenze

```bash
cd frontend
composer install
npm install
```

### 2. Configura ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Modifica `.env` con le tue credenziali MySQL.

### 3. Database

```bash
php artisan migrate --seed
```

### 4. Avvia server

```bash
# Terminale 1: Laravel
php artisan serve

# Terminale 2: Vite (asset watcher)
npm run dev
```

### 5. Accedi

🌐 **URL:** http://localhost:8000

**Credenziali:** `admin@test.com` / `password`

---

## 🛠️ Comandi Utili

### Docker

```bash
./deploy.sh logs          # Visualizza logs
./deploy.sh restart       # Riavvia container
./deploy.sh artisan ...   # Esegui comando artisan
./deploy.sh shell         # Apri shell nel container
./deploy.sh backup        # Backup database
./deploy.sh status        # Stato container
```

### Locale

```bash
php artisan migrate       # Esegui migrations
php artisan db:seed       # Popola database
php artisan route:list    # Lista routes
php artisan tinker        # REPL Laravel
```

---

## 📚 Documentazione Completa

- **README.md** - Documentazione completa dell'applicazione
- **DEPLOYMENT.md** - Guida deployment dettagliata
- **frontend/README.md** - Documentazione tecnica

---

## ✅ Checklist Post-Deploy

- [ ] Container avviato: `docker ps | grep ristorante`
- [ ] Health check OK: `curl http://localhost:3000/up`
- [ ] Login funzionante
- [ ] Cambiate password utenti demo
- [ ] Backup configurato

---

**Pronto! 🎉**

Hai deployato con successo l'applicazione Gestionale Ristorante!
