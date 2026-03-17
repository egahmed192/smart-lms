<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

$string['pluginname'] = 'مزامنة أودو';
$string['access_blocked'] = 'تم حظر الوصول';
$string['access_blocked_message'] = 'تم إيقاف وصولك إلى نظام إدارة التعلم بسبب انتهاء الترخيص. يرجى التواصل مع إدارة المدرسة.';
$string['odoo_sync_settings'] = 'إعدادات مزامنة أودو';
$string['odoo_api_url'] = 'رابط واجهة برمجة التطبيقات لأودو';
$string['odoo_api_url_help'] = 'الرابط الأساسي لواجهة LMS في أودو (مثال: https://arafa.online)';
$string['odoo_api_user'] = 'مستخدم واجهة API (البريد الإلكتروني)';
$string['odoo_api_password'] = 'كلمة مرور واجهة API';
$string['task_sync_from_odoo'] = 'مزامنة المستخدمين والتراخيص من أودو';
$string['odoo_apierror'] = 'خطأ في واجهة أودو: {$a}';
$string['odoo_login_failed'] = 'فشل تسجيل الدخول إلى أودو.';
$string['task_push_grades'] = 'إرسال الدرجات إلى أودو';
$string['sync_status'] = 'حالة المزامنة';
$string['sync_status_heading'] = 'أحدث أخطاء المزامنة';
$string['retry'] = 'إعادة المحاولة';
$string['failure_time'] = 'الوقت';
$string['failure_user'] = 'المستخدم';
$string['failure_odoo_id'] = 'معرّف أودو';
$string['failure_action'] = 'الإجراء';
$string['failure_error'] = 'الخطأ';
$string['no_failures'] = 'لا توجد أخطاء حديثة.';
$string['retry_success'] = 'نجحت إعادة المحاولة.';
$string['retry_failed'] = 'فشلت إعادة المحاولة.';
$string['task_license_expiry_reminder'] = 'تذكير انتهاء الترخيص';
$string['license_expiry_days_before'] = 'عدد الأيام قبل الانتهاء لإرسال تذكير';
$string['license_expiry_days_before_help'] = 'سيتم إرسال تذكير يومي للمستخدمين الذين سينتهي ترخيصهم خلال هذا العدد من الأيام.';
$string['license_expiry_reminder_subject'] = 'ترخيصك على وشك الانتهاء';
$string['license_expiry_reminder_body'] = 'سينتهي ترخيص التعلم الخاص بك بتاريخ {$a}. يرجى التواصل مع المدرسة للتجديد.';
$string['course_map'] = 'ربط الصفوف بالمقررات';
$string['course_map_heading'] = 'ربط مقررات مودل بصف أودو (الصف الدراسي + الفصل)';
$string['course_map_intro'] = 'يتم مزامنة السنوات والفصول من بيانات الطلاب في أودو. اربط كل مقرر في مودل بسنة (صف) وفصل في أودو ليتم تسجيل الطلاب تلقائياً.';
$string['odoo_year'] = 'سنة أودو (الصف)';
$string['odoo_standard'] = 'فصل أودو (الفصل/الشعبة)';
$string['moodle_course'] = 'مقرر مودل';
$string['add_mapping'] = 'إضافة ربط';
$string['delete_mapping'] = 'إزالة';
$string['mapping_added'] = 'تمت إضافة الربط.';
$string['mapping_removed'] = 'تمت إزالة الربط.';
$string['no_years_standards'] = 'لا توجد سنوات أو فصول بعد. قم بتشغيل مهمة المزامنة من أودو أولاً.';
$string['synced_years'] = 'السنوات المزامنة (الصفوف)';
$string['synced_standards'] = 'الفصول المزامنة';
$string['profile_category_odoo'] = 'بيانات الطالب (أودو)';
$string['profile_odoo_id'] = 'معرّف طالب أودو';
$string['profile_year'] = 'الصف';
$string['profile_standard'] = 'الفصل';
$string['profile_license_due'] = 'تاريخ انتهاء الترخيص';
$string['profile_license_status'] = 'حالة الترخيص';
$string['profile_license_active'] = 'نشط';
$string['profile_license_expired'] = 'منتهي';
$string['profile_license_other'] = 'أخرى';
$string['course_deleted_id'] = 'تم حذف المقرر (المعرّف {$a})';
$string['mapping_warning_no_manual_enrol'] = 'تحذير: لا توجد طريقة تسجيل يدوي لهذا المقرر.';
$string['when_student_not_in_odoo'] = 'عندما لا يعود الطالب موجوداً في أودو';
$string['when_student_not_in_odoo_help'] = 'إذا كان الطالب قد تمت مزامنته سابقاً ثم لم يعد يظهر في أودو، يتم تطبيق هذا الإجراء عند المزامنة التالية.';
$string['when_student_not_in_odoo_do_nothing'] = 'عدم فعل شيء';
$string['when_student_not_in_odoo_unenrol'] = 'إلغاء التسجيل من جميع المقررات المرتبطة بأودو';
$string['when_student_not_in_odoo_suspend'] = 'إلغاء التسجيل وتعليق حساب المستخدم';

