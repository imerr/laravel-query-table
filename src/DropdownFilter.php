<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

class DropdownFilter implements Filter {
    public const FieldName = "df";
    protected array $values;

    public function __construct(array $values) {
        $this->values = $values;
    }

    public function getQueryName(QueryTable $table, Field $field) {
        return $table->fieldName(DropdownFilter::FieldName . "_" . $field->key);
    }

    public function render(QueryTable $table, Field $field) {
        return view("query_table::_dropdown_filter", [
            "table" => $table,
            "field" => $field,
            "values" => $this->values,
            "fieldName" => $this->getQueryName($table, $field)
        ]);
    }

    public function apply(QueryTable $table, Field $field, Builder $query) {
        $filter = request()->query($this->getQueryName($table, $field));
        if ($filter !== null && isset($this->values[$filter])) {
            $query->where($field->key, "=", $filter);
        }
    }
}
