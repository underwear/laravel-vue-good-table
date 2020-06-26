# laravel-vue-good-table
Customizable table tool for Laravel, uses powerful [vue-good-table](https://xaksis.github.io/vue-good-table/). Server side tables without pain.

Supports pagination, filtering, searching, sorting. Inspired by Laravel Nova :)

![til](https://s7.gifyu.com/images/lvgt-demo.gif)

## Reqs
- Laravel 5.6+
- Using Vue.js in your project

## Usage example

1. Use `InteractsWithVueGoodTable` trait in your controller and implement two methods: `getColumns()` and `getQuery()`.
2. Register two new routes.
3. Use Vue component `laravel-vue-good-table` wherever you want.

#### Controller:
```php

namespace App\Http\Controllers;

use LaravelVueGoodTable\InteractsWithVueGoodTable;
use LaravelVueGoodTable\Columns\Column;
use LaravelVueGoodTable\Columns\Date;
use Illuminate\Http\Request;
use App\User;

class TestController extends Controller
{
    use InteractsWithVueGoodTable;

    /**
     * Get the query builder
     * 
     * @param Request $request
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery(Request $request)
    {
        return User::query();
    }

    /**
     * Get the columns displayed in the table
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Text::make('ID', 'id')
                ->sortable()
                ->searchable(),
                
            Text::make('Name', 'name')
                ->searchable(),
                
            Text::make('E-mail', 'email')
                ->searchable(),
                
            Date::make('Created At', 'created_at')
                ->sortable()
                ->dateOutputFormat('dd.MM.yyyy HH:mm:ss'),
        ];
    }
}
```

#### Routes:
```php
Route::get('/lvgt/config', 'TestController@handleConfigRequest');
Route::get('/lvgt/data', 'TestController@handleDataRequest');
```

#### Blade/HTML:
```blade
<div id="vue">
    <laravel-vue-good-table data-url="/lvgt/data" config-url="/lvgt/config"/>
</div>
```

## Installation

1. Install the package with composer:
```bash
composer require underwear/laravel-vue-good-table
```

2. Install [vue-good-table](https://xaksis.github.io/vue-good-table/) with npm:
```bash
npm i
npm install --save vue-good-table
```

3. Copy laravel-vue-good-table Vue component to your vue components folder
```bash
cp vendor/underwear/laravel-vue-good-table/js/LaravelVueGoodTable.vue resources/js/components/LaravelVueGoodTable.vue 
```

4. Register vue-good-table plugin and laravel-vue-good-table component in your `resources/app.js` file:
```javascript
window.Vue = require('vue');

// vue-good-table component
import VueGoodTablePlugin from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css'
Vue.use(VueGoodTablePlugin);

// laravel-vue-good-table component
import LaravelVueGoodTable from './components/LaravelVueGoodTable';
Vue.component('laravel-vue-good-table', LaravelVueGoodTable);

const app = new Vue({
    el: '#app',
});
```

and don't forget to rebuild and include your assets :)

## Documentation
See [DOCUMENTATION.md](./DOCUMENTATION.md)

## Known issues:
- For using multiselect you need install and globally register [vue-select](https://vue-select.org/), cause vue-good-table [issue #714](https://github.com/xaksis/vue-good-table/issues/714)
