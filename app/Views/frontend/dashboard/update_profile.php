<!-- Tom Select CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
.ts-control {
    border: 1px solid #ced4da !important;
    padding: 0.375rem 0.75rem !important;
}

.ts-wrapper.multi .ts-control > div {
    background-color: #0d6efd !important;
    color: white !important;
}
</style>

<section>
    <div class="login pro-edit-update">
        <div class="container">
            <div class="row">
                <div class="inn">
                    <div class="rhs">
                        <div class="form-login">
                            <form id="profileEditForm" method="post" enctype="multipart/form-data" novalidate>
                                <!-- BASIC INFO -->
                                <div class="edit-pro-parti">
                                    <div class="form-tit">
                                        <h4>Basic Info</h4>
                                        <h1>Edit my profile</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="first_name">First Name:</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $userdata->user_name ?? ''; ?>" placeholder="Enter your first name" required>
                                            <div class="invalid-feedback">Please provide your first name.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="last_name">Last Name:</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $userdata->user_last_name ?? ''; ?>" placeholder="Enter your last name" required>
                                            <div class="invalid-feedback">Please provide your last name.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="marital_status">Marital Status:</label>
                                            <select class="form-select chosen-select" id="marital_status" name="marital_status" required>
                                                <option value="">Select Marital Status</option>
                                                <option value="Never married" <?php echo ($userdata->marital_status ?? '') == 'Never married' ? 'selected' : ''; ?>>Never married</option>
                                                <option value="Divorced" <?php echo ($userdata->marital_status ?? '') == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                                                <option value="Widow" <?php echo ($userdata->marital_status ?? '') == 'Widow' ? 'selected' : ''; ?>>Widow</option>
                                                <option value="Widower" <?php echo ($userdata->marital_status ?? '') == 'Widower' ? 'selected' : ''; ?>>Widower</option>
                                                <option value="Awaiting Divorce Finalisation" <?php echo ($userdata->marital_status ?? '') == 'Awaiting Divorce Finalisation' ? 'selected' : ''; ?>>Awaiting Divorce Finalisation</option>
                                                <option value="Marriage Annulled" <?php echo ($userdata->marital_status ?? '') == 'Marriage Annulled' ? 'selected' : ''; ?>>Marriage Annulled</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your marital status.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="height">Height:</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="height" name="height" value="<?php echo $userdata->height ?? ''; ?>" placeholder="Enter your height" min="100" max="250" step="0.1">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid height between 100-250 cm.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="dob">Date of Birth:</label>
                                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $userdata->date_of_birth ?? ''; ?>" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
                                            <div class="invalid-feedback">Please select a valid date of birth (must be 18+).</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="tob">Time of Birth:</label>
                                            <input type="time" class="form-control" id="tob" name="tob" value="<?php echo $userdata->time_of_birth ?? ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="pob">Place of Birth:</label>
                                            <input type="text" class="form-control" id="pob" name="pob" value="<?php echo $userdata->place_of_birth ?? ''; ?>" placeholder="Enter your place of birth">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="religions">Religion:</label>
                                            <select class="form-select chosen-select" id="religions" name="religions" required>
                                                <option value="">Select Religion</option>
                                                <?php foreach($religions as $religionsrow) { ?>
                                                    <option value="<?php echo $religionsrow->id; ?>" <?php echo ($userdata->religion_id ?? '') == $religionsrow->id ? 'selected' : ''; ?>>
                                                        <?php echo $religionsrow->name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-feedback">Please select your religion.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="castes">Caste:</label>
                                            <select class="form-select" id="castes" name="castes" required>
                                                <option value="">Select Caste</option>
                                                <?php if(isset($castes) && !empty($castes)): ?>
                                                    <?php foreach($castes as $caste): ?>
                                                        <option value="<?php echo $caste->id; ?>" <?php echo ($userdata->caste_id ?? '') == $caste->id ? 'selected' : ''; ?>>
                                                            <?php echo $caste->name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <div class="invalid-feedback">Please select your caste.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="mother_tongue">Mother Tongue:</label>
                                            <select class="form-select" id="mother_tongue" name="mother_tongue" required>
                                                <option value="">Select Mother Tongue</option>
                                                <option value="hindi" <?php echo ($userdata->mother_tongue ?? '') == 'hindi' ? 'selected' : ''; ?>>Hindi</option>
                                                <option value="bengali" <?php echo ($userdata->mother_tongue ?? '') == 'bengali' ? 'selected' : ''; ?>>Bengali</option>
                                                <option value="telugu" <?php echo ($userdata->mother_tongue ?? '') == 'telugu' ? 'selected' : ''; ?>>Telugu</option>
                                                <option value="marathi" <?php echo ($userdata->mother_tongue ?? '') == 'marathi' ? 'selected' : ''; ?>>Marathi</option>
                                                <option value="tamil" <?php echo ($userdata->mother_tongue ?? '') == 'tamil' ? 'selected' : ''; ?>>Tamil</option>
                                                <option value="urdu" <?php echo ($userdata->mother_tongue ?? '') == 'urdu' ? 'selected' : ''; ?>>Urdu</option>
                                                <option value="gujarati" <?php echo ($userdata->mother_tongue ?? '') == 'gujarati' ? 'selected' : ''; ?>>Gujarati</option>
                                                <option value="kannada" <?php echo ($userdata->mother_tongue ?? '') == 'kannada' ? 'selected' : ''; ?>>Kannada</option>
                                                <option value="malayalam" <?php echo ($userdata->mother_tongue ?? '') == 'malayalam' ? 'selected' : ''; ?>>Malayalam</option>
                                                <option value="odia" <?php echo ($userdata->mother_tongue ?? '') == 'odia' ? 'selected' : ''; ?>>Odia</option>
                                                <option value="punjabi" <?php echo ($userdata->mother_tongue ?? '') == 'punjabi' ? 'selected' : ''; ?>>Punjabi</option>
                                                <option value="assamese" <?php echo ($userdata->mother_tongue ?? '') == 'assamese' ? 'selected' : ''; ?>>Assamese</option>
                                                <option value="maithili" <?php echo ($userdata->mother_tongue ?? '') == 'maithili' ? 'selected' : ''; ?>>Maithili</option>
                                                <option value="santali" <?php echo ($userdata->mother_tongue ?? '') == 'santali' ? 'selected' : ''; ?>>Santali</option>
                                                <option value="kashmiri" <?php echo ($userdata->mother_tongue ?? '') == 'kashmiri' ? 'selected' : ''; ?>>Kashmiri</option>
                                                <option value="nepali" <?php echo ($userdata->mother_tongue ?? '') == 'nepali' ? 'selected' : ''; ?>>Nepali</option>
                                                <option value="sindhi" <?php echo ($userdata->mother_tongue ?? '') == 'sindhi' ? 'selected' : ''; ?>>Sindhi</option>
                                                <option value="konkani" <?php echo ($userdata->mother_tongue ?? '') == 'konkani' ? 'selected' : ''; ?>>Konkani</option>
                                                <option value="dogri" <?php echo ($userdata->mother_tongue ?? '') == 'dogri' ? 'selected' : ''; ?>>Dogri</option>
                                                <option value="manipuri" <?php echo ($userdata->mother_tongue ?? '') == 'manipuri' ? 'selected' : ''; ?>>Manipuri</option>
                                                <option value="bodo" <?php echo ($userdata->mother_tongue ?? '') == 'bodo' ? 'selected' : ''; ?>>Bodo</option>
                                                <option value="sanskrit" <?php echo ($userdata->mother_tongue ?? '') == 'sanskrit' ? 'selected' : ''; ?>>Sanskrit</option>
                                                <option value="english" <?php echo ($userdata->mother_tongue ?? '') == 'english' ? 'selected' : ''; ?>>English</option>
                                                <option value="other" <?php echo ($userdata->mother_tongue ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your mother tongue.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="other_languages">Other Languages Spoken:</label>
                                            <input type="text" class="form-control" id="other_languages" name="other_languages" value="<?php echo $userdata->other_languages ?? ''; ?>" placeholder="Enter other languages">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="dietary_preference">Dietary Preference:</label>
                                            <select class="form-select" id="dietary_preference" name="dietary_preference" required>
                                                <option value="">Select</option>
                                                <option value="Veg" <?php echo ($userdata->dietary_preference ?? '') == 'Veg' ? 'selected' : ''; ?>>Veg</option>
                                                <option value="Non Veg" <?php echo ($userdata->dietary_preference ?? '') == 'Non Veg' ? 'selected' : ''; ?>>Non Veg</option>
                                                <option value="Vegan" <?php echo ($userdata->dietary_preference ?? '') == 'Vegan' ? 'selected' : ''; ?>>Vegan</option>
                                                <option value="Doesn't Matter" <?php echo ($userdata->dietary_preference ?? '') == 'Doesn\'t Matter' ? 'selected' : ''; ?>>Doesn't Matter</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your dietary preference.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="family_home">Family Home:</label>
                                            <input type="text" class="form-control" id="family_home" name="family_home" value="<?php echo $userdata->family_home ?? ''; ?>" placeholder="Enter family home">
                                        </div>
                                        
                                        
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="current_residence">Current Place of Residence:</label>
                                            <input type="text" class="form-control" id="current_residence" name="current_residence" value="<?php echo $userdata->current_residence ?? ''; ?>" placeholder="Enter current residence">
                                        </div>
      <!-- HOBBIES MULTISELECT -->
                             <!-- Hobbies Select -->
                        <?php 
$hobbies = isset($userdata->hobbies) ? explode(',', $userdata->hobbies) : [];
$hobbies = array_map('trim', $hobbies);
?>    
                             
<div class="col-md-6 form-group">
    <label class="lb" for="hobbies">Hobbies:</label>
    <select id="hobbies" name="hobbies[]" multiple placeholder="Choose your hobbies...">
        <!-- Remove the value attribute from select tag -->
        <option value="traditional" <?php echo in_array('traditional', $hobbies) ? 'selected' : ''; ?>>Traditional</option>
        <option value="modern" <?php echo in_array('modern', $hobbies) ? 'selected' : ''; ?>>Modern</option>
        <option value="religious" <?php echo in_array('religious', $hobbies) ? 'selected' : ''; ?>>Religious</option>
        <option value="liberal" <?php echo in_array('liberal', $hobbies) ? 'selected' : ''; ?>>Liberal</option>
        <option value="conservative" <?php echo in_array('conservative', $hobbies) ? 'selected' : ''; ?>>Conservative</option>
        <option value="progressive" <?php echo in_array('progressive', $hobbies) ? 'selected' : ''; ?>>Progressive</option>
        <option value="reading" <?php echo in_array('reading', $hobbies) ? 'selected' : ''; ?>>Reading</option>
        <option value="traveling" <?php echo in_array('traveling', $hobbies) ? 'selected' : ''; ?>>Traveling</option>
        <option value="cooking" <?php echo in_array('cooking', $hobbies) ? 'selected' : ''; ?>>Cooking</option>
        <option value="music" <?php echo in_array('music', $hobbies) ? 'selected' : ''; ?>>Music</option>
        <option value="dancing" <?php echo in_array('dancing', $hobbies) ? 'selected' : ''; ?>>Dancing</option>
        <option value="sports" <?php echo in_array('sports', $hobbies) ? 'selected' : ''; ?>>Sports</option>
        <option value="yoga" <?php echo in_array('yoga', $hobbies) ? 'selected' : ''; ?>>Yoga</option>
        <option value="gardening" <?php echo in_array('gardening', $hobbies) ? 'selected' : ''; ?>>Gardening</option>
        <option value="painting" <?php echo in_array('painting', $hobbies) ? 'selected' : ''; ?>>Painting</option>
        <option value="photography" <?php echo in_array('photography', $hobbies) ? 'selected' : ''; ?>>Photography</option>
        <option value="writing" <?php echo in_array('writing', $hobbies) ? 'selected' : ''; ?>>Writing</option>
        <option value="movies" <?php echo in_array('movies', $hobbies) ? 'selected' : ''; ?>>Watching Movies</option>
        <option value="fitness" <?php echo in_array('fitness', $hobbies) ? 'selected' : ''; ?>>Fitness / Gym</option>
        <option value="volunteering" <?php echo in_array('volunteering', $hobbies) ? 'selected' : ''; ?>>Volunteering</option>
        <option value="technology" <?php echo in_array('technology', $hobbies) ? 'selected' : ''; ?>>Technology / Gadgets</option>
        <option value="fashion" <?php echo in_array('fashion', $hobbies) ? 'selected' : ''; ?>>Fashion</option>
        <option value="adventure" <?php echo in_array('adventure', $hobbies) ? 'selected' : ''; ?>>Adventure</option>
        <option value="pets" <?php echo in_array('pets', $hobbies) ? 'selected' : ''; ?>>Pet Care / Animals</option>
        <option value="social_work" <?php echo in_array('social_work', $hobbies) ? 'selected' : ''; ?>>Social Work</option>
    </select>
</div>

                                        
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="manglik_status">Manglik Status:</label>
                                            <select class="form-select" id="manglik_status" name="manglik_status" required>
                                                <option value="">Select</option>
                                                <option value="Manglik" <?php echo ($userdata->manglik_status ?? '') == 'Manglik' ? 'selected' : ''; ?>>Manglik</option>
                                                <option value="Non Manglik" <?php echo ($userdata->manglik_status ?? '') == 'Non Manglik' ? 'selected' : ''; ?>>Non Manglik</option>
                                                <option value="Anshik" <?php echo ($userdata->manglik_status ?? '') == 'Anshik' ? 'selected' : ''; ?>>Anshik</option>
                                                <option value="Non Believer" <?php echo ($userdata->manglik_status ?? '') == 'Non Believer' ? 'selected' : ''; ?>>Non Believer</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your manglik status.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="photos">Photos (Max 5):</label>
                                            <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*" onchange="validateFiles(this)">
                                            <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF. Max file size: 5MB each.</small>
                                            <div class="invalid-feedback">Please select valid image files (max 5).</div>
                                            <!-- Show existing photos -->
                                            <?php if(isset($user_photos) && !empty($user_photos)): ?>
                                                <div class="mt-2">
                                                    <small class="text-success">Existing Photos:</small>
                                                    <?php foreach($user_photos as $photo): ?>
                                                        <img src="<?php echo base_url('assets/uploads/profiles/' . $photo->photo_path); ?>" width="50" height="50" class="rounded me-1" alt="Profile Photo">
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="dp">DP:</label>
                                            <input type="file" class="form-control" id="dp" name="dp" accept="image/*" onchange="validateSingleFile(this)">
                                            <small class="form-text text-muted">Profile picture. Max file size: 5MB.</small>
                                            <div class="invalid-feedback">Please select a valid image file.</div>
                                            <!-- Show current profile picture -->
                                            <?php if(isset($userdata->user_photo) && !empty($userdata->user_photo)): ?>
                                                <div class="mt-2">
                                                    <small class="text-success">Current Profile Picture:</small>
                                                    <img src="<?php echo base_url('assets/uploads/profiles/' . $userdata->user_photo); ?>" width="50" height="50" class="rounded" alt="Profile Picture">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="kundali_matching">Need for Kundali Matching:</label>
                                            <select class="form-select" id="kundali_matching" name="kundali_matching" required>
                                                <option value="">Select</option>
                                                <option value="Must" <?php echo ($userdata->kundali_matching ?? '') == 'Must' ? 'selected' : ''; ?>>Must</option>
                                                <option value="Optional" <?php echo ($userdata->kundali_matching ?? '') == 'Optional' ? 'selected' : ''; ?>>Optional</option>
                                                <option value="Not Required" <?php echo ($userdata->kundali_matching ?? '') == 'Not Required' ? 'selected' : ''; ?>>Not Required</option>
                                                <option value="Non Believer" <?php echo ($userdata->kundali_matching ?? '') == 'Non Believer' ? 'selected' : ''; ?>>Non Believer</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your preference for kundali matching.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- EDUCATION & PROFESSION -->
                                <div class="edit-pro-parti">
                                    <div class="form-tit">
                                        <h4>Education & Profession</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="education">Highest Educational Qualification:</label>
                                            <select class="form-select" id="education" name="education" required>
                                                <option value="">Select Qualification</option>
                                                <!-- School Education -->
                                                <optgroup label="School Education">
                                                    <option value="10th_pass" <?php echo ($education_data->highest_education ?? '') == '10th_pass' ? 'selected' : ''; ?>>10th Pass (Matriculation)</option>
                                                    <option value="12th_pass" <?php echo ($education_data->highest_education ?? '') == '12th_pass' ? 'selected' : ''; ?>>12th Pass (Intermediate)</option>
                                                    <option value="12th_pursuing" <?php echo ($education_data->highest_education ?? '') == '12th_pursuing' ? 'selected' : ''; ?>>12th Pursuing</option>
                                                </optgroup>
                                                
                                                <!-- Diploma Courses -->
                                               <optgroup label="Diploma Courses">
    <option value="Diploma in Engineering" <?php echo ($education_data->highest_education ?? '') == 'Diploma in Engineering' ? 'selected' : ''; ?>>Diploma in Engineering</option>
    <option value="Diploma in Management" <?php echo ($education_data->highest_education ?? '') == 'Diploma in Management' ? 'selected' : ''; ?>>Diploma in Management</option>
    <option value="Diploma in Computer Applications" <?php echo ($education_data->highest_education ?? '') == 'Diploma in Computer Applications' ? 'selected' : ''; ?>>Diploma in Computer Applications</option>
    <option value="Other Diploma" <?php echo ($education_data->highest_education ?? '') == 'Other Diploma' ? 'selected' : ''; ?>>Other Diploma</option>
</optgroup>

                                                
                                                <!-- Undergraduate Degrees -->
                                                <optgroup label="Undergraduate Degrees">
                                                    <option value="btech" <?php echo ($education_data->highest_education ?? '') == 'btech' ? 'selected' : ''; ?>>B.Tech / B.E. (Engineering)</option>
                                                    <option value="bsc" <?php echo ($education_data->highest_education ?? '') == 'bsc' ? 'selected' : ''; ?>>B.Sc (Science)</option>
                                                    <option value="bcom" <?php echo ($education_data->highest_education ?? '') == 'bcom' ? 'selected' : ''; ?>>B.Com (Commerce)</option>
                                                    <option value="ba" <?php echo ($education_data->highest_education ?? '') == 'ba' ? 'selected' : ''; ?>>B.A (Arts)</option>
                                                    <option value="bba" <?php echo ($education_data->highest_education ?? '') == 'bba' ? 'selected' : ''; ?>>BBA (Business Administration)</option>
                                                    <option value="bca" <?php echo ($education_data->highest_education ?? '') == 'bca' ? 'selected' : ''; ?>>BCA (Computer Applications)</option>
                                                    <option value="mbbs" <?php echo ($education_data->highest_education ?? '') == 'mbbs' ? 'selected' : ''; ?>>MBBS (Medicine)</option>
                                                    <option value="bds" <?php echo ($education_data->highest_education ?? '') == 'bds' ? 'selected' : ''; ?>>BDS (Dentistry)</option>
                                                    <option value="bams" <?php echo ($education_data->highest_education ?? '') == 'bams' ? 'selected' : ''; ?>>BAMS (Ayurveda)</option>
                                                    <option value="bhms" <?php echo ($education_data->highest_education ?? '') == 'bhms' ? 'selected' : ''; ?>>BHMS (Homeopathy)</option>
                                                    <option value="bpharm" <?php echo ($education_data->highest_education ?? '') == 'bpharm' ? 'selected' : ''; ?>>B.Pharm (Pharmacy)</option>
                                                    <option value="llb" <?php echo ($education_data->highest_education ?? '') == 'llb' ? 'selected' : ''; ?>>LLB (Law)</option>
                                                    <option value="barch" <?php echo ($education_data->highest_education ?? '') == 'barch' ? 'selected' : ''; ?>>B.Arch (Architecture)</option>
                                                    <option value="bdes" <?php echo ($education_data->highest_education ?? '') == 'bdes' ? 'selected' : ''; ?>>B.Des (Design)</option>
                                                    <option value="bhm" <?php echo ($education_data->highest_education ?? '') == 'bhm' ? 'selected' : ''; ?>>BHM (Hotel Management)</option>
                                                    <option value="other_ug" <?php echo ($education_data->highest_education ?? '') == 'other_ug' ? 'selected' : ''; ?>>Other Undergraduate Degree</option>
                                                </optgroup>
                                                
                                                <!-- Postgraduate Degrees -->
                                                <optgroup label="Postgraduate Degrees">
                                                    <option value="mtech" <?php echo ($education_data->highest_education ?? '') == 'mtech' ? 'selected' : ''; ?>>M.Tech / M.E. (Engineering)</option>
                                                    <option value="msc" <?php echo ($education_data->highest_education ?? '') == 'msc' ? 'selected' : ''; ?>>M.Sc (Science)</option>
                                                    <option value="mcom" <?php echo ($education_data->highest_education ?? '') == 'mcom' ? 'selected' : ''; ?>>M.Com (Commerce)</option>
                                                    <option value="ma" <?php echo ($education_data->highest_education ?? '') == 'ma' ? 'selected' : ''; ?>>M.A (Arts)</option>
                                                    <option value="mba" <?php echo ($education_data->highest_education ?? '') == 'mba' ? 'selected' : ''; ?>>MBA (Business Administration)</option>
                                                    <option value="mca" <?php echo ($education_data->highest_education ?? '') == 'mca' ? 'selected' : ''; ?>>MCA (Computer Applications)</option>
                                                    <option value="md" <?php echo ($education_data->highest_education ?? '') == 'md' ? 'selected' : ''; ?>>MD / MS (Medicine)</option>
                                                    <option value="mds" <?php echo ($education_data->highest_education ?? '') == 'mds' ? 'selected' : ''; ?>>MDS (Dentistry)</option>
                                                    <option value="mpharm" <?php echo ($education_data->highest_education ?? '') == 'mpharm' ? 'selected' : ''; ?>>M.Pharm (Pharmacy)</option>
                                                    <option value="llm" <?php echo ($education_data->highest_education ?? '') == 'llm' ? 'selected' : ''; ?>>LLM (Law)</option>
                                                    <option value="march" <?php echo ($education_data->highest_education ?? '') == 'march' ? 'selected' : ''; ?>>M.Arch (Architecture)</option>
                                                    <option value="mdes" <?php echo ($education_data->highest_education ?? '') == 'mdes' ? 'selected' : ''; ?>>M.Des (Design)</option>
                                                    <option value="other_pg" <?php echo ($education_data->highest_education ?? '') == 'other_pg' ? 'selected' : ''; ?>>Other Postgraduate Degree</option>
                                                </optgroup>
                                                
                                                <!-- Doctorate & Research -->
                                                <optgroup label="Doctorate & Research">
                                                    <option value="phd" <?php echo ($education_data->highest_education ?? '') == 'phd' ? 'selected' : ''; ?>>Ph.D (Doctorate)</option>
                                                    <option value="dsc" <?php echo ($education_data->highest_education ?? '') == 'dsc' ? 'selected' : ''; ?>>D.Sc (Science Doctorate)</option>
                                                    <option value="dlitt" <?php echo ($education_data->highest_education ?? '') == 'dlitt' ? 'selected' : ''; ?>>D.Litt (Literature Doctorate)</option>
                                                </optgroup>
                                                
                                                <!-- Professional Courses -->
                                                <optgroup label="Professional Courses">
                                                    <option value="ca" <?php echo ($education_data->highest_education ?? '') == 'ca' ? 'selected' : ''; ?>>CA (Chartered Accountant)</option>
                                                    <option value="cs" <?php echo ($education_data->highest_education ?? '') == 'cs' ? 'selected' : ''; ?>>CS (Company Secretary)</option>
                                                    <option value="icwa" <?php echo ($education_data->highest_education ?? '') == 'icwa' ? 'selected' : ''; ?>>ICWA (Cost Accountant)</option>
                                                    <option value="cfa" <?php echo ($education_data->highest_education ?? '') == 'cfa' ? 'selected' : ''; ?>>CFA (Chartered Financial Analyst)</option>
                                                    <option value="frm" <?php echo ($education_data->highest_education ?? '') == 'frm' ? 'selected' : ''; ?>>FRM (Financial Risk Manager)</option>
                                                    <option value="cpa" <?php echo ($education_data->highest_education ?? '') == 'cpa' ? 'selected' : ''; ?>>CPA (Certified Public Accountant)</option>
                                                    <option value="acca" <?php echo ($education_data->highest_education ?? '') == 'acca' ? 'selected' : ''; ?>>ACCA (Association of Chartered Certified Accountants)</option>
                                                </optgroup>
                                                
                                                <!-- Other Qualifications -->
                                                <optgroup label="Other Qualifications">
                                                    <option value="iti" <?php echo ($education_data->highest_education ?? '') == 'iti' ? 'selected' : ''; ?>>ITI (Industrial Training Institute)</option>
                                                    <option value="vocational" <?php echo ($education_data->highest_education ?? '') == 'vocational' ? 'selected' : ''; ?>>Vocational Training</option>
                                                    <option value="other" <?php echo ($education_data->highest_education ?? '') == 'other' ? 'selected' : ''; ?>>Other Qualification</option>
                                                </optgroup>
                                            </select>
                                            <div class="invalid-feedback">Please select your highest educational qualification.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="education_level">Level:</label>
                                            <select class="form-select" id="education_level" name="education_level" required>
                                                <option value="">Select Level</option>
                                                <option value="Class X" <?php echo ($education_data->education_level ?? '') == 'Class X' ? 'selected' : ''; ?>>Class X</option>
                                                <option value="Class XII" <?php echo ($education_data->education_level ?? '') == 'Class XII' ? 'selected' : ''; ?>>Class XII</option>
                                                <option value="Diploma" <?php echo ($education_data->education_level ?? '') == 'Diploma' ? 'selected' : ''; ?>>Diploma</option>
                                                <option value="Graduation" <?php echo ($education_data->education_level ?? '') == 'Graduation' ? 'selected' : ''; ?>>Graduation</option>
                                                <option value="Post Graduation" <?php echo ($education_data->education_level ?? '') == 'Post Graduation' ? 'selected' : ''; ?>>Post Graduation</option>
                                                <option value="Doctorate" <?php echo ($education_data->education_level ?? '') == 'Doctorate' ? 'selected' : ''; ?>>Doctorate</option>
                                                <option value="Professional Degree" <?php echo ($education_data->education_level ?? '') == 'Professional Degree' ? 'selected' : ''; ?>>Professional Degree</option>
                                                <option value="Others" <?php echo ($education_data->education_level ?? '') == 'Others' ? 'selected' : ''; ?>>Others</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your education level.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="institution">Institution:</label>
                                            <input type="text" class="form-control" id="institution" name="institution" value="<?php echo $education_data->institution ?? ''; ?>" placeholder="Enter institution">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="degree">Degree / Certificate Obtained:</label>
                                            <input type="text" class="form-control" id="degree" name="degree" value="<?php echo $education_data->degree ?? ''; ?>" placeholder="Enter degree or certificate">
                                        </div>
                                    
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="profession">Profession:</label>
                                            <select class="form-select" id="profession" name="profession" required>
                                                <option value="">Select Profession</option>
                                                
                                                <!-- IT & Software -->
                                                       <optgroup label="IT & Software">
                                                    <option value="Software Engineer" <?php echo ($education_data->profession ?? '') == 'Software Engineer' ? 'selected' : ''; ?>>Software Engineer</option>
                                                    <option value="Web Developer" <?php echo ($education_data->profession ?? '') == 'Web Developer' ? 'selected' : ''; ?>>Web Developer</option>
                                                    <option value="Mobile App Developer" <?php echo ($education_data->profession ?? '') == 'Mobile App Developer' ? 'selected' : ''; ?>>Mobile App Developer</option>
                                                    <option value="Data Scientist" <?php echo ($education_data->profession ?? '') == 'Data Scientist' ? 'selected' : ''; ?>>Data Scientist</option>
                                                    <option value="AI/ML Engineer" <?php echo ($education_data->profession ?? '') == 'AI/ML Engineer' ? 'selected' : ''; ?>>AI/ML Engineer</option>
                                                    <option value="DevOps Engineer" <?php echo ($education_data->profession ?? '') == 'DevOps Engineer' ? 'selected' : ''; ?>>DevOps Engineer</option>
                                                    <option value="Cloud Architect" <?php echo ($education_data->profession ?? '') == 'Cloud Architect' ? 'selected' : ''; ?>>Cloud Architect</option>
                                                    <option value="Cybersecurity Analyst" <?php echo ($education_data->profession ?? '') == 'Cybersecurity Analyst' ? 'selected' : ''; ?>>Cybersecurity Analyst</option>
                                                    <option value="IT Manager" <?php echo ($education_data->profession ?? '') == 'IT Manager' ? 'selected' : ''; ?>>IT Manager</option>
                                                    <option value="System Administrator" <?php echo ($education_data->profession ?? '') == 'System Administrator' ? 'selected' : ''; ?>>System Administrator</option>
                                                    <option value="Database Administrator" <?php echo ($education_data->profession ?? '') == 'Database Administrator' ? 'selected' : ''; ?>>Database Administrator</option>
                                                    <option value="QA Engineer" <?php echo ($education_data->profession ?? '') == 'QA Engineer' ? 'selected' : ''; ?>>QA Engineer</option>
                                                    <option value="UI/UX Designer" <?php echo ($education_data->profession ?? '') == 'UI/UX Designer' ? 'selected' : ''; ?>>UI/UX Designer</option>
                                                    <option value="Technical Support" <?php echo ($education_data->profession ?? '') == 'Technical Support' ? 'selected' : ''; ?>>Technical Support</option>
                                                </optgroup>

                                                
                                                <!-- Add all other profession options with selected attributes -->
                                                <!-- ... (rest of profession options with selected attributes) ... -->
                                                
                                            </select>
                                            <div class="invalid-feedback">Please select your profession.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="work_experience">Work Experience:</label>
                                            <input type="text" class="form-control" id="work_experience" name="work_experience" value="<?php echo $education_data->work_experience ?? ''; ?>" placeholder="Company / Years Worked">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="income">Current Income (Annual/CTC):</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control" id="income" name="income" value="<?php echo $education_data->income ?? ''; ?>" placeholder="Enter income" min="0">
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid income amount.</div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="lb" for="about_self">Say a few words about yourself:</label>
                                            <textarea class="form-control" id="about_self" name="about_self" placeholder="Write something about yourself" maxlength="500"><?php echo $education_data->about_self ?? ''; ?></textarea>
                                            <small class="form-text text-muted"><span id="about_self_counter"><?php echo strlen($education_data->about_self ?? ''); ?></span>/500 characters</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAMILY DETAILS -->
                                <div class="edit-pro-parti">
                                    <div class="form-tit">
                                        <h4>Family Details</h4>
                                        <p class="text-muted">Add details of your immediate family members</p>
                                    </div>
                                    
                                    <div id="familyMembersContainer">
                                        <?php 
                                        $family_members = $family_members ?? [];
                                        if(empty($family_members)): ?>
                                            <!-- Default first family member -->
                                            <div class="family-member-card card mb-3" data-member-index="0">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Family Member #1</h6>
                                                    <button type="button" class="btn btn-sm btn-danger remove-family-member" disabled>
                                                        <i class="fas fa-times"></i> Remove
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="lb">Relationship:</label>
                                                            <select class="form-select" name="family_relationship[]" required>
                                                                <option value="">Select Relationship</option>
                                                                <option value="father">Father</option>
                                                                <option value="mother">Mother</option>
                                                                <option value="brother">Brother</option>
                                                                <option value="sister">Sister</option>
                                                                <option value="grandfather">Grandfather</option>
                                                                <option value="grandmother">Grandmother</option>
                                                                <option value="uncle">Uncle</option>
                                                                <option value="aunt">Aunt</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="lb">Name:</label>
                                                            <input type="text" class="form-control" name="family_name[]" placeholder="Enter name">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="lb">Age:</label>
                                                            <input type="number" class="form-control" name="family_age[]" placeholder="Age" min="1" max="120">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label class="lb">Occupation:</label>
                                                            <input type="text" class="form-control" name="family_occupation[]" placeholder="Enter occupation">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label class="lb">Marital Status:</label>
                                                            <select class="form-select" name="family_marital_status[]">
                                                                <option value="">Select</option>
                                                                <option value="married">Married</option>
                                                                <option value="unmarried">Unmarried</option>
                                                                <option value="divorced">Divorced</option>
                                                                <option value="widowed">Widowed</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label class="lb">Education:</label>
                                                            <input type="text" class="form-control" name="family_education[]" placeholder="Educational qualification">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach($family_members as $index => $member): ?>
                                                <div class="family-member-card card mb-3" data-member-index="<?php echo $index; ?>">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">Family Member #<?php echo $index + 1; ?></h6>
                                                        <button type="button" class="btn btn-sm btn-danger remove-family-member" <?php echo $index == 0 ? 'disabled' : ''; ?>>
                                                            <i class="fas fa-times"></i> Remove
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label class="lb">Relationship:</label>
                                                                <select class="form-select" name="family_relationship[]" required>
                                                                    <option value="">Select Relationship</option>
                                                                    <option value="father" <?php echo $member->relationship == 'father' ? 'selected' : ''; ?>>Father</option>
                                                                    <option value="mother" <?php echo $member->relationship == 'mother' ? 'selected' : ''; ?>>Mother</option>
                                                                    <option value="brother" <?php echo $member->relationship == 'brother' ? 'selected' : ''; ?>>Brother</option>
                                                                    <option value="sister" <?php echo $member->relationship == 'sister' ? 'selected' : ''; ?>>Sister</option>
                                                                    <option value="grandfather" <?php echo $member->relationship == 'grandfather' ? 'selected' : ''; ?>>Grandfather</option>
                                                                    <option value="grandmother" <?php echo $member->relationship == 'grandmother' ? 'selected' : ''; ?>>Grandmother</option>
                                                                    <option value="uncle" <?php echo $member->relationship == 'uncle' ? 'selected' : ''; ?>>Uncle</option>
                                                                    <option value="aunt" <?php echo $member->relationship == 'aunt' ? 'selected' : ''; ?>>Aunt</option>
                                                                    <option value="other" <?php echo $member->relationship == 'other' ? 'selected' : ''; ?>>Other</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label class="lb">Name:</label>
                                                                <input type="text" class="form-control" name="family_name[]" value="<?php echo $member->name ?? ''; ?>" placeholder="Enter name">
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label class="lb">Age:</label>
                                                                <input type="number" class="form-control" name="family_age[]" value="<?php echo $member->age ?? ''; ?>" placeholder="Age" min="1" max="120">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label class="lb">Occupation:</label>
                                                                <input type="text" class="form-control" name="family_occupation[]" value="<?php echo $member->occupation ?? ''; ?>" placeholder="Enter occupation">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label class="lb">Marital Status:</label>
                                                                <select class="form-select" name="family_marital_status[]">
                                                                    <option value="">Select</option>
                                                                    <option value="married" <?php echo ($member->marital_status ?? '') == 'married' ? 'selected' : ''; ?>>Married</option>
                                                                    <option value="unmarried" <?php echo ($member->marital_status ?? '') == 'unmarried' ? 'selected' : ''; ?>>Unmarried</option>
                                                                    <option value="divorced" <?php echo ($member->marital_status ?? '') == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                                                                    <option value="widowed" <?php echo ($member->marital_status ?? '') == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 form-group">
                                                                <label class="lb">Education:</label>
                                                                <input type="text" class="form-control" name="family_education[]" value="<?php echo $member->education ?? ''; ?>" placeholder="Educational qualification">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="button" id="addFamilyMember" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Add Another Family Member
                                            </button>
                                            <small class="text-muted ms-2">You can add up to 10 family members</small>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="family_income">Family Income (Monthly):</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control" id="family_income" name="family_income" value="<?php echo $family_background->family_income ?? ''; ?>" placeholder="Excluding person for whom match is being sought" min="0">
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid family income amount.</div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="family_type">Family Type:</label>
                                            <select class="form-select" id="family_type" name="family_type">
                                                <option value="">Select Family Type</option>
                                                <option value="nuclear" <?php echo ($family_background->family_type ?? '') == 'nuclear' ? 'selected' : ''; ?>>Nuclear Family</option>
                                                <option value="joint" <?php echo ($family_background->family_type ?? '') == 'joint' ? 'selected' : ''; ?>>Joint Family</option>
                                                <option value="extended" <?php echo ($family_background->family_type ?? '') == 'extended' ? 'selected' : ''; ?>>Extended Family</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="family_values">Family Values:</label>
                                            <select class="form-select" id="family_values" name="family_values[]" multiple>
                                                <?php 
                                                $family_values = isset($family_background->family_values) ? explode(',', $family_background->family_values) : [];
                                                ?>
                                                <option value="traditional" <?php echo in_array('traditional', $family_values) ? 'selected' : ''; ?>>Traditional</option>
                                                <option value="modern" <?php echo in_array('modern', $family_values) ? 'selected' : ''; ?>>Modern</option>
                                                <option value="religious" <?php echo in_array('religious', $family_values) ? 'selected' : ''; ?>>Religious</option>
                                                <option value="liberal" <?php echo in_array('liberal', $family_values) ? 'selected' : ''; ?>>Liberal</option>
                                                <option value="conservative" <?php echo in_array('conservative', $family_values) ? 'selected' : ''; ?>>Conservative</option>
                                                <option value="progressive" <?php echo in_array('progressive', $family_values) ? 'selected' : ''; ?>>Progressive</option>
                                            </select>
                                            <small class="form-text text-muted">Hold Ctrl to select multiple values</small>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="lb" for="family_status">Family Status:</label>
                                            <select class="form-select" id="family_status" name="family_status">
                                                <option value="">Select Status</option>
                                                <option value="upper_class" <?php echo ($family_background->family_status ?? '') == 'upper_class' ? 'selected' : ''; ?>>Upper Class</option>
                                                <option value="upper_middle_class" <?php echo ($family_background->family_status ?? '') == 'upper_middle_class' ? 'selected' : ''; ?>>Upper Middle Class</option>
                                                <option value="middle_class" <?php echo ($family_background->family_status ?? '') == 'middle_class' ? 'selected' : ''; ?>>Middle Class</option>
                                                <option value="lower_middle_class" <?php echo ($family_background->family_status ?? '') == 'lower_middle_class' ? 'selected' : ''; ?>>Lower Middle Class</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="lb" for="about_family">Say a few words about your family:</label>
                                            <textarea class="form-control" id="about_family" name="about_family" placeholder="Write something about your family background, values, and traditions" maxlength="500"><?php echo $family_background->about_family ?? ''; ?></textarea>
                                            <small class="form-text text-muted"><span id="about_family_counter"><?php echo strlen($family_background->about_family ?? ''); ?></span>/500 characters</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- PARTNER PREFERENCES -->
                             <div class="edit-pro-parti">
    <div class="form-tit">
        <h4>Partner Preferences</h4>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label class="lb">Age Range:</label>
            <div class="row">
                <div class="col-6">
                    <input type="number" class="form-control" name="partner_age_from" value="<?php echo $partner_preferences->age_from ?? ''; ?>" placeholder="From" min="18" max="100">
                </div>
                <div class="col-6">
                    <input type="number" class="form-control" name="partner_age_to" value="<?php echo $partner_preferences->age_to ?? ''; ?>" placeholder="To" min="18" max="100">
                </div>
            </div>
            <div class="invalid-feedback d-block">Please enter a valid age range (18-100).</div>
        </div>
        <div class="col-md-6 form-group">
            <label class="lb">Height Range (cm):</label>
            <div class="row">
                <div class="col-6">
                    <input type="number" class="form-control" name="partner_height_from" value="<?php echo $partner_preferences->height_from ?? ''; ?>" placeholder="From" min="100" max="250" step="0.1">
                </div>
                <div class="col-6">
                    <input type="number" class="form-control" name="partner_height_to" value="<?php echo $partner_preferences->height_to ?? ''; ?>" placeholder="To" min="100" max="250" step="0.1">
                </div>
            </div>
            <div class="invalid-feedback d-block">Please enter a valid height range (100-250 cm).</div>
        </div>
        <div class="col-md-6 form-group">
            <label class="lb" for="partner_religion">Religion:</label>
            <select class="form-select" id="partner_religion" name="partner_religion">
                <option value="">Select Religion</option>
                <?php foreach($religions as $religionsrow) { ?>
                    <option value="<?php echo $religionsrow->id; ?>" <?php echo ($partner_preferences->religion_id ?? '') == $religionsrow->id ? 'selected' : ''; ?>>
                        <?php echo $religionsrow->name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label class="lb" for="partner_caste">Caste:</label>
            <select class="form-select" id="partner_caste" name="partner_caste">
                <option value="">Select Caste</option>
                <?php if(isset($castes) && !empty($castes)): ?>
                    <?php foreach($castes as $caste): ?>
                        <option value="<?php echo $caste->id; ?>" <?php echo ($partner_preferences->caste_id ?? '') == $caste->id ? 'selected' : ''; ?>>
                            <?php echo $caste->name; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label class="lb" for="partner_dietary">Dietary Preference:</label>
            <select class="form-select" id="partner_dietary" name="partner_dietary">
                <option value="">Select</option>
                <option value="Veg" <?php echo ($partner_preferences->dietary_preference ?? '') == 'Veg' ? 'selected' : ''; ?>>Veg</option>
                <option value="Non Veg" <?php echo ($partner_preferences->dietary_preference ?? '') == 'Non Veg' ? 'selected' : ''; ?>>Non Veg</option>
                <option value="Vegan" <?php echo ($partner_preferences->dietary_preference ?? '') == 'Vegan' ? 'selected' : ''; ?>>Vegan</option>
                <option value="Doesn't Matter" <?php echo ($partner_preferences->dietary_preference ?? '') == 'Doesn\'t Matter' ? 'selected' : ''; ?>>Doesn't Matter</option>
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label class="lb" for="partner_smoker">Smoker:</label>
            <select class="form-select" id="partner_smoker" name="partner_smoker">
                <option value="">Select</option>
                <option value="Strictly No" <?php echo ($partner_preferences->smoker ?? '') == 'Strictly No' ? 'selected' : ''; ?>>Strictly No</option>
                <option value="Occasionally" <?php echo ($partner_preferences->smoker ?? '') == 'Occasionally' ? 'selected' : ''; ?>>Occasionally</option>
                <option value="Doesn't Matter" <?php echo ($partner_preferences->smoker ?? '') == 'Doesn\'t Matter' ? 'selected' : ''; ?>>Doesn't Matter</option>
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label class="lb" for="partner_drinking">Drinking Habit:</label>
            <select class="form-select" id="partner_drinking" name="partner_drinking">
                <option value="">Select</option>
                <option value="Strictly No" <?php echo ($partner_preferences->drinking_habit ?? '') == 'Strictly No' ? 'selected' : ''; ?>>Strictly No</option>
                <option value="Occasionally" <?php echo ($partner_preferences->drinking_habit ?? '') == 'Occasionally' ? 'selected' : ''; ?>>Occasionally</option>
                <option value="Doesn't Matter" <?php echo ($partner_preferences->drinking_habit ?? '') == 'Doesn\'t Matter' ? 'selected' : ''; ?>>Doesn't Matter</option>
            </select>
        </div>
        
        <div class="col-md-12 form-group">
            <label class="lb" for="about_partner">Say a few words about your partner preference:</label>
            <textarea class="form-control" id="about_partner" name="about_partner" placeholder="Write something about partner preference" maxlength="500"><?php echo $partner_preferences->about_partner ?? ''; ?></textarea>
            <small class="form-text text-muted"><span id="about_partner_counter"><?php echo strlen($partner_preferences->about_partner ?? ''); ?></span>/500 characters</small>
        </div>
    </div>
</div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url('assets/frontend/assets/js/jquery.min.js'); ?>"></script>
<script>
$(document).ready(function() {
    const selectedHobbies = <?php echo json_encode($hobbies); ?>;
    
    console.log('Initializing TomSelect with:', selectedHobbies);
    
    // TomSelect initialize करें और values set करें
    const tomSelect = new TomSelect('#hobbies', {
        plugins: ['remove_button'],
        maxItems: null,
        create: false,
        placeholder: 'Choose your hobbies...'
    });
    
    // Values set करें
    tomSelect.setValue(selectedHobbies);
    
    console.log('TomSelect initialized successfully');
});
</script>
 <script>
        $(document).ready(function() {
          $('#profileEditForm').submit('click',function(){
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('update-user'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			    	
		
				 $(":submit").attr("disabled", false);
			
			  showAlert(data.class,data.title,data.message);
	
			 
		
				
			}
		});
		return false;
	}); 

        });

</script>
<script>
$(document).ready(function() {
    let familyMemberCount = 1;
    const maxFamilyMembers = 10;

    // Add new family member
    $('#addFamilyMember').on('click', function() {
        if (familyMemberCount >= maxFamilyMembers) {
            alert(`You can add maximum ${maxFamilyMembers} family members.`);
            return;
        }

        familyMemberCount++;
        const newIndex = familyMemberCount - 1;

        const newMemberHtml = `
            <div class="family-member-card card mb-3" data-member-index="${newIndex}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Family Member #${familyMemberCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-family-member">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="lb">Relationship:</label>
                            <select class="form-select" name="family_relationship[]" required>
                                <option value="">Select Relationship</option>
                                <option value="father">Father</option>
                                <option value="mother">Mother</option>
                                <option value="brother">Brother</option>
                                <option value="sister">Sister</option>
                                <option value="grandfather">Grandfather</option>
                                <option value="grandmother">Grandmother</option>
                                <option value="uncle">Uncle</option>
                                <option value="aunt">Aunt</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="lb">Name:</label>
                            <input type="text" class="form-control" name="family_name[]" placeholder="Enter name">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="lb">Age:</label>
                            <input type="number" class="form-control" name="family_age[]" placeholder="Age" min="1" max="120">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="lb">Occupation:</label>
                            <input type="text" class="form-control" name="family_occupation[]" placeholder="Enter occupation">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="lb">Marital Status:</label>
                            <select class="form-select" name="family_marital_status[]">
                                <option value="">Select</option>
                                <option value="married">Married</option>
                                <option value="unmarried">Unmarried</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="lb">Education:</label>
                            <input type="text" class="form-control" name="family_education[]" placeholder="Educational qualification">
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#familyMembersContainer').append(newMemberHtml);
        updateRemoveButtons();
    });

    // Remove family member
    $(document).on('click', '.remove-family-member', function() {
        if (familyMemberCount > 1) {
            $(this).closest('.family-member-card').remove();
            familyMemberCount--;
            renumberFamilyMembers();
            updateRemoveButtons();
        }
    });

    // Renumber family members after removal
    function renumberFamilyMembers() {
        $('.family-member-card').each(function(index) {
            const newNumber = index + 1;
            $(this).find('.card-header h6').text(`Family Member #${newNumber}`);
            $(this).attr('data-member-index', index);
        });
    }

    // Update remove buttons state
    function updateRemoveButtons() {
        const removeButtons = $('.remove-family-member');
        if (familyMemberCount === 1) {
            removeButtons.prop('disabled', true);
        } else {
            removeButtons.prop('disabled', false);
        }
    }

    // Character counter for family description
    $('#about_family').on('input', function() {
        const length = $(this).val().length;
        $('#about_family_counter').text(length);
    });

    // Initialize
    updateRemoveButtons();
});
</script>
<script>
$(document).ready(function() {
    // Religion to Caste dynamic dropdown
    $('#religions').on('change', function() {
        var religion_id = $(this).val();
        if(religion_id){
            $.ajax({
                url: "<?php echo base_url('get-castes'); ?>",
                type: "POST",
                data: {religion_id: religion_id},
                dataType: "json",
                success: function(data){
                    $('#castes').empty();
                    $('#castes').append('<option value="">Select Caste</option>');
                    $.each(data, function(key, value){
                        $('#castes').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }else{
            $('#castes').html('<option value="">Select Caste</option>');
        }
    });
    
        $('#partner_religion').on('change', function() {
        var religion_id = $(this).val();
        if(religion_id){
            $.ajax({
                url: "<?php echo base_url('get-castes'); ?>",
                type: "POST",
                data: {religion_id: religion_id},
                dataType: "json",
                success: function(data){
                    $('#partner_caste').empty();
                    $('#partner_caste').append('<option value="">Select Caste</option>');
                    $.each(data, function(key, value){
                        $('#partner_caste').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }else{
            $('#partner_caste').html('<option value="">Select Caste</option>');
        }
    });
    
    
    
    // Character counters for textareas
    $('#about_self, #about_family, #about_partner').on('input', function() {
        var length = $(this).val().length;
        var counterId = $(this).attr('id') + '_counter';
        $('#' + counterId).text(length);
    });
    
    // Form validation
    $('#profileEditForm').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
    
    // Education level auto-selection based on qualification
    $('#education').on('change', function() {
        var education = $(this).val();
        var levelSelect = $('#education_level');
        
        // Reset to default
        levelSelect.val('');
        
        // Auto-select based on qualification
        if (education.includes('10th') || education.includes('12th')) {
            if (education.includes('10th')) {
                levelSelect.val('Class X');
            } else if (education.includes('12th')) {
                levelSelect.val('Class XII');
            }
        } else if (education.includes('diploma')) {
            levelSelect.val('Diploma');
        } else if (education.startsWith('b') || education.includes('ug') || education === 'mbbs' || education === 'bds' || education === 'bams' || education === 'bhms' || education === 'bpharm' || education === 'llb' || education === 'barch' || education === 'bdes' || education === 'bhm') {
            levelSelect.val('Graduation');
        } else if (education.startsWith('m') || education.includes('pg') || education === 'md' || education === 'mds' || education === 'mpharm' || education === 'llm' || education === 'march' || education === 'mdes') {
            levelSelect.val('Post Graduation');
        } else if (education.includes('phd') || education.includes('dsc') || education.includes('dlitt')) {
            levelSelect.val('Doctorate');
        } else if (education === 'ca' || education === 'cs' || education === 'icwa' || education === 'cfa' || education === 'frm' || education === 'cpa' || education === 'acca') {
            levelSelect.val('Professional Degree');
        }
    });
});

// File validation functions
function validateFiles(input) {
    const maxFiles = 5;
    const maxSize = 5 * 1024 * 1024; // 5MB
    const files = input.files;
    
    if (files.length > maxFiles) {
        alert(`You can only upload a maximum of ${maxFiles} files.`);
        input.value = '';
        return false;
    }
    
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > maxSize) {
            alert(`File "${files[i].name}" is too large. Maximum file size is 5MB.`);
            input.value = '';
            return false;
        }
    }
    
    return true;
}

function validateSingleFile(input) {
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    if (input.files.length > 0 && input.files[0].size > maxSize) {
        alert('File is too large. Maximum file size is 5MB.');
        input.value = '';
        return false;
    }
    
    return true;
}
</script>