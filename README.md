Author: Ian Ooi (https://github.com/ionaic)
2015

This is a simple PHP script for embedding some number of posts from a tumblr blog into a website.  I looked and couldn't find any so I wrote one for my website.

To use, simply modify the file to include your tumblr API key, the desired number of posts, and your blog url, then include as below wherever you wish the posts to be embedded in your page.

You will need an API key from Tumblr, which can be obtained at: https://www.tumblr.com/oauth/apps

The blog url should be in the form: abcde.tumblr.com

Further documentation on the API used by this script can be found at: https://www.tumblr.com/docs/en/api/v2

CSS file included which contains empty rules for every class created by the PHP script.

Two rules are left defined, .tumblr-tag:before and .tumblr-quote-source:before for providing a hash (#) before each tag and a dash (-) before the source of a quote.  Remove or change these as desired, I just found those to be generally useful and universal.
