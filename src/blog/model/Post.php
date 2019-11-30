<?php

namespace Framework\Blog\Model;

class Post 
{
    protected $id;
    protected $title;
    protected $author;
    protected $content;
    protected $date_creation;

    public function __construct($id, $title, $content, $date_creation)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setContent($content);
        $this->setDateCreation($date_creation);
    }

    public function id()
    {
        return $this->id;
    }
    public function title()
    {
        return $this->title;
    }
    public function content()
    {
        return $this->content;
    }
    public function dateCreation()
    {
        return $this->date_creation;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function setTitle($title)
    {
        if (is_string($title))
        {
            $this->title = $title;
        }
    }

    public function setContent($content)
    {
        if (is_string($content))
        {
            $this->content = $content;
        }
    }

    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }
}
