## ADDED Requirements

### Requirement: User can create a card on their list
The system SHALL allow users to create a new card within a list on a board they own, with title, optional description, due date, status, priority, and automatic position assignment.

#### Scenario: Successful card creation with minimal data
- **WHEN** user submits a card creation form with only title "Fix login bug"
- **THEN** system creates card with default status "todo", default priority "medium", and position at end of list

#### Scenario: Card creation with all fields
- **WHEN** user submits a card with title, description, due date, status "in_progress", and priority "high"
- **THEN** system creates card with all provided values

#### Scenario: Card creation without title
- **WHEN** user submits a card creation form without a title
- **THEN** system displays validation error "The title field is required"

#### Scenario: Create card on non-owned board
- **WHEN** user attempts to create a card on a list belonging to a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: Cards belong to a list
The system SHALL enforce that every card belongs to exactly one list through a foreign key relationship.

#### Scenario: Card association on creation
- **WHEN** user creates a card on list ID 3
- **THEN** system stores the card with list_id = 3

#### Scenario: Cascading delete from list
- **WHEN** a list is deleted
- **THEN** system cascades delete to all associated cards

### Requirement: User can view cards within a list
The system SHALL display all cards belonging to a list in position order.

#### Scenario: Cards displayed in order
- **WHEN** user views a list with cards at positions 10, 20, 30
- **THEN** system displays cards in ascending position order

#### Scenario: List with no cards
- **WHEN** user views a list with no cards
- **THEN** system displays an empty list with option to create first card

### Requirement: User can update a card
The system SHALL allow users to update all card attributes including title, description, due date, status, priority, and position.

#### Scenario: Successful card update
- **WHEN** user changes card status from "todo" to "in_progress"
- **THEN** system saves the new status and displays success message

#### Scenario: Update card title to empty
- **WHEN** user attempts to change card title to empty string
- **THEN** system displays validation error "The title field is required"

#### Scenario: Update card on non-owned board
- **WHEN** user attempts to update a card on a list belonging to a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: User can delete a card
The system SHALL allow users to soft delete cards from lists on their boards.

#### Scenario: Successful card deletion
- **WHEN** user deletes a card from their list
- **THEN** system soft deletes the card and displays success message

#### Scenario: Delete card on non-owned board
- **WHEN** user attempts to delete a card on a list belonging to a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Deleted card not visible
- **WHEN** user views list after deleting a card
- **THEN** system does not display the deleted card

### Requirement: Cards have status tracking
The system SHALL support three status values: todo, in_progress, and done, with default value of todo.

#### Scenario: Default status on creation
- **WHEN** user creates a card without specifying status
- **THEN** system sets status to "todo"

#### Scenario: Status transition
- **WHEN** user updates card status from "todo" to "in_progress"
- **THEN** system saves the new status

#### Scenario: Invalid status value
- **WHEN** user attempts to set status to "completed" (not in enum)
- **THEN** system displays validation error listing valid status values

### Requirement: Cards have priority levels
The system SHALL support three priority values: low, medium, and high, with default value of medium.

#### Scenario: Default priority on creation
- **WHEN** user creates a card without specifying priority
- **THEN** system sets priority to "medium"

#### Scenario: High priority assignment
- **WHEN** user creates a card with priority "high"
- **THEN** system stores the card with priority "high"

#### Scenario: Invalid priority value
- **WHEN** user attempts to set priority to "urgent" (not in enum)
- **THEN** system displays validation error listing valid priority values

### Requirement: Cards support due dates
The system SHALL allow optional due dates on cards for deadline tracking.

#### Scenario: Card with due date
- **WHEN** user creates a card with due_date "2026-04-15"
- **THEN** system stores the due date and displays it on the card

#### Scenario: Card without due date
- **WHEN** user creates a card without specifying due_date
- **THEN** system stores due_date as null

#### Scenario: Invalid date format
- **WHEN** user submits due_date in format "15/04/2026"
- **THEN** system displays validation error requiring valid date format

### Requirement: Card position supports ordering
The system SHALL use integer position field to maintain card order within a list.

#### Scenario: New card positioned at end
- **WHEN** user creates a card on a list with max position 30
- **THEN** system assigns position 40 to the new card

#### Scenario: Reordering card within list
- **WHEN** user drags a card to a new position in the same list
- **THEN** system updates the card's position value

#### Scenario: Moving card between lists
- **WHEN** user drags a card from one list to another
- **THEN** system updates both list_id and position

### Requirement: User can search cards by title
The system SHALL allow users to search cards within a board by title using partial matching.

#### Scenario: Search cards by partial title
- **WHEN** user searches for "bug" on a board
- **THEN** system displays all cards with "bug" in the title (case-insensitive)

#### Scenario: Search with no results
- **WHEN** user searches for a term that matches no cards
- **THEN** system displays "No cards found" message

#### Scenario: Search scope limited to board
- **WHEN** user searches on board A
- **THEN** system only searches cards belonging to lists on board A

### Requirement: User can filter cards by status
The system SHALL allow users to filter cards by their status value.

#### Scenario: Filter by single status
- **WHEN** user selects status filter "in_progress"
- **THEN** system displays only cards with status "in_progress"

#### Scenario: Clear status filter
- **WHEN** user clears the status filter
- **THEN** system displays all cards regardless of status

#### Scenario: Filter with no matches
- **WHEN** user filters by status that has no cards
- **THEN** system displays empty results

### Requirement: User can filter cards by priority
The system SHALL allow users to filter cards by their priority value.

#### Scenario: Filter by single priority
- **WHEN** user selects priority filter "high"
- **THEN** system displays only cards with priority "high"

#### Scenario: Clear priority filter
- **WHEN** user clears the priority filter
- **THEN** system displays all cards regardless of priority

### Requirement: User can combine search and filters
The system SHALL allow users to apply search and multiple filters simultaneously.

#### Scenario: Search and status filter combined
- **WHEN** user searches for "bug" and filters by status "todo"
- **THEN** system displays only cards containing "bug" in title with status "todo"

#### Scenario: Search, status, and priority filters combined
- **WHEN** user searches for "feature" with status "in_progress" and priority "high"
- **THEN** system displays only cards matching all three criteria

### Requirement: Cards are paginated
The system SHALL paginate card results to limit page size and improve performance.

#### Scenario: Card pagination on board view
- **WHEN** a board has more than 100 cards total across all lists
- **THEN** system paginates results while maintaining list grouping

#### Scenario: Pagination maintains filters
- **WHEN** user navigates to page 2 of filtered results
- **THEN** system preserves search term and filter selections

### Requirement: Card authorization inherits from board
The system SHALL enforce that users can only create, update, or delete cards on boards they own.

#### Scenario: Card access via board ownership
- **WHEN** user owns a board
- **THEN** system allows all card operations on lists belonging to that board

#### Scenario: Card access denied for non-owned board
- **WHEN** user does not own a board
- **THEN** system denies all card operations on lists belonging to that board

### Requirement: Card data validation
The system SHALL validate card input data according to defined constraints.

#### Scenario: Title length validation
- **WHEN** user submits card title exceeding 255 characters
- **THEN** system displays validation error "The title must not exceed 255 characters"

#### Scenario: Description length validation
- **WHEN** user submits card description exceeding 2000 characters
- **THEN** system displays validation error about maximum description length

#### Scenario: Title required validation
- **WHEN** user submits card without title
- **THEN** system displays validation error "The title field is required"

### Requirement: Cards use soft deletes
The system SHALL soft delete cards to allow recovery and maintain data integrity.

#### Scenario: Soft deleted card excluded from queries
- **WHEN** user queries cards for a list
- **THEN** system excludes soft deleted cards from results by default

#### Scenario: Cascading soft delete from list
- **WHEN** a list is soft deleted
- **THEN** system soft deletes all associated cards

#### Scenario: Cascading soft delete from board
- **WHEN** a board is soft deleted
- **THEN** system soft deletes all associated lists and their cards
