<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * El mapeo de políticas para los modelos.
     *
     * @var array
     */
    protected $policies = [
        Task::class => TaskPolicy::class, // Registra la política para el modelo Task
    ];

    /**
     * Registrar cualquier servicio de autorización / autenticación.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Registramos las políticas para la gestión de tareas
        Gate::define('view', [TaskPolicy::class, 'view']);
        Gate::define('update', [TaskPolicy::class, 'update']);
        Gate::define('delete', [TaskPolicy::class, 'delete']);
    }
}
