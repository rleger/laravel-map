<?php namespace App\Http\Controllers;

use App\Mapping\MappingProviders\GoogleMaps;
use App\Mapping\MappingService;
use GuzzleHttp\Client;


class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

    /**
     * Create a new controller instance.
     *
     * @return \App\Http\Controllers\WelcomeController
     */
	public function __construct()
	{
		$this->middleware('guest');
	}

    /**
     * Show the application welcome screen to the user.
     *
     * @internal param MappingService $map
     *
     * @return Response
     */
	public function index()
	{
        $from = '4 bis rue victor delavelle, besançon';
        $to = '144 route de toussieu, 69800';
        $to = 'les rousses';

        $map = new GoogleMaps(new Client(), 'fr', 'fr', null);

        $static = $map->staticMap($from, ['size' => '128x128']);
        return $static->get();

        // return $map->itinerary($from, $to)->get();

        $itinerary = $map->itinerary($from, $to)->get('array');

        return [
            'distance' => $itinerary['routes'][0]['legs'][0]['distance']['text'],
            'duration' => $itinerary['routes'][0]['legs'][0]['duration']['text'],
            'start_address' => $itinerary['routes'][0]['legs'][0]['start_address'],
            'end_address' => $itinerary['routes'][0]['legs'][0]['end_address'],
        ];

        dd($distance, $duration);

        return $itinerary;

		return view('welcome');

	}

}
