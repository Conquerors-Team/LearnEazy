<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Http\Requests;
use App\User;
use App\Institute;
use App\Assosiates;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Exception;

class InstituteAssosiatesController extends Controller
{
   public function __construct()
    {
      $this->middleware('auth');
    }

    
    /**
     * Assosiate one institute with other institute
     * @return [type] [description]
     */
    public function index($id)
    {
      
      if(!checkRole(getUserGrade(1)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record       = Institute::find($id);
        $institutes   = Institute::where('id','!=',$id)->where('status',1)->get();
        $pre_data     = Assosiates::where('institute_id','=',$record->id)->get();

        $associates   = 0;
        if(count($pre_data) > 0) 
        $associates   = $pre_data;

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('associated_with_other_institutes');
        $data['institutes']         = $institutes;
        $data['associates']         = $associates;
        $data['record']             = $record;
        

        $view_name = getTheme().'::institutes.associates.list';
        return view($view_name, $data);	

    }
   
    /**
     * Add the associates to selected institute
     * @param Request $request [description]
     */
    public function addAssociates(Request $request)
    {
    	if(!$request->has('institutes_ids')){
             
          flash('Oops..!','please_select_the_institutes','overlay');
          return back();   
    	}
       
        $institutes  = $request->institutes_ids; 
        $exchange    = $request->is_twoway;
        $institute   = Institute::find($request->institute_id);
        
        foreach ($institutes as $key => $value){
          
          $record                 = new Assosiates();
          $record->user_id        = $institute->user_id;
          $record->institute_id   = $institute->id;
          $record->assosiated_id  = $key;

          if(isset($exchange[$key])){
             
             $record->is_twoway  = 1;
          }

          $record->updated_by    = Auth::user()->id;
          $record->save();

       }

       flash('success','institutes_are_associated_successfully','success');
       return redirect(URL_VIEW_INSTITUES); 

    }

}