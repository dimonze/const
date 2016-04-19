$(function () {
  setInterval(function () {
    var listTask = $("#showDetails").find('li');
    listTask.each(function (key, value) {
      if ($(value).attr('id') == 'in progress') {
        $.post('/current_state/', {},
                function (data)
                {
                  $('#showDetails').html(data);
                  $('.output').show();
                  $('#showDetails a').click(function (event, ui) {
                    $('.loadsmallState').show();
                    var id = $(event.target).attr('id');
                    $.post('/showRes/', {id: id},
                    function (data)
                    {
                      $('.details').html(data);
                      $('.loadsmallState').hide();
                      $('.details').show();
                    });
                  });
                });
      }
    });

  }, 120000);

  //Re-index method
  $('.reindex').click(function (event, ui) {
    $('.loadsmall').show();
    $.get("/cron_task/", function (data) {
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
    $('.details').hide();
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
    $('.details').hide();
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
    $('.details').hide();
    $('.start').hide();
    $(event.target).find('.start').show();
    $('.option').show();
    if ($(event.target).val() === 1) {
      $(event.target).find('.loadingmessage').show();
      $.get("/parameters/index?params_type=" + $(event.target).attr("name") + "&generalNeeded=1", function (data) {
        $(".options").html(data);
        $(event.target).find('.loadingmessage').hide();
        $(".options").show();
      });
    } else if ($(event.target).val() === 2) {
      $(event.target).find('.loadingmessage').show();
      $.get("/parameters/index?params_type=" + $(event.target).attr("name") + "&generalNeeded=", function (data) {
        $(".options").html(data);
        $(event.target).find('.loadingmessage').hide();
        $(".options").show();
      });
    } else {
      $(".options").hide();
    }

  });

  //Start action function
  $('.start').click(function () {
    $('.details').hide();
    var startedAction = $('#selectableActionsList').find('.ui-selected').find('.loadingmessage');
    $('.option').hide();
    var link = '';
    var systemVariables = {};
    systemVariables['vm_name'] = $('#selectableVmList').find('.ui-selected').attr('name');
    systemVariables['action'] = $('#selectableActionsList').find('.ui-selected').attr('name');

    var inputs = $('.options :input');
    var options = {};
    inputs.each(function () {
      options[this.name] = $(this).val();
    });
    startedAction.show();
    $.post('/exec/', {systemVariables: systemVariables, options: options}, function (data) {
      $.post('/current_state/', {},
              function (data)
              {
                $('#showDetails').html(data);
                $('.loadsmallState').hide();
                $('.output').show();
                startedAction.hide();
                $('#showDetails a').click(function (event, ui) {
                  $('.loadsmallState').show();
                  var id = $(event.target).attr('id');
                  $.post('/showRes/', {id: id},
                  function (data)
                  {
                    $('.details').html(data);
                    $('.loadsmallState').hide();
                    $('.details').show();
                  });
                });
              });
    });
    $('.output').show();
  });



  $('.checkTasksState').click(function () {
    $('.details').hide();
    $('.loadsmallState').show();
    $.post('/current_state/', {},
            function (data)
            {
              $('#showDetails').html(data);
              $('.loadsmallState').hide();
              $('.output').show();
              $('#showDetails a').click(function (event, ui) {
                $('.loadsmallState').show();
                var id = $(event.target).attr('id');
                $.post('/showRes/', {id: id},
                function (data)
                {
                  $('.details').html(data);
                  $('.loadsmallState').hide();
                  $('.details').show();
                });
              });
            });
  });



});



