PHBEE v0.1
==========
PHBee is a micro PHP framework. It's loosely inspired by Zend Framework and Code Igniter.

## Apache configuration

	<VirtualHost *>
		ServerName phbee.local
		DocumentRoot /path/to/public/dir

		SetEnv APPLICATION_ENV development

		<Directory /path/to/public/dir>
			AllowOverride None
			Order allow,deny
			Allow from all

			RewriteEngine On
			RewriteCond %{REQUEST_FILENAME} -f [OR]
			RewriteCond %{REQUEST_FILENAME} -l [OR]
			RewriteCond %{REQUEST_FILENAME} -d
			RewriteRule ^.*$ - [L]
			RewriteRule ^.*$ index.php [L]
		</Directory>
	</VirtualHost>
