l5-ldap
============

An LDAP/Active Directory authentication driver for Laravel 5.

This package will enable you to have basic authentication with a config-based ACL for admin and viewers of any auth based portion of a Laravel 4 based site. In addition, the package is capable of tying into Apache based NTLM authentication. You will need to install and configure both `php5-ldap` and `libapache2-mod-auth-ntlm-winbind` for Apache2 (Visit http://goo.gl/SzkuVo for a tutorial). If it is not installed, the package should still operate.

Installation
============

To install this in your application add the following to your `composer.json` file

```json
require {
	"qwertytech/l5-ldap": "dev-master"
}
```

Then run `composer install` or `composer update` as appropriate

Once you have finished downloading the package from Packagist.org you need to tell your Application to use the LDAP service provider.

Open `app/config/app.php` and add:

`Qwertytech\L5Ldap\L5LdapServiceProvider`

This tells Laravel 4 to use the service provider from the vendor folder.

You also need to direct Auth to use the ldap driver instead of Eloquent or Database. 

Edit `app/config/auth.php` and change driver to `ldap`

Configuration
=============

Add the following config into your `app/config/auth.php` file

```js
/**
 * LDAP Configuration for qwertytech/l5-ldap
 */
'ldap' => array(
	// Domain controller (host), Domain to search (domain), 
	// OU containing users (basedn), OU containing groups (groupdn)
	'host' => 'ldap://dc', // You can also use ldaps://
	'domain' => 'domain.com',
	'timeout' => 3, // Timeout in seconds (It is optional, if you don´t select it is used the default value)
	'basedn' => 'OU=Users,DC=domain,DC=com',
	'groupdn' => 'OU=Groups,DC=domain,DC=com',

	// Domain credentials the app should use to access DC
	// This user doesn't need any privileges
	'dn_user' => '*',
	'dn_pass' => '*',

	//At minimum, you'll need these attributes
	'attributes' => array(
		'dn', 
		'samaccountname',
		'memberof'
	),

	// Optionally require groups to gain auth view access
	'groups' => array('AuthViewers'),

	// Optionally require group admins
	'admin_groups' => array('IT'),

	// Optionally require owners/admins (username)
	'owners' => array('ceo'),
),
```

Usage
======

In addition to the default Auth functionality, You can enable NTLM authentication with the auto() method from provided Guard class. Edit `app/config/filters.php` and change to:

```js
Route::filter('auth', function()
{
	// !Auth::user() checks to see if the user has access permission
	if (!Auth::auto() || Auth::guest()) return Redirect::guest('login');
});
```

