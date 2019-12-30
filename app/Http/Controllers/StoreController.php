<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

class StoreController extends Controller
{
    private $timeLimit = 5;

    public function index(Request $request)
    {
        $data = null;

        // getting last stored time
        $ttl = Cookie::get('ttl');

        // checking if TTL is 5min or less
        if (Carbon::now()->diffInMinutes(Carbon::parse($ttl)) <= $this->timeLimit) {
            $data = collect(json_decode(Cookie::get('keys')));

            if ($request->query('keys')) {
                $params = $request->query('keys');

                $keys = explode(",", $params);

                $data = $data->filter(function ($value, $key) use ($keys) {
                    if (in_array($key, $keys)) {
                        return $value;
                    }
                });
            }

            //set TTL counter
            setcookie('ttl', Carbon::now());
        } else {
            //clear existing values forcefully
            \cookie()->forget('keys');
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $keys = [];

        foreach ($request->all() as $i => $key) {
            $keys[$i] = $key;
        }

        // set data here
        setcookie('keys', json_encode($keys), $this->timeLimit);

        //set TTL counter
        setcookie('ttl', Carbon::now());

        return response()->json([
            'message' => "Values are stored.",
            'expires_in' => $this->timeLimit * 60,
            'data' => $keys,
        ], 201);
    }
}
