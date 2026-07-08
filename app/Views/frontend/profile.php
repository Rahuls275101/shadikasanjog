<section>
    <div class="all-pro-head">
        <div class="container">
            <div class="row">
                <h1>Profiles</h1>
            </div>
        </div>
    </div>

    <!--FILTER ON MOBILE VIEW-->
    <div class="fil-mob fil-mob-act">
        <h4>Profile filters <i class="fa fa-filter" aria-hidden="true"></i></h4>
    </div>
</section>
<style>
.pro-detail h4 a {
  color: rgb(33, 37, 41);
  font-weight: 600;
  font-size: 27px;
  font-family: "Noto Sans", sans-serif;
}


.verified-badge {
font-size: 12px;
  padding: 3px 8px;
  border-radius: 12px;
  margin-left: 8px;
  color: #fff;
  font-weight: 600;

  background-color: #1e73be;

}

@media screen and (max-width: 769px) {
    .fil-mob-view {
        display: block !important;
    }
}

</style>
<section>
    <div class="all-weddpro all-jobs all-serexp chosenini">
        <form action="#">
            <div class="container">
                <div class="row mt-4 mt-xl-5">
                    <!--quicksearch-->
                    <div class="col-12 col-xl-4 pt-4 box-left fil-mob-view">
                        <span class="filter-clo">×</span>
                        
                        <div class="accordion main-according" id="accordionExample">
                            <!--accordingone-->
                            <div class="accordion-item main-according-g">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Search By Profile Number
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="inputone">
                                                    <div class="col-12 col-xl-12 input-box-left">
                                                        <input type="text" id="search_input" class="form-control" placeholder="Search By Profile Number"
                                                            aria-label="Username" aria-describedby="basic-addon1" />
                                                    </div>
                                                    <div class="col-12 col-xl-5 m-auto mt-3 mt-xl-2 ">
                                                        <button type="button" id="btnSearch" class="btn bt-search-left"><i class="fa fa-search"
                                                                aria-hidden="true"></i> Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--accordingone-->
                            
                            
                            <?php if($userdata && $userdata->membership_status == 'Active') { ?>
                            
                             <?php }  ?>
                             
                             
                                    <div class="accordion-item main-according-g">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Quick Search
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="quickone">
                                                    <select class="form-select chosen-select" aria-label="Default select example" name="age">
                                                        <option value="">Select Age</option>
                                                        <option value="18-30">Up to 25</option>
                                                        <option value="31-40">26 to 30</option>
                                                        <option value="41-50">31 to 35</option>
                                                        <option value="51-60">36 to 40 </option>
                                                        <option value="51-60">41 to 45 </option>
                                                        <option value="51-60">46 to 50 </option>
                                                        <option value="51-60">51 to 55 </option>
                                                        <option value="51-60">56 to 60 </option>
                                                        <option value="51-60">61 and Above </option>
                                                    </select>
                                                    
                                                    <select class="form-select mt-2" aria-label="Default select example" name="height">
                                                        <option value="">Select Height</option>
                                                        <option value="4.0-4.5">4'0" - 4'5"</option>
                                                        <option value="4.6-5.0">4'6" - 5'0"</option>
                                                        <option value="5.1-5.5">5'1" - 5'5"</option>
                                                        <option value="5.6-6.0">5'6" - 6'0"</option>
                                                        <option value="6.1+">6'1"+</option>
                                                    </select>

                                                    <select class="form-select mt-2" id="religionSelect" name="religion">
                                                        <option value="" selected>Select Religion</option>
                              <option value="">Any</option>
                                              <?php foreach($religions as $r){ ?>
          <option value="<?= $r->id ?>" ><?= $r->name ?></option>
        <?php } ?>
                                                    </select>
                                             <!-- Input for Other Religion -->
                                                    <input type="text" class="form-control mt-2" id="otherReligion"
                                                        placeholder="Please specify" style="display:none;">

                                                    <select class="form-select mt-2" id="casteSelect" name="caste">
                                                        <option value="" selected>Select Caste</option>
                                                        <option value="">Any</option>
                              <option value="Brahmin">Brahmin</option>
                              <option value="Kshatriya">Kshatriya</option>
                              <option value="Vaishya">Vaishya</option>
                              <option value="Other">Other</option>
                                                       
                                                    </select>

                                                    <!-- Input for Other Caste -->
                                                    <input type="text" class="form-control mt-2" id="otherCaste" placeholder="Please specify"
                                                        style="display:none;">
                                                </div>
                                                <div class="col-12 col-xl-5 m-auto mt-3 mt-xl-2 ">
                                                    <button type="button" class="btn bt-search-left"><i class="fa fa-search"
                                                            aria-hidden="true"></i> Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--accordingtwo-->
                          <div class="accordion-item main-according-g">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed " id="btnAdvancedSearch" class="" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Advanced Search
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="advans-search">
                                                    <div class="row mt-2 mt-xl-3">
                                                        <!-- Age -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-user" aria-hidden="true"></i></span>
                                                                Age
                                                            </h4>
                                                            
                                                            <select class="chosen-select testselect4" name="age_adv">
                                                                <option value="">Select Age</option>
                                                        <option value="18-30">Up to 25</option>
                                                        <option value="31-40">26 to 30</option>
                                                        <option value="41-50">31 to 35</option>
                                                        <option value="51-60">36 to 40 </option>
                                                        <option value="51-60">41 to 45 </option>
                                                        <option value="51-60">46 to 50 </option>
                                                        <option value="51-60">51 to 55 </option>
                                                        <option value="51-60">56 to 60 </option>
                                                        <option value="51-60">61 and Above </option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Height -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-user" aria-hidden="true"></i></span>
                                                                Height
                                                            </h4>
                                                            <select class="form-select chosen-select testselect4" name="height_adv">
                                                                <option value="">Select Height</option>
                                                                <option value="4.0-4.5">4'0" - 4'5"</option>
                                                                <option value="4.6-5.0">4'6" - 5'0"</option>
                                                                <option value="5.1-5.5">5'1" - 5'5"</option>
                                                                <option value="5.6-6.0">5'6" - 6'0"</option>
                                                                <option value="6.1+">6'1"+</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Badge -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-certificate" aria-hidden="true"></i></span>
                                                                Badge
                                                            </h4>
                                                            <div class="form-group">
                                                                <ul class="badge-list">
                                                                    <li><input type="checkbox" id="badgeAll" value="All" checked><label
                                                                            for="badgeAll">All</label></li>
                                                                    <li><input type="checkbox" id="badgeGreen" value="Green"><label
                                                                            for="badgeGreen">Green</label></li>
                                                                    <li><input type="checkbox" id="badgeOrange" value="Orange"><label
                                                                            for="badgeOrange">Orange</label></li>
                                                                    <li><input type="checkbox" id="badgeBlue" value="Blue"><label
                                                                            for="badgeBlue">Verified</label></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Marital Status -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-heart" aria-hidden="true"></i></span>
                                                                Marital Status
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="marital_status">
                                                                <option value="">Select</option>
                                                                <option value="">Any</option>
                                                                <option value="never_married">Never Married</option>
                                                                <option value="divorced">Divorced</option>
                                                                <option value="annulled">Marriage Annulled</option>
                                                                <option value="widow">Widow</option>
                                                                <option value="widower">Widower</option>
                                                                <option value="">Any</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Religion -->
                                                        <div class="col-12 col-xl-12 select-input-adv">
                                                            <h4 class="selct-txt">
                                                                <span><i class="fa fa-bell-o" aria-hidden="true"></i></span>
                                                                Religion
                                                            </h4>
                                                            <select id="religionSelectAdv" class="chosen-select testselect4"  name="religion_adv">
                                                                 <option value="">Select</option>
                                                                 <option value="">Any</option>
                                                                 <?php foreach($religions as $r){ ?>
          <option value="<?= $r->id ?>" ><?= $r->name ?></option>
        <?php } ?>
                                                            </select>
                                                        </div>
                                                        
                                                        

                                                        
                                                        <!-- Caste -->
                                                       <div class="col-12 col-xl-12 select-input-adv">
    <h4 class="selct-txt">
        <span><i class="fa fa-users" aria-hidden="true"></i></span>
        Caste
    </h4>

    <select class="chosen-select " id="casteSelectAdv" name="caste_adv">
        <option value="">Select Caste</option>
        <option value="">Any</option>
                                  <option value="Brahmin">Brahmin</option>
                                  <option value="Kshatriya">Kshatriya</option>
                                  <option value="Vaishya">Vaishya</option>
                                  <option value="Other">Other</option>
    </select>
</div>
                                                        
                                                        <!-- Educational Qualifications -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-graduation-cap"
                                                                        aria-hidden="true"></i></span>
                                                                Educational Qualifications
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="qualification">
                                                                <option value="">Select Qualification</option>
                                                                
                                                                
                                                                 <?php foreach($education_qualifications as $education) { ?>
                <option value="<?= $education->id; ?>"><?= $education->qualification_name; ?></option>
              <?php } ?>
                                                               
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Mangal Status -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-star" aria-hidden="true"></i></span>
                                                                Mangal Status
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="mangal_status">
                                                                <option value="">Select</option>
                                                                <option value="Non Manglik">Non Manglik</option>
                                                                <option value="manglik">Manglik</option>
                                                                <option value="anshik">Anshik</option>
                                                                <option value="non_believer">Non-Believer</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Profession -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                                                                Profession
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="profession">
                                                                <option value="">Select Profession</option>
                                                                <option value="">Any</option>
                                                                 <?php foreach($profession_categories as $p){ ?>
          <option value="<?= $p->id ?>" ><?= $p->category_name ?></option>
        <?php } ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Living in -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-home" aria-hidden="true"></i></span>
                                                                Living in
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="living_in">
                                                                <option value="">Select Living in</option>
                                                                <option value="">Any Where</option>
                                                                <option value="india">India</option>
                                                                <option value="overseas">Overseas</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Income / CTC -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-money" aria-hidden="true"></i></span>
                                                                Income / CTC
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="income">
                                                                <option value="">Select Income</option>
                                                               <?php
          $incomes=[
            'Below 10 LPA','10 - 20 LPA','20 - 30 LPA',
            '30 - 40 LPA','40 - 50 LPA','50 - 75 LPA','75 - 99 LPA','1 Cr PA and above'
          ];
          foreach($incomes as $i){
          ?>
          <option value="<?= $i ?>">
          
            <?= $i ?>
          </option>
          <?php } ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Dietary Preference -->
                                                        <div class="col-12 col-xl-12 select-input-adv ">
                                                            <h4 class="selct-txt"><span><i class="fa fa-cutlery" aria-hidden="true"></i></span>
                                                                Dietary Preference
                                                            </h4>
                                                            <select class="chosen-select testselect4" name="dietary_preference">
                                                                <option value="">Select  Dietary Preference</option>
                                                               <?php
          $diets=['Veg','Non Veg','Vegan','Doesn’t Matter'];
          foreach($diets as $d){
          ?>
          <option value="<?= $d ?>"><?= $d ?></option>
          <?php } ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- Show Profiles -->
                                                        <div class="col-12 col-xl-12 select-input-adv mt-xl-2">
                                                            <h4 class="selct-txt">
                                                                <span><i class="fa fa-shield" aria-hidden="true"></i></span>
                                                                Show Profiles
                                                            </h4>
                                                            <div class="form-group">
                                                                <ul class="badge-list">
                                                                    <li>
                                                                        <input type="checkbox" id="showAll" value="All" checked>
                                                                        <label for="showAll">All</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="checkbox" id="showWithPhotos" value="WithPhotos">
                                                                        <label for="showWithPhotos">With Photos</label>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-xl-5 m-auto mt-3 mt-xl-2 ">
                                                <button type="button" class="btn bt-search-left"><i class="fa fa-search"
                                                        aria-hidden="true"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--accordingtwo-->
                            
                           
                          
                     
                            
                            <!--accordingthree-->
                         
                            <!--accordingthree-->
                        </div>
                    </div>
                    <!--quicksearch-->
                    
                    <div class="col-12 col-xl-8">
                        <div class="short-all">
                            <div class="short-lhs">
                                Show Profile
                            </div>
                            <div class="short-rhs">
                                <ul>
                                    <li>Sort by:</li>
                                    <li>
                                        <div class="form-group date">
                                            <select class="chosen-select" id="sortSelect">
                                                <option value="date_newest">Date posted: Newest</option>
                                                <option value="date_oldest">Date posted: Oldest</option>
                                                <option value="age_youngest">Age: Youngest</option>
                                                <option value="age_oldest">Age: Oldest</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="all-list-sh">
                            <!-- PROFILE LIST -->
                            <ul id="profile-list" class="ajax_list">
                                <!-- Profiles will be loaded via AJAX -->
                            </ul>
                            
                            <!-- Pagination -->
                            <br>
                            
                             <div id="pagination_link" class="mt-4"></div>
                        </div>
                        
                    </div>
                   
                </div>
            </div>
        </form>
    </div>
</section>

<!-- CONTACT DETAILS MODAL -->
<div class="modal fade" id="contactModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Contact Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center" id="contactModalBody">
        Loading...
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/frontend/assets/js/jquery.min.js'); ?>"></script>

<style>
/* Accordion Header */
#collapseTwo {
    background: #f9f9f9;
}

/* Main Box */
.quickone {
    border: 2px solid #e91e63;
    border-radius: 12px;
    padding: 20px;
    background: #fff;
}

/* Select Fields */
.quickone .form-select {
    width: 100%;
    height: 48px;
    border: 1px solid #ccc;
    padding: 8px 12px;
    font-size: 14px;
    background-color: #ffffff;
    transition: all 0.3s ease;
}

/* Hover & Focus Effect */
.quickone .form-select:focus {
    border-color: #e91e63;
    box-shadow: none;
    background-color: #fff;
}

/* Spacing between fields */
.quickone .form-select + .form-select {
    margin-top: 12px;
}

/* Input fields (Other Religion / Caste) */
.quickone input.form-control {
    height: 45px;
    border-radius: 8px;
    margin-top: 12px;
    border: 1px solid #ccc;
}

/* Search Button */
.bt-search-left {
    width: 100%;
    background: #e91e63;
    color: #fff;
    border-radius: 8px;
    padding: 10px;
    border: none;
    font-weight: 500;
    transition: 0.3s;
}

.bt-search-left:hover {
    background: #c2185b;
}

/* Center Button Properly */
.col-12.col-xl-5 {
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .quickone {
        padding: 15px;
    }

    .quickone .form-select {
        height: 44px;
        font-size: 13px;
    }

    .bt-search-left {
        padding: 8px;
        font-size: 14px;
    }
}
</style>


<script>

$(document).ready(function () {

    // CONTACT DETAILS CLICK
    $(document).on('click', '.viewContactBtn', function () {

        let userId = $(this).data('user-id');

        // 🔒 Check Membership First
        if (membershipStatus !== 'Active') {

            Swal.fire({
                icon: 'warning',
                title: 'Membership Required',
                text: 'Please purchase a membership plan to view contact details.',
                confirmButtonText: 'View Plans',
                confirmButtonColor: '#F37254'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('plans'); ?>";
                }
            });

            return false;
        }

        // ✅ Membership Active → AJAX Call
        $.ajax({
            url: "<?= base_url('profile/getContactDetails'); ?>",
            type: "POST",
            dataType: "json",
            data: { user_id: userId },

            beforeSend: function () {
                $('#contactModalBody').html("Loading...");
                $('#contactModal').modal('show');
            },

            success: function (response) {

                if (response.status) {

                    $('#contactModalBody').html(`
                        <div style="font-size:16px;">
                            <p><strong>Mobile:</strong> ${response.mobile}</p>
                            <p><strong>Email:</strong> ${response.email}</p>
                        </div>
                    `);

                } else {

                    $('#contactModalBody').html(`
                        <p style="color:red;">${response.message}</p>
                    `);
                }
            },

            error: function () {
                $('#contactModalBody').html(`
                    <p style="color:red;">Error loading contact details.</p>
                `);
            }

        });

    });

});
$(document).ready(function () {
    
                                                                // Religion to Caste dynamic dropdown
                                                                
                                                                
                                                                $('#religionSelect').on('change', function () {
    
  

    var religion_id = $(this).val();

    if (!religion_id) {
        $('#casteSelectAdv').html('<option value="">Select Caste</option>');
        return;
    }

    $.ajax({
        url: "<?php echo base_url('get-castes'); ?>",
        type: "POST",
        data: { religion_id: religion_id },
        dataType: "json",
        success: function (response) {

            var html = '<option value="">Select Caste</option>';

            $.each(response, function (i, item) {
                html += '<option value="' + item.id + '">' + item.name + '</option>';
            });

            $('#casteSelect').html(html);
        }
    });

});


$('#religionSelectAdv').on('change', function () {
    
  

    var religion_id = $(this).val();

    if (!religion_id) {
        $('#casteSelectAdv').html('<option value="">Select Caste</option>');
        return;
    }

    $.ajax({
        url: "<?php echo base_url('get-castes'); ?>",
        type: "POST",
        data: { religion_id: religion_id },
        dataType: "json",
        success: function (response) {

            var html = '<option value="">Select Caste</option>';

            $.each(response, function (i, item) {
                html += '<option value="' + item.id + '">' + item.name + '</option>';
            });

            $('#casteSelectAdv').html(html);
        }
    });

});
    // Send Interest Button Click Handler
    $(document).on('click', '.btn-interest, .send-interest', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var receiverId = $btn.data('userid') || $btn.attr('data-userid');
        var profileName = $btn.data('name') || 'this profile';
        
        // Check if user is logged in
        <?php if(!session()->get('loggedin')): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Login Required',
                text: 'Please login to send interest',
                confirmButtonText: 'Login Now',
                confirmButtonColor: '#F37254'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('login') ?>";
                }
            });
            return false;
        <?php endif; ?>
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Send Interest?',
            text: 'Do you want to send interest to ' + profileName + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send it!'
        }).then((result) => {
            if (result.isConfirmed) {
                sendInterest(receiverId, $btn);
            }
        });
    });

    // Function to send interest via AJAX
    function sendInterest(receiverId, $btn) {
        // Show loading on button
        var originalText = $btn.text();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Sending...').prop('disabled', true);
        
        $.ajax({
            url: "<?php echo base_url('interest/send'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                receiver_id: receiverId,
                <?= csrf_token() ?>: "<?= csrf_hash() ?>"
            },
            success: function(response) {
                if (response.status === true) {
                    Swal.fire({
                        icon:  response.status ,
                        title: ' Success!',
                        text: response.message || 'Interest sent successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Change button state
                    $btn.removeClass('btn-interest').addClass('btn-interest-sent').html('<i class="fa fa-check"></i> Interest Sent').prop('disabled', true);
                    
                } else {
                    Swal.fire({
                        icon: response.status,
                        title: '',
                        text: response.message || 'Failed to send interest. Please try again.'
                    });
                    
                    // Reset button
                    $btn.html(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
                console.log('Response:', xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.'
                });
                
                // Reset button
                $btn.html(originalText).prop('disabled', false);
            }
        });
    }

    // Check interest status on page load
    function checkInterestStatus() {
        $('.btn-interest').each(function() {
            var $btn = $(this);
            var receiverId = $btn.data('userid');
            
            $.ajax({
                url: "<?= base_url('check-interest-status') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    receiver_id: receiverId,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                success: function(response) {
                    if (response.status === 'sent') {
                        $btn.removeClass('btn-interest').addClass('btn-interest-sent').html('<i class="fa fa-check"></i> Interest Sent').prop('disabled', true);
                    } else if (response.status === 'pending') {
                        $btn.removeClass('btn-interest').addClass('btn-interest-pending').html('<i class="fa fa-clock-o"></i> Pending').prop('disabled', true);
                    } else if (response.status === 'accepted') {
                        $btn.removeClass('btn-interest').addClass('btn-interest-accepted').html('<i class="fa fa-heart"></i> Accepted').prop('disabled', true);
                    }
                }
            });
        });
    }
    
    // Call checkInterestStatus on page load
    <?php if(session()->get('loggedin')): ?>
        checkInterestStatus();
    <?php endif; ?>
});
</script>
<script>
var membershipStatus = "<?= isset($userdata->membership_status) ? $userdata->membership_status : 'Inactive'; ?>";

$(document).ready(function () {
    // Initialize Chosen
    if ($.fn.chosen) {
        $(".chosen-select").chosen({ width: "100%" });
    }

    // Helper function to get select values safely
    function getVal(selector) {
        var $el = $(selector);
        return $el.length ? $el.val() : '';
    }

    // Show membership alert
    function showMembershipAlert() {
        Swal.fire({
            icon: 'warning',
            title: 'Membership Required',
            text: 'To use this feature you are requested to become a paid member. Visit Manage Plan for the necessary requirement',
            confirmButtonText: 'View Plans',
            confirmButtonColor: '#F37254'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('plans') ?>";
            }
        });
    }

    // Main AJAX function
    function ajax_list(page) {
        page = page || 1;

        // Check if advanced search is open and membership is not active
        if ($('#collapseThree').hasClass('show') && membershipStatus !== 'Active') {
            showMembershipAlert();
            return false;
        }

        // Get badge filter
        var badgeFilter = [];
        if ($('#badgeAll').is(':checked')) {
            badgeFilter = ['All'];
        } else {
            if ($('#badgeGreen').is(':checked')) badgeFilter.push('Green');
            if ($('#badgeOrange').is(':checked')) badgeFilter.push('Orange');
            if ($('#badgeBlue').is(':checked')) badgeFilter.push('Blue');
            if (badgeFilter.length === 0) badgeFilter = ['All'];
        }

        // Get show filter
        var showFilter = $('#showWithPhotos').is(':checked') ? 'WithPhotos' : 'All';

        // Determine which search to use (Quick or Advanced)
        var isAdvancedOpen = $('#collapseThree').hasClass('show');
        
    var filters = {
    search: $('#search_input').val() || '',
    
    age_quick: getVal('select[name="age"]'),
    height_quick: getVal('select[name="height"]'),
    religion_quick: getVal('select[name="religion"]'),
    caste_quick: getVal('select[name="caste"]'),

    age_adv: getVal('select[name="age_adv"]'),
    height_adv: getVal('select[name="height_adv"]'),
    religion_adv: getVal('select[name="religion_adv"]'),
    caste_adv: getVal('select[name="caste_adv"]'),

    is_advanced: $('#collapseThree').hasClass('show') ? 1 : 0,

    marital_status: getVal('select[name="marital_status"]'),
    qualification: getVal('select[name="qualification"]'),
    mangal_status: getVal('select[name="mangal_status"]'),
    profession: getVal('select[name="profession"]'),
    living_in: getVal('select[name="living_in"]'),
    income: getVal('select[name="income"]'),
    dietary_preference: getVal('select[name="dietary_preference"]'),

    badge: badgeFilter,
    show: showFilter,
    sort: $('#sortSelect').val()
};

        // Show loading
        $('#profile-list').html('<li class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading profiles...</p></li>');

        $.ajax({
            url: "<?= base_url('ajax_list_profile') ?>/" + page,
            type: "POST",
            dataType: "json",
            data: filters,
            success: function(response) {
                if (response && response.product_list) {
                    $('#profile-list').html(response.product_list);
                    
                    if (response.pagination_link) {
                        $('#pagination_link').html(response.pagination_link);
                    } else {
                        $('#pagination_link').html('');
                    }
                    
                    // Update header text
                    if (response.headoutput) {
                        $('.short-lhs').text(response.headoutput);
                    }
                } else {
                    $('#profile-list').html('<li class="text-center py-5"><p>No profiles found.</p></li>');
                    $('#pagination_link').html('');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
                console.log('Response:', xhr.responseText);
                $('#profile-list').html('<li class="text-center py-5 text-danger"><p>Error loading profiles. Please try again.</p></li>');
                $('#pagination_link').html('');
            }
        });
    }

    // Block Advanced Search opening for non-members
$('#collapseThree').on('show.bs.collapse', function(e) {
    if (membershipStatus !== 'Active') {
        e.preventDefault();
        e.stopPropagation();
        showMembershipAlert();
        return false;
    }

    setTimeout(function() {
        ajax_list(1);
    }, 500); // 5000ms = 5 seconds
});


$('#collapseTwo').on('show.bs.collapse', function(e) {

    setTimeout(function() {
        ajax_list(1);
    }, 500); // 5000ms = 5 seconds
});
    // Search button handlers - Quick Search
    $(document).on('click', '.bt-search-left', function(e) {
        e.preventDefault();
        // Check which section this button belongs to
        var $parent = $(this).closest('.accordion-body');
        if ($parent.length && $parent.closest('#collapseThree').length) {
            // This is advanced search button
            if (membershipStatus !== 'Active') {
                showMembershipAlert();
                return false;
            }
        }
        ajax_list(1);
    });

    // Search by profile number
    $('#btnSearch').on('click', function(e) {
        e.preventDefault();
        ajax_list(1);
    });

    $('#search_input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            ajax_list(1);
        }
    });

    // Sort change
    $('#sortSelect').on('change', function() {
        ajax_list(1);
    });

    // Quick Search field changes
    $(document).on('change', 'select[name="age"], select[name="height"], select[name="religion"], select[name="caste"]', function() {
        // Only trigger if quick search is visible and advanced is closed
        if (!$('#collapseThree').hasClass('show')) {
            ajax_list(1);
        }
    });

    // Advanced Search field changes
    $(document).on('change', 'select[name="age_adv"], select[name="height_adv"], select[name="religion_adv"], select[name="caste_adv"], select[name="marital_status"], select[name="qualification"], select[name="mangal_status"], select[name="profession"], select[name="living_in"], select[name="income"], select[name="dietary_preference"]', function() {
        if ($('#collapseThree').hasClass('show')) {
            ajax_list(1);
        }
    });

    // Badge checkbox changes
    $('#badgeAll, #badgeGreen, #badgeOrange, #badgeBlue').on('change', function() {
        var $this = $(this);
        if ($this.val() === 'All' && $this.is(':checked')) {
            $('#badgeGreen, #badgeOrange, #badgeBlue').prop('checked', false);
        } else {
            $('#badgeAll').prop('checked', false);
        }
        ajax_list(1);
    });

    // Show photos checkbox
    $('#showWithPhotos, #showAll').on('change', function() {
        if ($(this).attr('id') === 'showWithPhotos' && $(this).is(':checked')) {
            $('#showAll').prop('checked', false);
        } else if ($(this).attr('id') === 'showAll' && $(this).is(':checked')) {
            $('#showWithPhotos').prop('checked', false);
        }
        ajax_list(1);
    });

    // Pagination click
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var page = $(this).attr('data-ci-pagination-page');
        if (page) {
            ajax_list(parseInt(page));
        } else {
            // Try to get page from href
            var href = $(this).attr('href');
            if (href) {
                var match = href.match(/\/ajax_list_profile\/(\d+)/);
                if (match && match[1]) {
                    ajax_list(parseInt(match[1]));
                }
            }
        }
    });

    // Mobile filter toggle
    $('.fil-mob-act').on('click', function() {
        $('.fil-mob-view').slideToggle();
    });

    $('.filter-clo').on('click', function() {
        $('.fil-mob-view').slideUp();
    });

    // Load initial data
    ajax_list(1);
});
</script>


