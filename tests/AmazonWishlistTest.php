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
        $wishlist = new AmazonWishlist();
        $wishlistArray = $wishlist->getArray();
        $this->assertTrue(is_array($wishlistArray));
    }

    public function testGetJson()
    {
        $wishlist = new AmazonWishlist();
        $wishlistJson = $wishlist->getJson();
        $this->assertTrue(is_string($wishlistJson));
    }

}
