<?php

namespace JmWri\AmazonWishlist\Wishlist;


use JmWri\AmazonWishlist\WishlistItem;

/**
 * Class WishlistV2
 * @package JmWri\AmazonWishlist\Wishlist
 */
class WishlistV2 extends BaseWishlist
{

    /**
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    protected function getWishlistItems()
    {
        return pq('.g-items-section div[id^="item_"]');
    }

    /**
     * @param string $itemHtml
     * @param int $page
     * @param string $tag
     * @param bool $getAuthor
     *
     * @return false|WishlistItem
     */
    protected function getWishlistItem($itemHtml, $page, $tag, $getAuthor)
    {
        $itemPq = pq($itemHtml);
        $name = htmlentities(
            trim(
                $itemPq->find('a[id^="itemName_"]')->html()
            )
        );
        $link = $itemPq->find('a[id^="itemName_"]')->attr('href');

        $rating = $itemPq->find('.a-icon-star')->contents()->html();
        $matches = array();
        preg_match('/[0-5].[0-9]/', $rating, $matches);
        $rating = count($matches) ? $matches[0] : 'N/A';

        $totalRatings = $itemPq->contents()->html();
        $matches = array();
        preg_match('/\(([0-9]|,)+\)/', $totalRatings, $matches);
        $totalRatings = str_replace(
            array('(', ')'),
            '',
            count($matches) ? $matches[0] : ''
        );
        $totalRatings = trim($totalRatings);

        $wishlistItem = new WishlistItem();
        $wishlistItem->setName($name);
        $wishlistItem->setLink($this->source->getFullLink($link));
        $wishlistItem->setOldPrice('N/A');
        $wishlistItem->setNewPrice(trim($itemPq->find('span[id^="itemPrice_"]')->html()));
        $wishlistItem->setDateAdded(trim(
                str_replace(
                    'Added',
                    '',
                    $itemPq->find('div[id^="itemAction_"] .a-size-small')->html())
            )
        );
        $wishlistItem->setPriority(trim(
            $itemPq->find('span[id^="itemPriorityLabel_"]')->html()
        ));
        $wishlistItem->setRating($rating);
        $wishlistItem->setTotalRatings($totalRatings);
        $wishlistItem->setComment(trim(
            $itemPq->find('span[id^="itemComment_"]')->html()
        ));
        $wishlistItem->setPicture($itemPq->find('div[id^="itemImage_"] img')->attr('src'));
        $wishlistItem->setPage($page);
        $wishlistItem->setAsin($this->getAsin($wishlistItem->getLink()));
        $wishlistItem->setLargeSslImage($this->getLargeSslImage($wishlistItem->getPicture()));
        $wishlistItem->setAffiliateUrl($this->source->getAffiliateLink($wishlistItem->getAsin(), $tag));
        if ($getAuthor) {
            $setAuthor = $itemPq
                ->find('div[id^="itemInfo_"] .a-row.a-size-small:has(h5 a[id^="itemName_"])');
            $setAuthor->find('h5')->remove();
            $setAuthor = trim(
                preg_replace('/\([\ \w]+\)/',
                    '',
                    str_replace('by', '', $setAuthor->text())
                )
            );
            $wishlistItem->setAuthor($setAuthor);
        }

        return $wishlistItem;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return parent::checkPageCount(count(pq('#wishlistPagination li[data-action="pag-trigger"]')));
    }

}
