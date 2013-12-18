jquery-maxsubmit
================

jQuery plugin to detect if too many form items will be submitte for the server to cope with.

Some appications, such as ecommerce sites, can have administration forms that submit well
over a thousand parameters. PHP, by default, is set to accept only one thousand parameters
and so some of the submitted data can get lost.

Most applications don't check whether they received everything, and so data can get broken
easily and silently. A WooCommerce product with 40 variations can have over 1300 submitted
form items, and when saving the product you have no idea that much of that data is being
discarded.

Luckily the maximum number of accepted parameters can be changed in php.ini The problem is,
many site owners have no idea this needs to be done until it is too late and their
WooCommerce store has lost half its product variations.

What this jQuery plugin attempts to do, is warn the site administrator before a form is
submitted, on the client (browser) side, and give the administrator a chance to cancel the
submit and change the settings on the server. It does this by counting how many items
will be submitted in a form (it does this, hopefully, intellidently by taking into account
all the form item types and selected values). The plugin is given the maximum number of
items the server will accept when the page is generated, so it has a number to compare to.
