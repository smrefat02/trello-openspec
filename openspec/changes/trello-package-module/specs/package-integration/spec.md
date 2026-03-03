## ADDED Requirements

### Requirement: Service Provider implements boot method
The system SHALL implement TrelloServiceProvider boot() method to load package resources.

#### Scenario: Boot method loads routes
- **WHEN** boot() method executes
- **THEN** loadRoutesFrom() is called with package routes/web.php path

#### Scenario: Boot method loads migrations
- **WHEN** boot() method executes
- **THEN** loadMigrationsFrom() is called with package database/migrations path

#### Scenario: Boot method loads views
- **WHEN** boot() method executes
- **THEN** loadViewsFrom() is called with package resources/views path and 'trello' namespace

#### Scenario: Boot method registers policies
- **WHEN** boot() method executes
- **THEN** Gate::policy() registers BoardPolicy, ListPolicy, and CardPolicy

### Requirement: Service Provider implements register method
The system SHALL implement TrelloServiceProvider register() method to bind services to container.

#### Scenario: Register binds BoardService
- **WHEN** register() method executes
- **THEN** BoardService is bound as singleton in container

#### Scenario: Register binds ListService
- **WHEN** register() method executes
- **THEN** ListService is bound as singleton in container

#### Scenario: Register binds CardService
- **WHEN** register() method executes
- **THEN** CardService is bound as singleton in container

#### Scenario: Services resolvable from container
- **WHEN** controller resolves service from container
- **THEN** same instance is returned across requests (singleton behavior)

### Requirement: Routes registered with proper configuration
The system SHALL load package routes with appropriate prefix and middleware.

#### Scenario: Routes file loaded from package
- **WHEN** loadRoutesFrom() is called
- **THEN** system loads routes from applets/trello/routes/web.php

#### Scenario: Routes accessible via /trello prefix
- **WHEN** routes are registered
- **THEN** all routes are accessible under /trello/ URL path

#### Scenario: Auth middleware applied
- **WHEN** routes are accessed
- **THEN** authentication middleware protects all routes

#### Scenario: Web middleware group applied
- **WHEN** routes are accessed
- **THEN** web middleware (sessions, CSRF) is active

### Requirement: Migrations registered and discoverable
The system SHALL register package migrations so Laravel can execute them.

#### Scenario: Migrations loaded from package directory
- **WHEN** loadMigrationsFrom() is called
- **THEN** system registers applets/trello/database/migrations directory

#### Scenario: Migrations appear in migrate:status
- **WHEN** php artisan migrate:status is run
- **THEN** package migrations appear in the output

#### Scenario: Migrations execute with migrate command
- **WHEN** php artisan migrate is run
- **THEN** package migrations create database tables

#### Scenario: Migrations rollback with migrate:rollback
- **WHEN** php artisan migrate:rollback is run
- **THEN** package migrations are rolled back

### Requirement: Views registered with namespace
The system SHALL register package views with 'trello' namespace for isolation.

#### Scenario: Views loaded from package directory
- **WHEN** loadViewsFrom() is called
- **THEN** system registers applets/trello/resources/views directory

#### Scenario: Views accessible via namespace
- **WHEN** view('trello::boards.index') is called
- **THEN** system loads view from package directory

#### Scenario: Blade components use namespace
- **WHEN** <x-trello::sidebar /> is used in template
- **THEN** system loads component from package views

#### Scenario: View resolution prioritizes published views
- **WHEN** views are published to main app
- **THEN** published views override package views

### Requirement: Policies registered with Gate facade
The system SHALL register all policy classes via Gate::policy() method.

#### Scenario: BoardPolicy registered
- **WHEN** Gate::policy() is called for Board model
- **THEN** BoardPolicy class is registered as authorization handler

#### Scenario: ListPolicy registered
- **WHEN** Gate::policy() is called for TrelloList model
- **THEN** ListPolicy class is registered as authorization handler

#### Scenario: CardPolicy registered
- **WHEN** Gate::policy() is called for Card model
- **THEN** CardPolicy class is registered as authorization handler

#### Scenario: Policies discoverable by authorize helper
- **WHEN** $this->authorize('update', $board) is called
- **THEN** system finds and executes BoardPolicy::update method

### Requirement: Services bound as singletons
The system SHALL register service classes as singletons in the service container.

#### Scenario: Singleton binding for BoardService
- **WHEN** container resolves BoardService twice
- **THEN** same instance is returned both times

#### Scenario: Singleton binding for ListService
- **WHEN** container resolves ListService twice
- **THEN** same instance is returned both times

#### Scenario: Singleton binding for CardService
- **WHEN** container resolves CardService twice
- **THEN** same instance is returned both times

#### Scenario: Services injected into controllers
- **WHEN** controller constructor type-hints service
- **THEN** container automatically injects service instance

### Requirement: Provider registered in main application
The system SHALL register TrelloServiceProvider in laragenie config/app.php.

#### Scenario: Provider in providers array
- **WHEN** config/app.php is examined
- **THEN** TrelloServiceProvider::class is listed in providers array

#### Scenario: Provider class fully qualified
- **WHEN** provider is registered
- **THEN** full namespace Trello\Providers\TrelloServiceProvider::class is used

#### Scenario: Provider loaded during boot
- **WHEN** Laravel application boots
- **THEN** TrelloServiceProvider register() and boot() methods execute

### Requirement: Package autoloading configured
The system SHALL configure Composer to autoload package classes via PSR-4.

#### Scenario: Package composer.json has autoload config
- **WHEN** applets/trello/composer.json is examined
- **THEN** autoload.psr-4 maps "Trello\\" to "src/"

#### Scenario: Main composer.json has path repository
- **WHEN** laragenie/composer.json is examined
- **THEN** repositories array includes path type pointing to ../applets/trello

#### Scenario: Main composer.json requires package
- **WHEN** laragenie/composer.json is examined
- **THEN** require section includes "internal/trello": "@dev"

#### Scenario: Composer update registers package
- **WHEN** composer update is run
- **THEN** package is symlinked and classes are autoloadable

### Requirement: Application boots successfully with package
The system SHALL ensure Laravel application starts without errors with package loaded.

#### Scenario: php artisan serve starts successfully
- **WHEN** php artisan serve is executed
- **THEN** application starts and listens on port 8000

#### Scenario: No errors during boot
- **WHEN** application boots
- **THEN** no exceptions or errors are thrown

#### Scenario: Package routes are registered
- **WHEN** php artisan route:list is executed
- **THEN** all Trello routes appear with trello prefix

#### Scenario: Package views are discoverable
- **WHEN** view('trello::boards.index') is called
- **THEN** view renders without errors

### Requirement: User model extended with relationship
The system SHALL add boards relationship to existing User model.

#### Scenario: User has boards relationship
- **WHEN** User model is examined
- **THEN** boards() method exists returning hasMany(Board::class)

#### Scenario: Relationship uses fully qualified class name
- **WHEN** boards() relationship is defined
- **THEN** uses Trello\Models\Board::class

#### Scenario: Relationship functions correctly
- **WHEN** $user->boards is accessed
- **THEN** Eloquent returns collection of user's boards

### Requirement: Tailwind configured to scan package views
The system SHALL configure Tailwind CSS to process classes from package views.

#### Scenario: Tailwind config includes package path
- **WHEN** tailwind.config.js content array is examined
- **THEN** array includes '../applets/trello/resources/views/**/*.blade.php'

#### Scenario: Package classes compiled into CSS
- **WHEN** npm run build is executed
- **THEN** Tailwind classes from package views are included in output CSS

#### Scenario: Package pages styled correctly
- **WHEN** package views are rendered in browser
- **THEN** all Tailwind utility classes apply proper styling

### Requirement: Package can be conditionally loaded
The system SHALL allow package to be enabled or disabled via provider registration.

#### Scenario: Commenting provider disables package
- **WHEN** TrelloServiceProvider is commented out in config/app.php
- **THEN** package routes and features are not available

#### Scenario: Uncommenting provider enables package
- **WHEN** TrelloServiceProvider is uncommented and config cleared
- **THEN** package routes and features become available

#### Scenario: Main app unaffected by package state
- **WHEN** package is disabled
- **THEN** main application continues to function normally

### Requirement: Package tests integrate with main test suite
The system SHALL allow package tests to run via main application test command.

#### Scenario: Package tests extend main TestCase
- **WHEN** package test classes are examined
- **THEN** they extend Tests\TestCase from main application

#### Scenario: Package tests run with artisan test
- **WHEN** php artisan test is executed
- **THEN** package tests execute alongside main app tests

#### Scenario: Tests access package classes correctly
- **WHEN** tests import Trello\Models\Board
- **THEN** classes are properly resolved via autoloading

#### Scenario: Tests use package routes
- **WHEN** tests call route('trello.boards.store')
- **THEN** named routes from package are accessible

### Requirement: Error handling for missing dependencies
The system SHALL provide clear error messages if package dependencies are not met.

#### Scenario: Error if User model missing
- **WHEN** package boots and User model does not exist
- **THEN** system throws clear exception indicating User model required

#### Scenario: Error if auth middleware not available
- **WHEN** package routes use auth middleware that is not registered
- **THEN** system throws exception during route registration

#### Scenario: Error if migrations fail
- **WHEN** migration fails due to missing users table
- **THEN** error message indicates dependency on users table

### Requirement: Package follows Laravel package conventions
The system SHALL structure package according to Laravel package development standards.

#### Scenario: Directory structure follows conventions
- **WHEN** package structure is examined
- **THEN** directories match Laravel package pattern (src/, routes/, database/, resources/)

#### Scenario: Namespace follows PSR-4
- **WHEN** class files are examined
- **THEN** namespaces match directory structure (Trello\Models\Board in src/Models/Board.php)

#### Scenario: Service provider extends Laravel base
- **WHEN** TrelloServiceProvider is examined
- **THEN** class extends Illuminate\Support\ServiceProvider

#### Scenario: Package metadata in composer.json
- **WHEN** package composer.json is examined
- **THEN** name, description, type, require sections are properly defined
