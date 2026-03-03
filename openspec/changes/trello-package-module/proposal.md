## Why

This Laravel application needs a production-ready project management capability structured as a reusable package. Building the Trello module as a self-contained package in `applets/trello` enables modularity, portability, and clear separation from the core application while maintaining full integration with Laravel's ecosystem.

## What Changes

- Add Trello package structure in `applets/trello/` with Laravel package conventions
- Create `TrelloServiceProvider` to register routes, views, migrations, and policies
- Implement composer autoloading (PSR-4) for the Trello namespace within the package
- Register the package provider in `laragenie/config/app.php` or use auto-discovery
- Create database schema for Boards, Lists, and Cards with relationships and constraints
- Implement full CRUD operations with RESTful routing for all entities
- Add Form Request validation classes for data integrity
- Implement Laravel Policies for resource authorization
- Build responsive Blade+Tailwind UI with Kanban layout inside package views
- Add search and filtering capabilities for cards
- Include pagination for boards and cards
- Implement position-based ordering for drag-drop
- Add soft deletes for all entities
- Create database seeders for demo data within the package
- Include Feature tests for CRUD operations and authorization

## Capabilities

### New Capabilities
- `package-structure`: Laravel package setup in `applets/trello` with Service Provider, composer.json autoloading, and registration in main application
- `board-management`: Complete CRUD for boards with ownership, visibility controls (private/public), pagination, and soft deletes
- `list-management`: CRUD for lists with position-based ordering, relationship to boards, and soft deletes
- `card-management`: Full card lifecycle including CRUD, status tracking (todo/in_progress/done), priority levels, due dates, search by title, filtering by status/priority, position ordering, and soft deletes
- `trello-authorization`: Policy-based access control ensuring users can only access their own boards with cascading permissions
- `trello-ui`: Responsive Blade component library with Tailwind CSS including sidebar navigation, Kanban board layout, and mobile-responsive design
- `trello-database`: Migration files within package for boards, lists, cards tables with indexing, foreign keys, cascading rules, and seeders
- `package-integration`: Service provider integration to boot routes, views, migrations, policies within Laravel application

### Modified Capabilities
<!-- No existing capabilities are being modified -->

## Impact

**New Code:**
- `applets/trello/` directory with complete Laravel package structure
- `applets/trello/src/` containing Models, Controllers, Requests, Policies, Services, Providers
- `applets/trello/routes/web.php` for module routes
- `applets/trello/database/migrations/` for database schema
- `applets/trello/resources/views/` for Blade templates
- `applets/trello/composer.json` for package autoloading
- Feature tests in `applets/trello/tests/Feature/`

**Modified Code:**
- `laragenie/config/app.php`: Register `TrelloServiceProvider` in providers array
- `laragenie/app/Models/User.php`: Add `hasMany` relationship for boards

**Dependencies:**
- Existing User model (add relationships)
- Laravel authentication system (routes protected by `auth` middleware)
- Tailwind CSS (already configured via `vite.config.js`)

**Database:**
- Three new tables with foreign key relationships to `users` table
- Indexes for performance on common queries
- Soft delete columns on all tables

**Configuration:**
- Package uses service provider to register all components
- No breaking changes to existing application
- Module can be enabled/disabled by commenting out provider registration
