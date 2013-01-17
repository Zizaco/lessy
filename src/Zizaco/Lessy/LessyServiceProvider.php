<?php namespace Zizaco\Lessy;

use Illuminate\Support\ServiceProvider;

define('LESSY_VERSION', '0.5');

class LessyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		/**
		 * Lessy will NOT run in production environment
		 */
		if( $this->app->__get('env') != 'production' )
		{
			$this->package('zizaco/lessy');
			$lessy = new Lessy($this->app);
			$lessy->compileLessFiles();
		}
	}

	/**
	 * Register the commands.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['command.lessy.compile'] = $this->app->share(function($app)
		{
			return new LessyCommand($app);
		});

		$this->commands('command.lessy.compile');
	}

}
