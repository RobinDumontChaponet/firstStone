<?php


abstract class Dated extends Transitive\Utils\Model
{
    /**
     * @var int
     */
    protected $cTime;

    /**
     * @var int
     */
    protected $mTime;

    /**
     * @var int
     */
    protected $aTime;

    public function __construct(int $id = -1)
    {
        parent::__construct($id);
        $this->cTime = time();
        $this->aTime = time();
    }

    public function getCreationTime(): int
    {
        return $this->cTime;
    }

    public function getModificationTime(): ?int
    {
        return $this->mTime;
    }

    public function getAccessTime(): ?int
    {
        return $this->aTime;
    }

    public function setCreationTime(int $cTime): void
    {
        $this->cTime = $cTime;
    }

    public function setModificationTime(int $mTime = null): void
    {
        $this->mTime = $mTime;
    }

    public function setAccessTime(int $aTime = null): void
    {
        $this->aTime = $aTime;
    }
}
