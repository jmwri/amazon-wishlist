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
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return false|WishlistItem
     */
    protected function getWishlistItem($itemHtml, $page, $getIsbn, $getAuthor)
    {
        $name = htmlentities(
            trim(
                pq($itemHtml)->find('a[id^="itemName_"]')->html()
            )
        );
        $link = pq($itemHtml)->find('a[id^="itemName_"]')->attr('href');

        if (! $name || ! $link) {
            return false;
        }

        $total_ratings = pq($itemHtml)
            ->find('div[id^="itemInfo_"] div:a-spacing-small:first a.a-link-normal:last')->html();
        $total_ratings = trim(
            str_replace(
                array('(', ')'),
                '',
                $total_ratings
            )
        );
        $total_ratings = is_numeric($total_ratings) ? $total_ratings : '';

        $wishlistItem = new WishlistItem();
        $wishlistItem->setName($name);
        $wishlistItem->setLink($this->amazonWishlist->getBaseUrl() . $link);
        $wishlistItem->setOldPrice('N/A');
        $wishlistItem->setNewPrice(trim(pq($itemHtml)->find('span[id^="itemPrice_"]')->html()));
        $wishlistItem->setDateAdded(trim(
                str_replace(
                    'Added',
                    '',
                    pq($itemHtml)->find('div[id^="itemAction_"] .a-size-small')->html())
            )
        );
        $wishlistItem->setPriority(trim(
            pq($itemHtml)->find('span[id^="itemPriorityLabel_"]')->html()
        ));
        $wishlistItem->setRating('N/A');
        $wishlistItem->setTotalRatings($total_ratings);
        $wishlistItem->setComment(trim(
            pq($itemHtml)->find('span[id^="itemComment_"]')->html()
        ));
        $wishlistItem->setPicture(pq($itemHtml)->find('div[id^="itemImage_"] img')->attr('src'));
        $wishlistItem->setPage($page);
        $wishlistItem->setAsin($this->getASIN($wishlistItem->getLink()));
        $wishlistItem->setLargeSslImage($this->getLargeSslImage($wishlistItem->getPicture()));
        $wishlistItem->setAffiliateUrl($this->getAffiliateLink($wishlistItem->getAsin()));
        if ($getIsbn) {
            $wishlistItem->setIsbn($this->getISBN($wishlistItem->getLink()));
        }
        if ($getAuthor) {
            $setAuthor = pq($itemHtml)
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
