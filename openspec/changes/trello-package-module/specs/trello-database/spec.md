## ADDED Requirements

### Requirement: Boards table structure
The system SHALL create a boards table with proper columns, indexes, and constraints.

#### Scenario: Boards table columns
- **WHEN** boards migration is executed
- **THEN** table includes id, user_id, title, description, visibility, deleted_at, created_at, updated_at

#### Scenario: User foreign key constraint
- **WHEN** boards table is created
- **THEN** user_id has foreign key constraint to users.id with ON DELETE CASCADE

#### Scenario: Title index for performance
- **WHEN** boards table is created
- **THEN** title column has index for search optimization

#### Scenario: Soft delete column
- **WHEN** boards table is created
- **THEN** deleted_at column exists for soft delete functionality

#### Scenario: Visibility default value
- **WHEN** boards table is created
- **THEN** visibility column has default value of 'private'

### Requirement: Lists table structure
The system SHALL create a trello_lists table with proper columns, indexes, and constraints.

#### Scenario: Lists table columns
- **WHEN** trello_lists migration is executed
- **THEN** table includes id, board_id, title, position, deleted_at, created_at, updated_at

#### Scenario: Board foreign key constraint
- **WHEN** trello_lists table is created
- **THEN** board_id has foreign key constraint to boards.id with ON DELETE CASCADE

#### Scenario: Position index for ordering
- **WHEN** trello_lists table is created
- **THEN** position column has index for efficient ordering queries

#### Scenario: Composite index for board lists
- **WHEN** trello_lists table is created
- **THEN** composite index exists on (board_id, position) for optimized retrieval

#### Scenario: Soft delete column
- **WHEN** trello_lists table is created
- **THEN** deleted_at column exists for soft delete functionality

### Requirement: Cards table structure
The system SHALL create a cards table with proper columns, indexes, and constraints.

#### Scenario: Cards table columns
- **WHEN** cards migration is executed
- **THEN** table includes id, list_id, title, description, due_date, status, priority, position, deleted_at, created_at, updated_at

#### Scenario: List foreign key constraint
- **WHEN** cards table is created
- **THEN** list_id has foreign key constraint to trello_lists.id with ON DELETE CASCADE

#### Scenario: Title index for search
- **WHEN** cards table is created
- **THEN** title column has index for search optimization

#### Scenario: Due date index for filtering
- **WHEN** cards table is created
- **THEN** due_date column has index for date-based queries

#### Scenario: Status index for filtering
- **WHEN** cards table is created
- **THEN** status column has index for status filter queries

#### Scenario: Priority index for filtering
- **WHEN** cards table is created
- **THEN** priority column has index for priority filter queries

#### Scenario: Position index for ordering
- **WHEN** cards table is created
- **THEN** position column has index for ordering

#### Scenario: Composite index for list cards
- **WHEN** cards table is created
- **THEN** composite index exists on (list_id, position) for optimized retrieval

#### Scenario: Composite index for status-priority filtering
- **WHEN** cards table is created
- **THEN** composite index exists on (status, priority) for combined filter queries

#### Scenario: Status enum constraint
- **WHEN** cards table is created
- **THEN** status column is constrained to values: todo, in_progress, done

#### Scenario: Priority enum constraint
- **WHEN** cards table is created
- **THEN** priority column is constrained to values: low, medium, high

#### Scenario: Status default value
- **WHEN** cards table is created
- **THEN** status column has default value of 'todo'

#### Scenario: Priority default value
- **WHEN** cards table is created
- **THEN** priority column has default value of 'medium'

#### Scenario: Soft delete column
- **WHEN** cards table is created
- **THEN** deleted_at column exists for soft delete functionality

### Requirement: Migrations located in package directory
The system SHALL place all migration files within the package's database/migrations directory.

#### Scenario: Migration files in package
- **WHEN** package structure is examined
- **THEN** migrations exist in applets/trello/database/migrations/

#### Scenario: Migrations loaded via Service Provider
- **WHEN** TrelloServiceProvider boots
- **THEN** loadMigrationsFrom() registers package migration directory

#### Scenario: Migrations discoverable by artisan
- **WHEN** php artisan migrate:status is run
- **THEN** package migrations appear in the list

### Requirement: Cascading delete relationships
The system SHALL implement cascading deletes from boards to lists to cards.

#### Scenario: Deleting board cascades to lists
- **WHEN** a board is deleted
- **THEN** all associated lists are automatically deleted via CASCADE

#### Scenario: Deleting list cascades to cards
- **WHEN** a list is deleted
- **THEN** all associated cards are automatically deleted via CASCADE

#### Scenario: Deleting board cascades to all nested resources
- **WHEN** a board is deleted
- **THEN** both lists and their cards are deleted through cascade chain

### Requirement: Migration files are timestamped and ordered
The system SHALL create migrations in proper dependency order for execution.

#### Scenario: Boards migration before lists
- **WHEN** migrations are executed
- **THEN** boards table is created before trello_lists table

#### Scenario: Lists migration before cards
- **WHEN** migrations are executed
- **THEN** trello_lists table is created before cards table

#### Scenario: Migration timestamp ordering
- **WHEN** migrations exist
- **THEN** file timestamps ensure correct execution order (boards < trello_lists < cards)

### Requirement: Migration rollback support
The system SHALL include down() methods in all migrations for rollback capability.

#### Scenario: Rollback drops tables in reverse order
- **WHEN** migrations are rolled back
- **THEN** tables are dropped in reverse order: cards, trello_lists, boards

#### Scenario: Rollback removes foreign key constraints
- **WHEN** migrations are rolled back
- **THEN** foreign key constraints are properly removed before dropping tables

### Requirement: Column data types are appropriate
The system SHALL use appropriate data types for all columns.

#### Scenario: ID columns use big integers
- **WHEN** tables are created
- **THEN** id columns use bigIncrements (BIGINT UNSIGNED)

#### Scenario: Foreign keys match referenced types
- **WHEN** foreign keys are defined
- **THEN** data type matches the referenced column type

#### Scenario: Title columns use string type
- **WHEN** tables with title columns are created
- **THEN** title uses string type with length 255

#### Scenario: Description columns use text type
- **WHEN** tables with description columns are created
- **THEN** description uses text type for longer content

#### Scenario: Enum columns use enum type
- **WHEN** status and priority columns are created
- **THEN** columns use enum type with defined values

#### Scenario: Date columns use date type
- **WHEN** due_date column is created
- **THEN** column uses date type (not datetime)

#### Scenario: Position columns use integer type
- **WHEN** position columns are created
- **THEN** columns use integer type

### Requirement: Nullable constraints are correct
The system SHALL properly define which columns allow null values.

#### Scenario: Required fields not nullable
- **WHEN** tables are created
- **THEN** id, user_id, board_id, list_id, title, position, status, priority are NOT NULL

#### Scenario: Optional fields nullable
- **WHEN** tables are created
- **THEN** description, due_date, deleted_at are nullable

### Requirement: Database seeders for testing
The system SHALL provide seeders to populate sample data for development and testing.

#### Scenario: Seeders located in package
- **WHEN** package structure is examined
- **THEN** seeders exist in applets/trello/database/seeders/

#### Scenario: Board seeder creates sample boards
- **WHEN** BoardSeeder is executed
- **THEN** system creates sample boards for testing users

#### Scenario: List seeder creates sample lists
- **WHEN** ListSeeder is executed
- **THEN** system creates sample lists for seeded boards

#### Scenario: Card seeder creates sample cards
- **WHEN** CardSeeder is executed
- **THEN** system creates sample cards across different statuses and priorities

#### Scenario: Seeder respects relationships
- **WHEN** seeders are executed
- **THEN** all foreign key relationships are valid

#### Scenario: Seeder is idempotent
- **WHEN** seeder is run multiple times
- **THEN** system handles existing data gracefully (truncate first or check existence)

### Requirement: Character encoding is UTF-8
The system SHALL use UTF-8 character encoding for all tables and columns.

#### Scenario: Table charset
- **WHEN** tables are created
- **THEN** tables use utf8mb4 character set

#### Scenario: Collation setting
- **WHEN** tables are created
- **THEN** tables use utf8mb4_unicode_ci collation

### Requirement: Index naming follows conventions
The system SHALL use clear, consistent naming for indexes.

#### Scenario: Foreign key index naming
- **WHEN** foreign key indexes are created
- **THEN** names follow pattern: tablename_columnname_foreign

#### Scenario: Regular index naming
- **WHEN** regular indexes are created
- **THEN** names follow pattern: tablename_columnname_index

#### Scenario: Composite index naming
- **WHEN** composite indexes are created
- **THEN** names describe included columns: tablename_col1_col2_index

### Requirement: Migration files follow Laravel conventions
The system SHALL follow Laravel migration naming and structure conventions.

#### Scenario: Migration file naming
- **WHEN** migration files are created
- **THEN** names follow pattern: YYYY_MM_DD_HHMMSS_create_tablename_table.php

#### Scenario: Migration class naming
- **WHEN** migration classes are defined
- **THEN** class names use PascalCase matching file description

#### Scenario: Up and down methods
- **WHEN** migration classes are defined
- **THEN** both up() and down() methods are implemented

### Requirement: Prevent duplicate index definitions
The system SHALL avoid redundant indexes.

#### Scenario: Foreign key auto-index
- **WHEN** foreign key constraint is created
- **THEN** system does not create duplicate index if one already exists on the column

#### Scenario: Composite index replaces single column index
- **WHEN** composite index includes a column
- **THEN** separate single-column index on that column may be redundant (evaluate per query pattern)

### Requirement: Indexes optimize common queries
The system SHALL create indexes that optimize frequently executed queries.

#### Scenario: User's boards query optimization
- **WHEN** fetching all boards for a user
- **THEN** user_id index (from foreign key) optimizes this query

#### Scenario: Board's lists query optimization
- **WHEN** fetching ordered lists for a board
- **THEN** (board_id, position) composite index optimizes this query

#### Scenario: List's cards query optimization
- **WHEN** fetching ordered cards for a list
- **THEN** (list_id, position) composite index optimizes this query

#### Scenario: Card search optimization
- **WHEN** searching cards by title
- **THEN** title index enables efficient LIKE queries

#### Scenario: Card filter optimization
- **WHEN** filtering cards by status and priority
- **THEN** (status, priority) composite index optimizes this query

### Requirement: Schema supports soft deletes
The system SHALL implement database support for soft delete functionality.

#### Scenario: Deleted_at column on all tables
- **WHEN** tables are created
- **THEN** all tables include deleted_at timestamp column

#### Scenario: Soft delete index
- **WHEN** tables use soft deletes
- **THEN** deleted_at column may be included in composite indexes for query performance

#### Scenario: Soft delete queries exclude deleted records
- **WHEN** standard queries are executed
- **THEN** WHERE deleted_at IS NULL condition filters out soft-deleted records
