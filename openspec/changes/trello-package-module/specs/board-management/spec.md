## ADDED Requirements

### Requirement: User can create a board
The system SHALL allow authenticated users to create a new board with a title, optional description, and visibility setting.

#### Scenario: Successful board creation
- **WHEN** user submits a valid board creation form with title "Project Alpha"
- **THEN** system creates a new board owned by the user and redirects to the board view

#### Scenario: Board creation without title
- **WHEN** user submits a board creation form without a title
- **THEN** system displays validation error "The title field is required"

#### Scenario: Board creation with invalid visibility
- **WHEN** user submits a board with visibility value "shared" (not private/public)
- **THEN** system displays validation error indicating valid options are private or public

### Requirement: User can view their boards list
The system SHALL display a paginated list of all boards owned by the authenticated user.

#### Scenario: Viewing boards with pagination
- **WHEN** user has 25 boards and visits the boards index page
- **THEN** system displays the first 15 boards with pagination controls

#### Scenario: Empty boards list
- **WHEN** user has no boards
- **THEN** system displays an empty state message encouraging board creation

### Requirement: User can view a single board
The system SHALL display the full details of a board including its title, description, visibility, and all associated lists with cards.

#### Scenario: Viewing owned board
- **WHEN** user accesses a board they own
- **THEN** system displays the board in Kanban layout with all lists and cards

#### Scenario: Viewing non-existent board
- **WHEN** user accesses a board ID that does not exist
- **THEN** system returns 404 Not Found

### Requirement: User can update their board
The system SHALL allow users to update the title, description, and visibility of their own boards.

#### Scenario: Successful board update
- **WHEN** user submits valid updated data for their board
- **THEN** system saves changes and displays success message

#### Scenario: Updating board title to empty
- **WHEN** user attempts to change board title to empty string
- **THEN** system displays validation error "The title field is required"

#### Scenario: Update another user's board
- **WHEN** user attempts to update a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: User can delete their board
The system SHALL allow users to soft delete their own boards, removing them from view but preserving data.

#### Scenario: Successful board deletion
- **WHEN** user deletes their board
- **THEN** system soft deletes the board and all associated lists and cards, redirecting to boards index

#### Scenario: Delete another user's board
- **WHEN** user attempts to delete a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Deleted board not visible in list
- **WHEN** user views their boards list after deleting a board
- **THEN** system does not display the deleted board

### Requirement: Board ownership is enforced
The system SHALL ensure that only the board owner can view, update, or delete their boards.

#### Scenario: Access control on board view
- **WHEN** user attempts to view another user's private board
- **THEN** system returns 403 Forbidden

#### Scenario: Automatic user assignment
- **WHEN** user creates a board
- **THEN** system automatically assigns the authenticated user as the owner

### Requirement: Boards have visibility settings
The system SHALL support private and public visibility settings for boards, defaulting to private.

#### Scenario: Default visibility is private
- **WHEN** user creates a board without specifying visibility
- **THEN** system sets visibility to "private"

#### Scenario: Public board creation
- **WHEN** user creates a board with visibility set to "public"
- **THEN** system stores the board with public visibility

### Requirement: Board data validation
The system SHALL validate board input data according to defined constraints.

#### Scenario: Title length validation
- **WHEN** user submits board title exceeding 255 characters
- **THEN** system displays validation error "The title must not exceed 255 characters"

#### Scenario: Description length validation
- **WHEN** user submits board description exceeding 1000 characters
- **THEN** system displays validation error about maximum description length

### Requirement: Boards support search by title
The system SHALL allow users to search their boards by title using partial matching.

#### Scenario: Search boards by title
- **WHEN** user searches for "project" among their boards
- **THEN** system displays all boards with "project" in the title (case-insensitive)

#### Scenario: No search results
- **WHEN** user searches for a term that matches no boards
- **THEN** system displays empty results with "No boards found" message
