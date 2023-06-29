<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;

class TextFilter implements Filter {
    public const FieldName = "tf";
    /**
     * @var string
     */
    private string $operand;
    protected string $prefix;
    private string $postfix;

    public function __construct(string $operand = "LIKE", ?string $prefix = null, ?string $postfix = null) {
        $this->operand = $operand;
        if ($prefix === null) {
            if ($operand == "LIKE") {
                $prefix = "%";
            } else {
                $prefix = "";
            }
        }
        $this->prefix = $prefix;
        if ($postfix === null) {
            if ($operand == "LIKE") {
                $postfix = "%";
            } else {
                $postfix = "";
            }
        }
        $this->postfix = $postfix;
    }

    public function getQueryName(QueryTable $table, Field $field) {
        return $table->fieldName(\imer\QueryTable\TextFilter::FieldName."_".$field->key);
    }

    public function render(QueryTable $table, Field $field) {
        return view("query_table::_text_filter", ["table" => $table, "field" => $field, "fieldName" => $this->getQueryName($table, $field)]);
    }

    public function apply(QueryTable $table, Field $field, Builder $query) {
        $filter = request()->query($this->getQueryName($table, $field));
        if ($filter !== null && $filter !== "") {
            $query->where($field->key, $this->operand, $this->prefix.$filter.$this->postfix);
        }
    }
}
