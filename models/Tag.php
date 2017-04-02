<?php


class Tag extends Named
{
    public function __construct(int $id, string $name)
    {
        parent::__construct($id, $name);
    }

    public function __toString(): string
    {
        return '<a href="articles/tag/'.$this->id.'" class="tag">'.$this->name.'</a>';
    }
}
