<?php

namespace Framework\Blog\Model;

class Post
{
    protected $id;
    protected $title;
    protected $author;
    protected $kicker;
    protected $content;
    protected $date_creation;
    protected $modification_date;
    protected $published;

    public function __construct($id, $title, $kicker, $author, $content, $date_creation, $modification_date, $published)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setKicker($kicker);
        $this->setAuthor($author);
        $this->setContent($content);
        $this->setDateCreation($date_creation);
        $this->setModificationDate($modification_date);
        $this->setPublished($published);
    }

    public function id()
    {
        return $this->id;
    }
    public function title()
    {
        return $this->title;
    }
    public function author()
    {
        return $this->author;
    }
    public function kicker()
    {
        return $this->kicker;
    }
    public function content()
    {
        return $this->content;
    }
    public function dateCreation()
    {
        return $this->date_creation;
    }
    public function modificationDate()
    {
        return $this->modification_date;
    }
    public function published()
    {
        return $this->published;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function setTitle($title)
    {
        if (is_string($title)) {
            $this->title = $title;
        }
    }

    public function setKicker($kicker)
    {
        if (is_string($kicker)) {
            $this->kicker = $kicker;
        }
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setContent($content)
    {
        if (is_string($content)) {
            $this->content = $content;
        }
    }

    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }

    public function setModificationDate($modification_date)
    {
        $this->modificationDate = $modification_date;
    }

    public function setPublished($published)
    {
        $this->published = $published;
    }
}
