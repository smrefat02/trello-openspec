<?php

namespace Trello\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Trello\Services\BoardService;
use Trello\Services\ListService;
use Trello\Services\CardService;
use Trello\Models\Board;
use Trello\Models\TrelloList;
use Trello\Models\Card;
use Trello\Policies\BoardPolicy;
use Trello\Policies\ListPolicy;
use Trello\Policies\CardPolicy;

class TrelloServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind services as singletons
        $this->app->singleton(BoardService::class, function ($app) {
            return new BoardService();
        });

        $this->app->singleton(ListService::class, function ($app) {
            return new ListService();
        });

        $this->app->singleton(CardService::class, function ($app) {
            return new CardService();
        });
    }

    /** 
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'trello');

        // Register policies
        Gate::policy(Board::class, BoardPolicy::class);
        Gate::policy(TrelloList::class, ListPolicy::class);
        Gate::policy(Card::class, CardPolicy::class);
    }
}
