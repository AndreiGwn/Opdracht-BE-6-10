<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructeur extends Model
{
    protected $table = 'instructeurs';

    protected $primaryKey = 'Id';

    const CREATED_AT = 'DatumAangemaakt';

    const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'Voornaam',
        'Tussenvoegsel',
        'Achternaam',
        'Mobiel',
        'DatumInDienst',
        'AantalSterren',
        'IsActief',
        'Opmerking',
    ];

    public function getNaamAttribute()
    {
        return $this->Voornaam.
            ($this->Tussenvoegsel ? ' '.$this->Tussenvoegsel : '').
            ($this->Achternaam ? ' '.$this->Achternaam : '');
    }

    public function voertuigen()
    {
        return $this->belongsToMany(Voertuig::class, 'voertuig_instructeurs', 'InstructeurId', 'VoertuigId')
            ->withPivot('Id', 'DatumToekenning', 'IsActief', 'Opmerking')
            ->withTimestamps('DatumAangemaakt', 'DatumGewijzigd');
    }
}
