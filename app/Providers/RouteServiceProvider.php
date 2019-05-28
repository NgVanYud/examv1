<?php

namespace App\Providers;

use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $bindingInfo = [
            [
              'podcast' => 'user',
              'model' => '\App\Models\User',
              'findBy' => 'uuid'
            ],
            [
                'podcast' => 'subject',
                'model' => '\App\Models\Subject',
                'findBy' => 'slug'
            ],
            [
                'podcast' => 'term',
                'model' => '\App\Models\Term',
                'findBy' => 'uuid'
            ],
            [
              'podcast' => 'format',
              'model' => '\App\Models\Format',
              'findBy' => 'id'
            ]
        ];

        foreach ($bindingInfo as $obj) {
            Route::bind($obj['podcast'], function($findBy) use ($obj) {
                $record = $obj['model']::where($obj['findBy'], $findBy)->first();
                if(!$record) {
                    throw new GeneralException(__('exceptions.general'), 404);
                }
                return $record;
            });
        }
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
