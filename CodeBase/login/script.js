const username = document.querySelector('#username');
const password = document.querySelector('#password');
const submit = document.querySelector('#submit');

submit.addEventListener('click', (event)=> {
    event.preventDefault();

    //USERNAME VALIDATION
    if(username.ariaValueMax.trim() == '') {
        error(username, 'username can not be empty');
    }
});

function error(element, msg) {
    element.style.border = '3px red solid';
}