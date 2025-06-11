<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class LmsSettings extends Model
{
    use HasSlug;
     protected  $settings = array(
     'categoryImagepath'        => "public/uploads/lms/categories/",
     'contentImagepath'     	=> "public/uploads/lms/content/",
     'seriesImagepath'          => "public/uploads/lms/series/",
     'notesImagepath'          => "public/uploads/lms/notes/",
     'seriesThumbImagepath'     => "public/uploads/lms/series/thumb/",
     'notesThumbImagepath'     => "public/uploads/lms/notes/thumb/",
     'defaultCategoryImage'     => "default.png",
     'imageSize'                => 300,
     'examMaxFileSize'          => 10000,
     'content_types'            => array(
                                    'text' => 'Text',
                                    'file' => 'File (PDF/Image)',
                                    // 'video' => 'Video File',
                                    // 'audio' => 'Audio File',
                                    'video_url' => 'Video URL',
                                    'iframe' => 'Iframe',
                                    // 'audio_url' => 'Audio URL',
                                    // 'url' => 'URL',
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
