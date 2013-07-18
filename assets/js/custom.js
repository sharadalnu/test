$('#signin-form').submit(function() {
  $.post('signin.php', $(this).serialize(), processLogin);
  return false;
});

function processLogin(data) {
  if (data === 'No such account') {
    $('#notifications h4').text('Account does not exist. Do you want to create one?');
    $('#notifications a').text('Yes');
    $('#notifications a').attr('href', 'register.php');
    $('#notifications a').show();
    $('#notifications').fadeIn();
  } else if (data === 'Wrong password') {
    $('#notifications h4').text('Wrong Password');
    $('#notifications a').hide();
    $('#notifications').fadeIn();
  } else if (data === 'Success') {
    //location.reload(); Use to reload page
    window.location.replace("ssi.php");
  }
}

$('#notifications').alert();
$('#notifications .close').click(function() {
  $(this).parent().fadeOut();
});

// Dropdown on Hover
$('#main-bar .dropdown').hover(function() {
  $(this).addClass('open');
}, function() {
  $(this).removeClass('open');
});

$('#inputUsername').keyup(function() {
  if ($('.spinner').length !== 0) {
    return;
  }
  
  $(this).parent().spin('small');
  var css = { };
  var additional = $('#inputUsername').parent().parent().parent().parent().position().left;
  var spinnerPos = $('#inputUsername').position().left + $('.spinner').width();
  
  if ($('#signin-modal').find($('#inputUsername')[0]).length) {
    spinnerPos -= additional;
    css.top = $('#inputUsername').position().top + 'px';
  }
  
  css.left = spinnerPos + 'px';
  
  $('.spinner').css(css);
});

// Username Check
$('#inputUsername').parent().append('<span class="help-inline"></span>'); // Initial help inline
$('#inputUsername').typeWatch({
  callback: function(value) {
    $.post('accountcheck.php', $(this).serialize(), usernameTaken);
  },
  wait: 600,
  highlight: true,
  captureLength: 0
});

function usernameTaken(data) {
  $('#inputUsername').parent().spin(false);
  
  if (data === '') {
    $('#inputUsername').parent().parent().removeClass('error');
    $('#usernameGroup .help-inline').text('');
    $('button[type="submit"]').removeAttr('disabled');
    return;
  }
  
  $('#inputUsername').parent().parent().addClass('error');
  $('#usernameGroup .help-inline').text('Username taken.');
  $('#registerGroup button[type="submit"]').attr('disabled', 'disabled');
}

// Modal AJAX
$('#signin-modal-form').submit(function() {
  $.post('signin.php', $(this).serialize(), modalLogin);
  return false;
});

function modalLogin(data) {
  $('#signin-username').parent().parent().removeClass('error');
  $('#signin-password').parent().parent().removeClass('error');
  $('.help-inline').remove();
  
  if (data === 'No such account') {
    $('#signin-username').parent().parent().addClass('error');
    
    if ($('#signin-username').parent().find('.help-inline').length === 0) {
      $('#signin-username').parent().append('<span class="help-inline">No such account.</span>');
    }
  } else if (data === 'Wrong password') {
    $('#signin-password').parent().parent().addClass('error');
    
    if ($('#signin-password').parent().find('.help-inline').length === 0) {
      $('#signin-password').parent().append('<span class="help-inline">Wrong password.</span>');
    }
  } else if (data === 'Success') {
    //location.reload(); Use to reload page
    window.location.replace("ssi.php");
  }
}

$('#cate_gory').click(function(){offline();});



