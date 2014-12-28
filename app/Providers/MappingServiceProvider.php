<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MappingServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Mapping service Binding
		$this->app->bind('App\Mapping\MappingProvider', 'App\Mapping\MappingProviders\GoogleMaps');

		// Adapter Binding
		$this->app->bind('Ivory\HttpAdapter\HttpAdapterInterface', 'Ivory\HttpAdapter\GuzzleHttpHttpAdapter');

	}

}
