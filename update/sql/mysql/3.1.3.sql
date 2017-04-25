ALTER TABLE `jos_phocadownload_layout` ADD `subcategory` TEXT AFTER `categories`;
UPDATE `jos_phocadownload_layout` SET `subcategory` = '<div class="pd-subcategory"><a href="{pdlink}">{pdtitle}</a><small>{pdnumdocs}</small></div>' WHERE `id` = '1';
