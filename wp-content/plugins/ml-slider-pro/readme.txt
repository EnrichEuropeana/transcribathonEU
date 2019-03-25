=== MetaSlider Pro ===

Requires at least: 3.5
Tested up to: 5.1
Stable tag: 2.13.1
Requires PHP: 5.2

Extends MetaSlider, allowing slides to be opened in a lightbox.

== Changelog ==

= 2.13.1 - 2019/Mar/20 =

* FIX: Fixes a bug where some users will see an error with Youtube

= 2.13.0 - 2019/Mar/19 =

* FEATURE: Adds lazy loading to YouTube videos
* TWEAK: Updates bundled updater class dependency to latest series (1.6.*)
* FIX: Updates computed ratio on videos to work with locale settings

= 2.12.0 - 2018/Dec/13 =

* FEATURE: Adds the ability to update a video url
* FEATURE: Adds the ability to mute a video on start
* FIX: Updates various Vimeo functionality to match their API changes
* FIX: Updates various YouTube functionality to match their API changes
* DEPRECATE: Remove showinfo option to hide title on YouTube video (https://developers.google.com/youtube/player_parameters#release_notes_08_23_2018)

= 2.11.0 - 2018/Nov/17 =

* FIX: Fixes issue where some themes would break the slides query
* FIX: Hides private and password protected post slides by default

= 2.10.1 - 2018/Oct/30 =

* FIX: Fixes a bug where some slides were not properly being hidden
* FIX: Fixes a bug where Vimeo slides report a fatal error

= 2.10.0 - 2018/Oct/25 =

* FEATURE: Adds option to make thumbs more responsive
* FIX - Fixes bug where jQuery $ is not defined
* COMPATIBILITY: Now requires MetaSlider base plugin 3.10.0+

= 2.9.2 - 2018/Sept/28 =

* FIX: Fixes bug where some slides would not save properly

= 2.9.1 - 2018/Sept/26 =

* FIX: Fixes bug where some slides scheduled with the standalone plugin previously would break
* FIX: Fixes bug where thumbs and filmstrip would show even when not scheduled

= 2.9.0 - 2018/Sept/24 =

* FEATURE: Adds ability to schedule a slide by day of the week
* FEATURE: Adds a clock showing the server time on the schedule tab
* TWEAK: Changes schedule query method to remove slow query
* FIX: Fixes how plugin handles when original schedule class is found
* FIX: Fixes bug when adding extra JS parameter hooks

= 2.8.0 - 2018/Sept/12 =

* FEATURE: Adds ability to change layer slide background
* FEATURE: Adds schedule functionality
* FEATURE: Adds the ability to toggle a slide's visibility
* TWEAK: Improves videos ratio code
* FIX: Adds specificity to the default selector

= 2.7.3 - 2017/Dec/15 =

* TWEAK: Fixes Youtube slide markup
* FIX: Adds and updates text-domain for translation support

= 2.7.2 - 2017/Nov/28 =

* FIX: Fixes abililty to navigate External Slide tabs

= 2.7.1 - 2017/Nov/14 =

* FEATURE: Allow a slide to be restored after deletion
* FEATURE: Attempts to make the UX elements more obvious
* FIX: Allow for https image URLs
* TWEAK: Changes the description and team name
* TWEAK: Turkish translation. Provided by Ali Sabri Gök
* TWEAK: Adds better update handling by checking version numbers

= 2.7 =
- New Pro slides will be added as a custom post type
- Add caption field to External Slides

2.6.8
- Add 'allowfullscreen' Vimeo attribute 

2.6.7
- Fix: Force https vimeo urls
- Fix: Workaround wptexturize and wpautop by removing && from video JavaScript
- Fix: Apply Smart Pad to post feed slides
- Fix: Apply metaslider_flex_slider_image_attributes and metaslider_responsive_slider_image_attributes filters to laye slides

2.6.6
- Fix PHP notice (wp-updates.com check)
- Fix black thumbnails (YouTube Player Update)
- Allow empty i tags in layer editor
- Fix PHP notice (deleting vimeo slide)

2.6.5
- Fix bug with multiple post feed slideshows and filmstrip thumbnails
- Add metaslider_tubeplayer_protocol filter
- Fix Vantage bug with YouTube first slide autoplay
- Remove TGM Plugin Activation class

2.6.4
- Add white as an option for text color in the layer editor
- Fix empty color block showing in the color picker

2.6.3
- Update TGM Plugin Activation class to latest (Security fix)

2.6.2
- Fix post feed captions

2.6.1
- Add URL parameter to metaslider_layer_video_attributes filter
- Fix conflict with slideshow parameters and post feed slides containing slideshows
- Add ability to use 'metaslider_post_feed_image' custom field to override featured image
- Add metaslider_layer_editor_font_sizes and metaslider_layer_editor_colors filters
- Fix post feed slide delete button

2.6
- Add change slide image option to vimeo, youtube and layer slides
- Video background option added to layer slides

2.5.1
- Fix post feed slide warning
- Use $ instead of jQuery in dynamic JS
- Allow links to be clicked in layers when a background image url is specified
- Fix YouTube slide error with R. Slides

2.5
- Hide events without thumbnails from post feed
- Add {thumb} template tag to post feed
- Refactor method for generating thumbnails
- Add External URL slide type
- Move classes from <img> to <li> for flexslider slideshows
- Change autoload from private to public

2.4.6
- Hide layers until slideshow is ready
- Re-crop thumbnails when original image is re-cropped
- Add metaslider_flex_slider_filmstrip_parameters filter

2.4.5
- Add filter for post feed args - see https://gist.github.com/tomhemsley/100930dafd179bae2d1d
- Run post feed content filter before the default tags have been parsed
- Fix Responsive Slides second animation flash

2.4.4
- Fix layer background link functionality
- Return filtered CSS correctly
- Don't clone layers without animation
- Fix navigation defaulting to thumbnails

2.4.3 (internal)
- Add actions for youtube & vimeo iframes

2.4.2
- Show warning in layer editor when no layer is selected
- Fix layer editor styling

2.4.1
- Fix downscale only setting
- Fix layer scaling initiation code

2.4
- Theme Editor refactored
-- Caption text align added
-- Caption border radius added
-- Custom Prev / Next arrows added
-- Enable or disable arrow / bullets / navigation custom styling

- Post Feed slides improved
-- Custom templates added
-- WooCommerce support added
-- Filters added for output (in line with standard image slides)

CKEditor updated to 4.4 (IE11 fixes)
Avoid wpautop errors with Layer Slides
Video slides now use jpeg mime type to avoid getID3 errors
Post Feed slides, call wp_reset_query after thumbnail extraction
Layer Editor: process qTranslate shortcodes
Added 'Loop' options for Flex & Nivo Slider
Fix HTTPS video previews
Check slideshow width and height before launching layer editor
Layer Slide scaling JS extracted to it's own jQuery plugin

2.3.2
- Post Feed: Fix Taxonomy restriction

2.3.1
- Menu Order added to Post Feed Slide
- Post Content (With Formatting) option added to Post Feed Slide

2.3
- Filmstrip navigation option added (Flex Slider)
- Layer Scaling options added

2.3-beta (internal)
- New Feature: Layer Slide background link, SEO options
- Change: Tabbed interface on all slides

2.2.8 (internal)
- Fix: Orderby parameter on Post Feed slides

2.2.7 (internal)
- Change: Add List item classes to slide types (flexslider only)

2.2.6 (internal)
- Change: Add metaslider_post_feed_caption filter

2.2.5 (internal)
- Fix: Vimeo auto play bug (When first slide is set to autoPlay)

2.2.4
- Fix: Allow layers to scale up past 100%

2.2.3
- Fix: Post Feed/Nivo Slider captions (for MetaSlider 2.6)

2.2.2
- Fix: PHP Warnings

2.2.1
- Fix: Invalid CSS

2.2
- New Feature: Auto Play setting for YouTube videos
- New Feature: Auto Play setting for Vimeo videos
- Fix: Force CKEditor to use 'en' lang files
- TGM Plugin activation check for MetaSlider Lite

2.1.2 (internal)
- Fix: WPML: Check 'is_plugin_active' function exists before calling

2.1.1 (internal)
- Change: Lang files removed from CKEditor to reduce plugin size
- Change: Images in Layers given a max-width
- Improvement: Fix to work with 'SvegliaT buttons' plugin

2.1 (internal)
- Improvement: YouTube & Vimeo settings
- Fix: Reset wp_query after post feed to fix comment setting on page

2.0.4
- Fix: Responsive layer scaling

2.0.3
- Fix: Strict warning for Walker Class compatibility (Since WP3.6 change)

2.0.2
- Improvement: "Title & Excerpt" option added for post feed caption
- Fix: Responsive slider - Pause Vimeo/YouTube when navigating to next slide

2.0.1
- Fix: Vimeo HTTPS
- Fix: Hover Pause is now compatible with YouTube slides (Flex Slider)
- Fix: Play/Pause video functionality and Auto Play (Flex Slider)
- Improvement: Responsive Slides output tidied up for YouTube & Vimeo slides

2.0
- New Feature: Thumbnail navigation for Flex & Nivo Slider
- Improvement: Pro functionality refactored into 'modules'
- Improvement: Theme editor CSS output tidied up
- Fix: YouTube thumbnail date
- Fix: YouTube videos on HTTPS

1.2.2
- Fix: Vimeo slideshows not pausing correctly

1.2.1
- Fix: Vertical slides with HTML Overlay not working
- Fix: YouTube & Vimeo slides not saving on some installations
- Change: Post Feed limit changed to 'number' input type

1.2
- WYSIWYG Editor Added to HTML Overlay slides
- Plugin localized
- Fix: Post Feeds now only count posts with featured images set

1.1.4
- Fix for YouTube and Vimeo slides when thumbnail download fails

1.1.3
- Youtube debug removed

1.1.2
- PHP Short tag fixed
- Theme editor CSS fixed
- "More Slide Types" menu item removed
- Alt text added to HTML Overlay slide type
- HTML Validation Fixes

1.1.1
- HTML Overlay bug fixed when slideshow has a single slide

1.1
- Theme Editor added
- Vimeo thumbnail loader now uses build in WordPress functionality

1.0.1
- Hide overflow on HTML Slides (to stop animations from 'leaking' into other slides)

1.0
- Initial Version

== Upgrade Notice ==
* 2.13.1 : Update now if you see an error notice regarding Youtube
