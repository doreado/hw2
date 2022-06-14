function validationError(item, errorType = 'length') {
  if (typeof item === 'string' || item instanceof String) {
    item = formItems[item];
  }

  if (!item.error.visible) {
    const target = item.domElement;
    const targetParent = target.parentNode;
    const err = document.createElement('div');
    err.setAttribute('class', 'validation-error ' + item.domElement.name);
    err.textContent = item.error.msgs[errorType];
    item.error.domError = err;

    targetParent.appendChild(err);

    item.error.visible = true;
  } else {
    item.error.domError.textContent = item.error.msgs[errorType];
  }

  item.valid = false;
}

function getErrors() {
  for (let item in formItems) {
    if (!formItems[item].valid) {
      validationError(formItems[item]);
    }
  }
}

function removeError(item) {
  item.valid = true;
  if (item.error.visible) {
    item.error.domError.textContent = '';
    item.error.domError.removeAttribute('class', 'validation-error ' + item.domElement.name);
  }
  item.error.visible = false;
}

function usrVal() {
  const item = formItems[this.name];

  if (this.value.length < 4 || this.value.length > 16) {
    validationError(item);
  } else {
    fetch("/is_registered/username/" + this.value)
      .then(response => response.json())
      .then(json => {
        if (json.is_registered) {
          validationError(this.name, 'availability');
        } else {
          removeError(formItems[this.name]);
        }
      })
  }
}

function nameVal(event) {
  const item = formItems[this.name];

  const inputLength = event.currentTarget.value.length;

  if (inputLength < 1 || inputLength > 16) {
    validationError(item)
  } else {
    removeError(item);
  }
}

function emailVal(event) {
  const item = formItems[this.name];
  const inputLength = event.currentTarget.value.length;

  if (inputLength < 1) {
    validationError(item);
    return;
  }

  const pattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
  if (!pattern.test(event.currentTarget.value)) {
    validationError(item, 'format');
    return
  }

  fetch("/is_registered/email/" + this.value)
    .then(response => response.json())
    .then(json => {
      if (json.is_registered) {
        validationError(this.name, 'availability');
      } else {
        removeError(formItems[this.name]);
      }
    })
}

function pwdVal(event) {
  const inputLength = event.currentTarget.value.length;
  const item = formItems[this.name];

  if (inputLength < 8 || inputLength > 20) {
    validationError(item);
    return;
  }

  const pattern = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
  if (!pattern.test(event.currentTarget.value)) {
    validationError(item, 'missingChar');
    return;
  }

  removeError(item);
}

function onSignupText(text) {
  const signupError = document.createElement('div');
  signupError.setAttribute('class', 'error-box');
  signupError.textContent = text;

  const subButton = document.querySelector("[type='submit']").parentNode;
  form.insertBefore(signupError, subButton);
}
function isValid() {
  return formItems.username.domElement.value.length > 0
    && formItems.name.domElement.value.length > 0
    && formItems.surname.domElement.value.length > 0
    && formItems.email.domElement.value.length > 0
    && formItems.password.domElement.value.length > 0
    && document.querySelectorAll('.validation-error')
}

function subSignup(event) {
  if (!isValid()) {
    event.preventDefault();
    getErrors();
   }
}

const formItems = {
  "username": {
    valid: false,
    handler: usrVal,
    domElement: null,
    error: {
      visible: false,
      domError: null,
      msgs: {
        length: "L'username deve avere tra 4 e 16 caratteri",
        availability: "L'username è occupato"
      },
    }
  },
  "name": {
    valid: false,
    handler: nameVal,
    domElement: null,
    error: {
      visible: false,
      domError: null,
      msgs: {
        length: "Il nome deve avere tra 1 e 16 caratteri"
      },
    }
  },
  "surname": {
    valid: false,
    handler: nameVal,
    domElement: null,
    error: {
      domError: null,
      visible: false,
      msgs: {
        length: "Il cognome deve avere tra 1 e 16 caratteri"
      }
    }
  },
  "email": {
    valid: false,
    handler: emailVal,
    domElement: null,
    error: {
      domError: null,
      visible: false,
      msgs: {
        availability: "Esiste già un account associato a questa mail",
        length: "Questo campo non può essere vuoto",
        format: "Formato non valido!",
      }
    }
  },
  "password": {
    valid: false,
    handler: pwdVal,
    domElement: null,
    error: {
      domError: null,
      visible: false,
      msgs: {
        length: "La password deve contenere tra gli 8 e i 20 caratteri",
        missingChar: "La password deve contenere almeno una maiuscola, una \
        minuscola, un numero e un carattere speciale"
      }
    }
  }
}

for (let item in formItems) {
  formItems[item].domElement = document.querySelector("input[name=" + item + "]");
  formItems[item].domElement.addEventListener('blur', formItems[item].handler);
}

const form = document.forms['signup'];
form.addEventListener('submit', subSignup);
