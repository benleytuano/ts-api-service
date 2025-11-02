# Permissions API Documentation

## Overview
API endpoints to fetch permissions in the system. Useful for displaying available permissions in permission management interfaces, role assignment forms, and permission-based UI controls.

---

## Permissions API

### 1. Get All Permissions
**GET** `/api/permissions`

**Authentication:** Not required (public)

**Description:** Retrieves all available permissions in the system, sorted alphabetically by name.

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "assign tickets"
    },
    {
      "id": 2,
      "name": "create tickets"
    },
    {
      "id": 3,
      "name": "delete tickets"
    },
    {
      "id": 4,
      "name": "update tickets"
    },
    {
      "id": 5,
      "name": "view tickets"
    }
  ],
  "message": "Permissions retrieved successfully"
}
```

### 2. Get Specific Permission with Roles
**GET** `/api/permissions/{id}`

**Authentication:** Not required (public)

**Description:** Retrieves a specific permission with all roles that have this permission.

**Example:** `GET /api/permissions/1`

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "assign tickets",
    "roles": [
      {
        "id": 1,
        "name": "admin"
      },
      {
        "id": 2,
        "name": "agent"
      }
    ]
  },
  "message": "Permission retrieved successfully"
}
```

---

## Error Responses

### Permission Not Found (404 Not Found)

```json
{
  "success": false,
  "message": "Failed to retrieve permission",
  "error": "No query results found for model [App\\Models\\Permission] 1"
}
```

### Server Error (500 Internal Server Error)

```json
{
  "success": false,
  "message": "Failed to retrieve permissions",
  "error": "Error details"
}
```

---

## Frontend Usage Examples

### JavaScript - Fetch All Permissions
```javascript
async function getAllPermissions() {
  try {
    const response = await fetch('/api/permissions');
    const { data: permissions } = await response.json();

    permissions.forEach(perm => {
      console.log(`${perm.id}: ${perm.name}`);
    });

    return permissions;
  } catch (error) {
    console.error('Error fetching permissions:', error);
  }
}
```

### JavaScript - Fetch Specific Permission
```javascript
async function getPermission(permissionId) {
  try {
    const response = await fetch(`/api/permissions/${permissionId}`);
    const { data: permission } = await response.json();

    console.log(`Permission: ${permission.name}`);
    console.log('Roles with this permission:');
    permission.roles.forEach(role => {
      console.log(`- ${role.name}`);
    });

    return permission;
  } catch (error) {
    console.error('Error fetching permission:', error);
  }
}
```

### React Example - Permissions Dropdown
```jsx
import { useEffect, useState } from 'react';

function PermissionSelect() {
  const [permissions, setPermissions] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/permissions')
      .then(res => res.json())
      .then(data => {
        setPermissions(data.data);
        setLoading(false);
      })
      .catch(error => {
        console.error('Error:', error);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <select>
      <option value="">Select a permission</option>
      {permissions.map(perm => (
        <option key={perm.id} value={perm.id}>
          {perm.name}
        </option>
      ))}
    </select>
  );
}

export default PermissionSelect;
```

### React Example - Permissions Checkboxes
```jsx
import { useEffect, useState } from 'react';

function PermissionCheckboxes({ selectedPermissions = [] }) {
  const [permissions, setPermissions] = useState([]);
  const [checked, setChecked] = useState(selectedPermissions);

  useEffect(() => {
    fetch('/api/permissions')
      .then(res => res.json())
      .then(data => setPermissions(data.data));
  }, []);

  const handleChange = (permId) => {
    setChecked(prev =>
      prev.includes(permId)
        ? prev.filter(id => id !== permId)
        : [...prev, permId]
    );
  };

  return (
    <div>
      <h3>Permissions</h3>
      {permissions.map(perm => (
        <label key={perm.id}>
          <input
            type="checkbox"
            checked={checked.includes(perm.id)}
            onChange={() => handleChange(perm.id)}
          />
          {perm.name}
        </label>
      ))}
    </div>
  );
}

export default PermissionCheckboxes;
```

### Vue Example - Permissions List
```vue
<template>
  <div>
    <h3>Available Permissions</h3>
    <div v-if="loading">Loading...</div>
    <ul v-else>
      <li v-for="perm in permissions" :key="perm.id">
        {{ perm.name }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      permissions: [],
      loading: true
    };
  },
  mounted() {
    fetch('/api/permissions')
      .then(res => res.json())
      .then(data => {
        this.permissions = data.data;
        this.loading = false;
      });
  }
};
</script>
```

### Vue Example - Permission Checkboxes
```vue
<template>
  <div>
    <h3>Select Permissions</h3>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <label v-for="perm in permissions" :key="perm.id">
        <input
          type="checkbox"
          :value="perm.id"
          v-model="selectedPermissions"
        />
        {{ perm.name }}
      </label>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      permissions: [],
      selectedPermissions: [],
      loading: true
    };
  },
  mounted() {
    fetch('/api/permissions')
      .then(res => res.json())
      .then(data => {
        this.permissions = data.data;
        this.loading = false;
      });
  }
};
</script>
```

---

## Related Endpoints

- **Get All Roles**: `GET /api/roles` - List all roles
- **Get Role with Permissions**: `GET /api/roles/{id}` - Get specific role with its permissions
- **Get All Users**: `GET /api/users` - List all users
- **Get Users by Role**: `GET /api/users/role/{roleName}` - Get users with specific role

---

## Summary of Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/permissions` | Get all permissions |
| GET | `/api/permissions/{id}` | Get permission with roles |

