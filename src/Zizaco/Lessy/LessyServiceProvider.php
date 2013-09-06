<?php namespace Zizaco\Lessy;

use Illuminate\Support\ServiceProvider;

define('LESSY_VERSION', '0.6.2');

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
		if( $this->app->__get('env') != 'production'  || $this->app['config']->get('lessy::force_compile') )
		{
			$this->package('zizaco/lessy');
			$lessy = new Lessy($this->app);

			// Compiles less file if manual_compile_only is not enabled
			if (! $this->app['config']->get('lessy::manual_compile_only'))
			{
				$lessy->compileLessFiles();
			}
		}
	}

	/**
	 * Register the commands.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('lessy', function($app)
		{
		    return new Lessy($app);
		});

		$this->app['config']->package('zizaco/lessy', __DIR__.'/../../config');

		$this->app['command.lessy.compile'] = $this->app->share(function($app)
		{
			return new LessyCommand($app);
		});

		$this->commands('command.lessy.compile');
	}

}
