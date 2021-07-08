<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBatch extends Model
{
    use HasFactory;

    protected $table = 'user_has_batches';

    protected $guarded = [];

    public $timestamps = false;

    public function batch()
    {
        return $this->hasOne(Batch::class);
    }
}
