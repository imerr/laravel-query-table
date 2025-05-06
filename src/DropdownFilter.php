<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

class DropdownFilter implements Filter {
    public const FieldName = "df";
    protected array $values;
    protected mixed $defaultFilter = null;

    public function __construct(array $values, mixed $defaultFilter = null) {
        $this->values = $values;
        $this->defaultFilter = $defaultFilter;
    }

    public function getQueryName(QueryTable $table, Field $field) {
        return $table->fieldName(DropdownFilter::FieldName . "_" . $field->key);
    }

    public function default($defaultFilter): static {
        $this->defaultFilter = $defaultFilter;
        return $this;
    }

    public function render(QueryTable $table, Field $field) {
        return view("query_table::_dropdown_filter", [
            "table" => $table,
            "field" => $field,
            "values" => $this->values,
            "fieldName" => $this->getQueryName($table, $field),
            "defaultFilter" => $this->defaultFilter,
        ]);
    }

    public function apply(QueryTable $table, Field $field, Builder $query) {
        $filter = request()->query($this->getQueryName($table, $field), $this->defaultFilter);
        if ($filter !== null && isset($this->values[$filter])) {
            $query->where($field->key, "=", $filter);
        }
    }
}
