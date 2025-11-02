# Users API Documentation

## Overview
API endpoints to fetch users with their roles and departments for use in forms, dropdowns, and user management on the frontend.

---

## Users API

### 1. Get All Users
**GET** `/api/users`

**Authentication:** Not required (public)

**Description:** Retrieves all users in the system with their roles and departments.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "role_id": 1,
      "department_id": 1,
      "role": {
        "id": 1,
        "name": "admin"
      },
      "department": {
        "id": 1,
        "name": "IT Support"
      }
    },
    {
      "id": 2,
      "first_name": "Jane",
      "last_name": "Smith",
      "email": "jane@example.com",
      "role_id": 2,
      "department_id": 2,
      "role": {
        "id": 2,
        "name": "agent"
      },
      "department": {
        "id": 2,
        "name": "Medical Records"
      }
    }
  ],
  "message": "Users retrieved successfully"
}
```

### 2. Get Specific User
**GET** `/api/users/{id}`

**Authentication:** Not required (public)

**Description:** Retrieves a specific user with their role and department.

**Example:** `GET /api/users/1`

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "role_id": 1,
    "department_id": 1,
    "role": {
      "id": 1,
      "name": "admin"
    },
    "department": {
      "id": 1,
      "name": "IT Support"
    }
  },
  "message": "User retrieved successfully"
}
```

### 3. Get Users by Role
**GET** `/api/users/role/{roleName}`

**Authentication:** Not required (public)

**Description:** Retrieves all users with a specific role.

**Example:** `GET /api/users/role/agent`

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "first_name": "Jane",
      "last_name": "Smith",
      "email": "jane@example.com",
      "role_id": 2,
      "department_id": 2,
      "role": {
        "id": 2,
        "name": "agent"
      },
      "department": {
        "id": 2,
        "name": "Medical Records"
      }
    },
    {
      "id": 3,
      "first_name": "Bob",
      "last_name": "Johnson",
      "email": "bob@example.com",
      "role_id": 2,
      "department_id": 1,
      "role": {
        "id": 2,
        "name": "agent"
      },
      "department": {
        "id": 1,
        "name": "IT Support"
      }
    }
  ],
  "message": "Users with role 'agent' retrieved successfully"
}
```

### 4. Get Users by Department
**GET** `/api/users/department/{departmentId}`

**Authentication:** Not required (public)

**Description:** Retrieves all users in a specific department.

**Example:** `GET /api/users/department/1`

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "role_id": 1,
      "department_id": 1,
      "role": {
        "id": 1,
        "name": "admin"
      },
      "department": {
        "id": 1,
        "name": "IT Support"
      }
    },
    {
      "id": 3,
      "first_name": "Bob",
      "last_name": "Johnson",
      "email": "bob@example.com",
      "role_id": 2,
      "department_id": 1,
      "role": {
        "id": 2,
        "name": "agent"
      },
      "department": {
        "id": 1,
        "name": "IT Support"
      }
    }
  ],
  "message": "Users retrieved successfully"
}
```

---

## Frontend Usage Examples

### JavaScript - Fetch All Users
```javascript
const response = await fetch('/api/users');
const { data: users } = await response.json();

users.forEach(user => {
  console.log(`${user.first_name} ${user.last_name} - ${user.role.name}`);
});
```

### JavaScript - Fetch Users by Role
```javascript
const response = await fetch('/api/users/role/agent');
const { data: agents } = await response.json();

agents.forEach(agent => {
  console.log(`${agent.first_name} ${agent.last_name}`);
});
```

### JavaScript - Fetch Users by Department
```javascript
const response = await fetch('/api/users/department/1');
const { data: deptUsers } = await response.json();

deptUsers.forEach(user => {
  console.log(`${user.first_name} ${user.last_name}`);
});
```

### React Example - Users Dropdown
```jsx
import { useEffect, useState } from 'react';

function UserSelect() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/users')
      .then(res => res.json())
      .then(data => {
        setUsers(data.data);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <select>
      <option value="">Select a user</option>
      {users.map(user => (
        <option key={user.id} value={user.id}>
          {user.first_name} {user.last_name} ({user.role.name})
        </option>
      ))}
    </select>
  );
}

export default UserSelect;
```

### React Example - Agents Dropdown
```jsx
import { useEffect, useState } from 'react';

function AgentSelect() {
  const [agents, setAgents] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/users/role/agent')
      .then(res => res.json())
      .then(data => {
        setAgents(data.data);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <select>
      <option value="">Select an agent</option>
      {agents.map(agent => (
        <option key={agent.id} value={agent.id}>
          {agent.first_name} {agent.last_name}
        </option>
      ))}
    </select>
  );
}

export default AgentSelect;
```

---

## Summary of Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/users` | Get all users |
| GET | `/api/users/{id}` | Get specific user |
| GET | `/api/users/role/{roleName}` | Get users by role |
| GET | `/api/users/department/{departmentId}` | Get users by department |

