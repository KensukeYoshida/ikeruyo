jQuery(document).ready(function(t){"use strict";function s(){t(".gambit_fullwidth_row").each(function(s){var i=t(document.gambitFindElementParentRow(t(this)[0])),r=i.css("webkitTransform"),a=i.css("mozTransform"),n=i.css("msTransform"),o=i.css("transform");i.css({width:"",position:"",maxWidth:"",left:"",paddingLeft:"",paddingRight:"",webkitTransform:"",mozTransform:"",msTransform:"",transform:""});var e=t(this).attr("data-content-width")||i.children(":not([class^=gambit])").width()+"px";if(i.parent().css("overflowX","visible"),i.css("left",""),i.css({width:"100vw",position:"relative",maxWidth:t(window).width(),left:-i.offset().left,webkitTransform:r,mozTransform:a,msTransform:n,transform:o}),""!==e){var d,f,m,w=0;d=-1!==e.search("%")?parseFloat(e)/100*t(window).width():parseFloat(e),w=(t(window).width()-d)/2,f=w+parseFloat(i.css("marginLeft")),m=w+parseFloat(i.css("marginRight")),d>t(window).width()&&(f=0,m=0),i.css({paddingLeft:f,paddingRight:m})}})}s(),t(window).resize(function(){s()})});