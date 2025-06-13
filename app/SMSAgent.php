<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App;
use App\Http\Controllers\Traits\HasSlug;
use App\User;
use DB;
use SMS;
use Exception;
use Craftsys\Msg91\Facade\Msg91;

class SMSAgent extends Model
{
  use HasSlug;
	public $users    = [];
	public $message = '';
	public $role_id = 0;
	public $categories = [];
	public $final_users = [];
	public $current_user = [];

  public static function boot()
    {
        parent::boot();
        SMSAgent::observe(new \App\Observers\UserActionsObserver);
    }

    public function sendSMS()
    {
        $this->users = User::where('role_id','=',$this->role_id)->get();
        $this->filterUsersOnCategories();
        foreach ($this->final_users as $user) {
            $user = (object)$user;
            $this->setCurrentUser($user);
            try{
          $driver = getSetting('sms_driver', 'sms_settings');
          if ( 'msg91' === $driver ) {
            $current_user = $this->getCurrentUser();
            Msg91::sms()
            ->to($current_user->phone) // set the mobile with country code
            ->message($this->message) // provide your message
            ->send(); // send

            $this->smsHistory( $phone, $message, 'otp');

          } else {
            SMS::send($this->message, null, function($sms) {
                  $current_user = $this->getCurrentUser();
                  $sms->to($current_user->phone);
              });
            }
        }
        catch(Exception $e)
        {

        }

       }
       return TRUE;
    }

    public static function sendUserSMS($phone, $message, $institute_id = '') {
        try{
          $driver = getSetting('sms_driver', 'sms_settings');
          if ( 'msg91' === $driver ) {
            // $phone = '917793940988';
            //$message = 'Hi How are you?';

            $config = [
              'key' => '330527AmJxv4Ye5ecf6aecP1',
            ];
           

            $client = new \Craftsys\Msg91\Client($config);
            $result = $client->sms()
            ->to($phone) // set the mobile with country code
            ->options(function ($options) {
                $options->transactional() // set that it is a transactional message
                    ->from('LEARNZ') // set the sender
                    ->unicode(); // handle unicode as the message contains unicode characters
            })
            ->message($message) // provide your message
            ->send(); // send

            $data = [
              'message' => $message,
              'controller_details' => json_encode(getController()),
              'phone' => $phone,
              'sms_type' => 'sms',
              'created_at' => date('Y-m-d H:i:s'),
            ];
            if ( \Auth::check() ) {
              $data['institute_id'] = adminInstituteId();
            } elseif( ! empty( $institute_id )) {
              $data['institute_id'] = $institute_id;
            } else {
              $data['institute_id'] = OWNER_INSTITUTE_ID;
            }
            \DB::table('sms_usage_history')->insert($data);

            //dd( $result );
          } else {
          SMS::send($message, null, function($sms) {
                  $sms->to($phone);
              });
            }
        }
        catch(Exception $e)
        {
            dd( $e );
        }
    }

    public function smsHistory( $phone, $message, $type ) {
      $data = [
        'message' => $message,
        'controller_details' => json_encode(getController()),
        'phone' => $phone,
        'sms_type' => $type,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      if ( \Auth::check() ) {
        $data['institute_id'] = adminInstituteId();
      } elseif( ! empty( $institute_id )) {
        $data['institute_id'] = $institute_id;
      } else {
        $data['institute_id'] = OWNER_INSTITUTE_ID;
      }
      \DB::table('sms_usage_history')->insert($data);
    }

    public static function sendUserOTPSMS($phone, $message, $otp = '') {

        // try{
          $driver = getSetting('sms_driver', 'sms_settings');

          // If 'default country code is not empty we need to append it to phone number so that it make it with country code. If you dont want to append country code you just empty this value from site settings.'
          /*
          $default_country_code = getSetting('default_country_code', 'site_settings');
          if ( ! empty( $default_country_code ) ) {
            $phone = $default_country_code . $phone;
          } else {
            $phone = '91' . $phone;
          }
          */





          // if ( 'msg91' === $driver ) {
          //   $config = [
          //     'key' => env('MSG91_KEY'),
          //   ];
           

          //   $client = new \Craftsys\Msg91\Client($config);
          //   $otp = $client->otp( $otp );

          //   $otp->to($phone) // set the mobile with country code
          //   ->message($message) // provide your message
          //   ->send(); // send

          //   smsHistory( $phone, $message, 'otp');

          // } else {
          // SMS::send($message, null, function($sms) {
          //         $sms->to($phone);
          //     });
          //   }

        // }
        // catch(Exception $e)
        // {
        //     dd( $e );
        // }


        
         $config = [
        'key' => env('MSG91_KEY'),
      ];
      $client = new \Craftsys\Msg91\Client($config);
      $otp = '1234';
      \Log::info("Simulated OTP send to {$phone}. OTP: {$otp}. Message: {$message}");
      smsHistory($phone, $message, 'otp');
      return true;
    }

    public function setCurrentUser($user)
    {
    	$this->current_user = $user;
    }

    public function getCurrentUser()
    {
    	return $this->current_user;
    }

    public function filterUsersOnCategories()
    {
    	foreach($this->users as $user)
    	{
    		if($user->phone )
    		{
    			if(count($this->categories)) {
    			if($user->settings) {
    			$settings = array_keys((array)json_decode($user->settings)->user_preferences);
    			if(array_intersect($settings, $this->categories))
    			{
    				$this->final_users[$user->id]['name'] = $user->name;
    				$this->final_users[$user->id]['phone'] = $user->phone;
    			}
    		}
    	}
    	else{
    			$this->final_users[$user->id]['name'] = $user->name;
    			$this->final_users[$user->id]['phone'] = $user->phone;
    	}

    		}
    	}
    	return TRUE;
    }
}
