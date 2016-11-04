<?php

namespace JmWri\AmazonWishlist\Wishlist;


use JmWri\AmazonWishlist\AmazonWishlistException;
use JmWri\AmazonWishlist\WishlistItem;

/**
 * Class WishlistV1
 * @package JmWri\AmazonWishlist\Wishlist
 */
class WishlistV1 extends BaseWishlist
{

    /**
     * @param string $url
     * @param int $pages
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return WishlistItem[]
     * @throws AmazonWishlistException
     */
    public function getWishlist($url, $pages, $getIsbn = false, $getAuthor = false)
    {
        $wishlistItems = [];

        for ($page = 1; $page <= $pages; $page++) {
            $contents = $this->getDocumentFile("{$url}&page=$page");
            if ($contents == '') {
                throw new AmazonWishlistException('Failed to load a page of the wishlist');
            }

            // Get all items
            $items = pq('tbody.itemWrapper');

            foreach ($items as $item) {
                $wishlistItem = $this->getWishlistItem($item, $page, $getIsbn, $getAuthor);
                if ($wishlistItem) {
                    $wishlistItems[] = $wishlistItem;
                }
            }
        }

        return $wishlistItems;
    }

    /**
     * @param string $itemHtml
     * @param int $page
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return false|WishlistItem
     */
    protected function getWishlistItem($itemHtml, $page, $getIsbn, $getAuthor)
    {
        $check_if_regular = pq($itemHtml)->find('span.commentBlock nobr');

        if ($check_if_regular == '') {
            return false;
        }

        $wishlistItem = new WishlistItem();
        $wishlistItem->setName(trim(pq($itemHtml)->find('span.productTitle strong a')->html()));
        $wishlistItem->setLink(pq($itemHtml)->find('span.productTitle a')->attr('href'));
        $wishlistItem->setOldPrice(pq($itemHtml)->find('span.strikeprice')->html());
        $wishlistItem->setNewPrice(trim(pq($itemHtml)->find('span[id^="itemPrice_"]')->html()));
        $wishlistItem->setDateAdded(trim(
            str_replace(
                'Added',
                '',
                pq($itemHtml)->find('span.commentBlock nobr')->html()
            )
        ));
        $wishlistItem->setPriority(pq($itemHtml)->find('span.priorityValueText')->html());
        $wishlistItem->setRating(pq($itemHtml)->find('span.asinReviewsSummary a span span')->html());
        $wishlistItem->setTotalRatings(pq($itemHtml)->find('span.crAvgStars a:nth-child(2)')->html());
        $wishlistItem->setComment(trim(
            pq($itemHtml)->find('span.commentValueText')->html()
        ));
        $wishlistItem->setPicture(pq($itemHtml)->find('td.productImage a img')->attr('src'));
        $wishlistItem->setPage($page);
        $wishlistItem->setAsin($this->getASIN($wishlistItem->getLink()));
        $wishlistItem->setLargeSslImage($this->getLargeSslImage($wishlistItem->getPicture()));
        $wishlistItem->setAffiliateUrl($this->getAffiliateLink($wishlistItem->getAsin()));
        if ($getIsbn) {
            $wishlistItem->setIsbn($this->getISBN($wishlistItem->getLink()));
        }
        if ($getAuthor) {
            $wishlistItem->setAuthor($this->getAuthor($wishlistItem->getLink()));
        }

        $wishlistItems[] = $wishlistItem;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return parent::checkPageCount(count(pq('.pagDiv .pagPage')));
    }

}
