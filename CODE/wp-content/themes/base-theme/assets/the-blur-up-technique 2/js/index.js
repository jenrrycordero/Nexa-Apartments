jQuery(window).load(function() {
  var img, header, enhancedClass;
  // Quit early if older browser (e.g. IE8).
  if (!('addEventListener' in window)) {
    return;
  }

  img = new Image();
  header = jQuery('.post-header');

  // Rather convoluted, but parses out the first mention of a background
  // image url for the enhanced header, even if the style is not applied.
  var bigSrc = 'http://nexa-apartments/wp-content/uploads/2016/12/home-banner.jpg';

  // Assign an onLoad handler to the dummy image *before* assigning the src
  img.onload = function() {
    header.css({'background-image': 'url("' + bigSrc + '")'});
  };


  // Finally, trigger the whole preloading chain by giving the dummy
  // image its source.
  if (bigSrc) {
    img.src = bigSrc;
  }
});