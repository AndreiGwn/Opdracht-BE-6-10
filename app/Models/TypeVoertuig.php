<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeVoertuig extends Model
{
    protected $table = 'type_voertuigs';

    protected $primaryKey = 'Id';

    const CREATED_AT = 'DatumAangemaakt';

    const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'TypeVoertuig',
        'Rijbewijscategorie',
        'IsActief',
        'Opmerking',
    ];

    public function voertuigen()
    {
        return $this->hasMany(Voertuig::class, 'TypeVoertuigId', 'Id');
    }
}
