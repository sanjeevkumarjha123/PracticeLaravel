<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    use HasFactory;
    protected $fillable = [
        'fullname',
        'company_id',
        'mobile',
        'email',
        'role',
        'password',
        'image',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
