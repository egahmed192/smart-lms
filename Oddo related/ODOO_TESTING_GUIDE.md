# How to Test the Odoo LMS Integration

This guide covers how to test all new Odoo functions: API client, status endpoint, manual sync, and hourly sync.

---

## 1. Prerequisites

### Environment (backend `.env`)

Ensure these are set for **live Odoo** tests (optional for status-only tests):

```env
ODOO_LMS_BASE_URL=https://your-odoo-lms.com
ODOO_LMS_EMAIL=your_lms_user
ODOO_LMS_PASSWORD=your_lms_password
```

Or legacy names: `ODOO_URL`, `ODOO_USERNAME`, `ODOO_PASSWORD` (see `backend/env-template.txt`).

- **Without these:** you can still call **status** and **sync**; sync will return “Odoo LMS configuration not set”.
- **With these:** you can run the test script, trigger sync, and rely on the hourly job.

### Admin login

All integration endpoints require **admin** role. Use the admin account you created (e.g. `admin@aals.com` / `admin123`) to get a JWT for the API calls below.

---

## 2. Test 1: Odoo status (no Odoo required)

Checks if Odoo is configured and shows last sync info.

**Request:**

```http
GET http://localhost:3000/api/integrations/odoo/status
Authorization: Bearer <ADMIN_JWT>
```

**Example (PowerShell):**

```powershell
$token = "YOUR_JWT_HERE"
Invoke-RestMethod -Uri "http://localhost:3000/api/integrations/odoo/status" -Headers @{ Authorization = "Bearer $token" }
```

**Expected response (not configured):**

```json
{
  "configured": false,
  "lastSyncAt": null,
  "lastSyncStatus": null
}
```

**Expected response (configured, after at least one sync):**

```json
{
  "configured": true,
  "lastSyncAt": "2025-03-10T12:00:00.000Z",
  "lastSyncStatus": "success"
}
```

---

## 3. Test 2: Odoo LMS API script (login + student/parent search)

This script calls the Odoo LMS JSON-RPC API directly: login, then student search and parent search. Use it to verify connectivity and response shape.

**Run (from backend folder):**

```bash
cd backend
npx ts-node scripts/test-odoo-lms-api.ts
```

**Requires in `.env`:** `ODOO_LMS_BASE_URL`, `ODOO_LMS_EMAIL`, `ODOO_LMS_PASSWORD` (or legacy `ODOO_URL` / `ODOO_USERNAME` / `ODOO_PASSWORD`).

**What it does:**

- Logs in to `/api/lms/login`.
- Calls `/api/lms/student/search` with `name: 'ا'` and logs count + sample student.
- Calls `/api/lms/parent/search` with `name: 'ا'` and logs count + sample parent.
- Prints field analysis for DB mapping.

If credentials are missing, it only prints doc-based analysis and does not call Odoo.

---

## 4. Test 3: Manual full sync (Odoo → platform)

Runs the full sync: fetch students/parents from Odoo, upsert users, derive courses, upsert enrollments, deactivate expired licenses, and optionally push to Moodle (if Moodle sync is part of the same flow).

**Request:**

```http
POST http://localhost:3000/api/integrations/odoo/sync
Authorization: Bearer <ADMIN_JWT>
Content-Type: application/json

{
  "entity_type": "all",
  "direction": "from"
}
```

**Example (PowerShell):**

```powershell
$token = "YOUR_JWT_HERE"
$body = '{"entity_type":"all","direction":"from"}'
Invoke-RestMethod -Uri "http://localhost:3000/api/integrations/odoo/sync" -Method Post -Headers @{ Authorization = "Bearer $token"; "Content-Type" = "application/json" } -Body $body
```

**Other supported payloads:**

| Purpose              | Body |
|----------------------|------|
| Sync one user from Odoo | `{"entity_type":"user","entity_id":123,"direction":"from"}` |
| Sync one enrollment from Odoo | `{"entity_type":"enrollment","entity_id":456,"direction":"from"}` |
| Sync one user to Odoo | `{"entity_type":"user","entity_id":123,"direction":"to"}` |
| Sync one enrollment to Odoo | `{"entity_type":"enrollment","course_id":1,"student_id":2,"direction":"to"}` |

**Expected (configured, success):** `200` with `message` and `result` (e.g. `updatedUserIds`, `updatedCourseIds`).

**Expected (not configured):** `400` with `error: "Odoo LMS configuration not set"` and instructions.

---

## 5. Test 4: Hourly sync (automatic)

The server runs an **hourly job** that:

1. Calls `syncAllFromOdoo()` (if Odoo integration is enabled).
2. Then runs Moodle sync (users, courses, enrollments for rows with `moodle_enrollment_id` NULL).

**How to verify:**

- Ensure `system_config.odoo_integration_enabled` is `true` (or set `ODOO_INTEGRATION_ENABLED=true` in env if the app reads it).
- Start the backend and wait for the first run (logs say “first run in 1 minute”) or trigger a manual sync (Test 3).
- After a sync, call **GET /api/integrations/odoo/status** again; `lastSyncAt` and `lastSyncStatus` should update.
- Check `odoo_sync_log` in the database for `synced_at`, `status`, and `details`.

---

## 6. Quick checklist

| Test | What | Command / endpoint |
|------|------|--------------------|
| 1 | Odoo configured? Last sync? | `GET /api/integrations/odoo/status` (with admin JWT) |
| 2 | Live Odoo API (login + search) | `npx ts-node scripts/test-odoo-lms-api.ts` (from `backend`) |
| 3 | Manual full sync | `POST /api/integrations/odoo/sync` with `{"entity_type":"all","direction":"from"}` (admin JWT) |
| 4 | Hourly sync | Start backend; check logs and `odoo_sync_log` after 1 minute and each hour |

---

## 7. Getting an admin JWT

**Option A – Login via API:**

```http
POST http://localhost:3000/api/auth/login
Content-Type: application/json

{"email":"admin@aals.com","password":"admin123"}
```

Use the `token` from the response in the `Authorization: Bearer <token>` header.

**Option B – Browser:** Log in at `http://localhost:4200`, then in DevTools → Application (or Network) find the token your app sends (e.g. in `Authorization` header or stored auth service) and copy it for the requests above.

---

## 8. Database checks after sync

- **users:** `odoo_student_id`, `odoo_parent_id`, `odoo_student_sequence`, `national_id` populated for synced users.
- **courses:** Rows created/updated from Odoo standards/years; `odoo_standard_id`, `odoo_year_id` set.
- **course_enrollments:** Synced enrollments; optional `moodle_enrollment_id` after Moodle sync.
- **odoo_sync_log:** One row per sync with `synced_at`, `status`, `details`.

This lets you confirm that the new Odoo functions (status, API script, manual sync, hourly job) work end-to-end.
