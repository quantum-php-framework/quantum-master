<?php

namespace Qontent\Entities;

use Quantum\Doctrine\Entity;

/**
 * @Entity
 * @Table(name="qo_posts")
 */
class Post extends Entity
{
    /**
     * @Column(type="string")
     */
    private $title;

    /**
     * @Column(type="text")
     */
    private $content;


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }



}