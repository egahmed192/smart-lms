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

## Staged rollout

- Deploy to staging first; run Odoo sync with a test API account.
- Pilot with a small group of teachers and parents before full go-live.
- Monitor `local_odoo_sync_license_audit` and `local_message_audit_log` for issues.
