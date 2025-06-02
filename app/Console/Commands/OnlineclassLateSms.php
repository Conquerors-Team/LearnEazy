<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Onlineclass;
use DB;

use Illuminate\Console\Command;


defined('CRON_JOB') or define('CRON_JOB', 'Yes');

class OnlineclassLateSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onlineclass:sms';

    private $default_logo;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send SMS to the student mobile if he not yet joined the online class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->default_logo = url('images/logo.png');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->send_sms();
    }

    private function send_sms() {
        $onlineclasses = \App\Onlineclass::whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')->whereRaw("'" . date('Y-m-d') . '\' BETWEEN DATE(valid_from) AND DATE(valid_to)');

// echo getEloquentSqlWithBindings( $onlineclasses );die();

//echo "\r\n" . date('H:i:s') . '@@' . date('H:i:s', strtotime(date('Y-m-d H:i:s') . ' + 30 minute'));
        if ( $onlineclasses->count() > 0 ) {
            foreach( $onlineclasses->get() as $row) {

                $batch = \App\Batch::find( $row->batch_id );

                $permission_check_sms = \App\Alert::join('alerts_enabled', 'alerts_enabled.alert_id', '=', 'alerts.id')->where('name', 'onlineclass_absent')->where('type', 'SMS')->where('institute_id', $row->institute_id)->where('batch_id', $row->batch_id)->first();

                $permission_check_email = \App\Alert::join('alerts_enabled', 'alerts_enabled.alert_id', '=', 'alerts.id')->where('name', 'onlineclass_absent')->where('type', 'Email')->where('institute_id', $row->institute_id)->where('batch_id', $row->batch_id)->first();

                if ( $batch && ( $permission_check_sms || $permission_check_email ) ) {

                    $before_mins = 15;
                    $onlineclass_absent_time_before = \App\Alert::join('alerts_enabled', 'alerts_enabled.alert_id', '=', 'alerts.id')->where('name', 'onlineclass_absent_time_before')->where('type', 'SMS')->where('institute_id', $row->institute_id)->first();
                    if ( $onlineclass_absent_time_before ) {
                        $before_mins = (int)$onlineclass_absent_time_before->batch_id;
                    }

                    //echo $row->class_time . '##' . date('H:i:s') . '@@' . date('H:i:s', strtotime($row->class_time . " + $before_mins minute"));
                    // Class started and its 30mins over!
                    if ( date('H:i:s') > date('H:i:s', strtotime($row->class_time . " + $before_mins minute")) ) {
                        $batch_students = \App\BatchStudent::where('batch_id', $row->batch_id)->groupBy('user_id')->get();
                        $batch_attendance = \App\OnlineclassAttendance::where('class_id', $row->id)->groupBy('student_id')->pluck('student_id')->toArray();

                        $batch_absebts_notified = \App\OnlineclassAbsentNotification::where('class_id', $row->id)->groupBy('student_id')->pluck('student_id')->toArray();
                        // print_r($batch_absebts_notified);
                        //echo getEloquentSqlWithBindings(\App\BatchStudent::where('batch_id', $row->batch_id)->groupBy('user_id'));
                        //dd( $batch_students );
                        foreach ($batch_students as $student) {
                            // If student not in attendance list and not yet notified.
                            if ( ! in_array($student->user_id, $batch_attendance) && ! in_array($student->user_id, $batch_absebts_notified) ) {
                                // echo $row->id . '##' . $student->user->name . "\r\n";

                                $site_title = getSetting('site_title', 'site_settings');
                                $smsmessage = "Your ward (".$student->user->name.") is not present in todays class $row->title From $site_title";

                                $phone = $student->user->phone_code . $student->user->phone;
                                // $phone = '919866211858';
                                $notified_sms = $notified_email = 'no';
                                if ( canDo('sms_alerts', $student->user_id ) && ! empty( $phone ) && $permission_check_sms ) {
                                    $smsagent = \App\SMSAgent::sendUserSMS( $phone, $smsmessage, $row->institute_id);
                                    $notified_sms = 'yes';
                                }

                                $email = $student->user->email;
                                if ( canDo('email_alerts', $student->user_id ) && ! empty( $email ) && $permission_check_email ) {
                                    $data = [
                                        'student_name' => $student->user->name,
                                        'class_name' => $row->title,
                                        'class_time' => $row->class_time,
                                        'date' => date('d/m/Y'),
                                        'to_email' => $email,
                                    ];
                                    sendEmail('absent-notification', $data);

                                    emailHistory( $email, json_encode($data), $row->institute_id );

                                    $notified_email = 'yes';
                                }
                                //echo $notified_sms . '##' . $notified_email . '@@' . $email . '##' . $phone . "\r\n";
                                // die();

                                \App\OnlineclassAbsentNotification::create([
                                    'class_id' => $row->id,
                                    'student_id' => $student->user_id,
                                    'notified_sms' => $notified_sms,
                                    'notified_email' => $notified_email,
                                ]);

                            }
                        }
                    }
                }
            }
        }
    }
}
