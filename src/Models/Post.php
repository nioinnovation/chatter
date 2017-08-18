<?php

namespace DevDojo\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevDojo\Chatter\Helpers\ChatterHelper;

class Post extends Model
{
    use SoftDeletes;
    
    protected $table = 'chatter_post';
    public $timestamps = true;
    protected $fillable = ['chatter_discussion_id', 'user_id', 'body', 'markdown'];
    protected $dates = ['deleted_at'];

    public function discussion()
    {
        return $this->belongsTo(Models::className(Discussion::class), 'chatter_discussion_id');
    }

    public function user()
    {
        return $this->belongsTo(config('chatter.user.namespace'));
    }

    /**
     * If config.soft_deletes is true 
     * this only updates the deleted_at 
     * timestamp on the Model
     */
    public function deletePost() 
    {
        if ($this->discussion->posts()->oldest()->first()->id === $this->id) {
            if(config('chatter.soft_deletes')) {
                $this->discussion->posts()->delete();
                $this->discussion()->delete();
            } else {
                $this->discussion->posts()->forceDelete();
                $this->discussion()->forceDelete();
            }

            return redirect(ChatterHelper::baseRoute())->with([
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully deleted the response and '.strtolower(config('chatter.titles.discussion')).'.',
            ]);
        }

        $this->delete();

        return redirect($this->discussion->url)->with([
            'chatter_alert_type' => 'success',
            'chatter_alert'      => 'Successfully deleted the response from the '.config('chatter.titles.discussion').'.',
        ]);
    }
}
