<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QueryTable {
    public const FilterFormId = "filter_form";
    private const FieldSort = "sort";
    private const FieldPage = "page";
    private const FieldSortDir = "sort_dir";
    public const SortAsc = "asc";
    public const SortDesc = "desc";

    private Builder $query;
    /**
     * @var Field[]
     */
    public array $fields = [];
    private Request $request;
    private $name = "";
    private $resultsPerPage = 50;
    private $defaultSort = null;
    private $defaultSortDir = self::SortAsc;

    public function __construct(Request $request, Builder $query) {
        $this->request = $request;
        $this->query = $query;
    }

    public function __toString(): string {
        return $this->render(request())->render();
    }

    /**
     * Sets the name of this querytable, if there is multiple ones on the same page
     * This avoids conflicts for GET parameters and the like
     * @param $name
     * @return $this
     */
    public function tableName($name): QueryTable {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addField(Field $field): QueryTable {
        $this->fields[] = $field;
        return $this;
    }

    public function defaultSortField(string $fieldName) : static {
        $this->defaultSort = $fieldName;
        return $this;
    }

    public function defaultSortDir(string $dir) : static {
        if ($dir != self::SortAsc && $dir != self::SortDesc) {
            throw new \InvalidArgumentException("Sort direction must be one of '".self::SortAsc."' or '".self::SortDesc."'");
        }
        $this->defaultSortDir = $dir;
        return $this;
    }

    /**
     * Sets the number of results displayed per page
     * @param $resultsPerPage
     * @return $this
     */
    public function resultsPerPage($resultsPerPage): static {
        $this->resultsPerPage = $resultsPerPage;
        return $this;
    }

    public function fieldName(string $field) {
        $r = "qt_";
        if ($this->name) {
            $r .= $this->name . "_";
        }
        return $r . $field;
    }

    public function sortUrl(string $field, string $direction) {
        return $this->url([$this->fieldName(self::FieldSort) => $field, $this->fieldName(self::FieldSortDir) => $direction], [$this->fieldName(self::FieldPage)]);
    }

    private function url(array $add = [], array $remove = []) {
        $question = $this->request->getBaseUrl().$this->request->getPathInfo() === '/' ? '/?' : '?';

        if (count($this->request->query()) > 0) {
            $existing = $this->request->query();
            foreach ($remove as $r) {
                if (isset($existing[$r])) {
                    unset($existing[$r]);
                }
            }
            return $this->request->url() . $question . Arr::query(array_merge($existing, $add));
        } else {
            return $this->request->fullUrl() . $question . Arr::query($add);
        }
    }

    public function render(): \Illuminate\Contracts\View\View {
        $sortDirection = $this->request->input($this->fieldName(self::FieldSortDir), $this->defaultSortDir) == self::SortDesc ? self::SortDesc : self::SortAsc;
        $sortField = $this->request->input($this->fieldName(self::FieldSort), $this->defaultSort);
        foreach ($this->fields as $field) {
            if ($field->key && $sortField == $field->key) {
                $this->query->orderBy($field->key, $sortDirection);
            }
        }
        $this->query->where(function ($q) {
            foreach ($this->fields as $field) {
                if ($field->filter) {
                    $field->filter->apply($this, $field, $q);
                }
            }
        });

        $this->results = $this->query->paginate($this->resultsPerPage, ["*"], $this->fieldName(self::FieldPage));
        return view("query_table::table", ["table" => $this, "sortField" => $sortField, "sortDirection" => $sortDirection]);
    }

    public function skipFieldFilterForm($fieldName) : bool {
        return $fieldName == $this->fieldName(self::FieldPage);
    }
}
