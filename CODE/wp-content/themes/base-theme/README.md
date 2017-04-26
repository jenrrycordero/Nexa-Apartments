#General Instructions
General documentation about the Razz Theme and how to use it and develop on top of it. Please check this guide for any question.




##Requirements & Libraries
- **Requirements**
    - PHP > 5.4
    - [Composer](https://getcomposer.org/)
    - [Gulp](http://gulpjs.com/)
    - [Node](https://nodejs.org/en/)
    - [Ruby](http://rubyonrails.org/)
- **Base libraries/plugins**
    - [ACF](https://www.advancedcustomfields.com/)
    - [Bootstrap 3.X](http://getbootstrap.com/)
    - [Modernizr 2.X](https://modernizr.com/)
    - [isMobile](https://github.com/kaimallea/isMobile)
    - [Owl Carousel](http://www.owlcarousel.owlgraphic.com)
    - [Video background]() **PENDING**
- **Integrated Components**
    - [Compass](http://compass-style.org/)

##Manual Reference
1. [Folder structure](#folder)
2. [General Steps](#general)
3. [Styles](#style)
4. [Scripts](#script)
5. [ACF](#acf)
6. [Templates and Code structure](#code)
7. [Custom Post Types](#cpt)
8. [External Libraries](#lib)
9. [Plugins Used](#plugins)
10. [Guidelines](#notes)
11. [Gulp](#gulp)
12. [Todo](#todo)
13. [Bugs](#bugs)
14. [Extra documentation](//github.com/razzinteractive/base-theme/tree/master/documentation):
    - [Shortcodes](//github.com/razzinteractive/base-theme/tree/master/documentation/shortcodes.md)

##<a name="folder"></a>Folder structure
- acf: Contains the ACF Plugin. Any update on the pluggin should be added there
- acf-fields: All the Fields definitions. Any .php file will be automatically loaded.
- assets: Contains all the assets of the theme.
    - conditionizr: This folder contains all the scripts/css to be loaded through the [conditionizr](https://github.com/conditionizr/conditionizr/) library according to his tests.
    - fonts: Any font definitions
    - img: img used on the theme.
    - lib: contain any external library. (drop here any library that require a folder structure, like those who include css or images)
    - scripts: generic scripts/libraries. (drop here any script library that can standalone with any other file or assets)
    - style: generic style/libraries.
- code-ajax: All the AJAX endpoints handlers (recommended to use Classes).
- code-renders: All the Renders Functions theme-specific. (recommended to use Classes).
- code-shortcodes: All the Shortcode code. Generally they will be Classes containing said shortcodes, but they can also be simple files. Please check the specific documentation for the Shortcodes
- cpt: Contains any Custom Post Type used on the theme. Each CPT should be contained on a folder with the cpt-type as the name of the folder.
- dist: Contains all the compiled files (js and css) for the theme. They are compiled through Gulp, [see reference](#gulp)
- languages: Contain all the language translations string if any. Used with the wp function _load_theme_textdomain()_
- scripts: All the scripts used on the theme. They will be minified through GULP and output to the _dist/_ folder. If you **DONT** want this behavior please rename the file with the prefix _ (__ignoreFile.js_);
- scss: All SCSS files, they will be compiled through GULP and output to the _dist/_ folder. If you **DONT** want this behavior please rename the file with the prefix _ (__ignoreFile.css_);
- templates: Contains the definitions of the templates.
- templates-parts: Contains the code of the templates, either the whole template or parts of it.
- theme-code: Contains the core code of the theme, this files will be automatically loaded.
- vendor: External libraries used through composer.



###<a name="general"></a>General steps
0. Make sure your system have all the required components, if no please install them.
1. Run the terminal commands **npm install** and **composer install**
2. Rename the folder to the name of the site or theme name specific for the site.
3. Edit **style.css** to use that name on the description of the theme (the screenshot.png may be changed too if needed). This file **is not included** on the stylesheets of the site, so dont add any style on it (or include it by yourself)
4. The theme will have support for different menus styles. Those menus are located on the folder _templates-parts/menus/_. (**TODO**) In the future there will be an option to dynamically select the menus style. (function _theme_get_menu(menuName)_)
5. Layouts modules ([see documentation](https://www.advancedcustomfields.com/resources/flexible-content/)). The layout system work through using the blocks on the layout file and rendering then one after another. 
    - To to declare a new module just need to edit the layout Custom Field and add it. 
    - The **name** of the layout needs to be used as filename on the path _templates-parts/layout/_, for example if a module is called **one-column** there has to be a file _templates-parts/layout/one-column.php_ and in that file is where will be all the code needed to process the module.
6. Any other template should be defined on the _templates/_ folder, and his code defined on the _templates-parts/_ folder, trying to keep the names with meaning and related.
7. By default the single post are sent to the _templates-parts/single.php_ file, and that redirection is processed on the function **theme_get_single_template()**, in order to analyze if it should include the default or the cpt specific.
8. Ajaxs end points should be defined on the file _theme-ajax.php_ and the code processing the response should be inside the folder _code-ajax/_. All the files included on that folder are loaded automatically.
9. Shortcodes should be defined on the file _theme-shortcodes.php_ and the code processing the response should be inside the folder _code-shortcode/_. All the files included on that folder are loaded automatically. The class approach is **recommended**, please look at the file _ThemeShortcodesCore.php_ to see an example of how it should be defined and on the file _theme-shortcodes.php_ is an example of how to define the shortcode and his handler association.
10. Any library/component you want to install (either through composer, npm or manually) should be added to **GULP** so it can be compiled and integrated on the minimized files (or can be added manually but this is not advice unless you know what are you doing). To do this please edit the file *gulpfile_config.js* and add the path to the library, comment the ones you aren't going to use or un-comment the ones to be used.

###<a name="style"></a>Style Adjustment
To personalize the style there are several things to do.
1. First you can customize/change the bootstrap file definitions (**PENDING**)
2. You can edit the _scss/*_ files and after that run the gulp script to compile then (see [Gulp section](#gulp)). As a rule, any file which name start with the underscore char **will be ignored** on the compilation process, so its responsibility of the developer to include it if needed.
3. To add any plain css its recomended to do so using the _assets/style/_ folder.
4. Finally any custom css can be added either through _scss/*_ or directly on the plain _assets/style/*_ files.The SCSS are recommended ofc.

When creating the scss definitions please use the following standard to define the media-queries:
```
.example{ 
  //general css rules
  //general css rules
  
  @include breakpoint(...) {
    //css rules to be applied on this breakpoint
  }
  @include breakpoint(...) {
    //css rules to be applied on this other breakpoint
  }
}
```
On this example the **breakpoint** function is a mixin ([official site](http://breakpoint-sass.com/), [documentation](https://github.com/at-import/breakpoint/wiki)) that allow you to dynamically create the needed media queries, accepting quite a lot of options. Please check the documentation (on that link ) for the full list of parameters it will accept.

Currently the scss files that are run through the _gulp task_ (the watcher run the task) have support to use the **gulp auto-prefix** and **compass**. The autoprefix feature means that when writing code you dont need to worry about adding the vendor specific prefix, just write regular definitions, like this example:
 ``` css
 .class {
    transform: translateY(10px)
 }
 ```
and after the compilation through the gulp task the final code will be:
``` css 
.class{
  -webkit-transform: translateY(10px); 
  -moz-transform: translateY(10px);
  -ms-transform: translateY(10px); 
  -o-transform: translateY(10px); 
  transform: translateY(10px);
}
 ```
 The **compass** ([see documentation](http://compass-style.org/)) option allows you to use all the features defined on that CSS framework, like mixins and functions and so. To use it make sure to enable the compass support on PHPStorm (**TODO**). The scss files compiled through compass (all of the scss files) are output to the _dist/css/compass/_ directory, you dont need to do anything with them, just to know that they are there.



####ACF field options to be used on .scss files.
The theme have support for **sass** fields on the _options page_; those fields will be saved to the file _wp-content/uploads/generated_scss/options_vars*-options-page-slug*.scss_, which is included by default on the _/scss/main.scss_ file so you will have access to anything defined there. To use this feature please follow this steps.
1. Create an custom field on the default options (see note below) and, for the field key, **make sure** to name it starting with **s_**, this is used to identify the fields that are going to be compiled.
2. Every time you update that options page where those options are defined the file are going to be recompiled and stored on the path indicated above.
By default the **theme-general-settings** options page is included on the _main.scss_ file, keep in mind that any new options page with *s_* files will generate his own var file that should be included manually in some other scss file. 

###<a name="script"></a>Script Adjustment
The Theme Scripts files are located inside the _scripts/_ folder, after working with then please run the GULP script to minify them. Any external/custom script should be added to the _assets/scripts/*_ folder.

To compile the scripts please see [Gulp section](#gulp). As a rule, any file which name start with the underscore char **will be ignored** on the compilation process, so its responsibility of the developer to include it if needed.

The theme have support for **ES6**.


###<a name="acf"></a>ACF
The theme is developed on top of ACF and by default it have several Templates field definitions and support for them. Those definitions are included on the folder _acf-fields/_ in case you need to edit/check then. 

If you develop any new module please export the file and include there and make sure to do a commit to the main repository (**PENDING**)

###<a name="code"></a>Templates and Code Structure.
- Pages Templates: The pages templates are divided in 2 parts
    - First the general definition of the template can be found on the folder _templates/*_. This is done to ensure the general definitions of the templates to be isolated of the code itself. In some cases this can come handy.
    - Second the code itself of the template (this can also be composed of another code sections) can be found on the _templates-parts/*_ folder. This way if some script/ajax-call need to access directly to the template part can do it without the general definitions of it. The content of this folder will be called on demand.
- Shortcodes: 
    - The shortcodes are included on the file _theme-shortcodes.php_. This file **must** include **all** the shortcodes's definitions (except for those specific to CPT) as well as **only** the call of the function that process it; it should't have any code in it to ensure its keep simple/clean. 
    - The Shortcodes definitions can be added inside the folder _code-shortcodes/*_, every _.php_ file inside that folder will be included through the _functions.php_ so there is no need to re-include it
- Ajax endpoints: 
    - The ajax endpoitns definitions are created on the file _theme-ajax.php_. This file **must** include **all** the endpoitns (except for those specific to CPT) and the funcion/class that will process them.
    - The Ajax Handlers should be added inside the folder _code-ajax/*_. Those files will be included automatically.
- Theme-core Folder: This folder contains the theme-core related files, should be modified with care since this is will be changing from version to version and can be override.
    - _filters.php_: This file contains the theme related filters (attachment to the head, menus, etc)
    - _helpers.php_: File with the helpers. This functions basically help the code, they **DO NOT** render code or any output, just data processint.
    - _functions-theme.php_: File with generic functions, usually to help render/process code sections (like the ones for the img replacements)
- Custom Post Types: The theme will come without any external CPT definitions, but if any theme require the development of any CPT those definitions should be included on the _cpt/_ folder. A more detailed guide is provided on the index **6. Custom Post Types**
- Assets folder: This folder contain all the theme-specific file, like images used, some specific libraries, etc. There isnt any strict rule to keep to make modifications here.    
  

###<a name="cpt"></a>Custom Post Types. (STILL DEVELOPING)
To create CPT definitions its recommended to create a folder (needs to be named as the **post-type** used on the CPT) inside the folder _cpt/_ so it can be easily located and referenced. Inside that folder for the specific CPT we can place all the required files and class definitions (the Class approach is **recommended** for any CPT development). 

After all is created you can edit the _functions.php_ file, _section 2: General includes_ and use the function _theme_get_cpt($cptType)_ to include the Main Class for the CPT (will be the post type with the first char in Capital Case -no camelcase-) (**with a class approach you should only need to include the main class of the CPT and that file will process everything else**). This will help to connect and disconnect in the future any CPT that isn't required for that specific theme.

The definition/creation of the CPT should be done on the constructor of the Main class (the one that is included on the functions php).The recommended structure inside any CPT is provided with the basic theme on the CPT **example** and it contains a fully functional example of any CPT (shortcodes, helpers, ajax calls, etc).

With this approach and in order for the **single-templates** to work it's needed to have a _cpt/**post-type**/**post-type**-single.php_ file, this fill will be loaded automatically whenever an user tries to see a single post of the indicated CPT (this is automatically generated from the theme.)


###<a name="lib"></a>External libraries
If the site require any external library please feel free to include then, the recommended approach is to use composer to import then, but you can add it manually if needed. if you add it manually please use the _assets/scripts/_ directory for single-js files and _assets/lib*_ for a complex library with assets and so.
- **Conditionizr**: This library is used to load scripts, styles or add classes based on several conditions. on the _header.php_ file, at the end of the _head_ tag you can find the config of the plugin, where you can set the path for the asset folder (where the system expect to find the script/styles). For more info please check the [documentation](https://github.com/conditionizr/conditionizr/blob/master/docs/DOCS.md#conditionizr-v400-docs)
- **[Owl Carousel](http://www.owlcarousel.owlgraphic.com/demos/demos.html)**: This is used (as the current version of the theme) as the main library for Video background. 
---

###<a name="notes"></a>Guidelines
- **Nonce fields**: When developing any type of client communication please use the nonce fields as recommended from WordPress. The theme offer the functions _generate_nonce_field_for()_ and _check_nonce_for()_ as a wrappers from the ones native in WP to make it easy.

###<a name="plugins"></a>Plugins **PENDING**
- Gravity Form
- Contact Form 7
- Foobox
- Lightbox gallery
- etc..

##<a name="gulp"></a>GULP:
Currently we have support to minify and merge several scripts and css, it can be checked on the file **gulpfile.js**. If your development need an specific feature you can edit the gulpfile and add/remove scripts to the variables to include them or no. To run a task please use 
``` bash 
gulp taskName
```
The tasks are:
- **build**: execute all the task, vendorHead, vendorFooter, themeCss, themeJs.
- **vendor**: execute both, vendorHead and vendorFooter, tasks.
- **vendorHead**: generate the scripts/styles from the vendors/libraries identified as to go on the head of the site
- **vendorFooter**: generate the scripts/styles from the vendors/libraries identified as to go on the footer of the site
- **theme**: execute both, themeCss and themeJs, tasks.
- **themeCss**: generate the styles from the theme css/scss files (scan the _scss/_ folder)
- **themeJs**: generate the scripts from the theme js files (scan the _scripts/_ folder)

**Config GULP and adding files/libraries to be compiled**. We have a config file (_**./gulpfile\_config.js**_) where you can define which files do you want to process through gulp, as well as the destination of the compiled files and some other options. Please be careful using this.

**Watcher**
We have implemented a watcher, it will look to changes on the files _scss/*.scss_ and _scripts/*.js_ and if some of those changes it will re-run the tasks "themeCss" and "themeJs". When the compilation finalize the system will display a notification (a native notification) informing that it has finished or, if there is an error, displaying information about the error that can be used to track it. If there is no error then you should be able to reload the page safely since the changes will be already compiled. To run the watcher just execute the command:
``` bash 
gulp watch
```

##<a name="todo"></a>TODO:
- [ ] Support for the Customizer Options (in wordpress) to use it to inject some of the options.
- [ ] Develop code specific for each supported post-format. https://developer.wordpress.org/themes/functionality/post-formats/
- [ ] Override URL for CPT, lets make it a Class and Add a feature to the options page where you can select the CPT you want to override his slugs.
- [ ] Comments and Comment-thread basic style/layout.
- [ ] Use Bootstrap-sass approach. Currently using **bootstrap.min.css** directly
- [ ] Use Font awesome scss approach (?). Currently using **fontawesome.min.css** direclty
- [x] Watcher over CSS and JS files. (**gulp watch**)
- [ ] General Functions (~~render images~~, ~~render social list~~, menus, load more behavior (**??**), buttons. )
- [ ] Add generic shortcodes (~~date~~, ~~footer_image~~, ...)
- [ ] Admin option to hide/display extra options for each module. This is to be done through javascript. the fields to be hidden should have a class (`.extra-option`)
- [ ] Possibility to select the form.css|form.scss file to include base on the type of form to be used
- [x] IES 6 
- [ ] Support for more fields on the sass watcher.
- [ ] Include underscore/lodash


##<a name="todo"></a>TODO 2:
- **Layout Base**
    - [ ] Hero banner. Finish the basic structure. Support for 1 image, video bg, text position, jump arrow.
    - [ ] Hero Slider banner. Video bg, slider width image, text, text-animation. Beside text position, jump arrow.
    - [ ] Row - 1 Column. Image bg, Video bg, Color bg, text.
    - [ ] Row - 2 Column. Image bg, Video bg, Color bg for the whole row. On each column: Videobg, imagebg, colorbg. Title & text
    - [ ] Row - Basic shortcode
    - [ ] Row - 3 Columns. Image bg, Video bg, Color bg for the whole row. On each column: Videobg, imagebg, colorbg. Title & text
    - [ ] Row - Gallery Grid. Image bg, Video bg, Color bg for the whole row. Number of elements of the grid on each responsive.
    - [ ] Row - CPT Grid. Image bg, Video bg, Color bg for the whole row. Number of elements of the grid on each responsive.
- **Gallery Layout**
    - [ ] Similar to the Row - Gallery Grid but with the loadmore behavior and filters by ajax.
    - [ ] Support to select different layouts of the gallery
- **Forms**
    - [ ] Style Reset|Basic form
    - [ ] Style basic form with Gravity Form.
    - [ ] Style basic form with Contact Form 7

* _All **ROW** will have padding, extra-class, content (true-false). Visible on admin._
* _Each page will have a settings section. In that section we can define the type of behavior for the columns...?. Visible on admin_


##<a name="bugs"></a>Bugs:
- [x] Z index on the sass files.
- [x] fonts paths on the .scss/less files
- [x] error rendering the social list with the order field.