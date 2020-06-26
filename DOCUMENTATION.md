# Documentation

1. Columns
   * [Defining Columns](#defining-columns)
       + [Column Conventions](#column-conventions)
   * [Sortable Columns](#sortable-columns)
   * [Searchable Columns](#searchable-columns)
   * [Filterable Columns](#filterable-columns)
   * [Column Types](#column-types)
       + [Text Column](#text-column)
       + [Number Column](#number-column)
       + [Decimal Column](#decimal-column)
       + [Text Column](#text-column)
       + [Percantage Column](#percentage-column)
       + [Boolean Column](#boolean-column)
       + [Date Column](#date-column)
       + [Computed Column](#computed-column)
2. [Database Query](#database-query)
3. [Customization](#customization)

## Columns

### Defining Columns

When you use `InteractsWithVueGoodTable` trait in your controller, it requires implementation of `getColumns` method.
Vue good table supports columns: number, decimal, percentage, boolean, date.

To add a columns, we can simply add it to the controllers's `getColumns` method.
Typically, columns may be created using their static make method.
This method accepts several arguments; however, you usually only need to pass the "human readable" name of the field.
Lvgt will automatically "snake case" this string to determine the underlying database column:


```php
use LaravelVueGoodTable\Columns\Number;
use LaravelVueGoodTable\Columns\Text;

/**
 * Get the columns displayed by the table.
 *
 * @return array
 */
protected function getColumns: array
{
    return [
         Number::make('ID')
              ->sortable(),
                
         Text::make('Name'),
    ];
}
```

#### Column Conventions
As noted above, Lvgt will "snake case" the displayable name of the column to determine the underlying database column.
However, if necessary, you may pass the column name as the second argument to the field's make method:

```php
Text::make('Name', 'name_column')
```

### Sortable Columns
When attaching a column to a table, you may use the sortable method to indicate that the table may be sorted by the given column:
```php
Text::make('Name')->sortable()
```

### Searchable Columns
When attaching a column to a table, you may use the searchable method to indicate that the table may be filtered by search query entering by the given column:
```php
Text::make('Name')->searchable()
```

### Filterable Columns
When attaching a column to a table, you may use the filterable method to indicate that the table may be filtered by value in this column.

Simple input:
```php
Text::make('Id')->filterable(true, 'Type id')
```

Select:
```php
Text::make('Status')->filterable(true, 'Choose status', ['offline', 'online'])
```

Multiselect:
```php
Text::make('Hobby')->filterable(true, 'Choose hobbies', ['Sport', 'Dancing', 'PC-Gaming', 'Cooking'])
```

### Column Types
Let's explore all of the available types and their options. Most of them just extend `Text` column behavior.

#### Text Column
Takes attribute value from database result and put into table.
```php
use LaravelVueGoodTable\Columns\Text;

Text::make('Name')
```

You can set width of table column:
```php
Text::make('Name')->width('100px')
```

You can use display raw html inside the table cell
```php
Text::make('Name')->html()
```

If you need prepare value before displaying, use method `displayUsing`:
```php
Text::make('Category', 'category_id')
    ->displayUsing(function ($value, $row) {
        return "<a href='/categories/{$value}'>{$row->category_name}</a>";
    })
    ->html()
```

#### Number Column
Number - right aligned

#### Decimal Column
Decimal - right aligned, 2 decimal places

#### Percentage Column
Percentage - expects a decimal like 0.03 and formats it as 3.00%

#### Boolean Column
Boolean - right aligned

#### Date Column
Date - expects a string representation of date eg '20170530'. You should also specify `dateInputFormat` and `dateOutputFormat`.

```php
use LaravelVueGoodTable\Columns\Date;

Date::make('Created At', 'created_at')
    ->dateOutputFormat('dd.MM.yyyy HH:mm:ss')
```

Vue-good-table uses date-fns for date parsing. [Check out their formats here](https://date-fns.org/v2.0.0-beta.4/docs/parse)

#### Computed Column
If you need some extra field to put there you own value, no problem, use `ComputedField`:
```php
ComputedColumn::make('Random value', 'random', function ($resource) {
    return random(0, 100);
})
```
*Notice: You can't make computed column searchable or sortable.*


## Database Query
When you use `InteractsWithVueGoodTable` trait in your controller, it also requires implementation of `getQuery` method.

You can use Eloquent query builder or just common database query builder

```php
use Illuminate\Http\Request;
use App\User;

/**
 * Get the query builder
 *
 * @return array
 */
protected function getQuery(Request $request)
{
    return User::query();
}
```

You can customize query builder depends on request:
```php
protected function getQuery(Request $request)
{
    return User::query()
      ->where('company_id', $request->route()->parameter('company_id');)
}
```

Use `join` and `select` methods:
```php
protected function getQuery(Request $request)
{
    return User::query()
        ->select([
            'users.*',
            'hobbies.name as hobby_name',
            'roles.name as role_name'
        ])
        ->join('roles', 'roles.id', '=', 'users.role_id')
        ->join('hobbies', 'hobbies.id', '=', 'users.hobby_id');
}
```

Checkout [Laravel docs about Query Builder](https://laravel.com/docs/master/queries)

### Using query builders with databases, which don't support column aliases in where clause
PostgreSQL and some others strict sql standart databases don't support using column aliases in where clause.
For this case you can use method `whereClauseAttribute` on columns:
```php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use LaravelVueGoodTable\Columns\Text;
use LaravelVueGoodTable\InteractsWithVueGoodTable;

class UserController extends Controller
{
    use InteractsWithVueGoodTable;

    /**
     * Get the query builder
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function getQuery(Request $request)
    {
        return User::query()
            ->select([
                'users.*',
                'hobbies.name as hobby_name',
                'roles.name as role_name'
            ])
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('hobbies', 'hobbies.id', '=', 'users.hobby_id');
    }

    /**
     * Get the columns displayed in the table
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Text::make('Name', 'name')
                ->whereClauseAttribute('users.name'),

            Text::make('Role', 'role_name')
                ->whereClauseAttribute('roles.name'),

            Text::make('Hobby', 'hobby_name')
                ->whereClauseAttribute('hobbies.name'),
        ];
    }
}
```


## Customization
- [Search](https://xaksis.github.io/vue-good-table/guide/configuration/search-options.html)
- [Pagination](https://xaksis.github.io/vue-good-table/guide/configuration/pagination-options.html)

Bind params for each instance of component or edit `resources/js/components/LaravelVueGoodTable.vue` to change global appearance and behavior in your project.

Check out [vue-good-table docs about advanced customizations](https://xaksis.github.io/vue-good-table/guide/advanced/#custom-row-template)

