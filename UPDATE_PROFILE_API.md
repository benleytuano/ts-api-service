# Update Profile API Documentation

## Overview
API endpoint to update user profile information (email and/or password). Users can update either field independently or both at the same time. At least one field must be provided.

---

## Update Profile Endpoint

### Update Email and/or Password
**POST** `/api/auth/update-profile`

**Authentication:** Required (Bearer token)

**Description:** Updates the authenticated user's email and/or password. At least one field must be provided.

---

## Request Body

```json
{
  "email": "newemail@example.com",
  "password": "NewPassword123",
  "password_confirmation": "NewPassword123"
}
```

### Field Requirements

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `email` | string | Optional* | Must be valid email, unique in system |
| `password` | string | Optional* | Minimum 8 characters |
| `password_confirmation` | string | Conditional | Required if password is provided, must match password |

*At least one of `email` or `password` must be provided

---

## Response Examples

### Success Response (200 OK)

```json
{
  "success": true,
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "newemail@example.com",
    "role_id": 3,
    "department_id": 1,
    "created_at": "2025-05-27T10:30:00.000000Z",
    "updated_at": "2025-05-27T11:45:00.000000Z",
    "role": {
      "id": 3,
      "name": "agent"
    },
    "department": {
      "id": 1,
      "name": "IT Support"
    }
  },
  "message": "Profile updated successfully"
}
```

### Validation Error (422 Unprocessable Entity)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "This email is already in use."
    ],
    "password_confirmation": [
      "Password confirmation does not match."
    ]
  }
}
```

### No Fields Provided (422 Unprocessable Entity)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "fields": [
      "At least email or password must be provided."
    ]
  }
}
```

### Unauthenticated (401 Unauthorized)

```json
{
  "message": "Unauthenticated."
}
```

---

## Frontend Usage Examples

### JavaScript - Update Email Only
```javascript
async function updateEmail(newEmail) {
  try {
    const response = await fetch('/api/auth/update-profile', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        email: newEmail
      })
    });

    const data = await response.json();
    
    if (response.ok) {
      console.log('Email updated:', data.user.email);
      return data.user;
    } else {
      console.error('Errors:', data.errors);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}
```

### JavaScript - Update Password Only
```javascript
async function updatePassword(newPassword, confirmation) {
  try {
    const response = await fetch('/api/auth/update-profile', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        password: newPassword,
        password_confirmation: confirmation
      })
    });

    const data = await response.json();
    
    if (response.ok) {
      console.log('Password updated successfully');
      return data.user;
    } else {
      console.error('Errors:', data.errors);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}
```

### JavaScript - Update Both
```javascript
async function updateProfile(email, password, confirmation) {
  try {
    const response = await fetch('/api/auth/update-profile', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        email: email,
        password: password,
        password_confirmation: confirmation
      })
    });

    const data = await response.json();
    
    if (response.ok) {
      console.log('Profile updated:', data.user);
      return data.user;
    } else {
      console.error('Errors:', data.errors);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}
```

### React Example - Update Profile Form
```jsx
import { useState } from 'react';

function UpdateProfileForm({ token }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirm, setPasswordConfirm] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [errors, setErrors] = useState({});

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});
    setMessage('');

    try {
      const response = await fetch('/api/auth/update-profile', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          email: email || undefined,
          password: password || undefined,
          password_confirmation: passwordConfirm || undefined
        })
      });

      const data = await response.json();

      if (response.ok) {
        setMessage('Profile updated successfully!');
        setEmail('');
        setPassword('');
        setPasswordConfirm('');
      } else {
        setErrors(data.errors || {});
      }
    } catch (error) {
      setMessage('Error updating profile');
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Update Profile</h2>

      {message && <div className="success">{message}</div>}

      <div>
        <label>Email:</label>
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          placeholder="Leave empty to keep current"
        />
        {errors.email && <span className="error">{errors.email[0]}</span>}
      </div>

      <div>
        <label>New Password:</label>
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          placeholder="Leave empty to keep current"
        />
        {errors.password && <span className="error">{errors.password[0]}</span>}
      </div>

      <div>
        <label>Confirm Password:</label>
        <input
          type="password"
          value={passwordConfirm}
          onChange={(e) => setPasswordConfirm(e.target.value)}
          placeholder="Confirm new password"
        />
        {errors.password_confirmation && <span className="error">{errors.password_confirmation[0]}</span>}
      </div>

      {errors.fields && <div className="error">{errors.fields[0]}</div>}

      <button type="submit" disabled={loading}>
        {loading ? 'Updating...' : 'Update Profile'}
      </button>
    </form>
  );
}

export default UpdateProfileForm;
```

### Vue Example - Update Profile Form
```vue
<template>
  <div>
    <h2>Update Profile</h2>

    <div v-if="message" class="success">{{ message }}</div>

    <form @submit.prevent="handleSubmit">
      <div>
        <label>Email:</label>
        <input
          v-model="email"
          type="email"
          placeholder="Leave empty to keep current"
        />
        <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
      </div>

      <div>
        <label>New Password:</label>
        <input
          v-model="password"
          type="password"
          placeholder="Leave empty to keep current"
        />
        <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>
      </div>

      <div>
        <label>Confirm Password:</label>
        <input
          v-model="passwordConfirm"
          type="password"
          placeholder="Confirm new password"
        />
        <span v-if="errors.password_confirmation" class="error">
          {{ errors.password_confirmation[0] }}
        </span>
      </div>

      <span v-if="errors.fields" class="error">{{ errors.fields[0] }}</span>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Updating...' : 'Update Profile' }}
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      password: '',
      passwordConfirm: '',
      loading: false,
      message: '',
      errors: {},
      token: localStorage.getItem('auth_token')
    };
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.errors = {};
      this.message = '';

      try {
        const response = await fetch('/api/auth/update-profile', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`
          },
          body: JSON.stringify({
            email: this.email || undefined,
            password: this.password || undefined,
            password_confirmation: this.passwordConfirm || undefined
          })
        });

        const data = await response.json();

        if (response.ok) {
          this.message = 'Profile updated successfully!';
          this.email = '';
          this.password = '';
          this.passwordConfirm = '';
        } else {
          this.errors = data.errors || {};
        }
      } catch (error) {
        this.message = 'Error updating profile';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
```

---

## Validation Rules

| Field | Rules |
|-------|-------|
| `email` | nullable, valid email format, unique (except current user), max 255 chars |
| `password` | nullable, minimum 8 characters |
| `password_confirmation` | nullable, must match password if provided |
| **At least one field** | Must provide email or password (or both) |

---

## Related Endpoints

- **Login**: `POST /api/auth/login` - Authenticate user
- **Register**: `POST /api/auth/register` - Create new account
- **Get Current User**: `GET /api/auth/me` - Get authenticated user info
- **Logout**: `POST /api/auth/logout` - Logout user

---

## Summary

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/auth/update-profile` | Update email and/or password | Required |

