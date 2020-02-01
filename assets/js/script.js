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

  });

  //event handler when edit details button is clicked
  $(document).on('click', '.btn-edit-desc', function (event) {
    var projid = $(event.target).data('proj-id');
    var jsonFile =  $('#form-refresh').data('json-file');
    //console.log(projid);
    //console.log(jsonFile);

    var toSend = {};
    toSend['projid'] = projid;
    toSend['json-file'] = jsonFile;

    //load modal via ajax passing id as parameter
    $.ajax({
      url: 'http://appdev.local/assets/includes/partials/modaldlg.php',
      data: toSend,
      type: 'GET',
      success: function (dlg) {
        //on success inject modal into DOM  
        $('#modaldlg').html(dlg);
        
        //show modal dialog
        $('#modal-edit-details').modal('show');

        //remove modal from DOM on close
        $("#modal-edit-details").on('hidden.bs.modal', function () {

          $("#modal-edit-details").remove();
        });

        //event handler when modal saves
        $('#btn-save').click(function() {
          var myData =  $('#edit-form').serializeArray();
          myData.push({name: 'projid', value: projid});
          myData.push({name: 'json-file', value: jsonFile});
          $.ajax({
            url: 'http://appdev.local/assets/includes/partials/modaldlg.php',
            data: $.param(myData),
            type: 'POST',
            success: function (msg) {
              //display msg

              //update UI
              var dlgID = $("#modal-edit-details").data('proj-id');
              var detailsTitle = $('#details-title').val();
              var detailsDesc = $('#details-description').val();
              $('#title-' + dlgID).text(detailsTitle);
              $('#desc-' + dlgID).text(detailsDesc);
            }
          }

          );
        }); //btnSaveClick
      }
    });
        
  });

  

  //

});

