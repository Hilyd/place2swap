<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as A;


class Article
{
    private $id;

    /**
     * @A\NotBlank()
     * @A\Length(max=40)
     * @var string
     */
    private $title;


    /**
     * @A\NotBlank()
     * @A\Length(min=1,max=5000)
     * @var string
     */
    private $description;

    /**
     * @A\Date()
     */
    private $date;

    /**
     * @A\NotBlank()
     * @A\Length(min=1, max=200)
     * @var string
     */
    private $author;

    /**
     * @A\NotBlank(message="Sélectionnez une image")
     * @A\File(mimeTypes={ "image/png", "image/jpeg" })
     */
    private $image;

    public function __construct(array $array = [])
    {
        if (count($array)) {
            if (isset($array['title'])) {
                $this->setTitle((string)$array['title']);
            }
            if (isset ($array['description'])) {
                $this->setDescription((string)$array['description']);
            }
            if (isset($array['author'])) {
                $this->setAuthor((string)$array['author']);
            }
            if (isset($array['image'])) {
                $this->setImage((string)$array['image']);
            }
            if (isset($array['date'])) {
                $this->setDate((string)$array['date']);
            }


        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function toArray(): array
    {
        $article = [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'date' => $this->getDate(),
            'author' => $this->getAuthor(),
            'image' => $this->getImage()
        ];
        return $article;
    }


}