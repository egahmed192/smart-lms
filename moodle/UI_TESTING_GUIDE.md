# Moodle–Odoo Integration: UI Testing Guide

Follow these steps **in order** to test every part of the plan from the Moodle (and Odoo) UI. Replace `https://your-moodle-site` with your actual Moodle URL. **If you use XAMPP with the moodle folder in htdocs:** use `http://localhost:8080/moodle` (the app is served from the `public/` subfolder via .htaccess).

---

## Before you start

- **Moodle** is running and you can log in as an administrator.
- **Odoo** API is reachable (if you test sync); you have API URL, user, and password.
- You have (or will create) test users: at least one **Student**, one **Parent** (linked to that student), one **Teacher**, and one **Manager/School Admin**.

---

## Part 1: Site setup and theme

### 1.1 Enable the School theme

1. Log in as **Administrator**.
2. Go to **Site administration** (gear icon or `/admin`) → **Appearance** → **Themes** → **Theme settings**.
3. Set **Default theme** (and optionally **Allow user themes**) to **School**.
4. Save.
5. **Check:** After refresh, the site uses the School theme (same look as Boost; role links may appear where the theme uses them).

---

## Part 2: Odoo sync (local_odoo_sync)

### 2.1 Configure Odoo API

1. **Site administration** → **Plugins** → **Local plugins** → **Odoo sync** (or go to **Site administration** → **Settings** and find **Odoo sync** in the block).
2. Set:
   - **Odoo API URL** (e.g. `https://arafa.online` or your Odoo instance).
   - **Odoo API user** and **Odoo API password**.
   - **License expiry warning (days before)** (e.g. `7`).
3. Save.
4. **Check:** No PHP errors; settings are stored.

### 2.2 Run / check sync status

1. **Site administration** → **Plugins** → **Local plugins** → **Odoo sync** → **Sync status**  
   **or** open: `https://your-moodle-site/local/odoo_sync/status.php`
2. You should see either “No failures” or a list of sync failures with **Retry** buttons.
3. To run the sync task:
   - **Site administration** → **Server** → **Scheduled tasks**.
   - Find **“Sync from Odoo”** (`local_odoo_sync\task\sync_from_odoo`).
   - Click **Run now**.
4. **Check:** After run, status page shows no new errors (or retry individual failures). In **Site administration** → **Users** → **Browse list of users**, you should see **Students** and **Parents** created/updated from Odoo (if Odoo has data).

### 2.3 Course mapping (year + class)

- Sync enrolls students into Moodle courses based on **year** and **class** (standard) from Odoo.
- Courses must be mapped to year+class in the `local_odoo_sync_course_map` table (usually via sync or admin tool; check plugin docs or DB).
- **Check:** After sync, students appear in the correct courses.

---

## Part 3: License enforcement

### 3.1 When license is valid

1. Log in as a **Student** or **Parent** that has a valid license in Odoo (and was synced).
2. **Check:** You can use the site normally (dashboard, courses, etc.).

### 3.2 When license is expired (blocked page)

1. In Odoo (or in the Moodle DB table `local_odoo_sync_lic`), set that user’s license to **expired** (or past `license_due_date`).
2. Run the Odoo sync again (or update the record in Moodle for testing).
3. Log in as that user (or refresh if already logged in).
4. **Check:** You are redirected to the **blocked** page:  
   `https://your-moodle-site/local/odoo_sync/blocked.php`  
   with a message that access is blocked and a **Logout** button.

### 3.3 License expiry warning (notification)

- The plugin can send a warning **X days before** expiry (X = setting from 2.1).
- **Check:** With a user whose license expires in less than X days, after the scheduled task runs, the user receives the configured notification (in-app or email, depending on implementation).

---

## Part 4: Parent–student relationships (local_parent_portal)

### 4.1 Admin: View / manage relationships

1. Log in as **Administrator** (or user with `local/parent_portal:manage_relationships`).
2. Go to **Site administration** → **Users** → **Parent portal**  
   **or** open: `https://your-moodle-site/local/parent_portal/index.php`
3. **Check:** You see a list of **parent–student relationships** (or “No relationships”).
4. Click **Add relationship**.
5. Choose a **Parent** user and a **Student** user, then save.
6. **Check:** The new row appears in the list. You can delete it from the list if needed.

### 4.2 Parent: Dashboard and child view

1. Log in as a **Parent** that has at least one linked **Student** (from sync or from 4.1).
2. Open **My children** (Parent dashboard):  
   `https://your-moodle-site/local/parent_portal/dashboard.php`  
   (Or use the theme link “My children” if the theme shows it.)
3. **Check:** You see a list of linked children (names/links).
4. Click one child.
5. **Check:** You see **Child overview** (`child.php`): courses, grades link, and optionally messages. If that child’s license is expired, you see the “license expired” message and restricted access.

### 4.3 Parent: No access to other students

1. Log in as **Parent**.
2. Manually open: `https://your-moodle-site/local/parent_portal/child.php?studentid=ANOTHER_STUDENT_ID` (ID of a student **not** linked to this parent).
3. **Check:** Access is denied or you are redirected (no data for other students).

---

## Part 5: Messaging audit (local_message_audit)

### 5.1 View message log

1. Log in as **Administrator** (or user with `local/message_audit:view_logs`).
2. Go to **Site administration** → **Reports** → **Message audit**  
   **or** open: `https://your-moodle-site/local/message_audit/index.php`
3. **Check:** You see the **Message log** (sender, receiver, time, message preview, flagged). Use “Flagged only” and “All” links to filter.

### 5.2 Keyword rules

1. On the same Message audit page, click **Keyword rules**  
   **or** open: `https://your-moodle-site/local/message_audit/keywords.php`
2. Add a keyword rule (pattern, severity, action: e.g. flag, block, notify).
3. **Check:** Rule is saved and listed.
4. Send a message (as any user) that contains that keyword.
5. **Check:** In **Message log**, that message appears and is **Flagged** (and, if implemented, admin gets a notification).

### 5.3 Student–parent messaging restriction

1. Log in as a **Student** and try to send a **direct message** to a **Parent** (or vice versa).
2. **Check:** Message is blocked or restricted, with a notice that student–parent messaging is not allowed (or only with teacher/supervisor). Exact message from lang string `student_parent_violation`.

### 5.4 Bulk messaging

1. Log in as **Manager** (or user with `local/message_audit:send_bulk_message`).  
   - If you use a “School Admin” role, assign that role the capability **Send bulk message** in **Site administration** → **Users** → **Permissions** → **Define roles** (or **Assign roles**).
2. Go to **Site administration** → **Reports** → **Send bulk message**  
   **or** open: `https://your-moodle-site/local/message_audit/bulk.php`
3. **Check:** You see **Target** dropdown: All students, All teachers, All parents, All users, class-based options, cohort, parents of class.
4. Select a target (e.g. **All students**). If needed, set **Course**, **Class**, or **Cohort**.
5. Click **Update count**. **Check:** Recipients count updates.
6. Enter a short **Message** and click **Send to N recipients**.
7. **Check:** Success message “Bulk message sent to X recipients.” You receive a confirmation notification.
8. In **Message log** (Part 5.1), **Check:** One log entry per recipient with `bulk_send` / bulk metadata; you can filter or scan to see bulk sends.

---

## Part 6: Assessments (local_assessments)

Assessments are **per course**. You need a course where you are Teacher or have assessment management rights.

### 6.1 Open assessments in a course

1. Log in as **Teacher** (or Assessment Manager / Exams Officer).
2. Go to **My courses** and open a **course**.
3. Open the assessments list:  
   `https://your-moodle-site/local/assessments/index.php?id=COURSE_ID`  
   (Replace `COURSE_ID` with the course id, e.g. from the course URL.)
4. **Check:** You see **Assessments** list (or “No assessments”).

### 6.2 Create a public assessment

1. On the assessments list, click **Add assessment**.
2. Create an assessment: **Name**, **Type = Public**, **Full mark**, **Weight**, **Announcement date**, **Assessment date**, etc. Save.
3. **Check:** It appears in the list with type “public”.

### 6.3 Enter evaluations (grades)

1. On the assessments list, click **Evaluations** for that assessment.
2. Enter **marks** for students (and comments if the form has them). Save.
3. **Check:** Average and ranks update; you can see the list of evaluations.

### 6.4 Secretive assessment (and secret codes)

1. Create another assessment with **Type = Secretive** (if your role has **Manage secretive assessments**).
2. **Check:** Only users with `local/assessments:manage_secretive` see full management; teachers may only create/edit public.
3. **Export secret codes:** As a user with **View secret codes** (e.g. Assessment Manager):
   - **Site administration** → **Reports** → **Export secret codes**  
   **or** open: `https://your-moodle-site/local/assessments/export_codes.php`
4. **Check:** You get a CSV/Excel of student secret codes (and that it’s logged). Teachers must **not** see secret codes in the normal UI.

### 6.5 Excel import (evaluations)

1. In a course, open an assessment that allows import (you have **Import/export** or **Manage secretive**).
2. Open: `https://your-moodle-site/local/assessments/import.php?id=COURSE_ID&assessmentid=ASSESSMENT_ID`
3. Upload an Excel/CSV with the expected columns (e.g. secret code or user id, mark).
4. **Check:** Dry-run / preview if present, then commit; evaluations appear in **Evaluations** for that assessment.

### 6.6 Grade visibility (announcement date)

1. Set an assessment’s **Announcement date** to a **future** date.
2. Log in as **Student** or **Parent** and open the place where they see grades for that course/assessment.
3. **Check:** They do **not** see grades for that assessment until the announcement date (and after any cache refresh). Teachers/designated roles can see before the date.

---

## Part 7: Grades push to Odoo (local_odoo_sync)

- This is backend/scheduled: Moodle pushes course totals (and optionally assessment details) to Odoo via the sync plugin.
1. **Site administration** → **Server** → **Scheduled tasks**.
2. Find the task that pushes grades (e.g. **Push grades to Odoo**).
3. Run it manually.
4. **Check:** In Odoo (or via API), the corresponding student/course grade data is updated. In Moodle, check **Sync status** for any push failures and retry if needed.

---

## Part 8: Quick checklist (all plans)

| # | What to test | Where (UI) | Pass? |
|---|----------------|------------|-------|
| 1 | School theme active | Appearance → Theme settings | ☐ |
| 2 | Odoo sync settings saved | Settings → Odoo sync | ☐ |
| 3 | Sync runs; status page | Reports / Local plugins → Odoo sync → Sync status; Scheduled tasks | ☐ |
| 4 | License blocked page | Log in as expired user → blocked.php | ☐ |
| 5 | Parent relationships list & add | Users → Parent portal | ☐ |
| 6 | Parent dashboard & child view | Parent: Parent portal dashboard, child.php | ☐ |
| 7 | Message log & filters | Reports → Message audit | ☐ |
| 8 | Keyword rules & flagging | Message audit → Keyword rules; send message with keyword | ☐ |
| 9 | Student–parent messaging blocked | Student → message Parent (or vice versa) | ☐ |
| 10 | Bulk message send & audit | Reports → Send bulk message; then Message log | ☐ |
| 11 | Assessments list & add (course) | Course → /local/assessments/index.php?id=... | ☐ |
| 12 | Evaluations & grades | Assessments → Evaluations | ☐ |
| 13 | Secret codes export | Reports → Export secret codes | ☐ |
| 14 | Excel import evaluations | Assessments → Import | ☐ |
| 15 | Announcement date hides grades | Student/Parent view before date | ☐ |
| 16 | Grades push to Odoo | Scheduled task; verify in Odoo/API | ☐ |

---

## Troubleshooting

- **403 / Access denied:** Assign the required capability to your role (Define roles → choose role → edit → enable the capability).
- **Bulk / Message audit not in menu:** You need `local/message_audit:view_logs` or `send_bulk_message`; menu is under **Reports**.
- **Parent portal not in menu:** You need `local/parent_portal:manage_relationships` (admin) or `local/parent_portal:view_child_data` (parent dashboard).
- **Assessments link in course:** There may be no automatic course menu item; bookmark or add a link to `/local/assessments/index.php?id=COURSE_ID` in the course (e.g. in a description or custom block).
- **Odoo sync fails:** Check API URL, credentials, and firewall; check **Sync status** for error messages and use **Retry** for failed items.

---

*Replace `https://your-moodle-site` with your real Moodle base URL (e.g. `http://localhost/moodle/public`) when opening the direct links.*
