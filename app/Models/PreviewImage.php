<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreviewImage extends Model
{
    use HasFactory;
    public function preview_imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
