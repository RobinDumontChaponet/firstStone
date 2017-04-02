<?php


class Media extends Transitive\Utils\Model
{
    private static $types = ['image', 'sound', 'video'];
    private static $sizes = ['small', 'medium', 'large'];

    /**
     * @var enum
     */
    private $type;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $extension;

    private $name;
    private $title;

    private $maxSize;

    public function __construct(int $id = -1, $type = 'image', $mimeType = 'image/jpeg', $extension = 'jpg', $maxSize = 'small', $name = null, $title = null)
    {
        parent::__construct($id);

        $this->setType($type);
        $this->setMimeType($mimeType);
        $this->setExtension($extension);
        $this->setMaxSize($maxSize);
        $this->setName($name);
        $this->setTitle($title);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMaxSize(): string
    {
        return $this->maxSize;
    }

    public function setType(string $type): void
    {
        if(!in_array($type, self::$types))
            throw new \Exception('Invalid type');
        $this->type = $type;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function setName(string $name = null): void
    {
        $this->name = $name;
    }

    public function setTitle(string $title = null): void
    {
        $this->title = $title;
    }

    public function setMaxSize(string $maxSize): void
    {
        if(!in_array($maxSize, self::$sizes))
            throw new \Exception('Invalid size');
        $this->maxSize = $maxSize;
    }

    public function __toString()
    {
        return '<img src="'.PUBLIC_DATA.'media/'.$this->getMaxSize().'/'.$this->getId().'.'.$this->getExtension().'" alt="" />';
    }
}
