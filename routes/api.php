<?php



Route::post('/login', ['uses' => 'PWA\LoginController@postLogin']);

Route::post('/dashboardDetails' , ['uses' => 'PWA\LoginController@dashboardDetails']);

Route::post('/subjectDetails' ,['uses' => 'PWA\LoginController@subjectDetails'] );

Route::post('/topicDetails' ,['uses' => 'PWA\LoginController@topicDetails'] );

Route::post('/notestopicDetails',['uses' => 'PWA\LoginController@notestopicDetails']);	

Route::post('/upcomingaNDcompleted' , ['uses'=>'PWA\LoginController@upcomingaNDcompleted']);

Route::post('/quizInstructions',['uses'=>'PWA\LoginController@quizInstructions']);

Route::post('/quizQuestions' ,['uses'=>'PWA\LoginController@quizQuestions'] );

Route::post('/finishQuiz',['uses'=>'PWA\LoginController@finishQuiz']);

Route::post('/quizAnswers',['uses'=>'PWA\LoginController@quizAnswers']);

Route::post('/quizDetailedReport',['uses'=>'PWA\LoginController@quizDetailedReports']);

Route::post('/quizReport' ,['uses'=>'PWA\LoginController@QuizReport']);

Route::post('/allquizzes', ['uses'=>'PWA\LoginController@allQuizzes']);

Route::post('/getAnalysis', ['uses'=>'PWA\LoginController@getAnalysis']);

Route::post('/update-user-info', ['uses' => 'PWA\LoginController@updateUserInfo']);

Route::post('/subscriptionDetails',['uses' => 'PWA\LoginController@subscriptionDetails']);

Route::post('/subjectTests',['uses' => 'PWA\LoginController@subjectTests']);

Route::post('/grandTests',['uses' => 'PWA\LoginController@grandTests']);


Route::post('/packageDetails',['uses'=>'PWA\LoginController@packageDetails']);

Route::post('/previousYearsTests',['uses' => 'PWA\LoginController@previousYearsTests']);

Route::post('/razorPaySuccess',['uses'=>'PWA\LoginController@razorpaySuccess']);

// Route::post('/notificationData',['uses'=>'PWA\LoginController@notificationData']);

Route::post('/notificationData',['uses'=>'PWA\LoginController@notification']);

Route::post('/noteDest',['uses'=>'PWA\LoginController@noteDest']);

Route::post('/examList',['uses'=>'PWA\LoginController@examList']);

Route::get('/userNumber' ,['uses'=>'PWA\LoginController@userNumber']);

Route::get('/getBoards',['uses'=> 'PWA\LoginController@boards']);

Route::post('/class_courses',['uses'=> 'PWA\LoginController@getClassCourses']);

Route::post('/registration',['uses'=> 'PWA\LoginController@registration']);

Route::post('/otpVerify',['uses'=>'PWA\LoginController@otpVerify']);

Route::post('/enrollPhone',['uses'=>'PWA\LoginController@enrollPhone']);

Route::post('/getuserPhone',['uses'=>'PWA\LoginController@getuserPhone']);

Route::post('/classAttendence',['uses'=>'PWA\LoginController@classAttendence']);

Route::post('/freeEnrollPack',['uses'=>'PWA\LoginController@freeEnroll']);

Route::post('/useractiveStatus',['uses'=>'PWA\LoginController@useractiveStatus']);

Route::post('/packageStatus',['uses'=>'PWA\LoginController@packageStatus']);

?>
