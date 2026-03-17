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

$string['pluginname'] = 'تدقيق الرسائل';
$string['message_log'] = 'سجل الرسائل';
$string['view_logs'] = 'عرض سجلات الرسائل';
$string['bulk_message'] = 'إرسال رسالة جماعية';
$string['sender'] = 'المرسل';
$string['receiver'] = 'المستلم';
$string['time'] = 'الوقت';
$string['flagged'] = 'مُعلَّمة';
$string['keyword_rules'] = 'قواعد الكلمات المفتاحية';
$string['add_keyword'] = 'إضافة كلمة';
$string['messages_monitored_notice'] = 'يتم مراقبة جميع الرسائل لأغراض الامتثال.';
$string['no_messages'] = 'لا توجد رسائل.';
$string['no_keywords'] = 'لا توجد قواعد كلمات مفتاحية.';
$string['pattern'] = 'النمط';
$string['severity'] = 'الخطورة';
$string['action'] = 'الإجراء';
$string['action_flag'] = 'تعليم';
$string['action_notify_admin'] = 'إشعار الإدارة';
$string['action_flag_and_notify'] = 'تعليم وإشعار الإدارة';
$string['bulk_sent'] = 'تم إرسال الرسالة الجماعية إلى {$a} مستلم.';
$string['student_parent_violation'] = 'لا يُسمح بمراسلة الطالب/ولي الأمر (المرسل لا يملك الصلاحية).';
$string['target'] = 'الهدف';
$string['target_students'] = 'كل الطلاب';
$string['target_teachers'] = 'كل المعلمين';
$string['target_parents'] = 'كل أولياء الأمور';
$string['target_all'] = 'كل المستخدمين';
$string['target_class_students'] = 'كل طلاب الصف';
$string['target_class_teachers'] = 'كل معلمي الصف';
$string['target_class_all'] = 'كل مستخدمي الصف';
$string['target_cohort'] = 'كل مستخدمي الدفعة';
$string['target_parents_of_class'] = 'كل أولياء أمور طلاب الصف';
$string['class'] = 'الصف';
$string['cohort'] = 'الدفعة';
$string['recipients'] = 'المستلمون';
$string['no_recipients'] = 'لا يوجد مستلمون يطابقون عوامل التصفية المحددة.';
$string['message'] = 'الرسالة';
$string['send_to_n_recipients'] = 'إرسال إلى {$a} مستلم';
$string['class_label'] = 'السنة {$a->year} – الفصل {$a->standard}';
$string['update_count'] = 'تحديث العدد';
$string['message_flagged_notify_subject'] = 'تم تعليم رسالة للمراجعة';
$string['message_flagged_notify_body'] = 'تم تعليم رسالة بواسطة قواعد الكلمات المفتاحية.

المرسل: {$a->sender}
المستلم: {$a->receiver}
المطابقة: {$a->reason}

معاينة: {$a->preview}

افتح سجل الرسائل للتفاصيل.';
$string['bulk_send_notification'] = 'تم إرسال رسالة جماعية إلى {$a} مستلم.';
$string['bulk_sending'] = 'جارٍ إرسال الرسائل الجماعية';
$string['bulk_progress_intro'] = 'جارٍ الإرسال إلى {$a} مستلم... الرجاء الانتظار.';
$string['bulk_progress_status'] = 'تم إرسال {$a->sent} من {$a->total}';
$string['bulk_progress_nodata'] = 'لا يوجد إرسال جماعي جارٍ.';
$string['bulk_progress_error'] = 'حدث خطأ أثناء الإرسال.';
$string['back_to_bulk'] = 'العودة إلى الإرسال الجماعي';

// Phone number flagging.
$string['flag_egyptian_phones'] = 'تعليم أرقام الهواتف المصرية';
$string['flag_egyptian_phones_help'] = 'عند التفعيل، سيتم تعليم الرسائل التي تحتوي على أرقام هواتف مصرية (مثل +20... أو 0020... أو 01xxxxxxxxx) تلقائياً.';
$string['flag_reason_egyptian_phone'] = 'تم اكتشاف رقم هاتف مصري';

