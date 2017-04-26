#Shortcodes
On this document we will describe the list of shortcodes provided by the theme with their parameters as well as the general explanation of how to use them.

##Shortcodes list
- [Date](#theme_date)
- [Footer Image](#theme_footer_image)

###<a name="theme-date"></a>Date
Code: **\[theme_date year="0|1" month="0|1" day="0|1" format="|m d Y" date="|timestamp"\]**<br/>
Implemented on: *ThemeShortcodesCore->theme_date()*<br/>
Documentation: 
``` php
/**
* Get the date
* @see date()
* 
* @param array $attributes An array with the following indexes :\n
*                          - year  : Default 0. 1 to display year.
*                          - month : Default 0. 1 to display month
*                          - day   : Default 0. 1 to display day
*                          - format : Default empty. Format to display. Override year, month, day displays.
*                          - date  : timestamp for the date or empty or now to use it to generate the date from
*                          there
*
* @return string a text representation of the date.
**/ 
```

###<a name="theme_footer_image"></a>Footer Image
Code: **\[theme_footer_image url="0|1" object="0|1" field="footer_image|--"\]**<br/>
Implemented on: *ThemeShortcodesCore->theme_footer_image*<br/>
Documentation: 
``` php
/**
* Get the Image defined on the options page as <b>Footer Image</b>. 
* @see wp_get_attachment_image_url(), wp_get_attachment_metadata()
* 
* @param array $attributes An array with the following indexes :\n
*                          - url  : Default 0. 1 to return just the url.
*                          - object : Default 0. 1 to return an object with the media info. If url=1 this param will be ignore.
*                          - field : Default "footer_image". Indicate the field to be retrieved. The whole code works under 'options' only.
*
* @return string an url for the image or empty if fails. keep in mind that the Object will return the values defined on 
*                wp_get_attachment_metadata(), unless the field was stored to return an object. In which case that 
*                object will be returned. (the defualt implementation of the field is to return an ID)
*/
```
