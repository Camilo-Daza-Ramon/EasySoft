var page = 1;
var current_page = 1;
var total_page = 0;
var is_ajax_fire = 0;
var _token = $('input:hidden[name=_token]').val();
var parametros = {
    nombre : $('#nombre').val(),
    '_token' : $('input:hidden[name=_token]').val(),
    codigo_dane : $('#codigo_dane').val(),
    estado : $('#estado').val()
    /*"_token" : $('input:text[name=_token]').val()*/
};

manageData();

/* manage data list */
function manageData() {
	$.ajax({
        dataType: 'json',
        url: url,
        data: {page:page}
    }).done(function(data){

    	total_page = data.last_page;
    	current_page = data.current_page;

    	$('#pagination').twbsPagination({
	        totalPages: total_page,
	        visiblePages: current_page,
	        onPageClick: function (event, pageL) {
	        	page = pageL;
                if(is_ajax_fire != 0){
	        	  getPageData();
                }
	        }
	    });

    	manageRow(data.data);
        is_ajax_fire = 1;
    });
}

/*$.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': _token
        }
});*/

/* Get Page Data*/
function getPageData() {
	$.ajax({
    	dataType: 'json',
    	url: url,
    	data: {page:page}
	}).done(function(data){
		manageRow(data.data);
	});
}

/* Add new Item table row */
function manageRow(data) {
	var	rows = '';
	$.each( data, function( key, value ) {
	  	rows = rows + '<tr>';
        rows = rows + '<td>'+value.id+'</td>';
	  	rows = rows + '<td>'+value.nombre+'</td>';
	  	rows = rows + '<td>'+value.codigo_dane+'</td>';
	  	rows = rows + '<td data-id="'+value.id+'">';
        rows = rows + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Edit</button> ';
        rows = rows + '<button class="btn btn-danger remove-item">Delete</button>';
        rows = rows + '</td>';
	  	rows = rows + '</tr>';
	});

	$("tbody").html(rows);
}

/* Create new Item */
$(".crud-submit").click(function(e){
    e.preventDefault();
    var form_action = $("#create-item").find("form").attr("action");
    var nombre = $("#create-item").find("input[name='nombre']").val();
    var codigo_dane = $("#create-item").find("input[name='codigo_dane]").val();
    var estado = $("#create-item").find("select[name='estado]").val();

    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:parametros
    }).done(function(data){
        getPageData();
        $(".modal").modal('hide');
        toastr.success('Item Created Successfully.', 'Success Alert', {timeOut: 5000});
    });
	
});

/* Remove Item */
$("body").on("click",".remove-item",function(){
    var id = $(this).parent("td").data('id');
    var c_obj = $(this).parents("tr");
    $.ajax({
        dataType: 'json',
        type:'delete',
        url: url + '/' + id,
    }).done(function(data){
        c_obj.remove();
        toastr.success('Item Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        getPageData();
    });
});

/* Remove Item */
$("body").on("click",".edit-item",function(){
    var id = $(this).parent("td").data('id');
    var title = $(this).parent("td").prev("td").prev("td").text();
    var description = $(this).parent("td").prev("td").text();

    $("#edit-item").find("input[name='title']").val(title);
    $("#edit-item").find("textarea[name='description']").val(description);
    $("#edit-item").find("form").attr("action",url + '/' + id);
});

/* Create new Item */
$(".crud-submit-edit").click(function(e){
    e.preventDefault();
    var form_action = $("#edit-item").find("form").attr("action");
    var title = $("#edit-item").find("input[name='title']").val();
    var description = $("#edit-item").find("textarea[name='description']").val();

    $.ajax({
        dataType: 'json',
        type:'PUT',
        url: form_action,
        data: parametros
    }).done(function(data){
        getPageData();
        $(".modal").modal('hide');
        toastr.success('Item Updated Successfully.', 'Success Alert', {timeOut: 5000});
    });
});