<?php

namespace JmWri\AmazonWishlist\Wishlist;

use JmWri\AmazonWishlist\AmazonWishlist;
use JmWri\AmazonWishlist\AmazonWishlistException;
use JmWri\AmazonWishlist\PhpQueryTrait;
use JmWri\AmazonWishlist\WishlistItem;

/**
 * Class BaseWishlist
 * @package JmWri\AmazonWishlist\Wishlist
 */
abstract class BaseWishlist
{

    use PhpQueryTrait;

    /**
     * @var AmazonWishlist
     */
    protected $amazonWishlist;

    /**
     * BaseWishlist constructor.
     *
     * @param AmazonWishlist $amazonWishlist
     */
    public function __construct(& $amazonWishlist)
    {
        $this->amazonWishlist = $amazonWishlist;
    }

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

            $items = $this->getWishlistItems();

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
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    abstract protected function getWishlistItems();

    /**
     * @param string $itemHtml
     * @param int $page
     * @param bool $getIsbn
     * @param bool $getAuthor
     *
     * @return false|WishlistItem
     */
    abstract protected function getWishlistItem($itemHtml, $page, $getIsbn, $getAuthor);

    /**
     * @param int $count
     *
     * @return int
     */
    protected function checkPageCount($count = 0)
    {
        return $count > 0 ? $count : 1;
    }

    /**
     * Get the ASIN of the product
     *
     * ASIN is a 10 character string - https://en.wikipedia.org/wiki/Amazon_Standard_Identification_Number
     * Typical URls
     * http://www.amazon.co.uk/dp/B00IKIKDOM/ref=wl_it_dp_v_nS_ttl/278-9067265-3899254?_encoding=UTF8&colid=15SCUHW5RONL6&coliid=ICBVF90YVEFF4&psc=1
     * http://www.amazon.co.jp/dp/B00OOD4RLC/ref=wl_it_dp_v_S_ttl/376-5041442-9894656?_encoding=UTF8&colid=HIOT27YDKDXW&coliid=I2199742B598MC
     * http://www.amazon.de/dp/B00TRQ0CSI/ref=wl_it_dp_v_nS_ttl/275-4926214-1753269?_encoding=UTF8&colid=X82UNL4VMFM9&coliid=I2G0K6DW0S1MHT
     * http://www.amazon.ca/dp/B00A7WDYYU/ref=wl_it_dp_v_nS_ttl/180-5102401-8253319?_encoding=UTF8&colid=3OR5VN6044A6I&coliid=I30UZD33GMHBK3
     * http://www.amazon.com/dp/B00DQYNKCM/ref=wl_it_dp_v_nS_ttl/191-5492771-2500240?_encoding=UTF8&colid=37XI10RRD17X2&coliid=ILV2H2MHRX7HU&psc=1
     * http://www.amazon.es/dp/B007RXO716/ref=wl_it_dp_v_nS_ttl/279-0188662-6542856?_encoding=UTF8&colid=1ORXZQUAJ8H96&coliid=I35SODJIWT2DTA
     * http://www.amazon.in/dp/B00E81GGGY/ref=wl_it_dp_v_nS_ttl/275-0633160-8128200?_encoding=UTF8&colid=1HCKFSCVFG2UW&coliid=IUH4Z2TCPKDF2
     * http://www.amazon.it/dp/B00Y0O5L6U/ref=wl_it_dp_v_nS_ttl/280-4610661-1922667?_encoding=UTF8&colid=2RPUB231AJ78D&coliid=IFOHKF3REGMUU&psc=1
     * http://www.amazon.com.br/dp/B00YSILJZU/ref=wl_it_dp_v_nS_ttl/177-3586976-1287133?_encoding=UTF8&colid=3OF5TPV1ZMWLM&coliid=I36RLGSQVQOUYH
     * http://www.amazon.fr/dp/B003FRXFWK/ref=wl_it_dp_v_S_ttl/275-1202652-6343230?_encoding=UTF8&colid=1EHE58O7QKWTH&coliid=I3EGD6MPOTC9GY
     *
     * @param string $url
     *
     * @return string
     */
    protected function getASIN($url)
    {
        $ASIN = str_replace("{$this->amazonWishlist->getBaseUrl()}/dp/", '', $url);
        $ASIN = substr($ASIN, 0, 10);

        return $ASIN;
    }

    /**
     * Change:
     *      http://ecx.images-amazon.com/images/I/41kWB4Z4PTL._SL250_.jpg
     * To:
     *      https://images-eu.ssl-images-amazon.com/images/I/41kWB4Z4PTL._SL2500_.jpg
     *
     * Image URLs are always .com for some reason.
     *
     * @param string $imageUrl
     *
     * @return string
     */
    protected function getLargeSslImage($imageUrl)
    {
        $largeSSLImage = str_replace(
            'http://ecx.images-amazon.com',
            'https://images-eu.ssl-images-amazon.com',
            $imageUrl
        );
        $largeSSLImage = str_replace(
            '_.jpg',
            '0_.jpg',
            $largeSSLImage
        );

        return $largeSSLImage;
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
     * @param string $AISN
     *
     * @return string
     */
    protected function getAffiliateLink($AISN)
    {
        return "{$this->amazonWishlist->getBaseUrl()}/dp/{$AISN}/ref=nosim?"
                . "{$this->amazonWishlist->getAffiliateTagParam()}";
    }

    /**
     * Go to product details page for ISBN
     *
     * @param string $url
     *
     * @return string
     */
    protected function getISBN($url)
    {
        $productPage = $this->getDocumentFile($url);

        return trim(
            str_replace('ISBN-13:', '',
                trim(
                    pq($productPage)->find('.bucket .content li:has(b:contains("ISBN-13"))')->text()
                ))
        );
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function getAuthor($url)
    {
        $productPage = $this->getDocumentFile($url);

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
