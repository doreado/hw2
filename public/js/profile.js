function onFollowButton(event) {
  const clicked = event.currentTarget;
  const toFollow = clicked.dataset.followed !== 'true';
  clicked.setAttribute("data-followed", toFollow);
  clicked.src = toFollow ? '/figures/followed_dark.png' : '/figures/not_followed_dark.png';
  fetch("/toggle_follow/" + toFollow)
    .then(response => response.json())
    .then(json => {
      if (!json.success) {
        alert("Qualcosa è andato storto. Per favore ricarica la pagina");
      }
    })
}

function displayPost(post, view) {
  const postCurr = document.createElement("div");
  postCurr.setAttribute("id", post.id);
  postCurr.classList.add("entry-recently");
  view.appendChild(postCurr);

  const leftElem = document.createElement("div");
  leftElem.setAttribute("id", "recently-left");
  postCurr.appendChild(leftElem);

  const posterBox = document.createElement("div");
  posterBox.classList.add("recently-poster-box");
  leftElem.appendChild(posterBox);

  const poster = document.createElement("img");
  posterBox.appendChild(poster);

  const postInfo = document.createElement("div");
  postInfo.setAttribute("id", "post-info");
  leftElem.appendChild(postInfo);

  const movieTitle = document.createElement("h1");
  postInfo.appendChild(movieTitle);
  const movieReview = document.createElement("p");
  postInfo.appendChild(movieReview);
  fetch("/get_movie/" + post.type_id)
    .then(response => response.json())
    .then(json => {
      if (json.data) {
        poster.src = json.data.poster;
        movieTitle.textContent = json.data.title;
        movieReview.textContent = post.content;
      }
    });

  if (loggedProfile) {
    const xIconBox = document.createElement("div");
    xIconBox.classList.add("x-icon-box");
    postCurr.appendChild(xIconBox);
    const xIcon = document.createElement("img");
    xIcon.classList.add("x-icon");
    xIcon.src = "http://localhost:8000/figures/x_icon_dark.png";
    xIconBox.append(xIcon);
    xIconBox.addEventListener('click', _ => {
      fetch("/remove_post/" + post.id)
        .then(response => response.json())
        .then(json => {
          if (!json.success) {
            alert("E' successo qualcosa di strano");
          } else {
            postCurr.parentNode.removeChild(postCurr);
          }
        });
    });
  }
}

function viewPosts(data, view) {
  for (let entry of data) {
    displayPost(entry, view);
  }
}

function removePreference(event) {
  const clicked = event.currentTarget;
  fetch("http://localhost:8000/remove_preference/"
    + clicked.dataset.preferenceType + "/" + clicked.dataset.preference)
    .then(response => response.json)
    .then( _ => {
      const plusIcon = clicked.parentNode.parentNode.querySelector('.plus-icon-box');
      plusIcon.classList.remove('hidden');
      plusIcon.addEventListener('click', onPlusIconClick);
      clicked.parentNode.parentNode.removeChild(clicked.parentNode);
      }
    )
}

function addPreference(event) {
  const form = event.currentTarget.parentNode;
  const plusButton = form.parentNode.querySelector('.plus-icon-box');
  event.preventDefault()

  const formData = new FormData(form);
  if (formData.get('pref').length == 0) {
    return;
  }

  fetch("http://localhost:8000/add_preference", {
    method: "post",
    body: formData
  });

  event.currentTarget.removeEventListener('click', addPreference);
  form.classList.add('hidden');
  const itemBox = document.createElement('div');
  itemBox.classList.add('preference-box');

  const item = document.createElement('p');
  item.textContent = formData.get('pref');
  item.classList.add('preference');
  itemBox.appendChild(item);
  const xIconBox = document.createElement("div");
  xIconBox.classList.add("x-icon-box");
  xIconBox.setAttribute('data-preference-type', formData.get('type'));
  xIconBox.setAttribute('data-preference', formData.get('pref'));
  xIconBox.addEventListener('click', removePreference)
  const xIcon = document.createElement("img");
  xIcon.classList.add("x-icon");
  xIcon.src = "http://localhost:8000/figures/x_icon_dark.png";
  xIconBox.append(xIcon);
  itemBox.appendChild(xIconBox)

  plusButton.parentNode.insertBefore(itemBox, plusButton);
  if (form.parentNode.querySelectorAll('.preference-box').length < 5) {
    plusButton.classList.remove('hidden');
    plusButton.addEventListener('click', onPlusIconClick)
  }
}

function onPlusIconClick(event) {
  event.currentTarget.classList.add('hidden');
  event.currentTarget.removeEventListener('click', onPlusIconClick);
  const form = event.currentTarget.parentNode.querySelector('form');
  if (form) form.classList.remove('hidden');
  form.querySelector('[type=submit]').addEventListener('click', addPreference);
  // event.currentTarget.parentNode.remkoveChild(event.currentTarget);
}

function setPreferences() {
  if (!loggedProfile) return;

  const preferences = document.querySelectorAll('.preference-box .x-icon-box');
  if (!preferences) return;
  preferences.forEach(node => {
    node.addEventListener('click', removePreference)
  })

  document.querySelectorAll('.plus-icon-box').forEach(node =>{
    if (node.parentNode.querySelectorAll('.preference-box').length < 5) {
      node.classList.remove('hidden');
      node.addEventListener('click', onPlusIconClick);
    }
  })
}

function createRecently() {
  const view = document.createElement("div");
  view.setAttribute("class", "view hidden");
  view.setAttribute("data-view-type", "recently");
  document.querySelector(".tab-view").appendChild(view);

  fetch("/get_recently")
    .then(response => response.json())
    .then(json => {
      if (json.data.length) {
        viewPosts(json.data, view);
      } else {
        const hintBox = document.createElement("p");
        hintBox.textContent = "Nessuna attività recente";
        view.appendChild(hintBox);
      }
    });
}

function watchedFilms(view) {
  const watchedFilmsBox = document.createElement("div");
  watchedFilmsBox.setAttribute("class", "summary-box");
  view.appendChild(watchedFilmsBox);

  const titleBox = document.createElement("h1");
  titleBox.textContent = "Film visti";
  watchedFilmsBox.appendChild(titleBox);
  fetch("/get_watched_movies")
    .then(response => response.json())
    .then(json => {
      if (json.data.length) {
        const posterBox = document.createElement("div");
        posterBox.classList.add("movie-poster-box");
        watchedFilmsBox.appendChild(posterBox);

        for (let film of json.data) {
          fetch("/get_movie_poster/" + film.type_id)
            .then(response => response.json())
            .then(json => {
              const poster = document.createElement("img");
              poster.classList.add("movie-poster");
              poster.src =  json.src;
              posterBox.appendChild(poster);
            })
        }
      } else {
        const hintBox = document.createElement("p");
        hintBox.textContent = "Non sono stati visti film";

        watchedFilmsBox.appendChild(hintBox);
      }
    });
}

function watchlist(view) {
  const watchlist = document.createElement("div");
  watchlist.classList.add("summary-box");
  view.appendChild(watchlist);

  const titleBox = document.createElement("h1");
  titleBox.textContent = "Watchlist";
  watchlist.appendChild(titleBox);

  fetch("/get_watchlist")
    .then(response => response.json())
    .then(json => {
      if (json.data.length) {
        const posterBox = document.createElement("div");
        posterBox.classList.add("movie-poster-box");
        watchlist.appendChild(posterBox);

        for (let film of json.data) {
          fetch("/get_movie_poster/" + film.type_id)
            .then(response => response.json())
            .then(json => {
              const poster = document.createElement("img");
              poster.classList.add("movie-poster");
              poster.src = json.src;
              posterBox.appendChild(poster);
            })
        }
      } else {
        const hintBox = document.createElement("p");
        hintBox.textContent = "Non ci sono film nella watchlist";
        watchlist.appendChild(hintBox);
      }
    });
}

function follower(view) {
  const followerBox = document.createElement("div");
  followerBox.setAttribute("class", "summary-box follower");
  view.appendChild(followerBox);

  const titleBox = document.createElement("h1");
  titleBox.textContent = "Seguaci";
  followerBox.appendChild(titleBox);

  fetch("/get_follower")
    .then(response =>  response.json())
    .then(json => {
      if (json.data.length) {
        const followers = json.data;

        const followerPicBox = document.createElement("div");
        followerPicBox.classList.add("follower-pic-box");
        followerBox.appendChild(followerPicBox);
        for (let follower of followers) {
          const followerProfile = document.createElement("a");
          followerProfile.setAttribute("href", "/profile/" + follower.id);
          followerPicBox.appendChild(followerProfile);
          const pic = document.createElement("img");
          pic.classList.add("summary-profile-pic")
          const profilePic = follower.profile_pic ?
            'data:image/jpg;charset=utf8;base64,' + follower.profile_pic :
            '/figures/fallback_profile_icon.png';
          pic.src = profilePic;
          followerProfile.appendChild(pic);
        }
      } else {
        const hintBox = document.createElement("p");
        hintBox.textContent = "Nessun seguace";
        followerBox.appendChild(hintBox, document.querySelector("div.summary-box.following"));
      }
    });
}

function following(view) {
  const followingBox = document.createElement("div");
  followingBox.setAttribute("class", "summary-box following");
  view.appendChild(followingBox);

  const titleBox = document.createElement("h1");
  titleBox.textContent = "Seguiti";
  followingBox.appendChild(titleBox);

  fetch("/get_followed")
    .then(response => response.json())
    .then(json => {
      if (json.data.length) {
        const followed = json.data;

        const followingPicBox = document.createElement("div");
        followingPicBox.classList.add("following-pic-box");
        followingBox.appendChild(followingPicBox);

        for (let following of followed) {
          const followingProfile = document.createElement("a");
          followingProfile.setAttribute("href", "/profile/" + following.id);
          followingPicBox.appendChild(followingProfile);
          const pic = document.createElement("img");
          pic.classList.add("summary-profile-pic")
          const profilePic = following.profile_pic ?
            'data:image/jpg;charset=utf8;base64,' + following.profile_pic :
            '/figures/fallback_profile_icon.png';
          pic.src = profilePic;
          followingProfile.appendChild(pic);
        }
      } else {
        const hintBox = document.createElement("p");
        hintBox.textContent = "Nessun seguito";
        followingBox.appendChild(hintBox);
      }
    });
}

function getUsername() {
  const image = document.querySelector('.icon-box img.icon');
  image.addEventListener('click', onFollowButton);
}

function createSummary() {
  const view = document.createElement("div");
  view.setAttribute("class", "view");
  view.setAttribute("data-view-type", "summary");
  document.querySelector(".tab-view").appendChild(view);

  watchedFilms(view);
  watchlist(view);
  follower(view);
  following(view);
}

const views = document.querySelectorAll(".tab-row-option");
for (let view of views) {
  view.addEventListener('click', event => {
    const lastSelected = document.querySelector(".tab-row-option.selected");
    lastSelected.classList.remove("selected");
    document.querySelector("div.view[data-view-type="
      + lastSelected.dataset.viewType + "]").classList.add("hidden");

    event.currentTarget.classList.add("selected");
    document.querySelector("div.view[data-view-type=" +
        event.currentTarget.dataset.viewType + "]")
        .classList.remove("hidden");
  });
}

const selected = document.querySelector(".tab-row-option.selected");

let loggedProfile = true ;
const image = document.querySelector('.icon-box img.icon');
if (image) {
    image.addEventListener('click', onFollowButton);
    loggedProfile = false;
}
createSummary();
createRecently();
setPreferences()
