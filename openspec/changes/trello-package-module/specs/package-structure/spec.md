## ADDED Requirements

### Requirement: Package structure follows Laravel conventions
The system SHALL organize the Trello module as a Laravel package in `applets/trello` following standard package structure.

#### Scenario: Package directory structure exists
- **WHEN** package is examined
- **THEN** directory contains src/, routes/, database/, resources/, tests/ folders

#### Scenario: Source files organized by type
- **WHEN** examining src/ directory
- **THEN** subdirectories exist for Providers, Models, Http/Controllers, Http/Requests, Policies, Services

#### Scenario: Package has composer.json
- **WHEN** package root is examined
- **THEN** composer.json file exists with package metadata and autoload configuration

### Requirement: PSR-4 autoloading configured
The system SHALL configure PSR-4 autoloading for the Trello namespace in package composer.json.

#### Scenario: PSR-4 autoload mapping
- **WHEN** composer.json is examined
- **THEN** autoload section maps "Trello\\" namespace to "src/" directory

#### Scenario: Namespace used in all package classes
- **WHEN** package classes are examined
- **THEN** all classes use Trello namespace (e.g., Trello\Models\Board)

#### Scenario: Composer autoload generation
- **WHEN** composer dump-autoload is run
- **THEN** Trello namespace classes are discoverable

### Requirement: TrelloServiceProvider registers package components
The system SHALL provide a ServiceProvider that registers routes, views, migrations, and policies.

#### Scenario: Service provider class exists
- **WHEN** package is examined
- **THEN** TrelloServiceProvider class exists in src/Providers/

#### Scenario: Routes loaded in boot method
- **WHEN** service provider boots
- **THEN** loadRoutesFrom() registers routes/web.php

#### Scenario: Migrations loaded in boot method
- **WHEN** service provider boots
- **THEN** loadMigrationsFrom() registers database/migrations/

#### Scenario: Views loaded with namespace
- **WHEN** service provider boots
- **THEN** loadViewsFrom() registers views with 'trello' namespace

#### Scenario: Policies registered via Gate
- **WHEN** service provider boots
- **THEN** Gate::policy() registers BoardPolicy, ListPolicy, CardPolicy

#### Scenario: Services bound in container
- **WHEN** service provider register method executes
- **THEN** BoardService, ListService, CardService are registered as singletons

### Requirement: Package registered in main application
The system SHALL register TrelloServiceProvider in the main Laravel application.

#### Scenario: Provider listed in config/app.php
- **WHEN** laragenie/config/app.php is examined
- **THEN** providers array includes Trello\Providers\TrelloServiceProvider::class

#### Scenario: Package loaded during boot
- **WHEN** Laravel application boots
- **THEN** TrelloServiceProvider boot() and register() methods execute

#### Scenario: Application runs without errors
- **WHEN** php artisan serve is executed
- **THEN** application starts successfully with package loaded

### Requirement: Composer path repository configured
The system SHALL configure main application to load package via path repository.

#### Scenario: Path repository in main composer.json
- **WHEN** laragenie/composer.json is examined
- **THEN** repositories array contains path type entry pointing to ../applets/trello

#### Scenario: Package required in dependencies
- **WHEN** laragenie/composer.json is examined
- **THEN** require section includes "internal/trello": "@dev"

#### Scenario: Composer update loads package
- **WHEN** composer update is run from laragenie/
- **THEN** package is symlinked and autoloading configured

### Requirement: Package routes use prefix and middleware
The system SHALL prefix all package routes with /trello and apply auth middleware.

#### Scenario: Routes file uses prefix
- **WHEN** routes/web.php is examined
- **THEN** Route::prefix('trello') wraps all route definitions

#### Scenario: Routes use middleware
- **WHEN** routes/web.php is examined
- **THEN** middleware(['web', 'auth']) is applied to route group

#### Scenario: Routes accessible at /trello path
- **WHEN** application routes are listed
- **THEN** all Trello routes begin with /trello/ prefix

#### Scenario: Named routes use trello prefix
- **WHEN** routes are named
- **THEN** route names begin with trello. (e.g., trello.boards.index)

### Requirement: Package views use namespace
The system SHALL access package views via trello:: namespace.

#### Scenario: Views referenced with namespace
- **WHEN** controllers return views
- **THEN** view names use trello:: prefix (e.g., trello::boards.index)

#### Scenario: Blade components use namespace
- **WHEN** components are referenced in views
- **THEN** component tags use trello:: prefix (e.g., <x-trello::sidebar />)

#### Scenario: Views resolved from package directory
- **WHEN** view is rendered
- **THEN** system loads template from applets/trello/resources/views/

### Requirement: Package migrations run with main app
The system SHALL integrate package migrations into main application migration system.

#### Scenario: Migrations discoverable by artisan
- **WHEN** php artisan migrate:status is run
- **THEN** package migrations appear in migration list

#### Scenario: Migrations execute with migrate command
- **WHEN** php artisan migrate is run
- **THEN** package migrations create boards, trello_lists, cards tables

#### Scenario: Migrations respect dependency order
- **WHEN** migrations are executed
- **THEN** main app migrations run before package migrations (users table exists first)

#### Scenario: Rollback removes package tables
- **WHEN** php artisan migrate:rollback is run with appropriate steps
- **THEN** package tables are dropped in reverse order

### Requirement: Package models accessible from main app
The system SHALL enable main application to interact with package models.

#### Scenario: User model references Board model
- **WHEN** App\Models\User defines boards relationship
- **THEN** relationship uses Trello\Models\Board class

#### Scenario: Models accessible via namespace
- **WHEN** code references Trello models
- **THEN** classes loadable via use Trello\Models\Board statement

#### Scenario: Eloquent relationships work cross-namespace
- **WHEN** User model calls hasMany(Board::class)
- **THEN** relationship functions correctly across namespaces

### Requirement: Tailwind scans package views
The system SHALL configure Tailwind to scan package views for CSS class compilation.

#### Scenario: Tailwind config includes package path
- **WHEN** tailwind.config.js content array is examined
- **THEN** array includes '../applets/trello/resources/views/**/*.blade.php'

#### Scenario: Package view classes compiled
- **WHEN** npm run build is executed
- **THEN** Tailwind classes used in package views are included in compiled CSS

#### Scenario: Package pages styled correctly
- **WHEN** package views are rendered
- **THEN** Tailwind utility classes apply proper styling

### Requirement: Package can be disabled
The system SHALL allow package to be disabled by removing provider registration.

#### Scenario: Removing provider disables package
- **WHEN** TrelloServiceProvider is commented out in config/app.php
- **THEN** package routes, views, and features are unavailable

#### Scenario: Main app runs without package
- **WHEN** package provider is disabled
- **THEN** main application continues to function normally

#### Scenario: Re-enabling provider restores functionality
- **WHEN** provider is uncommented and config cached cleared
- **THEN** package features become available again

### Requirement: Package tests integrate with main test suite
The system SHALL enable package tests to run using main application test environment.

#### Scenario: Package has tests directory
- **WHEN** package structure is examined
- **THEN** tests/Feature/ directory exists with test classes

#### Scenario: Tests extend main TestCase
- **WHEN** package test classes are examined
- **THEN** tests extend Tests\TestCase from main application

#### Scenario: Tests run with artisan test
- **WHEN** php artisan test is executed from main app
- **THEN** package tests execute alongside main app tests

#### Scenario: Tests access package classes
- **WHEN** tests reference Trello classes
- **THEN** classes are properly autoloaded via namespace
