#Invice Management System

A professinal invoice management Application built with **Angular** and **Symfony**.

## 1. Features

- **Client Searching** search clients by name or company.
- **Invoice Creation** Creating invoices with real-time calculation.
- **Bulk Import** Support importing CSV invoices.
- **UI** Glassmorphic theme.

## 2. Technologies

- **Fronend** Angular 21.2.7, Tailwind CSS
- **Backend** symfony, PHP 8.4
- **Database** SQLite

## 3. Setup

### Backend (Symfony)

1. Navigate to `/backend`.
2. Install dependencies: `composer install`.
3. Create DB: `php bin/console doctrine:database:create`.
4. Run migrations: `php bin/console doctrine:migrations:migrate`.
5. Load sample: `php bin/console doctrine:fixtures:load`.
6. Start server: `symfony serve`.

### Frontend (Angular)

1. Naviagte to `/frontend`.
2. Install dependencies: `npm install`.
3. Strat server: `ng serve`.
4. Open `http://localhost:4200`.
