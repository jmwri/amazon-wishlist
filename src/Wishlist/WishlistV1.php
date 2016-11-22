<?php

namespace JmWri\AmazonWishlist\Wishlist;


use JmWri\AmazonWishlist\WishlistItem;

/**
 * Class WishlistV1
 * @package JmWri\AmazonWishlist\Wishlist
 */
class WishlistV1 extends BaseWishlist
{

    /**
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    protected function getWishlistItems()
    {
        return pq('tbody.itemWrapper');
    }

    /**
     * @param string $itemHtml
     * @param int $page
     * @param bool $getAuthor
     *
     * @return false|WishlistItem
     */
    protected function getWishlistItem($itemHtml, $page, $tag, $getAuthor)
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
        $wishlistItem->setAsin($this->getAsin($wishlistItem->getLink()));
        $wishlistItem->setLargeSslImage($this->getLargeSslImage($wishlistItem->getPicture()));
        $wishlistItem->setAffiliateUrl($this->source->getAffiliateLink($wishlistItem->getAsin(), $tag));
        if ($getAuthor) {
            $wishlistItem->setAuthor($this->getAuthor($wishlistItem->getLink()));
        }

        return $wishlistItem;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return parent::checkPageCount(count(pq('.pagDiv .pagPage')));
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function getAuthor($url)
    {
        $productPage = $this->source->getDocumentFile($url);

        return trim(
            str_replace(
                '(Author)',
                '',
                trim(
                    pq($productPage)->find('#byline .author .a-popover-preload .a-size-medium')->text()
                )
            )
        );
    }

}
