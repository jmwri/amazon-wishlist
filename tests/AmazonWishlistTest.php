<?php

namespace JmWri\AmazonWishlist\Test;

use JmWri\AmazonWishlist\AmazonWishlist;

/**
 * Class TestAmazonWishlist
 * @package JmWri\AmazonWishlist\Test
 */
class TestAmazonWishlist extends AmazonWishlist
{
    /**
     * @var null|string
     */
    protected $baseUrl = '/';

}


/**
 * Class AmazonWishlistTest
 * @package JmWri\AmazonWishlist\Test
 */
class AmazonWishlistTest extends BaseTest
{

    /**
     * @var string[]
     */
    protected static $idValid;

    /**
     * @var mixed[]
     */
    protected static $idInvalid;

    /**
     * @var string[]
     */
    protected static $tldValid;

    /**
     * @var mixed[]
     */
    protected static $tldInvalid;

    /**
     * @var string[]
     */
    protected static $revealValid;

    /**
     * @var mixed[]
     */
    protected static $revealInvalid;

    /**
     * @var string[]
     */
    protected static $sortValid;

    /**
     * @var mixed[]
     */
    protected static $sortInvalid;

    /**
     * @var string|null[]
     */
    protected static $affiliateTagValid;

    /**
     * @var mixed[]
     */
    protected static $affiliateTagInvalid;

    /**
     * @var AmazonWishlist
     */
    protected static $wishlist;

    public static function setUpBeforeClass()
    {
        self::$idValid = [
            '2EZ944B2S8C5Q',
            '7IS947A8S9H3E',
        ];

        self::$idInvalid = [
            1234,
            '',
            null,
        ];

        self::$tldValid = [
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
        ];

        self::$tldInvalid = [
            '.com.au',
            '.com.mx',
            '.nl',
            123,
            '',
        ];

        self::$revealValid = [
            'unpurchased',
            'purchased',
            'all',
        ];

        self::$revealInvalid = [
            'wishlist',
            'favourite',
            1234,
            '',
        ];

        self::$sortValid = [
            'date',
            'priority',
            'title',
            'price-low',
            'price-high',
            'updated',
        ];

        self::$sortInvalid = [
            1234,
            '',
            'not-valid',
        ];

        self::$affiliateTagValid = [
            'text-affiliate-tag',
            null,
        ];

        self::$affiliateTagInvalid = [
            '',
            1234,
        ];

        self::$wishlist = new AmazonWishlist(
            self::$idValid[0],
            self::$tldValid[0],
            self::$revealValid[0],
            self::$sortValid[0],
            null
        );
    }

    public function testSetIdValid()
    {
        foreach (self::$idValid as $id) {
            $this->assertTrue(self::$wishlist->setId($id));
        }
    }

    public function testSetIdInvalid()
    {
        foreach (self::$idInvalid as $id) {
            $this->expectException(\InvalidArgumentException::class);
            self::$wishlist->setId($id);
        }
    }

    public function testSetTldValid()
    {
        foreach (self::$tldValid as $tld) {
            $this->assertTrue(self::$wishlist->setAmazonTld($tld));
        }
    }

    public function testSetTldInvalid()
    {
        foreach (self::$tldInvalid as $tld) {
            $this->expectException(\InvalidArgumentException::class);
            self::$wishlist->setAmazonTld($tld);
        }
    }

    public function testSetRevealValid()
    {
        foreach (self::$revealValid as $reveal) {
            $this->assertTrue(self::$wishlist->setReveal($reveal));
        }
    }

    public function testSetRevealInvalid()
    {
        foreach (self::$revealInvalid as $reveal) {
            $this->expectException(\InvalidArgumentException::class);
            self::$wishlist->setReveal($reveal);
        }
    }

    public function testSetSortValid()
    {
        foreach (self::$sortValid as $sort) {
            $this->assertTrue(self::$wishlist->setSort($sort));
        }
    }

    public function testSetSortInvalid()
    {
        foreach (self::$sortInvalid as $sort) {
            $this->expectException(\InvalidArgumentException::class);
            self::$wishlist->setSort($sort);
        }
    }

    public function testSetAffiliateTagValid()
    {
        foreach (self::$affiliateTagValid as $affiliateTag) {
            $this->assertTrue(self::$wishlist->setAffiliateTag($affiliateTag));
        }
    }

    public function testSetAffiliateTagInvalid()
    {
        foreach (self::$affiliateTagInvalid as $affiliateTag) {
            $this->expectException(\InvalidArgumentException::class);
            self::$wishlist->setAffiliateTag($affiliateTag);
        }
    }

    public function testGetArray()
    {
        $wishlist = new AmazonWishlist('2EZ944B2S8C5Q');
        $wishlistArray = $wishlist->getArray();
        $this->assertTrue(is_array($wishlistArray));
    }

    public function testGetJson()
    {
        $wishlist = new AmazonWishlist('2EZ944B2S8C5Q');
        $wishlistJson = $wishlist->getJson();
        $this->assertTrue(is_string($wishlistJson));
    }

}
