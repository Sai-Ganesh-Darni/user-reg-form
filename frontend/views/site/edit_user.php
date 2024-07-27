<?php
    use yii\helpers\Url;
?>

<style>
    .select2-selection__rendered {
        line-height: 31px !important;
    }
    .select2-container .select2-selection--single  {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }

    .toast{
        background-color: white;
    }

    .toast-success{
        color: green;
    }

    .toast-error{
        color: red;
    }

</style>

<form id = 'userForm'>
<?php 
    // $request = Yii::$app->request;
    // echo 'ID: ' . $request->get('model');
    // print_r($model);
?>

    <h3>Edit User Information</h3>

    <label for = 'first_name'>
        First Name 
        <input type = 'text' id = 'first_name' name = 'first_name' value = '' />
        <p class = 'red-text' id = 'first_nameError'></p>
    </label>

    <label for = 'last_name'>
        Last Name
        <input type = 'text' id = 'last_name' name = 'last_name' value = '' />
        <p class = 'red-text' id = 'last_nameError'></p>
    </label>

    <label for = 'email'>
        Email
        <input type = 'email' name = 'email' id = 'email' value = '' />
        <p class = 'red-text' id = 'emailError'></p>
    </label>

    <label for = 'phone'>
        Phone Number
        <input id = 'phone' type = 'tel' name = 'phone' value = '' style = 'margin-left:46px;'/>
        <p class = 'red-text' id = 'phoneError'></p>
    </label>

    <div class = 'gender-select'>
        <label style="margin-bottom:4px;">
            Gender
        </label>
        <div >
            <label>
                <input class = 'with-gap' type = 'radio' name = 'gender' value = 'male'  />
                <span>Male</span>
            </label>
            <label>
                <input class = 'with-gap' type = 'radio' name = 'gender' value = 'female'/>
                <span>Female</span>
            </label>
        </div>
        <p class = 'red-text' id = 'genderError'></p>
    </div>

    <div class = 'input-field col s12'>
        <select class='browser-default single-select' name = 'education_qualification' id = 'education_qualification'>
            <option value = '' disabled selected>Choose your highest education qualification</option>
            <option value = 'high_school'>High School</option>
            <option value = 'associate_degree'>Associate Degree</option>
            <option value = 'bachelor_degree'>Bachelor's Degree</option>
            <option value='master_degree' >Master's Degree</option>
            <option value = 'doctoral_degree'>Doctoral Degree</option>
        </select>
        <p class = 'red-text' id = 'education_qualificationError'></p>
    </div>

    <div class = 'input-field col s12'>
        <select class="browser-default multi-select" id="hobbies" name="hobbies[]" multiple="multiple">
            <option value = '' disabled>Select your hobbies</option>
            <option value = 'reading' >Reading</option>
            <option value = 'travelling' >Travelling</option>
            <option value = 'cooking' >Cooking</option>
            <option value = 'sports' >Sports</option>
            <option value = 'music' >Music</option>
        </select>
        <p class = 'red-text' id = 'hobbiesError'></p>
    </div>
    

    
    <button id="submit-btn" class = 'btn waves-effect waves-light' type = 'submit' name = 'submit' >submit</button>
    

   
</form>





<script>





$(document).ready(function() {
    
    // Initialize the Materialize select
    $('select').formSelect();
    

    // Initialize the Selec2 single select 
    $('.single-select').select2({
        placeholder:"Choose your highest education qualification",
        allowClear: true,
        width:'100%',
        height:'10px',
    })

    // Initialize the Selec2 Multiple select 
    $('.multi-select').select2({
        placeholder:"Select your hobbies",
        allowClear:true,
        width:'100%',
        height:'10px',
    });


     
    // Method to validate the email
    function isValidEmail(email) {
        const regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    

    // Initialize the Internation Telephone input plugin
    let phoneInput='';
    $('userForm').ready(function(){
      const phoneInputField = document.querySelector("#phone");
      phoneInput = window.intlTelInput(phoneInputField, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        initialCountry: "auto",
        geoIpLookup: function(success, failure) {
            fetch("https://ipapi.co/json")
            .then(function(res) { return res.json(); })
            .then(function(data) { success(data.country_code); })
            .catch(function() { failure(); });
        }
        });
    })


    const searchParams = new URLSearchParams(window.location.search);

    if(searchParams.has('id')){
        const id = searchParams.get('id');
        const url = $(this).attr('action');
        $.get(url,{'id':id},
            function(data){
                form_fields.map(field => {
                    $('#'+field).val(data[field]);
                    if(field==='gender'){
                        $(`input[value=${data[field]}]`).prop('checked',true);
                    } 
                });
            }
        );
    }



    const form_fields = ['first_name','last_name','email','phone','gender','education_qualification','hobbies'];

    // Validation function
    function isFormValid(){
        const errors = {
          first_name: '* required',
          last_name: '* required',
          email: '* required',
          phone: '* required',
          gender: '* required',
          education_qualification: '* required',
          hobbies: '* required'
        };

        data = $('form').serializeArray();
        
        data.forEach(each => {
            if(each.name === 'gender' || each.name === 'education_qualification' ){
                errors[each.name] = '';
            }
            else if(each.name === 'hobbies[]'){
                errors['hobbies'] = '';
            }
            else{
                if(each.value===''){
                    errors[each.name] = '* required'
                }
                else if(each.name==='first_name' || each.name==='last_name'){
                    errors[each.name] = !/^[a-zA-Z\s]+$/.test(each.value)?'* Must be letters or spaces':'';
                }else if(each.name === 'email'){
                    errors[each.name] = !/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(each.value)?'* invalid email address':'';
                }
                else if(each.name === 'phone'){
                    errors[each.name] = !/\d+$/.test($('#phone').val()) || !phoneInput.isValidNumber()?'* invalid phone number':'';
                }else{
                    errors[each.name] = '';
                }
            }
            
        })

        Object.entries(errors).map(error => $('#'+error[0]+'Error').text(error[1]));


        if(Object.values(errors).every(error => error === '')){
            return true;
        }else{
            return false;
        }
    }



    $('#userForm').change(function(){
        data = $('form').serializeArray();
        isFormValid();
    })
    
    
 
    // Submits the form
    $('#userForm').submit(function(e) {
        e.preventDefault();
        data = $('form').serializeArray();
        console.log(data);

        if (isFormValid()) {
            $('#submit-btn').attr('disabled',true);
            $('#submit-btn').text('Loading...');
            data = $('form').serializeArray();
            
            data.push({name: 'phone',value:phoneInput.getNumber()})
            data.push({name:'id',value:searchParams.get('id')});
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            data.push({ name: $('meta[name="csrf-param"]').attr('content'), value: csrfToken });

            // console.log(data);
            var url = $(this).attr('action');
            
            $.ajax({
                url: url,
                type: 'PUT',
                data: data,
                success: function(response) {
                    
                    response = JSON.parse(response);
                
                    if(response.success) {
                        M.toast({html:'✓ '+response.message,displayLength:'4000',classes:'toast-success rounded'});
                        setTimeout(() => {
                            window.location.href = 'index.php?r=site/get-users';
                        }, 3000);
                    } else {
                        console.log('Entererd Failure');
                        M.toast({html:'* '+response.message,displayLength:'4000',classes:'toast-error rounded'});
                        if (response.errors) {
                            console.log(response.errors); // Log validation errors for debugging
                        }
                    }
                    
                    $('#submit-btn').attr('disabled', false).text('Submit');
                    
                },
                error: function(xhr, status, error) {
                    alert('Failed submitting the form: ' + error);
                    console.error(xhr.responseText); // Log the response text for debugging
                    $('#submit-btn').attr('disabled', false).text('Submit');

                }
            });
        }
      
    });

    



});

</script>
