<?php

namespace imer\QueryTable;

use Illuminate\Support\Arr;

class Field {
    public string $key = "";
    private ?string $translationKey = null;
    /**
     * @var callable($
     */
    private $formatter = null;
    public bool $sortable = false;
    public ?Filter $filter = null;
    public $nameText = null;

    public function __construct($key) {
        $this->key = $key;
    }

    /**
     * Sets the translation key to use for the field name
     * @param $translationKey
     * @return Field
     */
    public function translationKey($translationKey): static {
        $this->translationKey = $translationKey;
        return $this;
    }

    public function sortable($sortable = true): static {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * Sets the translation attribute to use from "validation.attributes" for the field name
     * @param $translationAttribute
     * @return Field
     */
    public function translationAttribute($translationAttribute): static {
        $this->translationKey = "validation.attributes." . $translationAttribute;
        return $this;
    }

    /**
     * Sets text to use for the human readable field name
     * @param $text
     * @return $this
     */
    public function nameText($text) {
        $this->nameText = $text;
        $this->translationKey = null;
        return $this;
    }

    public function getName() {
        if ($this->nameText !== null) {
            return $this->nameText;
        }
        if (!$this->translationKey && !$this->key) {
            return "";
        }
        return trans($this->translationKey ?? "validation.attributes." . $this->key);
    }

    /**
     * Sets a formatter,
     * @param ?callable $formatter
     * @return $this
     */
    public function formatter(?callable $formatter) {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * Formats the value as a link
     * The url is retrieved via calling the $link callable with the current row
     * Additional link attributes may be provided via the $linkAttributes parameter, these are pasted as-as into the <a> tag
     * @param callable $link
     * @param string $linkAttributes
     * @return $this
     */
    public function formatAsLink(callable $link, string $linkAttributes = ''): static {
        $this->formatter = function ($v, $row) use ($linkAttributes, $link) {
            return new HtmlString('<a href="'.e($link($row)).'" '.$linkAttributes.'>'.e($v).'</a>');
        };
        return $this;
    }

    /**
     * Adds a filter to this field
     * @param Filter $filter
     * @return $this
     */
    public function filter(Filter $filter) : static {
        $this->filter = $filter;
        return $this;
    }

    public function renderValue($row, $index) {
        if ($this->formatter) {
            $fn = $this->formatter;
            return $fn(Arr::get($row, $this->key), $row);
        }
        return Arr::get($row, $this->key);
    }

    public function renderPreTableContent($row, $index) : string {
        return "";
    }
}
