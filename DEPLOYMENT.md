# 🚀 Deployment Guide - Gestionale Ristorante

Guida completa per il deployment dell'applicazione in produzione.

## 📋 Pre-requisiti

- [x] Server Linux (Ubuntu 20.04+ / Debian 11+ consigliati)
- [x] Docker e Docker Compose installati
- [x] Cloudflare Tunnel configurato (opzionale se esponi direttamente il server)
- [x] Dominio configurato: `app.coreaon.org`

---

## 🔧 Step 1: Preparazione Server

### 1.1 Clona il repository

```bash
cd /home/user
git clone <repository-url> coreoan-ristoranti
cd coreoan-ristoranti
```

### 1.2 Verifica le variabili d'ambiente

Il file `.env` è già presente. Verifica e modifica i seguenti valori:

```bash
nano .env
```

**CAMBIA ASSOLUTAMENTE QUESTE PASSWORD:**

```env
# MySQL
MYSQL_ROOT_PASSWORD=<strong-password-here>
MYSQL_PASSWORD=<strong-password-here>

# Ristorante DB
RISTORANTE_DB_PASSWORD=<strong-password-here>

# n8n
N8N_BASIC_AUTH_PASSWORD=<strong-password-here>

# API Key
API_KEY=<random-secure-key>
```

> **Suggerimento:** Usa `openssl rand -base64 32` per generare password sicure.

---

## 🏗️ Step 2: Build e Deploy

### 2.1 Avvia i servizi base (se non già running)

```bash
docker-compose up -d traefik mysql phpmyadmin
```

Verifica che MySQL sia healthy:

```bash
docker-compose ps mysql
```

Output dovrebbe mostrare `healthy`.

### 2.2 Build del container frontend

```bash
docker-compose build frontend
```

⏱️ Questo processo richiederà 5-10 minuti. Il build multi-stage:
1. Installa dipendenze PHP (Composer)
2. Installa dipendenze Node (NPM)
3. Compila assets con Vite (production)
4. Crea immagine runtime con PHP-FPM + Nginx

### 2.3 Crea la directory storage

```bash
mkdir -p frontend/storage/framework/{cache/data,sessions,views}
mkdir -p frontend/storage/logs
mkdir -p frontend/bootstrap/cache
sudo chown -R 82:82 frontend/storage frontend/bootstrap/cache
```

> **Nota:** UID 82 è `www-data` in Alpine Linux.

### 2.4 Avvia il container frontend

```bash
docker-compose up -d frontend
```

### 2.5 Verifica il container

```bash
docker-compose ps frontend
docker-compose logs -f frontend
```

Output dovrebbe mostrare Nginx e PHP-FPM avviati correttamente.

---

## 🗄️ Step 3: Setup Database

### 3.1 Verifica il database

Il database `ristorante_db` e l'utente dovrebbero essere già creati dallo script init.

Verifica:

```bash
docker exec -it mysql mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e "SHOW DATABASES;"
```

Dovresti vedere `ristorante_db` nella lista.

### 3.2 Genera APP_KEY

```bash
docker exec ristorante-frontend php artisan key:generate
```

### 3.3 Esegui le migrazioni

```bash
docker exec ristorante-frontend php artisan migrate
```

Conferma con `yes` quando richiesto.

### 3.4 Popola il database con i dati demo

```bash
docker exec ristorante-frontend php artisan db:seed
```

Questo creerà:
- 2 utenti (admin@test.com, staff@test.com)
- 15 tavoli
- ~10 prenotazioni demo

---

## 🌐 Step 4: Configurazione DNS/Cloudflare

### Opzione A: Cloudflare Tunnel (Consigliato)

1. Vai su Cloudflare Tunnel dashboard
2. Crea un nuovo tunnel o modifica quello esistente
3. Aggiungi route:
   - **Hostname:** `app.coreaon.org`
   - **Service:** `http://localhost:80`
   - **HTTP Host Header:** `app.coreaon.org`

### Opzione B: DNS A Record

1. Vai su Cloudflare DNS settings
2. Crea un A record:
   - **Name:** `app`
   - **IPv4 address:** `<your-server-ip>`
   - **Proxy status:** Proxied (arancione)

---

## ✅ Step 5: Verifica

### 5.1 Health Check

```bash
curl http://localhost:3000/up
```

Output: `OK`

### 5.2 Verifica via Traefik

```bash
curl -H "Host: app.coreaon.org" http://localhost
```

Dovresti vedere l'HTML della pagina di login.

### 5.3 Accesso via browser

Apri il browser e vai su: **https://app.coreaon.org**

Dovresti vedere la pagina di login.

**Credenziali demo:**
- Email: `admin@test.com`
- Password: `password`

---

## 🔒 Step 6: Sicurezza Post-Deploy

### 6.1 Cambia le password degli utenti

```bash
docker exec -it ristorante-frontend php artisan tinker
```

Poi esegui:

```php
$user = App\Models\User::where('email', 'admin@test.com')->first();
$user->password = bcrypt('new-strong-password');
$user->save();

$user = App\Models\User::where('email', 'staff@test.com')->first();
$user->password = bcrypt('another-strong-password');
$user->save();

exit;
```

### 6.2 Disabilita la registrazione (opzionale)

L'app non ha route di registrazione, ma verifica che non ci siano route pubbliche non autorizzate:

```bash
docker exec ristorante-frontend php artisan route:list
```

### 6.3 Configura i backup del database

Crea un cron job per backup giornalieri:

```bash
crontab -e
```

Aggiungi:

```cron
0 3 * * * docker exec mysql mysqldump -uroot -p${MYSQL_ROOT_PASSWORD} ristorante_db > /backup/ristorante_$(date +\%Y\%m\%d).sql
```

---

## 🔄 Step 7: Aggiornamenti Futuri

### Pull degli ultimi cambiamenti

```bash
cd /home/user/coreoan-ristoranti
git pull origin main
```

### Rebuild e restart

```bash
docker-compose build frontend
docker-compose up -d frontend
docker exec ristorante-frontend php artisan migrate
docker exec ristorante-frontend php artisan optimize:clear
```

---

## 📊 Monitoring e Logs

### Logs in real-time

```bash
docker-compose logs -f frontend
```

### Logs Nginx

```bash
docker exec ristorante-frontend tail -f /var/log/nginx/access.log
docker exec ristorante-frontend tail -f /var/log/nginx/error.log
```

### Logs PHP

```bash
docker exec ristorante-frontend tail -f storage/logs/laravel.log
```

### Statistiche container

```bash
docker stats ristorante-frontend
```

---

## 🛠️ Troubleshooting

### Container non si avvia

```bash
docker-compose logs frontend
docker-compose restart frontend
```

### Errore permessi storage

```bash
docker exec ristorante-frontend chmod -R 775 storage bootstrap/cache
docker exec ristorante-frontend chown -R www-data:www-data storage bootstrap/cache
```

### Database connection error

Verifica che il database sia accessibile:

```bash
docker exec ristorante-frontend php artisan db:show
```

### Asset non caricati

Rebuilda il container:

```bash
docker-compose build --no-cache frontend
docker-compose up -d frontend
```

### Cache issues

```bash
docker exec ristorante-frontend php artisan cache:clear
docker exec ristorante-frontend php artisan config:clear
docker exec ristorante-frontend php artisan route:clear
docker exec ristorante-frontend php artisan view:clear
```

---

## 🎯 Performance Tips

### Ottimizzazione Laravel

```bash
docker exec ristorante-frontend php artisan optimize
docker exec ristorante-frontend php artisan config:cache
docker exec ristorante-frontend php artisan route:cache
docker exec ristorante-frontend php artisan view:cache
```

### Monitoraggio risorse

```bash
docker exec ristorante-frontend php artisan about
```

---

## 📝 Checklist Post-Deploy

- [ ] Database creato e migrazioni eseguite
- [ ] APP_KEY generata
- [ ] Seeder eseguito
- [ ] Password utenti cambiate
- [ ] Cloudflare/DNS configurato
- [ ] HTTPS funzionante
- [ ] Health check risponde OK
- [ ] Login funzionante
- [ ] Dashboard caricata correttamente
- [ ] Creazione prenotazione funzionante
- [ ] Backup configurato
- [ ] Logs monitorati

---

## 🆘 Supporto

Se incontri problemi:

1. Controlla i logs: `docker-compose logs -f frontend`
2. Verifica lo stato: `docker-compose ps`
3. Controlla la health: `curl http://localhost:3000/up`
4. Consulta il README.md nella cartella frontend

---

**Deployment completato con successo! 🎉**

L'applicazione è ora disponibile su: **https://app.coreaon.org**
