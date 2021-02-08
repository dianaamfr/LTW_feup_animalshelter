'use strict'

// ----- Register -----
let register = document.querySelector('#register > form');

if(register){

  let username = document.querySelector('#register > form input[name=username]');
  username.addEventListener('keyup', validateUsername, false);

  let password = document.querySelector('#register > form input[name=password]');
  let repeat = document.querySelector('#register  > form input[name=password_repeat]');
  password.addEventListener('keyup', validatePassword, false);
  repeat.addEventListener('keyup', validateRepeat.bind(repeat, password), false);

  register.addEventListener('submit', validateAuth, false);
}

// ----- Edit Profile ------

'use strict'

let edit = document.querySelector('#editProfile > form')
let edit_btn = document.querySelector('#editProfile > form input[name=submit_btn]')
let delete_profile_btn = document.querySelector('#editProfile > form input[name=deleteprofile_btn]')

if(edit){
    let newusername = document.querySelector('#editProfile > form input[name=editUsername]')
    newusername.addEventListener('keyup', validateUsername, false);

    let newpassword = document.querySelector('#editProfile > form input[name=editPassword]')
    let repeat = document.querySelector('#editProfile  > form input[name=edit_password_repeat]')
    newpassword.addEventListener('keyup', validatePassword, false);
    repeat.addEventListener('keyup', validateRepeat.bind(repeat, newpassword), false);

    edit_btn.addEventListener('click', validateAuth, false);
    delete_profile_btn.addEventListener('click', validateAuth, false);
}


// Validations

/**
 * Check if username only contains lowercase letters, numbers and has at least 5 characters. Show hint if it doesn't
 */
function validateUsername() {
  if (!/^[_a-z0-9]{5,}$/.test(this.value))
    this.classList.add('invalid');
  else
    this.classList.remove('invalid');
}

/**
 * Check if password contains at least 8 characters, including a lowercase letter, an uppercase letter,
 * a number and a symbol. Show hint if it doesn't
 */
function validatePassword(other) {
  if (!/^.*(?=.*[A-Z])(?=.*[!@#$&*%+=,\-\_\.;?])(?=.*[0-9]).{8,}$/.test(this.value))
    this.classList.add('invalid');
  else
    this.classList.remove('invalid');
}

/**
 * Check if password repeat matches password. Show hint if it doesn't
 */
function validateRepeat(password) {
  if (this.value !== password.value)
    this.classList.add('invalid');
  else
    this.classList.remove('invalid');
}

/**
 * Prevent default action if there is an invalid field.
 * If editing the profile and every field is valid ask for confirmation of the delete action. 
 * If the user does not confirm, prevent default delete profile action
 */
function validateAuth(event) {  
  let inputs = this.querySelectorAll('form input');
  for (let i = 0; i < inputs.length; i++){
    if (inputs[i].classList.contains('invalid')){
      event.preventDefault();
      return;
    }
  }

  // ------ Delete Profile Confirmation -------

  if(event.target === delete_profile_btn){
    let answer = confirm("Are you sure you want to delete you profile?");

    if(!answer) event.preventDefault();
  }
}



