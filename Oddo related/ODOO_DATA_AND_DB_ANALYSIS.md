# Odoo LMS Data vs Platform Database — Analysis

## How Odoo stores data (from API responses)

### Students — `POST /api/lms/student/search`

| Odoo field | Type | Example | Notes |
|------------|------|---------|--------|
| `id` | number | 8197 | Odoo student record ID (primary key in Odoo) |
| `student_full_name` | string | "ياسين  أحمد  السيد سمري" | Arabic full name |
| `student_en_full_name` | string | "Yasseen Ahmed Elsayed Semary" or "" | English full name (can be empty) |
| `sequence` | string | "S-23-08197" | School sequence/code (unique per student) |
| `year_apply_for` | object | `{ id: 34, display_name: "الصف الاول الابتدائى" }` | Grade level |
| `standard_id` | object | `{ id: 19, display_name: "1/A ابتدائى" }` | Class/section |
| `license_due_date` | string \| null | "2023-10-17" or null | License expiry (YYYY-MM-DD) |
| `guardian_national_id` | string | "28103242101757" | Links to parent (parent.identification_id) |

### Parents — `POST /api/lms/parent/search`

| Odoo field | Type | Example | Notes |
|------------|------|---------|--------|
| `id` | number | 48 | Odoo parent record ID |
| `name` | string | "خيرى عبد الهادى السيد مشالى" | Full name |
| `email` | string | "eman.sobh@pharma.cu.edu.eg" or "" | Can be empty |
| `identification_id` | string | "28204301700237" | National ID (matches student.guardian_national_id) |

### Courses / classes in Odoo

Odoo does **not** expose a separate course/list endpoint. A “class” is the pair:

- `(year_apply_for.id, standard_id.id)`  
  or  
- `(year_apply_for.display_name, standard_id.display_name)`  

One platform course is derived per unique such pair; enrollments come from “student belongs to this class”.

---

## Platform database mapping

### `users` (students from Odoo)

| Platform column | Source | Notes |
|-----------------|--------|--------|
| `odoo_student_id` | student.id | UNIQUE, link to Odoo |
| `odoo_student_sequence` | student.sequence | e.g. S-23-08197 (display/lookup) |
| `national_id` | (optional; Odoo API does not return student national_id) | Left null for students unless API extended |
| `first_name`, `last_name` | student_en_full_name or student_full_name | Split on last space |
| `license_expiry_date` | student.license_due_date | DATE |
| `email` | Placeholder `odoo_student_{id}@platform.local` | Required unique; Odoo does not provide student email |
| `role` | 'student' | |

### `users` (parents from Odoo)

| Platform column | Source | Notes |
|-----------------|--------|--------|
| `odoo_parent_id` | parent.id | UNIQUE |
| `national_id` | parent.identification_id | Indexed for guardian link |
| `first_name`, `last_name` | parent.name | Split on last space |
| `email` | parent.email or `odoo_parent_{id}@platform.local` | |
| `role` | 'parent' | |

### `parent_student_relationships`

- `parent_id`: platform user id (parent), from lookup by `identification_id` = student.`guardian_national_id`.
- `student_id`: platform user id (student).

### `courses` (derived from Odoo class)

| Platform column | Source | Notes |
|-----------------|--------|--------|
| `odoo_standard_id` | standard_id.id | |
| `odoo_year_id` | year_apply_for.id | |
| `name` | display_name (e.g. "4/A ابتدائى") | From standard_id/year_apply_for |
| `code` | `odoo-{standard_id}-{year_id}` | Unique per class |
| UNIQUE(odoo_standard_id, odoo_year_id) | | One platform course per Odoo class |

### `course_enrollments`

- `course_id`: platform course id (from odoo_standard_id + odoo_year_id).
- `student_id`: platform user id (student).
- Derived from “each student’s (standard_id, year_apply_for) → one course”.

### `odoo_sync_log`

| Column | Purpose |
|--------|---------|
| sync_type | e.g. 'auto' |
| entity_type | 'user', 'enrollment', 'full_sync' |
| entity_id | 0 for full_sync, or single-entity id |
| entity_odoo_id | Odoo-side id when syncing one record |
| records_affected | Count for full_sync |
| details | JSON (e.g. counts per entity type) |
| status | success / failed / pending |
| error_message | On failure |

---

## Improvements applied

1. **Odoo columns (already in schema/migration)**  
   - `users`: `odoo_student_id`, `odoo_parent_id`, `national_id`, `odoo_student_sequence`.  
   - `courses`: `odoo_standard_id`, `odoo_year_id`, unique (odoo_standard_id, odoo_year_id).  
   - `odoo_sync_log`: `entity_odoo_id`, `records_affected`, `details`.

2. **`odoo_student_sequence`**  
   - Added so we store Odoo’s student code (e.g. S-23-08197) for display and optional search; sync writes it on insert/update from `student.sequence`.

3. **Live requests**  
   - Test script: `backend/scripts/test-odoo-lms-api.ts`.  
   - Set `ODOO_LMS_BASE_URL`, `ODOO_LMS_EMAIL`, `ODOO_LMS_PASSWORD` in `.env` (to a real Odoo LMS instance), then run from `backend`:  
     `npx ts-node scripts/test-odoo-lms-api.ts`  
   - With placeholders (e.g. `your-odoo-instance.com`), the script reports “getaddrinfo ENOTFOUND”; with valid config it will log login + student/parent response shapes.

---

## Running the migration

For an **existing** database, run once:

```bash
mysql -u root -p aals_platform < backend/database/migrations/add_odoo_lms_sync_columns.sql
```

New installs use `schema_unified.sql`, which already includes these columns.
