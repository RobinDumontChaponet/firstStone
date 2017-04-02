<?php

use Transitive\Utils\ModelException as ModelException;

class Profile extends Dated
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $pseudonym;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var Media
     */
    private $media;

    public function __construct(User $user, string $pseudonym = null, string $firstName = null, string $lastName = null)
    {
        parent::__construct($user->getId());
        $this->user = $user;

        $this->pseudonym = $pseudonym;
        $this->firstName = $firstName ?? '';
        $this->lastName = $lastName ?? '';
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->setId($user->getId());
    }

    public function getPseudonym(): string
    {
        return $this->pseudonym;
    }

    public function setPseudonym(string $pseudonym): void
    {
        $e = null;
        $pseudonym = trim($pseudonym);

        if(is_numeric($pseudonym))
            $e = new ModelException('Le pseudonyme ne peut pas être constitué de chiffres seulement.', null, $e);

        if(strlen($pseudonym) > 40)
            $e = new ModelException('Le pseudonyme ne peut contenir plus de 20 caractères.', null, $e);

        ModelException::throw($e);

        $this->pseudonym = $pseudonym;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $e = null;
        $firstName = trim($firstName);

        if(strlen($firstName) > 40)
            $e = new ModelException('Le champs de prénom ne peut contenir plus de 25 caractères.', null, $e);

        ModelException::throw($e);

        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $e = null;
        $lastName = trim($lastName);

        if(strlen($lastName) > 40)
            $e = new ModelException('Le champs de nom ne peut contenir plus de 25 caractères.', null, $e);

        ModelException::throw($e);

        $this->lastName = $lastName;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(Media $media = null): void
    {
        $this->media = $media;
    }

    public function __toString(): string
    {
        $str = '<address class="user">';
        $str .= '	<a rel="author" href="/profile/'.$this->getUser()->getId().'" target="_blank">'.$this->getFirstName().' '.$this->getLastName().'</a>';
        $str .= '</address>';

        return $str;
    }
}
