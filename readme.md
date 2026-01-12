# Cronwatch

Application Laravel déployée sur **Fly.io**, avec un environnement **local Dockerisé** et un pipeline **CI/CD GitHub Actions**.

Ce README décrit **l’architecture**, **les environnements**, **les commandes essentielles** et **les pièges évités**.

---

## 🧱 Architecture globale

### Environnements

| Environnement | Stack |
|---------------|-------|
| Local (dev) | Docker Compose (nginx + php-fpm + postgres) |
| Production | Fly.io (1 VM, nginx + php-fpm) |

---

## 📁 Arborescence importante

```
cronwatch/
├── apps/
│   └── backend/            # Code Laravel
│       ├── app/
│       ├── public/
│       ├── artisan
│       └── composer.json
│
├── infra/
│   ├── docker/
│   │   ├── dev/
│   │   │   ├── Dockerfile
│   │   │   └── docker-compose.yml
│   │   └── prod/
│   │       └── Dockerfile
│   │
│   └── nginx/
│       ├── dev.conf
│       └── prod.conf
│
├── fly.toml
└── .github/workflows/
    └── deploy.yml
```

---

## 🐳 Environnement LOCAL (Docker)

### Services

| Service | Rôle |
|-------|------|
| nginx | Reverse proxy HTTP |
| app | PHP-FPM + Laravel |
| db | PostgreSQL |
| adminer | Interface DB |

### docker-compose.yml (résumé)

- nginx écoute sur `localhost:8080`
- adminer sur `localhost:8081`
- PHP-FPM exposé sur le réseau Docker

### Nginx DEV (`infra/nginx/dev.conf`)

```nginx
location ~ \.php$ {
    fastcgi_pass app:9000; # IMPORTANT : nom du service Docker
}
```

### Lancer le projet en local

```bash
docker compose up --build
```

Accès :
- App : http://localhost:8080
- Adminer : http://localhost:8081

---

## ☁️ Environnement PRODUCTION (Fly.io)

### Stack

- 1 VM Fly.io
- nginx + php-fpm **dans le même container**
- PostgreSQL via Fly.io (pg + pgbouncer)

### Nginx PROD (`infra/nginx/prod.conf`)

```nginx
location ~ \.php$ {
    fastcgi_pass 127.0.0.1:9000; # IMPORTANT : localhost
}
```

⚠️ **Il n’y a PAS de docker-compose en prod**

---

## 🛠 Dockerfile PROD (résumé)

- PHP 8.3 FPM
- Extensions PHP nécessaires
- Composer
- Build optimisé (no-dev)
- nginx + php-fpm lancés ensemble

```dockerfile
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php-fpm -D && nginx -g 'daemon off;'"]
```

---

## 🔐 Variables d’environnement (Fly.io)

### Gestion

Les variables **NE SONT PAS dans le repo**.
Elles sont stockées via :

```bash
fly secrets set APP_KEY=... DB_PASSWORD=...
```

### Vérifier côté serveur

```bash
fly ssh console -a cronwatch
php artisan tinker
env('APP_KEY')
```

---

## 🚀 Déploiement

### Manuel

```bash
fly deploy
```

### Migrations en PROD

Gérées automatiquement via `fly.toml` :

```toml
[deploy]
release_command = "php artisan migrate --force"
```

✔️ Exécuté **à chaque déploiement**
✔️ Dans un contexte sûr
✔️ Sans SSH manuel

---

## 🤖 CI/CD – GitHub Actions

### Pipeline

1. Checkout
2. Build image Docker
3. Deploy Fly.io
4. Release command (migrations)

Aucune commande `fly ssh console` nécessaire dans le workflow.

---

## 🧠 Pièges évités

### ❌ Erreur classique

```
host not found in upstream "app"
```

Cause :
- Conf nginx DEV utilisée en PROD

### ✅ Règle d’or

| Environnement | fastcgi_pass |
|--------------|-------------|
| Docker | `app:9000` |
| Fly.io | `127.0.0.1:9000` |

---

## 🧪 Commandes utiles

### Logs Fly.io

```bash
fly logs
```

### Statut machines

```bash
fly status
```

### Console serveur

```bash
fly ssh console -a cronwatch
```

---

## ✅ État actuel du projet

- ✅ Laravel 12 fonctionnel
- ✅ Environnements séparés
- ✅ Déploiement stable
- ✅ CI/CD propre
- ✅ Base solide pour scaling

---

## 📌 À faire plus tard (optionnel)

- Horizon / queue worker
- Scheduler Fly.io
- Observabilité (Sentry, logs structurés)
- Scaling horizontal

---

## 👤 Auteur

**Paul Serrano**  
Backend Developer – Laravel / PHP  

---

🟢 Projet prêt pour la production.

