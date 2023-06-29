<?php

namespace imer\QueryTable;

class BoolFilter extends DropdownFilter {
    public function __construct() {
        parent::__construct([
            0 => trans("query_table::misc.no"),
            1 => trans("query_table::misc.yes"),
        ]);
    }
}
