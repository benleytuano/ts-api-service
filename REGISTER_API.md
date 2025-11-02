# Register API Documentation

## Overview
API endpoint for user registration. New users must provide their details, select a role and department, and create a password.

---

## Register Endpoint

### Register New User
**POST** `/api/auth/register`

**Authentication:** Not required (public)

**Description:** Creates a new user account with the provided credentials, role, and department.

---

## Request Body

```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "SecurePassword123",
  "password_confirmation": "SecurePassword123",
  "role_id": 3,
  "department_id": 1
}
```

---

## Validation Rules

| Field | Rules | Description |
|-------|-------|-------------|
| `first_name` | required, string, max:255 | User's first name |
| `last_name` | required, string, max:255 | User's last name |
| `email` | required, email, unique, max:255 | Must be unique in the system |
| `password` | required, string, min:8 | Minimum 8 characters |
| `password_confirmation` | required, same:password | Must match password field |
| `role_id` | required, integer, exists:roles,id | Must be a valid role ID |
| `department_id` | required, integer, exists:departments,id | Must be a valid department ID |

---

## Success Response (201 Created)

```json
{
  "user": {
    "id": 4,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "role_id": 3,
    "department_id": 1,
    "created_at": "2025-05-27T10:30:00.000000Z",
    "updated_at": "2025-05-27T10:30:00.000000Z"
  }
}
```

---

## Error Responses

### Validation Error (422 Unprocessable Entity)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email has already been taken."
    ],
    "password": [
      "The password must be at least 8 characters."
    ],
    "role_id": [
      "The selected role_id is invalid."
    ]
  }
}
```

### Server Error (500 Internal Server Error)

```json
{
  "message": "Server error occurred",
  "error": "Error details"
}
```

---

## Frontend Usage Examples

### JavaScript - Basic Registration
```javascript
async function registerUser() {
  const payload = {
    first_name: "John",
    last_name: "Doe",
    email: "john@example.com",
    password: "SecurePassword123",
    password_confirmation: "SecurePassword123",
    role_id: 3,
    department_id: 1
  };

  try {
    const response = await fetch('/api/auth/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    const data = await response.json();

    if (response.ok) {
      console.log('Registration successful!', data.user);
      // Redirect to login or dashboard
    } else {
      console.error('Registration failed:', data.errors);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}
```

### React Example - Registration Form
```jsx
import { useState } from 'react';

function RegisterForm() {
  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: '',
    department_id: ''
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      const response = await fetch('/api/auth/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
      });

      const data = await response.json();

      if (response.ok) {
        console.log('Registration successful!', data.user);
        // Redirect to login
        window.location.href = '/login';
      } else {
        setErrors(data.errors || {});
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label>First Name</label>
        <input
          type="text"
          name="first_name"
          value={formData.first_name}
          onChange={handleChange}
          required
        />
        {errors.first_name && <span>{errors.first_name[0]}</span>}
      </div>

      <div>
        <label>Last Name</label>
        <input
          type="text"
          name="last_name"
          value={formData.last_name}
          onChange={handleChange}
          required
        />
        {errors.last_name && <span>{errors.last_name[0]}</span>}
      </div>

      <div>
        <label>Email</label>
        <input
          type="email"
          name="email"
          value={formData.email}
          onChange={handleChange}
          required
        />
        {errors.email && <span>{errors.email[0]}</span>}
      </div>

      <div>
        <label>Role</label>
        <select
          name="role_id"
          value={formData.role_id}
          onChange={handleChange}
          required
        >
          <option value="">Select a role</option>
          <option value="1">Admin</option>
          <option value="2">Agent</option>
          <option value="3">User</option>
        </select>
        {errors.role_id && <span>{errors.role_id[0]}</span>}
      </div>

      <div>
        <label>Department</label>
        <select
          name="department_id"
          value={formData.department_id}
          onChange={handleChange}
          required
        >
          <option value="">Select a department</option>
          <option value="1">IT Support</option>
          <option value="2">Medical Records</option>
          <option value="3">Administration</option>
        </select>
        {errors.department_id && <span>{errors.department_id[0]}</span>}
      </div>

      <div>
        <label>Password</label>
        <input
          type="password"
          name="password"
          value={formData.password}
          onChange={handleChange}
          required
        />
        {errors.password && <span>{errors.password[0]}</span>}
      </div>

      <div>
        <label>Confirm Password</label>
        <input
          type="password"
          name="password_confirmation"
          value={formData.password_confirmation}
          onChange={handleChange}
          required
        />
        {errors.password_confirmation && <span>{errors.password_confirmation[0]}</span>}
      </div>

      <button type="submit" disabled={loading}>
        {loading ? 'Registering...' : 'Register'}
      </button>
    </form>
  );
}

export default RegisterForm;
```

### Vue Example - Registration Form
```vue
<template>
  <form @submit.prevent="handleSubmit">
    <div>
      <label>First Name</label>
      <input v-model="form.first_name" type="text" required />
      <span v-if="errors.first_name" class="error">{{ errors.first_name[0] }}</span>
    </div>

    <div>
      <label>Last Name</label>
      <input v-model="form.last_name" type="text" required />
      <span v-if="errors.last_name" class="error">{{ errors.last_name[0] }}</span>
    </div>

    <div>
      <label>Email</label>
      <input v-model="form.email" type="email" required />
      <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
    </div>

    <div>
      <label>Role</label>
      <select v-model="form.role_id" required>
        <option value="">Select a role</option>
        <option value="1">Admin</option>
        <option value="2">Agent</option>
        <option value="3">User</option>
      </select>
      <span v-if="errors.role_id" class="error">{{ errors.role_id[0] }}</span>
    </div>

    <div>
      <label>Department</label>
      <select v-model="form.department_id" required>
        <option value="">Select a department</option>
        <option value="1">IT Support</option>
        <option value="2">Medical Records</option>
        <option value="3">Administration</option>
      </select>
      <span v-if="errors.department_id" class="error">{{ errors.department_id[0] }}</span>
    </div>

    <div>
      <label>Password</label>
      <input v-model="form.password" type="password" required />
      <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>
    </div>

    <div>
      <label>Confirm Password</label>
      <input v-model="form.password_confirmation" type="password" required />
      <span v-if="errors.password_confirmation" class="error">{{ errors.password_confirmation[0] }}</span>
    </div>

    <button type="submit" :disabled="loading">
      {{ loading ? 'Registering...' : 'Register' }}
    </button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      form: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role_id: '',
        department_id: ''
      },
      errors: {},
      loading: false
    };
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.errors = {};

      try {
        const response = await fetch('/api/auth/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(this.form)
        });

        const data = await response.json();

        if (response.ok) {
          this.$router.push('/login');
        } else {
          this.errors = data.errors || {};
        }
      } catch (error) {
        console.error('Error:', error);
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
```

---

## Important Notes

1. **Password Requirements**: Minimum 8 characters
2. **Email Uniqueness**: Email must be unique in the system
3. **Role & Department**: Must select valid existing role and department
4. **Password Confirmation**: Must match the password field exactly
5. **Response**: Returns the created user object (without password)

---

## Related Endpoints

- **Login**: `POST /api/auth/login` - Authenticate and get token
- **Get Current User**: `GET /api/auth/me` - Get authenticated user info
- **Logout**: `POST /api/auth/logout` - Revoke authentication token
- **Get Roles**: `GET /api/roles` - List available roles
- **Get Departments**: `GET /api/departments` - List available departments

