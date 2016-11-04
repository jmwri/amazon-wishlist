# Amazon Wishlist

[![Build Status](https://travis-ci.org/jmwri/amazon-wishlist.svg?branch=master)](https://travis-ci.org/jmwri/amazon-wishlist)
[![Total Downloads](https://poser.pugx.org/jmwri/amazon-wishlist/d/total.svg)](https://packagist.org/packages/jmwri/amazon-wishlist)
[![Latest Stable Version](https://poser.pugx.org/jmwri/amazon-wishlist/v/stable.svg)](https://packagist.org/packages/jmwri/amazon-wishlist)
[![Latest Unstable Version](https://poser.pugx.org/jmwri/amazon-wishlist/v/unstable.svg)](https://packagist.org/packages/jmwri/amazon-wishlist)
[![License](https://poser.pugx.org/jmwri/amazon-wishlist/license.svg)](https://packagist.org/packages/jmwri/amazon-wishlist)

**https://github.com/jmwri/amazon-wishlist**

This package is used to retrieve Amazon Wishlist data. There is no official API so this package uses web scraping. It works with the old and the new Amazon Wishlist design.

The following Amazon stores have wishlist functionality:
* UK
* USA
* Canada
* Brazil
* Japan
* Germany
* France
* India
* Italy
* Spain

Amazon Wishlist uses [phpQuery](http://code.google.com/p/phpquery/) (server-side CSS3 selector driven DOM API based on jQuery) to scrape Amazon's Wishlist page.

* Scrapes the following from your Amazon Wishlist:
    * Item name
    * Item link
    * Price of item when added to wish list
    * Current price of item
    * Date added to wish list
    * Priority (set by you)
    * Item rating
    * Total ratings
    * Comments on item (set by you)
    * Picture of item
* Great to host your own wishlist
* I recommend you cache so as not to hit Amazon too much
* Supports multi-page Amazon Wishlist's as well as Amazon Wishlist 'ideas'

# Getting Started

To get started, you need to create an instance of `AmazonWistlist`. The minimum required parameters are `$id` and `$tld`. There is more information on all parameters below.

## Amazon ID
You can find your Amazon ID by navigating to your wishlist. You will see a URL similar to `https://www.amazon.co.uk/gp/registry/wishlist/2EZ944B2S8C5Q/ref=cm_wl_list_o_0?`. My Amazon ID from that URL is `2EZ944B2S8C5Q` so you can grab yours from there.

## Amazon Country
The `tld` parameter is used to target the right Amazon region. For example, the UK website is `www.amazon.co.uk` so the UK TLD is `.co.uk`.

Defaults to `.co.uk`.

Tested with the following TLDs:
* `.co.uk`
* `.com`
* `.ca`
* `.com.br`
* `.co.jp`
* `.de`
* `.fr`
* `.in`
* `.it`
* `.es`

The following regions do not currently offer wishlists:
* `.com.au`
* `.com.mx`
* `.nl`

## Reveal
With the `reveal` parameter you can filter which wishlist items to keep.

Defaults to `unpurchased`.

Options:
* `unpurchased`
* `purchased`
* `all`  

## Sort
The `sort` parameter dictates which order the wishlist items will be returned in.

Defaults to `date`.

Options
* `date`
* `priority`
* `title`
* `price-low` low to high
* `price-high` high to low
* `updated`


## Amazon Associate / Affiliate tag
You can optionally pass your affiliate tag to the `$affiliateTag` parameter. This parameter has no default.

## Output Formats
Currently Amazon Wishlist supports the following formats:
* `array`
* `json`

# Usage
## Source
```
$wishlist = new AmazonWishlist();
$json = $wishlist->getJson(true, true); // $getIsbn, $getAuthor
echo $wishlistArray;
```

## Output
```
[
  {
    "name": "Jim Dunlop Nylon Guitar Picks \/ Plectrums (Handy pack of 6 Picks)",
    "link": "http:\/\/www.amazon.co.uk\/dp\/B000Q8479G\/ref=wl_it_dp_v_nS_ttl\/254-6401618-7451003?_encoding=UTF8&colid=2EZ944B2S8C5Q&coliid=IRWRMX6CVN0BT",
    "oldPrice": "N\/A",
    "newPrice": "\u00a32.29",
    "dateAdded": "2 November, 2016",
    "priority": "",
    "rating": "N\/A",
    "totalRatings": "",
    "comment": "",
    "picture": "https:\/\/images-eu.ssl-images-amazon.com\/images\/I\/21dxJZ3XQfL._SL500_SL135_.jpg",
    "page": 1,
    "asin": "B000Q8479G",
    "largeSslImage": "https:\/\/images-eu.ssl-images-amazon.com\/images\/I\/21dxJZ3XQfL._SL500_SL1350_.jpg",
    "affiliateUrl": "http:\/\/www.amazon.co.uk\/dp\/B000Q8479G\/ref=nosim?",
    "isbn": null,
    "author": null
  }
]
```

# Support
https://github.com/jmwri/amazon-wishlist/issues