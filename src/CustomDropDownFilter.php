<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

class CustomDropDownFilter extends DropdownFilter {
    /**
     * @var callable
     */
    private $filterCallback;

    public function __construct(array $values, callable $filterCallback) {
        $this->filterCallback = $filterCallback;
        parent::__construct($values);
    }

    public function apply(QueryTable $table, Field $field, Builder $query) {
        $filter = request()->query($this->getQueryName($table, $field));
        if ($filter !== null && isset($this->values[$filter])) {
            $cb = $this->filterCallback;
            $cb($query, $filter);
        }
    }
}
