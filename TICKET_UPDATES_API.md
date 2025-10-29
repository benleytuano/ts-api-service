# Ticket Updates API Documentation

## Overview
The ticket updates feature allows users to add comments, notes, and track changes on tickets.

## Database Structure

### `ticket_updates` Table
- `id` - Primary key
- `ticket_id` - Foreign key to tickets table
- `user_id` - Foreign key to users table (who made the update)
- `message` - The update content (text)
- `type` - Type of update: `comment`, `status_change`, `assignment`, `internal_note`
- `is_internal` - Boolean flag for internal notes (visible only to agents/admins)
- `old_value` - Optional: previous value (for tracking changes)
- `new_value` - Optional: new value (for tracking changes)
- `created_at` - Timestamp
- `updated_at` - Timestamp

## API Endpoints

### 1. Get All Updates for a Ticket
**GET** `/api/tickets/{id}/updates`

**Authentication:** Required (Sanctum)

**Description:** Retrieves all updates for a specific ticket. Regular users only see public updates, while agents and admins see all updates including internal notes.

**Response:**
```json
[
  {
    "id": 1,
    "ticket_id": 5,
    "user_id": 2,
    "message": "I've started working on this issue",
    "type": "comment",
    "is_internal": false,
    "old_value": null,
    "new_value": null,
    "created_at": "2025-05-27T10:30:00.000000Z",
    "updated_at": "2025-05-27T10:30:00.000000Z",
    "user": {
      "id": 2,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com"
    }
  }
]
```

### 2. Create a New Update/Comment
**POST** `/api/tickets/{id}/updates`

**Authentication:** Required (Sanctum)

**Request Body:**
```json
{
  "message": "This is my comment or update",
  "type": "comment",              // Optional: comment, status_change, assignment, internal_note
  "is_internal": false,           // Optional: true for internal notes (agents/admins only)
  "old_value": null,              // Optional: for tracking changes
  "new_value": null               // Optional: for tracking changes
}
```

**Validation Rules:**
- `message` - Required, string
- `type` - Optional, must be one of: `comment`, `status_change`, `assignment`, `internal_note`
- `is_internal` - Optional, boolean (only agents/admins can set to true)
- `old_value` - Optional, string, max 255 characters
- `new_value` - Optional, string, max 255 characters

**Response (201 Created):**
```json
{
  "id": 1,
  "ticket_id": 5,
  "user_id": 2,
  "message": "This is my comment or update",
  "type": "comment",
  "is_internal": false,
  "old_value": null,
  "new_value": null,
  "created_at": "2025-05-27T10:30:00.000000Z",
  "updated_at": "2025-05-27T10:30:00.000000Z",
  "user": {
    "id": 2,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com"
  }
}
```

## Permission Rules

### Regular Users (Ticket Creators)
- Can view only **public** updates on their tickets
- Can create **public** comments only
- Cannot create internal notes
- Cannot see internal notes

### Agents
- Can view **all** updates (public and internal) on tickets
- Can create both public comments and internal notes
- Can see all internal notes

### Admins
- Can view **all** updates (public and internal) on all tickets
- Can create both public comments and internal notes
- Can see all internal notes

## Usage Examples

### Frontend - Get Updates for a Ticket
```javascript
// Fetch all updates for ticket ID 5
const response = await fetch('/api/tickets/5/updates', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});
const updates = await response.json();
```

### Frontend - Add a Comment
```javascript
// Add a public comment
const response = await fetch('/api/tickets/5/updates', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    message: 'I have the same issue with this equipment',
    type: 'comment'
  })
});
const newUpdate = await response.json();
```

### Frontend - Add an Internal Note (Agents/Admins Only)
```javascript
// Add an internal note (only visible to agents/admins)
const response = await fetch('/api/tickets/5/updates', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    message: 'Contacted vendor, waiting for replacement part',
    type: 'internal_note',
    is_internal: true
  })
});
const newUpdate = await response.json();
```

## Model Relationships

### Ticket Model
```php
// Get all updates for a ticket
$ticket->updates;

// Get only public updates
$ticket->updates()->public()->get();

// Get only internal notes
$ticket->updates()->internal()->get();
```

### TicketUpdate Model
```php
// Get the ticket this update belongs to
$update->ticket;

// Get the user who created this update
$update->user;
```

## Update Types

- **`comment`** - Regular comment/update from user or agent
- **`status_change`** - Logged when ticket status changes (can track old/new values)
- **`assignment`** - Logged when ticket is assigned/reassigned (can track old/new assignee)
- **`internal_note`** - Private note visible only to agents and admins

