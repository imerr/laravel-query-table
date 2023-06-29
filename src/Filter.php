<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

interface Filter {
    public function apply(QueryTable $table, Field $field, Builder $query);

    public function render(QueryTable $table, Field $field);
}
