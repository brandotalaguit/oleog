$(document).keydown(function(e) {
  if (e.ctrlKey)
  {
    e.preventDefault();
    alert( "This key is disabled on this system" );
    // $("#errMsg .modal-body").empty().append("<strong class='text-danger'><i class='fa fa-warning'></i>This key is disabled on this system</strong> ");
    // $("#errMsg").modal('show');
    return false;
  }
  if (e.keyCode>=112 && e.keyCode<=123)
  {
    e.preventDefault();
    alert( "This key is disabled on this system" );
    return false;
    // $("#errMsg .modal-body").empty().append("<strong class='text-danger'><i class='fa fa-warning'></i>This key is disabled on this system</strong> ");
    // $("#errMsg").modal('show');
    
  }
  // console.log(e.which);
});
 $(document).on("mousedown",function(e) {
  if (e.button == 2)
  {
    e.preventDefault();
    alert( "This key is disabled on this system" );
    return false;
    // $("#errMsg .modal-body").empty().append("<strong class='text-danger'><i class='fa fa-warning'></i>This key is disabled on this system</strong> ");
    // $("#errMsg").modal('show');     
  } 
});
document.addEventListener("contextmenu", function(e){
    e.preventDefault();
}, false);