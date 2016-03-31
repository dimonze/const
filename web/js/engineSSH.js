$(function () {
  apended = "no";
  $('#selectable').bind("mousedown", function (e) {
    e.metaKey = false;
  }).selectable({
    stop: function (event, ui) {
      $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
    }
  });
  $("#selectable").on("selectablestart", function (event, ui) {
    event.originalEvent.ctrlKey = false;
  });

  $('#selectable li').click(function (event) {
    var _text = $(event.target).attr('id');

    var list = $("#selectableVm").find('li').show();
    
    $("#selectableVm li").removeClass("ui-selected");
    $("#vmMenu").show();
    $('.start').hide();
    $("#options").hide();
    $("#selectableActions").hide();
    $('.output').hide();
    if(apended === "no"){
    $("#selectableVm li").each(function (key, value) {
      if ($(value).attr("title") !== "disabled") {
        $(value).append('<b style="color:green"> &#9679;</b>');
      } else {
        $(value).append('<b style="color:red"> &#9679;</b>');
      }
      apended = "yes";
    });
  }
    list.each(function (key, value) {
      var _txt = $(value).attr("id");
      if (_txt !== _text) {
        $(value).hide();
        $("#selectableActions li").each(function (key, value) {
          $(value).hide();

        });
      }
    });
  });

  $('#selectableVm li').click(function (event) {
    $("#selectableActions li").removeClass("ui-selected");
    $('.start').hide();
    $("#options").hide();
    if ($('#selectableVm').find('.ui-selected').attr('title') === "disabled") {
      $("#selectableActions").hide();
    } else {
      $("#selectableActions li").each(function (key, value) {
        $(value).show();
        $("#selectableActions").show();
        $("#actions").show();
      });
    }
  });

  $('#selectableVm').bind("mousedown", function (e) {
    e.metaKey = false;
  }).selectable({
    stop: function (event, ui) {
      $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
    }
  });
  $("#selectableVm").on("selectablestart", function (event, ui) {
    event.originalEvent.ctrlKey = false;
  });

  $('#selectableActions').bind("mousedown", function (e) {
    e.metaKey = false;
  }).selectable({
    stop: function (event, ui) {
      $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
    }
  });
  $("#selectableActions").on("selectablestart", function (event, ui) {
    event.originalEvent.ctrlKey = false;
  });

  $('#selectableActions li').click(function (event, ui) {
    $('.start').show();
    if ($(event.target).val() === 1) {
      $('#loadingmessage').show();
      $.get("/parameters/index?params_type=" + $(event.target).attr("id") + "&generalNeeded=1", function (data) {
        $("#options").html(data);
        $('#loadingmessage').hide();
        $("#options").show();
      });
    }else if ($(event.target).val() === 2) {
      $('#loadingmessage').show();
      $.get("/parameters/index?params_type=" + $(event.target).attr("id") + "&generalNeeded=", function (data) {
        $("#options").html(data);
        $('#loadingmessage').hide();
        $("#options").show();
      });      
    } else {
      $("#options").hide();
    }

  });

  $(".start").click(function () {
    $('#loadingmessage').show();
    var host = $('#selectableVm').find('.ui-selected').attr('id');
    var port = 122 + $('#selectableVm').find('.ui-selected').attr('name');
    var user = "";
    var pass = "";
    var gwhost = "";
    var dpshost = "";
    var actions = $('#selectableActions').find('.ui-selected').attr('id');
    var ostype = $('#selectableVm').find('.ui-selected').attr('title');

    var $inputs = $('#option :input');
    var options = {};
    options["test"] = 0;
    $inputs.each(function () {
      options[this.name] = $(this).val();
    });
    $.post('/frontend_dev.php/exec/', {accessVmHost: host, accessVmPort: port,
      accessVmUser: user, accessVmPass: pass, gwHost: gwhost,
      dpsHost: dpshost, actions: actions, ostype: ostype, options: options},
    function (data)
    {
      $('.output').html(data);
      $('#loadingmessage').hide();
    });
    $('.output').show();

  });
});

