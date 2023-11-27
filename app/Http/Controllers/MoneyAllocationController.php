<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoneyAllocation;
use Illuminate\Support\Facades\DB;

class MoneyAllocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get_vote_details(Request $request){

        $year = $request->validate([
            'year' => ['required', 'string'],
        ]);

        $databaseName = $request->year.'dgfm';

        try {
             config(['database.connections.mysql_dynamic.database' => $databaseName]);
             DB::reconnect('mysql_dynamic');
         } catch (\Exception $e) {
             dd($e->getMessasge());
        }
         $voteNumbers = array("222-01-2-1205 (i) b", "222-01-2-1205 (viii) a", "222-01-2-1301 (i)", "222-01-2-1302 (i)", "222-01-2-1302 (vii)", "222-01-2-1302 (viii)", "222-01-2-1302 (xi)", "222-01-2-1302 (xiii)", "222-01-3-2002 (iii)", "222-01-3-2003 (ii)");
       
         $voteAmounts = array();
        foreach($voteNumbers as $voteNumber){
            array_push($voteAmounts, MoneyAllocation::join('votes', 'votes.vote_id', '=', 'm_money_allocation.Vot_Number')->where('year', $year)->where('vote_number', $voteNumber)->sum('amount') ?? 0);
            //if multiple allocations are available total of all allocations are taken
        }
        $result = array_combine($voteNumbers, $voteAmounts);
        return response()->json($result);
    }
}
