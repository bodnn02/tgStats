<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Request;
use Illuminate\Database\Query\JoinClause;
use function Laravel\Prompts\select;

class StoryController extends Controller
{

    function getData(

    ){
        $user = DB::select('SELECT username, login, path, best_time, best_day  FROM users WHERE id = '.$id);
        $user[0]->follow =  DB::table('contacts')
            ->join('subscriptions', 'contacts.id', '=', 'subscriptions.contact_id')
            ->where('subscriptions.user_id', '=', $id)
            ->whereNotNull('subscriptions.followed_by_user' )
            ->select('contacts.*')
            ->get();

        $user[0]->stories = DB::select('select * from stories where user_id = '.$id);

        $followers = DB::table('subscriptions')
            ->where('followed_by_user', true)
            ->where('subscriptions.user_id', $id)
            ->join('contacts', 'subscriptions.contact_id', '=', 'contacts.id')
            ->join('stories', 'views.story_id', '=', $id)
            ->join('views', 'contacts.id', '=', 'views.follower_id')

            ->get();


        //$user[0]->followers;

//            $followers  =  DB::table('contacts')
//            ->join('subscriptions', 'contacts.id', '=', 'subscriptions.contact_id')
//            ->where('subscriptions.user_id', '=', $id)
//            ->whereNotNull('subscriptions.follower' )
//            ->select('contacts.*')
//            ->groupBy('contacts.id');
//
//        $views = DB::table('views')
//            ->joinSub($followers, 'followers', function (JoinClause $join) {
//                $join->on('views.follower_id', '=', 'followers.id');
//            })
//            ->groupBy('followers.id')
//            ->get();


//
//        $views = DB::table('contacts')
//            ->select('contacts.id', DB::raw('COUNT(DISTINCT stories.id) AS views'), DB::raw('SUM(views.like) AS likes'))
//            ->join('subscriptions', 'subscriptions.contact_id', '=', 'contacts.id')
//            ->leftJoin('stories', function($join) {
//                $join->on('stories.user_id', '=', 'subscriptions.user_id');
//            })
//            ->leftJoin('views', function($join) {
//                $join->on('views.story_id', '=', 'stories.id');
//                $join->on('views.follower_id', '=', 'subscriptions.contact_id');
//            })
//            ->where('subscriptions.user_id', $id)
//            ->groupBy('contacts.id')
//            ->get();
//



        $user[0]->premium_followers =  DB::table('contacts')
            ->join('subscriptions', 'contacts.id', '=', 'subscriptions.contact_id')
            ->where('subscriptions.user_id', '=', $id)
            ->where('subscriptions.follower', '=', 1 )
            ->where('contacts.premium', '=', 1 )
            ->select('contacts.*')
            ->get();



        var_dump( $followers);
        die();

        $data = json_encode($user[0]);
        return $data;
    }
}
