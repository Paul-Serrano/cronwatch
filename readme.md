# Cronwatch

Application **Laravel** déployée sur **Fly.io**, avec un environnement **local Dockerisé** et un pipeline **CI/CD via GitHub Actions**.

Ce projet est conçu avec une approche **production-first** : séparation claire des environnements, déploiement automatisé, observabilité et alerting fiables, sans dépendance à des outils payants.

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
- PHP-FPM exposé uniquement sur le réseau Docker

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
- Application : http://localhost:8080
- Adminer : http://localhost:8081

---

## ☁️ Environnement PRODUCTION (Fly.io)

### Stack

- 1 VM Fly.io
- nginx + php-fpm **dans le même container**
- PostgreSQL managé via Fly.io (pg + pgbouncer)

### Nginx PROD (`infra/nginx/prod.conf`)

```nginx
location ~ \.php$ {
    fastcgi_pass 127.0.0.1:9000; # IMPORTANT : localhost
}
```

⚠️ **Il n’y a PAS de docker-compose en production**

---

## 🛠 Dockerfile PROD (résumé)

- PHP 8.4 FPM
- Extensions PHP nécessaires
- Composer
- Build optimisé (`--no-dev`)
- nginx + php-fpm lancés dans le même container

```dockerfile
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php-fpm -D && nginx -g 'daemon off;'"]
```

---

## 🔐 Variables d’environnement (Fly.io)

### Gestion

Les variables **ne sont jamais stockées dans le repository**.

Elles sont définies via :

```bash
fly secrets set APP_KEY=... DB_PASSWORD=...
```

---

## 🩺 Healthcheck & Alerting (Production)

Cronwatch utilise un **healthcheck applicatif** combiné à **Better Stack Uptime** pour détecter les pannes réelles et envoyer des alertes fiables.

---

## 👤 Auteur

**Paul Serrano**  
Backend Developer – Laravel / PHP  


keyId = 0032e957b2787050000000001
application key = K003CBv1e41d1yG79D0JllQRAi3TV7k

