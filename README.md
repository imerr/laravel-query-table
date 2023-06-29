# QueryTable
A laravel plugin for displaying query results as a table with built-in filtering/sorting/pagination.
Views use bootstrap and font-awesome, but can be published and modified via `php artisan vendor:publish --provider="imer\QueryTable\QueryTableProvider"` command
While this package is still in early stages the api might still change for better usability

Example:
```php

use imer\QueryTable;
use imer\QueryTable\HtmlString;

// ...

$countries = ["de" => "Germany", "gb" => "United Kingdom", "us" => "United States"];
$customers = (new QueryTable($request, Customer::query()))
    ->addField(
        (new QueryTable\Field("customer_number"))
            ->sortable()
            ->filter(new QueryTable\TextFilter())
            ->formatAsLink(function (Customer $r) {
                return route("customer.show", ["id" => $r->id]);
            })
    )
    ->addField(
        (new QueryTable\Field("company"))
            ->sortable()
            ->filter(new QueryTable\TextFilter())
    )
    ->addField((new QueryTable\Field("name"))->sortable()->filter(new QueryTable\TextFilter()))
    ->addField((new QueryTable\Field("email"))->sortable()->filter(new QueryTable\TextFilter()))
    ->addField(
    // Filter by yes/no
        (new QueryTable\Field("tax_exempt"))
            ->sortable()
            ->filter(new QueryTable\BoolFilter())
            ->formatter(function ($v) {
                return $v ? trans("misc.yes") : trans("misc.no");
            })
    )
    ->addField(
    // Country field with a dropdown filter
        (new QueryTable\Field("country"))
            ->sortable()
            ->filter(new QueryTable\DropdownFilter($countries))
            ->formatter(function ($v) use ($countries) {
                return $countries[$v] ?? "n/a";
            })
    )
    ->addField((new QueryTable\ViewField("customer._table_actions"))
        ->nameText(new HtmlString('<a href="' . e(route("customer.create")). '" class="btn btn-success"><i class="fa fa-plus"></i></a>')));

// pass $customers to your view and simply output the table:
// {!! $customers !!}
```

Turns into: ![preview of rendered table](https://github.com/imerr/laravel-query-table/assets/1426904/4ae52043-d2bf-4882-a995-ce84af3c72e0)

Filter demo: <video width="320" height="240" src="https://github.com/imerr/laravel-query-table/assets/1426904/d675ada5-0595-4d5f-92f9-968225aa6459"></video>





