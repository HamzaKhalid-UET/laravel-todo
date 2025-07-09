<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class todo extends Model
{
    protected $table = 'todos';
    protected $fillable = ['title', 'description', 'user_id', 'status','priority','due_date'];
    public $timestamps = true;



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
