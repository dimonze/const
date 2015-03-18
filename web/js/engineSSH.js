/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(function() {
  jQuery(function() {
    jQuery("#list_vms").accordion();
    jQuery("#list_vms li").draggable({
      appendTo: "body",
      helper: "clone"
    });
    jQuery("#list_actions").accordion();
    jQuery("#list_actions li").draggable({
      appendTo: "body",
      helper: "clone"
    });
    jQuery("#work_area ol").droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
      accept: ":not(.ui-sortable-helper):not(#act)",
      drop: function(event, ui) {
        jQuery(this).find(".placeholder").remove();
        jQuery("<li id="+ ui.draggable[0].children[0].id +"></li>").text(ui.draggable.text()).appendTo(this);
        //console.log(ui.draggable[0].children[0].id);
      }
    });
  });

//  jQuery.getJSON('env', function(data) {
//    jQuery.each(data, function(index, value) {
//      //jQuery("#list_vms").append('<h2><a href="#">' + index + '</a></h2><div><ul>');
//      jQuery.getJSON('vms?env_id=' + value.id, function(data) {
//        jQuery.each(data, function(index_vm, value) {
//         // jQuery("#list_vms ul").append('<li>' + value.vm_name + '</li>');
//        });
//      });
//      //jQuery("#list_vms").append('</ul></div>');
//    });
//  });
//
//  jQuery.getJSON('actions', function(data) {
//    jQuery.each(data, function(index, value) {
//      //jQuery(".left_pain_actions").append('<div class="eaction pos" id="' + index + '">' + index + '</div>');
//    });
//  });

});