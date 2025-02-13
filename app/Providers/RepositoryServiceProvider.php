<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\ContactRepository;
use App\Repositories\ManagerRepository;
use App\Repositories\ListRepository;
use App\Repositories\OpportunityRepository;
use App\Repositories\PipelineRepository;
use App\Repositories\PipelineColumnRepository;

use App\Models\User;
use App\Models\Contact;
use App\Models\Manager;
use App\Models\ListModel;
use App\Models\Opportunity;
use App\Models\PipelineColumn;
use App\Models\Pipeline;
use App\Models\Task;
use App\Repositories\TaskRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository(new User());
        });
        $this->app->bind(ContactRepository::class, function ($app) {
            return new ContactRepository(new Contact());
        });
        $this->app->bind(ManagerRepository::class, function ($app) {
            return new ManagerRepository(new Manager());
        });
        $this->app->bind(ListRepository::class, function ($app) {
            return new ListRepository(new ListModel());
        });
        $this->app->bind(OpportunityRepository::class, function ($app) {
            return new OpportunityRepository(new Opportunity());
        });
        $this->app->bind(PipelineRepository::class, function ($app) {
            return new PipelineRepository(new Pipeline());
        });
        $this->app->bind(PipelineColumnRepository::class, function ($app) {
            return new PipelineColumnRepository(new PipelineColumn());
        });
        $this->app->bind(TaskRepository::class, function ($app) {
            return new TaskRepository(new Task());
        });
    }

    public function boot()
    {
        //
    }
}
