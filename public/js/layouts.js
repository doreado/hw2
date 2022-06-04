function createNav(type) {
  const body = document.querySelector('body');
  const nav = document.createElement('nav');
  body.appendChild(nav);

  const titleLink = document.createElement('div');
  titleLink.id = 'title-link';
  nav.appendChild(titleLink);

  const siteName = document.createElement('div');
  siteName.id = 'site-name';
  siteName.textContent = "L'occhio tagliato";
  titleLink.appendChild(siteName);

  const linkBox = document.createElement('div');
  titleLink.appendChild(linkBox);
  const link = document.createElement('a');
  if (type === 'profile') {
    link.href = "/home";
    link.textContent = 'HOME';
    linkBox.appendChild(link);
  } else {
    let username, id;
    fetch('/whoami')
      .then(response => response.json())
      .then(json => {
        username = json.username;
        id = json.id;
        // link.href = "profile.php?q=" + id;
        // link.textContent = username;
        linkBox.innerHTML = 'Benvenuto, <a href='+"profile.php?u=" + id +">"+username+"</a>";
      })
  }

  const logoutBox = document.createElement('div');
  nav.appendChild(logoutBox);
  const logout = document.createElement('a');
  logout.textContent = "LOGOUT";
  logout.href = 'logout.php';
  logoutBox.appendChild(logout);
}
