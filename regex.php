<?php


// title, img_url, w_link, img_ext
$regex1 = '/title="([\w\W]{3,50})">[\s]<img width="[\d]*" height="[\d]*" data-src="(https:\/\/reaperscans\.com\/wp-content\/uploads\/[\w\W]{10,40}(\.[\w]{3,4}))[\w\W]{10,800}<h3 class="h5">[\s]*<a href="(https:\/\/reaperscans\.com\/series\/[\w-]{3,50}\/)/';

// title, img_url, img_ext
$regex2 = '/title="([\w\W]{3,50})">[\s]<img width="[\d]*" height="[\d]*" data-src="(https:\/\/reaperscans\.com\/wp-content\/uploads\/[\w\W]{10,40}(\.[\w]{3,4}))/';

// chapter link and no
$regex3 = '/(https:\/\/reaperscans\.com\/series\/([\w-]{3,50})\/chapter-[\d-]{1,4}\/)" class="btn-link"> Chapter ([\d.]{1,4})/';

// W_title
$regex4 = '/<h3 class="h5">[\s]<a href="https:\/\/reaperscans\.com\/series\/[\w\W]{3,50}\/">([\w\W]{3,100})<\/a>/';