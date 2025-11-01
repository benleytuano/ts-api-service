# Roles & Departments API Documentation

## Overview
API endpoints to fetch roles and departments for use in forms, dropdowns, and user management on the frontend.

---

## Roles API

### 1. Get All Roles
**GET** `/api/roles`

**Authentication:** Not required (public)

**Description:** Retrieves all available roles in the system.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "admin"
    },
    {
      "id": 2,
      "name": "agent"
    },
    {
      "id": 3,
      "name": "user"
    }
  ],
  "message": "Roles retrieved successfully"
}
```

### 2. Get Specific Role with Permissions
**GET** `/api/roles/{id}`

**Authentication:** Not required (public)

**Description:** Retrieves a specific role with all its associated permissions.

**Example:** `GET /api/roles/1`

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "admin",
    "permissions": [
      {
        "id": 1,
        "name": "create_ticket"
      },
      {
        "id": 2,
        "name": "edit_ticket"
      },
      {
        "id": 3,
        "name": "delete_ticket"
      }
    ]
  },
  "message": "Role retrieved successfully"
}
```

---

## Departments API

### 1. Get All Departments
**GET** `/api/departments`

**Authentication:** Not required (public)

**Description:** Retrieves all departments in the system.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "IT Support"
    },
    {
      "id": 2,
      "name": "Medical Records"
    },
    {
      "id": 3,
      "name": "Administration"
    }
  ],
  "message": "Departments retrieved successfully"
}
```

### 2. Get Specific Department with Locations
**GET** `/api/departments/{id}`

**Authentication:** Not required (public)

**Description:** Retrieves a specific department with all its associated locations.

**Example:** `GET /api/departments/1`

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "IT Support",
    "locations": [
      {
        "id": 1,
        "name": "Main Building",
        "department_id": 1
      },
      {
        "id": 2,
        "name": "Annex Building",
        "department_id": 1
      }
    ]
  },
  "message": "Department retrieved successfully"
}
```

### 3. Get Departments with User Counts
**GET** `/api/departments/with-counts`

**Authentication:** Not required (public)

**Description:** Retrieves all departments with the count of users in each department.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "IT Support",
      "users_count": 5
    },
    {
      "id": 2,
      "name": "Medical Records",
      "users_count": 3
    },
    {
      "id": 3,
      "name": "Administration",
      "users_count": 2
    }
  ],
  "message": "Departments with user counts retrieved successfully"
}
```

---

## Frontend Usage Examples

### JavaScript - Fetch All Roles
```javascript
// Get all roles for a dropdown
const response = await fetch('/api/roles');
const { data: roles } = await response.json();

// Use in dropdown
roles.forEach(role => {
  console.log(`${role.id}: ${role.name}`);
});
```

### JavaScript - Fetch All Departments
```javascript
// Get all departments for a dropdown
const response = await fetch('/api/departments');
const { data: departments } = await response.json();

// Use in dropdown
departments.forEach(dept => {
  console.log(`${dept.id}: ${dept.name}`);
});
```

### JavaScript - Fetch Department with Locations
```javascript
// Get a specific department with its locations
const departmentId = 1;
const response = await fetch(`/api/departments/${departmentId}`);
const { data: department } = await response.json();

console.log(department.name);
department.locations.forEach(location => {
  console.log(`  - ${location.name}`);
});
```

### React Example - Roles Dropdown
```jsx
import { useEffect, useState } from 'react';

function RoleSelect() {
  const [roles, setRoles] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/roles')
      .then(res => res.json())
      .then(data => {
        setRoles(data.data);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <select>
      <option value="">Select a role</option>
      {roles.map(role => (
        <option key={role.id} value={role.id}>
          {role.name}
        </option>
      ))}
    </select>
  );
}

export default RoleSelect;
```

### React Example - Departments Dropdown
```jsx
import { useEffect, useState } from 'react';

function DepartmentSelect() {
  const [departments, setDepartments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/departments')
      .then(res => res.json())
      .then(data => {
        setDepartments(data.data);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <select>
      <option value="">Select a department</option>
      {departments.map(dept => (
        <option key={dept.id} value={dept.id}>
          {dept.name}
        </option>
      ))}
    </select>
  );
}

export default DepartmentSelect;
```

### Vue Example - Roles Dropdown
```vue
<template>
  <select v-model="selectedRole">
    <option value="">Select a role</option>
    <option v-for="role in roles" :key="role.id" :value="role.id">
      {{ role.name }}
    </option>
  </select>
</template>

<script>
export default {
  data() {
    return {
      roles: [],
      selectedRole: ''
    };
  },
  mounted() {
    fetch('/api/roles')
      .then(res => res.json())
      .then(data => {
        this.roles = data.data;
      });
  }
};
</script>
```

---

## Error Handling

All endpoints return error responses in this format:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message"
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `404` - Resource not found
- `500` - Server error

---

## Summary of Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/roles` | Get all roles |
| GET | `/api/roles/{id}` | Get role with permissions |
| GET | `/api/departments` | Get all departments |
| GET | `/api/departments/{id}` | Get department with locations |
| GET | `/api/departments/with-counts` | Get departments with user counts |

