/*
 * jQuery Phoca Tabs
 * https://www.phoca.cz
 *
 * Copyright (C) 2020 Jan Pavelka www.phoca.cz
 *
 * Licensed under the MIT license
 */

jQuery(document).ready(function() {    

    jQuery('.phTabsContainer').hide();
    jQuery('.phTabsContainer.active').show();
        
    jQuery('.phTabs ul li a').click(function(){

        var parentId    = jQuery(this).closest('.phTabs').attr('id');
        var id          = jQuery(this).attr('id');

        jQuery('#' + parentId + ' ul li a').removeClass('active');           
        jQuery(this).addClass('active');
        jQuery('#' + parentId + ' .phTabsContainer').hide();
        jQuery('#' + parentId + ' .phTabsContainer').removeClass('active');
        jQuery('#' + id + 'Container').show();
        jQuery('#' + id + 'Container').addClass('active');
     
    });
});