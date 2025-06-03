<?php
/*
$base1 = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base1 .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
*/

//  $base1 = 'https://learnizz.com/';
//  $base = 'https://learnizz.com/';
$base1 = 'http://localhost/LearnEazy/';
 $base = 'http://localhost/LearnEazy/';
 
//  $base1 = 'http://127.0.0.1:8000/';
//  $base = 'http://127.0.0.1:8000/';

define('PREFIX1', $base1.'public/');
define('BASE_PATH', $base.'/');
define('PREFIX', $base);


// $base = 'http://127.0.0.1:8000/';  // no trailing slash

// define('PREFIX', $base);                   
// define('BASE_PATH', $base . '/');           
// define('PREFIX1', $base . 'public/');     

define('URL_HOME', PREFIX.'/home');


// dd($_SERVER);
//Design Source File Paths
define('CSS', PREFIX1.'css/');
define('JS', PREFIX1.'js/');
define('FONTAWSOME', PREFIX1.'font-awesome/css/');
define('IMAGES', PREFIX1.'images/');
define('AJAXLOADER', IMAGES.'ajax-loader.svg');
define('AJAXLOADER_FADEIN_TIME', 100);
define('AJAXLOADER_FADEOUT_TIME', 100);
define('FRONT_ASSETS', PREFIX1.'front/');


define('UPLOADS', PREFIX1.'uploads/');
define('EXAM_UPLOADS', UPLOADS.'exams/');
define('IMAGE_PATH_UPLOAD_SERIES', UPLOADS.'exams/series/');
define('IMAGE_PATH_UPLOAD_SERIES_THUMB', UPLOADS.'exams/series/thumb/');

define('IMAGE_PATH_UPLOAD_EXAMSERIES_DEFAULT', UPLOADS.'exams/series/default.png');

define('IMAGE_PATH_UPLOAD_LMS_CATEGORIES', UPLOADS.'lms/categories/');
define('IMAGE_PATH_UPLOAD_LMS_DEFAULT', UPLOADS.'lms/categories/default.png');
define('IMAGE_PATH_UPLOAD_LMS_CONTENTS', UPLOADS.'lms/content/');

define('IMAGE_PATH_UPLOAD_LMS_SERIES', UPLOADS.'lms/series/');
define('IMAGE_PATH_UPLOAD_LMS_SERIES_THUMB', UPLOADS.'lms/series/thumb/');

define('IMAGE_PATH_UPLOAD_PAID_CONTENT', UPLOADS.'paidcontents/series/');
define('IMAGE_PATH_UPLOAD_PAID_CONTENT_THUMB', UPLOADS.'paidcontents/series/thumb/');

define('IMAGE_PATH_PROFILE', UPLOADS.'users/');
define('IMAGE_PATH_PROFILE_THUMBNAIL', UPLOADS.'users/thumbnail/');
define('IMAGE_PATH_PROFILE_THUMBNAIL_DEFAULT', UPLOADS.'users/thumbnail/default.png');

define('IMAGE_PATH_SETTINGS', UPLOADS.'settings/');



define('DOWNLOAD_LINK_USERS_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/users_template.xlsx');
define('DOWNLOAD_LINK_SUBJECTS_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/subjects_template.xlsx');
define('DOWNLOAD_LINK_CHAPTERS_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/chapters_template.xlsx');
define('DOWNLOAD_LINK_TOPICS_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/topics_template.xlsx');
define('DOWNLOAD_LINK_QUESTION_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/');

define('DOWNLOAD_LINK_ONLINECLASSES_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/onlineclasses_template.xlsx');


define('DOWNLOAD_EMPTY_DATA_DATABASE', PREFIX1.'downloads/database/install.sql');
define('DOWNLOAD_SAMPLE_DATA_DATABASE', PREFIX1.'downloads/database/install_dummy_data.sql');



define('CURRENCY_CODE', '$ ');
define('RECORDS_PER_PAGE', '8');


define('OWNER_ROLE_ID', '1');
define('ADMIN_ROLE_ID', '2');
define('USER_ROLE_ID', '5');
define('STUDENT_ROLE_ID', '5');
define('PARENT_ROLE_ID', '6');
define('INSTITUTE_ROLE_ID', '7');
define('FACULTY_ROLE_ID', '8');


define('GOOGLE_TRANSLATE_LANGUAGES_LINK', 'https://cloud.google.com/translate/docs/languages');

define('PAYMENT_STATUS_CANCELLED', 'cancelled');
define('PAYMENT_STATUS_SUCCESS', 'success');
define('PAYMENT_STATUS_PENDING', 'pending');
define('PAYMENT_STATUS_ABORTED', 'aborted');
define('PAYMENT_RECORD_MAXTIME', '30'); //TIME IN MINUTES
//define('SUPPORTED_GATEWAYS', ['paypal','payu']);

define('URL_INSTALL_SYSTEM', PREFIX.'install');
define('URL_UPDATE_INSTALLATATION_DETAILS', PREFIX.'update-details');
define('URL_FIRST_USER_REGISTER', PREFIX.'install/register');
define('URL_USERS_CONFIRM', PREFIX.'user/confirmation/');

//MASTER SETTINGS MODULE
define('URL_MASTERSETTINGS_SETTINGS', PREFIX.'mastersettings/settings');
define('URL_MASTERSETTINGS_EMAIL_TEMPLATES', PREFIX.'email/templates');
define('URL_MASTERSETTINGS_TOPICS', PREFIX.'mastersettings/topics');
define('URL_MASTERSETTINGS_SUBJECTS', PREFIX.'mastersettings/subjects');

//QUIZ MODULE
define('URL_QUIZZES', PREFIX.'exams/quizzes');
define('URL_QUIZ_QUESTIONBANK', PREFIX.'exams/questionbank');
define('URL_QUIZ_ADD', PREFIX.'exams/quiz/add');
define('URL_QUIZ_EDIT', PREFIX.'exams/quiz/edit');
define('URL_QUIZ_DELETE', PREFIX.'exams/quiz/delete/');
define('URL_QUIZ_GETLIST', PREFIX.'exams/quiz/getList');
define('URL_QUIZ_UPDATE_QUESTIONS', PREFIX.'exams/quiz/update-questions/');
define('URL_QUIZ_GET_QUESTIONS', PREFIX.'exams/quiz/get-questions');

//QUESTIONBANK CATEGORIES
define('URL_QUESTIONBANK_CATEGORIES', PREFIX.'questionbank/categories');
define('URL_QUESTIONBANK_CATEGORY_EDIT', PREFIX.'questionbank/categories/edit');
define('URL_QUESTIONBANK_CATEGORY_ADD', PREFIX.'questionbank/categories/add');
define('URL_QUESTIONBANK_CATEGORY_DELETE', PREFIX.'questionbank/categories/delete/');

//QUIZ CATEGORIES
define('URL_QUIZ_CATEGORIES', PREFIX.'exams/categories');
define('URL_QUIZ_CATEGORY_EDIT', PREFIX.'exams/categories/edit');
define('URL_QUIZ_CATEGORY_ADD', PREFIX.'exams/categories/add');
define('URL_QUIZ_CATEGORY_DELETE', PREFIX.'exams/categories/delete/');

//BOARD CATEGORIES
define('URL_BOARDS', PREFIX.'exams/boards');
define('URL_BOARD_EDIT', PREFIX.'exams/board/edit');
define('URL_BOARD_ADD', PREFIX.'exams/board/add');
define('URL_BOARD_DELETE', PREFIX.'exams/board/delete/');
define('URL_BOARD_GETDATA', PREFIX.'exams/board/getlist');

define('URL_REF_BOOKS', PREFIX.'exams/ref_books');
define('URL_REF_BOOKS_EDIT', PREFIX.'exams/ref_books/edit');
define('URL_REF_BOOKS_ADD', PREFIX.'exams/ref_books/add');
define('URL_REF_BOOKS_DELETE', PREFIX.'exams/ref_books/delete/');
define('URL_REF_BOOKS_GETDATA', PREFIX.'exams/ref_books/getlist');

define('URL_BOARDS_CLASSES', PREFIX.'exams/boards_classes');
define('URL_BOARD_CLASSES_EDIT', PREFIX.'exams/boards_classes/edit');
define('URL_BOARD_CLASSES_ADD', PREFIX.'exams/boards_classes/add');
define('URL_BOARD_CLASSES_DELETE', PREFIX.'exams/boards_classes/delete/');
define('URL_BOARD_CLASSES_GETDATA', PREFIX.'exams/boards_classes/getlist');

define('URL_BOARDS_SUBJECTS', PREFIX.'exams/boards_subjects');
define('URL_BOARD_SUBJECTS_EDIT', PREFIX.'exams/boards_subjects/edit');
define('URL_BOARD_SUBJECTS_ADD', PREFIX.'exams/boards_subjects/add');
define('URL_BOARD_SUBJECTS_DELETE', PREFIX.'exams/boards_subjects/delete/');
define('URL_BOARD_SUBJECTS_GETDATA', PREFIX.'exams/boards_subjects/getlist');

define('URL_BOARDS_CHAPTERS', PREFIX.'exams/boards_chapters');
define('URL_BOARD_CHAPERS_EDIT', PREFIX.'exams/boards_chapters/edit');
define('URL_BOARD_CHAPTERS_ADD', PREFIX.'exams/boards_chapters/add');
define('URL_BOARD_CHAPTERS_DELETE', PREFIX.'exams/boards_chapters/delete/');
define('URL_BOARD_CHAPTERS_GETDATA', PREFIX.'exams/boards_chapters/getlist');

//BOARD CATEGORIES
define('URL_SUBJECTLOGOS', PREFIX.'exams/subjectlogos');
define('URL_SUBJECTLOGOS_EDIT', PREFIX.'exams/subjectlogos/edit');
define('URL_SUBJECTLOGOS_ADD', PREFIX.'exams/subjectlogos/add');
define('URL_SUBJECTLOGOS_DELETE', PREFIX.'exams/subjectlogos/delete/');
define('URL_SUBJECTLOGOS_GETDATA', PREFIX.'exams/subjectlogos/getlist');

//Student Classes
define('URL_INSTITUTE_CLASSES', PREFIX.'institute/classes');
define('URL_INSTITUTE_CLASS_GETDATA', PREFIX.'institutes/student-classes/getlist');
define('URL_INSTITUTE_CLASS_EDIT', PREFIX.'institute/classes/edit');
define('URL_INSTITUTE_CLASS_ADD', PREFIX.'institute/classes/add');
define('URL_INSTITUTE_CLASS_DELETE', PREFIX.'institute/classes/delete/');

//Packages
define('URL_PACKAGES', PREFIX.'packages/list');
define('URL_PACKAGES_GETDATA', PREFIX.'packages/getList');
define('URL_PACKAGES_EDIT', PREFIX.'packages/edit/');
define('URL_PACKAGES_ADD', PREFIX.'packages/add');
define('URL_PACKAGES_DELETE', PREFIX.'packages/delete/');

//Courses
define('URL_INSTITUTE_COURSE', PREFIX.'institute/courses');
define('URL_INSTITUTE_COURSE_GETDATA', PREFIX.'institutes/courses/getlist');
define('URL_INSTITUTE_COURSE_EDIT', PREFIX.'institute/courses/edit');
define('URL_INSTITUTE_COURSE_ADD', PREFIX.'institute/courses/add');
define('URL_INSTITUTE_SET_PERMISSION', PREFIX.'institute/setpermissions/add/');
define('URL_INSTITUTE_COURSE_DELETE', PREFIX.'institute/courses/delete/');

//QUESTIONSBANK MODULE
define('URL_QUESTIONBANK_VIEW', PREFIX.'exams/questionbank/view/');
define('URL_QUESTIONBANK_ADD_QUESTION', PREFIX.'exams/questionbank/add-question/');
define('URL_QUESTIONBANK_EDIT_QUESTION', PREFIX.'exams/questionbank/edit-question/');
define('URL_QUESTIONBANK_EDIT', PREFIX.'exams/questionbank/edit');
define('URL_QUESTIONBANK_ADD', PREFIX.'exams/questionbank/add');
define('URL_QUESTIONBANK_GETLIST', PREFIX.'exams/questionbank/getList');
define('URL_QUESTIONBANK_DELETE', PREFIX.'exams/questionbank/delete/');
define('URL_QUESTIONBANK_GETQUESTION_LIST', PREFIX.'exams/questionbank/getquestionslist/');

define('URL_QUESTIONBAMK_IMPORT', PREFIX.'exams/questionbank/import');

//SUBJECTS MODULE
define('URL_SUBJECTS', PREFIX.'mastersettings/subjects');
define('URL_SUBJECTS_ADD', PREFIX.'mastersettings/subjects/add');
define('URL_SUBJECTS_EDIT', PREFIX.'mastersettings/subjects/edit');
define('URL_SUBJECTS_DELETE', PREFIX.'mastersettings/subjects/delete/');

define('URL_SUBJECTS_IMPORT', PREFIX.'mastersettings/subjects/import');

define('URL_CHAPTERS_IMPORT', PREFIX.'mastersettings/chapters/import');

//TOPICS MODULE
define('URL_TOPICS', PREFIX.'mastersettings/topics');
define('URL_TOPICS_LIST', PREFIX.'mastersettings/topics/list');
define('URL_TOPICS_ADD', PREFIX.'mastersettings/topics/add');
define('URL_TOPICS_EDIT', PREFIX.'mastersettings/topics/edit');
define('URL_TOPICS_DELETE', PREFIX.'mastersettings/topics/delete/');
define('URL_TOPICS_GET_PARENT_TOPICS', PREFIX.'mastersettings/topics/get-parents-topics/');

define('URL_TOPICS_IMPORT', PREFIX.'mastersettings/topics/import');
//EMAIL TEMPLATES MODULE
define('URL_EMAIL_TEMPLATES', PREFIX.'email/templates');
define('URL_EMAIL_TEMPLATES_ADD', PREFIX.'email/templates/add');
define('URL_EMAIL_TEMPLATES_EDIT', PREFIX.'email/templates/edit');
define('URL_EMAIL_TEMPLATES_DELETE', PREFIX.'email/templates/delete/');

//INSTRUCTIONS MODULE
define('URL_INSTRUCTIONS', PREFIX.'exam/instructions/list');
define('URL_INSTRUCTIONS_ADD', PREFIX.'exams/instructions/add');
define('URL_INSTRUCTIONS_EDIT', PREFIX.'exams/instructions/edit/');
define('URL_INSTRUCTIONS_DELETE', PREFIX.'exams/instructions/delete/');
define('URL_INSTRUCTIONS_GETLIST', PREFIX.'exams/instructions/getList');

//LANGUAGES MODULE
define('URL_LANGUAGES_LIST', PREFIX.'languages/list');
define('URL_LANGUAGES_ADD', PREFIX.'languages/add');
define('URL_LANGUAGES_EDIT', PREFIX.'languages/edit');
define('URL_LANGUAGES_UPDATE_STRINGS', PREFIX.'languages/update-strings/');
define('URL_LANGUAGES_DELETE', PREFIX.'languages/delete/');
define('URL_LANGUAGES_GETLIST', PREFIX.'languages/getList/');
define('URL_LANGUAGES_MAKE_DEFAULT', PREFIX.'languages/make-default/');

//SETTINGS MODULE
define('URL_SETTINGS_LIST', PREFIX.'mastersettings/settings');
define('URL_SETTINGS_VIEW', PREFIX.'mastersettings/settings/view/');
define('URL_SETTINGS_ADD', PREFIX.'mastersettings/settings/add');
define('URL_SETTINGS_EDIT', PREFIX.'mastersettings/settings/edit/');
define('URL_SETTINGS_DELETE', PREFIX.'mastersettings/settings/delete/');
define('URL_SETTINGS_GETLIST', PREFIX.'mastersettings/settings/getList/');
define('URL_SETTINGS_ADD_SUBSETTINGS', PREFIX.'mastersettings/settings/add-sub-settings/');




//CONSTANST FOR USERS MODULE
define('URL_USERS', PREFIX.'users-list');
define('URL_USERS_GETLIST', PREFIX.'users/list/getList/');

define('URL_USER_DETAILS', PREFIX.'users/details/');
define('URL_USERS_EDIT', PREFIX.'users/edit/');
define('URL_USERS_ADD', PREFIX.'users/create');
define('URL_USERS_DELETE', PREFIX.'users/delete/');
define('URL_USERS_SETTINGS', PREFIX.'users/settings/');
define('URL_USERS_CHANGE_PASSWORD', PREFIX.'users-change-password/');
define('URL_USERS_LOGOUT', PREFIX.'logout');
define('URL_PARENT_LOGOUT', PREFIX.'parent-logout');
define('URL_USERS_REGISTER', PREFIX.'register');
define('URL_USERS_LOGIN', PREFIX.'login');
define('URL_USERS_UPDATE_PARENT_DETAILS', PREFIX.'users/parent-details/');
define('URL_SEARCH_PARENT_RECORDS', PREFIX.'users/search/parent');

define('URL_USERS_IMPORT', PREFIX.'import-users/import');
define('URL_USERS_IMPORT_REPORT', PREFIX.'users/import-report');

// define('URL_FORGOT_PASSWORD', PREFIX.'users/forgot-password');
define('URL_USERS_FORGOT_PASSWORD', PREFIX.'users/forgot-password');


define('URL_RESET_PASSWORD', PREFIX.'password/reset');



			///////////////////
			//STUDENT MODULE //
			///////////////////

//STUDENT NAVIGATION
define('URL_STUDENT_EXAM_CATEGORIES', PREFIX.'exams/student/categories');
define('URL_STUDENT_EXAM_SUBJECTS', PREFIX.'exams/student/subjects');
define('URL_STUDENT_SUBJECTS', PREFIX.'exams/student/learning-subjects');
define('URL_STUDENT_EXAM_ATTEMPTS', PREFIX.'exams/student/exam-attempts/');
define('URL_STUDENT_ANALYSIS_SUBJECT', PREFIX.'student/analysis/subject/');
define('URL_STUDENT_ANALYSIS_BY_EXAM', PREFIX.'student/analysis/by-exam/');
define('URL_STUDENT_SUBSCRIPTIONS_PLANS', PREFIX.'subscription/plans');
define('URL_STUDENT_LIST_INVOICES', PREFIX.'subscription/list-invoices/');


///////////////////
// STUDENT EXAMS //
///////////////////
define('URL_STUDENT_EXAM_ALL', PREFIX.'exams/student/exams/all');
define('URL_STUDENT_EXAMS', PREFIX.'exams/student/exams/');
define('URL_STUDENT_SUBJECT_EXAMS', PREFIX.'exams/student/subject-exams/');
define('URL_STUDENT_QUIZ_GETLIST', PREFIX.'exams/student/quiz/getList/');

define('URL_STUDENT_QUIZ_PAID', PREFIX.'student-paid/quiz/getList/');

define('URL_STUDENT_QUIZ_GETLIST_ALL', PREFIX.'exams/student/quiz/getList/all');
define('URL_STUDENT_TAKE_EXAM', PREFIX.'exams/student/quiz/take-exam/');
define('URL_STUDENT_EXAM_GETATTEMPTS', PREFIX.'exams/student/get-exam-attempts/');
define('URL_STUDENT_EXAM_ANALYSIS_BYSUBJECT', PREFIX.'student/analysis/by-subject/');
define('URL_STUDENT_EXAM_ANALYSIS_BYEXAM', PREFIX.'student/analysis/get-by-exam/');
define('URL_STUDENT_EXAM_FINISH_EXAM', PREFIX.'exams/student/finish-exam/');


//PARENT NAVIGATION
define('URL_PARENT_CHILDREN', PREFIX.'parent/children');
define('URL_PARENT_CHILDREN_LIST', PREFIX.'parent/children_list');
define('URL_PARENT_CHILDREN_GETLIST', PREFIX.'parent/children/getList/');
define('URL_SUBSCRIBE', PREFIX.'subscription/subscribe/');

define('URL_PARENT_ANALYSIS_FOR_STUDENTS', PREFIX.'children/analysis');


// define('URL_STUDENT_COMPLETED_EXAMS', PREFIX.'atp_user/student/completedexams/');
// define('URL_STUDENT_GET_EXAMS', PREFIX.'atp_user/student/getexamslist/');
define('URL_STUDENT_VIEW_MARKS', PREFIX.'student/view/marks/');

//STUDENT BOOKMARKS
define('URL_BOOKMARKS', PREFIX.'student/bookmarks/');
define('URL_BOOKMARK_ADD', PREFIX.'student/bookmarks/add');
define('URL_BOOKMARK_DELETE', PREFIX.'student/bookmarks/delete/');
define('URL_BOOKMARK_DELETE_BY_ID', PREFIX.'student/bookmarks/delete_id/');
define('URL_BOOKMARK_AJAXLIST', PREFIX.'student/bookmarks/getList/');
define('URL_BOOKMARK_SAVED_BOOKMARKS', PREFIX.'student/bookmarks/getSavedList');


//EXAM SERIES
define('URL_EXAM_SERIES', PREFIX.'exams/exam-series');
define('URL_EXAM_SERIES_ADD', PREFIX.'exams/exam-series/add');
define('URL_EXAM_SERIES_DELETE', PREFIX.'exams/exam-series/delete/');
define('URL_EXAM_SERIES_EDIT', PREFIX.'exams/exam-series/edit/');
define('URL_EXAM_SERIES_AJAXLIST', PREFIX.'exams/exam-series/getList');
define('URL_EXAM_SERIES_UPDATE_SERIES', PREFIX.'exams/exam-series/update-series/');
define('URL_EXAM_SERIES_GET_EXAMS', PREFIX.'exams/exam-series/get-exams');


define('URL_STUDENT_EXAM_SERIES_LIST', PREFIX.'exams/student-exam-series/list');
define('URL_STUDENT_EXAM_SERIES_VIEW_ITEM', PREFIX.'exams/student-exam-series/');

//STUDENT PAID CONTENT
define('URL_PAID_CONTENT', PREFIX.'package/student-paid-content');
define('URL_PAID_CONTENT_ADD', PREFIX.'package/student-paid-content/add');
define('URL_PAID_CONTENT_DELETE', PREFIX.'package/student-paid-content/delete/');
define('URL_PAID_CONTENT_EDIT', PREFIX.'package/student-paid-content/edit/');
define('URL_PAID_CONTENT_AJAXLIST', PREFIX.'package/student-paid-content/getList');




define('URL_PAYMENTS_CHECKOUT', PREFIX.'payments/checkout/');


define('URL_PAYMENTS_LIST', PREFIX.'payments/list/');
define('URL_PAYNOW', PREFIX.'payments/paynow/');
define('URL_PAYPAL_PAYMENT_SUCCESS', PREFIX.'payments/paypal/status-success');
define('URL_PAYPAL_PAYMENT_CANCEL', PREFIX.'payments/paypal/status-cancel');

define('URL_PAYPAL_PAYMENTS_AJAXLIST', PREFIX.'payments/getList/');

define('URL_PAYU_PAYMENT_SUCCESS', PREFIX.'payments/payu/status-success');
define('URL_PAYU_PAYMENT_CANCEL', PREFIX.'payments/payu/status-cancel');
define('URL_UPDATE_OFFLINE_PAYMENT', PREFIX.'payments/offline-payment/update');

//COUPONS MODULE
define('URL_COUPONS', PREFIX.'coupons/list');
define('URL_COUPONS_ADD', PREFIX.'coupons/add');
define('URL_COUPONS_EDIT', PREFIX.'coupons/edit/');
define('URL_COUPONS_DELETE', PREFIX.'coupons/delete/');
define('URL_COUPONS_GETLIST', PREFIX.'coupons/getList');

define('URL_COUPONS_VALIDATE', PREFIX.'coupons/validate-coupon');
define('URL_COUPONS_USAGE', PREFIX.'coupons/get-usage');
define('URL_COUPONS_USAGE_AJAXDATA', PREFIX.'coupons/get-usage-data');



// Online Classes Module
define('URL_ADMIN_ONLINECLASSES', PREFIX.'admin/onlineclasses');
define('URL_ADMIN_ONLINECLASSES_ADD', PREFIX.'admin/onlineclasses/add');
define('URL_ADMIN_ONLINECLASSES_EDIT', PREFIX.'admin/onlineclasses/edit/');
define('URL_ADMIN_ONLINECLASSES_DELETE', PREFIX.'admin/onlineclasses/delete/');
define('URL_ADMIN_ONLINECLASSES_GETLIST', PREFIX.'admin/onlineclasses/getList');
define('URL_ADMIN_ONLINECLASSES_GETATTENDENCELIST', PREFIX.'admin/onlineclasses/getAttendencetList');

//Online Classes Student
define('URL_ONLINECLASSES', PREFIX.'onlineclasses/list');
define('URL_ONLINECLASSES_VIEW', PREFIX.'onlineclasses/show/');

// Notifications Module
define('URL_ADMIN_NOTIFICATIONS', PREFIX.'admin/notifications');
define('URL_ADMIN_NOTIFICATIONS_ADD', PREFIX.'admin/notifications/add');
define('URL_ADMIN_NOTIFICATIONS_EDIT', PREFIX.'admin/notifications/edit/');
define('URL_ADMIN_NOTIFICATIONS_DELETE', PREFIX.'admin/notifications/delete/');
define('URL_ADMIN_NOTIFICATIONS_GETLIST', PREFIX.'admin/notifications/getList');

//Online Classes Student
define('URL_NOTIFICATIONS', PREFIX.'notifications/list');
define('URL_NOTIFICATIONS_VIEW', PREFIX.'notifications/show/');


//LMS MODULE
define('URL_LMS_CATEGORIES', PREFIX.'lms/categories');
define('URL_LMS_CATEGORIES_ADD', PREFIX.'lms/categories/add');
define('URL_LMS_CATEGORIES_EDIT', PREFIX.'lms/categories/edit/');
define('URL_LMS_CATEGORIES_DELETE', PREFIX.'lms/categories/delete/');
define('URL_LMS_CATEGORIES_GETLIST', PREFIX.'lms/categories/getList');

//LMS GROUPS
define('URL_LMS_GROUPS', PREFIX.'lms-groups');
define('URL_LMS_GROUPS_VIEW', PREFIX.'lms-groups/show/');
define('URL_LMS_GROUPS_ADD', PREFIX.'lms/groups/add');
define('URL_LMS_GROUPS_EDIT', PREFIX.'lms/groups/edit/');
define('URL_LMS_GROUPS_DELETE', PREFIX.'lms/groups/delete/');
define('URL_LMS_GROUPS_GETLIST', PREFIX.'lms/groups/getList');
define('URL_LMS_GROUPS_UPDATE_SERIES', PREFIX.'lms/groups/update-series/');

// LMS CONTENT
define('URL_LMS_CONTENT', PREFIX.'lms/content');
define('URL_LMS_CONTENT_ADD', PREFIX.'lms/content/add');
define('URL_LMS_CONTENT_EDIT', PREFIX.'lms/content/edit/');
define('URL_LMS_CONTENT_DELETE', PREFIX.'lms/content/delete/');
define('URL_LMS_CONTENT_GETLIST', PREFIX.'lms/content/getList');
define('URL_LMS_CONTENT_VIEW', PREFIX.'lms/content/');


//LMS SERIES
define('URL_LMS_SERIES', PREFIX.'lms-series');
define('URL_LMS_SERIES_ADD', PREFIX.'lms/series/add');
define('URL_LMS_SERIES_DELETE', PREFIX.'lms/series/delete/');
define('URL_LMS_SERIES_EDIT', PREFIX.'lms/series/edit/');
define('URL_LMS_SERIES_AJAXLIST', PREFIX.'lms/series/getList');
define('URL_LMS_SERIES_UPDATE_SERIES', PREFIX.'lms/series/update-series/');

define('URL_LMS_SERIES_UPDATE_SERIES_EXAMS', PREFIX.'lms/series/update-series-exams/');
define('URL_LMS_SERIES_UPDATE_SERIES_COURSES', PREFIX.'lms/series/update-series-courses/');

define('URL_LMS_SERIES_GET_SERIES', PREFIX.'lms/series/get-series');
define('VALID_IS_PAID_TYPE', PREFIX.'user/paid/');

//LMS Notes
define('URL_LMS_NOTES', PREFIX.'lms/notes');
define('URL_LMS_NOTES_ADD', PREFIX.'lms/notes/add');
define('URL_LMS_NOTES_DELETE', PREFIX.'lms/notes/delete/');
define('URL_LMS_NOTES_EDIT', PREFIX.'lms/notes/edit/');
define('URL_LMS_NOTES_AJAXLIST', PREFIX.'lms/notes/getList');
define('URL_LMS_NOTES_UPDATE_NOTES', PREFIX.'lms/notes/update-series/');
define('URL_LMS_NOTES_UPDATE_NOTES_EXAMS', PREFIX.'lms/notes/update-series-exams/');
define('URL_LMS_NOTES_GET_NOTES', PREFIX.'lms/notes/get-series');


//LMS STUDENT SERIES
define('URL_STUDENT_LMS_CATEGORIES', PREFIX.'learning-management/categories');
define('URL_STUDENT_LMS_CATEGORIES_VIEW', PREFIX.'learning-management/view/');
define('URL_STUDENT_LMS_SERIES', PREFIX.'learning-management/series');
define('URL_STUDENT_LMS_SERIES_VIEW', PREFIX.'learning-management/series/');


//Results Constants
define('URL_RESULTS_VIEW_ANSWERS', PREFIX.'student/exam/answers/');
define('URL_RESULTS_VIEW_SUBJECT_WISE_REPORT', PREFIX.'student/exam/answers/subject_report/');

 define('URL_COMPARE_WITH_TOPER', PREFIX.'toppers/compare-with-topper/');

// FEEDBACK SYSTEM
define('URL_FEEDBACK_SEND', PREFIX.'feedback/send');
define('URL_FEEDBACKS', PREFIX.'feedback/list');
define('URL_FEEDBACK_VIEW', PREFIX.'feedback/view-details/');
define('URL_FEEDBACK_DELETE', PREFIX.'feedback/delete/');
define('URL_FEEDBACKS_GETLIST', PREFIX.'feedback/getlist');

//MESSAGES
define('URL_MESSAGES', PREFIX.'messages');
define('URL_MESSAGES_SHOW', PREFIX.'messages/');
define('URL_MESSAGES_CREATE', PREFIX.'messages/create');


define('URL_GENERATE_CERTIFICATE', PREFIX.'result/generate-certificate/');


define('URL_PAYMENT_REPORTS', PREFIX.'payments-report/');
define('URL_ONLINE_PAYMENT_REPORTS', PREFIX.'payments-report/online');
define('URL_ONLINE_PAYMENT_REPORT_DETAILS', PREFIX.'payments-report/online/');
define('URL_ONLINE_PAYMENT_REPORT_DETAILS_AJAX', PREFIX.'payments-report/online/getList/');
define('URL_OFFLINE_PAYMENT_REPORTS', PREFIX.'payments-report/offline');
define('URL_OFFLINE_PAYMENT_REPORT_DETAILS', PREFIX.'payments-report/offline/');
define('URL_OFFLINE_PAYMENT_REPORT_DETAILS_AJAX', PREFIX.'payments-report/offline/getList/');

define('URL_PAYMENT_REPORT_EXPORT', PREFIX.'payments-report/export');
define('URL_GET_PAYMENT_RECORD', PREFIX.'payments-report/getRecord');
define('URL_PAYMENT_APPROVE_OFFLINE_PAYMENT', PREFIX.'payments/approve-reject-offline-request');


define('URL_SEND_SMS', PREFIX.'sms/index');
define('URL_SEND_SMS_NOW', PREFIX.'sms/send');

define('URL_FACEBOOK_LOGIN', PREFIX.'auth/facebook');
define('URL_GOOGLE_LOGIN', PREFIX.'auth/google');

//Site Pages
define('SITE_PAGES_PRIVACY', PREFIX.'site/privacy-policy');
define('SITE_PAGES_TERMS', PREFIX.'site/terms-conditions');
define('SITE_PAGES_ABOUT_US', PREFIX.'site/about-us');
define('SITE_PAGES_CONTACT_US', PREFIX.'site/contact-us');
define('URL_VIEW_SITE_COURSES', PREFIX.'site/courses');
define('URL_VIEW_SITE_PATTREN', PREFIX.'site/pattren');
define('URL_VIEW_SITE_PRICING', PREFIX.'site/pricing');
define('URL_VIEW_SITE_SYALLABUS', PREFIX.'site/syllabus');


// Front End Part
define('URL_FRONTEND_EXAMS_LIST', PREFIX.'exams/list');
define('URL_FRONTEND_START_EXAM', PREFIX. 'exams/start-exam/');
define('URL_FRONTEND_FINISH_EXAM', PREFIX. 'exams/finish-exam/');

define('IMAGE_PATH_EXAMS', UPLOADS.'exams/categories/');
define('IMAGE_PATH_EXAMS_DEFAULT', UPLOADS.'exams/categories/default.png');

//Resume Exam
define('URL_SAVE_RESUME_EXAM_DATA', PREFIX.'resume/examdata/save');


//Update DataBase
define('URL_UPDATE_DATABASE', PREFIX.'update/application');
define('URL_EXAM_TYPES', PREFIX.'exam-types');
define('URL_EDIT_EXAM_TYPE', PREFIX.'edit/exam-type/');
define('URL_UPDATE_EXAM_TYPE', PREFIX.'update/exam-type/');
define('URL_RAZORPAY_SUCCESS', PREFIX.'razoapay/success');

define('URL_SAVE_SUBSCRIPTION_EMAIL', PREFIX.'subscription/email');


//Subscribed users
define('URL_SUBSCRIBED_USERS', PREFIX.'subscribed/users');
define('URL_SUBSCRIBED_USERS_DATA', PREFIX.'subscribed/users/data');

//All exam categories
define('URL_VIEW_ALL_EXAM_CATEGORIES', PREFIX.'exam/categories');
define('URL_VIEW_ALL_PRACTICE_EXAMS', PREFIX.'practice-exams');
define('URL_VIEW_ALL_LMS_CATEGORIES', PREFIX.'LMS/all-categories');
define('URL_VIEW_LMS_CONTENTS', PREFIX.'LMS/contents/');
define('URL_DOWNLOAD_LMS_CONTENT', PREFIX.'download/lms/contents/');
define('URL_LMS_VIDEO_CONTENT', PREFIX.'lms/video/');
define('URL_SITE_CONTACTUS', PREFIX.'contact-us');
define('URL_SEND_CONTACTUS', PREFIX.'send/contact-us/details');
define('URL_GET_FRONT_END_SERIES_CONTENTS', PREFIX.'get/series/contents');

//Themes
define('URL_THEMES_LIST', PREFIX.'themes/list');
define('URL_THEMES_GET_DATA', PREFIX.'themes/data');
define('URL_THEME_MAKE_DEFAULT', PREFIX.'make/default/theme/');
define('URL_VIEW_THEME_SETTINGS', PREFIX.'theme/settings/');
define('URL_UPDATE_THEME_SUBSETTINGS', PREFIX.'theme/update/settings/');

//Institute
define('URL_INSTITUTE_REGISTER', PREFIX.'institute/registration');
define('URL_ADD_INSTITUTE_REGISTER', PREFIX.'add/institute');
define('URL_VIEW_INSTITUES', PREFIX.'institutes');
define('URL_INSTITUTES_GETDATATABLE', PREFIX.'institutes_all/getlist');
define('URL_INSTITUTE_DETAILS', PREFIX.'instutite/details/');
define('URL_UPDATE_INSTITUTE_STATUS', PREFIX.'change/institute-status');
define('APPROVE', '1');
define('REJECT', '2');
define('BLOCK', '3');
define('UNBLOCK', '4');
define('URL_EDIT_INSTITUTE_DETAILS', PREFIX.'edit/institute/');

define('URL_USERS_DASHBOARD', PREFIX.'dashboard');

//Institute Associates
define('URL_VIEW_ASSOSIATES_LIST', PREFIX.'associate/institute/');
define('URL_ADD_ASSOCIATES', PREFIX.'add/associate');

//Sub Institutes
define('URL_INSTITUTE_BRANCH', PREFIX.'institutes/branches');
define('URL_INSTITUTE_BRANCH_ADD', PREFIX.'institutes/branches/add');
define('URL_INSTITUTE_BRANCH_EDIT', PREFIX.'institutes/branches/edit/');
define('URL_INSTITUTE_BRANCH_DELETE', PREFIX.'institutes/branches/delete/');
define('URL_INSTITUTE_BRANCH_GETDATA', PREFIX.'institutes/branches/getlist');
define('IMAGE_PATH_UPLOAD_BRANCH_DEFAULT', UPLOADS.'institues/branches/default.png');
define('IMAGE_PATH_UPLOAD_BRANCH', UPLOADS.'institues/branches/');

//Permissions
define('URL_PERMISSIONS', PREFIX.'permissions');
define('URL_PERMISSIONS_ADD', PREFIX.'permissions/add');
define('URL_PERMISSIONS_EDIT', PREFIX.'permissions/edit');
define('URL_PERMISSIONS_DELETE', PREFIX.'permissions/delete/');
define('URL_PERMISSIONS_GETDATA', PREFIX.'permissions/getlist');

//Competitive Exams Types

define('URL_COMPETITIVE_EXAM_TYPES', PREFIX.'competitive-exam-types');
define('URL_COMPETITIVE_EXAM_TYPES_ADD', PREFIX.'competitive-exam-types/add');
define('URL_COMPETITIVE_EXAM_TYPES_EDIT', PREFIX.'competitive-exam-types/edit');
define('URL_COMPETITIVE_EXAM_TYPES_DELETE', PREFIX.'competitive-exam-types/delete/');
define('URL_COMPETITIVE_EXAM_TYPES_GETDATA', PREFIX.'competitive-exam-types/getlist');

//Question Bank Types

define('URL_QUESTION_BANK_TYPES', PREFIX.'question-bank-types');
define('URL_QUESTION_BANK_TYPES_ADD', PREFIX.'question-bank-types/add');
define('URL_QUESTION_BANK_TYPES_EDIT', PREFIX.'question-bank-types/edit');
define('URL_QUESTION_BANK_TYPES_DELETE', PREFIX.'question-bank-types/delete/');
define('URL_QUESTION_BANK_TYPES_GETDATA', PREFIX.'question-bank-types/getlist');

//Question Bank Types
define('URL_QUESTION_BANK_MANAGEMENT', PREFIX.'exams/questionbank-management');

define('URL_TOPICS_DIRECTORY', PREFIX.'exams/topics-directory');


//BATCHS MODULE
define('URL_BATCHS', PREFIX.'batches/list');
define('URL_BATCHS_ADD', PREFIX.'batches/add');
define('URL_BATCHS_EDIT', PREFIX.'batches/edit/');
define('URL_BATCHS_DELETE', PREFIX.'batches/delete/');
define('URL_BATCHS_GETLIST', PREFIX.'batches/getList');

define('URL_BATCHS_ADD_STUDENTS', PREFIX.'batches/addstudents/');
define('URL_GET_STUDENTS', PREFIX.'batches/getstudents');
define('URL_STORE_STUDENT_TO_BATCH', PREFIX.'batches/store/students');
define('URL_VIEW_BATCH_STUDENTS', PREFIX.'batches/students/');
define('URL_UPDATE_STUDENT_TO_BATCH', PREFIX.'update/batches/students');
define('URL_AVAILABLE_BATCH_STUDENTS', PREFIX.'available/batch/students');

define('URL_BATCHS_ADD_LMS', PREFIX.'batches/addlms/');
define('URL_GET_LMS', PREFIX.'batches/getlms');
define('URL_STORE_LMS_TO_BATCH', PREFIX.'batches/store/lms');

define('URL_BATCHS_ADD_LMSNOTES', PREFIX.'batches/addlmsnotes/');
define('URL_GET_LMSNOTES', PREFIX.'batches/getlmsnotes');

//Fee Module
define('IMAGE_PATH_PROFILE_DEFAULT', UPLOADS.'users/default.png');

define('URL_PAY_FEE', PREFIX.'pay/fee');
define('URL_STUDENT_FEE_PAY_ADD', PREFIX.'add/fee');
define('URL_GET_FEE_CATEGORY_STUDENTS', PREFIX.'fee/batch/students');
define('URL_BATCH_STUDNET_DETAILS', PREFIX.'batch/students-details');
define('URL_ADD_DISCOUNT_TO_STUDENT', PREFIX.'batch/add-discount');

// Fee Reports
define('URL_GET_FEE_REPORTS_BATCH_WISE', PREFIX.'fee-reports/batch');
define('URL_GET_FEE_PAID_STUDENTS_BATCH', PREFIX.'batch/fee-reports');
define('URL_PRINT_BATCH_FEE_REPORTS', PREFIX.'print/fee-reports');
define('URL_GET_FEE_REPORTS_INSTITUTE_WISE', PREFIX.'institute/fee-reports');
define('URL_GET_INSTITUTE_BATCHES', PREFIX.'get/institute/batches');
define('URL_GET_FEE_DATE_WISE_REPORTS', PREFIX.'fee-paid-reports');
define('URL_GET_DAILY_FEE_REPORTS', PREFIX.'get/daily-reports');

//Front Batches Details
define('URL_FRONTEND_BATCHES_DETAILS', PREFIX.'batches/details/');
define('EXPIRE_NOTIFICATION_BEFORE', 10);
define('DEFAULT_VALIDITY_DAYS', 20);
define('TIMER_ENABLE_BEFORE', 5); // In minutes
define('OWNER_INSTITUTE_ID', 1);
define('OWNER_USER_ID', 1);
define('PAYMENT_ALERT_BEFORE', 5);

define('OTP_LENGTH', 4);
define('OTP_MAX_USAGE', 5000);

// Question Bank Type.
define('QUESTIONSBANK_TYPE_LMSQUIZ', '1');
define('QUESTIONSBANK_TYPE_EXAM', '2');
define('QUESTIONSBANK_TYPE_TESTSERIES', '3');

define('QUESTIONSBANK_TYPE_PREVIOUSYEAR_JEE', '6');
define('QUESTIONSBANK_TYPE_PREVIOUSYEAR_NEET', '7');

// QuizCategory.
define('QUIZTYPE_SUBJECT', '1');
define('QUIZTYPE_LIVEQUIZ', '2');
define('QUIZTYPE_LMS', '3');

define('MASTER_INSTITUTE_ID', '1');