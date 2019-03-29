<?php

namespace Butschster\Head\MetaTags\Entities;

use Butschster\Head\MetaTags\Meta;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;

class Tag implements TagInterface
{
    /**
     * @var string
     */
    protected $tagName;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var bool
     */
    protected $closeTag;

    /**
     * @var string
     */
    protected $placement = Meta::PLACEMENT_HEAD;

    /**
     * @param string $tagName
     * @param array $attributes
     * @param bool $closeTag
     */
    public function __construct(string $tagName, array $attributes, bool $closeTag = false)
    {
        $this->tagName = $tagName;
        $this->attributes = $attributes;
        $this->closeTag = $closeTag;
    }

    /**
     * @return string
     */
    public function getPlacement(): string
    {
        return $this->placement;
    }

    /**
     * @param string $placement
     *
     * @return $this
     */
    public function setPlacement(string $placement)
    {
        $this->placement = $placement;

        return $this;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @return string
     */
    public function compiledAttributes(): string
    {
        $html = [];

        foreach ($this->getAttributes() as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0
            ? implode(' ', $html)
            : '';
    }

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if (is_numeric($key)) {
            return e($value);
        }

        if (!is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return sprintf(
            '<%s %s%s>',
            $this->tagName,
            $this->compiledAttributes(),
            $this->closeTag ? ' /' : ''
        );
    }
}