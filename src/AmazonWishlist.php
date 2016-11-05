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
    use PhpQueryTrait;

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
     * @var null|string
     */
    protected $baseUrl = 'http://www.amazon';

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
        $id,
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
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setId($id)
    {
        if (! is_string($id)) {
            throw new \InvalidArgumentException('ID is not a string');
        }
        if (! strlen($id)) {
            throw new \InvalidArgumentException('ID is empty');
        }

        $this->id = $id;
        return true;
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
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setAmazonTld($amazonTld)
    {
        if (! is_string($amazonTld)) {
            throw new \InvalidArgumentException('TLD is not a string');
        }
        if (! in_array($amazonTld, [
            '.co.uk',
            '.com',
            '.ca',
            '.com.br',
            '.co.jp',
            '.de',
            '.fr',
            '.in',
            '.it',
            '.es',
        ])
        ) {
            throw new \InvalidArgumentException('Invalid TLD supplied');
        }

        $this->amazonTld = $amazonTld;
        return true;
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
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setReveal($reveal)
    {
        if (! is_string($reveal)) {
            throw new \InvalidArgumentException('Reveal is not a string');
        }
        if (! in_array($reveal, [
            'unpurchased',
            'purchased',
            'all',
        ])
        ) {
            throw new \InvalidArgumentException('Invalid reveal supplied');
        }

        $this->reveal = $reveal;
        return true;
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
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setSort($sort)
    {
        if (! is_string($sort)) {
            throw new \InvalidArgumentException('Sort is not a string');
        }
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

        return true;
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
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setAffiliateTag($tag)
    {
        if (! is_string($tag) && ! is_null($tag)) {
            throw new \InvalidArgumentException('Affiliate tag is not a string or null');
        }
        if (is_string($tag) && ! strlen($tag)) {
            throw new \InvalidArgumentException('Affiliate tag must have content if it is a string');
        }

        $this->affiliateTag = $tag;
        return true;
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
        return "{$this->baseUrl}{$this->getAmazonTld()}";
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
     * @param string $baseUrl
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setBaseUrl($baseUrl)
    {
        if (! is_string($baseUrl)) {
            throw new \InvalidArgumentException('Base URL is not a string');
        }
        if (! strlen($baseUrl)) {
            throw new \InvalidArgumentException('Base URL is empty');
        }

        $this->baseUrl = $baseUrl;
        return true;
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
        $url = $this->getUrl();
        $content = $this->getDocumentFile($url);
        if ($content == '') {
            throw new AmazonWishlistException('Unable to load wishlist');
        }

        if (count(pq('tbody.itemWrapper')) > 0) {
            $wishlist = new WishlistV1($this);
        } else {
            $wishlist = new WishlistV2($this);
        }

        $pages = $wishlist->getPageCount();

        return $wishlist->getWishlist($url, $pages, $getIsbn, $getAuthor);
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
