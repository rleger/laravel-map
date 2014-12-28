<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a great spot to register your various container
		// bindings with the application. As you can see, we are registering our
		// "Registrar" implementation here. You can add your own bindings too!

		$this->app->bind('Illuminate\Contracts\Auth\Registrar', 'App\Services\Registrar');

		$this->app->bind('App\Mapping\MappingProvider', 'App\Mapping\MappingProviders\GoogleMaps');

		$this->app->bind('App\Mapping\Adapters\Adapter', 'App\Mapping\Adapters\GuzzleAdapter');
	}

}
