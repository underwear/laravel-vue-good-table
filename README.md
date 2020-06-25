# laravel-vue-good-table
Vue-good-table wrapper for Laravel. Server side tables without pain.

## Reqs:
1. Installed and globally registered [vue-good-table](https://xaksis.github.io/vue-good-table/)
2. Installed and globally registered [vue-select](https://vue-select.org/), cause vue-good-table [issue #714](https://github.com/xaksis/vue-good-table/issues/714)

## Usage

Controller:
```php
class TestController extends Controller
{
    use InteractsWithVueGoodTable;

    protected function getQuery(Request $request)
    {
        return Auction::query();
    }

    protected function getColumns(): array
    {
        return [
            Text::make('ID', 'auction_id')
                ->sortable()
                ->searchable(),

            Text::make('Trip', 'trip_id')
                ->displayUsing(function ($value) {
                    return "<a href='/transport/trip/{$value}'>$value</a>";
                })
                ->html(),

            Date::make('Start date', 'start_date')
                ->sortable()
                ->dateOutputFormat('dd.MM.yyyy HH:mm:ss'),
        ];
    }
}
```

Routes:
```php
Route::get('/lvgt/config', 'TestController@handleConfigRequest');
Route::get('/lvgt/data', 'TestController@handleDataRequest');
```

Blade/HTML:
```blade
<div id="vue">
    <laravel-vue-good-table data-url="/lvgt/data" config-url="/lvgt/config"/>
</div>
```