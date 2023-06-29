<?php

namespace imer\QueryTable;

class QueryTableProvider extends \Illuminate\Support\ServiceProvider {
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'query_table');
        $this->loadViewsFrom(__DIR__.'/../views', 'query_table');
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/query_table'),
        ]);
    }
}