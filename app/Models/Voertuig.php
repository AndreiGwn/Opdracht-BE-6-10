<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voertuig extends Model
{
    protected $table = 'voertuigs';

    protected $primaryKey = 'Id';

    const CREATED_AT = 'DatumAangemaakt';

    const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'Kenteken',
        'Type',
        'Bouwjaar',
        'Brandstof',
        'TypeVoertuigId',
        'IsActief',
        'Opmerking',
    ];

    public function typeVoertuig()
    {
        return $this->belongsTo(TypeVoertuig::class, 'TypeVoertuigId', 'Id');
    }

    public function instructeurs()
    {
        return $this->belongsToMany(Instructeur::class, 'voertuig_instructeurs', 'VoertuigId', 'InstructeurId')
            ->withPivot('Id', 'DatumToekenning', 'IsActief', 'Opmerking')
            ->withTimestamps('DatumAangemaakt', 'DatumGewijzigd');
    }
}
