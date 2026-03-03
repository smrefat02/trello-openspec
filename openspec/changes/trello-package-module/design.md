## Context

This design implements a Trello-style project management module as a self-contained Laravel package within an existing Laravel 11 application. The application currently has basic user authentication and uses Blade templating with Tailwind CSS.

The module must be structured in `applets/trello/` as a reusable Laravel package that can be loaded into the main `laragenie` application through a Service Provider. This approach enables modularity, potential portability to other projects, and clear architectural boundaries.

**Constraints:**
- Laravel 11 framework conventions
- Package must reside in `applets/trello/` directory
- Package must register itself via Service Provider
- Existing User model in main app cannot be heavily modified
- Blade + Tailwind stack (no heavy JavaScript framework)
- Production-ready: proper authorization, validation, error handling

## Goals / Non-Goals

**Goals:**
- Production-ready Trello clone structured as Laravel package
- Clean separation between package and main application
- Service Provider pattern for component registration
- Proper authorization ensuring users only access their own data
- Responsive UI with Kanban-style layout
- Search, filter, and pagination capabilities
- Comprehensive testing coverage

**Non-Goals:**
- Publishing package to Packagist (internal package only)
- Multi-tenancy or workspace features
- Real-time collaboration (no WebSockets)
- Board sharing between users (private boards only for MVP)
- Card attachments, comments, or activity history
- Integration with external services
- Mobile native apps (responsive web only)

## Decisions

### 1. Package Structure: Laravel Package Pattern in `applets/trello/`

**Decision:** Structure the module as a Laravel package following standard package conventions with Service Provider registration.

**Package Directory Structure:**
```
applets/trello/
├── composer.json (PSR-4 autoload for Trello namespace)
├── src/
│   ├── Providers/
│   │   └── TrelloServiceProvider.php
│   ├── Models/
│   │   ├── Board.php
│   │   ├── TrelloList.php
│   │   └── Card.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── BoardController.php
│   │   │   ├── ListController.php
│   │   │   └── CardController.php
│   │   └── Requests/
│   │       ├── StoreBoardRequest.php
│   │       ├── UpdateBoardRequest.php
│   │       └── ... (other FormRequests)
│   ├── Policies/
│   │   ├── BoardPolicy.php
│   │   ├── ListPolicy.php
│   │   └── CardPolicy.php
│   └── Services/
│       ├── BoardService.php
│       ├── ListService.php
│       └── CardService.php
├── routes/
│   └── web.php
├── database/
│   ├── migrations/
│   │   ├── 2026_03_03_000001_create_boards_table.php
│   │   ├── 2026_03_03_000002_create_trello_lists_table.php
│   │   └── 2026_03_03_000003_create_cards_table.php
│   └── seeders/
│       ├── BoardSeeder.php
│       ├── ListSeeder.php
│       └── CardSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── components/
│       │   ├── sidebar.blade.php
│       │   ├── board-card.blade.php
│       │   ├── list-column.blade.php
│       │   └── card-item.blade.php
│       └── boards/
│           ├── index.blade.php
│           ├── show.blade.php
│           └── ...
└── tests/
    └── Feature/
        ├── BoardTest.php
        └── CardTest.php
```

**Rationale:**
- **Modularity**: Complete isolation of Trello code from main application
- **Portability**: Package can potentially be extracted to standalone repo
- **Laravel Standard**: Follows Laravel package development conventions
- **Maintainability**: All related code grouped together
- **Testability**: Package can have its own test suite

**Alternatives Considered:**
- Monolith in `app/` directory: Rejected - less modular, harder to extract
- Composer-loaded external package: Overkill for internal-only module

### 2. Service Provider Registration Pattern

**Decision:** Create `TrelloServiceProvider` that registers routes, views, migrations, policies, and other components during Laravel boot process.

**TrelloServiceProvider Implementation:**
```php
namespace Trello\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class TrelloServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register services
        $this->app->singleton(BoardService::class);
        $this->app->singleton(ListService::class);
        $this->app->singleton(CardService::class);
    }

    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'trello');
        
        // Register policies
        Gate::policy(Board::class, BoardPolicy::class);
        Gate::policy(TrelloList::class, ListPolicy::class);
        Gate::policy(Card::class, CardPolicy::class);
    }
}
```

**Main App Registration** (`laragenie/config/app.php`):
```php
'providers' => [
    // ... other providers
    \Trello\Providers\TrelloServiceProvider::class,
],
```

**Rationale:**
- **Laravel Pattern**: Standard way to register package components
- **Boot Lifecycle**: Components loaded during application bootstrap
- **Centralized**: Single point of integration with main app
- **Configurable**: Easy to enable/disable by commenting provider

**Alternatives Considered:**
- Manual route inclusion: Rejected - doesn't follow Laravel conventions
- Package auto-discovery: Considered but requires composer setup complexity

### 3. Composer Autoloading for Package Namespace

**Decision:** Create `applets/trello/composer.json` with PSR-4 autoloading for `Trello\` namespace, then require it in main app's `composer.json`.

**Package composer.json:**
```json
{
    "name": "internal/trello",
    "description": "Trello-style project management module",
    "type": "library",
    "autoload": {
        "psr-4": {
            "Trello\\": "src/"
        }
    },
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0"
    }
}
```

**Main App composer.json** (add to repositories and require):
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../applets/trello"
        }
    ],
    "require": {
        "internal/trello": "@dev"
    }
}
```

**Rationale:**
- **PSR-4 Standard**: Industry standard autoloading
- **Namespace Isolation**: `Trello\` namespace separate from `App\`
- **Composer Integration**: Leverages existing dependency management
- **Development Mode**: `@dev` allows local development

**Alternatives Considered:**
- Manual classmap in main composer.json: Rejected - less maintainable
- Direct PSR-4 in main composer.json: Works but less portable

### 4. Database Schema Design

**Decision:** Same schema as modular approach with three tables, but migrations live in package directory.

**Schema remains:**
- **boards**: id, user_id (FK CASCADE), title, description, visibility (enum), soft deletes
- **trello_lists**: id, board_id (FK CASCADE), title, position, soft deletes
- **cards**: id, list_id (FK CASCADE), title, description, due_date, status (enum), priority (enum), position, soft deletes

**Migration Location:** `applets/trello/database/migrations/`

**Rationale:**
- Same proven schema design
- Migrations packaged with module
- `loadMigrationsFrom()` in provider makes Laravel discover them
- Enables `php artisan migrate` to process package migrations

**Alternatives Considered:**
- Migrations in main app: Rejected - breaks package encapsulation
- Publishable migrations: Considered for later if needed

### 5. View Namespace and Blade Components

**Decision:** Register views with `trello` namespace, reference as `trello::boards.index`.

**View Loading:**
```php
// In TrelloServiceProvider
$this->loadViewsFrom(__DIR__.'/../../resources/views', 'trello');
```

**Usage in Routes/Controllers:**
```php
return view('trello::boards.index', ['boards' => $boards]);
```

**Blade Components:**
```blade
{{-- In package views --}}
<x-trello::sidebar :boards="$boards" />
<x-trello::card-item :card="$card" />
```

**Rationale:**
- **Namespace Isolation**: Prevents conflicts with main app views
- **Laravel Pattern**: Standard package view registration
- **Component Prefix**: `trello::` makes component origin clear
- **Overridable**: Main app can publish and override views if needed

**Alternatives Considered:**
- No namespace (direct loading): Rejected - naming conflicts likely
- Publishing all views to main app: Makes updating package harder

### 6. Route Prefixing and Middleware

**Decision:** Prefix all Trello routes with `/trello` and apply `auth` middleware in package route file.

**Route File** (`applets/trello/routes/web.php`):
```php
use Illuminate\Support\Facades\Route;
use Trello\Http\Controllers\BoardController;

Route::prefix('trello')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::resource('boards', BoardController::class);
        Route::post('lists', [ListController::class, 'store'])->name('trello.lists.store');
        // ... other routes
    });
```

**Rationale:**
- **Namespace Separation**: Clear URL structure for Trello features
- **Authentication**: `auth` middleware protects all routes
- **Web Middleware**: Sessions, CSRF protection via `web` group
- **Named Routes**: Route names prefixed with `trello.` for clarity

**Alternatives Considered:**
- No prefix: Rejected - potential route conflicts with main app
- API routes: Not needed for Blade-based UI (future consideration)

### 7. Cross-Package Model Relationships

**Decision:** Trello models reference User model from main app via fully-qualified class name.

**Board Model:**
```php
namespace Trello\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**User Model** (in main app):
```php
namespace App\Models;

use Trello\Models\Board;

class User extends Authenticatable
{
    public function boards()
    {
        return $this->hasMany(Board::class);
    }
}
```

**Rationale:**
- **Proper ORM**: Eloquent relationships work across namespaces
- **Type Hints**: Full class names ensure correct resolution
- **Flexibility**: User model extended with minimal changes
- **Single Source**: User remains in main app (auth boundary)

**Alternatives Considered:**
- Duplicate User model in package: Rejected - breaks single source of truth
- Interface-based user reference: Overkill for straightforward relationship

### 8. Service Layer Pattern

**Decision:** Same service layer pattern as modular approach, but services live in package.

**Services Location:** `applets/trello/src/Services/`

**Pattern:**
```php
namespace Trello\Services;

class BoardService
{
    public function createBoard(array $data): Board
    {
        return DB::transaction(function () use ($data) {
            return Board::create($data + ['user_id' => auth()->id()]);
        });
    }
}
```

**Rationale:**
- Proven pattern for business logic separation
- Services registered in provider's `register()` method
- Same testability and reusability benefits

### 9. Testing Strategy

**Decision:** Package tests in `applets/trello/tests/` run via main app's test suite.

**Test Configuration:**
- Tests use main app's `TestCase`
- Reference package classes via `Trello\` namespace
- Database refreshed with package migrations

**Example Test:**
```php
namespace Trello\Tests\Feature;

use Tests\TestCase;
use Trello\Models\Board;
use App\Models\User;

class BoardTest extends TestCase
{
    public function test_user_can_create_board()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('trello.boards.store'), [
                'title' => 'Test Board',
                'visibility' => 'private',
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('boards', ['title' => 'Test Board']);
    }
}
```

**Rationale:**
- Integration testing with real Laravel environment
- Tests package within context of main app
- Validates Service Provider registration

## Risks / Trade-offs

### 1. Package Path Dependency Complexity
**Risk:** Path-based composer dependency may confuse developers unfamiliar with pattern.  
**Mitigation:** Document setup clearly in README. Path repositories are standard Laravel package development approach.

### 2. Migration Execution Order
**Risk:** Package migrations must run after `users` table exists.  
**Mitigation:** Timestamp migrations appropriately. Migrations run in chronological order across all sources (main app + packages).

### 3. View Override Complexity
**Risk:** If main app publishes views, keeping them in sync with package updates is harder.  
**Mitigation:** For MVP, don't publish views. Document that view customization requires forking package.

### 4. Namespace Conflicts
**Risk:** Global `Trello` namespace could conflict with future packages.  
**Mitigation:** Unique top-level namespace is acceptable for internal package. If publishing externally, use vendor prefix (e.g., `Internal\Trello`).

### 5. Service Provider Registration Order
**Risk:** If provider loads before auth system, errors may occur.  
**Mitigation:** Register provider after auth providers in `config/app.php`. Laravel's deferred providers can help if needed.

### 6. Asset Compilation
**Risk:** Package views reference Tailwind classes that must be compiled into main app's CSS.  
**Mitigation:** Main app's `tailwind.config.js` must scan `applets/trello/resources/views/**/*.blade.php` in content paths.

### 7. Database Transaction Scope
**Risk:** Transactions in package services may interact unexpectedly with main app transactions.  
**Mitigation:** Use explicit DB::transaction() in services. Document transaction boundaries clearly.

## Migration Plan

### Deployment Steps:

1. **Create package structure** in `applets/trello/` with all directories
2. **Create package composer.json** with PSR-4 autoload for `Trello\` namespace
3. **Update main composer.json** to add path repository and require package
4. **Run `composer update`** to register package autoloading
5. **Create TrelloServiceProvider** with route, migration, view loading
6. **Register provider** in `laragenie/config/app.php`
7. **Run migrations** with `php artisan migrate` (package migrations auto-discovered)
8. **Update Tailwind config** to scan package views for CSS compilation
9. **Build assets** with `npm run build`
10. **Update User model** to add `boards` relationship
11. **Seed demo data** (optional) with `php artisan db:seed`
12. **Run tests** to verify integration

### Rollback Strategy:

- **Remove provider registration** from `config/app.php` (disables package)
- **Rollback migrations** for package tables
- **Remove composer dependency** and run `composer update`
- **Remove package directory** if needed
- **Zero impact** on main app - no breaking changes

### Compatibility:

- No breaking changes to existing features
- User model only extended with relationship (non-breaking)
- All Trello routes under `/trello` prefix (no conflicts)
- Package can be disabled without affecting main app

## Open Questions

1. **Composer Discovery:** Should we add package discovery configuration to enable automatic provider registration? (Requires extra composer setup)

2. **Config Publishing:** Should package have publishable config file for customization (e.g., route prefix, pagination size)? (Defer to future iteration)

3. **Event System:** If adding activity logging later, should events be package-scoped or use main app's event bus? (Recommend main app's event system for cross-package visibility)

4. **Asset Publishing:** Should package publish JS/CSS assets separately, or rely on main app's Vite build? (Current approach: main app Vite scans package views for Tailwind classes)

5. **Multiple Packages:** If adding more packages (Chat, Calendar), should we standardize on shared base provider or utilities? (Evaluate when second package is added)
