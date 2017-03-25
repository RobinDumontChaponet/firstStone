<?php

use Transitive\Utils\ModelException as ModelException;

abstract class Named extends Transitive\Utils\Model
{
    /**
     * @var string
     */
    protected $name;

    /**
     * __constructor.
     *
     * @param int    $id   (default: -1)
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        parent::__construct($id);
        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $e = null;
        $name = trim($name);

        if(empty($name))
            $e = new ModelException('Le nom doit être renseigné.', null, $e);

        if(strlen($name) > 40)
            $e = new ModelException('Le nom doit être au maximum de 40 caractères.', null, $e);

        ModelException::throw($e);

        $this->name = $name;
    }

    public function __toString(): string
    {
        return ' Named[id: '.$this->id.'; name: '.$this->name.'] ';
    }
}
