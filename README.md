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

The simplest way to implement the check is to use this JavaScript in the jQuery ready()
function:

    $('form').maxSubmit({max_count: 1000});
    
That will trigger on all forms, and warn the user if more than 1000 values are about to
be POSTed by the form. Additional settings allow you to modify the confirm box text,
or replace the standard confirm box with something more ambitious, such as a jquery.ui
dialog. You can target specific forms with different settings if you wish.

The server limit (1000 in this case) needs to be passed into the script above. This can
be found with a simple PHP function like this:

    /**
     * Get the submission limit.
     * Returns the lowest limit or false if no limit can be found.
     * An alternate default can be provided if required.
     * CHECKME: do we need to separate GET and POST limits, as they may apply
     * to different forms. The larger number of parameters is like to only
     * apply to POST forms, so POST is important. The REQUEST max vars is 
     * another thing to consider, as it will be the sum of GET and POST parameters.
     */
    function getFormSubmissionLimit($default = false)
    {
        // All these ini settings will affect the number of parameters that can be
        // processed. Check them all to find the lowest.
        $ini = array();
        $ini[] = ini_get('max_input_vars');
        $ini[] = ini_get('suhosin.get.max_vars');
        $ini[] = ini_get('suhosin.post.max_vars');
        $ini[] = ini_get('suhosin.request.max_vars');

        $ini = array_filter($ini, 'is_numeric');

        $lowest_limit = min($ini);

        return ($lowest_limit === false ? $default : $lowest_limit);
    }

That runs on the server and provides the server settings to insert into the JavaScript
initialisation, and will return 1000 by default on most PHP servers.

A simnple demo (index.php in this project) is running here: [http://www.acadweb.co.uk/maxsubmit/]

The jQuery plugin and the PHP function are the two building blocks. I intend to wrap them into a
simple WordPress plugin next. Just install it along with your WooCommerce plugin, and it will stop
you breaking your products with dozens of variations. Christmas may get in the way first ;-)
