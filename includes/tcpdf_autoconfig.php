<?php

// =============================
// Custom variables
// =============================

define('PDF_SKIP_FONTS', true);

// =============================
// Page configuration
// =============================

if(!defined('PDF_PAGE_FORMAT')){
    define('PDF_PAGE_FORMAT', 'A4');
}

if(!defined('PDF_PAGE_ORIENTATION')){
    define('PDF_PAGE_ORIENTATION', 'P');
}

// =============================
// Add custom fonts
// =============================

/*
if(!defined('K_PATH_MAIN')){
    define('K_PATH_MAIN', '');
}

if(!defined('PDF_FONT_NAME_MAIN')){
    define('PDF_FONT_NAME_MAIN', 'secca');
}
*/