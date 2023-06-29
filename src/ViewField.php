<?php

namespace imer\QueryTable;

class ViewField extends Field {
    private $view;
    private array $viewArgs;

    public function __construct($view, array $viewArgs = []) {
        $this->view = $view;
        $this->viewArgs = $viewArgs;
        parent::__construct('');
    }

    public function renderValue($row, $index) {
        return view($this->view, array_merge($this->viewArgs, ["row" => $row]));
    }
}
