function exportar(url, parametros){

  $('#opciones').attr('disabled',true);
  $('#icon-opciones').removeClass('fa-gears');
  $('#icon-opciones').addClass('fa-refresh fa-spin');


  $.ajax({
      type: "POST",
      url: url,
      data: parametros,
      xhrFields: {
          responseType: 'blob' // to avoid binary data being mangled on charset conversion
      },
      success: function(blob, status, xhr) {
          // check for a filename
          var filename = "";
          var disposition = xhr.getResponseHeader('Content-Disposition');
          if (disposition && disposition.indexOf('attachment') !== -1) {
              var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
              var matches = filenameRegex.exec(disposition);
              if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
          }

          if (typeof window.navigator.msSaveBlob !== 'undefined') {
              // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
              window.navigator.msSaveBlob(blob, filename);
          } else {
              var URL = window.URL || window.webkitURL;
              var downloadUrl = URL.createObjectURL(blob);

              if (filename) {
                  // use HTML5 a[download] attribute to specify filename
                  var a = document.createElement("a");
                  // safari doesn't support this yet
                  if (typeof a.download === 'undefined') {
                      window.location.href = downloadUrl;
                  } else {
                      a.href = downloadUrl;
                      a.download = filename;
                      document.body.appendChild(a);
                      a.click();
                  }
              } else {
                  window.location.href = downloadUrl;
              }

              setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
          }

          $('#opciones').attr('disabled',false);
          $('#icon-opciones').removeClass('fa-refresh fa-spin');
          $('#icon-opciones').addClass('fa-gears');
      },
      error: function(blob, status, xhr){
          toastr.options.positionClass = 'toast-bottom-right';
          toastr.error(xhr);

          $('#opciones').attr('disabled',false);
          $('#icon-opciones').removeClass('fa-refresh fa-spin');
          $('#icon-opciones').addClass('fa-gears');
      }
  });
}
