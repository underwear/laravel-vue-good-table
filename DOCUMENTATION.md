# Documentation

## Defining Columns

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
public function getColumns: array
{
    return [
         Number::make('ID')
              ->sortable(),
                
         Text::make('Name'),
    ];
}
```

## Column Conventions
As noted above, Lvgt will "snake case" the displayable name of the column to determine the underlying database column.
However, if necessary, you may pass the column name as the second argument to the field's make method:

```php
Text::make('Name', 'name_column')
```

