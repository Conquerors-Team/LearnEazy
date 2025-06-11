<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\InstallatationController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NativeController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\ChaptersController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\OnlineclassesController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\QuestionBankCategoryController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\ReferenceBooksController;
use App\Http\Controllers\BoardsClassesController;
use App\Http\Controllers\BoardsSubjectsController;
use App\Http\Controllers\BoardsChaptersController;
use App\Http\Controllers\SubjectLogosController;
use App\Http\Controllers\QuizCategoryController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\CompetitiveExamTypesController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CertificatesController;
use App\Http\Controllers\StudentPaidContentController;
use App\Http\Controllers\ExamSeriesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\InstructionsController;
use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ExamToppersController;
use App\Http\Controllers\LmsCategoryController;
use App\Http\Controllers\QuestionBankTypesController;
use App\Http\Controllers\LmsGroupController;
use App\Http\Controllers\LmsContentController;
use App\Http\Controllers\LmsSeriesController;
use App\Http\Controllers\LmsNotesController;
use App\Http\Controllers\StudentLmsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EmailTemplatesController;
use App\Http\Controllers\CouponcodesController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SMSAgentController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\FrontendExamsController;


use App\Http\Controllers\SiteThemesController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\InstituteAssosiatesController;
use App\Http\Controllers\InstituteBranchController;
use App\Http\Controllers\BatchesController;
use App\Http\Controllers\FeePayController;
use App\Http\Controllers\FeaturesUpdatesController;
use App\Http\Controllers\PackagesController;





// Installation Routes
if (env('DB_DATABASE') == '') {
    Route::get('/', [InstallatationController::class, 'index']);
    Route::get('/install', [InstallatationController::class, 'index']);
    Route::post('/update-details', [InstallatationController::class, 'updateDetails']);
    Route::post('/install', [InstallatationController::class, 'installProject']);
    Route::post('install/register', [InstallatationController::class, 'registerUser']);
}

// Home Route
Route::get('/', function () {
    return Auth::check() ? redirect('dashboard') : redirect()->route('site.home');
});

// Demo Mode Event Listener
if (env('DEMO_MODE')) {
    Event::listen('eloquent.saving: *', function ($model) {
        return urlHasString('finish-exam') || urlHasString('start-exam');
    });
}

if(env('DB_DATABASE')==''){
  Route::get('/', [SiteController::class, 'index']);
}

// Site Routes
Route::get('home', [SiteController::class, 'index'])->name('site.home');

// Dashboard Routes
Route::get('dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
Route::get('dashboard/testlang', [DashboardController::class, 'testLanguage']);

// Social Authentication
Route::get('auth/{slug}', [LoginController::class, 'redirectToProvider']);
Route::get('auth/{slug}/callback', [LoginController::class, 'handleProviderCallback']);


Route::get('about', [SiteController::class, 'about'])->name('site.about');
Route::get('courses/{course_slug?}', [SiteController::class, 'courses'])->name('site.courses');
Route::get('blog/{slug?}', [SiteController::class, 'blog'])->name('site.blog');
Route::get('pricing', [SiteController::class, 'pricing'])->name('site.pricing');
Route::get('pricing/student-packages/{course_id?}/{slug_type?}', [SiteController::class, 'getStudentPackages'])->name('pricing.student-packages');
Route::get('practice', [SiteController::class, 'practice'])->name('site.practice');
Route::match(['get', 'post'], 'contact', [SiteController::class, 'contact'])->name('site.contact');
Route::get('institute', [SiteController::class, 'institute'])->name('site.institute');
Route::get('board/{board_id}/{class?}/{subject?}', [SiteController::class, 'board'])->name('site.board');
Route::get('/media/file-download/{model}/{field}/{record_id}/{namespace?}', [SiteController::class, 'mediaFileDownload'])->name('site.media-file-download');
Route::get('ref-books', [SiteController::class, 'refBooks'])->name('site.ref_books');

// Authentication Routes
Route::get('login/{layout_type?}', [LoginController::class, 'getLogin'])->name('user.login');
Route::post('login', [LoginController::class, 'postLogin']);
Route::get('login-otp/{phone?}', [LoginController::class, 'getLoginOtp'])->name('login.otp');
Route::post('login-otp', [LoginController::class, 'postLoginOtp'])->name('login.post_otp');
Route::get('reset-session/{return?}', [LoginController::class, 'resetOtpSession'])->name('login.reset_otp_session');
Route::get('logout', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $user->is_loggedin = 'no';
        $user->save();
        flash(getPhrase('success'), getPhrase('logged_out_successfully'), 'success');
    }
    Auth::logout();
    Session::flush();
    return redirect()->route('site.home');
});
Route::get('parent-logout', function () {
    if (Auth::check()) {
        flash('Oops..!', getPhrase('parents_module_is_not_available'), 'error');
    }
    Auth::logout();
    return redirect()->route('user.login');
});
Route::get('register/{role?}', [RegisterController::class, 'getRegister'])->name('user.register');
Route::post('register', [RegisterController::class, 'postRegister']);
Route::get('user/confirmation/{slug}', [LoginController::class, 'confirmUser']);
Route::match(['get', 'post'], 'register-otp', [RegisterController::class, 'getOtpRegister'])->name('user-otp.register');
Route::get('password/reset/{slug?}', [PasswordController::class, 'getReset']);
Route::post('password/reset', [PasswordController::class, 'postReset']);
Route::post('users/forgot-password', [LoginController::class, 'resetUsersPassword']);





// Language Routes
Route::get('languages/list', [NativeController::class, 'index']);
Route::get('languages/getList', [NativeController::class, 'getDatatable'])->name('languages.dataTable');
Route::get('languages/add', [NativeController::class, 'create']);
Route::post('languages/add', [NativeController::class, 'store']);
Route::get('languages/edit/{slug}', [NativeController::class, 'edit']);
Route::patch('languages/edit/{slug}', [NativeController::class, 'update']);
Route::delete('languages/delete/{slug}', [NativeController::class, 'delete']);
Route::get('languages/make-default/{slug}', [NativeController::class, 'changeDefaultLanguage']);
Route::get('languages/update-strings/{slug}', [NativeController::class, 'updateLanguageStrings']);
Route::patch('languages/update-strings/{slug}', [NativeController::class, 'saveLanguageStrings']);

// User Routes
Route::get('users/staff/{role?}', [UsersController::class, 'index']);
Route::get('users/create', [UsersController::class, 'create']);
Route::delete('users/delete/{slug}', [UsersController::class, 'delete']);
Route::post('users/create/{role?}', [UsersController::class, 'store']);
Route::get('users/edit/{slug}', [UsersController::class, 'edit']);
Route::patch('users/edit/{slug}', [UsersController::class, 'update']);
Route::get('users/profile/{slug}', [UsersController::class, 'show']);

Route::get('users-list/{type?}/{type_id?}', [UsersController::class, 'index']);

Route::get('users/profile/{slug}', [UsersController::class, 'show']);
Route::get('users/details/{slug}', [UsersController::class, 'details']);

Route::get('users/settings/{slug}', [UsersController::class, 'settings']);
Route::patch('users/settings/{slug}', [UsersController::class, 'updateSettings']);

Route::get('users-change-password/{slug}', [UsersController::class, 'changePassword']);
Route::patch('users-change-password/{slug}', [UsersController::class, 'updatePassword']);
// Route::get('users-change-password/{slug}', [UsersController::class, 'changePassword']);
// Route::patch('users-change-password/{slug}', [UsersController::class, 'updatePassword']);

Route::get('import-users/import', [UsersController::class, 'importUsers']);
Route::post('import-users/import', [UsersController::class, 'readExcel']);

Route::get('users/import-report', [UsersController::class, 'importResult']);
Route::get('users/list/getList/{type?}/{type_id?}', [UsersController::class, 'getDatatable'])->name('users.dataTable');
Route::get('users/parent-details/{slug}', [UsersController::class, 'viewParentDetails']);
Route::patch('users/parent-details/{slug}', [UsersController::class, 'updateParentDetails']);
Route::post('users/search/parent', [UsersController::class, 'getParentsOnSearch']);
Route::match(['get', 'post'], 'users-permission/set-permissions/{user_slug}', [UsersController::class, 'setPermissions'])->name('users.set_permissions');

// Parent Routes
Route::get('parent/children', [ParentsController::class, 'index']);
Route::get('parent/children/list', [ParentsController::class, 'index']);
Route::get('parent/children/getList/{slug}', [ParentsController::class, 'getDatatable']);
Route::get('children/analysis', [ParentsController::class, 'childrenAnalysis']);

// Subject Routes
Route::get('mastersettings/subjects', [SubjectsController::class, 'index'])->name('mastersettings.subjects');
Route::get('mastersettings/subjects/add', [SubjectsController::class, 'create']);
Route::post('mastersettings/subjects/add', [SubjectsController::class, 'store']);
Route::get('mastersettings/subjects/edit/{slug}', [SubjectsController::class, 'edit']);
Route::patch('mastersettings/subjects/edit/{slug}', [SubjectsController::class, 'update']);
Route::delete('mastersettings/subjects/delete/{id}', [SubjectsController::class, 'delete']);
Route::get('mastersettings/subjects/getList', [SubjectsController::class, 'getDatatable'])->name('subjects.dataTable');
Route::get('mastersettings/subjects/import', [SubjectsController::class, 'import']);
Route::post('mastersettings/subjects/import', [SubjectsController::class, 'readExcel']);


// ==================
// Chapters Routes
// ==================
Route::prefix('mastersettings/chapters')->group(function() {
    Route::get('/', [ChaptersController::class, 'index'])->name('mastersettings.chapters_index');
    Route::get('add', [ChaptersController::class, 'create'])->name('mastersettings.chapters_create');
    Route::post('add', [ChaptersController::class, 'store'])->name('mastersettings.chapters_store');
    Route::get('edit/{slug}', [ChaptersController::class, 'edit'])->name('mastersettings.chapters_edit');
    Route::patch('edit/{slug}', [ChaptersController::class, 'update'])->name('mastersettings.chapters_update');
    Route::delete('delete/{id}', [ChaptersController::class, 'delete'])->name('mastersettings.chapters_delete');
    Route::get('getList', [ChaptersController::class, 'getDatatable'])->name('chapters.dataTable');

    Route::get('import', [ChaptersController::class, 'import'])->name('mastersettings.chapters_import');
    Route::post('import', [ChaptersController::class, 'readExcel'])->name('mastersettings.chapters_import_read');
});

// ==================
// Topics Routes
// ==================
Route::prefix('mastersettings/topics')->group(function() {
    Route::get('/', [TopicsController::class, 'index'])->name('mastersettings.topics');
    Route::get('add', [TopicsController::class, 'create']);
    Route::post('add', [TopicsController::class, 'store']);
    Route::get('edit/{slug}', [TopicsController::class, 'edit']);
    Route::patch('edit/{slug}', [TopicsController::class, 'update']);
    Route::delete('delete/{id}', [TopicsController::class, 'delete']);
    Route::get('getList', [TopicsController::class, 'getDatatable'])->name('topics.dataTable');

    Route::get('get-parents-topics/{subject_id}/{chapter_id}', [TopicsController::class, 'getChapterTopics']);
    Route::get('get-parents-topics-exam/{subject_id}/{chapter_id}', [TopicsController::class, 'getChapterTopicsExam']);
    Route::get('get-parents-chapters/{subject_id}', [TopicsController::class, 'getSubjectChapters'])->name('mastersettings.chapters_parent_chapters');

    Route::get('import', [TopicsController::class, 'import']);
    Route::post('import', [TopicsController::class, 'readExcel']);
});

// ==================
// Online Classes Routes
// ==================
Route::prefix('mastersettings/class')->group(function() {
    Route::get('get-batch/{class_id}', [OnlineclassesController::class, 'getBatches']);
    Route::get('get-user/{user_id}', [OnlineclassesController::class, 'getUser']);
    Route::get('get-faculty/{batch_id}', [OnlineclassesController::class, 'getFacultyBatches']);
    Route::get('get-faculty-subjects/{faculty_id}', [OnlineclassesController::class, 'getFacultySubjects']);
});

Route::get('onlineclasses/classend/alerts', [OnlineclassesController::class, 'classEndAlerts'])->name('onlineclasses.classend_alerts');

// ==================
// Exam System - Question Bank Routes
// ==================
Route::prefix('exams/questionbank')->group(function() {
    Route::get('/', [QuestionBankController::class, 'index'])->name('exams.questionbank');
    Route::get('add-question/{slug}', [QuestionBankController::class, 'create'])->name('exams.addquestion');
    Route::post('add/{slug?}', [QuestionBankController::class, 'store']);
    Route::get('view/{slug}', [QuestionBankController::class, 'show']);
    Route::get('edit-question/{slug}', [QuestionBankController::class, 'edit'])->name('exams.editquestion');
    Route::patch('edit/{slug}', [QuestionBankController::class, 'update']);
    Route::delete('delete/{id}', [QuestionBankController::class, 'delete']);
    Route::get('getList', [QuestionBankController::class, 'getDatatable'])->name('exams.questionbank.getList');

    Route::get('view-question/{subject_slug}/{slug}', [QuestionBankController::class, 'view'])->name('exams.questionbank.view-question');
    Route::get('getquestionslist/{slug}', [QuestionBankController::class, 'getQuestions'])->name('exams_questionbank.getquestionslist');

    Route::get('import', [QuestionBankController::class, 'import']);
    Route::post('import', [QuestionBankController::class, 'readExcel']);
});

// ==================
// Question Bank Categories Routes
// ==================
Route::prefix('questionbank/categories')->group(function() {
    Route::get('/', [QuestionBankCategoryController::class, 'index']);
    Route::get('add', [QuestionBankCategoryController::class, 'create']);
    Route::post('add', [QuestionBankCategoryController::class, 'store']);
    Route::get('edit/{slug}', [QuestionBankCategoryController::class, 'edit']);
    Route::patch('edit/{slug}', [QuestionBankCategoryController::class, 'update']);
    Route::delete('delete/{slug}', [QuestionBankCategoryController::class, 'delete']);
    Route::get('getList', [QuestionBankCategoryController::class, 'getDatatable'])->name('questionbankcategories.dataTable');
});

// ==================
// Boards Routes
// ==================
Route::prefix('exams')->group(function () {
    Route::get('boards', [BoardsController::class, 'index']);
    Route::get('board/add', [BoardsController::class, 'create']);
    Route::post('board/add', [BoardsController::class, 'store']);
    Route::get('board/edit/{slug}', [BoardsController::class, 'edit']);
    Route::patch('board/edit/{slug}', [BoardsController::class, 'update']);
    Route::delete('board/delete/{slug}', [BoardsController::class, 'delete']);

    Route::get('board/getList', [BoardsController::class, 'getDatatable'])->name('boards.dataTable');
});

// ==================
// Reference Books Routes
// ==================
Route::prefix('exams/ref_books')->group(function() {
    Route::get('/', [ReferenceBooksController::class, 'index']);
    Route::get('add', [ReferenceBooksController::class, 'create']);
    Route::post('add', [ReferenceBooksController::class, 'store']);
    Route::get('edit/{slug}', [ReferenceBooksController::class, 'edit']);
    Route::patch('edit/{slug}', [ReferenceBooksController::class, 'update']);
    Route::delete('delete/{slug}', [ReferenceBooksController::class, 'delete']);
    Route::get('getList', [ReferenceBooksController::class, 'getDatatable'])->name('ref_books.dataTable');
});

// ==================
// Boards Classes Routes
// ==================
Route::prefix('exams/boards_classes')->group(function() {
    Route::get('/', [BoardsClassesController::class, 'index']);
    Route::get('add', [BoardsClassesController::class, 'create']);
    Route::post('add', [BoardsClassesController::class, 'store']);
    Route::get('edit/{slug}', [BoardsClassesController::class, 'edit']);
    Route::patch('edit/{slug}', [BoardsClassesController::class, 'update']);
    Route::delete('delete/{slug}', [BoardsClassesController::class, 'delete']);
    Route::get('getList', [BoardsClassesController::class, 'getDatatable'])->name('boards_classes.dataTable');
});

// ==================
// Boards Subjects Routes
// ==================
Route::prefix('exams/boards_subjects')->group(function() {
    Route::get('/', [BoardsSubjectsController::class, 'index']);
    Route::get('add', [BoardsSubjectsController::class, 'create']);
    Route::post('add', [BoardsSubjectsController::class, 'store']);
    Route::get('edit/{slug}', [BoardsSubjectsController::class, 'edit']);
    Route::patch('edit/{slug}', [BoardsSubjectsController::class, 'update']);
    Route::delete('delete/{slug}', [BoardsSubjectsController::class, 'delete']);
    Route::get('getList', [BoardsSubjectsController::class, 'getDatatable'])->name('boards_subjects.dataTable');
});

// ==================
// Boards Chapters Routes
// ==================
Route::prefix('exams/boards_chapters')->group(function() {
    Route::get('/', [BoardsChaptersController::class, 'index']);
    Route::get('add', [BoardsChaptersController::class, 'create']);
    Route::post('add', [BoardsChaptersController::class, 'store']);
    Route::get('edit/{slug}', [BoardsChaptersController::class, 'edit']);
    Route::patch('edit/{slug}', [BoardsChaptersController::class, 'update']);
    Route::delete('delete/{slug}', [BoardsChaptersController::class, 'delete']);
    Route::get('getList', [BoardsChaptersController::class, 'getDatatable'])->name('boards_chapters.dataTable');
});

// ==================
// Subject Logos Routes
// ==================
Route::prefix('exams/subject_logos')->group(function() {
    Route::get('/', [SubjectLogosController::class, 'index']);
    Route::get('add', [SubjectLogosController::class, 'create']);
    Route::post('add', [SubjectLogosController::class, 'store']);
    Route::get('edit/{slug}', [SubjectLogosController::class, 'edit']);
    Route::patch('edit/{slug}', [SubjectLogosController::class, 'update']);
    Route::delete('delete/{slug}', [SubjectLogosController::class, 'delete']);
    Route::get('getList', [SubjectLogosController::class, 'getDatatable'])->name('subject_logos.dataTable');
});

// ==================
// Quiz Category Routes
// ==================
Route::prefix('exams/quiz_category')->group(function() {
    Route::get('/', [QuizCategoryController::class, 'index']);
    Route::get('add', [QuizCategoryController::class, 'create']);
    Route::post('add', [QuizCategoryController::class, 'store']);
    Route::get('edit/{slug}', [QuizCategoryController::class, 'edit']);
    Route::patch('edit/{slug}', [QuizCategoryController::class, 'update']);
    Route::delete('delete/{slug}', [QuizCategoryController::class, 'delete']);
    Route::get('getList', [QuizCategoryController::class, 'getDatatable'])->name('quiz_category.dataTable');
});

// ==================
// Student Class Routes
// ==================
Route::prefix('student/studentclass')->group(function() {
    Route::get('/', [StudentClassController::class, 'index']);
    Route::get('add', [StudentClassController::class, 'create']);
    Route::post('add', [StudentClassController::class, 'store']);
    Route::get('edit/{slug}', [StudentClassController::class, 'edit']);
    Route::patch('edit/{slug}', [StudentClassController::class, 'update']);
    Route::delete('delete/{slug}', [StudentClassController::class, 'delete']);
    Route::get('getList', [StudentClassController::class, 'getDatatable'])->name('studentclass.dataTable');
});

// ==================
// Course Routes
// ==================
Route::prefix('courses')->group(function() {
    Route::get('/', [CourseController::class, 'index']);
    Route::get('add', [CourseController::class, 'create']);
    Route::post('add', [CourseController::class, 'store']);
    Route::get('edit/{slug}', [CourseController::class, 'edit']);
    Route::patch('edit/{slug}', [CourseController::class, 'update']);
    Route::delete('delete/{slug}', [CourseController::class, 'delete']);
    Route::get('getList', [CourseController::class, 'getDatatable'])->name('courses.dataTable');
});

// ==================
// Permissions Routes
// ==================
Route::prefix('permissions')->group(function() {
    Route::get('/', [PermissionsController::class, 'index']);
    Route::get('add', [PermissionsController::class, 'create']);
    Route::post('add', [PermissionsController::class, 'store']);
    Route::get('edit/{slug}', [PermissionsController::class, 'edit']);
    Route::patch('edit/{slug}', [PermissionsController::class, 'update']);
    Route::delete('delete/{slug}', [PermissionsController::class, 'delete']);
    Route::get('/getlist', [PermissionsController::class, 'getDatatable'])->name('permissions.dataTable');
});

// ==================
// Competitive Exam Types Routes
// ==================S
Route::prefix('competitive_exam_types')->group(function() {
    Route::get('/', [CompetitiveExamTypesController::class, 'index']);
    Route::get('add', [CompetitiveExamTypesController::class, 'create']);
    Route::post('add', [CompetitiveExamTypesController::class, 'store']);
    Route::get('edit/{slug}', [CompetitiveExamTypesController::class, 'edit']);
    Route::patch('edit/{slug}', [CompetitiveExamTypesController::class, 'update']);
    Route::delete('delete/{slug}', [CompetitiveExamTypesController::class, 'delete']);
    Route::get('getList', [CompetitiveExamTypesController::class, 'getDatatable'])->name('competitive_exam_types.dataTable');
});

// Quiz Student Categories
Route::get('exams/student/categories', [StudentQuizController::class, 'index']);
Route::get('exams/student/subjects', [StudentQuizController::class, 'subjects']);

Route::get('exams/student/learning-subjects', [StudentQuizController::class, 'learningSubjects']);
Route::get('exams/student/subject-details/{slug}', [StudentQuizController::class, 'learningSubjects']);

Route::get('exams/student/exams/{slug?}/{type?}', [StudentQuizController::class, 'exams']);
Route::get('exams/student/quiz/getList/{slug?}/{type?}', [StudentQuizController::class, 'getDatatable']);

Route::get('multiple-student/exams', [StudentQuizController::class, 'multipleSubjectExams'])->name('multisubject.exams');
Route::get('multiple-student/exams-list', [StudentQuizController::class, 'multipleSubjectExamsGetList'])->name('student.multisubject.examsgetlist');

Route::get('exams/student/quiz/take-exam/{slug?}/{lms_slug?}', [StudentQuizController::class, 'instructions']);
Route::post('exams/student/start-exam/{slug}/{lms_slug?}', [StudentQuizController::class, 'startExam']);
Route::get('exams/student/start-exam/{slug}/{lms_slug?}', [StudentQuizController::class, 'index']);
Route::get('exams/student/subject-exams/{slug?}', [StudentQuizController::class, 'subjectExams'])->name('student_subject.exams');

Route::post('exams/student/finish-exam/{slug}', [StudentQuizController::class, 'finishExam']);
Route::get('exams/student/reports/{slug}', [StudentQuizController::class, 'reports']);
Route::post('Report-question/{question_id}', [StudentQuizController::class, 'reportIssue']);
Route::get('reported_issues/index', [StudentQuizController::class, 'issuesIndex']);
Route::get('reported_issues/get_issues_table/{id}', [StudentQuizController::class, 'getIssuesDatatable']);

Route::get('exams/student/exam-attempts/{user_slug}/{exam_slug?}', [StudentQuizController::class, 'examAttempts']);
Route::get('exams/student/get-exam-attempts/{user_slug}/{exam_slug?}', [StudentQuizController::class, 'getExamAttemptsData']);

Route::get('student/analysis/by-exam/{user_slug}', [StudentQuizController::class, 'examAnalysis']);
Route::get('student/analysis/get-by-exam/{user_slug}', [StudentQuizController::class, 'getExamAnalysisData']);

Route::get('student/analysis/by-subject/{user_slug}/{exam_slug?}/{results_slug?}', [StudentQuizController::class, 'subjectAnalysisInExam']);
Route::get('student/analysis/subject/{user_slug}', [StudentQuizController::class, 'overallSubjectAnalysis']);

// Student Reports
Route::get('student/exam/answers/{quiz_slug}/{result_slug}', [ReportsController::class, 'viewExamAnswers']);
Route::get('student/exam/answers/subject_report/{quiz_slug}/{result_slug}', [ReportsController::class, 'viewSubjectWiseReport']);

// Quiz
Route::get('exams/quizzes', [QuizController::class, 'index'])->name('exams.quizzes');
Route::get('exams/test-series', [QuizController::class, 'testSeries'])->name('exams.test_series');
Route::get('exams/live-quizzes', [QuizController::class, 'liveQuizzes'])->name('exams.live_quizzes');

Route::get('exams/quiz/add/{exam_type?}', [QuizController::class, 'create']);
Route::post('exams/quiz/add/{exam_type?}', [QuizController::class, 'store']);

Route::get('exams/quiz/edit/{slug}', [QuizController::class, 'edit']);
Route::patch('exams/quiz/edit/{slug}', [QuizController::class, 'update']);
Route::delete('exams/quiz/delete/{slug}', [QuizController::class, 'delete']);
Route::get('exams/quiz/getList/{type?}', [QuizController::class, 'getDatatable'])->name('exams.quiz.getlist');

Route::get('exams/quiz/update-questions/{slug}', [QuizController::class, 'updateQuestions']);
Route::post('exams/quiz/update-questions/{slug}', [QuizController::class, 'storeQuestions']);

Route::match(['get', 'post'], 'exams/quiz/update-batches/{slug}', [QuizController::class, 'updateBatches'])->name('exams.quiz.update_batches');

Route::post('exams/quiz/get-questions', [QuizController::class, 'getSubjectData']);

// Certificates controller
Route::get('result/generate-certificate/{slug}', [CertificatesController::class, 'getCertificate']);

// Student Paid Content
Route::get('package/student-paid-content', [StudentPaidContentController::class, 'index'])->name('studentpaidcontent.index');
Route::get('package/student-paid-content/add', [StudentPaidContentController::class, 'create']);
Route::post('package/student-paid-content/add', [StudentPaidContentController::class, 'store']);
Route::get('package/student-paid-content/edit/{slug}', [StudentPaidContentController::class, 'edit'])->name('studentpaidcontent.edit');
Route::patch('package/student-paid-content/edit/{slug}', [StudentPaidContentController::class, 'update']);
Route::delete('package/student-paid-content/delete/{slug}', [StudentPaidContentController::class, 'delete']);
Route::get('package/student-paid-content/getList', [StudentPaidContentController::class, 'getDatatable'])->name('examseries.dataTable');

Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-courses/{slug}', [StudentPaidContentController::class, 'updateCourses'])->name('studentpaidcontent.courses');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-lmsseries/{slug}', [StudentPaidContentController::class, 'updateLmsSeries'])->name('studentpaidcontent.series');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-lmsnotes/{slug}', [StudentPaidContentController::class, 'updateLmsNotes'])->name('studentpaidcontent.notes');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-exams/{slug}/{type}', [StudentPaidContentController::class, 'updateExams'])->name('studentpaidcontent.exams');

Route::get('student/paid-content-types', [StudentQuizController::class, 'displayPaidContentTypes'])->name('student.paid_content_types');
Route::get('paid-content/{package_slug?}/{type?}/{type_slug?}', [StudentQuizController::class, 'studentPaidContent'])->name('student.paid_content');
Route::get('paid-content-subjectwise/{package_slug?}/{type}/{subject_slug?}/{type_slug?}', [StudentQuizController::class, 'studentPaidContentSubjectwise'])->name('student.paid_content_subjectwise');

Route::get('student-exams/{type}', [StudentQuizController::class, 'studentPaidExams'])->name('student.paid_exams');
Route::get('student-paid/quiz/getList/{type}', [StudentQuizController::class, 'getStudentPaidExams']);

Route::post('paid-content/get-lmsseries', [StudentPaidContentController::class, 'getLmsSeries'])->name('paid_content.lmsseries');
Route::post('paid-content/get-lmsnotes', [StudentPaidContentController::class, 'getLmsNotes'])->name('paid_content.lmsnotes');
Route::post('get-lms-groups', [LmsGroupController::class, 'getLmsGroups'])->name('lms.groups');

Route::post('paid-content/get-chapter-tests', [StudentPaidContentController::class, 'getChapterTests'])->name('paid_content.chapters-tests');
Route::post('paid-content/get-subject-tests', [StudentPaidContentController::class, 'getSubjectTests'])->name('paid_content.subject-tests');
Route::post('paid-content/get-previousyear-tests', [StudentPaidContentController::class, 'getPreviousYearTests'])->name('paid_content.previousyear-tests');
Route::post('paid-content/get-grand-tests', [StudentPaidContentController::class, 'getGrandTests'])->name('paid_content.grand-tests');

// Exam Series
Route::get('exams/exam-series', [ExamSeriesController::class, 'index']);
Route::get('exams/exam-series/add', [ExamSeriesController::class, 'create']);
Route::post('exams/exam-series/add', [ExamSeriesController::class, 'store']);
Route::get('exams/exam-series/edit/{slug}', [ExamSeriesController::class, 'edit']);
Route::patch('exams/exam-series/edit/{slug}', [ExamSeriesController::class, 'update']);
Route::delete('exams/exam-series/delete/{slug}', [ExamSeriesController::class, 'delete']);
Route::get('exams/exam-series/getList', [ExamSeriesController::class, 'getDatatable'])->name('examseries.dataTable');

// EXAM SERIES STUDENT LINKS
Route::get('exams/student-exam-series/list', [ExamSeriesController::class, 'listSeries']);
Route::get('exams/student-exam-series/{slug}', [ExamSeriesController::class, 'viewItem']);

Route::match(['get', 'post'], 'exams/series/update-series-courses/{slug}/{class?}', [ExamSeriesController::class, 'updateSeriesCourses'])->name('examseries.courses');

Route::get('exams/exam-series/update-series/{slug}', [ExamSeriesController::class, 'updateSeries']);
Route::post('exams/exam-series/update-series/{slug}', [ExamSeriesController::class, 'storeSeries']);
Route::post('exams/exam-series/get-exams', [ExamSeriesController::class, 'getExams']);
Route::get('payment/cancel', [ExamSeriesController::class, 'cancel']);
Route::post('payment/success', [ExamSeriesController::class, 'success']);


// ///////////////////
// // PAYMENT REPORTS //
// ///////////////////
Route::get('payments-report/', [PaymentsController::class, 'overallPayments']);

Route::get('payments-report/online/', [PaymentsController::class, 'onlinePaymentsReport']);
Route::get('payments-report/online/{slug}', [PaymentsController::class, 'listOnlinePaymentsReport']);
Route::get('payments-report/online/getList/{slug}', [PaymentsController::class, 'getOnlinePaymentReportsDatatable']);

Route::get('payments-report/offline/', [PaymentsController::class, 'offlinePaymentsReport']);
Route::get('payments-report/offline/{slug}', [PaymentsController::class, 'listOfflinePaymentsReport']);
Route::get('payments-report/offline/getList/{slug}', [PaymentsController::class, 'getOfflinePaymentReportsDatatable']);
Route::get('payments-report/export', [PaymentsController::class, 'exportPayments']);
Route::post('payments-report/export', [PaymentsController::class, 'doExportPayments']);

Route::post('payments-report/getRecord', [PaymentsController::class, 'getPaymentRecord']);
Route::post('payments/approve-reject-offline-request', [PaymentsController::class, 'approveOfflinePayment']);

// //////////////////
// // INSTRUCTIONS  //
// //////////////////

Route::get('exam/instructions/list', [InstructionsController::class, 'index']);
Route::get('exam/instructions', [InstructionsController::class, 'index']);
Route::get('exams/instructions/add', [InstructionsController::class, 'create']);
Route::post('exams/instructions/add', [InstructionsController::class, 'store']);
Route::get('exams/instructions/edit/{slug}', [InstructionsController::class, 'edit']);
Route::patch('exams/instructions/edit/{slug}', [InstructionsController::class, 'update']);
Route::delete('exams/instructions/delete/{slug}', [InstructionsController::class, 'delete']);
Route::get('exams/instructions/getList', [InstructionsController::class, 'getDatatable']);

// // BOOKMARKS MODULE
Route::get('student/bookmarks/{slug}', [BookmarksController::class, 'index']);
Route::post('student/bookmarks/add', [BookmarksController::class, 'create']);
Route::delete('student/bookmarks/delete/{id}', [BookmarksController::class, 'delete']);
Route::delete('student/bookmarks/delete_id/{id}', [BookmarksController::class, 'deleteById']);
Route::get('student/bookmarks/getList/{slug}', [BookmarksController::class, 'getDatatable']);
Route::post('student/bookmarks/getSavedList', [BookmarksController::class, 'getSavedBookmarks']);

// //////////////////////////
// // Online Classes Module //
///////////////////////////
Route::get('admin/onlineclasses/list', [OnlineclassesController::class, 'index'])->name('onlineclasses.index');
Route::get('admin/onlineclasses/attendence', [OnlineclassesController::class, 'attendenceIndex'])->name('onlineclasses.attendence');
Route::get('admin/onlineclasses', [OnlineclassesController::class, 'index']);
Route::get('admin/onlineclasses/add', [OnlineclassesController::class, 'create']);
Route::post('admin/onlineclasses/add', [OnlineclassesController::class, 'store']);
Route::get('admin/onlineclasses/edit/{slug}', [OnlineclassesController::class, 'edit']);
Route::patch('admin/onlineclasses/edit/{slug}', [OnlineclassesController::class, 'update']);
Route::delete('admin/onlineclasses/delete/{slug}', [OnlineclassesController::class, 'delete']);
Route::get('admin/onlineclasses/getList', [OnlineclassesController::class, 'getDatatable']);
Route::get('admin/onlineclasses/getAttendencetList', [OnlineclassesController::class, 'getAttendenceDatatable']);

// Online Classes FOR STUDENT
Route::get('onlineclasses/list', [OnlineclassesController::class, 'usersList']);
Route::get('onlineclasses/show/{slug}', [OnlineclassesController::class, 'display']);
Route::get('onlineclass/white-board/{slug?}', [OnlineclassesController::class, 'whiteBoard'])->name('onlineclasses.whiteboard');
Route::get('student/onlineclasses/attendence/{id}', [OnlineclassesController::class, 'studentAttendence']); // Record attendance.

Route::get('class-attendance/{slug}', [OnlineclassesController::class, 'classAttendence'])->name('class.attendence');
Route::get('class-attendance/getList/{slug}', [OnlineclassesController::class, 'classAttendenceList'])->name('class.attendence.list');

Route::get('class-absent/{slug}', [OnlineclassesController::class, 'classAbsent'])->name('class.absent');
Route::get('class-absent/getList/{slug}', [OnlineclassesController::class, 'classAbsentList'])->name('class.absent.list');

Route::match(['get', 'post'], 'onlinceclass/lmsnotes/{slug}', [OnlineclassesController::class, 'assignLmsNotes'])->name('onlineclass.lmsnotes');

// //////////////////////////
// // Notifications Module //
///////////////////////////
Route::get('admin/notifications/list', [NotificationsController::class, 'index']);
Route::get('admin/notifications', [NotificationsController::class, 'index']);
Route::get('admin/notifications/add', [NotificationsController::class, 'create']);
Route::post('admin/notifications/add', [NotificationsController::class, 'store']);
Route::get('admin/notifications/edit/{slug}', [NotificationsController::class, 'edit']);
Route::patch('admin/notifications/edit/{slug}', [NotificationsController::class, 'update']);
Route::delete('admin/notifications/delete/{slug}', [NotificationsController::class, 'delete']);
Route::get('admin/notifications/getList', [NotificationsController::class, 'getDatatable']);

// notifications FOR STUDENT
Route::get('notifications/list', [NotificationsController::class, 'usersList']);
Route::get('notifications/show/{slug}', [NotificationsController::class, 'display']);

// BOOKMARKS MODULE
Route::get('toppers/compare-with-topper/{user_result_slug}/{compare_slug?}', [ExamToppersController::class, 'compare']);

// ////////////////
// // LMS MODULE //
//////////////////

// LMS Categories
Route::get('lms/categories', [LmsCategoryController::class, 'index']);
Route::get('lms/categories/add', [LmsCategoryController::class, 'create']);
Route::post('lms/categories/add', [LmsCategoryController::class, 'store']);
Route::get('lms/categories/edit/{slug}', [LmsCategoryController::class, 'edit']);
Route::patch('lms/categories/edit/{slug}', [LmsCategoryController::class, 'update']);
Route::delete('lms/categories/delete/{slug}', [LmsCategoryController::class, 'delete']);
Route::get('lms/categories/getList', [LmsCategoryController::class, 'getDatatable'])->name('lmscategories.dataTable');

// LMS Groups
Route::get('lms-groups', [LmsGroupController::class, 'index'])->name('lms-groups.index');
Route::get('lms/groups/add', [LmsGroupController::class, 'create']);
Route::get('lms-groups/show/{slug}/{subject_slug?}', [LmsGroupController::class, 'show'])->name('lms-groups.show');
Route::post('lms/groups/add', [LmsGroupController::class, 'store']);
Route::get('lms/groups/edit/{slug}', [LmsGroupController::class, 'edit']);
Route::patch('lms/groups/edit/{slug}', [LmsGroupController::class, 'update']);
Route::delete('lms/groups/delete/{slug}', [LmsGroupController::class, 'delete']);
Route::get('lms-groups/getGroupsList', [LmsGroupController::class, 'getDatatable'])->name('lmsgroups.dataTable');
Route::get('lms/groups/update-series/{slug}', [LmsGroupController::class, 'updateSeries'])->name('lmsgroups.updateseries');
Route::post('lms/groups/update-series/{slug}', [LmsGroupController::class, 'updateSeries']);
Route::get('lms-groups-subject-series/{subject_slug}', [LmsGroupController::class, 'lmsGroupSubjectSeries'])->name('lms-groups-subject.series');

// LMS Contents
Route::get('lms/content', [LmsContentController::class, 'index'])->name('lms.content');
Route::get('lms/content/add', [LmsContentController::class, 'create']);
Route::post('lms/content/add', [LmsContentController::class, 'store']);
Route::get('lms/content/edit/{slug}', [LmsContentController::class, 'edit']);
Route::patch('lms/content/edit/{slug}', [LmsContentController::class, 'update']);
Route::delete('lms/content/delete/{slug}', [LmsContentController::class, 'delete']);
Route::get('lms/content/getList', [LmsContentController::class, 'getDatatable'])->name('lmscontent.dataTable');
Route::get('lms/content/{slug}/{content_slug?}', [LmsContentController::class, 'viewItem']);

// LMS Series
Route::get('lms/series', [LmsSeriesController::class, 'index'])->name('lms.series.index');
Route::get('lms/series/add', [LmsSeriesController::class, 'create']);
Route::post('lms/series/add', [LmsSeriesController::class, 'store']);
Route::get('lms/series/edit/{slug}', [LmsSeriesController::class, 'edit']);
Route::patch('lms/series/edit/{slug}', [LmsSeriesController::class, 'update']);
Route::delete('lms/series/delete/{slug}', [LmsSeriesController::class, 'delete']);
Route::get('lms/series/getList', [LmsSeriesController::class, 'getDatatable'])->name('lmsseries.dataTable');

// LMS Series
Route::match(['get', 'post'], 'lms/change-content-order/{slug}', [LmsSeriesController::class, 'changeContentOrder'])->name('lmsseries.chang_content_order');

// LMS Notes
Route::get('lms/notes', [LmsNotesController::class, 'index'])->name('lms.notes');
Route::get('lms/notes/add', [LmsNotesController::class, 'create']);
Route::post('lms/notes/add', [LmsNotesController::class, 'store']);
Route::get('lms/notes/edit/{slug}', [LmsNotesController::class, 'edit']);
Route::patch('lms/notes/edit/{slug}', [LmsNotesController::class, 'update']);
Route::delete('lms/notes/delete/{slug}', [LmsNotesController::class, 'delete']);
Route::get('lms/notes/getList', [LmsNotesController::class, 'getDatatable'])->name('lmsnotes.dataTable');
Route::get('lms/prew-notes/{slug}', [LmsNotesController::class, 'previewNotes'])->name('lms.preview_notes');

// LMS SERIES STUDENT LINKS
Route::get('lms/exam-series/list', [LmsSeriesController::class, 'listSeries']);
Route::get('lms/exam-series/{slug}', [LmsSeriesController::class, 'viewItem']);

Route::get('lms/series/update-series/{slug}', [LmsSeriesController::class, 'updateSeries']);
Route::post('lms/series/update-series/{slug}', [LmsSeriesController::class, 'storeSeries']);

Route::get('lms/series/update-series-exams/{slug}', [LmsSeriesController::class, 'updateSeriesExams']);
Route::post('lms/series/update-series-exams/{slug}', [LmsSeriesController::class, 'storeSeriesExams']);

Route::match(['get', 'post'], 'lms/series/update-series-courses/{slug}/{course?}', [LmsSeriesController::class, 'updateSeriesCourses'])->name('lmsseries.courses');

Route::post('lms/series/get-series', [LmsSeriesController::class, 'getSeries']);
Route::get('payment/cancel', [LmsSeriesController::class, 'cancel']);
Route::post('payment/success', [LmsSeriesController::class, 'success']);

// LMS Student view
Route::get('learning-management/categories', [StudentLmsController::class, 'index']);
Route::get('learning-management/view/{slug}', [StudentLmsController::class, 'viewCategoryItems']);
Route::get('learning-management/subjects', [StudentLmsController::class, 'subjects'])->name('studentlms.subjects');
Route::get('learning-management/view-subject/{slug}/{series_slug?}', [StudentLmsController::class, 'viewSubjectItems'])->name('studentlms.subjectitems');
Route::get('learning-management-notes/view-subject/{slug}/{series_slug?}', [StudentLmsController::class, 'viewSubjectItemsNotes'])->name('studentlmsnotes.subjectitems');
Route::get('learning-management/series', [StudentLmsController::class, 'series']);
Route::get('learning-management/series/{slug}/{content_slug?}', [StudentLmsController::class, 'viewItem']);
Route::get('user/paid/{slug}/{content_slug}', [StudentLmsController::class, 'verifyPaidItem']);
Route::get('learning-management/content/{req_content_type}', [StudentLmsController::class, 'content']);
Route::get('learning-management/content/show/{slug}', [StudentLmsController::class, 'showContent']);

// Payments Controller
Route::get('payments/list/{slug}', [PaymentsController::class, 'index']);
Route::get('payments/getList/{slug}', [PaymentsController::class, 'getDatatable']);
Route::get('payments/checkout/{type}/{slug}', [PaymentsController::class, 'checkout'])->name('payments.checkout');
Route::get('payments/paynow/{slug}', [DashboardController::class, 'index']);
Route::post('payments/paynow/{slug}', [PaymentsController::class, 'paynow']);
Route::post('payments/paypal/status-success', [PaymentsController::class, 'paypal_success']);
Route::get('payments/paypal/status-cancel', [PaymentsController::class, 'paypal_cancel']);
Route::post('payments/payu/status-success', [PaymentsController::class, 'payu_success']);
Route::post('payments/payu/status-cancel', [PaymentsController::class, 'payu_cancel']);
Route::post('payments/offline-payment/update', [PaymentsController::class, 'updateOfflinePayment']);

////////////////////////////
// SETTINGS MODULE //
///////////////////////////

// LMS Categories
Route::get('mastersettings/settings/', [SettingsController::class, 'index']);
Route::get('mastersettings/settings/index', [SettingsController::class, 'index']);
Route::get('mastersettings/settings/add', [SettingsController::class, 'create']);
Route::post('mastersettings/settings/add', [SettingsController::class, 'store']);
Route::get('mastersettings/settings/edit/{slug}', [SettingsController::class, 'edit']);
Route::patch('mastersettings/settings/edit/{slug}', [SettingsController::class, 'update']);
Route::get('mastersettings/settings/view/{slug}', [SettingsController::class, 'viewSettings']);
Route::get('mastersettings/settings/add-sub-settings/{slug}', [SettingsController::class, 'addSubSettings']);
Route::post('mastersettings/settings/add-sub-settings/{slug}', [SettingsController::class, 'storeSubSettings']);
Route::patch('mastersettings/settings/add-sub-settings/{slug}', [SettingsController::class, 'updateSubSettings']);
Route::get('mastersettings/settings/getList', [SettingsController::class, 'getDatatable'])->name('mastersettings.dataTable');

////////////////////////////
// EMAIL TEMPLATES MODULE //
///////////////////////////

// LMS Categories
Route::get('email/templates', [EmailTemplatesController::class, 'index']);
Route::get('email/templates/add', [EmailTemplatesController::class, 'create']);
Route::post('email/templates/add', [EmailTemplatesController::class, 'store']);
Route::get('email/templates/edit/{slug}', [EmailTemplatesController::class, 'edit']);
Route::patch('email/templates/edit/{slug}', [EmailTemplatesController::class, 'update']);
Route::delete('email/templates/delete/{slug}', [EmailTemplatesController::class, 'delete']);
Route::get('email/templates/getList', [EmailTemplatesController::class, 'getDatatable'])->name('emailtemplates.dataTable');

// Coupons Module
Route::get('coupons/list', [CouponcodesController::class, 'index']);
Route::get('coupons/add', [CouponcodesController::class, 'create']);
Route::post('coupons/add', [CouponcodesController::class, 'store']);
Route::get('coupons/edit/{slug}', [CouponcodesController::class, 'edit']);
Route::patch('coupons/edit/{slug}', [CouponcodesController::class, 'update']);
Route::delete('coupons/delete/{slug}', [CouponcodesController::class, 'delete']);
Route::get('coupons/getList/{slug?}', [CouponcodesController::class, 'getDatatable']);
Route::get('coupons/get-usage', [CouponcodesController::class, 'getCouponUsage']);
Route::get('coupons/get-usage-data', [CouponcodesController::class, 'getCouponUsageData']);
Route::post('coupons/update-questions/{slug}', [CouponcodesController::class, 'storeQuestions']);
Route::post('coupons/validate-coupon', [CouponcodesController::class, 'validateCoupon']);

// Feedback Module
Route::get('feedback/list', [FeedbackController::class, 'index']);
Route::get('feedback/view-details/{slug}', [FeedbackController::class, 'details']);
Route::get('feedback/send', [FeedbackController::class, 'create']);
Route::post('feedback/send', [FeedbackController::class, 'store']);
Route::delete('feedback/delete/{slug}', [FeedbackController::class, 'delete']);
Route::get('feedback/getlist', [FeedbackController::class, 'getDatatable']);

// SMS Module
Route::get('sms/index', [SMSAgentController::class, 'index']);
Route::post('sms/send', [SMSAgentController::class, 'sendSMS']);

/////////////////////
// MESSAGES MODULE //
/////////////////////

Route::prefix('messages')->group(function () {
    Route::get('/', [MessagesController::class, 'index'])->name('messages');
    Route::get('create', [MessagesController::class, 'create'])->name('messages.create');
    Route::post('/', [MessagesController::class, 'store'])->name('messages.store');
    Route::get('{id}/{request_id?}', [MessagesController::class, 'show'])->name('messages.show');
    Route::put('{id}/{request_id?}', [MessagesController::class, 'update'])->name('messages.update');
});

/////////////////////
// PRIVACY POLICY  //
/////////////////////

Route::get('site/{slug?}', [SiteController::class, 'sitePages']);

// CSRF Refresh Route
Route::get('refresh-csrf', function () {
    return csrf_token();
});

// Front End Part
Route::get('exams/list', [FrontendExamsController::class, 'examsList']);
Route::get('exams/start-exam/{slug}', [FrontendExamsController::class, 'startExam']);
Route::post('exams/finish-exam/{slug}', [FrontendExamsController::class, 'finishExam']);

// Resume Exam
Route::post('resume/examdata/save', [StudentQuizController::class, 'saveResumeExamData']);
Route::get('exam-types', [QuizController::class, 'examTypes']);
Route::get('edit/exam-type/{code}', [QuizController::class, 'editExamType']);
Route::post('update/exam-type/{code}', [QuizController::class, 'updateExamType']);
Route::post('razoapay/success', [PaymentsController::class, 'razorpaySuccess']);

// Theme Updates
Route::post('subscription/email', [SiteController::class, 'saveSubscription']);
Route::get('front-end/get-class-courses/{class_id}', [SiteController::class, 'getClassCourses']);

// Subscribed Users
Route::get('subscribed/users', [UsersController::class, 'SubscribedUsers']);
Route::get('subscribed/users/data', [UsersController::class, 'SubscribersData']);

// All Exam categories
Route::get('exam/categories/{slug?}', [SiteController::class, 'frontAllExamCats']);
Route::get('practice-exams/{slug?}', [SiteController::class, 'frontAllExamCats']);
Route::get('LMS/all-categories/{slug?}', [SiteController::class, 'forntAllLMSCats']);
Route::get('LMS/contents/{slug}', [SiteController::class, 'forntLMSContents']);
Route::get('download/lms/contents/{slug}', [SiteController::class, 'downloadLMSContent']);
Route::get('lms/video/{slug}/{cat_id?}', [SiteController::class, 'viewVideo']);
Route::get('contact-us', function () {
    $view_name = getTheme() . '::site.contact-us';
    $data['active_class'] = "contact-us";
    $data['title'] = getPhrase('contact_us');
    return view($view_name, $data);
});
Route::post('send/contact-us/details', [SiteController::class, 'ContactUs']);
Route::post('get/series/contents', [SiteController::class, 'getSeriesContents']);

// Themes
Route::get('themes/list', [SiteThemesController::class, 'index']);
Route::get('themes/data', [SiteThemesController::class, 'getDatatable']);
Route::get('make/default/theme/{id}', [SiteThemesController::class, 'makeDefault']);
Route::get('theme/settings/{slug}', [SiteThemesController::class, 'viewSettings']);
Route::post('theme/update/settings/{slug}', [SiteThemesController::class, 'updateSubSettings']);

Route::get('test-route', function () {
    return 'Route is working';
});

// Institute
Route::get('institute/registration', [InstituteController::class, 'instituteRegistration']);
// Route::post('institute/registration', [InstituteController::class, 'storeInstitute']);
Route::post('add/institute', [InstituteController::class, 'addInstitute']);
Route::get('institutes/{assigned_type?}/', [InstituteController::class, 'viewInstitutes']);
Route::get('institutes_all/getlist', [InstituteController::class, 'getDatatable']);
Route::get('instutite/details/{id}', [InstituteController::class, 'details']);
Route::post('change/institute-status', [InstituteController::class, 'updateInstitute']);
Route::get('edit/institute/{id}', [InstituteController::class, 'edit']);
Route::patch('edit/institute/{id}', [InstituteController::class, 'update']);
Route::get('institute/setpermissions/add/{id}', [InstituteController::class, 'setPermissions']);
Route::patch('institute/setpermissions/add/{id}', [InstituteController::class, 'setPermissions']);

// Institute Associates
Route::get('associate/institute/{id}', [InstituteAssosiatesController::class, 'index']);
Route::post('add/associate', [InstituteAssosiatesController::class, 'addAssociates']);

// Institute Branches
Route::get('institutes/branches', [InstituteBranchController::class, 'index']);
Route::get('institutes/branches/add', [InstituteBranchController::class, 'create']);
Route::post('institutes/branches/add', [InstituteBranchController::class, 'store']);
Route::get('institutes/branches/edit/{slug}', [InstituteBranchController::class, 'edit']);
Route::patch('institutes/branches/edit/{slug}', [InstituteBranchController::class, 'update']);
Route::delete('institutes/branches/delete/{id}', [InstituteBranchController::class, 'delete']);
Route::get('institutes/branches/getlist', [InstituteBranchController::class, 'getDatatable']);

// Batches Module
Route::get('batches/list', [BatchesController::class, 'index'])->name('batches.index');
Route::get('batches/add', [BatchesController::class, 'create']);
Route::post('batches/add', [BatchesController::class, 'store']);
Route::get('batches/edit/{slug}', [BatchesController::class, 'edit']);
Route::patch('batches/edit/{slug}', [BatchesController::class, 'update']);
Route::delete('batches/delete/{slug}', [BatchesController::class, 'delete']);
Route::get('batches/getList', [BatchesController::class, 'getDatatable']);

Route::get('batches/addstudents/{batch_id}', [BatchesController::class, 'addStudents']);
Route::post('batches/getstudents', [BatchesController::class, 'getStudents']);
Route::post('batches/store/students', [BatchesController::class, 'storeStudents']);
Route::get('batches/students/{batch_id}', [BatchesController::class, 'viewBatchStudents']);
Route::post('update/batches/students', [BatchesController::class, 'updateBatchStudents']);
Route::post('available/batch/students', [BatchesController::class, 'getBatchStudents']);

Route::match(['get', 'post'], 'batches/addlms/{batch_id}', [BatchesController::class, 'addLms'])->name('batch.addlms');
Route::post('batches/getlms', [BatchesController::class, 'getLms']);
Route::post('batches/store/lms', [BatchesController::class, 'storeLms']);

Route::match(['get', 'post'], 'batches/addlmsnotes/{batch_id}', [BatchesController::class, 'addLmsnotes'])->name('batch.addlmsnotes');

Route::get('batches/report/{batch_id}/{exam_slug?}', [BatchesController::class, 'batchReport'])->name('batches.report');

Route::match(['get', 'post'], 'batches/email-alerts', [BatchesController::class, 'emsilAlerts'])->name('batch.email-alerts');
Route::match(['get', 'post'], 'batches/sms-alerts', [BatchesController::class, 'smsAlerts'])->name('batch.sms-alerts');
Route::match(['get', 'post'], 'batches/email-sms-alerts', [BatchesController::class, 'emsilSmsAlerts'])->name('batch.email-sms-alerts');

// Fee Module
Route::get('pay/fee', [FeePayController::class, 'index']);
Route::post('add/fee', [FeePayController::class, 'payFee']);
Route::post('fee/batch/students', [FeePayController::class, 'getStudents']);
Route::post('batch/students-details', [FeePayController::class, 'studentDetails']);
Route::post('batch/add-discount', [FeePayController::class, 'addDiscount']);

// Fee Reports
Route::get('fee-reports/batch', [FeePayController::class, 'batchReports']);
Route::post('batch/fee-reports', [FeePayController::class, 'feePaidBatchStudents']);
Route::post('print/fee-reports', [FeePayController::class, 'printReports']);
Route::get('institute/fee-reports', [FeePayController::class, 'instituteReports']);
Route::post('get/institute/batches', [FeePayController::class, 'instituteBatches']);
Route::get('fee-paid-reports', [FeePayController::class, 'dateReports']);
Route::post('get/daily-reports', [FeePayController::class, 'getDailyReports']);

Route::get('update/application', [FeaturesUpdatesController::class, 'updateDatabase']);

// Front End Batches Details
Route::get('batches/details/{institute_id}', [SiteController::class, 'batchesDetails']);
Route::get('notes/student/{slug?}', [StudentQuizController::class, 'LmsNotes'])->name('student.lms_notes');

Route::match(['get', 'post'], '/system-reset', [UsersController::class, 'systemReset'])->name('users.system-reset');
Route::post('class-courses', [SiteController::class, 'getCourses'])->name('class.courses');

// Packages
Route::get('packages/list', [PackagesController::class, 'index'])->name('packages.list');
Route::get('packages/add', [PackagesController::class, 'create'])->name('packages.add');
Route::post('packages/add', [PackagesController::class, 'store'])->name('packages.add');
Route::get('packages/edit/{slug}', [PackagesController::class, 'edit'])->name('packages.edit');
Route::patch('packages/edit/{slug}', [PackagesController::class, 'update'])->name('packages.edit');
Route::delete('packages/delete/{slug}', [PackagesController::class, 'delete'])->name('packages.delete');
Route::get('packages/getList/{slug?}', [PackagesController::class, 'getDatatable'])->name('packages.getList');
Route::get('renew-packages/{slug?}', [PackagesController::class, 'renewPackage'])->name('packages.renew');
Route::post('renew-packages', [PackagesController::class, 'storeRenewRequests'])->name('package_renew.request');

Route::get('renewal-requests', [PackagesController::class, 'renewRequests'])->name('packages.renew.requests');
// Route::post('renewal-requests/list', [PackagesController::class, 'getRenewRequests'])->name('packages.renew.requests.list');
// Route::post('renewal-requests/approve', [PackagesController::class, 'approveRenewRequest'])->name('packages.renew.requests.approve');
// Route::post('renewal-requests/delete', [PackagesController::class, 'deleteRenewRequest'])->name('packages.renew.requests.delete');

// // Topics Module
// Route::get('topics/list', [TopicsController::class, 'index'])->name('topics.list');
// Route::get('topics/add', [TopicsController::class, 'create'])->name('topics.add');
// Route::post('topics/add', [TopicsController::class, 'store'])->name('topics.add');
// Route::get('topics/edit/{slug}', [TopicsController::class, 'edit'])->name('topics.edit');
// Route::patch('topics/edit/{slug}', [TopicsController::class, 'update'])->name('topics.edit');
// Route::delete('topics/delete/{slug}', [TopicsController::class, 'delete'])->name('topics.delete');
// Route::get('topics/getList', [TopicsController::class, 'getDatatable'])->name('topics.getList');

// // LMS Series
// Route::get('lms-series/list', [LmsSeriesController::class, 'index'])->name('lms_series.list');
// Route::get('lms-series/add', [LmsSeriesController::class, 'create'])->name('lms_series.add');
// Route::post('lms-series/add', [LmsSeriesController::class, 'store'])->name('lms_series.add');
// Route::get('lms-series/edit/{slug}', [LmsSeriesController::class, 'edit'])->name('lms_series.edit');
// Route::patch('lms-series/edit/{slug}', [LmsSeriesController::class, 'update'])->name('lms_series.edit');
// Route::delete('lms-series/delete/{slug}', [LmsSeriesController::class, 'delete'])->name('lms_series.delete');
// Route::get('lms-series/getList', [LmsSeriesController::class, 'getDatatable'])->name('lms_series.getList');

// // Online Classes
// Route::get('online-classes/list', [OnlineclassesController::class, 'index'])->name('online_classes.list');
// Route::get('online-classes/add', [OnlineclassesController::class, 'create'])->name('online_classes.add');
// Route::post('online-classes/add', [OnlineclassesController::class, 'store'])->name('online_classes.add');
// Route::get('online-classes/edit/{slug}', [OnlineclassesController::class, 'edit'])->name('online_classes.edit');
// Route::patch('online-classes/edit/{slug}', [OnlineclassesController::class, 'update'])->name('online_classes.edit');
// Route::delete('online-classes/delete/{slug}', [OnlineclassesController::class, 'delete'])->name('online_classes.delete');
// Route::get('online-classes/getList', [OnlineclassesController::class, 'getDatatable'])->name('online_classes.getList');

Route::get('get-renewal-requests', [PackagesController::class, 'getRenewalRequests'])->name('packages.get_renewal_requests');
Route::get('renewal-requests', [PackagesController::class, 'renewalRequests'])->name('packages.renewal_requests');

// Route::post('file-manager-custom/upload', [\Alexusmai\LaravelFileManager\Controllers\FileManagerController::class, 'upload']);

Route::get('exams/dashboard', [QuizController::class, 'dashboard'])->name('exams.dashboard');

Route::get('exams/questionbank-management', [QuizController::class, 'questionBankManagement'])->name('exams.questionbank.management');
Route::get('exams/topics-directory', [TopicsController::class, 'topicsDirectory'])->name('exams.topics.directory');

Route::get('lmsseries/topics-directory', [LmsSeriesController::class, 'lmsSeriesDirectory'])->name('lmsseries.directory');

Route::get('online-classes/get-lms/{subject_id}/{chapter_id}', [OnlineclassesController::class, 'getLms'])->name('onlineclasses.get_lms');
Route::get('online-classes/get-notes/{subject_id}/{chapter_id}', [OnlineclassesController::class, 'getNotes'])->name('onlineclasses.get_notes');

Route::post('get-pop-quiz-info/{lms_series_id}/{content_id}', [BatchesController::class, 'changePopQuizInfo'])->name('change-pop.quiz_info');

Route::get('live-quiz/unpop/{online_class_id}', [QuizController::class, 'unPopExam'])->name('live-quiz.unpop');

Route::match(['get', 'post'], 'onlineclasses/add-live-quiz/{slug}', [OnlineclassesController::class, 'updateLiveQuiz2'])->name('onlineclasses.add_live_quiz');

Route::get('user/login-history', [UsersController::class, 'loginHistory'])->name('user.login_history');
Route::get('user/login-history/getList', [UsersController::class, 'getHistory'])->name('login_history.dataTable');

Route::get('user/user-actions', [UsersController::class, 'actionsHistory'])->name('user.actions');
Route::get('user/user-actions/getList', [UsersController::class, 'getActionsHistory'])->name('user_actions.dataTable');

Route::get('import-classes/import', [OnlineclassesController::class, 'importClasses'])->name('onlineclasses.import');
Route::post('import-classes/import', [OnlineclassesController::class, 'readExcel'])->name('onlineclasses.import_post');

Route::get('batch-reports/index',  [BatchesController::class, 'batchReportsList'])->name('batch.reports');

Route::get('batch-reports/getList', [BatchesController::class, 'getBatchReportsList'])->name('batch.get_reports');

Route::get('terms-of-service', [SiteController::class, 'termsOfService'])->name('site.terms_of_service');
Route::get('privacy-policy', [SiteController::class, 'privacyPolicy'])->name('site.privacy_policy');

Route::get('student/trail-notify-dismiss/{payment_id}', [StudentQuizController::class, 'trailNotifyDismiss'])->name('student.trail_notify_dismiss');

Route::get('student/enable-free-trail/{paidcontent_slug}', [StudentQuizController::class, 'enableFreeTrail'])->name('student.enable_free_trail');

// Student Classes
Route::get('institute/classes', [StudentClassController::class, 'index']);
Route::get('institute/classes/add', [StudentClassController::class, 'create']);
Route::post('institute/classes/add', [StudentClassController::class, 'store']);
Route::get('institute/classes/edit/{slug}', [StudentClassController::class, 'edit']);
Route::patch('institute/classes/edit/{slug}', [StudentClassController::class, 'update']);
Route::delete('institute/classes/delete/{slug}', [StudentClassController::class, 'delete']);
Route::get('institutes/student-classes/getlist', [StudentClassController::class, 'getDatatable'])->name('institute_classes.dataTable');

// Courses
Route::get('institute/courses', [CourseController::class, 'index']);
Route::get('institute/courses/add', [CourseController::class, 'create']);
Route::post('institute/courses/add', [CourseController::class, 'store']);
Route::get('institute/courses/edit/{slug}', [CourseController::class, 'edit']);
Route::patch('institute/courses/edit/{slug}', [CourseController::class, 'update']);
Route::delete('institute/courses/delete/{slug}', [CourseController::class, 'delete']);
Route::get('institutes/courses/getlist', [CourseController::class, 'getDatatable'])->name('institute_courses.dataTable');


//Subject Logos
Route::prefix('exams/subjectlogos')->group(function () {
    
    Route::get('/', [SubjectLogosController::class, 'index']);
    Route::get('/add', [SubjectLogosController::class, 'create']);
    Route::post('/add', [SubjectLogosController::class, 'store']);
    Route::get('/edit/{slug}', [SubjectLogosController::class, 'edit']);
    Route::patch('/edit/{slug}', [SubjectLogosController::class, 'update']);
    Route::delete('/delete/{slug}', [SubjectLogosController::class, 'delete']);
    
    Route::get('/getList', [SubjectLogosController::class, 'getDatatable'])
         ->name('subjectlogs.dataTable');

});



Route::prefix('question-bank-types')->group(function () {

    Route::get('/', [QuestionBankTypesController::class, 'index']);
    Route::get('/add', [QuestionBankTypesController::class, 'create']);
    Route::post('/add', [QuestionBankTypesController::class, 'store']);
    Route::get('/edit/{id}', [QuestionBankTypesController::class, 'edit']);
    Route::patch('/edit/{id}', [QuestionBankTypesController::class, 'update']);
    Route::delete('/delete/{id}', [QuestionBankTypesController::class, 'delete']);

    Route::get('/getlist', [QuestionBankTypesController::class, 'getDatatable'])
         ->name('question-bank-types.dataTable');

});


Route::prefix('competitive-exam-types')->group(function () {

    Route::get('/', [CompetitiveExamTypesController::class, 'index']);
    Route::get('/add', [CompetitiveExamTypesController::class, 'create']);
    Route::post('/add', [CompetitiveExamTypesController::class, 'store']);
    Route::get('/edit/{id}', [CompetitiveExamTypesController::class, 'edit']);
    Route::patch('/edit/{id}', [CompetitiveExamTypesController::class, 'update']);
    Route::delete('/delete/{id}', [CompetitiveExamTypesController::class, 'delete']);

    Route::get('/getlist', [CompetitiveExamTypesController::class, 'getDatatable'])
         ->name('competitive-exam-types.dataTable');

});

// LMS Series
Route::get('lms-series/{slug?}', [LmsSeriesController::class, 'index'])->name('lms.series');
Route::get('lms/series/add', [LmsSeriesController::class, 'create']);
Route::post('lms/series/add', [LmsSeriesController::class, 'store']);
Route::get('lms/series/edit/{slug}', [LmsSeriesController::class, 'edit']);
Route::patch('lms/series/edit/{slug}', [LmsSeriesController::class, 'update']);
Route::delete('lms/series/delete/{slug}', [LmsSeriesController::class, 'delete']);
Route::get('lms/series/getList', [LmsSeriesController::class, 'getDatatable'])->name('lmsseries.dataTable');
Route::match(['get', 'post'], 'lms/change-content-order/{slug}', [LmsSeriesController::class, 'changeContentOrder'])->name('lmsseries.chang_content_order');


// Quiz Categories
Route::get('exams/categories', [QuizCategoryController::class, 'index']);
Route::get('exams/categories/add', [QuizCategoryController::class, 'create']);
Route::post('exams/categories/add', [QuizCategoryController::class, 'store']);
Route::get('exams/categories/edit/{slug}', [QuizCategoryController::class, 'edit']);
Route::patch('exams/categories/edit/{slug}', [QuizCategoryController::class, 'update']);
Route::delete('exams/categories/delete/{slug}', [QuizCategoryController::class, 'delete']);

Route::get('exams/categories/getList', [QuizCategoryController::class, 'getDatatable'])->name('quizcategories.dataTable');