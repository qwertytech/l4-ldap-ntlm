<?php namespace Qwertytech\L5Ldap;

use Illuminate\Support\ServiceProvider;

/**
 * An LDAP/NTLM authentication driver for Laravel 4.
 *
 * @author Brian Wells (https://github.com/wells/)
 * 
 */
class L5LdapServiceProvider extends ServiceProvider {

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
		$this->package('qwertytech/l5-ldap');

		//Add the LDAP/NTLM Auth driver
		$this->app['auth']->extend('ldap', function($app)
		{
			return new L5LdapGuard(
				new L5LdapUserProvider(
					$app['config']->get('auth.ldap')
				),
				$app->make('session.store')
			);
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() { }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('ldap');
	}

}
