function fallbackCopyTextToClipboard(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Fallback: Copying text command was ' + msg);
  } catch (err) {
    console.error('Fallback: Oops, unable to copy', err);
  }

  document.body.removeChild(textArea);
}

function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text).then(function() {
    console.log('Async: Copying to clipboard was successful!');
  }, function(err) {
    console.error('Async: Could not copy text: ', err);
  });
}



$(document).ready(function(){
  // $('[data-toggle="tooltip"]').tooltip(
  //   { delay: { 'show': 500, 'hide': 500 } }
  // );

  // $("[data-toggle='tooltip']").on('shown.bs.tooltip', function(event){
  //       $(event.target).tooltip("hide");
  //   });

  $(document).on('click', '.btn-copy', function (event) {
    var path = $(event.target).data('path');
    copyTextToClipboard(path);
    // $(event.target).tooltip(
    //   { delay: { 'show': 500, 'hide': 500 } }
    // );
    
    $(event.target).attr('title', 'Path copied to clipboard').tooltip("show"); //show the tooltip
    
    setTimeout(function () {
      $(event.target).tooltip("hide");
      $(event.target).tooltip("destroy");
      $(event.target).attr('title', path);
      }, 1000);

  })
});

