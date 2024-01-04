<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoneyAllocation;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class MoneyAllocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get_vote_details(Request $request){

        $validated_data = $request->validate([
            'year' => ['required', 'string'],
        ]);

        $year = $validated_data['year'];
        $databaseName = $year.'dgfm';


        try {
             config(['database.connections.mysql_dynamic.database' => $databaseName]);
             DB::reconnect('mysql_dynamic');
         } catch (\Exception $e) {
             dd($e->getMessasge());
        }
        //  $voteNumbers = array("222-01-2-1205 (i) b", "222-01-2-1205 (viii) a", "222-01-2-1301 (i)", "222-01-2-1302 (i)", "222-01-2-1302 (vii)", "222-01-2-1302 (viii)", "222-01-2-1302 (xi)", "222-01-2-1302 (xiii)", "222-01-3-2002 (iii)", "222-01-3-2003 (ii)");
         $voteNumbers = $request['vote_numbers'] ?? array();
         // json encoded vote_numbers array must be passed with the api request
       
         $voteAmounts = array();
        foreach($voteNumbers as $voteNumber){

            $voteExists = Vote::where('vote_number', $voteNumber)
                ->exists();

            if ($voteExists) {
                $amount = MoneyAllocation::join('votes', 'votes.vote_id', '=', 'm_money_allocation.Vot_Number')
                    ->where('year', $year)
                    ->where('vote_number', $voteNumber)
                    ->sum('amount') ?? 0;
                //if multiple allocations are available total of all allocations are taken
        
                array_push($voteAmounts, $amount);
            } else {
                // If voteNumber not found, set -1
                array_push($voteAmounts, -1);
            }
            
        }
        $result = array_combine($voteNumbers, $voteAmounts);
        return response()->json($result);
    }

    public function vote_exists(Request $request){

        $validated_data = $request->validate([
            'year' => ['required', 'string'],
            'vote_head' => ['required', 'string'],
        ]);

        $voteExists = Vote::where('year', $validated_data['year'])
                ->where('vote_number', $validated_data['vote_head'])
                ->exists() ? 1 : 0;

        return response()->json([
            'exists' => voteExists,
        ]);

    }
}
