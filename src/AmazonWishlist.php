<?php

namespace JmWri\AmazonWishlist;

use JmWri\AmazonWishlist\Wishlist\WishlistV1;
use JmWri\AmazonWishlist\Wishlist\WishlistV2;

use JmWri\AmazonWishlist\Source\AmazonSource;

/**
 * Class AmazonWishlist
 * @package JmWri\AmazonWishlist
 */
class AmazonWishlist
{

    /**
     * @var AmazonSource $source
     *
     */
    protected $source;

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
     * @param AmazonSource $source
     * @param string $reveal
     * @param string $sort
     * @param null|string $affiliateTag
     */
    public function __construct(
        $source,
        $reveal = 'unpurchased',
        $sort = 'date',
        $affiliateTag = null
    ) {
        $this->source = $source;
        $this->setReveal($reveal);
        $this->setSort($sort);
        $this->setAffiliateTag($affiliateTag);
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
    public function getReveal()
    {
        return $this->reveal;
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
    public function getSort()
    {
        return $this->sort;
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
     * @return null|string
     */
    public function getAffiliateTag()
    {
        return $this->affiliateTag;
    }

    /**
     * @param string $id
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setId($id)
    {
        return $this->source->setId($id);
    }

    /**
     * @param string $tld
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setTld($tld)
    {
        return $this->source->setTld($tld);
    }

    /**
     * According to https://affiliate-program.amazon.co.uk/gp/associates/help/t5/a21
     *
     * > So if you need to build a simple text link to a specific item on Amazon.co.uk, here is the link format you
     * need to use: http://www.amazon.co.uk/dp/ASIN/ref=nosim?tag=YOURASSOCIATEID
     *
     * e.g. http://www.amazon.co.uk/dp/B00U7EXH72/ref=nosim?tag=shkspr-21
     *
     * Is this the same for all countries?
     *
     * Your Associate ID only works with one country
     * https://affiliate-program.amazon.co.uk/gp/associates/help/t22/a13%3Fie%3DUTF8%26pf_rd_i%3Dassoc_he..
     *
     * @param string $aisn
     *
     * @return string
     */
    protected function getAffiliateLink($aisn)
    {
        $this->source->getAffiliateLink($aisn, $this->getAffiliateTag());
    }

    /**
     * @param bool $getAuthor
     *
     * @return WishlistItem[]
     * @throws AmazonWishlistException
     */
    protected function getWishlist($getAuthor = false)
    {
        $params = [
            'reveal' => $this->getReveal(),
            'sort' => $this->getSort(),
            'tag' => $this->getAffiliateTag(),
        ];
        $content = $this->source->getDocumentFileWithParams($params);
        if ($content == '') {
            throw new AmazonWishlistException('Unable to load wishlist');
        }

        if (count(pq('tbody.itemWrapper')) > 0) {
            $wishlist = new WishlistV1($this->source, $this->getAffiliateTag());
        } else {
            $wishlist = new WishlistV2($this->source, $this->getAffiliateTag());
        }

        $pages = $wishlist->getPageCount();

        return $wishlist->getWishlist($this->source, $params, $pages, $getAuthor);
    }

    /**
     * @param bool $getAuthor
     *
     * @return array
     */
    public function getArray($getAuthor = false)
    {
        $wishlistItemsToArray = [];

        foreach ($this->getWishlist($getAuthor) as $wishlistItem) {
            $wishlistItemsToArray[] = $wishlistItem->toArray();
        }

        return $wishlistItemsToArray;
    }

    /**
     * @param bool $getAuthor
     *
     * @return string
     */
    public function getJson($getAuthor = false)
    {
        return json_encode($this->getArray($getAuthor));
    }

}
