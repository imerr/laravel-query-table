<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

class DateRangeFilter implements Filter {
    public const FieldNameFrom = "drf";
    public const FieldNameTo = "drt";

    public function __construct() {
    }

    public function getQueryNameFrom(QueryTable $table, Field $field) {
        return $table->fieldName(DateRangeFilter::FieldNameFrom . "_" . $field->key);
    }

    public function getQueryNameTo(QueryTable $table, Field $field) {
        return $table->fieldName(DateRangeFilter::FieldNameTo . "_" . $field->key);
    }

    public function render(QueryTable $table, Field $field) {
        return view("query_table::_date_range_filter", [
            "table" => $table,
            "field" => $field,
            "fieldNameFrom" => $this->getQueryNameFrom($table, $field),
            "fieldNameTo" => $this->getQueryNameTo($table, $field),
        ]);
    }

    public function apply(QueryTable $table, Field $field, Builder $query) {
        $filterFrom = request()->query($this->getQueryNameFrom($table, $field));
        $filterTo = request()->query($this->getQueryNameTo($table, $field));
        if ($filterFrom && $filterTo) {
            $query->where($field->key, ">=", $filterFrom);
            $query->where($field->key, "<", $filterTo);
        }
    }
}
