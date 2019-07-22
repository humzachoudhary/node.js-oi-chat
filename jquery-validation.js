// Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"

  $("form[name='userForm']").validate({  //form name atribute must be register
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      username: "required",  //this part of code will take atribute with name firstname and make it required
      password: "required",

            
      /*password: {
        required: true,
        minlength: 5
      },
      password2 : {
      	required:true,
        minlength : 5,
        equalTo : "#password"
      }*/
    },

    // Specify validation error messages
    messages: {
      username: "*Please enter your first name",
      password: "*Please enter your password",

      
     
      
      /*password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"

      },
      password2: {
      	required: "Please confirm your password",
      	equalTo : "Your password do not match password you entered first",
      	minlength: "Your password must be at least 5 characters long"
      },*/

      
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid 
    submitHandler: function(form) { // <- pass 'form' argument in
        $(".submit").attr("disabled", true);
        form.submit(); //that will make users not able to press subimt button until everything what's defined is entered
    }
  });
});
