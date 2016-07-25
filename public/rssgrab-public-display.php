<?php

if(function_exists('fetch_feed')) {
    $options = get_option('rssgrab_settings');

    $rss = fetch_feed($options['rssgrab_feed']);

    if (!is_wp_error($rss)) {
        $max = $rss->get_item_quantity(10);
        $items = $rss->get_items(0, $max);
    }

    echo '<h3>' . $rss->get_title() . '</h3>' .
         '<dl>';
    if($max == 0) {
        // If the feed doesn't load.
        echo '<dt>Error with feed.</dt>';
    } else foreach ($items as $item) {
        // Otherwise echo out the items.
        echo '<dt>' .
                '<a href="' . $item->get_permalink(). '" title="' . $item->get_permalink() . '">' .
                $item->get_title() .
                '</a>' .
                '<br />' .
                $item->get_date('j F Y @ g:i a') .
            '</dt>' .
            '<dd>' .
                $item->get_description() .
            '</dd>';
    }
    echo '</dl>';
}
?>
