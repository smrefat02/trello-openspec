## 1. Package Structure Setup

- [x] 1.1 Create applets/trello directory structure (src/, routes/, database/, resources/, tests/)
- [x] 1.2 Create src subdirectories (Providers/, Models/, Http/Controllers/, Http/Requests/, Policies/, Services/)
- [x] 1.3 Create database subdirectories (migrations/, seeders/)
- [x] 1.4 Create resources/views subdirectories (layouts/, components/, boards/)
- [x] 1.5 Create tests/Feature directory

## 2. Package Composer Configuration

- [x] 2.1 Create applets/trello/composer.json with package metadata (name: internal/trello, type: library)
- [x] 2.2 Add PSR-4 autoload configuration mapping "Trello\\" namespace to "src/" directory
- [x] 2.3 Add require section with PHP ^8.2 and Laravel ^11.0 dependencies
- [x] 2.4 Update laragenie/composer.json to add path repository pointing to ../applets/trello
- [x] 2.5 Add "internal/trello": "@dev" to laragenie/composer.json require section
- [x] 2.6 Run composer update from laragenie directory to register package

## 3. Service Provider Creation

- [x] 3.1 Create TrelloServiceProvider class in applets/trello/src/Providers/
- [x] 3.2 Implement register() method to bind BoardService, ListService, CardService as singletons
- [x] 3.3 Implement boot() method to load routes with loadRoutesFrom(__DIR__.'/../../routes/web.php')
- [x] 3.4 Add loadMigrationsFrom(__DIR__.'/../../database/migrations') to boot method
- [x] 3.5 Add loadViewsFrom(__DIR__.'/../../resources/views', 'trello') to boot method
- [x] 3.6 Register policies in boot() with Gate::policy(Board::class, BoardPolicy::class) for all three models
- [x] 3.7 Register TrelloServiceProvider in laragenie/config/app.php providers array

## 4. Database Migrations

- [x] 4.1 Create 2026_03_03_000001_create_boards_table.php in applets/trello/database/migrations/
- [x] 4.2 Add columns to boards migration: id, user_id (FK CASCADE), title, description, visibility (enum private/public), deleted_at, timestamps
- [x] 4.3 Add indexes: title, user_id (via FK), composite deleted_at
- [x] 4.4 Set default visibility='private' in boards migration
- [x] 4.5 Create 2026_03_03_000002_create_trello_lists_table.php migration
- [x] 4.6 Add columns to lists migration: id, board_id (FK CASCADE), title, position, deleted_at, timestamps
- [x] 4.7 Add indexes: (board_id, position) composite, position
- [x] 4.8 Create 2026_03_03_000003_create_cards_table.php migration
- [x] 4.9 Add columns to cards migration: id, list_id (FK CASCADE), title, description, due_date, status (enum), priority (enum), position, deleted_at, timestamps
- [x] 4.10 Add indexes to cards: title, due_date, status, priority, (list_id, position) composite, (status, priority) composite
- [x] 4.11 Set defaults: status='todo', priority='medium' in cards migration
- [x] 4.12 Test migrations with php artisan migrate from laragenie directory

## 5. Eloquent Models

- [x] 5.1 Create Board model in applets/trello/src/Models/ with namespace Trello\Models
- [x] 5.2 Add SoftDeletes trait, fillable fields, casts for visibility enum to Board model
- [x] 5.3 Add relationships to Board: belongsTo(User::class), hasMany(TrelloList::class)
- [x] 5.4 Create TrelloList model in applets/trello/src/Models/
- [x] 5.5 Add SoftDeletes trait, fillable fields, relationships to TrelloList: belongsTo(Board::class), hasMany(Card::class)
- [x] 5.6 Create Card model in applets/trello/src/Models/
- [x] 5.7 Add SoftDeletes trait, fillable fields, casts for enums and due_date to Card model
- [x] 5.8 Add relationship to Card: belongsTo(TrelloList::class, 'list_id')
- [x] 5.9 Update laragenie/app/Models/User.php to add boards() hasMany relationship using Trello\Models\Board

## 6. Service Layer

- [x] 6.1 Create BoardService in applets/trello/src/Services/ with namespace Trello\Services
- [x] 6.2 Implement createBoard, updateBoard, deleteBoard methods using DB transactions
- [x] 6.3 Add searchBoards method with title search and pagination to BoardService
- [x] 6.4 Create ListService in applets/trello/src/Services/
- [x] 6.5 Implement createList, updateList, deleteList methods with position management
- [x] 6.6 Add position calculation logic: max(position) + 10 for new lists
- [x] 6.7 Create CardService in applets/trello/src/Services/
- [x] 6.8 Implement createCard, updateCard, deleteCard, updatePosition methods
- [x] 6.9 Add searchAndFilterCards method with title search, status/priority filters, pagination
- [x] 6.10 Add position management logic to CardService

## 7. Form Request Validation

- [x] 7.1 Create StoreBoardRequest in applets/trello/src/Http/Requests/ with namespace Trello\Http\Requests
- [x] 7.2 Add validation rules: title required|max:255, description nullable|max:1000, visibility required|in:private,public
- [x] 7.3 Create UpdateBoardRequest with same validation rules
- [x] 7.4 Create StoreListRequest with rules: title required|max:255, board_id required|exists:boards,id
- [x] 7.5 Create UpdateListRequest with rules: title required|max:255, position nullable|integer
- [x] 7.6 Create StoreCardRequest with rules: title required|max:255, description nullable|max:2000, list_id required|exists:trello_lists,id, due_date nullable|date, status/priority nullable with enum values
- [x] 7.7 Create UpdateCardRequest with same validation plus position nullable|integer
- [x] 7.8 Add custom error messages to all FormRequests for better UX

## 8. Authorization Policies

- [x] 8.1 Create BoardPolicy in applets/trello/src/Policies/ with namespace Trello\Policies
- [x] 8.2 Implement viewAny, view, create, update, delete methods checking board->user_id === auth()->id()
- [x] 8.3 Create ListPolicy with create, update, delete methods checking board ownership via list->board->user_id
- [x] 8.4 Create CardPolicy with create, update, delete methods checking board ownership via card->list->board->user_id
- [x] 8.5 Verify policies are registered in TrelloServiceProvider boot() method via Gate::policy()

## 9. Controllers

- [x] 9.1 Create BoardController in applets/trello/src/Http/Controllers/ with namespace Trello\Http\Controllers
- [x] 9.2 Implement index, create, store, show, edit, update, destroy methods in BoardController
- [x] 9.3 Inject BoardService via constructor dependency injection
- [x] 9.4 Add authorize() calls for update and destroy actions in BoardController
- [x] 9.5 Create ListController with store, update, destroy methods
- [x] 9.6 Inject ListService and add authorize() calls to ListController
- [x] 9.7 Create CardController with store, update, destroy methods
- [x] 9.8 Inject CardService and add authorize() calls to CardController
- [x] 9.9 Implement search/filter parameter handling in BoardController show method
- [x] 9.10 Use route model binding for Board, TrelloList, Card models in route definitions

## 10. Routes Definition

- [x] 10.1 Create routes/web.php in applets/trello/ directory
- [x] 10.2 Add Route::prefix('trello')->middleware(['web', 'auth'])->group() wrapper
- [x] 10.3 Define resource routes: Route::resource('boards', BoardController::class) with named routes
- [x] 10.4 Add list routes: Route::post('lists', [ListController::class, 'store'])->name('trello.lists.store')
- [x] 10.5 Add list update/destroy routes with route model binding
- [x] 10.6 Add card routes: Route::post('cards', [CardController::class, 'store'])->name('trello.cards.store')
- [x] 10.7 Add card update/destroy routes with route model binding
- [x] 10.8 Verify routes load via TrelloServiceProvider and test with php artisan route:list

## 11. Blade Layout & Components

- [x] 11.1 Create resources/views/layouts/app.blade.php in package as Trello-specific layout
- [x] 11.2 Create resources/views/components/sidebar.blade.php displaying boards list with namespace trello::
- [x] 11.3 Create resources/views/components/board-card.blade.php for board preview cards
- [x] 11.4 Create resources/views/components/list-column.blade.php for Kanban list columns
- [x] 11.5 Create resources/views/components/card-item.blade.php with title, due date, status/priority badges
- [x] 11.6 Create resources/views/components/modal.blade.php for reusable modals
- [x] 11.7 Create resources/views/components/flash-message.blade.php for success/error alerts
- [x] 11.8 Apply Tailwind CSS classes to all components

## 12. Blade Views

- [x] 12.1 Create resources/views/boards/index.blade.php with grid layout, search input, pagination
- [x] 12.2 Add "Create New Board" button and empty state to index view
- [x] 12.3 Create resources/views/boards/create.blade.php with board creation form
- [x] 12.4 Create resources/views/boards/edit.blade.php with board edit form
- [x] 12.5 Create resources/views/boards/show.blade.php with Kanban layout (horizontal lists, vertical cards)
- [x] 12.6 Add search input and status/priority filter dropdowns to board show view
- [x] 12.7 Add "Add List" and "Add Card" buttons to board show view
- [x] 12.8 Display validation errors and flash messages using components
- [x] 12.9 Add responsive Tailwind classes for mobile/tablet/desktop breakpoints
- [x] 12.10 Implement collapsible sidebar for mobile using Alpine.js or vanilla JS

## 13. JavaScript Interactivity

- [x] 13.1 Create public JavaScript file or inline scripts in views for Trello functionality
- [x] 13.2 Implement modal show/hide functionality with vanilla JavaScript
- [x] 13.3 Implement flash message auto-hide (5s timeout) and dismiss button handler
- [x] 13.4 Add HTML5 drag-and-drop event listeners for cards (dragstart, dragover, drop)
- [x] 13.5 Create AJAX request to update card position on drop using Fetch API
- [x] 13.6 Add drag visual feedback (cursor change, drop zone highlighting with CSS classes)
- [x] 13.7 Implement drag-and-drop for list reordering
- [x] 13.8 Add form submission loading states (disable button, show spinner)
- [x] 13.9 Update laragenie/tailwind.config.js content array to include '../applets/trello/resources/views/**/*.blade.php'
- [x] 13.10 Run npm run build from laragenie to compile assets with package views

## 14. Database Seeders

- [x] 14.1 Create BoardSeeder in applets/trello/database/seeders/
- [x] 14.2 Implement BoardSeeder to create 5 sample boards for first user
- [x] 14.3 Create ListSeeder to create 3 lists per board ("To Do", "In Progress", "Done")
- [x] 14.4 Create CardSeeder to create 10-15 cards with varied status, priority, due dates
- [x] 14.5 Ensure seeders use Trello\Models namespace and respect foreign key relationships
- [x] 14.6 Create TrelloSeeder main seeder class to call all package seeders in order
- [x] 14.7 Test seeders by calling them from DatabaseSeeder or directly with php artisan db:seed --class=Trello\\Database\\Seeders\\TrelloSeeder

## 15. Feature Tests

- [ ] 15.1 Create tests/Feature/BoardTest.php in package with namespace Trello\Tests\Feature
- [ ] 15.2 Extend Tests\TestCase from main application in all test classes
- [ ] 15.3 Write test: authenticated user can view their boards list
- [ ] 15.4 Write test: authenticated user can create a board
- [ ] 15.5 Write test: authenticated user can update their board
- [ ] 15.6 Write test: authenticated user can delete their board
- [ ] 15.7 Write test: user cannot view another user's board (403)
- [ ] 15.8 Write test: user cannot update another user's board (403)
- [ ] 15.9 Write test: user cannot delete another user's board (403)
- [ ] 15.10 Write test: board creation validation fails without title
- [ ] 15.11 Create tests/Feature/CardTest.php
- [ ] 15.12 Write test: user can create card on their list
- [ ] 15.13 Write test: user can update card on their list
- [ ] 15.14 Write test: user can delete card from their list
- [ ] 15.15 Write test: user cannot create card on another user's board (403)
- [ ] 15.16 Write test: card search by title works correctly
- [ ] 15.17 Write test: card filtering by status works correctly
- [ ] 15.18 Write test: card filtering by priority works correctly
- [ ] 15.19 Run tests with php artisan test from laragenie directory

## 16. Integration & Configuration

- [ ] 16.1 Verify TrelloServiceProvider is registered in laragenie/config/app.php providers array
- [ ] 16.2 Clear Laravel config cache with php artisan config:clear
- [ ] 16.3 Clear Laravel route cache with php artisan route:clear
- [ ] 16.4 Verify package routes appear in php artisan route:list with /trello prefix
- [ ] 16.5 Test views load correctly: access /trello/boards and verify trello::boards.index renders
- [ ] 16.6 Verify Tailwind classes from package views are compiled into app.css
- [ ] 16.7 Test that package migrations run: php artisan migrate:status shows package migrations
- [ ] 16.8 Verify User model boards relationship works: $user->boards returns collection

## 17. Final Testing & Polish

- [ ] 17.1 Add navigation link to Trello module in main application navigation
- [ ] 17.2 Test complete user flow: create board → add list → add card → search/filter → delete
- [ ] 17.3 Verify all flash messages display correctly
- [ ] 17.4 Test responsive layout on mobile, tablet, desktop browsers
- [ ] 17.5 Verify drag-and-drop works smoothly for cards and lists
- [ ] 17.6 Test pagination on boards index and card lists
- [ ] 17.7 Verify soft deletes: deleted items don't appear in queries
- [ ] 17.8 Check authorization: users cannot access others' boards
- [ ] 17.9 Verify cascading deletes: deleting board removes lists and cards
- [ ] 17.10 Test that commenting out TrelloServiceProvider disables package without breaking main app
- [ ] 17.11 Run php artisan optimize to cache routes and config
- [ ] 17.12 Update main README.md with Trello package documentation and setup instructions

## 18. UI/UX Enhancement - Trello-Style Design

- [x] 18.1 Update board header background with gradient (blue-600 to blue-700), make header sticky
- [x] 18.2 Improve list column styling: add rounded corners (rounded-lg), subtle shadow, lighter background (#ebecf0)
- [x] 18.3 Update card styling: white background, rounded-md, shadow-sm, hover:shadow-md transition, remove onclick border
- [x] 18.4 Refine status badges: use pill-shaped design with proper colors (green for done, blue for in progress, gray for todo)
- [x] 18.5 Improve priority badges: red for high (bg-red-100 text-red-700), yellow for medium, blue for low
- [x] 18.6 Add card hover effects: slight elevation, cursor-pointer visual feedback
- [x] 18.7 Update list header with better typography: font-semibold text-sm text-gray-700
- [x] 18.8 Add horizontal scroll indicators for board with many lists
- [x] 18.9 Improve "Add Card" button styling: subtle gray background, hover state, icon support
- [x] 18.10 Update drag-and-drop visual feedback: opacity 0.5 for dragging, blue-100 background for drop zones
- [x] 18.11 Add card count badge to list headers showing number of cards
- [x] 18.12 Improve modal styling: larger modals, better spacing, consistent button styling
- [x] 18.13 Add smooth transitions for all interactive elements (transition-all duration-200)
- [x] 18.14 Update board card grid on index page: better spacing, hover effects, board preview thumbnails
- [x] 18.15 Add empty state illustrations/icons for "no cards" and "no lists" states
- [x] 18.16 Compile CSS with npm run build and verify all Tailwind classes are included

## 19. Advanced Card Features - Labels, Checklists, Comments

- [x] 19.1 Create card_labels migration: id, card_id (FK), name, color (enum), position, timestamps
- [x] 19.2 Create card_checklists migration: id, card_id (FK), title, position, timestamps
- [x] 19.3 Create checklist_items migration: id, checklist_id (FK), title, is_completed (boolean), position, timestamps
- [x] 19.4 Create card_comments migration: id, card_id (FK), user_id (FK), comment (text), timestamps
- [x] 19.5 Create card_attachments migration: id, card_id (FK), user_id (FK), filename, file_path, file_size, mime_type, timestamps
- [x] 19.6 Create card_members migration: id, card_id (FK), user_id (FK), timestamps (pivot table)
- [x] 19.7 Create CardLabel model with relationships: belongsTo(Card::class)
- [x] 19.8 Create CardChecklist model with relationships: belongsTo(Card::class), hasMany(ChecklistItem::class)
- [x] 19.9 Create ChecklistItem model with relationships: belongsTo(CardChecklist::class)
- [x] 19.10 Create CardComment model with relationships: belongsTo(Card::class), belongsTo(User::class)
- [x] 19.11 Create CardAttachment model with relationships: belongsTo(Card::class), belongsTo(User::class)
- [x] 19.12 Update Card model: add hasMany(CardLabel), hasMany(CardChecklist), hasMany(CardComment), hasMany(CardAttachment), belongsToMany(User::class, 'card_members')
- [x] 19.13 Run migrations: php artisan migrate
- [x] 19.14 Create LabelController with store, update, destroy methods for card labels
- [x] 19.15 Create ChecklistController with store, update, destroy methods for checklists
- [x] 19.16 Create ChecklistItemController with store, update (toggle completion), destroy methods
- [x] 19.17 Create CommentController with store, update, destroy methods for card comments
- [x] 19.18 Create AttachmentController with store, destroy methods (with file upload handling)
- [x] 19.19 Add routes for labels, checklists, checklist items, comments, attachments
- [x] 19.20 Update CardDetailsModal to show labels section with color badges
- [x] 19.21 Add "Add Label" button in modal sidebar with label creation form
- [x] 19.22 Display checklists in card details modal with progress bar
- [x] 19.23 Add "Add Checklist" button in modal sidebar
- [x] 19.24 Implement checklist item toggle functionality (mark complete/incomplete)
- [x] 19.25 Create comments section in modal with "Add a comment" textarea
- [x] 19.26 Display activity timeline showing card creation, label additions, checklist updates
- [x] 19.27 Add "Add an item" button in checklists to create new checklist items
- [x] 19.28 Style labels with predefined color palette (purple, green, yellow, orange, red, blue)
- [x] 19.29 Add members section to card with avatar display
- [x] 19.30 Implement file upload for attachments with progress indicator
- [x] 19.31 Compile CSS and test all new features- [x] 19.32 Fix checklist counter real-time update: add updateCardChecklistDisplay() function to sync board view when checklist items are toggled in modal
- [x] 19.33 Add checklist-counter CSS class to card-item.blade.php for JavaScript targeting of checklist display