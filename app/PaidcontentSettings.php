<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaidcontentSettings extends Model
{
     protected  $settings = array(
     'categoryImagepath'        => "public/uploads/paidcontents/categories/",
     'contentImagepath'     	=> "public/uploads/paidcontents/content/",
     'seriesImagepath'          => "public/uploads/paidcontents/series/",
     'notesImagepath'          => "public/uploads/paidcontents/notes/",
     'seriesThumbImagepath'     => "public/uploads/paidcontents/series/thumb/",
     'notesThumbImagepath'     => "public/uploads/paidcontents/notes/thumb/",
     'defaultCategoryImage'     => "default.png",
     'imageSize'                => 300,
     'examMaxFileSize'          => 10000,
     'content_types'            => array(
                                    'text' => 'Text',
                                    'file' => 'File (PDF/Image)',
                                    'video' => 'Video File',
                                    'audio' => 'Audio File',
                                    'video_url' => 'Video URL',
                                    'iframe' => 'Iframe',
                                    'audio_url' => 'Audio URL',
                                    'url' => 'URL',
                                    'animation' => 'Animation file',
                                    )
     );





    /**
     * This method returns the settings related to Library System
     * @param  boolean $key [For specific setting ]
     * @return [json]       [description]
     */
    public function getSettings($key = FALSE)
    {
    	if($key && array_key_exists($key,$settings))
    		return json_encode($this->settings[$key]);
    	return json_encode($this->settings);
    }
}
