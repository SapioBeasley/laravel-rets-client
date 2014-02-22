<?php namespace Lara\Rets;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class RetsServiceProvider extends ServiceProvider {

	public function register()
	{
        $this->app['rets'] = $this->app->share(function($app)
        {
            return new \Lara\Rets\Rets;
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Rets', 'Lara\Rets\Facades\Rets');
        });
	}



}