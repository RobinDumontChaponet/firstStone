<?php

use Transitive\Utils\ModelException as ModelException;

class Article extends Dated
{
    private $name;
    private $title;

    private $summary;
    private $content;

    private $user;

    /**
     * @var Tag[]
     */
    private $tags;

    public function __construct(int $id, string $name, ?string $title = null, string $content = '', string $summary = '', array $tags = array())
    {
        parent::__construct($id);
        $this->setName($name);
        $this->setTitle($title ?? '');
        $this->setSummary($summary);
        $this->setContent($content);
        $this->tags = $tags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

	public function hasContent() : bool
	{
		return !empty($this->content);
	}

	public function hasSummary() : bool
	{
		return !empty($this->summary);
	}

    public function getTags(): array
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags[$tag->getId()] = $tag;
    }

    public function removeTag(Tag $tag): void
    {
        $this->removeTagById($tag->getId());
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setName(string $name): void
    {
        $e = null;
        $name = trim($name);

        if(empty($name))
            $e = new ModelException('Le titre de l\'article doit être renseigné.', null, $e);

        if(strlen($name) > 40)
            $e = new ModelException('Le titre doit être de maximum 40 caractères.', null, $e);

        ModelException::throw($e);

        $this->name = $name;
    }

    public function setTitle(string $title): void
    {
        $e = null;
        $title = trim($title);

        if(is_numeric($title))
            $e = new ModelException('Le sous-titre ne peut pas être constitué de chiffres seulement.', null, $e);

        if(strlen($title) > 25)
            $e = new ModelException('Le sous-titre doit être de maximum 25 caractères.', null, $e);

        ModelException::throw($e);

        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = trim($content);
    }

    public function setSummary(string $summary): void
    {
        $this->summary = trim($summary);
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function removeTagById(int $tagId): void
    {
        if(isset($this->tags[$tagId]))
            unset($this->tags[$tagId]);
    }

    public function __toString(): string
    {
        $len = strlen($this->getContent());

        $str = '<p>';
        if($len > 200 && $len < 210)
            $str .= substr($this->getContent(), 0, 200);

        $str .= '</p>';
        $str .= '<p><a href="article/'.$this->getId().'">Lire la suite</a></p>';
    }

    public function asListItem(): string
    {
        $str = '<article class="article">';
        $str .= '<time datetime="'.date('Y-m-d\TH:i:s', $this->getCreationTime()).'" pubdate>'.strftime('Le %e %b %G à %kh%M', $this->getCreationTime()).'</time>';
        $str .= '<h1><a href="article/'.$this->getId().'" title="'.$this->getTitle().'">'.$this->getName().'</a></h1>';

		if($this->hasSummary())
			$text = $this->getSummary();
		else {
			$text = $this->getContent();
			if(strlen($text) > 310)
        		$text = substr($text, 0, 300) . '…';
        }

        $str .= '<p>'.$text.'</p>';
        $str .= '<p><a href="article/'.$this->getId().'">Lire la suite</a></p>';

//         $str .= $this->getUser();
/*
        foreach($this->tags as $tag)
            $str .= $tag.PHP_EOL;
*/

        $str .= '</article>';

        return $str;
    }
}
