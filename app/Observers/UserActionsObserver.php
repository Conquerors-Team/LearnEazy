<?php

namespace App\Observers;

use Auth;
use App\UserAction;
use \DrewM\MailChimp\MailChimp;

class UserActionsObserver
{
    public function saved($model)
    {
        if ($model->wasRecentlyCreated == true) {
            // Data was just created
            $action = 'Created';
        } else {
            // Data was updated
            $action = 'Updated';
        }
        if (Auth::check()) {
            UserAction::create([
                'user_id'      => Auth::user()->id,
                'action'       => $action,
                'action_model' => ucfirst($model->getTable()),
                'action_id'    => $model->id,
                'ipaddress'    => GetIP(),
                'record_original'    => json_encode( $model->getOriginal() ),
                'record_update'    => json_encode( $model->getAttributes() ),
            ]);
        } else {
            // If it is CRON job.
            UserAction::create([
                'user_id'      => null,
                'action'       => $action,
                'action_model' => $model->getTable(),
                'action_id'    => $model->id,
                'ipaddress'    => GetIP(),
                'record_original'    => json_encode( $model->getOriginal() ),
                'record_update'    => json_encode( $model->getAttributes() ),
            ]);
        }
    }


    public function deleting($model)
    {
        if (Auth::check()) {
            UserAction::create([
                'user_id'      => Auth::user()->id,
                'action'       => 'deleted',
                'action_model' => $model->getTable(),
                'action_id'    => $model->id,

                'record_original'    => json_encode( $model->getOriginal() ),
                'record_update'    => json_encode( $model->getAttributes() ),
            ]);
        } else {
            // If it is CRON job.
            UserAction::create([
                'user_id'      => null,
                'action'       => $action,
                'action_model' => $model->getTable(),
                'action_id'    => $model->id,

                'record_original'    => json_encode( $model->getOriginal() ),
                'record_update'    => json_encode( $model->getAttributes() ),
            ]);
        }
    }
}