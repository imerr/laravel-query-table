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

    public static function concat($a, $b): HtmlString {
        if ($a instanceof HtmlString) {
            if ($b instanceof HtmlString) {
                return new HtmlString($a->string . $b->string);
            }
            return new HtmlString($a->string . e($b));
        } else if ($b instanceof HtmlString) {
            return new HtmlString(e($a) . $b->string);
        }
        return new HtmlString(e($a) . e($b));
    }


    /**
     * @inheritDoc
     */
    public function toHtml() {
        return $this->string;
    }
}
