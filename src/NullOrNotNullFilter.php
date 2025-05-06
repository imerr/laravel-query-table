<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

class NullOrNotNullFilter extends DropdownFilter {
    public function __construct() {
        parent::__construct([
            0 => trans("query_table::misc.not_set"),
            1 => trans("query_table::misc.set"),
        ]);
    }

    public function defaultFilterNull() {
        return $this->default(0);
    }
    public function defaultFilterNotNull() {
        return $this->default(1);
    }


    public function apply(QueryTable $table, Field $field, Builder $query) {
        $filter = request()->query($this->getQueryName($table, $field), $this->defaultFilter);
        if ($filter !== null && isset($this->values[$filter])) {
            if ($filter) {
                $query->whereNotNull($field->key);
            } else {
                $query->whereNull($field->key);
            }
        }
    }
}
