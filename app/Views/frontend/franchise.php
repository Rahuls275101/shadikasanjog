 
 
 <section>
    <div class="container">
      <div class="titleHeader">
        <h2> <?php echo $franchise->franchise_name; ?></h2>
        <p class="mb-0">📌  <?php echo $franchise->franchise_address; ?></p>
        <input type="hidden" id="franchise_time"  value="<?php echo $franchise->franchise_time; ?>">
      </div>
      <div class="row">
        <div class="col-12" id="question-container">
            <p class="bg-dark text-white py-3 mb-2 px-2 rounded w-100" id="question">Objective Question</p>
          <form id="answer-form">
            <input type="hidden" id="questioninput" name="questioninput" value="">
            <input type="hidden" id="franchise_id" name="franchise_id" value="<?php echo $franchise->franchise_id; ?>">
            <div class="form-check">
              <input type="radio" class="form-check-input" id="check1" name="option" value="something">
              <label class="form-check-label" id="nswer-0"><span id="answer-0">Loading...</span></label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="check2" name="option" value="something">
              <label class="form-check-label" id="nswer-1"><span id="answer-1">Loading...</span></label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="check2" name="option" value="something">
              <label class="form-check-label" id="nswer-1"><span id="answer-2">Loading...</span></label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="check2" name="option" value="something">
              <label class="form-check-label" id="nswer-2"><span id="answer-3">Loading...</span></label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="check2" name="option" value="something">
              <label class="form-check-label" id="nswer-3"><span id="answer-4">Loading...</span></label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="check2" name="option" value="something">
              <label class="form-check-label" id="nswer-4"><span id="answer-5">Loading...</span></label>
            </div>
          <hr>
            <div class="my-2">
              <input type="name" class="form-control" id="your-name" placeholder="Your full name" name="username">
              <input type="text" class="form-control"  name="pincode" minlength="7" maxlength="7" placeholder="First 7 mobile number" required/>
            <input type="text" class="form-control"  name="address" placeholder="Your Address" required/>
            </div><hr>
            <div class="">
              <button type="submit" class="btn btn-dark w-50">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  
  <section>
    <div class="container">
      <div class="titleHeader">
        <h4 id="questionsecond">Last Question: Who was the brother of Sugreeve ?</h4>
        <p class="mb-0" id="questionfrequency">Question Frequency: 15 minutes</p>
      </div>
      <div class="row pt-5">
        <div class="col-12">
          <h3 class="text-center"><img src="<?php echo base_url('assets/'); ?>/frontend/icons/trophy.png">&nbsp;Winner</h3>
          
       
              <?php foreach($winner as $winnerrow) { ?>
  
        <p class="bg-dark py-2 text-white text-center mb-0"><?php echo $winnerrow->answered_user_name; ?> - <?php echo $winnerrow->answered_user_pin; ?></p>
        <?php } ?>
              
          
          
          
            
          
          <p class="bg-light py-2 text-dark text-center"><a href="<?php echo base_url('tour/'); ?>/<?php echo $cms_pages->cms_id; ?>" class="btn card-btn py-1 rounded-0"><?php echo $cms_pages->cms_page_heading; ?> <img src="<?php echo base_url('assets/'); ?>/frontend/icons/car-side.png" class="animated-car"></a></p>
        </div>
      </div>
    </div>
    <div class="container pt-5">
      <h4 class="text-center pb-2"><img src="<?php echo base_url('assets/'); ?>/frontend/icons/correct.png"> Correct Answer</h4>
      <p class="bg-light p-2 text-center"><?php echo $franchise->franchise_discount; ?></p>
      <ul class="list-group list-group-flush scroll3 my-2 scrollbar3 user-answers">
    
        
      </ul>
      <a href="rama-lalla-calling.html" class="card btn p-0 text-decoration-none mt-4 w-100 text-center">
        <div class="card-body">Rama Lalla Calling <img src="icons/rama.png"></div> 
        <div class="card-footer">🙏 Jai Shri Rama</div>
      </a>
    </div>
  </section>
  
  <section>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <!-- FREE Membership -->
          <div class="offcanvas offcanvas-start" id="free-membership">
            <div class="offcanvas-header">
              <h2 class="offcanvas-title">Yoga Sagar</h2>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
              <p class="text-muted pb-2 mb-2">
                FREE membership: 4 Options
              </p>
              <a aria-label="Dharma Sankalpam" href="https://wa.me/919738708760" class="card text-decoration-none me-2 text-center mb-2">
                <div class="card-body">Play Ramayana</div> 
                <div class="card-footer">for correct answer</div>
              </a>
              <a aria-label="Dharma Sankalpam" href="https://wa.me/919738708760" class="card text-decoration-none me-2 text-center mb-2">
                <div class="card-body">Dharma Course</div> 
                <div class="card-footer">free course</div>
              </a>
              <a aria-label="Dharma Sankalpam" href="https://wa.me/919738708760" class="card text-decoration-none me-2 text-center mb-2">
                <div class="card-body">Travel Plans</div> 
                <div class="card-footer">for any travel</div>
              </a>
              <a aria-label="Dharma Sankalpam" href="https://wa.me/919738708760" class="card text-decoration-none me-2 text-center mb-2">
                <div class="card-body">Ritual Plans</div> 
                <div class="card-footer">for any ritual</div>
              </a>
              <h5 class="fs-4 border-2 border-top pt-3 mt-3 text-center border-primary">
                &#x1f64f; Jai Shri Rama
              </h5>
            </div>
          </div>
          <h6 class="text-muted ps-2 pb-2"><a href="#." class="text-decoration-none" data-bs-target="#free-membership" data-bs-toggle="offcanvas">FREE Membership</a> <span class="no-wrap">&#10095; Recent Members</span></h6>
          <div class="scrollmenu">
            <a href="#." class="btn btn-outline-secondary">Ravi Kumar Singh</a>
            <a href="#." class="btn btn-outline-secondary">Ravi Kumar Singh</a>
            <a href="#." class="btn btn-outline-secondary">Ravi Kumar Singh</a>
            <a href="#." class="btn btn-outline-secondary">Ravi Kumar Singh</a>
            <a href="#." class="btn btn-outline-secondary">Ravi Kumar Singh</a>
            <a href="#." class="btn btn-outline-secondary">Ravi Kumar Singh</a>
          </div>
        </div>
        
        <div class="col-12 pt-4">
          <div class="sharethis-inline-share-buttons st-center st-has-labels  st-inline-share-buttons st-animated" id="st-1"><div class="st-btn st-first " data-network="facebook" style="display: inline-block;">
              <img alt="facebook sharing button" src="https://platform-cdn.sharethis.com/img/facebook.svg">
              <span class="st-label">Share</span>
            </div><div class="st-btn " data-network="twitter" style="display: inline-block;">
              <img alt="twitter sharing button" src="https://platform-cdn.sharethis.com/img/twitter.svg">
              <span class="st-label">Tweet</span>
            </div><div class="st-btn " data-network="email" style="display: inline-block;">
              <img alt="email sharing button" src="https://platform-cdn.sharethis.com/img/email.svg">
              <span class="st-label">Email</span>
            </div><div class="st-btn " data-network="whatsapp" style="display: inline-block;">
              <img alt="whatsapp sharing button" src="https://platform-cdn.sharethis.com/img/whatsapp.svg">
              <span class="st-label">Share</span>
            </div><div class="st-btn st-last " data-network="sharethis" style="display: inline-block;">
              <img alt="sharethis sharing button" src="https://platform-cdn.sharethis.com/img/sharethis.svg">
              <span class="st-label">Share</span>
            </div>
          </div>
        </div>
        
        <div class="col-12 pt-5">
          <div id="disqus_thread"></div>
        </div>
      </div>
      
    </div>
  </section>
  
<script>
$(document).ready(function() {



    
    
$('#answer-form').submit('click',function(){
    

// Create FormData object and append form data including CKEditor content
var formData = new FormData(this);

    $(":submit").attr("disabled", true);
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('answarsave'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			    	$("#answer-form")[0].reset();
		
				 $(":submit").attr("disabled", false);
		
			 showAlert(data.class,data.title,data.message);	
			}
		});
		return false;
	});    
	

    
});
</script>
<script>
const questionsAndAnswers = <?php echo $jsArray; ?>;

questionsAndAnswersLength = questionsAndAnswers.length-parseInt(1);



function changeQuestionAndAnswers() {
    
  const questionElement = document.getElementById("question");
  const questionquestionsecondElement = document.getElementById("questionsecond");
  const questionfrequency = document.getElementById("questionfrequency");
  
  
  
  const franchiseTime = document.getElementById("franchise_time").value;
  const timecount = parseInt(franchiseTime)  * parseInt(1000);

   questionfrequency.textContent = 'Question Frequency: ' + franchiseTime + ' minutes';

  
  const answerElements = document.querySelectorAll("#answer-form label span");
  const checkboxes = document.querySelectorAll("input[type='radio']");
  
  
  let indexSecond = questionsAndAnswersLength;
  let index = 0;
  let nuans = 1;
  

  
  
  // Display the first question immediately
  const currentQuestion = questionsAndAnswers[index];
    const currentQuestionSecond = questionsAndAnswers[indexSecond];
  questionElement.textContent = currentQuestion.question;
  questionquestionsecondElement.textContent = 'Last Question: '+currentQuestionSecond.question;
  document.getElementById('questioninput').value = currentQuestion.question; // Ensure the correct ID is used
  currentQuestion.answers.forEach((answer, i) => {
    answerElements[i].textContent = answer;
    checkboxes[i].value = nuans; // Set checkbox value to answer
    
    nuans++;
   
  });

  currentQuestionSecond.useranswers.forEach((useranswer, i) => {
  htmlUseranswer = ' <li class="list-group-item text-muted">'+useranswer+'</li>';
 
  $('.user-answers').append(htmlUseranswer);
   
  });


 
  setInterval(() => {
    
    const currentQuestion = questionsAndAnswers[index];
     const currentQuestionSecond = questionsAndAnswers[indexSecond];
       
    questionElement.textContent = currentQuestion.question;
   
     questionquestionsecondElement.textContent = 'Last Question: '+currentQuestionSecond.question;
     
    
    document.getElementById('questioninput').value = currentQuestion.question; // Ensure the correct ID is used
    currentQuestion.answers.forEach((answer, i) => {
      answerElements[i].textContent = answer;
      checkboxes[i].value = nuans; // Set checkbox value to answer
      nuans++;
    
    });

    checkboxes.forEach((checkbox) => {
      checkbox.checked = false; // Clear all checkboxes
    });
    
    $('.user-answers').html('');
    
      currentQuestionSecond.useranswers.forEach((useranswer, i) => {
  htmlUseranswer = ' <li class="list-group-item text-muted">'+useranswer+'</li>';
 
  $('.user-answers').append(htmlUseranswer);
   
  });

    
    index = (index + 1) % questionsAndAnswers.length;
indexSecond = (indexSecond + 1) % questionsAndAnswers.length;
    
  }, timecount); // 15 seconds in milliseconds
}

changeQuestionAndAnswers();
</script>

