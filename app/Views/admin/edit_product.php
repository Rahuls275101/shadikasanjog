
<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();


		$arrayVariant = array();
	$priceVariant = array();
	$availableVariant = array();

 $pro_variant = $commanmodel->all_multiple_query_order_by('pro_variant',array('variant_pro_id' => $product->product_id),'pro_variant_id','ASC'); 
 
 foreach($pro_variant as $pro_variantrow) {
     $arrayVariant[] = $pro_variantrow->varian;
    $priceVariant[$pro_variantrow->varian] = array($pro_variantrow->pro_variant_price); 
    $availableVariant[$pro_variantrow->varian] = array($pro_variantrow->pro_variant_available);
 }
 

$arrayVariantJson = json_encode($arrayVariant);

$priceVariantJson = json_encode($priceVariant);

$availableVariantJson = json_encode($availableVariant);
?>
<style>
.note-editable,card-block
{
  height:200px !important;
}
</style>
<style>
.note-editable,card-block
{
  height:200px !important;
}
</style>
<style>
    .boximage{
            position: absolute;
    z-index: 999;
    right: 9px;
        float: right;
    font-size: 20px;
    padding: 7px;
    color: #183de7;
    }
</style>
<style type="text/css">

@import url(https://fonts.googleapis.com/icon?family=Material+Icons);
@import url("https://fonts.googleapis.com/css?family=Raleway");



.box {
    display: block;
    min-width: 83px;
    height: 185px;
    margin: 8px;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    overflow: hidden;
}

.upload-options {
  position: relative;
  height: 75px;
  background-color: cadetblue;
  cursor: pointer;
  overflow: hidden;
  text-align: center;
  transition: background-color ease-in-out 150ms;
}
.upload-options:hover {
  background-color: #7fb1b3;
}
.upload-options input {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
.upload-options label {
  display: flex;
  align-items: center;
  width: 100%;
  height: 100%;
  font-weight: 400;
  text-overflow: ellipsis;
  white-space: nowrap;
  cursor: pointer;
  overflow: hidden;
}
.upload-options label::after {
  content: "add";
  font-family: "Material Icons";
  position: absolute;
  font-size: 2.5rem;
  color: #e6e6e6;
  top: calc(50% - 2.5rem);
  left: calc(50% - 1.25rem);
  z-index: 0;
}
.upload-options label span {
  display: inline-block;
  width: 50%;
  height: 100%;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  vertical-align: middle;
  text-align: center;
}
.upload-options label span:hover i.material-icons {
  color: lightgray;
}

.js--image-preview {
    height: 133px;
    width: 100%;
    position: relative;
    overflow: hidden;
    background-image: url(category-create);
    background-color: white;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.js--image-preview::after {
  content: "photo_size_select_actual";
  font-family: "Material Icons";
  position: relative;
  font-size: 4.5em;
  color: #e6e6e6;
  top: calc(50% - 3rem);
  left: calc(50% - 2.25rem);
  z-index: 0;
}
.js--image-preview.js--no-default::after {
  display: none;
}
.js--image-preview:nth-child(2) {
  background-image: url("http://bastianandre.at/giphy.gif");
}

i.material-icons {
  transition: color 100ms ease-in-out;
  font-size: 2.25em;
  line-height: 55px;
  color: white;
  display: block;
}

.drop {
  display: block;
  position: absolute;
  background: rgba(95, 158, 160, 0.2);
  border-radius: 100%;
  transform: scale(0);
}

.animate {
  -webkit-animation: ripple 0.4s linear;
          animation: ripple 0.4s linear;
}

@-webkit-keyframes ripple {
  100% {
    opacity: 0;
    transform: scale(2.5);
  }
}

@keyframes ripple {
  100% {
    opacity: 0;
    transform: scale(2.5);
  }
}
</style>
<?php 


?> 




	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Update Vendor</h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Update Vendor
							</p>
						</div>

						
					</div>
					<div class="row">
						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
					
					      <div class="card">

          <?php if(session()->getFlashdata('failed')):?>
                    <div class="alert alert-danger alert-dismissable">
                       <?= session()->getFlashdata('failed') ?>
                    </div>
                <?php endif;?>

                <?php if(session()->getFlashdata('created')):?>
                    <div class="alert alert-success alert-dismissable">
                       <?= session()->getFlashdata('created') ?>
                    </div>
                <?php endif;?>

           

            <!-- /.card-header -->
                <form method="POST" action="<?php echo base_url('admin/update_product_process/'.$id); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">

                  <div class="col-md-9">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Vendor Title*</label>
                    <input type="text" required class="form-control" name="name" value="<?php echo $product->product_name; ?>">
                  </div>
                  </div>
               
                 
                       <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" required name="status">
                    <option value="Active" <?php if('Inactive' == $product->product_status) { echo "selected"; } ?>>Active</option>
                    <option value="Inactive" <?php if('Inactive' == $product->product_status) { echo "selected"; } ?>>Inactive</option>
                    </select> 
                  </div>
                  </div>
                  
             <div class="col-md-12" > 
               <div class="form-group">
                  <label for="exampleInputEmail1">Select Category</label>
                  <div id="category">
                      
                  </div>
                  </div>
                  </div>
                  
   
   
                      
               
             <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputFile"> Pdf</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="form-control" name="defaultimage">
                        
                        <input type="hidden" class="custom-file-input" name="primary_image_old" value="<?php echo $product->product_thumbnail; ?>">
                      </div>
                    </div>
                  </div>
                  </div>
                  
               
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1"> Overview</label>
                    <div class="mb-3">
                    <textarea class="ckeditor" placeholder="Enter Overview" style="width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="overview" id="overview"><?php echo $product->product_overview; ?></textarea>
                    </div>
                  </div>
                  </div>
      

  
                  
                 
                  
                  
                  
             <div class="card-footer" style="text-align: center;">
                  <input type="submit" name="CreateNewProduct" value="Submit" class="btn btn-primary">
                </div>
              </form>


                  </div>
                </div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->




<script>

    $(document).ready(function () {
        
        function getcat(id, select, htmlid){
    
     $('#' + htmlid).html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('getcategory'); ?>',
        data: {
            id: id,
            select: select
        },
        success: function(data){
            if (data != '') {
                $('#' + htmlid).html(data); 
            }
        }
    });
}

getcat(0, <?php echo $product->product_category; ?>, 'category');

    });
</script>






<script type="text/javascript">
     $(document).ready(function(){
const maxGroup = 5;
const maxField = 10;
let groupCounter = 1;
let arrayVariant = [];

let arrayPrice = [];
let arrayAvailable = [];
let arrayVimages = [];

let quantity = 0;

 $('.addprice').each(function() {
        const variantName = $(this).data('variant');
        const price = $(this).val();
        arrayPrice[variantName] = price;
    });

   $('.addAvailable').each(function() {
    const variantName = $(this).data('variant');
    const available = parseInt($(this).val(), 10) || 0; // Parse to integer and handle non-numeric values
    quantity += available; // Update the existing quantity variable
    arrayAvailable[variantName] = available; // Store available quantity in the array
    $('#quantity').val(quantity);
});
    
     $('.addVImage').each(function() {
       
            const variantName = $(this).data('variant');
            const images = $(this).val();
            arrayVimages[variantName] = images;
        
    });


function addGroup() {
    if ($('.group_wrapper').length < maxGroup) {
        const groupHtml = generateGroupHTML(groupCounter);
        $('.group_wrapper:last').after(groupHtml);
        $('#groupcount').val(groupCounter++);
        initializeSelect2(); // Ensure select2 is initialized

        // Trigger variant creation only if items are selected or added
       
    }
}



    
    
    

function generateGroupHTML(groupId) {
    return `
        <div class="group_wrapper">
            <button type="button" class="remove_group btn btn-sm btn-danger float-end"><i class="mdi mdi-minus"></i> Remove Group</button>
            <table class="table">
                <thead>
                    <tr><th>Item Name</th><th>Action</th></tr>
                </thead>
                <tbody class="field_wrapper">
                    <tr class="item-group">
                        <td><input type="text" class="form-control group-name" placeholder="Group name" name="group_${groupId}"></td>
                    </tr>
                    <tr class="item">
                        <td><input type="text" class="form-control item-name" placeholder="Item name" name="item_${groupId}[]"></td>
                        <td><button type="button" class="add_button btn btn-sm btn-primary" title="Add field"><i class="mdi mdi-plus-outline"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>`;
}

function removeGroup() {
    $(this).closest('.group_wrapper').remove();
    variantCreate(); // Update variants when a group is removed
}

function onGroupNameChange() {
    const groupName = $(this).val();
    const $item = $(this).closest('.group_wrapper').find('.item-name');
    $item.removeClass(function (index, className) {
        return (className.match(/(^|\s)variant-\S+/g) || []).join(' ');
    }).addClass('variant-' + groupName);

    variantCreate();
    
}





function variantCreate() {
    let classNamesArray = getClassNames();
    let variant = {};

    // Check if there are any valid items to create variants for
    if (classNamesArray.length === 0) {
        return; // Exit if no valid items exist
    }

    // Continue with the variant creation process if items exist
    classNamesArray.forEach(className => {
        variant[className] = new Set();
        $('.' + className).each(function () {
            let values = [];

            if ($(this).is('select')) {
                // Get selected values, and handle unselecting
                $(this).find('option:selected').each(function () {
                    let dataName = $(this).data('name');
                    let textValue = $(this).text();
                    let value = dataName || textValue;
                    if (value) values.push(value);
                });

                // If no option is selected, ensure to remove variants
                if (values.length === 0) {
                    let className = $(this).attr('class');
                    $('.' + className).each(function () {
                        $(this).removeClass('variant-' + className);
                    });
                }
            } else {
                let value = $(this).val();
                if (value) values.push(value);
            }

            values.forEach(val => variant[className].add(val));
        });
    });

    // Only proceed if we have created valid variants
    if (Object.keys(variant).length === 0 || Object.values(variant).every(set => set.size === 0)) {
        return; // Exit if no valid variants can be created
    }

    const cartesianProduct = Object.values(variant).reduce((a, b) =>
        a.flatMap(x => [...b].map(y => [...x, y])), [[]]);


    generateVariantHTML(cartesianProduct);
}



function generateVariantHTML(cartesianProduct) {
    let htmlVariant = `<tr class="item-group"><td>Variant</td><td>Price</td><td>Available</td><td>Image</td></tr>`;
    arrayVariant = [];

    cartesianProduct.forEach((combination, index) => {
        const variantName = combination.join("-");
        arrayVariant.push(variantName);

        // Get price, availability, and image from the arrays (if available)
        const price = arrayPrice[variantName] || '';  // Default to empty string if no price found
        const available = arrayAvailable[variantName] || '';  // Default to empty string if no availability found
        const images = arrayVimages[variantName] || '';  // Get image if available

        // Add the variant row with pre-populated values (price, availability, and image)
        htmlVariant += `
            <tr class="item-group-${variantName}">
                <td>${variantName} <input type="hidden" name="variant[]" value="${variantName}"></td>
                <td><input type="text" class="form-control addprice" placeholder="0.00" name="variant_price[]" data-variant="${variantName}" value="${price}"></td>
                <td><input type="number" class="form-control addAvailable" placeholder="0" name="variant_available[]" data-variant="${variantName}" value="${available}"></td>
                <td><input type="file" class="form-control" placeholder="" name="variant_images[]">`;

        // Check if there is an image for the variant and add the image thumbnail
        if (images !== '') {
            htmlVariant += `
                <td>
                    <input type="hidden" class="form-control addVImage" placeholder="0" name="variant_edit_images[]" data-variant="${variantName}" value="${images}">
                    <img class="cat-thumb" src="<?php echo base_url('assets/images/'); ?>/${images}" alt="Variant Image" style="max-width: 100px; max-height: 100px;">
                </td>`;
        } else {
            htmlVariant += `<td></td>`;
        }

        htmlVariant += `</tr>`;
    });

    $('.group_variant').html(htmlVariant);  // Insert the generated HTML
}





function getClassNames() {
    let classNamesArray = [];
    $('.item-name').each(function () {
        const classAttr = $(this).attr('class');
        const classNames = classAttr.match(/\bvariant-\S+/g);
        if (classNames) classNamesArray.push(...classNames);
    });

    // Also include select elements in class name collection
    $('.select2').each(function () {
        const classAttr = $(this).attr('class');  
        const classNames = classAttr.match(/\bvariant-\S+/g);
        if (classNames) classNamesArray.push(...classNames);
    });

    return classNamesArray;
}



$(document).on('change', '.add_group', function () {
    if ($(this).val() === 'add') {
        addGroup();
        $(this).val(""); // Reset the select after adding a group
    } else {
        var id = $(this).val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('getattributes'); ?>',
            dataType: "JSON",
            data: {
                id: id
            },
            success: function(data) {
                if (data.main && data.attributes) {
                    
                    
                    var groupId = groupCounter++;  // Assuming `groupCounter` is defined elsewhere in your code
                    $('#groupcount').val(groupId);
                    var groupHtml = `
                    <div class="group_wrapper">
                        <button type="button" class="remove_group btn btn-sm btn-danger float-end">
                            <i class="mdi mdi-minus"></i> Remove Group
                        </button>
                        
                        <table class="table">
                            <thead>
                                <tr><th>Item Name</th><th>Action</th></tr>
                            </thead>
                            <tbody class="field_wrapper">
                                <tr class="item-group">
                                    <td><input type="text" class="form-control group-name" placeholder="Group name" name="group_${groupId}" value="${data.main.attribute_main_name}"></td>
                                </tr>
                                <tr class="item">
                                    <td>
                                        <!-- Select2 with tags and multiple selections enabled -->
                                        <select class="select2 select2-hidden-accessible item-name" multiple="" data-placeholder="Select amenities" style="width: 100%;" name="item_${groupId}[]" >
                                            `;

                    // Loop through the attributes and create <option> tags dynamically
                    for (var i = 0; i < data.attributes.length; i++) {
                        var attributeSymbol = data.attributes[i].attributes_symbol;
                        groupHtml += `<option value="${data.attributes[i].attributes_id}" data-color="${attributeSymbol}" data-name="${data.attributes[i].attributes_name}" data-id="${data.attributes[i].attributes_id}">
                                        ${data.attributes[i].attributes_name}
                                    </option>`;
                    }
                    
                    groupHtml += `</select>
                                    </td>
                                    <td></td
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>`;

                    $('.group_wrapper:last').after(groupHtml);

                    // Initialize select2 for the new select element
                    initializeSelect2();

                    // Manually trigger the group name change to update variants
                    const newGroupNameField = $('.group_wrapper:last').find('.group-name');
                    onGroupNameChange.call(newGroupNameField[0]);

                } else {
                    console.error('Invalid data received:', data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
            }
        });
    }
});

// Initialize select2 and use templates to customize the display
function initializeSelect2() {
    $('.select2').select2({
        templateResult: function (data) {
            if (!data.id) return data.text; // No need to customize the placeholder

            var color = $(data.element).data('color');  // Get the color from data-color attribute
            var name = $(data.element).data('name') || data.text;  // Get the name from data-name attribute or use the text as fallback

            var $span = $('<span>').append(name); // Start by adding the name
            
            // Only add the color box if the color is defined
            if (color) {
                $span.append('<span style="display: inline-block; width: 20px; height: 20px; background-color: ' + color + '; border: 2px solid #fff; margin-left: 10px;"></span>');
            }
            
            return $span; // Combine the name and color preview (if available)
        },
        templateSelection: function (data) {
            variantCreate(); // If you need to call this on selection change
            var color = $(data.element).data('color'); // Get the color from data-color attribute
            var name = $(data.element).data('name') || data.text; // Get the name from data-name attribute or fallback to data.text
            
            var $span = $('<span>').append(name); // Start by adding the name
            
            // Only add the color box if the color is defined
            if (color) {
                $span.append('<span style="display: inline-block; width: 20px; height: 20px; background-color: ' + color + '; border: 2px solid #fff; margin-left: 10px;"></span>');
            }
            
            return $span; // Display the selected name with color preview (if available)
        }
    });
}




function checkAndUpdateGroupItems() {
    $('.group_wrapper').each(function() {
        // Iterate over all .group-name elements inside the current .group_wrapper
        $(this).find('.group-name').each(function() {
            const groupId = $(this).attr('name');
            const groupClass = groupId ? groupId.split('_')[1] : '';

            // Find items under this group
            $(this).closest('.group_wrapper').find('.item').each(function() {
                const itemId = $(this).find('.item-name').attr('name');
                const itemClass = itemId ? itemId.split('_')[1] : '';

                // Update the classes based on group and item
                if (groupClass && itemClass) {
                    $(this).find('.item-name').addClass(`variant-${groupClass}`);
                }
            });
        });

        // Second part of code: update classes for the item-name element in the current group
        const groupName = $(this).find('.group-name').val();
        const $item = $(this).find('.item-name');

        // Remove all existing variant classes
        $item.removeClass(function (index, className) {
            return (className.match(/(^|\s)variant-\S+/g) || []).join(' ');
        });

        // Add new variant class based on the group name
        if (groupName) {
            $item.addClass('variant-' + groupName);
        }

        // Call the variantCreate function after updating
        variantCreate();
    });
}


$(document).on('change', '.addprice', function () {
    const variantName = $(this).data('variant');  // Get variant name (e.g., 'blue-s')
    const price = $(this).val();  // Get the new price
    arrayPrice[variantName] = price;  // Store the price in the array

  
});


$(document).on('change', '.addAvailable', function () {
    const variantName = $(this).data('variant');  // Get variant name (e.g., 'blue-s')
    const available = parseInt($(this).val(), 10) || 0;  // Get the new value, parse as integer, handle invalid values
    
    arrayAvailable[variantName] = available;  // Store the value in the array

    // Recalculate the total sum
    let totalQuantity = 0;
    $('.addAvailable').each(function () {
        totalQuantity += parseInt($(this).val(), 10) || 0;  // Sum up all valid values
    });

    $('#quantity').val(totalQuantity);  // Update the total quantity in the input
});




$(document).on('click', '.remove_group', removeGroup);
$(document).on('keyup keydown change', '.group-name', onGroupNameChange);

$(document).on('keyup', '.item-name', variantCreate);


$(document).on('click', '.add_button', function () {
    const wrapper = $(this).closest('.group_wrapper').find('.field_wrapper');

    const groupId = $(this).closest('.group_wrapper').index();
      
    const itemClass = 'variant-' + $(this).closest('.group_wrapper').find('.group-name').val();
    
    if (wrapper.find('.item').length < maxField) {
        const fieldIndex = wrapper.find('.item').length + 1;
        const htmlFields = `
            <tr class="item">
                <td><input type="text" class="form-control item-name ${itemClass}" placeholder="Item name" name="item_${groupId}[]"></td>
                <td><button type="button" class="remove_button btn btn-sm btn-danger"><i class="mdi mdi-minus"></i></button></td>
            </tr>`;
        wrapper.append(htmlFields);

        // Trigger variant creation after adding an item
        variantCreate();
    }
});




   $(document).on('click', '.add_button_edit', function () {
        const wrapper = $(this).closest('.group_wrapper').find('.field_wrapper');
        const groupId = $(this).closest('.group_wrapper').index();
         var item = 'variant-'+$(this).closest('.group_wrapper').find('.group-name').val();
        groupEditId = $(this).data('fild_id')
        if (wrapper.find('.item').length < maxField) {
            const fieldIndex = wrapper.find('.item').length + 1;
            const htmlFields = `<tr class="item">
                <td><input type="text" class="form-control item-name ${item}" placeholder="Item name" name="item_new_edit_${groupEditId}[]"></td>
                <td><button type="button" class="remove_button btn btn-sm btn-danger"><i class="mdi mdi-minus"></i></button></td>
            </tr>`;
            wrapper.append(htmlFields);
             variantCreate();
        }
    });
    
    
     $(document).on('click', '.remove_button', function () {
        $(this).closest('tr').remove();
        variantCreate();
        
        
        
    });

  $(document).on('change', '.item-name, .group-name', function () {
    const groupWrapper = $(this).closest('.group_wrapper');
    const itemsExist = groupWrapper.find('.item-name').toArray().some(item => $(item).val() || $(item).val() !== "");

    if (itemsExist) {
        variantCreate();  // Create variants only if items are added or selected
    } else {
        // If no items are selected or entered, remove variants
        $('.group_variant').html("");  // Clear the variant list if nothing is selected
    }
});
         
$(document).ready(function() {
    // Initialize Select2 and process items when the page loads
    initializeSelect2();
    checkAndUpdateGroupItems();
    variantCreate();
});
    
         
        $("#addimage").click(function(){
 var html = '<div class="col-md-3 imagebox"><div class="form-group form-float"><div class="form-line"><label for="exampleInputEmail1">Image</label><div class="box"><div class="js--image-preview"> <i class="fas fa-trash-alt imageremove boximage" data-delete="Yes"></i></div><div class="upload-options"><label><input type="file" name="productimage[]" class="image-upload" accept="image/*" /></label></div></div></div></div></div>'; 

             $('.addimagedive').append(html);

             // initialize box-scope
var boxes = document.querySelectorAll('.imagebox');

for (let i = 0; i < boxes.length; i++) {
  let box = boxes[i];
  initDropEffect(box);
  initImageUpload(box);
  
}

             });
});
</script>
<script type="text/javascript">
 function initImageUpload(box) {

let imageremove = box.querySelector('.imageremove');
imageremove.addEventListener('click', imageremovefun);

function imageremovefun() {
  if($(this).data('delete')=='Yes') {
    box.remove();
  }
}

  let uploadField = box.querySelector('.image-upload');

  uploadField.addEventListener('change', getFile);

  function getFile(e){
    let file = e.currentTarget.files[0];
    checkType(file);
  }
  
  function previewImage(file){
    let thumb = box.querySelector('.js--image-preview'),
        reader = new FileReader();

    reader.onload = function() {
      thumb.style.backgroundImage = 'url(' + reader.result + ')';
    

    }
    reader.readAsDataURL(file);
    thumb.className += ' js--no-default';
  }

  function checkType(file){
    let imageType = /image.*/;
    if (!file.type.match(imageType)) {
      throw 'Datei ist kein Bild';
    } else if (!file){
      throw 'Kein Bild gew채hlt';
    } else {
      previewImage(file);
    }
  }
  
}

// initialize box-scope
var boxes = document.querySelectorAll('.imagebox');

for (let i = 0; i < boxes.length; i++) {
  let box = boxes[i];
  initDropEffect(box);
  initImageUpload(box);
  
}



/// drop-effect
function initDropEffect(box){
  let area, drop, areaWidth, areaHeight, maxDistance, dropWidth, dropHeight, x, y;
  
  // get clickable area for drop effect
  area = box.querySelector('.js--image-preview');
  area.addEventListener('click', fireRipple);
  
  function fireRipple(e){
    area = e.currentTarget
    // create drop
    if(!drop){
      drop = document.createElement('span');
      drop.className = 'drop';
      this.appendChild(drop);
    }
    // reset animate class
    drop.className = 'drop';
    
    // calculate dimensions of area (longest side)
    areaWidth = getComputedStyle(this, null).getProductValue("width");
    areaHeight = getComputedStyle(this, null).getProductValue("height");
    maxDistance = Math.max(parseInt(areaWidth, 10), parseInt(areaHeight, 10));

    // set drop dimensions to fill area
    drop.style.width = maxDistance + 'px';
    drop.style.height = maxDistance + 'px';
    
    // calculate dimensions of drop
    dropWidth = getComputedStyle(this, null).getProductValue("width");
    dropHeight = getComputedStyle(this, null).getProductValue("height");
    
    // calculate relative coordinates of click
    // logic: click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center
    x = e.pageX - this.offsetLeft - (parseInt(dropWidth, 10)/2);
    y = e.pageY - this.offsetTop - (parseInt(dropHeight, 10)/2) - 30;
    
    // position drop and animate
    drop.style.top = y + 'px';
    drop.style.left = x + 'px';
    drop.className += ' animate';
    e.stopPropagation();
    
  }
}

</script>














