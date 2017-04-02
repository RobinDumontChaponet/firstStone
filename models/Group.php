<?php


class Group extends Named
{
    /**
     * __constructor.
     *
     * @param int    $id   (default: -1)
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        parent::__construct($id, $name);
    }

    public function __toString(): string
    {
        return '<span class="group">'.$this->name.'('.$this->id.')</span>';
    }
}
