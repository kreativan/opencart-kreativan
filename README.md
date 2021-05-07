# Kreativan OpenCart UIkit
This project is complete opencart front-end rebuild/rewrite with uikit front-end framework, removing jquery, bootstrap and other dependencies. All (well 99% :)) of the jquery code is replaced with vanilla js, css and uikit is parsed with integrated Less compiler based on wikimedia php less processor https://github.com/wikimedia/less.php. Custom modifications, admin pages, features, modules are included.     

This project is suitable for building custom opencart shops and is not compatible wtih 3rd party themes and bootstrap dependent modules... Don't use it on production websites please!!! If you are curious, test it, and use it on your own risk...

Motivation for this projects was to learn a bit of opencart, so complete front-end rewrite sounded like a good way to do it :). And also, opencart need some modernization as it still runs on bootstrap 3 and jquery...

## Features

* All new front-end
* No jQuery & no Old Bootstrap
* UIkit Framework
* Custom admin menu
* Custom Settings
* Hero - Uikit slideshow on homepage based on opencart banners
* Popup - Display modal popup on page load
* Resize Model Tool - Resize images and keep aspect ration
* Less Parser - compile less files
* Dynamic multi-level offcanvas category menu that can be used for mobile and desktop
* Dynamic global and user menu for mobile devices
* AiO - All in One Products display module


## How to install

Don't dowload the repo and try to install it, it will not work! Download the release, because zip file needs to be named as `*.ocmod.zip`

* Do to the opencart directory restrictions (a bug?), need to install this extension first:    
  https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=33410
* Go to *Extensions -> Modifications* and refresh. 
* Upload `kreativan-oc-framework.ocmod.zip` via extension installler. *Extensions -> installer*
* At this point if you visit store front-end, you will get an error, dont worry, this is do to the custom files and modifications. Need to install and activate the theme, so continue...
* Go to *Extensions -> Modifications* and refresh.
* Go to *Extensions -> Extensions* and select extension type *Themes*
* Install and enable *Kreativan Framework*
* Go to *System -> Settings -> Your Store* and select *Kreativan Framework* theme.
* Go to *System -> Users -> User Groups* and add acceess and modify permissions for the custom pages. Just click 'Select All' and save :)

## Known Bugs
* After install, default theme does not work... ooops :). It's do to the modifications (ocmod). To fix this simply delete `Kreativan Framework` modifications
* File upload field on product page as product option not working
* Datetime custom field on checkout not working