<?php

namespace JmWri\AmazonWishlist\Test;

use JmWri\AmazonWishlist\AmazonWishlist;
use JmWri\AmazonWishlist\Source\AmazonSource;
use JmWri\AmazonWishlist\Source\FileSource;
use JmWri\AmazonWishlist\WishlistItem;

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
     * @var string[]
     */
    protected static $tldValid;

    /**
     * @var string[]
     */
    protected static $revealValid;

    /**
     * @var string[]
     */
    protected static $sortValid;

    /**
     * @var string|null[]
     */
    protected static $affiliateTagValid;

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

        self::$revealValid = [
            'unpurchased',
            'purchased',
            'all',
        ];

        self::$sortValid = [
            'date',
            'priority',
            'title',
            'price-low',
            'price-high',
            'updated',
        ];

        self::$affiliateTagValid = [
            'text-affiliate-tag',
            null,
        ];

        $source = new AmazonSource(self::$idValid[0], self::$tldValid[0]);
        self::$wishlist = new AmazonWishlist(
            $source,
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

    public function testSetIdInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setId(1234);
    }

    public function testSetIdInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setId('');
    }

    public function testSetIdInvalidNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setId(null);
    }

    public function testSetTldValid()
    {
        foreach (self::$tldValid as $tld) {
            $this->assertTrue(self::$wishlist->setTld($tld));
        }
    }

    public function testSetTldInvalidString()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setTld('not valid');
    }

    public function testSetTldInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setTld(1234);
    }

    public function testSetTldInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setTld('');
    }

    public function testSetRevealValid()
    {
        foreach (self::$revealValid as $reveal) {
            $this->assertTrue(self::$wishlist->setReveal($reveal));
        }
    }

    public function testSetRevealInvalidString()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setReveal('wishlist');
    }

    public function testSetRevealInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setReveal(1234);
    }

    public function testSetRevealInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setReveal('');
    }

    public function testSetSortValid()
    {
        foreach (self::$sortValid as $sort) {
            $this->assertTrue(self::$wishlist->setSort($sort));
        }
    }

    public function testSetSortInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setSort(1234);
    }

    public function testSetSortInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setSort('');
    }

    public function testSetSortInvalidString()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setSort('not valid');
    }

    public function testSetAffiliateTagValid()
    {
        foreach (self::$affiliateTagValid as $affiliateTag) {
            $this->assertTrue(self::$wishlist->setAffiliateTag($affiliateTag));
        }
    }

    public function testSetAffiliateTagInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setAffiliateTag('');
    }

    public function testSetAffiliateTagInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$wishlist->setAffiliateTag(1234);
    }

    public function testGetArrayAmazonSource()
    {
        $source = new AmazonSource('2EZ944B2S8C5Q');
        $wishlist = new AmazonWishlist($source);
        $wishlistArray = $wishlist->getArray(true);
        $this->assertTrue(is_array($wishlistArray));
        $this->assertEquals(4, count($wishlistArray));
    }

    public function testGetJsonAmazonSource()
    {
        $source = new AmazonSource('2EZ944B2S8C5Q');
        $wishlist = new AmazonWishlist($source);
        $wishlistJson = $wishlist->getJson(true);
        $wishlistArray = json_decode($wishlistJson, true);
        $this->assertTrue(is_array($wishlistArray));
        $this->assertEquals(4, count($wishlistArray));
    }

    public function testGetArrayFileSource()
    {
        $source = new FileSource(__DIR__ . '/html/wishlist_v2');
        $wishlist = new AmazonWishlist($source);
        $wishlistArray = $wishlist->getArray(true);
        $this->assertTrue(is_array($wishlistArray));
        $this->assertEquals(4, count($wishlistArray));
    }

    public function testGetJsonFileSource()
    {
        $source = new FileSource(__DIR__ . '/html/wishlist_v2');
        $wishlist = new AmazonWishlist($source);
        $wishlistJson = $wishlist->getJson(true);
        $wishlistArray = json_decode($wishlistJson, true);
        $this->assertTrue(is_array($wishlistArray));
        $this->assertEquals(4, count($wishlistArray));
    }

    public function testGetAuthor()
    {
        $source = new AmazonSource('2EZ944B2S8C5Q');
        $wishlist = new AmazonWishlist($source);
        /**
         * @var WishlistItem[]
         */
        $wishlist = $wishlist->getObject(true);

        /**
         * @var WishlistItem
         */
        $wishlistItem = $wishlist[1];
        $this->assertTrue(is_array($wishlist));
        $this->assertEquals('Node.js for Embedded Systems', $wishlistItem->getName());
        $this->assertEquals('Patrick Mulder', $wishlistItem->getAuthor());
    }

}
