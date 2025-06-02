<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

 // $agent = new Agent();
 //    if($agent->isAndroidOS())
 //    {
 //       return redirect('https://conquerorstech.net/');
 //    }


if(env('DB_DATABASE')=='')
{

   Route::get('/', 'InstallatationController@index');
   Route::get('/install', 'InstallatationController@index');
   Route::post('/update-details', 'InstallatationController@updateDetails');
   Route::post('/install', 'InstallatationController@installProject');
}

Route::get('/', function () {

    if(Auth::check())
    {
        return redirect('dashboard');
    }
    // dd('here');
    return redirect(URL_HOME);
});




if(env('DEMO_MODE')) {

    Event::listen('eloquent.saving: *', function ($model) {
        if(urlHasString('finish-exam') || urlHasString('start-exam'))
          return true;
      return false;


    });

}

 // Route::get('install/reg', 'InstallatationController@reg');
 Route::post('install/register', 'InstallatationController@registerUser');


if(env('DB_DATABASE')==''){
  Route::get('/', 'SiteController@index');
}
  Route::get('home', 'SiteController@index')->name('site.home');
// Route::get('/', function () {

//     if(Auth::check())
//     {
//         return redirect('dashboard');
//     }
//  return redirect(URL_USERS_LOGIN);
// });

Route::get('dashboard','DashboardController@index')->name('user.dashboard');
Route::get('dashboard/testlang','DashboardController@testLanguage');


Route::get('auth/{slug}','Auth\LoginController@redirectToProvider');
Route::get('auth/{slug}/callback','Auth\LoginController@handleProviderCallback');

Route::get('about','SiteController@about')->name('site.about');
Route::get('courses/{course_slug?}','SiteController@courses')->name('site.courses');
Route::get('blog/{slug?}','SiteController@blog')->name('site.blog');
Route::get('pricing','SiteController@pricing')->name('site.pricing');
Route::get('pricing/student-packages/{course_id?}/{slug_type?}','SiteController@getStudentPackages')->name('pricing.student-packages');
Route::get('practice','SiteController@practice')->name('site.practice');
Route::match(['get', 'post'], 'contact','SiteController@contact')->name('site.contact');
Route::get('institute','SiteController@institute')->name('site.institute');

Route::get('board/{board_id}/{class?}/{subject?}','SiteController@board')->name('site.board');
Route::get('/media/file-download/{model}/{field}/{record_id}/{namespace?}', [ 'uses' => 'SiteController@mediaFileDownload', 'as' => 'site.media-file-download' ]);
Route::get('ref-books','SiteController@refBooks')->name('site.ref_books');

// Authentication Routes...
Route::get('login/{layout_type?}', 'Auth\LoginController@getLogin')->name('user.login');
Route::post('login', 'Auth\LoginController@postLogin');

Route::get('login-otp/{phone?}', 'Auth\LoginController@getLoginOtp')->name('login.otp');
Route::post('login-otp', 'Auth\LoginController@postLoginOtp')->name('login.post_otp');
Route::get('reset-session/{return?}', 'Auth\LoginController@resetOtpSession')->name('login.reset_otp_session');

Route::get('logout', function(){

  if(Auth::check()) {
    $user = Auth::user();
    $user->is_loggedin = 'no';
    $user->save();
    flash(getPhrase('success'),getPhrase('logged_out_successfully'),'success');
  }

  Auth::logout();
  Session::flush();
  //return redirect(URL_USERS_LOGIN);
  return redirect()->route('site.home');
});

Route::get('parent-logout', function(){
    if(Auth::check())
        flash('Oops..!',getPhrase('parents_module_is_not_available'),'error');
    Auth::logout();
    return redirect(URL_USERS_LOGIN);
});

// Route::get('auth/logout', 'Auth\LoginController@getLogout');

// Registration Routes...
Route::get('register/{role?}', 'Auth\RegisterController@getRegister')->name('user.register');
Route::post('register', 'Auth\RegisterController@postRegister');
Route::get('user/confirmation/{slug}','Auth\LoginController@confirmUser');

Route::match(['get', 'post'], 'register-otp', 'Auth\RegisterController@getOtpRegister')->name('user-otp.register');
// Route::post('register-otp', 'Auth\RegisterController@postOtpRegister');

// Forgot Password Routes...
// Route::get('forgot-password', 'PasswordController@postEmail');
Route::get('password/reset/{slug?}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::post('users/forgot-password', 'Auth\AuthController@resetUsersPassword');


Route::get('languages/list', 'NativeController@index');
Route::get('languages/getList', [ 'as'   => 'languages.dataTable',
     'uses' => 'NativeController@getDatatable']);

Route::get('languages/add', 'NativeController@create');
Route::post('languages/add', 'NativeController@store');
Route::get('languages/edit/{slug}', 'NativeController@edit');
Route::patch('languages/edit/{slug}', 'NativeController@update');
Route::delete('languages/delete/{slug}', 'NativeController@delete');

Route::get('languages/make-default/{slug}', 'NativeController@changeDefaultLanguage');
Route::get('languages/update-strings/{slug}', 'NativeController@updateLanguageStrings');
Route::patch('languages/update-strings/{slug}', 'NativeController@saveLanguageStrings');



//Users
Route::get('users/staff/{role?}', 'UsersController@index');
Route::get('users/create', 'UsersController@create');
Route::delete('users/delete/{slug}', 'UsersController@delete');
Route::post('users/create/{role?}', 'UsersController@store');
Route::get('users/edit/{slug}', 'UsersController@edit');
Route::patch('users/edit/{slug}', 'UsersController@update');
Route::get('users/profile/{slug}', 'UsersController@show');

Route::get('users-list/{type?}/{type_id?}', 'UsersController@index');

Route::get('users/profile/{slug}', 'UsersController@show');
Route::get('users/details/{slug}', 'UsersController@details');

Route::get('users/settings/{slug}', 'UsersController@settings');
Route::patch('users/settings/{slug}', 'UsersController@updateSettings');

Route::get('users-change-password/{slug}', 'UsersController@changePassword');
Route::patch('users-change-password/{slug}', 'UsersController@updatePassword');

Route::get('import-users/import','UsersController@importUsers');
Route::post('import-users/import','UsersController@readExcel');

Route::get('users/import-report','UsersController@importResult');

Route::get('users/list/getList/{type?}/{type_id?}', [ 'as'   => 'users.dataTable',
    'uses' => 'UsersController@getDatatable']);

Route::get('users/parent-details/{slug}', 'UsersController@viewParentDetails');
Route::patch('users/parent-details/{slug}', 'UsersController@updateParentDetails');
Route::post('users/search/parent', 'UsersController@getParentsOnSearch');

Route::match(['get', 'post'], 'users-permission/set-permissions/{user_slug}', 'UsersController@setPermissions')->name('users.set_permissions');

// Route::match(['get', 'post'], 'exams/quiz/update-batches/{slug}', 'QuizController@updateBatches')->name('exams.quiz.update_batches');
// Route::get('users/list/getList/{role_name?}', 'UsersController@getDatatable');

            //////////////////////
            //Parent Controller //
            //////////////////////
Route::get('parent/children', 'ParentsController@index');
Route::get('parent/children/list', 'ParentsController@index');
Route::get('parent/children/getList/{slug}', 'ParentsController@getDatatable');
Route::get('children/analysis', 'ParentsController@childrenAnalysis');

                    /////////////////////
                    // Master Settings //
                    /////////////////////


//subjects
Route::get('mastersettings/subjects', 'SubjectsController@index')->name('mastersettings.subjects');
Route::get('mastersettings/subjects/add', 'SubjectsController@create');
Route::post('mastersettings/subjects/add', 'SubjectsController@store');
Route::get('mastersettings/subjects/edit/{slug}', 'SubjectsController@edit');
Route::patch('mastersettings/subjects/edit/{slug}', 'SubjectsController@update');
Route::delete('mastersettings/subjects/delete/{id}', 'SubjectsController@delete');
Route::get('mastersettings/subjects/getList', [ 'as'   => 'subjects.dataTable',
    'uses' => 'SubjectsController@getDatatable']);

Route::get('mastersettings/subjects/import', 'SubjectsController@import');
Route::post('mastersettings/subjects/import', 'SubjectsController@readExcel');

//Chapters
Route::get('mastersettings/chapters', 'ChaptersController@index')->name('mastersettings.chapters_index');
Route::get('mastersettings/chapters/add', 'ChaptersController@create')->name('mastersettings.chapters_create');
Route::post('mastersettings/chapters/add', 'ChaptersController@store')->name('mastersettings.chapters_store');
Route::get('mastersettings/chapters/edit/{slug}', 'ChaptersController@edit')->name('mastersettings.chapters_edit');
Route::patch('mastersettings/chapters/edit/{slug}', 'ChaptersController@update')->name('mastersettings.chapters_update');
Route::delete('mastersettings/chapters/delete/{id}', 'ChaptersController@delete')->name('mastersettings.chapters_delete');
Route::get('mastersettings/chapters/getList', [ 'as'   => 'chapters.dataTable',
    'uses' => 'ChaptersController@getDatatable']);



Route::get('mastersettings/chapters/import', 'ChaptersController@import')->name('mastersettings.chapters_import');
Route::post('mastersettings/chapters/import', 'ChaptersController@readExcel')->name('mastersettings.chapters_import_read');

//Topics
Route::get('mastersettings/topics', 'TopicsController@index')->name('mastersettings.topics');
Route::get('mastersettings/topics/add', 'TopicsController@create');
Route::post('mastersettings/topics/add', 'TopicsController@store');
Route::get('mastersettings/topics/edit/{slug}', 'TopicsController@edit');
Route::patch('mastersettings/topics/edit/{slug}', 'TopicsController@update');
Route::delete('mastersettings/topics/delete/{id}', 'TopicsController@delete');
Route::get('mastersettings/topics/getList', [ 'as'   => 'topics.dataTable',
    'uses' => 'TopicsController@getDatatable']);

// Route::get('mastersettings/topics/get-parents-topics/{subject_id}', 'TopicsController@getParentTopics');
Route::get('mastersettings/topics/get-parents-topics/{subject_id}/{chapter_id}', 'TopicsController@getChapterTopics');
Route::get('mastersettings/topics/get-parents-topics-exam/{subject_id}/{chapter_id}', 'TopicsController@getChapterTopicsExam');
Route::get('mastersettings/chapters/get-parents-chapters/{subject_id}', 'TopicsController@getSubjectChapters')->name('mastersettings.chapters_parent_chapters');

Route::get('mastersettings/subjects/get-institute-subjects/{institute_id}', 'TopicsController@getInstituteSubjects')->name('mastersettings.institute_subjects');

Route::get('student/lms/get-parents-topics/{subject_id}/{chapter_id}', 'TopicsController@getChapterParentTopics');
Route::get('student/lms/get-sub-topics/{subject_id}/{chapter_id}/{topic_id}', 'TopicsController@getChapterSubTopics');

Route::get('mastersettings/topics/import', 'TopicsController@import');
Route::post('mastersettings/topics/import', 'TopicsController@readExcel');

Route::get('mastersettings/class/get-batch/{class_id}', 'OnlineclassesController@getBatches');
Route::get('mastersettings/class/get-user/{user_id}', 'OnlineclassesController@getUser');
Route::get('mastersettings/class/get-faculty/{batch_id}', 'OnlineclassesController@getFacultyBatches');

Route::get('mastersettings/class/get-faculty-subjects/{faculty_id}', 'OnlineclassesController@getFacultySubjects');

Route::get('onlineclasses/classend/alerts', 'OnlineclassesController@classEndAlerts')->name('onlineclasses.classend_alerts');

                    ////////////////////////
                    // EXAMINATION SYSTEM //
                    ////////////////////////

//Question bank
Route::get('exams/questionbank', 'QuestionBankController@index')->name('exams.questionbank');
Route::get('exams/questionbank/add-question/{slug}', 'QuestionBankController@create')->name('exams.addquestion');
Route::get('exams/questionbank/view/{slug}', 'QuestionBankController@show');

Route::post('exams/questionbank/add/{slug?}', 'QuestionBankController@store');
Route::get('exams/questionbank/edit-question/{slug}', 'QuestionBankController@edit')->name('exams.editquestion');
Route::patch('exams/questionbank/edit/{slug}', 'QuestionBankController@update');
Route::delete('exams/questionbank/delete/{id}', 'QuestionBankController@delete');
Route::get('exams/questionbank/getList',  'QuestionBankController@getDatatable')->name('exams.questionbank.getList');

Route::get('exams/questionbank/view-question/{subject_slug}/{slug}', 'QuestionBankController@view')->name('exams.questionbank.view-question');

Route::get('exams/questionbank/getquestionslist/{slug}',
     'QuestionBankController@getQuestions')->name('exams_questionbank.getquestionslist');
Route::get('exams/questionbank/import',  'QuestionBankController@import');
Route::post('exams/questionbank/import',  'QuestionBankController@readExcel');

//Question bank Categories
Route::get('questionbank/categories', 'QuestionBankCategoryController@index');
Route::get('questionbank/categories/add', 'QuestionBankCategoryController@create');
Route::post('questionbank/categories/add', 'QuestionBankCategoryController@store');
Route::get('questionbank/categories/edit/{slug}', 'QuestionBankCategoryController@edit');
Route::patch('questionbank/categories/edit/{slug}', 'QuestionBankCategoryController@update');
Route::delete('questionbank/categories/delete/{slug}', 'QuestionBankCategoryController@delete');
Route::get('questionbank/categories/getList', [ 'as'   => 'questionbankcategories.dataTable',
    'uses' => 'QuestionBankCategoryController@getDatatable']);

//Board Categories
Route::get('exams/boards', 'BoardsController@index');
Route::get('exams/board/add', 'BoardsController@create');
Route::post('exams/board/add', 'BoardsController@store');
Route::get('exams/board/edit/{slug}', 'BoardsController@edit');
Route::patch('exams/board/edit/{slug}', 'BoardsController@update');
Route::delete('exams/board/delete/{slug}', 'BoardsController@delete');
Route::get('exams/board/getList', [ 'as'   => 'boards.dataTable',
    'uses' => 'BoardsController@getDatatable']);

Route::get('exams/ref_books', 'ReferenceBooksController@index');
Route::get('exams/ref_books/add', 'ReferenceBooksController@create');
Route::post('exams/ref_books/add', 'ReferenceBooksController@store');
Route::get('exams/ref_books/edit/{slug}', 'ReferenceBooksController@edit');
Route::patch('exams/ref_books/edit/{slug}', 'ReferenceBooksController@update');
Route::delete('exams/ref_books/delete/{slug}', 'ReferenceBooksController@delete');
Route::get('exams/ref_books/getList', [ 'as'   => 'ref_books.dataTable',
    'uses' => 'ReferenceBooksController@getDatatable']);

Route::get('exams/boards_classes', 'BoardsClassesController@index');
Route::get('exams/boards_classes/add', 'BoardsClassesController@create');
Route::post('exams/boards_classes/add', 'BoardsClassesController@store');
Route::get('exams/boards_classes/edit/{slug}', 'BoardsClassesController@edit');
Route::patch('exams/boards_classes/edit/{slug}', 'BoardsClassesController@update');
Route::delete('exams/boards_classes/delete/{slug}', 'BoardsClassesController@delete');
Route::get('exams/boards_classes/getList', [ 'as'   => 'boards_classes.dataTable',
    'uses' => 'BoardsClassesController@getDatatable']);

Route::get('exams/boards_subjects', 'BoardsSubjectsController@index');
Route::get('exams/boards_subjects/add', 'BoardsSubjectsController@create');
Route::post('exams/boards_subjects/add', 'BoardsSubjectsController@store');
Route::get('exams/boards_subjects/edit/{slug}', 'BoardsSubjectsController@edit');
Route::patch('exams/boards_subjects/edit/{slug}', 'BoardsSubjectsController@update');
Route::delete('exams/boards_subjects/delete/{slug}', 'BoardsSubjectsController@delete');
Route::get('exams/boards_subjects/getList', [ 'as'   => 'boards_subjects.dataTable',
    'uses' => 'BoardsSubjectsController@getDatatable']);

Route::get('exams/boards_chapters', 'BoardsChaptersController@index');
Route::get('exams/boards_chapters/add', 'BoardsChaptersController@create');
Route::post('exams/boards_chapters/add', 'BoardsChaptersController@store');
Route::get('exams/boards_chapters/edit/{slug}', 'BoardsChaptersController@edit');
Route::patch('exams/boards_chapters/edit/{slug}', 'BoardsChaptersController@update');
Route::delete('exams/boards_chapters/delete/{slug}', 'BoardsChaptersController@delete');
Route::get('exams/boards_chapters/getList', [ 'as'   => 'boards_chapters.dataTable',
    'uses' => 'BoardsChaptersController@getDatatable']);

//Subject Logos
Route::get('exams/subjectlogos', 'SubjectLogosController@index');
Route::get('exams/subjectlogos/add', 'SubjectLogosController@create');
Route::post('exams/subjectlogos/add', 'SubjectLogosController@store');
Route::get('exams/subjectlogos/edit/{slug}', 'SubjectLogosController@edit');
Route::patch('exams/subjectlogos/edit/{slug}', 'SubjectLogosController@update');
Route::delete('exams/subjectlogos/delete/{slug}', 'SubjectLogosController@delete');
Route::get('exams/subjectlogos/getList', [ 'as'   => 'subjectlogs.dataTable',
    'uses' => 'SubjectLogosController@getDatatable']);

//Quiz Categories
Route::get('exams/categories', 'QuizCategoryController@index');
Route::get('exams/categories/add', 'QuizCategoryController@create');
Route::post('exams/categories/add', 'QuizCategoryController@store');
Route::get('exams/categories/edit/{slug}', 'QuizCategoryController@edit');

Route::get('exams/student/subject-exams/{slug?}', 'StudentQuizController@subjectExams')->name('student_subject.exams');

Route::patch('exams/categories/edit/{slug}', 'QuizCategoryController@update');
Route::delete('exams/categories/delete/{slug}', 'QuizCategoryController@delete');
Route::get('exams/categories/getList', [ 'as'   => 'quizcategories.dataTable',
    'uses' => 'QuizCategoryController@getDatatable']);

//Student Classes
Route::get('institute/classes', 'StudentClassController@index');
Route::get('institute/classes/add', 'StudentClassController@create');
Route::post('institute/classes/add', 'StudentClassController@store');
Route::get('institute/classes/edit/{slug}', 'StudentClassController@edit');
Route::patch('institute/classes/edit/{slug}', 'StudentClassController@update');
Route::delete('institute/classes/delete/{slug}', 'StudentClassController@delete');
Route::get('institutes/student-classes/getlist', [ 'as'   => 'institute_classes.dataTable',
    'uses' => 'StudentClassController@getDatatable']);

//Courses
Route::get('institute/courses', 'CourseController@index');
Route::get('institute/courses/add', 'CourseController@create');
Route::post('institute/courses/add', 'CourseController@store');
Route::get('institute/courses/edit/{slug}', 'CourseController@edit');
Route::patch('institute/courses/edit/{slug}', 'CourseController@update');
Route::delete('institute/courses/delete/{slug}', 'CourseController@delete');
Route::get('institutes/courses/getlist', [ 'as'   => 'institute_courses.dataTable',
    'uses' => 'CourseController@getDatatable']);

//Permissions
Route::get('permissions', 'PermissionsController@index');
Route::get('permissions/add', 'PermissionsController@create');
Route::post('permissions/add', 'PermissionsController@store');
Route::get('permissions/edit/{id}', 'PermissionsController@edit');
Route::patch('permissions/edit/{id}', 'PermissionsController@update');
Route::delete('permissions/delete/{id}', 'PermissionsController@delete');
Route::get('permissions/getlist', [ 'as'   => 'permissions.dataTable',
    'uses' => 'PermissionsController@getDatatable']);

// Competitive Exams Types
Route::get('competitive-exam-types', 'CompetitiveExamTypesController@index');
Route::get('competitive-exam-types/add', 'CompetitiveExamTypesController@create');
Route::post('competitive-exam-types/add', 'CompetitiveExamTypesController@store');
Route::get('competitive-exam-types/edit/{id}', 'CompetitiveExamTypesController@edit');
Route::patch('competitive-exam-types/edit/{id}', 'CompetitiveExamTypesController@update');
Route::delete('competitive-exam-types/delete/{id}', 'CompetitiveExamTypesController@delete');
Route::get('competitive-exam-types/getlist', [ 'as'   => 'competitive-exam-types.dataTable',
    'uses' => 'CompetitiveExamTypesController@getDatatable']);

// Question Bank Types
Route::get('question-bank-types', 'QuestionBankTypesController@index');
Route::get('question-bank-types/add', 'QuestionBankTypesController@create');
Route::post('question-bank-types/add', 'QuestionBankTypesController@store');
Route::get('question-bank-types/edit/{id}', 'QuestionBankTypesController@edit');
Route::patch('question-bank-types/edit/{id}', 'QuestionBankTypesController@update');
Route::delete('question-bank-types/delete/{id}', 'QuestionBankTypesController@delete');
Route::get('question-bank-types/getlist', [ 'as'   => 'question-bank-types.dataTable',
    'uses' => 'QuestionBankTypesController@getDatatable']);

// Quiz Student Categories
Route::get('exams/student/categories', 'StudentQuizController@index');
Route::get('exams/student/subjects', 'StudentQuizController@subjects');

Route::get('exams/student/learning-subjects', 'StudentQuizController@learningSubjects');
Route::get('exams/student/subject-details/{slug}', 'StudentQuizController@learningSubjects');

Route::get('exams/student/exams/{slug?}/{type?}', 'StudentQuizController@exams');
Route::get('exams/student/quiz/getList/{slug?}/{type?}', 'StudentQuizController@getDatatable');

Route::get('multiple-student/exams', 'StudentQuizController@multipleSubjectExams')->name('multisubject.exams');
Route::get('multiple-student/exams-list', 'StudentQuizController@multipleSubjectExamsGetList')->name('student.multisubject.examsgetlist');

Route::get('exams/student/quiz/take-exam/{slug?}/{lms_slug?}', 'StudentQuizController@instructions');
Route::post('exams/student/start-exam/{slug}/{lms_slug?}', 'StudentQuizController@startExam');
Route::get('exams/student/start-exam/{slug}/{lms_slug?}', 'StudentQuizController@index');
Route::get('exams/student/subject-exams/{slug?}', 'StudentQuizController@subjectExams')->name('student_subject.exams');

Route::post('exams/student/finish-exam/{slug}', 'StudentQuizController@finishExam');
Route::get('exams/student/reports/{slug}', 'StudentQuizController@reports');
Route::post('Report-question/{question_id}','StudentQuizController@reportIssue');
Route::get('reported_issues/index','StudentQuizController@issuesIndex');
Route::get('reported_issues/get_issues_table/{id}','StudentQuizController@getIssuesDatatable');

Route::get('exams/student/exam-attempts/{user_slug}/{exam_slug?}', 'StudentQuizController@examAttempts');
Route::get('exams/student/get-exam-attempts/{user_slug}/{exam_slug?}', 'StudentQuizController@getExamAttemptsData');

Route::get('student/analysis/by-exam/{user_slug}', 'StudentQuizController@examAnalysis');
Route::get('student/analysis/get-by-exam/{user_slug}', 'StudentQuizController@getExamAnalysisData');

Route::get('student/analysis/by-subject/{user_slug}/{exam_slug?}/{results_slug?}', 'StudentQuizController@subjectAnalysisInExam');
Route::get('student/analysis/subject/{user_slug}', 'StudentQuizController@overallSubjectAnalysis');

//Student Reports
Route::get('student/exam/answers/{quiz_slug}/{result_slug}', 'ReportsController@viewExamAnswers');
Route::get('student/exam/answers/subject_report/{quiz_slug}/{result_slug}', 'ReportsController@viewSubjectWiseReport');


//Quiz
Route::get('exams/quizzes', 'QuizController@index')->name('exams.quizzes');
Route::get('exams/test-series', 'QuizController@testSeries')->name('exams.test_series');
Route::get('exams/live-quizzes', 'QuizController@liveQuizzes')->name('exams.live_quizzes');

Route::get('exams/quiz/add/{exam_type?}', 'QuizController@create');
Route::post('exams/quiz/add/{exam_type?}', 'QuizController@store');

Route::get('exams/quiz/edit/{slug}', 'QuizController@edit');
Route::patch('exams/quiz/edit/{slug}', 'QuizController@update');
Route::delete('exams/quiz/delete/{slug}', 'QuizController@delete');
Route::get('exams/quiz/getList/{type?}', 'QuizController@getDatatable')->name('exams.quiz.getlist');

Route::get('exams/quiz/update-questions/{slug}', 'QuizController@updateQuestions');
Route::post('exams/quiz/update-questions/{slug}', 'QuizController@storeQuestions');

Route::match(['get', 'post'], 'exams/quiz/update-batches/{slug}', 'QuizController@updateBatches')->name('exams.quiz.update_batches');
// Route::post('exams/quiz/update-batches/{slug}', 'QuizController@updateBatches');


Route::post('exams/quiz/get-questions', 'QuizController@getSubjectData');

//Certificates controller
Route::get('result/generate-certificate/{slug}', 'CertificatesController@getCertificate');

//Student Paid Content
Route::get('package/student-paid-content', 'StudentPaidContentController@index')->name('studentpaidcontent.index');
Route::get('package/student-paid-content/add', 'StudentPaidContentController@create');
Route::post('package/student-paid-content/add', 'StudentPaidContentController@store');
Route::get('package/student-paid-content/edit/{slug}', 'StudentPaidContentController@edit')->name('studentpaidcontent.edit');
Route::patch('package/student-paid-content/edit/{slug}', 'StudentPaidContentController@update');
Route::delete('package/student-paid-content/delete/{slug}', 'StudentPaidContentController@delete');
Route::get('package/student-paid-content/getList', 'StudentPaidContentController@getDatatable')->name('examseries.dataTable');
// Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-lmsseries/{slug}/{class?}', 'StudentPaidContentController@updateSeriesCourses')->name('studentpaidcontent.lmscontents');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-courses/{slug}', 'StudentPaidContentController@updateCourses')->name('studentpaidcontent.courses');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-lmsseries/{slug}', 'StudentPaidContentController@updateLmsSeries')->name('studentpaidcontent.series');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-lmsnotes/{slug}', 'StudentPaidContentController@updateLmsNotes')->name('studentpaidcontent.notes');
Route::match(['get', 'post'], 'package/studentpaidcontent/update-package-exams/{slug}/{type}', 'StudentPaidContentController@updateExams')->name('studentpaidcontent.exams');

Route::get('student/paid-content-types', 'StudentQuizController@displayPaidContentTypes')->name('student.paid_content_types');
Route::get('paid-content/{package_slug?}/{type?}/{type_slug?}', 'StudentQuizController@studentPaidContent')->name('student.paid_content');

Route::get('paid-content-subjectwise/{package_slug?}/{type}/{subject_slug?}/{type_slug?}', 'StudentQuizController@studentPaidContentSubjectwise')->name('student.paid_content_subjectwise');

Route::get('student-exams/{type}', 'StudentQuizController@studentPaidExams')->name('student.paid_exams');
Route::get('student-paid/quiz/getList/{type}', 'StudentQuizController@getStudentPaidExams');

Route::post('paid-content/get-lmsseries', 'StudentPaidContentController@getLmsSeries')->name('paid_content.lmsseries');
Route::post('paid-content/get-lmsnotes', 'StudentPaidContentController@getLmsNotes')->name('paid_content.lmsnotes');
Route::post('get-lms-groups', 'LmsGroupController@getLmsGroups')->name('lms.groups');

Route::post('paid-content/get-chapter-tests', 'StudentPaidContentController@getChapterTests')->name('paid_content.chapters-tests');
Route::post('paid-content/get-subject-tests', 'StudentPaidContentController@getSubjectTests')->name('paid_content.subject-tests');
Route::post('paid-content/get-previousyear-tests', 'StudentPaidContentController@getPreviousYearTests')->name('paid_content.previousyear-tests');
Route::post('paid-content/get-grand-tests', 'StudentPaidContentController@getGrandTests')->name('paid_content.grand-tests');

//Exam Series
Route::get('exams/exam-series', 'ExamSeriesController@index');
Route::get('exams/exam-series/add', 'ExamSeriesController@create');
Route::post('exams/exam-series/add', 'ExamSeriesController@store');
Route::get('exams/exam-series/edit/{slug}', 'ExamSeriesController@edit');
Route::patch('exams/exam-series/edit/{slug}', 'ExamSeriesController@update');
Route::delete('exams/exam-series/delete/{slug}', 'ExamSeriesController@delete');
Route::get('exams/exam-series/getList', 'ExamSeriesController@getDatatable')->name('examseries.dataTable');

//EXAM SERIES STUDENT LINKS
Route::get('exams/student-exam-series/list', 'ExamSeriesController@listSeries');
Route::get('exams/student-exam-series/{slug}', 'ExamSeriesController@viewItem');

Route::match(['get', 'post'], 'exams/series/update-series-courses/{slug}/{class?}', 'ExamSeriesController@updateSeriesCourses')->name('examseries.courses');



Route::get('exams/exam-series/update-series/{slug}', 'ExamSeriesController@updateSeries');
Route::post('exams/exam-series/update-series/{slug}', 'ExamSeriesController@storeSeries');
Route::post('exams/exam-series/get-exams', 'ExamSeriesController@getExams');
Route::get('payment/cancel', 'ExamSeriesController@cancel');
Route::post('payment/success', 'ExamSeriesController@success');

            /////////////////////
            // PAYMENT REPORTS //
            /////////////////////
Route::get('payments-report/', 'PaymentsController@overallPayments');

 Route::get('payments-report/online/', 'PaymentsController@onlinePaymentsReport');
 Route::get('payments-report/online/{slug}', 'PaymentsController@listOnlinePaymentsReport');
Route::get('payments-report/online/getList/{slug}', 'PaymentsController@getOnlinePaymentReportsDatatable');

Route::get('payments-report/offline/', 'PaymentsController@offlinePaymentsReport');
Route::get('payments-report/offline/{slug}', 'PaymentsController@listOfflinePaymentsReport');
Route::get('payments-report/offline/getList/{slug}', 'PaymentsController@getOfflinePaymentReportsDatatable');
Route::get('payments-report/export', 'PaymentsController@exportPayments');
Route::post('payments-report/export', 'PaymentsController@doExportPayments');

Route::post('payments-report/getRecord', 'PaymentsController@getPaymentRecord');
Route::post('payments/approve-reject-offline-request', 'PaymentsController@approveOfflinePayment');

            //////////////////
            // INSTRUCTIONS  //
            //////////////////

Route::get('exam/instructions/list', 'InstructionsController@index');
Route::get('exam/instructions', 'InstructionsController@index');
Route::get('exams/instructions/add', 'InstructionsController@create');
Route::post('exams/instructions/add', 'InstructionsController@store');
Route::get('exams/instructions/edit/{slug}', 'InstructionsController@edit');
Route::patch('exams/instructions/edit/{slug}', 'InstructionsController@update');
Route::delete('exams/instructions/delete/{slug}', 'InstructionsController@delete');
Route::get('exams/instructions/getList', 'InstructionsController@getDatatable');


//BOOKMARKS MODULE
Route::get('student/bookmarks/{slug}', 'BookmarksController@index');
Route::post('student/bookmarks/add', 'BookmarksController@create');
Route::delete('student/bookmarks/delete/{id}', 'BookmarksController@delete');
Route::delete('student/bookmarks/delete_id/{id}', 'BookmarksController@deleteById');
Route::get('student/bookmarks/getList/{slug}',  'BookmarksController@getDatatable');
Route::post('student/bookmarks/getSavedList',  'BookmarksController@getSavedBookmarks');

//////////////////////////
// Online Classes Module //
/////////////////////////
Route::get('admin/onlineclasses/list', 'OnlineclassesController@index')->name('onlineclasses.index');
Route::get('admin/onlineclasses/attendence', 'OnlineclassesController@attendenceIndex')->name('onlineclasses.attendence');
Route::get('admin/onlineclasses', 'OnlineclassesController@index');
Route::get('admin/onlineclasses/add', 'OnlineclassesController@create');
Route::post('admin/onlineclasses/add', 'OnlineclassesController@store');
Route::get('admin/onlineclasses/edit/{slug}', 'OnlineclassesController@edit');
Route::patch('admin/onlineclasses/edit/{slug}', 'OnlineclassesController@update');
Route::delete('admin/onlineclasses/delete/{slug}', 'OnlineclassesController@delete');
Route::get('admin/onlineclasses/getList', 'OnlineclassesController@getDatatable');
Route::get('admin/onlineclasses/getAttendencetList', 'OnlineclassesController@getAttendenceDatatable');
// Online Classes FOR STUDENT
Route::get('onlineclasses/list', 'OnlineclassesController@usersList');
Route::get('onlineclasses/show/{slug}', 'OnlineclassesController@display');
Route::get('onlineclass/white-board/{slug?}', 'OnlineclassesController@whiteBoard')->name('onlineclasses.whiteboard');
Route::get('student/onlineclasses/attendence/{id}', 'OnlineclassesController@studentAttendence'); // Record attendance.

Route::get('class-attendance/{slug}', 'OnlineclassesController@classAttendence')->name('class.attendence');
Route::get('class-attendance/getList/{slug}', 'OnlineclassesController@classAttendenceList')->name('class.attendence.list');

Route::get('class-absent/{slug}', 'OnlineclassesController@classAbsent')->name('class.absent');
Route::get('class-absent/getList/{slug}', 'OnlineclassesController@classAbsentList')->name('class.absent.list');

Route::match(['get', 'post'], 'onlinceclass/lmsnotes/{slug}', 'OnlineclassesController@assignLmsNotes')->name('onlineclass.lmsnotes');

//////////////////////////
// Notifications Module //
/////////////////////////
Route::get('admin/notifications/list', 'NotificationsController@index');
Route::get('admin/notifications', 'NotificationsController@index');
Route::get('admin/notifications/add', 'NotificationsController@create');
Route::post('admin/notifications/add', 'NotificationsController@store');
Route::get('admin/notifications/edit/{slug}', 'NotificationsController@edit');
Route::patch('admin/notifications/edit/{slug}', 'NotificationsController@update');
Route::delete('admin/notifications/delete/{slug}', 'NotificationsController@delete');
Route::get('admin/notifications/getList', 'NotificationsController@getDatatable');
// notifications FOR STUDENT
Route::get('notifications/list', 'NotificationsController@usersList');
Route::get('notifications/show/{slug}', 'NotificationsController@display');


//BOOKMARKS MODULE
Route::get('toppers/compare-with-topper/{user_result_slug}/{compare_slug?}', 'ExamToppersController@compare');


                        ////////////////
                        // LMS MODULE //
                        ////////////////

//LMS Categories
Route::get('lms/categories', 'LmsCategoryController@index');
Route::get('lms/categories/add', 'LmsCategoryController@create');
Route::post('lms/categories/add', 'LmsCategoryController@store');
Route::get('lms/categories/edit/{slug}', 'LmsCategoryController@edit');
Route::patch('lms/categories/edit/{slug}', 'LmsCategoryController@update');
Route::delete('lms/categories/delete/{slug}', 'LmsCategoryController@delete');
Route::get('lms/categories/getList', [ 'as'   => 'lmscategories.dataTable',
    'uses' => 'LmsCategoryController@getDatatable']);

//LMS Groups
Route::get('lms-groups', 'LmsGroupController@index')->name('lms-groups.index');
Route::get('lms/groups/add', 'LmsGroupController@create');
Route::get('lms-groups/show/{slug}/{subject_slug?}', 'LmsGroupController@show')->name('lms-groups.show');
Route::post('lms/groups/add', 'LmsGroupController@store');
Route::get('lms/groups/edit/{slug}', 'LmsGroupController@edit');
Route::patch('lms/groups/edit/{slug}', 'LmsGroupController@update');
Route::delete('lms/groups/delete/{slug}', 'LmsGroupController@delete');
Route::get('lms-groups/getGroupsList', [ 'as'   => 'lmsgroups.dataTable',
    'uses' => 'LmsGroupController@getDatatable']);
Route::get('lms/groups/update-series/{slug}', 'LmsGroupController@updateSeries')->name('lmsgroups.updateseries');
Route::post('lms/groups/update-series/{slug}', 'LmsGroupController@updateSeries');
Route::get('lms-groups-subject-series/{subject_slug}', 'LmsGroupController@lmsGroupSubjectSeries')->name('lms-groups-subject.series');

//LMS Contents
Route::get('lms/content', 'LmsContentController@index')->name('lms.content');
Route::get('lms/content/add', 'LmsContentController@create');
Route::post('lms/content/add', 'LmsContentController@store');
Route::get('lms/content/edit/{slug}', 'LmsContentController@edit');
Route::patch('lms/content/edit/{slug}', 'LmsContentController@update');
Route::delete('lms/content/delete/{slug}', 'LmsContentController@delete');
Route::get('lms/content/getList', [ 'as'   => 'lmscontent.dataTable',
    'uses' => 'LmsContentController@getDatatable']);
Route::get('lms/content/{slug}/{content_slug?}', 'LmsContentController@viewItem');



//LMS Series
Route::get('lms-series/{slug?}', 'LmsSeriesController@index')->name('lms.series');
Route::get('lms/series/add', 'LmsSeriesController@create');
Route::post('lms/series/add', 'LmsSeriesController@store');
Route::get('lms/series/edit/{slug}', 'LmsSeriesController@edit');
Route::patch('lms/series/edit/{slug}', 'LmsSeriesController@update');
Route::delete('lms/series/delete/{slug}', 'LmsSeriesController@delete');
Route::get('lms/series/getList', 'LmsSeriesController@getDatatable')->name('lmsseries.dataTable');
Route::match(['get', 'post'], 'lms/change-content-order/{slug}', 'LmsSeriesController@changeContentOrder')->name('lmsseries.chang_content_order');

//LMS Notes
Route::get('lms/notes', 'LmsNotesController@index')->name('lms.notes');
Route::get('lms/notes/add', 'LmsNotesController@create');
Route::post('lms/notes/add', 'LmsNotesController@store');
Route::get('lms/notes/edit/{slug}', 'LmsNotesController@edit');
Route::patch('lms/notes/edit/{slug}', 'LmsNotesController@update');
Route::delete('lms/notes/delete/{slug}', 'LmsNotesController@delete');
Route::get('lms/notes/getList', 'LmsNotesController@getDatatable')->name('lmsnotes.dataTable');
Route::get('lms/prew-notes/{slug}', 'LmsNotesController@previewNotes')->name('lms.preview_notes');

//LMS SERIES STUDENT LINKS
Route::get('lms/exam-series/list', 'LmsSeriesController@listSeries');
Route::get('lms/exam-series/{slug}', 'LmsSeriesController@viewItem');




Route::get('lms/series/update-series/{slug}', 'LmsSeriesController@updateSeries');
Route::post('lms/series/update-series/{slug}', 'LmsSeriesController@storeSeries');

Route::get('lms/series/update-series-exams/{slug}', 'LmsSeriesController@updateSeriesExams');
Route::post('lms/series/update-series-exams/{slug}', 'LmsSeriesController@storeSeriesExams');

Route::match(['get', 'post'], 'lms/series/update-series-courses/{slug}/{course?}', 'LmsSeriesController@updateSeriesCourses')->name('lmsseries.courses');

Route::post('lms/series/get-series', 'LmsSeriesController@getSeries');
Route::get('payment/cancel', 'LmsSeriesController@cancel');
Route::post('payment/success', 'LmsSeriesController@success');

//LMS Student view
Route::get('learning-management/categories', 'StudentLmsController@index');
Route::get('learning-management/view/{slug}', 'StudentLmsController@viewCategoryItems');
Route::get('learning-management/subjects', 'StudentLmsController@subjects')->name('studentlms.subjects');
Route::get('learning-management/view-subject/{slug}/{series_slug?}', 'StudentLmsController@viewSubjectItems')->name('studentlms.subjectitems');

Route::get('learning-management-notes/view-subject/{slug}/{series_slug?}', 'StudentLmsController@viewSubjectItemsNotes')->name('studentlmsnotes.subjectitems');

Route::get('learning-management/series', 'StudentLmsController@series');
Route::get('learning-management/series/{slug}/{content_slug?}', 'StudentLmsController@viewItem');
Route::get('user/paid/{slug}/{content_slug}', 'StudentLmsController@verifyPaidItem');
Route::get('learning-management/content/{req_content_type}', 'StudentLmsController@content');
Route::get('learning-management/content/show/{slug}', 'StudentLmsController@showContent');



//Payments Controller
Route::get('payments/list/{slug}', 'PaymentsController@index');
Route::get('payments/getList/{slug}', 'PaymentsController@getDatatable');

Route::get('payments/checkout/{type}/{slug}', 'PaymentsController@checkout')->name('payments.checkout');
Route::get('payments/paynow/{slug}', 'DashboardController@index');
Route::post('payments/paynow/{slug}', 'PaymentsController@paynow');
Route::post('payments/paypal/status-success','PaymentsController@paypal_success');
Route::get('payments/paypal/status-cancel', 'PaymentsController@paypal_cancel');

Route::post('payments/payu/status-success','PaymentsController@payu_success');
Route::post('payments/payu/status-cancel', 'PaymentsController@payu_cancel');
Route::post('payments/offline-payment/update', 'PaymentsController@updateOfflinePayment');




                        ////////////////////////////
                        // SETTINGS MODULE //
                        ///////////////////////////


//LMS Categories
Route::get('mastersettings/settings/', 'SettingsController@index');
Route::get('mastersettings/settings/index', 'SettingsController@index');
Route::get('mastersettings/settings/add', 'SettingsController@create');
Route::post('mastersettings/settings/add', 'SettingsController@store');
Route::get('mastersettings/settings/edit/{slug}', 'SettingsController@edit');
Route::patch('mastersettings/settings/edit/{slug}', 'SettingsController@update');
Route::get('mastersettings/settings/view/{slug}', 'SettingsController@viewSettings');
Route::get('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@addSubSettings');
Route::post('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@storeSubSettings');
Route::patch('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@updateSubSettings');

Route::get('mastersettings/settings/getList', [ 'as'   => 'mastersettings.dataTable',
     'uses' => 'SettingsController@getDatatable']);

                        ////////////////////////////
                        // EMAIL TEMPLATES MODULE //
                        ///////////////////////////

//LMS Categories
Route::get('email/templates', 'EmailTemplatesController@index');
Route::get('email/templates/add', 'EmailTemplatesController@create');
Route::post('email/templates/add', 'EmailTemplatesController@store');
Route::get('email/templates/edit/{slug}', 'EmailTemplatesController@edit');
Route::patch('email/templates/edit/{slug}', 'EmailTemplatesController@update');
Route::delete('email/templates/delete/{slug}', 'EmailTemplatesController@delete');
Route::get('email/templates/getList', [ 'as'   => 'emailtemplates.dataTable',
    'uses' => 'EmailTemplatesController@getDatatable']);


//Coupons Module
Route::get('coupons/list', 'CouponcodesController@index');
Route::get('coupons/add', 'CouponcodesController@create');
Route::post('coupons/add', 'CouponcodesController@store');
Route::get('coupons/edit/{slug}', 'CouponcodesController@edit');
Route::patch('coupons/edit/{slug}', 'CouponcodesController@update');
Route::delete('coupons/delete/{slug}', 'CouponcodesController@delete');
Route::get('coupons/getList/{slug?}', 'CouponcodesController@getDatatable');

Route::get('coupons/get-usage', 'CouponcodesController@getCouponUsage');
Route::get('coupons/get-usage-data', 'CouponcodesController@getCouponUsageData');
Route::post('coupons/update-questions/{slug}', 'CouponcodesController@storeQuestions');


Route::post('coupons/validate-coupon', 'CouponcodesController@validateCoupon');


//Feedback Module
Route::get('feedback/list', 'FeedbackController@index');
Route::get('feedback/view-details/{slug}', 'FeedbackController@details');
Route::get('feedback/send', 'FeedbackController@create');
Route::post('feedback/send', 'FeedbackController@store');
Route::delete('feedback/delete/{slug}', 'FeedbackController@delete');
Route::get('feedback/getlist', 'FeedbackController@getDatatable');

//SMS Module

Route::get('sms/index', 'SMSAgentController@index');
Route::post('sms/send', 'SMSAgentController@sendSMS');

                        /////////////////////
                        // MESSAGES MODULE //
                        /////////////////////


Route::group(['prefix' => 'messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}/{request_id?}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}/{request_id?}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});

                        /////////////////////
                        // PRIVACY POLICY  //
                        /////////////////////



Route::get('site/{slug?}', 'SiteController@sitePages');
// privacy-policy


                         ////////////////////
                         // UPDATE PATCHES //
                         ////////////////////
 // Route::get('updates/patch1', 'UpdatesController@patch1');
 // Route::get('updates/patch2', 'UpdatesController@patch2');
 // Route::get('updates/patch3', 'UpdatesController@patch3');
 // Route::get('updates/patch4', 'UpdatesController@patch4');
 // Route::get('update/application','UpdatesController@updateDatabase');

Route::get('refresh-csrf', function(){
    return csrf_token();
});


//Fornt End Part
 Route::get('exams/list', 'FrontendExamsController@examsList');
Route::get('exams/start-exam/{slug}', 'FrontendExamsController@startExam');
Route::post('exams/finish-exam/{slug}', 'FrontendExamsController@finishExam');

//Resume Exam
Route::post('resume/examdata/save','StudentQuizController@saveResumeExamData');
Route::get('exam-types','QuizController@examTypes');
Route::get('edit/exam-type/{code}','QuizController@editExamType');
Route::post('update/exam-type/{code}','QuizController@updateExamType');
Route::post('razoapay/success','PaymentsController@razorpaySuccess');


//Theme Updates
Route::post('subscription/email','SiteController@saveSubscription');
Route::get('front-end/get-class-courses/{class_id}','SiteController@getClassCourses');


//Subscribed Users
Route::get('subscribed/users','UsersController@SubscribedUsers');
Route::get('subscribed/users/data','UsersController@SubscribersData');

//All Exam categories
Route::get('exam/categories/{slug?}','SiteController@frontAllExamCats');
Route::get('practice-exams/{slug?}','SiteController@frontAllExamCats');
Route::get('LMS/all-categories/{slug?}','SiteController@forntAllLMSCats');
Route::get('LMS/contents/{slug}','SiteController@forntLMSContents');
Route::get('download/lms/contents/{slug}','SiteController@downloadLMSContent');
Route::get('lms/video/{slug}/{cat_id?}','SiteController@viewVideo');
Route::get('contact-us',function(){

      $view_name = getTheme().'::site.contact-us';
      $data['active_class']  = "contact-us";
      $data['title']  = getPhrase('contact_us');
        return view($view_name,$data);
});
Route::post('send/contact-us/details','SiteController@ContactUs');
Route::post('get/series/contents','SiteController@getSeriesContents');

//Themes

Route::get('themes/list','SiteThemesController@index');
Route::get('themes/data','SiteThemesController@getDatatable');
Route::get('make/default/theme/{id}','SiteThemesController@makeDefault');
Route::get('theme/settings/{slug}','SiteThemesController@viewSettings');
Route::post('theme/update/settings/{slug}','SiteThemesController@updateSubSettings');

//Institute
Route::get('institute/registration','InstituteController@instituteRegistration');
Route::post('add/institute','InstituteController@addInstitute');
Route::get('institutes/{assigned_type?}/','InstituteController@viewInstitutes');
Route::get('institutes_all/getlist','InstituteController@getDatatable');
Route::get('instutite/details/{id}','InstituteController@details');
Route::post('change/institute-status','InstituteController@updateInstitute');
Route::get('edit/institute/{id}', 'InstituteController@edit');
Route::patch('edit/institute/{id}', 'InstituteController@update');
Route::get('institute/setpermissions/add/{id}', 'InstituteController@setPermissions');
Route::patch('institute/setpermissions/add/{id}', 'InstituteController@setPermissions');


//Institute Associates
Route::get('associate/institute/{id}','InstituteAssosiatesController@index');
Route::post('add/associate','InstituteAssosiatesController@addAssociates');

//Institute Branches
Route::get('institutes/branches', 'InstituteBranchController@index');
Route::get('institutes/branches/add', 'InstituteBranchController@create');
Route::post('institutes/branches/add', 'InstituteBranchController@store');
Route::get('institutes/branches/edit/{slug}', 'InstituteBranchController@edit');
Route::patch('institutes/branches/edit/{slug}', 'InstituteBranchController@update');
Route::delete('institutes/branches/delete/{id}', 'InstituteBranchController@delete');
Route::get('institutes/branches/getlist', 'InstituteBranchController@getDatatable');


//Batches Module
Route::get('batches/list', 'BatchesController@index')->name('batches.index');
Route::get('batches/add', 'BatchesController@create');
Route::post('batches/add', 'BatchesController@store');
Route::get('batches/edit/{slug}', 'BatchesController@edit');
Route::patch('batches/edit/{slug}', 'BatchesController@update');
Route::delete('batches/delete/{slug}', 'BatchesController@delete');
Route::get('batches/getList', 'BatchesController@getDatatable');

Route::get('batches/addstudents/{batch_id}', 'BatchesController@addStudents');
Route::post('batches/getstudents', 'BatchesController@getStudents');
Route::post('batches/store/students', 'BatchesController@storeStudents');
Route::get('batches/students/{batch_id}', 'BatchesController@viewBatchStudents');
Route::post('update/batches/students', 'BatchesController@updateBatchStudents');
Route::post('available/batch/students', 'BatchesController@getBatchStudents');

Route::match(['get', 'post'], 'batches/addlms/{batch_id}', 'BatchesController@addLms')->name('batch.addlms');
Route::post('batches/getlms', 'BatchesController@getLms');
Route::post('batches/store/lms', 'BatchesController@storeLms');

Route::match(['get', 'post'], 'batches/addlmsnotes/{batch_id}', 'BatchesController@addLmsnotes')->name('batch.addlmsnotes');

Route::get('batches/report/{batch_id}/{exam_slug?}', 'BatchesController@batchReport')->name('batches.report');

Route::match(['get', 'post'], 'batches/email-alerts', 'BatchesController@emsilAlerts')->name('batch.email-alerts');
Route::match(['get', 'post'], 'batches/sms-alerts', 'BatchesController@smsAlerts')->name('batch.sms-alerts');
Route::match(['get', 'post'], 'batches/email-sms-alerts', 'BatchesController@emsilSmsAlerts')->name('batch.email-sms-alerts');

//Fee Module
Route::get('pay/fee', 'FeePayController@index');
Route::post('add/fee', 'FeePayController@payFee');
Route::post('fee/batch/students', 'FeePayController@getStudents');
Route::post('batch/students-details', 'FeePayController@studentDetails');
Route::post('batch/add-discount', 'FeePayController@addDiscount');

//Fee Reports
Route::get('fee-reports/batch', 'FeePayController@batchReports');
Route::post('batch/fee-reports', 'FeePayController@feePaidBatchStudents');
Route::post('print/fee-reports', 'FeePayController@printReports');
Route::get('institute/fee-reports', 'FeePayController@instituteReports');
Route::post('get/institute/batches', 'FeePayController@instituteBatches');
Route::get('fee-paid-reports', 'FeePayController@dateReports');
Route::post('get/daily-reports', 'FeePayController@getDailyReports');


Route::get('update/application', 'FeaturesUpdatesController@updateDatabase');

//Front End Batches Details
Route::get('batches/details/{institute_id}', 'SiteController@batchesDetails');

Route::get('notes/student/{slug?}', 'StudentQuizController@LmsNotes')->name('student.lms_notes');


Route::match(['get', 'post'], '/system-reset', ['uses' => 'UsersController@systemReset', 'as' => 'users.system-reset']);
Route::post('class-courses', 'SiteController@getCourses')->name('class.courses');

//Packages
Route::get('packages/list', 'PackagesController@index')->name('packages.list');
Route::get('packages/add', 'PackagesController@create')->name('packages.add');
Route::post('packages/add', 'PackagesController@store')->name('packages.add');
Route::get('packages/edit/{slug}', 'PackagesController@edit')->name('packages.edit');
Route::patch('packages/edit/{slug}', 'PackagesController@update')->name('packages.edit');
Route::delete('packages/delete/{slug}', 'PackagesController@delete')->name('packages.delete');
Route::get('packages/getList/{slug?}', 'PackagesController@getDatatable')->name('packages.getList');
Route::get('renew-packages/{slug?}', 'PackagesController@renewPackage')->name('packages.renew');
Route::post('renew-packages', 'PackagesController@storeRenewRequests')->name('package_renew.request');

Route::get('renewal-requests', 'PackagesController@renewalRequests')->name('packages.renewal_requests');
Route::get('get-renewal-requests', 'PackagesController@getRenewalRequests')->name('packages.get_renewal_requests');
//Route::post('file-manager-custom/upload', '\Alexusmai\LaravelFileManager\Controllers\FileManagerController@upload');

Route::get('exams/dashboard', 'QuizController@dashboard')->name('exams.dashboard');

Route::get('exams/questionbank-management', 'QuizController@questionBankManagement')->name('exams.questionbank.management');
Route::get('exams/topics-directory', 'TopicsController@topicsDirectory')->name('exams.topics.directory');

Route::get('lmsseries/topics-directory', 'LmsSeriesController@lmsSeriesDirectory')->name('lmsseries.directory');

Route::get('online-classes/get-lms/{subject_id}/{chapter_id}', 'OnlineclassesController@getLms')->name('onlineclasses.get_lms');
Route::get('online-classes/get-notes/{subject_id}/{chapter_id}', 'OnlineclassesController@getNotes')->name('onlineclasses.get_notes');

Route::post('get-pop-quiz-info/{lms_series_id}/{content_id}', 'BatchesController@changePopQuizInfo')->name('change-pop.quiz_info');

Route::get('live-quiz/unpop/{online_class_id}','QuizController@unPopExam')->name('live-quiz.unpop');

Route::match(['get', 'post'], 'onlineclasses/add-live-quiz/{slug}', 'OnlineclassesController@updateLiveQuiz2')->name('onlineclasses.add_live_quiz');

Route::get('user/login-history', 'UsersController@loginHistory')->name('user.login_history');
Route::get('user/login-history/getList', [ 'as'   => 'login_history.dataTable',
    'uses' => 'UsersController@getHistory']);

Route::get('user/user-actions', 'UsersController@actionsHistory')->name('user.actions');
Route::get('user/user-actions/getList', [ 'as'   => 'user_actions.dataTable',
    'uses' => 'UsersController@getActionsHistory']);

Route::get('import-classes/import','OnlineclassesController@importClasses')->name('onlineclasses.import');
Route::post('import-classes/import','OnlineclassesController@readExcel')->name('onlineclasses.import_post');

// Batch Reports.
Route::get('batch-reports/index',  'BatchesController@batchReportsList')->name('batch.reports');

Route::get('batch-reports/getList', 'BatchesController@getBatchReportsList')->name('batch.get_reports');

Route::get('terms-of-service', 'SiteController@termsOfService')->name('site.terms_of_service');
Route::get('privacy-policy', 'SiteController@privacyPolicy')->name('site.privacy_policy');

Route::get('student/trail-notify-dismiss/{payment_id}', 'StudentQuizController@trailNotifyDismiss')->name('student.trail_notify_dismiss');
Route::get('student/enable-free-trail/{paidcontent_slug}', 'StudentQuizController@enableFreeTrail')->name('student.enable_free_trail');
