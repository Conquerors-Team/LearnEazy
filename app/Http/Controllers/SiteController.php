<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App;
use \App\UserSubscription;
use \App\Quiz;
use \App\LmsSeries;
use \App\Institute;
use Response;
use Exception;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Session;

class SiteController extends Controller
{

    public function index()
    {
    $agent = new Agent();
    if($agent->isAndroidOS())
    {
       // return redirect('http://phpstack-418717-1336593.cloudwaysapps.com');
    }
    if(env('DB_DATABASE')!=''){

    try {

     $current_theme            = getDefaultTheme();
     $data['home_title']       = getThemeSetting('home_page_title',$current_theme);
     // $data['home_title']       = 'Welcome to LERNEASY';
     $data['home_link']        = getThemeSetting('home_page_link',$current_theme);
     $data['home_image']       = getThemeSetting('home_page_image',$current_theme);
     $data['home_back_image']  = getThemeSetting('home_page_background_image',$current_theme);



        $data['key'] = 'home';

        $data['active_class'] = 'home';
        $categories           = App\QuizCategory::getShowFrontCategories(8);
        $data['categories']   = $categories;

        if(count($categories) > 0 ){

          $firstOne        = $categories[0];
          $quizzes         = Quiz::where('category_id',$firstOne->id)
                                 ->where('show_in_front',1)
                                 ->where('total_marks','>',0)
                                 ->limit(6)
                                 ->inRandomOrder()
                                 ->get();

          $data['quizzes'] = $quizzes;
          // dd($quizzes);
        }

         $lms_cates  = LmsSeries::getFreeSeries(8);

         if(count($lms_cates) > 0){

            $firstlmsOne  = $lms_cates[0];
            $firstSeries  = LmsSeries::where('lms_category_id',$firstlmsOne->id)
                                       ->where('show_in_front',1)
                                       ->where('total_items','>',0)
                                       ->limit(6)
                                       ->inRandomOrder()
                                       ->get();

            $data['lms_cates']  = $lms_cates;
            $data['lms_series'] = $firstSeries;
         }

        $data['branches']   = Institute::get();

        $view_name = getTheme().'::site.index';
        //dd(getTheme());
        return view($view_name, $data);

          }catch (Exception $e) {

              // return view('200');
               return redirect( URL_UPDATE_DATABASE );
           }

      }

      else{

        return redirect('/install');
      }



        // return view('system-emails.site.subscription');
    }
    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function sitePages($key='privacy-policy')
    {

        $available_pages = ['privacy-policy', 'terms-conditions','about-us','courses','pattren','pricing','syllabus'];
        if(!in_array($key, $available_pages))
        {
            pageNotFound();
            return back();
        }
        $data['title']        = ucfirst(getPhrase($key));
        if($key == 'about-us'){

        $data['title']        = getPhrase('about_us');
        }
        elseif($key == 'privacy-policy'){
        $data['title']        = getPhrase('privacy_policy');

        }
        elseif($key == 'terms-conditions'){
        $data['title']        = getPhrase('terms_conditions');

        }
        $data['key']          = $key;
        $data['active_class'] = $key;

        // return view('site.dynamic-view', $data);

         $view_name = getTheme().'::site.dynamic-view';
        return view($view_name, $data);

    }


    /**
     * This method save the subscription email
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveSubscription(Request $request)
    {


       $email  = $request->useremail;
       $record   = UserSubscription::where('email',$email)->first();
       if(!$record){
           $new_record   = new UserSubscription();
           $new_record->email  = $email;
           $new_record->save();
           echo json_encode(array('status'=>'ok'));
       }
       else{
        echo json_encode(array('status'=>'existed'));
       }

    }

    /**
     * This method display the all fornt end exam categories
     * and exams
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function frontAllExamCats($slug='')
    {


        $data['key'] = 'home';

        $data['active_class'] = 'practice_exams';
        $categories           = App\QuizCategory::getShowFrontCategories();
        $data['categories']   = $categories;
        $quizzes  = array();

        if($categories && !$slug)
        {

          $firstOne        = $categories[0];
          $quizzes         = Quiz::where('category_id',$firstOne->id)
                                   ->where('show_in_front',1)
                                   ->where('total_marks','>',0)
                                   ->paginate(9);

          $data['title']  = ucfirst($firstOne->category);
        }
        if($categories && $slug){

           $category  = App\QuizCategory::where('slug',$slug)->first();
           $quizzes   = Quiz::where('category_id',$category->id)
                             ->where('show_in_front',1)
                             ->where('total_marks','>',0)
                             ->paginate(9);

            $data['title']  = ucfirst($category->category);

        }

          $data['quizzes']   = $quizzes;
          $data['quiz_slug'] = $slug;

        $view_name = getTheme().'::site.allexam_categories';
        return view($view_name, $data);


    }

    /**
     * View all front end lms categories and series
     * @param  string $slug [description]
     * @return [type]       [description]
     */
    public function forntAllLMSCats($slug='')
    {


        $data['key'] = 'home';

        $data['active_class'] = 'lms';
        $lms_cates            = array();
        $lms_cates            = LmsSeries::getFreeSeries();
        $data['lms_cates']    = $lms_cates;
        $all_series           = array();

        if(count($lms_cates) && !$slug)
        {

          $firstOne        = $lms_cates[0];
          $all_series      = LmsSeries::where('lms_category_id',$firstOne->id)
                                         ->where('show_in_front',1)
                                         ->where('total_items','>',0)
                                         ->paginate(9);

           $data['title']  = ucfirst($firstOne->category);

        }

        if($lms_cates && $slug)
        {
           $category     = App\LmsCategory::where('slug',$slug)->first();
           $all_series   = LmsSeries::where('lms_category_id',$category->id)
                                      ->where('show_in_front',1)
                                      ->where('total_items','>',0)
                                      ->paginate(9);

          $data['title']  = ucfirst($category->category);
        }
          $data['all_series']   = $all_series;
          $data['lms_cat_slug'] = $slug;

            $view_name = getTheme().'::site.alllms_categories';
        return view($view_name, $data);


    }

    /**
     * View all contents in specific lms series
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function forntLMSContents($slug)
    {

        $data['key'] = 'home';

        $data['active_class'] = 'lms';

        $lms_series   = LmsSeries::where('slug',$slug)->first();
        $lms_category = App\LmsCategory::where('id',$lms_series->lms_category_id)->first();
        $contents     = $lms_series->viewContents(9);

        $data['contents']     = $contents;
        $data['lms_series']   = $lms_series;
        $data['title']        = ucfirst($lms_series->title);
        $lms_cates            = LmsSeries::getFreeSeries();
        $data['lms_cates']    = $lms_cates;
        $data['lms_cat_slug'] = $lms_category->slug;

            $view_name = getTheme().'::site.lms-contents';
        return view($view_name, $data);

    }

    /**
     * Downlaod lms file type contents
     * @return [type] [description]
     */
    public function downloadLMSContent($content_slug){
        $content_record = App\LmsContent::getRecordWithSlug($content_slug);
        // dd($content_record);

        try {

           $pathToFile= "public/uploads/lms/content"."/".$content_record->file_path;

           return Response::download($pathToFile);

        } catch (Exception $e) {

           flash('Ooops','file_is_not_found','error');
           return back();
        }


    }

    /**
     * View video type lms contents
     * @param  [type] $content_slug [description]
     * @return [type]               [description]
     */
    public function viewVideo($content_slug,$series_id='')
    {
       // dd($series_id);
        $content_record = App\LmsContent::getRecordWithSlug($content_slug);


        $data['key'] = 'home';

        $data['active_class']    = 'lms';
        $data['title']           = ucfirst($content_record->title);
        $data['content_record']  = $content_record;
        $data['video_src']       =  $video_src = $content_record->file_path;
        if($series_id!=''){
           $first_series   = LmsSeries::where('id',$series_id)->first();

             $all_series   = LmsSeries::where('lms_category_id',$first_series->lms_category_id)
                                         ->where('id','!=',$first_series->id)
                                         ->where('show_in_front',1)
                                         ->where('total_items','>',0)
                                         ->get();
         // dd($all_series);
        }

         $data['first_series']  = $first_series;
         $data['all_series']    = $all_series;

          $view_name = getTheme().'::site.lms-content-video';
        return view($view_name, $data);
    }

    /**
     * Send a email to super admin with user contact us details
     * @param Request $request [description]
     */
    public function ContactUs(Request $request)
    {
       // dd($request);
       $data  = array();
       $data['name']     = $request->name;
       $data['email']    = $request->email;
       $data['number']   = $request->phone;
       $data['subject']  = $request->subject;
       $data['message']  = $request->message;

        try {

            $super_admin  = App\User::where('role_id',1)->first();

            $super_admin->notify(new \App\Notifications\UserContactUs($super_admin, $data));

            sendEmail('usercontactus', array('name'=> $request->name,
                      'to_email' => $request->email ));

         } catch (Exception $e) {
           // dd($e->getMessage());
         }

        flash('congratulations','our_team_will_contact_you_soon','success');
        return redirect()->route('site.contact');

    }


    public function getSeriesContents(Request $request)
    {
       $lms_series   = LmsSeries::find($request->lms_series_id);
       $contents     = $lms_series->viewContents();

       return json_encode(array('contents'=>$contents));

    }


    /**
     * View the batches details of a selected institute
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function batchesDetails($id)
    {
       $institute         = Institute::find($id);
       $batches           = App\Batch::where('institute_id',$institute->id)->get();

       $data['institute']    = $institute;
       $data['batches']      = $batches;
       $data['active_class'] = 'courses';
       $data['currency']     = getCurrencyCode();
       // $booked_seats      = App\BatchStudent::where('batch_id',$records->id)->get()->count();
       // $available_seats   = App\BatchStudent::where('batch_id',$records->id)->get()->count();

       $view_name = getTheme().'::batches.institute-batch-details';
        return view($view_name, $data);


    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function about()
    {
      $view_name = getTheme().'::site.about';
      return view($view_name);
    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function courses( $course_slug = '' )
    {
      $view_name = getTheme().'::site.courses';
      $data = [];
      if ( ! empty( $course_slug ) ) {
        $details = \App\Course::where('slug', $course_slug)->first();
        if ( $details ) {
          $data['course_details'] = $details;
        }
         $view_name = getTheme().'::site.course-details';
      }

      return view($view_name, $data);
    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function blog( $slug = '')
    {
      $view_name = getTheme().'::site.blog';
      $data = [];
      if ( ! empty( $slug ) ) {
        $details = \App\Course::where('slug', $slug)->first();
        if ( $details ) {
          $data['blog_details'] = $details;
        }
         $view_name = getTheme().'::site.single-blog';
      }
      return view($view_name);
    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function pricing()
    {
      $view_name = getTheme().'::site.pricing';
      return view($view_name);
    }
    /**
     * For logged in user course_id is item slug.
     */
    public function getStudentPackages(Request $request, $course_id = '', $slug_type = '')
    {
      if ( \Auth::check() ) {
        $student_courses = getStudentClasses('courses');
        $items = \App\StudentPaidContent::join('student_paid_contents_courses','student_paid_contents_courses.student_paid_contents_id','student_paid_contents.id')->whereIn('student_paid_contents_courses.course_id', $student_courses)->limit(4)->get();
      } else {
      $items = \App\StudentPaidContent::join('student_paid_contents_courses','student_paid_contents_courses.student_paid_contents_id','student_paid_contents.id')->where('student_paid_contents_courses.course_id', $course_id)->limit(4)->get();
      }
      // dd( $items );

      $examSettings = getExamSettings();

      $html = '';
      $total_packages = $items->count();

      if ( $total_packages >= 4 ) {
        $cols = 3;
      } else {
        $cols = 12/$total_packages;
      }

      // dd($items);
      if ( \Auth::check() ) {
        foreach ($items as $package) {
          if( $package->image == null){
            $package->image = "default.png";
          }
          $html .= "<div class='col-sm-".$cols."'>
          <img src='".PREFIX.$examSettings->courseImagepath.$package->image."' alt='' class='pricing-img' style='height: 80px;'><h2 class='pricing-header'>".$package->title."<p class='grey'>".$package->title."</p></h2><ul class='pricing-features'></ul>
          <a  href='".route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug])."' class='btn btn-lg btn-success button'>Buy now ₹ ".$package->cost."</a>
          </div>
          ";
        }
      } else {
      foreach ($items as $package) {

        if( $package->image == null){
          $package->image = "default.png";
        }
        $html .= "<div class='col-sm-".$cols."'>
        <img src='".PREFIX.$examSettings->courseImagepath.$package->image."' alt='' class='pricing-img' style='height: 180px;'><h2 class='pricing-header'>".$package->title."<p class='grey'>".$package->short_description."</p></h2><ul class='pricing-features'><li class='pricing-features-item'><i class='fa fa-check' style='color:#4CAF50;'></i> Online Videos</li>
          <li class='pricing-features-item'><i class='fa fa-check' style='color:#4CAF50;'></i> LIVE Classes</li></ul>
        <a  href='login' class='pricing-button pricing-price'>₹ ".$package->cost."</a>
        </div>
        ";
      }
      }

      return json_encode(array('items' => $html));
    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function practice()
    {
      $view_name = getTheme().'::site.practice';
      return view($view_name);
    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function contact(Request $request)
    {
      if ( $request->isMethod('post') ) {
        $rules = [
          'message' => 'required',
          'name' => 'required',
          'email' => 'required|email',
          'subject' => 'required',
        ];
        $this->validate($request,$rules);
      }
      $view_name = getTheme().'::site.contact';
      return view($view_name);
    }

    public function getCourses(Request $request) {

       $class_id = $request->student_class_id;
       $courses     = \DB::table('student_classes_courses')
            ->select('courses.*')
            ->join('courses', 'courses.id', 'student_classes_courses.course_id')
            ->where('student_classes_courses.student_class_id', $class_id)->get()->pluck('title', 'id')->prepend('Please select', '0')->toArray();

        $courses     = \DB::table('courses')->where('student_class_id', $class_id)
      ->where('courses.institute_id', OWNER_INSTITUTE_ID)->get()->pluck('title', 'id')->prepend('Please select', '0')->toArray();

      return response()->json(['courses'=>$courses]);

      // return json_encode(array('courses'=>$courses));
    }

    /**
     * This method will load the static pages
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function institute()
    {
      $view_name = getTheme().'::site.institute';
      return view($view_name);
    }

    public function getClassCourses( $class_id )
    {

    // $institute_id = adminInstituteId();

      // $list = \App\Course::join('student_classes_courses','student_classes_courses.course_id','courses.id')->join('student_classes','student_classes.id','student_classes_courses.student_class_id')->where('student_classes.id',$class_id)->get();

        $list = \App\Course::join('student_classes_courses','student_classes_courses.course_id','courses.id')->join('student_classes','student_classes.id','student_classes_courses.student_class_id')->where('student_classes.id',$class_id)->where('courses.institute_id', OWNER_INSTITUTE_ID)->get();
        // dd($list);
      $parents =  array();
      array_push($parents, array('id'=>0, 'text' => 'Please select'));
      foreach ($list as $key => $value) {
        $r = array('id'=>$value->id, 'text' => $value->title);
        array_push($parents, $r);
      }
      // dd($parents);
      return json_encode($parents);
  }


  /**
  * This method will load the static pages
  * @param  string $key [description]
  * @return [type]      [description]
  */
  public function board( $board_id, $class = '', $subject = '' )
  {
    $data = [];

    $board_slug = '';
    $board = \App\Board::where('slug', $board_id)->first();
    $data['board'] = $board;
    if ( $board ) {
      $board_id = $board->id;
      $board_slug = $board->slug;
    }
    $data['id'] = $board_id;
    $data['board_slug'] = $board_slug;

    $class_slug = '';
    if ( ! empty( $class ) ) {
      $board_class = \App\BoardClass::where('slug', $class)->first();
      if ( $board_class ) {
        $class = $board_class->id;
        $class_slug = $board_class->slug;
      }
    }
    $data['class_id'] = $class;
    $data['class_slug'] = $class_slug;

    $subject_slug = '';
    if ( ! empty( $subject ) ) {
      $board_subject = \App\BoardSubject::where('slug', $subject)->first();
      if ( $board_subject ) {
        $subject = $board_subject->id;
        $subject_slug = $board_subject->slug;
      }
    }
    $data['subject_id'] = $subject;
    $data['subject_slug'] = $subject_slug;

    /*
    $data['id'] = $board_id;
    $data['class_id'] = $class;
    $data['subject_id'] = $subject;
    */
//dd($data);
    $view_name = getTheme().'::site.board';
    return view($view_name, $data);
  }

  /**
  * This method will load the static pages
  * @param  string $key [description]
  * @return [type]      [description]
  */
  public function refBooks()
  {
    $view_name = getTheme().'::site.ref_books';
    return view($view_name);
  }

  public function mediaFileDownload( $model, $field, $id, $namespace = '' )
  {
      $model_original = $model;
      if ( ! empty( $namespace ) ) {
          $model = $namespace . '\\' . $model;
      } else {
          $model = '\App\\' . $model;
      }

      $record = $model::select( $field )->find($id);

      if ( ! $record ) {
          flash('Ooops...!','record_not_found', 'error');
          return redirect()->back();
      }

      if( $model_original == 'BoardChapter') {
        $path = public_path(env('UPLOAD_PATH')) . '/uploads/board-downloads/' . $record->$field;
      } else {
        $path = public_path(env('UPLOAD_PATH')) . '/uploads/ref-books/' . $record->$field;
      }

      if ( ! file_exists( $path ) ) {
          flash('Ooops...!','record_not_found', 'error');
          return redirect()->back();
      }

      $headers = [
              'Content-Type' => 'application/pdf',
           ];
      return response()->download($path, $record->file_input, $headers);
      //return Response::download($path);
  }

  /**
  * This method will load the static pages
  * @param  string $key [description]
  * @return [type]      [description]
  */
  public function termsOfService()
  {
    $view_name = getTheme().'::site.terms-of-service';
    return view($view_name);
  }

  /**
  * This method will load the static pages
  * @param  string $key [description]
  * @return [type]      [description]
  */
  public function privacyPolicy()
  {
    $view_name = getTheme().'::site.privacy-policy';
    return view($view_name);
  }
}
