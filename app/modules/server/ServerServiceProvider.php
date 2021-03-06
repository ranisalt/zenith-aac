<?php namespace App\Modules\Server;

use Illuminate\Support\ServiceProvider;
class ServerServiceProvider extends ServiceProvider {
	protected $defer = false;
	public function boot() {
		parent::boot('server');
	}

	public function register() {
		$this->app->bind('server', function($app) {
			switch ($this->app['config']['zenith.server_distro']) {
				case 'tfs0.4':
					return new TFS04ServiceProvider;
					break;

				case 'tfs1.0':
					return new TFS10ServiceProvider;
					break;

				case 'pyot':
					return new PyOTServiceProvider;
					break;
			}
		});
	}

	public function provides() {
		return array('server');
	}
}
