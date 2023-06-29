<?php

namespace imer\QueryTable;

class TextField extends Field {
    private $text;

    public function __construct($view) {
        $this->text = $view;
        parent::__construct('');
    }

    public function renderValue($row, $index) {
        if (is_callable($this->text)) {
            call_user_func($this->text, $row);
        }
        return $this->text;
    }
}
