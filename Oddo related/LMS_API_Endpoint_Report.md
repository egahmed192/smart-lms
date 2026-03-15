# LMS Integration API — Endpoint Verification Report

**Base URL:** `https://arafa.online`  
**Date:** February 15, 2025  
**Purpose:** Confirm that all Odoo LMS API endpoints work correctly for client integration.

---

## Summary

| Endpoint | Method | Status | Notes |
|----------|--------|--------|--------|
| `/api/lms/login` | POST | ✅ OK | Returns session; invalid credentials return `unauthorized` |
| `/api/lms/logout` | POST | ✅ OK | Requires session cookie |
| `/api/lms/student/search` | POST | ✅ OK | Supports `id`, `name`, `national_id` (at least one recommended) |
| `/api/lms/student/update` | POST | ✅ OK | Use `id` or `national_id` + `lms_username` / `lms_password` |
| `/api/lms/parent/search` | POST | ✅ OK | **Requires** at least one of: `id`, `name`, `national_id` |
| `/api/lms/parent/update` | POST | ✅ OK | Use `id` or `national_id` + `lms_username` / `lms_password` |

All documented endpoints respond as expected. Authentication is session-based (cookie after login).

---

## 1. Authentication

### 1.1 Login — `POST /api/lms/login`

**Request (success):**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "email": "lms_user",
    "password": "1"
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
      "user_id": 701,
      "session_id": "68159dcc38aad46106084b5879134da6a24eef1f"
    }
  }
}
```

**Request (invalid credentials):**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "email": "lms_user",
    "password": "wrong_password"
  },
  "id": 9
}
```

**Response (error):**

```json
{
  "jsonrpc": "2.0",
  "id": 9,
  "result": {
    "success": false,
    "error": "unauthorized",
    "message": "Invalid email or password"
  }
}
```

**Verification:** ✅ Login succeeds with valid credentials and returns `user_id` and `session_id`. Invalid credentials return `unauthorized`. Client must send the session cookie on subsequent requests.

---

### 1.2 Logout — `POST /api/lms/logout`

**Request:**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {},
  "id": 8
}
```

**Response:**

```json
{
  "jsonrpc": "2.0",
  "id": 8,
  "result": {
    "success": true,
    "message": "Logout successful"
  }
}
```

**Verification:** ✅ Logout succeeds when called with a valid session cookie.

---

## 2. Student Endpoints

### 2.1 Search Students — `POST /api/lms/student/search`

**Request (by national_id):**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "national_id": "31901171700812"
  },
  "id": 2
}
```

**Response (success):**

```json
{
  "jsonrpc": "2.0",
  "id": 2,
  "result": {
    "success": true,
    "data": {
      "count": 1,
      "students": [
        {
          "id": 8197,
          "student_full_name": "ياسين  أحمد  السيد سمري",
          "student_en_full_name": "Yasseen Ahmed Elsayed Semary",
          "sequence": "S-23-08197",
          "year_apply_for": {
            "id": 34,
            "display_name": "الصف الاول الابتدائى"
          },
          "standard_id": {
            "id": 19,
            "display_name": "1/A ابتدائى"
          },
          "license_due_date": "2023-10-17",
          "guardian_national_id": "28103242101757"
        }
      ]
    }
  }
}
```

**Request (by name — partial match):**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "name": "ا"
  },
  "id": 3
}
```

**Response (success — truncated):** Returns `count: 100` and up to 100 students. Sample first record:

```json
{
  "jsonrpc": "2.0",
  "id": 3,
  "result": {
    "success": true,
    "data": {
      "count": 100,
      "students": [
        {
          "id": 271,
          "student_full_name": "اروى خيرى عبد الهادى السيد مشالى",
          "student_en_full_name": "Arwa Khairy AbdelHadi ElSayed Mashali",
          "sequence": "S-21-00271",
          "year_apply_for": { "id": 27, "display_name": "الصف الرابع - الابتدائي" },
          "standard_id": { "id": 125, "display_name": "4/A ابتدائى" },
          "license_due_date": "2022-08-08",
          "guardian_national_id": "28204301700237"
        }
      ]
    }
  }
}
```

**Verification:** ✅ Search by `national_id` returns exact match. Search by `name` returns partial matches (e.g. Arabic character "ا") with pagination/cap (100 in sample). All documented student fields are present.

**Get-all / Pagination (verified by integration):**
- The API does **not** support `offset` or `limit`; a request with those params returns the same first 100 results.
- Search by **`id`** (e.g. `params: {"id": 271}`) returns exactly one student when the id exists, or `not_found` when it does not. Student ids in production are not necessarily contiguous (e.g. 271, 276, 279, …).
- To obtain **all** students (or parents), the Moodle sync uses **name-prefix iteration**: it calls search once per Arabic letter (ا, أ, آ, إ, ب, ت, … ي), merges results by id, and thus avoids the 100-per-call cap. Sequential id scan (e.g. 1 to N) is possible but requires knowing the id range and issues one request per id.

---

### 2.2 Update Student LMS Credentials — `POST /api/lms/student/update`

**Request:**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "id": 8197,
    "lms_username": "test_lms_user",
    "lms_password": "test_pass_123"
  },
  "id": 4
}
```

**Response:**

```json
{
  "jsonrpc": "2.0",
  "id": 4,
  "result": {
    "success": true,
    "message": "Student LMS credentials updated successfully",
    "data": {
      "id": 8197,
      "lms_username": "test_lms_user",
      "lms_password": "test_pass_123"
    }
  }
}
```

**Verification:** ✅ Update by student `id` succeeds. API accepts `id` or `national_id` plus optional `lms_username` and `lms_password`.

---

## 3. Parent Endpoints

### 3.1 Search Parents — `POST /api/lms/parent/search`

**Important:** At least one search parameter is required: `id`, `name`, or `national_id`. Empty `params` returns `bad_request`.

**Request (no params — error case):**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {},
  "id": 5
}
```

**Response (error):**

```json
{
  "jsonrpc": "2.0",
  "id": 5,
  "result": {
    "success": false,
    "error": "bad_request",
    "message": "At least one search parameter (id, name, or national_id) is required"
  }
}
```

**Request (by name):**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "name": "ا"
  },
  "id": 6
}
```

**Response (success — sample records):**

```json
{
  "jsonrpc": "2.0",
  "id": 6,
  "result": {
    "success": true,
    "data": {
      "count": 100,
      "parents": [
        {
          "id": 48,
          "name": "خيرى عبد الهادى السيد مشالى",
          "email": "eman.sobh@pharma.cu.edu.eg",
          "identification_id": "28204301700237"
        },
        {
          "id": 49,
          "name": "ياسر محمد محمود عبد الكريم",
          "email": "",
          "identification_id": "28102011700198"
        }
      ]
    }
  }
}
```

**Verification:** ✅ Search with `name` returns parents; empty params correctly return `bad_request`. Response includes `id`, `name`, `email`, `identification_id`.

---

### 3.2 Update Parent LMS Credentials — `POST /api/lms/parent/update`

**Request:**

```json
{
  "jsonrpc": "2.0",
  "method": "call",
  "params": {
    "id": 48,
    "lms_username": "test_parent_lms",
    "lms_password": "test_parent_pass"
  },
  "id": 7
}
```

**Response:**

```json
{
  "jsonrpc": "2.0",
  "id": 7,
  "result": {
    "success": true,
    "message": "Parent LMS credentials updated successfully",
    "data": {
      "id": 48,
      "lms_username": "test_parent_lms",
      "lms_password": "test_parent_pass"
    }
  }
}
```

**Verification:** ✅ Update by parent `id` succeeds. API accepts `id` or `national_id` (identification_id) plus optional `lms_username` and `lms_password`.

---

## 4. Error Response Summary

| Error Code     | When it occurs |
|----------------|----------------|
| `bad_request`  | Missing or invalid parameters (e.g. parent search with no params) |
| `unauthorized` | Invalid login credentials or expired/missing session |
| `not_found`    | No resource matching criteria (e.g. no students/parents found) |
| `server_error` | Internal server error |

All responses follow JSON-RPC 2.0 with `result.success`, and on failure `result.error` and `result.message`.

---

## 5. Conclusion

- **Login / Logout:** Working; session cookie must be sent for protected endpoints.
- **Student search:** Working with `id`, `name`, and `national_id`; sample data shows full student structure (Arabic/English names, sequence, grade, class, license date, guardian national ID).
- **Student update:** Working with `id` or `national_id` and optional LMS username/password.
- **Parent search:** Working when at least one of `id`, `name`, or `national_id` is provided; empty params correctly rejected.
- **Parent update:** Working with `id` or `national_id` and optional LMS username/password.

The LMS Integration API is **ready for production use** for syncing student and parent data from Odoo. Recommend documenting for the client that **parent search always requires at least one search parameter** (unlike a “list all” call).
