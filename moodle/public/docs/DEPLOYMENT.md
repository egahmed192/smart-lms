# Deployment and security checklist

## After installing plugins

1. **Upgrade Moodle**: Visit Site administration → Notifications and run the upgrade.
2. **Create roles**: Ensure these roles exist and are assigned as per plan:
   - Student, Teacher, School Admin, Parent (create from Manager duplicate or use existing).
   - Assign capabilities from each plugin's `db/access.php` to the correct roles.
3. **Odoo sync**: Configure Site administration → Plugins → Local plugins → Odoo sync (API URL, user, password). Run the scheduled task once manually (Site administration → Server → Scheduled tasks) to test.
4. **Course mapping**: For auto-enrollment by class, add rows to `local_odoo_sync_course_map` linking each Moodle course to `year_apply_for_id` and `standard_id` from Odoo.
5. **Theme**: Set Site administration → Appearance → Themes → Theme selector to "School" if desired.
6. **Message audit**: Add keyword rules in the database table `local_message_audit_keywords` if you need to flag messages.
7. **Security**: Ensure only designated users have `local/assessments:view_secret_codes` and `local/assessments:manage_secretive`. Run a capability audit (Site administration → Users → Permissions → Check permissions).

## Security checklist

Before go-live, verify:

- **All plugin pages**: Every script checks `require_login()` (or equivalent) and the appropriate capability before rendering content or performing actions.
- **Parent portal**: All parent-facing pages use `local_parent_portal_is_parent_of()` to ensure a parent only sees their linked students, and enforce license checks via `local_odoo_sync_is_license_valid()` where access depends on an active license.
- **Assessments**: Secret codes and export of secret codes are restricted to users with `local/assessments:import_export` or `local/assessments:manage_secretive`; teachers with only `manage_public` cannot create secretive assessments or see secret codes.
- **Message audit**: Bulk messaging and keyword rules are restricted to `local/message_audit:send_bulk_message` and `local/message_audit:view_logs` (or equivalent); student–parent messaging is limited to users with `local/message_audit:message_student_parent`.
- **Odoo sync**: Sync status and settings are restricted to `local/odoo_sync:manage`.
- Run Site administration → Users → Permissions → Check permissions for key roles and fix any over-assigned capabilities.

## Staged rollout

- Deploy to staging first; run Odoo sync with a test API account.
- Pilot with a small group of teachers and parents before full go-live.
- Monitor `local_odoo_sync_license_audit` and `local_message_audit_log` for issues.
