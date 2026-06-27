<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoertuigInstructeur extends Model
{
    protected $table = 'voertuig_instructeurs';

    protected $primaryKey = 'Id';

    const CREATED_AT = 'DatumAangemaakt';

    const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'VoertuigId',
        'InstructeurId',
        'DatumToekenning',
        'IsActief',
        'Opmerking',
    ];

    public function voertuig()
    {
        return $this->belongsTo(Voertuig::class, 'VoertuigId', 'Id');
    }

    public function instructeur()
    {
        return $this->belongsTo(Instructeur::class, 'InstructeurId', 'Id');
    }
}
