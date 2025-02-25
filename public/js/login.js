function validation(event) {
  const errMsg = document.querySelector('.empty' + '-' + this.name);
  if (errMsg) errMsg.remove();

  valid = form[this.name].value.length > 0;
  if (!valid) {
    const no = event.currentTarget.parentNode;
    const err = document.createElement('div');
    err.setAttribute('class', 'error' + ' empty' + '-' + this.name);
    err.textContent = 'Inserire ' + this.name;
    no.appendChild(err);
    return;
  }

  if (this.name == 'username') {
    fetch("/is_registered/username/" + form[this.name].value)
      .then(response => response.json())
      .then(json => {
        valid = json.is_registered;
        if (!valid) {
          const no = form[this.name].parentNode;
          const err = document.createElement('div');
          err.setAttribute('class', 'error' + ' empty' + '-' + this.name);
          err.textContent = 'Username non trovato';
          no.appendChild(err);
          valid = false
          return;
        }
      })
  }

  if (this.name == 'password') {
    valid = form[this.name].value.length >= 8  && form[this.name].value.length <= 20;
    if (!valid) {
      const no = form[this.name].parentNode;
      const err = document.createElement('div');
      err.setAttribute('class', 'error' + ' empty' + '-' + this.name);
      err.textContent = 'La password deve contenere tra gli 8 e i 20 caratteri';
      no.appendChild(err);
      return;
    }
  }
}

function vali(event) {
  if (form.username.value.length == 0 || form.password.value.length == 0) {
    event.preventDefault();
    for (let inputBox of inputBoxs) {
      if (!document.querySelector('.empty-' + inputBox.name)
        && form[inputBox.name].value.length == 0) {
        valid = false;
        const no = inputBox.parentNode;
        const err = document.createElement('div');
        err.setAttribute('class', 'error' + ' empty' + '-' + inputBox.name);
        err.textContent = 'Inserire ' + inputBox.name;
        no.appendChild(err);
      }
    }
  } else if(!valid) {
    event.preventDefault();
  }
}

let valid = false;
const form = document.forms['login'];
form.addEventListener('submit', vali);

const inputBoxs = document.querySelectorAll("input[type='text'], input[type='password']");
for (let inputBox of inputBoxs)
  inputBox.addEventListener('blur', validation);
