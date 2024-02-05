<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'contact',
        'departement_id',
        'montant_journalier'
    ];

    public function departement ()
    {
        return $this->belongsTo(Departement::class);
    }

    public function payments ()
    {
        return $this->hasMany(Payment::class);
    }
}
