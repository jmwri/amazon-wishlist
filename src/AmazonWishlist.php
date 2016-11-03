<?php

namespace JmWri\AmazonWishlist;

use JmWri\AmazonWishlist\Wishlist\WishlistV1;
use JmWri\AmazonWishlist\Wishlist\WishlistV2;

/**
 * Class AmazonWishlist
 * @package JmWri\AmazonWishlist
 */
class AmazonWishlist
{
    use UtilTrait;

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var string $amazonTld
     */
    protected $amazonTld;

    /**
     * @var string $reveal
     */
    protected $reveal;

    /**
     * @var string
     */
    protected $sort;

    /**
     * @var null|string
     */
    protected $affiliateTag;

    /**
     * AmazonWishlist constructor.
     *
     * @param string $id
     * @param string $tld
     * @param string $reveal
     * @param string $sort
     * @param null|string $affiliateTag
     */
    public function __construct(
        $id = '2EZ944B2S8C5Q',
        $tld = '.co.uk',
        $reveal = 'unpurchased',
        $sort = 'date',
        $affiliateTag = null
    ) {
        $this->setId($id);
        $this->setAmazonTld($tld);
        $this->setReveal($reveal);
        $this->setSort($sort);
        $this->setAffiliateTag($affiliateTag);
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $amazonTld
     */
    public function setAmazonTld($amazonTld)
    {
        $this->amazonTld = $amazonTld;
    }

    /**
     * @return string
     */
    public function getAmazonTld()
    {
        return $this->amazonTld;
    }

    /**
     * @param $reveal
     *
     * @throws \InvalidArgumentException
     */
    public function setReveal($reveal)
    {
        if (! in_array($reveal, [
            'unpurchased',
            'purchased',
            'all',
        ])
        ) {
            throw new \InvalidArgumentException('Invalid reveal value');
        }

        $this->reveal = $reveal;
    }

    /**
     * @return string
     */
    public function getRevealParam()
    {
        return "reveal={$this->reveal}";
    }

    /**
     * @param string $sort
     *
     * @throws \InvalidArgumentException
     */
    public function setSort($sort)
    {
        switch ($sort) {
            case 'date':
                $this->sort = 'date-added';
                break;
            case 'priority':
                $this->sort = 'priority';
                break;
            case 'title':
                $this->sort = 'universal-title';
                break;
            case 'price-high':
                $this->sort = 'universal-price';
                break;
            case 'price-low':
                $this->sort = 'universal-price-desc';
                break;
            case 'updated':
                $this->sort = 'last-updated';
                break;
            default:
                throw new \InvalidArgumentException('Invalid sort value');
        }
    }

    /**
     * @return string
     */
    public function getSortParam()
    {
        return "sort={$this->sort}";
    }

    /**
     * @param null|string $tag
     */
    public function setAffiliateTag($tag)
    {
        $this->affiliateTag = $tag;
    }

    /**
     * @return string
     */
    public function getAffiliateTagParam()
    {
        if (is_null($this->affiliateTag)) {
            return '';
        }

        return "tag={$this->affiliateTag}";
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return "http://www.amazon{$this->getAmazonTld()}";
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return "{$this->getBaseUrl()}/registry/wishlist/{$this->getId()}?"
                . "{$this->getRevealParam()}&{$this->getSortParam()}&layout=standard";
    }

    /**
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return WishlistItem[]
     * @throws AmazonWishlistException
     */
    protected function getWishlist($getIsbn = false, $getAuthor = false)
    {
        $content = $this->getDocumentFile($this->getUrl());
        if ($content == '') {
            throw new AmazonWishlistException('Unable to load wishlist');
        }

        if (count(pq('tbody.itemWrapper')) > 0) {
            $wishlist = new WishlistV1($this);
        } else {
            $wishlist = new WishlistV2($this);
        }

        $pages = $wishlist->getPageCount();

        return $wishlist->getWishlist($this->getUrl(), $pages, $getIsbn, $getAuthor);
    }

    /**
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return array
     */
    public function getArray($getIsbn = false, $getAuthor = false)
    {
        $wishlistItemsToArray = [];

        foreach ($this->getWishlist($getIsbn, $getAuthor) as $wishlistItem) {
            $wishlistItemsToArray[] = $wishlistItem->toArray();
        }

        return $wishlistItemsToArray;
    }

    /**
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return string
     */
    public function getJson($getIsbn = false, $getAuthor = false)
    {
        return json_encode($this->getArray($getIsbn, $getAuthor));
    }

}
