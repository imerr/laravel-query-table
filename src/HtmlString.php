<?php

namespace imer\QueryTable;

use Illuminate\Contracts\Support\Htmlable;

class HtmlString implements Htmlable {
    private string $string;

    public function __construct(string $string) {
        $this->string = $string;
    }
    public function __toString(): string {
        return $this->string;
    }

    /**
     * @inheritDoc
     */
    public function toHtml() {
        return $this->string;
    }
}
