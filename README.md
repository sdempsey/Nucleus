# Vital Blank WordPress Theme

This blank theme is the starting point for all Vital WordPress projects.

## Features

* CSS reset
* Custom CSS for editor [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/css/editor-style.css)
* `.group` class for clearing floats
* Styles for all core WordPress CSS classes
* Modernizr already enqueued
* htaccess boilerplate
* More image compression [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions.php#L70-L73)
* Custom login page [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions.php#L147-L161)
* Full cusomization of TinyMCE [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions.php#L164-L222)

## Functions

### Custom Pagination

No more "Older posts"/"Newer posts" links. [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions/pagination.php)

Usage: `<?php pagination(); ?>`

### Smart Excerpt

Returns an excerpt of a given length (in characters) and always ends with a complete sentence. [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions/extras.php#L3-L34)

Usage: `<?php vtl_smart_excerpt(50); ?>`

### Human-Friendly Post Dates

Prints human-friendly dates (ie. "2 days ago") if the post is less than one week old. Otherwise, it displays a standard datestamp. [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions/extras.php#L37-L56)

Usage: `<?php human_friendly_date(); ?>`

### Custom classes on individual widgets

A field available on all widgets allows the setting of custom CSS classes to better target specific widgets. [More info &rarr;](https://github.com/VitalDevTeam/vital-blank-wp-theme/blob/master/functions/extras.php#L101-L134)
