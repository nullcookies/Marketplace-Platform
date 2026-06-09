# Marketplace Platform

Internal tool for managing prices and product cards on Wildberries, Ozon, and Yandex.Market.

## Architecture &amp; Process Flow

![Architecture Diagram](docs/architecture.svg)

## Stack

- **Backend**: Symfony 6.4 + API Platform 3 + Doctrine ORM + PostgreSQL 15
- **Frontend**: Vue 3 + TypeScript + Pinia + Vite
- **Queue**: Symfony Messenger + Redis
- **Infrastructure**: DDEV (Docker)

## Features

- Price import from CSV/XLSX files
- Margin formula engine with configurable rules
- Data grid with tens of thousands of rows
- Publication queue for pushing prices to marketplaces
- API integrations with WB / Ozon / Yandex.Market
- JWT authentication
- Real-time import progress tracking

## Quick Start

### Prerequisites

- [DDEV](https://ddev.com/get-started/) (v1.24+)
- Docker Desktop

### Setup

```bash
# Start DDEV
ddev start

# Install PHP dependencies (done automatically)
ddev composer install

# Install frontend dependencies
ddev npm install

# Generate JWT keys
ddev exec backend php bin/console lexik:jwt:generate-keypair

# Create database and run migrations
ddev exec backend php bin/console doctrine:database:create --if-not-exists
ddev exec backend php bin/console doctrine:migrations:migrate

# (Optional) Load fixtures
ddev exec backend php bin/console doctrine:fixtures:load

# Start Vite dev server (in separate terminal)
ddev npm --dir frontend run dev
```

### URLs

| Service | URL |
|---------|-----|
| Symfony API | https://mp.ddev.site |
| API Docs (Swagger) | https://mp.ddev.site/api/docs |
| Frontend (Vite) | https://mp.ddev.site:5174 |
| Mailpit | https://mp.ddev.site:8026 |
| PostgreSQL | ddev mp psql |

## Project Structure

```
├── .ddev/              # DDEV configuration
│   ├── config.yaml     # Main DDEV config
│   ├── docker-compose.redis.yaml  # Redis service
│   └── commands/       # Custom commands
├── backend/            # Symfony application
│   ├── src/
│   │   ├── Entity/     # Doctrine entities
│   │   ├── Service/    # Business logic
│   │   ├── Message/    # Async messages
│   │   ├── Controller/ # Controllers
│   │   └── Enum/       # Enum types
│   ├── config/         # Symfony config
│   └── public/         # Entry point
├── frontend/           # Vue 3 SPA
│   ├── src/
│   │   ├── views/      # Page components
│   │   ├── stores/     # Pinia stores
│   │   ├── api/        # API clients
│   │   ├── router/     # Router config
│   │   └── types/      # TypeScript types
│   └── vite.config.ts  # Vite config
└── README.md
```

## API Endpoints

| Method | Path | Description |
|--------|------|-------------|
| POST | /api/login | JWT login |
| GET | /api/me | Current user |
| GET/POST | /api/products | List/Create products |
| GET/PUT | /api/products/{id} | Get/Update product |
| GET/POST | /api/prices | List/Create prices |
| GET/POST | /api/import_tasks | List/Create import tasks |
| GET/POST | /api/queue_jobs | List/Create queue jobs |
| GET/POST | /api/price_rules | List/Create rules |
| GET/POST | /api/shops | List/Create shops |
| GET/POST | /api/marketplaces | List/Create marketplaces |

## Price Formula Syntax

Formulas support variables:
- `{purchase_price}` - Purchase price
- `{wholesale_price}` - Wholesale price
- `{margin}` - Margin percent

Example: `{purchase_price} * 1.3 + 50`

## Development

```bash
# Run Symfony console
ddev exec php bin/console <command>

# Run migrations
ddev exec php bin/console doctrine:migrations:diff
ddev exec php bin/console doctrine:migrations:migrate

# Run tests
ddev exec php bin/phpunit

# Watch logs
ddev logs -f
```
