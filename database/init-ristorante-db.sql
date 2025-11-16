-- ============================================================================
-- Script di inizializzazione Database Ristorante
-- ============================================================================
-- Questo script viene eseguito automaticamente al primo avvio del container MySQL
-- Crea il database e l'utente per l'applicazione ristorante

-- Crea il database se non esiste
CREATE DATABASE IF NOT EXISTS `ristorante_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crea l'utente ristorante (se non esiste) con accesso da qualsiasi host
CREATE USER IF NOT EXISTS 'ristorante_user'@'%' IDENTIFIED BY 'ristorante_password_CHANGE_ME';

-- Garantisce tutti i privilegi sul database ristorante_db
GRANT ALL PRIVILEGES ON `ristorante_db`.* TO 'ristorante_user'@'%';

-- Applica le modifiche
FLUSH PRIVILEGES;

-- Log di conferma
SELECT 'Database ristorante_db creato con successo!' AS Message;
