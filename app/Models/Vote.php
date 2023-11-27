<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoneyAllocation;

class Vote extends Model
{
    use HasFactory;

    protected $connection = 'mysql_dynamic';

    protected $table = 'votes';

    protected $fillable = [
        'vote_id',
        'vote_number',
        'description',
        'vt_type',
        'create_user_id',
        'Create_date',
        ];

        public function moneyAllocation()
    {
        return $this->hasOne(MoneyAllocation::class, 'Vot_Number');
    }

}
