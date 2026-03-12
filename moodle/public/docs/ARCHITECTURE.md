# LMS Integration Architecture

## Platform versions

- **Moodle:** 5.1.3 (branch 501, version 2025100603)
- **Odoo:** LMS Integration API at `https://arafa.online` (see `Oddo related/LMS_API_Endpoint_Report.md`)
- **Integration:** Moodle is the main LMS; Odoo provides student/parent data via API only. No Odoo course API; course enrollment is derived from student `year_apply_for` and `standard_id`.

## Plugin structure

| Plugin | Purpose |
|--------|---------|
| `local_odoo_sync` | Odoo API client, sync students/parents, license expiry, course enrollment by year+class, push LMS credentials to Odoo |
| `local_parent_portal` | Parent–student relationship table, capabilities, parent dashboard and child views |
| `local_message_audit` | Message logging, keyword rules, admin monitoring UI, bulk messaging, compliance notices |
| `local_assessments` | Custom assessments, evaluations, student secret codes, Excel import/export, grade visibility by announcement date |
| `theme_school` | Custom theme with role-based dashboards (extends Boost) |

## Data flow

- **From Odoo:** student/search, parent/search → users, license_due_date, guardian_national_id → Moodle users, parent_portal links, enrollments (by year+standard_id).
- **To Odoo:** After creating Moodle user → student/update or parent/update with lms_username, lms_password.
- **Courses:** Created in Moodle; tagged with year_apply_for.id and standard_id.id; students auto-enrolled by sync. Class change in Odoo → unenroll from old courses, enroll in new.

## Roles

- **Core:** Student, Teacher, School Admin, Parent.
- **Additional:** System Administrator, Supervisor, Message Monitor, Assessment Manager.
- Teachers and School Admins are Moodle-only; Students and Parents synced from Odoo.
