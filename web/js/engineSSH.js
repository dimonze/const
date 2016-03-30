$(function () {
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
    $('.start').hide();
    $("#options").hide();
    $("#selectableActions").hide();
    $('.output').hide();
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
    $("#selectableActions li").each(function (key, value) {

      $(value).show();
      $("#selectableActions").show();

    });
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
      $.get("/parameters/index?params_type=" + $(event.target).attr("id"), function (data) {
        $("#options").html(data);
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
    var user = "root";
    var pass = "installed";
    var type = "typical";
    var gwhost = "";
    var dpshost = "";
    var actions = $('#selectableActions').find('.ui-selected').attr('id');
    var ostype = 0;
    
    var $inputs = $('#option :input');
    var options = {};
    $inputs.each(function () {
      options[this.name] = $(this).val();
    });
    console.log(options);
    $.post('/exec/', {accessVmHost: host, accessVmPort: port,
      accessVmUser: user, accessVmPass: pass, instType: type, gwHost: gwhost,
      dpsHost: dpshost, actions: actions, windows: ostype, options: options}, 
    function (data)
    {
      $('.output').html(data);
      $('#loadingmessage').hide();
    });
    $('.output').show();

  });
});

