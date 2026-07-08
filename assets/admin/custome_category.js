var BaseUrl = 'https://ase-electrical.co.uk/rent-house/admin/';
var webUrl = 'https://ase-electrical.co.uk/rent-house/';

   $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var userlist;
    userlist = $('#userlist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'em_userlist',
            "type": "POST",
            "data": function ( data ) {
                data.type = $('#type').val();
                 data.organization_id = $('#organization_id').val();
                data.name_search = $('#name_search').val();
                data.status = $('#status').val();
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-userlist').click(function(){ //button filter event click
        userlist.ajax.reload();  //just reload table
    });
    $('#btn-reset-userlist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        userlist.ajax.reload();  //just reload table
    });
function reload_table()
{
    userlist.ajax.reload(null,false); //reload datatable ajax 
}


});





   $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var memberlist;
    memberlist = $('#memberlist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'memberlist',
            "type": "POST",
            "data": function ( data ) {
                data.name_search = $('#name_search').val();
                data.status = $('#status').val();
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-memberlist').click(function(){ //button filter event click
        memberlist.ajax.reload();  //just reload table
    });
    $('#btn-reset-memberlist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        memberlist.ajax.reload();  //just reload table
    });
function reload_table()
{
    memberlist.ajax.reload(null,false); //reload datatable ajax 
}
});
  $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var scorelist;
    scorelist = $('#scorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'scorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-scorelist').click(function(){ //button filter event click
        scorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-scorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        scorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    scorelist.ajax.reload(null,false); //reload datatable ajax 
}


});





  $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var scorelist;
    cricketscorelist = $('#cricketscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'cricketscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-cricketscorelist').click(function(){ //button filter event click
        cricketscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-cricketscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        cricketscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    cricketscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});



  $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var scorelist;
    basketscorelist = $('#basketscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'basketscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-basketscorelist').click(function(){ //button filter event click
        basketscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-basketscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        basketscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    basketscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});

  $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var badmintonscorelist;
    badmintonscorelist = $('#badmintonscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'badmintonscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-badmintonscorelist').click(function(){ //button filter event click
        badmintonscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-badmintonscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        badmintonscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    badmintonscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});

$(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var tabletennisscorelist;
    tabletennisscorelist = $('#tabletennisscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'tabletennisscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-tabletennisscorelist').click(function(){ //button filter event click
        tabletennisscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-tabletennisscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        tabletennisscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    tabletennisscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});


$(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var runningscorelist;
    runningscorelist = $('#runningscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'runningscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-runningscorelist').click(function(){ //button filter event click
        runningscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-runningscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        runningscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    runningscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});

 $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var swimmingscorelist;
    swimmingscorelist = $('#swimmingscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'swimmingscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-swimmingscorelist').click(function(){ //button filter event click
        swimmingscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-swimmingscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        swimmingscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    swimmingscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});



  $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var tennisscorelist;
    tennisscorelist = $('#tennisscorelist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'tenniscorelist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-tennisscorelist').click(function(){ //button filter event click
        tennisscorelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-tennisscorelist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        tennisscorelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    tennisscorelist.ajax.reload(null,false); //reload datatable ajax 
}


});


  $(document).ready(function () {
      var comantable
       comantable = $('.comantable').DataTable({
             columnDefs: [
              
        ]
       });
    var fixtureslist;
    fixtureslist = $('#fixtureslist').DataTable({
        columnDefs: [
              
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'fixtureslist',
            "type": "POST",
            "data": function ( data ) {
            
                
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });

    $('#btn-filter-fixtureslist').click(function(){ //button filter event click
        fixtureslist.ajax.reload();  //just reload table
    });
    $('#btn-reset-fixtureslist').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        fixtureslist.ajax.reload();  //just reload table
    });
function reload_table()
{
    fixtureslist.ajax.reload(null,false); //reload datatable ajax 
}


});
 // catetegory -----------------------------------------------------------------------------------------------------------------------------------------------------
categoryList();
function categoryList(){
  var parent_id =  $('#parent_id').val();
  
  var subparent_id =  $('#subparent_id').val();


  
  
  
	$.ajax({
		type : "POST",
			url  : BaseUrl +"category-list",
     
     dataType : 'json',
      data : {parent_id: parent_id,subparent_id:subparent_id},
		success : function(data){
			var html = '';
			var i;
			for(i=0; i<data.length; i++){
			    if(parent_id >0){
      path = BaseUrl +'childcategory/'+data[i].category_id;
  } else if(subparent_id>0) {
       path = '#';
  } else {
       path = BaseUrl +'subcategory/'+data[i].category_id;
  }  
			    
				html += '<tr id="'+data[i].category_id+'" >'+
            '<td style="text-align: center;" contenteditable class="update" data-id="'+data[i].category_id+'" data-column="'+data[i].menu_order+'">'+data[i].menu_order+'</td>'+
             '<td style="text-align: center;">'+data[i].category_id+'</td>'+
						'<td style="text-align: center;">'+'<a href="#"> '+data[i].category_name+'('+ data[i].subCatCount +')</a><br>'+ data[i].subCatName+'</td>'+
            '<td style="text-align: center;">'+'<img src="'+webUrl+'/assets/category/'+data[i].category_image+'" style="height:100px; width:100px;">'+'</td>'+
						'<td style="text-align: center;">'+'<a href="javascript:void(0);" class="btn btn-'+data[i].category_status_color+' btn-xs">'+data[i].category_status+'</a>'+'</td>'+
						'<td style="text-align:center;">'+
							'<a href="javascript:void(0);" class="btn btn-info btn-xs editRecordCategory" data-menu_order="'+data[i].menu_order+'" data-category_id="'+data[i].category_id+'" data-category_name="'+data[i].category_name+'" data-category_status="'+data[i].category_status+'" data-category_image="'+data[i].category_image+'" data-category_title="'+data[i].metaTitle+'" data-category_keyword="'+data[i].metaKeyword+'" data-category_description="'+data[i].metaDescription+'">Edit</a>'+'  </td>'+
						'</tr>';
			}
			$('#category_list').html(html);					
		}
	});
}

$('#SavedCategory').submit('click',function(){
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
		$.ajax({
			type : "POST",
			url  : BaseUrl +"category_save",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
				$('#addCategory').modal('hide');
				$("#SavedCategory")[0].reset();
				 $(":submit").attr("disabled", false);
				categoryList();
				
			}
		});
		return false;
	});
	
	// show edit modal form with emp data
	$('#category').on('click','.editRecordCategory',function(){
	   
		$('#editCategory').modal('show');
		$("#edit_categoryID").val($(this).data('category_id'));
	
			$("#edit_categoryOrder").val($(this).data('menu_order'));
		$("#edit_categoryName").val($(this).data('category_name'));
		$("#edit_categoryStatus").val($(this).data('category_status'));
    $("#edit_categoryImage").val($(this).data('category_image'));
    $("#edit_categoryTitle").val($(this).data('category_title'));
    $("#edit_categoryKeyword").val($(this).data('category_keyword'));
    $("#edit_categoryDescription").val($(this).data('category_description'));
	});
	
	$('#EditedCategory').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : BaseUrl +"category_update",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
				$('#editCategory').modal('hide');
				$("#EditedCategory")[0].reset();
				 $(":submit").attr("disabled", false);
				categoryList();
			}
		});
		return false;
	});
	
// position	---------------------------------------------------------------------------------------------------------------------------------------------------------------
	
positionList();
function positionList(){



 var access = 'access';
  
  
	$.ajax({
		type : "POST",
			url  : BaseUrl +"position-list",
     
     dataType : 'json',
      data : {access: access},
		success : function(data){
			var html = '';
			var i;
			for(i=0; i<data.length; i++){

			    
				html += '<tr id="'+data[i].category_id+'" >'+
            '<td style="text-align: center;" contenteditable class="update" data-id="'+data[i].position_id+'" data-column="'+data[i].position_id+'">'+data[i].position_id+'</td>'+
           
						'<td style="text-align: center;">'+data[i].position_name+'</td>'+
            
						'<td style="text-align: center;">'+'<a href="javascript:void(0);" class="btn btn-'+data[i].position_status_color+' btn-xs">'+data[i].position_status+'</a>'+'</td>'+
						'<td style="text-align:center;">'+
							'<a href="javascript:void(0);" class="btn btn-info btn-xs editRecordposition" data-position_id="'+data[i].position_id+'" data-position_id="'+data[i].position_id+'" data-position_name="'+data[i].position_name+'" data-position_status="'+data[i].position_status+'"  >Edit</a>'+'  </td>'+
						'</tr>';
			}
			$('#position_list').html(html);					
		}
	});
}
	
$('#Savedposition').submit('click',function(){
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
		$.ajax({
			type : "POST",
			url  : BaseUrl +"position_save",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
				$('#addposition').modal('hide');
				$("#Savedposition")[0].reset();
				 $(":submit").attr("disabled", false);
				positionList();
				
			}
		});
		return false;
	});
	
	// show edit modal form with emp data
	$('#position').on('click','.editRecordposition',function(){
	   
		$('#editposition').modal('show');
		$("#edit_positionID").val($(this).data('position_id'));
	
		
		$("#edit_positionName").val($(this).data('position_name'));
		$("#edit_positionStatus").val($(this).data('position_status'));
  
	});
	
	$('#Editedposition').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : BaseUrl +"position_update",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
				$('#editposition').modal('hide');
				$("#Editedposition")[0].reset();
				 $(":submit").attr("disabled", false);
				positionList();
			}
		});
		return false;
	});
		
	  //  Product Listing Ajax data call -----------------------------------------------------------------------------------------------------------------------------
var productlist;
    //datatables
    productlist = $('#productlist').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'list_product_ajax',
            "type": "POST",
            "data": function ( data ) {
				
		
                data.product_search = $('#product_search').val();
                data.product_status = $('#product_status').val();
                data.menu_category_search = $('#menu_category_search').val();
              
                data.product_brand = $('#product_brand').val();
               
                data.product_show = $('#product_show').val();
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });


    $('#btn-filter-product').click(function(){ //button filter event click
        productlist.ajax.reload();  //just reload table
    });
    $('#btn-reset-product').click(function(){ //button reset event click
        $('#product-filter')[0].reset();
        productlist.ajax.reload();  //just reload table
    });
function reload_table()
{
    productlist.ajax.reload(null,false); //reload datatable ajax 
}


    $('#productlist').on('click', '.productTrending', function(){  
          var product_id = $(this).val();
          if ($(this).is(":checked"))
          {
              var trending = 'Yes';
          }
          else
          {
              var trending = 'No'; 
          }
          //alert(trending);
          $.ajax({
           method: "post",
           url: "product_trending_action",
           cache: false,    
           data:  { product_id: product_id, trending_product:trending },
           success: function(returnsellup)
           {
           },
          });
    });

	  //  Product Listing Ajax data call
var employeelist;
    //datatables
    employeelist = $('#employeelist').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'list_employee_ajax',
            "type": "POST",
            "data": function ( data ) {
				
		
                data.product_search = $('#employeeuct_search').val();
                data.product_status = $('#employee_status').val();
                data.menu_category_search = $('#menu_category_search').val();
              
                data.product_brand = $('#product_brand').val();
               
                data.product_show = $('#product_show').val();
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });


    $('#btn-filter-employee').click(function(){ //button filter event click
        employeelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-employee').click(function(){ //button reset event click
        $('#employee-filter')[0].reset();
        employeelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    employeelist.ajax.reload(null,false); //reload datatable ajax 
}

    $('#employeelist').on('click', '.attendance_check_in', function(){  
          var employee_id = $(this).data('employee_id');
          var attendance_type = $(this).data('attendance_type');
     
         
          $.ajax({
           method: "post",
           url: BaseUrl +"attendance_check_in",
           cache: false,    
           data:  { employee_id: employee_id, attendance_type:attendance_type },
           success: function(returnsellup)
           {
               employeelist.ajax.reload(null,false);
           },
          });
    });
      $('#employeelist').on('click', '.attendance_check_out', function(){  
          var employee_id = $(this).data('employee_id');
          var attendance_id = $(this).data('attendance_id');
     
         
          $.ajax({
           method: "post",
           url: BaseUrl +"attendance_check_out",
           cache: false,    
           data:  { employee_id: employee_id, attendance_id:attendance_id },
           success: function(returnsellup)
           {
               employeelist.ajax.reload(null,false);
           },
          });
    });
    
    	  //  Product Listing Ajax data call
var attendancelist;
    //datatables
    attendancelist = $('#attendancelist').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BaseUrl +'list_attendance_ajax',
            "type": "POST",
            "data": function ( data ) {
				
		
                data.product_search = $('#attendanceuct_search').val();
                data.product_status = $('#attendance_status').val();
                data.menu_category_search = $('#menu_category_search').val();
              
                data.product_brand = $('#product_brand').val();
               
                data.product_show = $('#product_show').val();
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });


    $('#btn-filter-attendance').click(function(){ //button filter event click
        attendancelist.ajax.reload();  //just reload table
    });
    $('#btn-reset-attendance').click(function(){ //button reset event click
        $('#attendance-filter')[0].reset();
        attendancelist.ajax.reload();  //just reload table
    });
function reload_table()
{
    attendancelist.ajax.reload(null,false); //reload datatable ajax 
}


	$('#attendancelist').on('click','.attendanceEdit',function(){
	   
		$('#editattendance').modal('show');
		$("#edit_attendanceID").val($(this).data('attendance_id'));
	
	
			$("#check_in_date").val($(this).data('check_in_date'));
				$("#check_in_time").val($(this).data('check_in_time'));
					$("#check_out_date").val($(this).data('check_out_date'));
						$("#check_out_time").val($(this).data('check_out_time'));
		$("#edit_Status").val($(this).data('attendance_type'));
		$("#edit_Reasons").val($(this).data('attendance_review'));
		
		   
  
	});
	$('#AttendanceUpdate').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : BaseUrl +"attendance_update",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
				$('#editattendance').modal('hide');
				$("#AttendanceUpdate")[0].reset();
				 $(":submit").attr("disabled", false);
				
				
				attendancelist.ajax.reload(null,false);
			}
		});
		return false;
	});
