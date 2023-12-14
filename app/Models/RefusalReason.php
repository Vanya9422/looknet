<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RefusalReason extends Model
{
    use HasFactory;

    /**
     * Get the parent commentable model (post or video).
     */
    public function ownerable(): MorphTo {
        return $this->morphTo();
    }
}
