# LMS Integration API

REST API endpoints for LMS (Learning Management System) integration with Odoo.

## Authentication

All endpoints (except `/api/lms/login`) require authentication. Use the login endpoint to obtain a session, then include the session cookie in subsequent requests.

### Login
```
POST /api/lms/login
Content-Type: application/json

{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "email": "user@example.com",
        "password": "password123"
    },
    "id": 1
}
```

**Response (success):**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": true,
        "message": "Login successful",
        "data": {
            "user_id": 1,
            "session_id": "abc123..."
        }
    }
}
```

**Response (error):**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": false,
        "error": "unauthorized",
        "message": "Invalid email or password"
    }
}
```

### Logout
```
POST /api/lms/logout
Content-Type: application/json

{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {},
    "id": 1
}
```

---

## Student Endpoints

### Search Students
Search for students by ID, name, or national ID.

```
POST /api/lms/student/search
Content-Type: application/json

{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "id": 123,                       // Optional: student record ID
        "name": "John",                  // Optional: partial name match
        "national_id": "12345678901234"  // Optional: student_identification_id
    },
    "id": 1
}
```

**Response (success):**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": true,
        "data": {
            "count": 1,
            "students": [
                {
                    "id": 123,
                    "student_full_name": "John Doe Smith",
                    "student_en_full_name": "John Doe Smith",
                    "sequence": "STU001",
                    "year_apply_for": {
                        "id": 1,
                        "display_name": "Grade 1"
                    },
                    "standard_id": {
                        "id": 5,
                        "display_name": "Class 1A"
                    },
                    "license_due_date": "2025-12-31",
                    "guardian_national_id": "98765432109876"
                }
            ]
        }
    }
}
```

### Update Student LMS Credentials
Update LMS username and/or password for a student using either database `id` **or** `national_id`.

```
POST /api/lms/student/update
Content-Type: application/json

{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "id": 123,                      // Optional: student record ID
        "national_id": "12345678901234", // Optional: student_identification_id
        "lms_username": "student001",   // Optional: LMS username
        "lms_password": "pass123"       // Optional: LMS password
    },
    "id": 1
}
```

**Response (success):**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": true,
        "message": "Student LMS credentials updated successfully",
        "data": {
            "id": 123,
            "lms_username": "student001",
            "lms_password": "pass123"
        }
    }
}
```

---

## Parent Endpoints

### Search Parents
Search for parents by ID, name, or national ID.

```
POST /api/lms/parent/search
Content-Type: application/json

{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "id": 123,                       // Optional: parent record ID
        "name": "Jane",                  // Optional: partial name match
        "national_id": "98765432109876"  // Optional: identification_id
    },
    "id": 1
}
```

**Response (success):**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": true,
        "data": {
            "count": 1,
            "parents": [
                {
                    "id": 123,
                    "name": "Jane Doe",
                    "email": "jane@example.com",
                    "identification_id": "98765432109876"
                }
            ]
        }
    }
}
```

### Update Parent LMS Credentials
Update LMS username and/or password for a parent using either database `id` **or** `national_id` (identification_id).

```
POST /api/lms/parent/update
Content-Type: application/json

{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
        "id": 123,                        // Optional: parent record ID
        "national_id": "98765432109876", // Optional: identification_id
        "lms_username": "parent001",     // Optional: LMS username
        "lms_password": "pass123"        // Optional: LMS password
    },
    "id": 1
}
```

**Response (success):**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": true,
        "message": "Parent LMS credentials updated successfully",
        "data": {
            "id": 123,
            "lms_username": "parent001",
            "lms_password": "pass123"
        }
    }
}
```

---

## Common Error Responses

| Error Code | Description |
|------------|-------------|
| `bad_request` | Missing or invalid parameters |
| `unauthorized` | Invalid credentials or session expired |
| `not_found` | Resource not found |
| `server_error` | Internal server error |

**Example error response:**
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "success": false,
        "error": "not_found",
        "message": "No students found matching the criteria"
    }
}
```

---

## Fields Reference

### Student Read Fields
| Field | Description |
|-------|-------------|
| `student_full_name` | Student's full Arabic name |
| `student_en_full_name` | Student's full English name |
| `sequence` | Student ID (e.g., STU001) |
| `year_apply_for` | Student's grade (id + display_name) |
| `standard_id` | Student's class (id + display_name) |
| `license_due_date` | License due date |
| `guardian_national_id` | Guardian's national ID |

### Student Write Fields
| Field | Description |
|-------|-------------|
| `lms_username` | LMS username |
| `lms_password` | LMS password |

### Parent Read Fields
| Field | Description |
|-------|-------------|
| `name` | Parent's full name |
| `email` | Parent's email |
| `identification_id` | Parent's national ID |

### Parent Write Fields
| Field | Description |
|-------|-------------|
| `lms_username` | LMS username |
| `lms_password` | LMS password |

---

## Usage Example (Python)

```python
import requests

BASE_URL = "http://localhost:8069"

# Login
response = requests.post(
    f"{BASE_URL}/api/lms/login",
    json={
        "jsonrpc": "2.0",
        "method": "call",
        "params": {
            "email": "admin@example.com",
            "password": "admin"
        },
        "id": 1
    }
)
session_cookie = response.cookies.get('session_id')

# Search student
response = requests.post(
    f"{BASE_URL}/api/lms/student/search",
    json={
        "jsonrpc": "2.0",
        "method": "call",
        "params": {
            "national_id": "12345678901234"
        },
        "id": 2
    },
    cookies={'session_id': session_cookie}
)
print(response.json())

# Update student LMS credentials
response = requests.post(
    f"{BASE_URL}/api/lms/student/update",
    json={
        "jsonrpc": "2.0",
        "method": "call",
        "params": {
            "id": 123,
            "lms_username": "student001",
            "lms_password": "newpass123"
        },
        "id": 3
    },
    cookies={'session_id': session_cookie}
)
print(response.json())
```

---

## Usage Example (cURL)

```bash
# Login
curl -X POST http://localhost:8069/api/lms/login \
  -H "Content-Type: application/json" \
  -c cookies.txt \
  -d '{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
      "email": "admin@example.com",
      "password": "admin"
    },
    "id": 1
  }'

# Search student
curl -X POST http://localhost:8069/api/lms/student/search \
  -H "Content-Type: application/json" \
  -b cookies.txt \
  -d '{
    "jsonrpc": "2.0",
    "method": "call",
    "params": {
      "national_id": "12345678901234"
    },
    "id": 2
  }'
```
