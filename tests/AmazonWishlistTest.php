<?php

namespace JmWri\AmazonWishlist\Test;

use JmWri\AmazonWishlist\AmazonWishlist;

/**
 * Class AmazonWishlistTest
 * @package JmWri\AmazonWishlist\Test
 */
class AmazonWishlistTest extends BaseTest
{

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
