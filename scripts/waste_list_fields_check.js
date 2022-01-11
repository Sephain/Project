let myForm = document.querySelector('#my_form');
let warn = document.querySelector('#warning');

document.querySelector('#btn_add').onclick = function(e) {
    e.preventDefault();
    if (document.querySelector('#one').value=="") {warning(warn, 'Пожалуйста, заполните все поля!')}
    else { myForm.submit();}
}

function warning(elem, message){
    elem.innerHTML = message;
}