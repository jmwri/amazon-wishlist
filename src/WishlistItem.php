<?php

namespace JmWri\AmazonWishlist;

use ReflectionClass;

/**
 * Class WishlistItem
 * @package JmWri\AmazonWishlist
 */
class WishlistItem
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $oldPrice;

    /**
     * @var string
     */
    protected $newPrice;

    /**
     * @var string
     */
    protected $dateAdded;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var int
     */
    protected $rating;

    /**
     * @var int
     */
    protected $totalRatings;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var string
     */
    protected $picture;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var string
     */
    protected $asin;

    /**
     * @var string
     */
    protected $largeSslImage;

    /**
     * @var string
     */
    protected $affiliateUrl;

    /**
     * @var string
     */
    protected $isbn;

    /**
     * @var string
     */
    protected $author;

    /**
     * WishlistItem constructor.
     *
     * @param $name
     * @param $link
     * @param $oldPrice
     * @param $newPrice
     * @param $dateAdded
     * @param $priority
     * @param $rating
     * @param $totalRatings
     * @param $comment
     * @param $picture
     * @param $page
     * @param $asin
     * @param $largeSslImage
     * @param $affiliateUrl
     * @param $isbn
     * @param $author
     */
    public function __construct(
        $name = null,
        $link = null,
        $oldPrice = null,
        $newPrice = null,
        $dateAdded = null,
        $priority = null,
        $rating = null,
        $totalRatings = null,
        $comment = null,
        $picture = null,
        $page = null,
        $asin = null,
        $largeSslImage = null,
        $affiliateUrl = null,
        $isbn = null,
        $author = null
    ) {
        $this->setName($name);
        $this->setLink($link);
        $this->setOldPrice($oldPrice);
        $this->setNewPrice($newPrice);
        $this->setDateAdded($dateAdded);
        $this->setPriority($priority);
        $this->setRating($rating);
        $this->setTotalRatings($totalRatings);
        $this->setComment($comment);
        $this->setPicture($picture);
        $this->setPage($page);
        $this->setAsin($asin);
        $this->setLargeSslImage($largeSslImage);
        $this->setAffiliateUrl($affiliateUrl);
        $this->setIsbn($isbn);
        $this->setAuthor($author);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * @param string $oldPrice
     */
    public function setOldPrice($oldPrice)
    {
        $this->oldPrice = $oldPrice;
    }

    /**
     * @return string
     */
    public function getNewPrice()
    {
        return $this->newPrice;
    }

    /**
     * @param string $newPrice
     */
    public function setNewPrice($newPrice)
    {
        $this->newPrice = $newPrice;
    }

    /**
     * @return string
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param string $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getTotalRatings()
    {
        return $this->totalRatings;
    }

    /**
     * @param int $totalRatings
     */
    public function setTotalRatings($totalRatings)
    {
        $this->totalRatings = $totalRatings;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * @param string $asin
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;
    }

    /**
     * @return string
     */
    public function getLargeSslImage()
    {
        return $this->largeSslImage;
    }

    /**
     * @param string $largeSslImage
     */
    public function setLargeSslImage($largeSslImage)
    {
        $this->largeSslImage = $largeSslImage;
    }

    /**
     * @return string
     */
    public function getAffiliateUrl()
    {
        return $this->affiliateUrl;
    }

    /**
     * @param string $affiliateUrl
     */
    public function setAffiliateUrl($affiliateUrl)
    {
        $this->affiliateUrl = $affiliateUrl;
    }

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
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
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $reflectionClass = new ReflectionClass(get_class($this));
        $array = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }

        return $array;
    }

}
