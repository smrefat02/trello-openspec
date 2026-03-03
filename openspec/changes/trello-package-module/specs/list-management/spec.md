## ADDED Requirements

### Requirement: User can create a list on their board
The system SHALL allow users to create a new list within a board they own, with a title and automatic position assignment.

#### Scenario: Successful list creation
- **WHEN** user submits a valid list creation form with title "To Do" on their board
- **THEN** system creates the list with position set to max(existing positions) + 10

#### Scenario: List creation without title
- **WHEN** user submits a list creation form without a title
- **THEN** system displays validation error "The title field is required"

#### Scenario: Create list on non-owned board
- **WHEN** user attempts to create a list on a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: Lists belong to a board
The system SHALL enforce that every list belongs to exactly one board through a foreign key relationship.

#### Scenario: List association on creation
- **WHEN** user creates a list on board ID 5
- **THEN** system stores the list with board_id = 5

#### Scenario: Orphaned lists prevented
- **WHEN** a board is deleted
- **THEN** system cascades delete to all associated lists

### Requirement: User can view lists within a board
The system SHALL display all lists belonging to a board in position order.

#### Scenario: Lists displayed in order
- **WHEN** user views a board with lists at positions 10, 20, 30
- **THEN** system displays lists in ascending position order

#### Scenario: Board with no lists
- **WHEN** user views a board with no lists
- **THEN** system displays an empty board with option to create first list

### Requirement: User can update a list
The system SHALL allow users to update the title and position of lists on their boards.

#### Scenario: Successful list title update
- **WHEN** user changes list title from "To Do" to "Backlog"
- **THEN** system saves the new title and displays success message

#### Scenario: Update list title to empty
- **WHEN** user attempts to change list title to empty string
- **THEN** system displays validation error "The title field is required"

#### Scenario: Update list on non-owned board
- **WHEN** user attempts to update a list on a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: User can delete a list
The system SHALL allow users to soft delete lists from their boards, removing the list and all associated cards.

#### Scenario: Successful list deletion
- **WHEN** user deletes a list from their board
- **THEN** system soft deletes the list and all associated cards

#### Scenario: Delete list on non-owned board
- **WHEN** user attempts to delete a list on a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Deleted list not visible
- **WHEN** user views board after deleting a list
- **THEN** system does not display the deleted list

### Requirement: List position supports ordering
The system SHALL use integer position field to maintain list order within a board.

#### Scenario: New list positioned at end
- **WHEN** user creates a list on a board with max position 30
- **THEN** system assigns position 40 to the new list

#### Scenario: Reordering list
- **WHEN** user drags a list to a new position
- **THEN** system updates the list's position value accordingly

#### Scenario: Position collision handling
- **WHEN** two lists would have the same position
- **THEN** system adjusts positions to maintain unique ordering

### Requirement: List authorization inherits from board
The system SHALL enforce that users can only create, update, or delete lists on boards they own.

#### Scenario: List access via board ownership
- **WHEN** user owns a board
- **THEN** system allows all list operations on that board

#### Scenario: List access denied for non-owned board
- **WHEN** user does not own a board
- **THEN** system denies all list operations on that board

### Requirement: List title validation
The system SHALL validate list title according to defined constraints.

#### Scenario: Title length validation
- **WHEN** user submits list title exceeding 255 characters
- **THEN** system displays validation error "The title must not exceed 255 characters"

#### Scenario: Title required validation
- **WHEN** user submits list without title
- **THEN** system displays validation error "The title field is required"

### Requirement: Lists use soft deletes
The system SHALL soft delete lists to allow recovery and maintain data integrity.

#### Scenario: Soft deleted list excluded from queries
- **WHEN** user queries lists for a board
- **THEN** system excludes soft deleted lists from results by default

#### Scenario: Cascading soft delete from board
- **WHEN** a board is soft deleted
- **THEN** system soft deletes all associated lists
