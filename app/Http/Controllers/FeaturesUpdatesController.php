<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Institute;
use Exception;
use Schema;
use DB;


class FeaturesUpdatesController extends Controller
{
     

    public function updateDatabase()
    {
    	
    	DB::beginTransaction();
 
        try {
               //Adding New Tables Total 6 new Tables
                
                // 1st Table
                $query =   "CREATE TABLE `batches` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `user_id` int(10) DEFAULT NULL,
                            `institute_id` int(10) DEFAULT NULL,
                            `name` varchar(250) DEFAULT NULL,
                            `start_date` date DEFAULT NULL,
                            `end_date` date DEFAULT NULL,
                            `start_time` varchar(20) DEFAULT NULL,
                            `end_time` varchar(20) DEFAULT NULL,
                            `capacity` int(10) NOT NULL DEFAULT '0',
                            `fee_perhead` decimal(10,2) NOT NULL DEFAULT '0.00',
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                          )"; DB::statement($query);

                $query  = "ALTER TABLE `batches` ADD PRIMARY KEY (`id`)"; 
                           DB::statement($query);

                $query  = "ALTER TABLE `batches` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";  
                           DB::statement($query);

                 // 2nd Table
                 $query =   "CREATE TABLE `batch_quizzes` (
							  `id` bigint(20) UNSIGNED NOT NULL,
							  `quiz_id` int(10) DEFAULT NULL,
							  `category_id` int(10) DEFAULT NULL,
							  `batch_id` int(10) DEFAULT NULL,
							  `institute_id` int(10) DEFAULT NULL,
							  `user_id` int(10) DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
							)"; DB::statement($query);

                $query  = "ALTER TABLE `batch_quizzes` ADD PRIMARY KEY (`id`)"; 
                           DB::statement($query);

                $query  = "ALTER TABLE `batch_quizzes` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";       DB::statement($query);


                // 3rd Table
                
                $query =   "CREATE TABLE `batch_students` (
							  `id` bigint(20) UNSIGNED NOT NULL,
							  `user_id` int(10) DEFAULT NULL,
							  `batch_id` int(10) DEFAULT NULL,
							  `institute_id` int(10) DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
							)"; DB::statement($query);

                $query  = "ALTER TABLE `batch_students` ADD PRIMARY KEY (`id`)"; 
                           DB::statement($query);

                $query  = "ALTER TABLE `batch_students` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";       DB::statement($query);
                
                // 4th Table
                $query =   "CREATE TABLE `fee_payments` (
							  `id` bigint(20) UNSIGNED NOT NULL,
							  `batch_student_id` bigint(20) UNSIGNED NOT NULL,
							  `user_id` int(10) DEFAULT NULL,
							  `batch_id` int(10) DEFAULT NULL,
							  `institute_id` int(10) DEFAULT NULL,
							  `amount` decimal(10,2) DEFAULT '0.00',
							  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `payment_method` varchar(20) DEFAULT NULL,
							  `paid_date` date DEFAULT NULL,
							  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `comments` text,
							  `added_by` int(10) DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
							)"; DB::statement($query);

                $query  = "ALTER TABLE `fee_payments` ADD PRIMARY KEY (`id`), ADD KEY `batch_student_id` (`batch_student_id`)"; 
                           DB::statement($query);

                $query  = "ALTER TABLE `fee_payments` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";       DB::statement($query);

                $query  = "ALTER TABLE `fee_payments` ADD CONSTRAINT `fee_payments_ibfk_1` FOREIGN KEY (`batch_student_id`) REFERENCES `batch_students` (`id`) ON DELETE CASCADE";       
                           DB::statement($query);

                // 5th Table
                
                  $query =   "CREATE TABLE `fee_payment_records` (
							  `id` bigint(20) UNSIGNED NOT NULL,
							  `fee_payment_id` bigint(20) UNSIGNED NOT NULL,
							  `user_id` int(10) DEFAULT NULL,
							  `batch_id` int(10) DEFAULT NULL,
							  `institute_id` int(10) NOT NULL DEFAULT '0',
							  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
							  `paid_date` date DEFAULT NULL,
							  `added_by` int(10) DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
							)"; DB::statement($query);

                $query  = "ALTER TABLE `fee_payment_records` ADD PRIMARY KEY (`id`), ADD KEY `fee_payment_id` (`fee_payment_id`)";       DB::statement($query);

                $query  = "ALTER TABLE `fee_payment_records` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";      
                          DB::statement($query);    

                // 6th Table
                 $query =   "CREATE TABLE `institutes` (
							  `id` bigint(20) UNSIGNED NOT NULL,
							  `user_id` bigint(20) UNSIGNED NOT NULL,
							  `parent_id` int(10) NOT NULL DEFAULT '0',
							  `institute_name` varchar(250) DEFAULT NULL,
							  `institute_address` text,
							  `status` tinyint(4) NOT NULL DEFAULT '0',
							  `comments` text,
							  `updated_by` int(10) DEFAULT NULL,
							  `phone` varchar(50) DEFAULT NULL,
							  `fax` varchar(15) DEFAULT NULL,
							  `web_site` varchar(255) DEFAULT NULL,
							  `logo` varchar(50) DEFAULT NULL,
							  `created_at` timestamp NULL DEFAULT NULL,
							  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
							)"; DB::statement($query);

                $query  = "ALTER TABLE `institutes` ADD PRIMARY KEY (`id`),ADD KEY `user_id` (`user_id`)";       
                           DB::statement($query);

                $query  = "ALTER TABLE `institutes` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";      DB::statement($query);  


                // Adding Institute Id to existed Tables
                $user   = User::where('role_id',1)->first();

            $institute                    = new Institute();
		        $institute->user_id           = $user->id;
		        $institute->institute_name    = getSetting('site_title','site_settings');
		        $institute->institute_address = getSetting('site_address','site_settings');
		        $institute->status            = 1;
		        $institute->comments          = "Approved";
		        $institute->updated_by        = $user->id;
            $institute->phone             = $user->phone;
		        $institute->save(); 

		        $institute_id   = $institute->id;


                $query  = "ALTER TABLE `couponcodes` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE couponcodes SET institute_id = ".$institute_id."";
                           DB::statement($query); 


                $query  = "ALTER TABLE `examseries` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE examseries SET institute_id = ".$institute_id."";
                           DB::statement($query);

                
                $query  = "ALTER TABLE `feedbacks` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE feedbacks SET institute_id = ".$institute_id."";
                           DB::statement($query);   


                $query  = "ALTER TABLE `instructions` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE instructions SET institute_id = ".$institute_id."";
                           DB::statement($query);  

                
                $query  = "ALTER TABLE `lmscategories` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE lmscategories SET institute_id = ".$institute_id."";
                           DB::statement($query);


                $query  = "ALTER TABLE `lmscontents` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE lmscontents SET institute_id = ".$institute_id."";
                           DB::statement($query); 

                
                $query  = "ALTER TABLE `lmsseries` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE lmsseries SET institute_id = ".$institute_id."";
                           DB::statement($query); 


                $query  = "ALTER TABLE `notifications` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE notifications SET institute_id = ".$institute_id."";
                           DB::statement($query);


                $query  = "ALTER TABLE `payments` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE payments SET institute_id = ".$institute_id."";
                           DB::statement($query); 


                $query  = "ALTER TABLE `questionbank` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE questionbank SET institute_id = ".$institute_id."";
                           DB::statement($query);


                $query  = "ALTER TABLE `quizcategories` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE quizcategories SET institute_id = ".$institute_id."";
                           DB::statement($query);


                
                $query  = "ALTER TABLE `quizresults` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE quizresults SET institute_id = ".$institute_id."";
                           DB::statement($query); 


                $query  = "ALTER TABLE `quizzes` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE quizzes SET institute_id = ".$institute_id."";
                           DB::statement($query);  

                $query  = "INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'institute', 'Institute', 'Institute', NULL, NULL)";
                           DB::statement($query); 

                $query  = "ALTER TABLE `subjects` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE subjects SET institute_id = ".$institute_id."";
                           DB::statement($query);
                
                                                                                                                                                                 
                $query  = "ALTER TABLE `users` ADD `institute_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE users SET institute_id = ".$institute_id."";
                           DB::statement($query); 


                $query  = "ALTER TABLE `users` ADD `branch_id` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);


                $query  = "ALTER TABLE `users` ADD `added_by` INT(10) NOT NULL DEFAULT '0' AFTER `updated_at`";
                           DB::statement($query);

                $query  = "UPDATE users SET added_by = ".$user->id."";
                           DB::statement($query);


                $query  = "DELETE FROM `settings` WHERE `settings`.`key` = 'social_logins' ";
                           DB::statement($query);

                
                 $query  = "INSERT INTO `settings` (`title`, `key`, `slug`, `image`, `settings_data`, `description`, `created_at`, `updated_at`) VALUES ('Category Settings', 'category_settings', 'category-settings', NULL, '{\r\n	\"student_can_add_exam_and_lms_categories\": {\r\n		\"value\": \"yes\",\r\n		\"type\": \"select\",\r\n		\"extra\": {\r\n			\"total_options\": \"2\",\r\n			\"options\": {\r\n				\"no\": \"No\",\r\n				\"yes\": \"Yes\"\r\n			}\r\n		},\r\n		\"tool_tip\": \"Student Can Add His Own Settings For Exam And LMS\"\r\n	},\r\n	\"main_branch_and_institutes_share_data\": {\r\n		\"value\": \"yes\",\r\n		\"type\": \"select\",\r\n		\"extra\": {\r\n			\"total_options\": \"2\",\r\n			\"options\": {\r\n				\"no\": \"No\",\r\n				\"yes\": \"Yes\"\r\n			}\r\n		},\r\n		\"tool_tip\": \"Institutes Can Share Data\"\r\n	}\r\n}', 'Exam and LMS Category Settings', NULL, '2018-07-24 00:35:35')";
                           DB::statement($query);                      

              DB::commit();
              flash('success','application_updated_successfully','success');    
         }

         catch ( Exception $e ) {

            DB::rollBack();
              // dd($e->getMessage());
            flash('success','application_updated_successfully', 'overlay');
             
        }

        return redirect( URL_HOME );
    }
}