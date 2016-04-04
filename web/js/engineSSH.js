$(function () {
  //Re-index method
  $('.reindex').click(function (event, ui) {
    $('.loadsmall').show();
    $.get("/Cron_task/", function (data) {
      $.get("/", function (data) {
        $('.content').html(data);
        $('.loadsmall').hide();
        window.location.reload(true);
      });
    });
  });

  //Disable multi select function for Env
  $('#selectableEnvList').bind("mousedown", function (e) {
    e.metaKey = false;
  }).selectable({
    stop: function (event, ui) {
      $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
    }
  });
  $("#selectableEnvList").on("selectablestart", function (event, ui) {
    event.originalEvent.ctrlKey = false;
  });

  //Selectable list function for Env
  $('#selectableEnvList li').click(function (event) {
    var env_ip = $(event.target).attr('id'); // Get IP of selected Environment

    var list = $("#selectableVmList").find('li').show(); //Get list of all Vms

    $("#selectableVmList li").removeClass("ui-selected");
    $(".vmMenu").show();
    $('.start').hide();
    $(".options").hide();
    $("#selectableActionsList").hide();
    $('.output').hide();
    //Hide vms with diferent accessVM environment
    list.each(function (key, value) {
      var vmAccessIp = $(value).attr("id");
      if (vmAccessIp !== env_ip) {
        $(value).hide();
        $("#selectableActionsList li").each(function (key, value) {
          $(value).hide();

        });
      }
    });
  });

  //Disable multi select function for Vms
  $('#selectableVmList').bind("mousedown", function (e) {
    e.metaKey = false;
  }).selectable({
    stop: function (event, ui) {
      $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
    }
  });
  $("#selectableVmList").on("selectablestart", function (event, ui) {
    event.originalEvent.ctrlKey = false;
  });

  //Selectable list function for Vms
  $('#selectableVmList li').click(function (event) {
    $("#selectableActionsList li").removeClass("ui-selected");
    $('.start').hide();
    $('.options').hide();
    $('.loadingmessage').hide();
    $("#selectableActionsList li").each(function (key, value) {
        $(value).show();
        $("#selectableActionsList").show();
        $(".actions").show();
      });
      
//    if ($('#selectableVmList').find('.ui-selected').attr('title') === "disabled") {
//      $("#selectableActionsList").hide();
//    } else {
//      $("#selectableActionsList li").each(function (key, value) {
//        $(value).show();
//        $("#selectableActionsList").show();
//        $(".actions").show();
//      });
//    }
  });


  //Disable multi select function for Actions
  $('#selectableActionsList').bind("mousedown", function (e) {
    e.metaKey = false;
  }).selectable({
    stop: function (event, ui) {
      $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
    }
  });
  $("#selectableActionsList").on("selectablestart", function (event, ui) {
    event.originalEvent.ctrlKey = false;
  });

  //Selectable list function for Actions
  $('#selectableActionsList li').click(function (event, ui) {
    $('.start').hide();
    $(event.target).find('.start').show();
    $('.option').show();
    if ($(event.target).val() === 1) {
      $(event.target).find('.loadingmessage').show();
      $.get("/parameters/index?params_type=" + $(event.target).attr("id") + "&generalNeeded=1", function (data) {
        $(".options").html(data);
        $(event.target).find('.loadingmessage').hide();
        $(".options").show();
      });
    } else if ($(event.target).val() === 2) {
      $(event.target).find('.loadingmessage').show();
      $.get("/parameters/index?params_type=" + $(event.target).attr("id") + "&generalNeeded=", function (data) {
        $(".options").html(data);
        $(event.target).find('.loadingmessage').hide();
        $(".options").show();
      });
    } else {
      $(".options").hide();
    }

  });

  //Start action function
  $(".start").click(function () {
    var startedAction = $('#selectableActionsList').find('.ui-selected').find('.loadingmessage');    
    $('.option').hide();    
 
    var systemVariables = {};
    systemVariables['host'] = $('#selectableVmList').find('.ui-selected').attr('id');
    systemVariables['port'] = $('#selectableVmList').find('.ui-selected').attr('name');
    systemVariables['user'] = null;
    systemVariables['pass'] = null;
    systemVariables['action'] = $('#selectableActionsList').find('.ui-selected').attr('id');
    systemVariables['ostype'] = $('#selectableVmList').find('.ui-selected').attr('title'); 
    
    var inputs = $('.options :input');
    var options = {};
    inputs.each(function () {
      options[this.name] = $(this).val();
    });
    startedAction.show();
    $.post('/frontend_dev.php/exec/', {systemVariables: systemVariables, options: options},
    function (data)
    {
      $('.output').html(data);
      startedAction.hide();
    });
    $('.output').show();
  });
});



