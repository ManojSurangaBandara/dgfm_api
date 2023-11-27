<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;

class MoneyAllocation extends Model
{
    use HasFactory;

    protected $connection = 'mysql_dynamic';

    protected $table = 'm_money_allocation';

    protected $fillable = [
        'allocationid',
        'year',
        'Vot_Number',
        'amount',
        'description',
        'createby',
        'createdate',
        'modifyby',
        'modifydate',
        'Sfhq_id',
        'branch_id',
        'from_branch',
        ];

    public function vote()
    {
        return $this->belongsTo(Vote::class, 'vote_id');
    }
}
