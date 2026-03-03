## ADDED Requirements

### Requirement: Only authenticated users can access Trello module
The system SHALL require authentication for all Trello routes and operations.

#### Scenario: Unauthenticated access to boards
- **WHEN** unauthenticated user attempts to access trello boards index
- **THEN** system redirects to login page

#### Scenario: Unauthenticated API request
- **WHEN** unauthenticated user attempts to create a board via POST request
- **THEN** system returns 401 Unauthorized or redirects to login

#### Scenario: Authenticated access granted
- **WHEN** authenticated user accesses boards index
- **THEN** system displays the user's boards

### Requirement: Board access requires ownership
The system SHALL enforce that users can only access, modify, or delete boards they own.

#### Scenario: Owner can view their board
- **WHEN** user accesses a board they own
- **THEN** system displays the board

#### Scenario: Non-owner cannot view private board
- **WHEN** user attempts to view a board owned by another user
- **THEN** system returns 403 Forbidden

#### Scenario: Owner can update their board
- **WHEN** user updates a board they own
- **THEN** system saves the changes

#### Scenario: Non-owner cannot update board
- **WHEN** user attempts to update a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Owner can delete their board
- **WHEN** user deletes a board they own
- **THEN** system soft deletes the board

#### Scenario: Non-owner cannot delete board
- **WHEN** user attempts to delete a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: List access requires board ownership
The system SHALL enforce that users can only access, modify, or delete lists on boards they own.

#### Scenario: Owner can create list on their board
- **WHEN** user creates a list on a board they own
- **THEN** system creates the list

#### Scenario: Non-owner cannot create list
- **WHEN** user attempts to create a list on a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Owner can update list on their board
- **WHEN** user updates a list on a board they own
- **THEN** system saves the changes

#### Scenario: Non-owner cannot update list
- **WHEN** user attempts to update a list on a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Owner can delete list from their board
- **WHEN** user deletes a list from a board they own
- **THEN** system soft deletes the list

#### Scenario: Non-owner cannot delete list
- **WHEN** user attempts to delete a list from a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: Card access requires board ownership
The system SHALL enforce that users can only access, modify, or delete cards on boards they own.

#### Scenario: Owner can create card on their board
- **WHEN** user creates a card on a list belonging to a board they own
- **THEN** system creates the card

#### Scenario: Non-owner cannot create card
- **WHEN** user attempts to create a card on a list belonging to a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Owner can update card on their board
- **WHEN** user updates a card on a list belonging to a board they own
- **THEN** system saves the changes

#### Scenario: Non-owner cannot update card
- **WHEN** user attempts to update a card on a list belonging to a board they do not own
- **THEN** system returns 403 Forbidden

#### Scenario: Owner can delete card from their board
- **WHEN** user deletes a card from a list belonging to a board they own
- **THEN** system soft deletes the card

#### Scenario: Non-owner cannot delete card
- **WHEN** user attempts to delete a card from a list belonging to a board they do not own
- **THEN** system returns 403 Forbidden

### Requirement: Authorization uses Laravel Policies
The system SHALL implement authorization checks using Laravel Policy classes for Board, List, and Card models.

#### Scenario: Policy automatically checks ownership
- **WHEN** BoardPolicy is invoked for update action
- **THEN** policy verifies that authenticated user ID matches board user_id

#### Scenario: Policy denies access to non-owners
- **WHEN** ListPolicy checks update permission for non-owner
- **THEN** policy returns false and system blocks the action

#### Scenario: Policy integrated with controllers
- **WHEN** controller method uses authorize() helper
- **THEN** system automatically checks the relevant policy before executing action

### Requirement: Policies registered via Service Provider
The system SHALL register all policies in TrelloServiceProvider boot method.

#### Scenario: Policies registered via Gate
- **WHEN** TrelloServiceProvider boots
- **THEN** Gate::policy() registers BoardPolicy, ListPolicy, CardPolicy

#### Scenario: Policies discoverable by authorization system
- **WHEN** authorize() helper is called
- **THEN** system finds correct policy via Gate registration

### Requirement: Authorization checks are consistent
The system SHALL apply authorization checks uniformly across all routes and actions.

#### Scenario: Authorization on view actions
- **WHEN** user attempts any view action (show, index)
- **THEN** system checks ownership policy before displaying data

#### Scenario: Authorization on create actions
- **WHEN** user attempts to create nested resources (list on board, card on list)
- **THEN** system verifies user owns the parent resource

#### Scenario: Authorization on update actions
- **WHEN** user attempts any update action
- **THEN** system checks ownership policy before saving changes

#### Scenario: Authorization on delete actions
- **WHEN** user attempts any delete action
- **THEN** system checks ownership policy before performing deletion

### Requirement: Cascade authorization through relationships
The system SHALL automatically cascade authorization from boards to lists to cards.

#### Scenario: List inherits board authorization
- **WHEN** checking if user can update a list
- **THEN** system verifies user owns the list's parent board

#### Scenario: Card inherits board authorization
- **WHEN** checking if user can update a card
- **THEN** system verifies user owns the board that contains the card's list

### Requirement: Authorization errors provide clear feedback
The system SHALL return appropriate HTTP status codes and error messages for authorization failures.

#### Scenario: 403 for unauthorized access
- **WHEN** user attempts action they are not authorized for
- **THEN** system returns HTTP 403 Forbidden status

#### Scenario: Error message for failed authorization
- **WHEN** user encounters authorization failure
- **THEN** system displays user-friendly error message

#### Scenario: Redirect after authorization failure
- **WHEN** authorization fails on web routes
- **THEN** system redirects to appropriate page (e.g., boards index) with error flash message

### Requirement: User ID is automatically assigned on creation
The system SHALL automatically set the authenticated user as the owner when creating boards.

#### Scenario: Automatic user assignment on board creation
- **WHEN** authenticated user creates a board
- **THEN** system sets board.user_id to auth()->id()

#### Scenario: User cannot specify different owner
- **WHEN** user attempts to create board with different user_id in request
- **THEN** system ignores the provided user_id and uses authenticated user's ID

### Requirement: Authorization does not expose data leakage
The system SHALL prevent information disclosure through authorization failures.

#### Scenario: Consistent response for non-existent and unauthorized resources
- **WHEN** user requests a board that does not exist or they do not own
- **THEN** system returns 404 Not Found (not revealing existence)

#### Scenario: Query scoping prevents leakage
- **WHEN** fetching boards list
- **THEN** system only queries boards where user_id matches authenticated user
