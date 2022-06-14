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

function getPics() {
  fetch("http://localhost/hw1/get_pics.php")
    .then(response => response.json())
    .then(json => {
      if (!json.cover_pic.empty) {
        const cover = document.getElementById("cover");
        const path = 'data:image/jpg;charset=utf8;base64,' + json.cover_pic.src;
        cover.style.backgroundImage = "url(" + path + ")";
      } else {
        // TODO: add button to add cover
      }
      if (!json.profile_pic.empty) {
        const profile_pic = document.getElementById("profile-pic");
        const img = document.createElement("img");
        img.src = 'data:image/jpg;charset=utf8;base64,' + json.profile_pic.src;
        profile_pic.appendChild(img);
      } else {
        // TODO: fallback icon and add button to add profile pic
      }
    });
}

function createSettings() {
  const tabRow = document.querySelector(".tab-row");
  const tabOption = document.createElement("div");
  tabOption.classList.add("tab-row-option");
  tabOption.setAttribute("data-view-type", "settings");
  tabOption.textContent = "Impostazioni";
  tabRow.appendChild(tabOption);

  const view = document.createElement("div");
  view.setAttribute("class", "view hidden");
  view.setAttribute("data-view-type", "settings");
  document.querySelector(".tab-view").appendChild(view);
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
          pic.src = 'data:image/jpg;charset=utf8;base64,' + follower.profile_pic;
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
      if (json.data) {
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
          pic.src = 'data:image/jpg;charset=utf8;base64,' + following.profile_pic;
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
  // const username = document.getElementById("username");
  // await fetch("http://localhost/hw1/username.php")
  //   .then(response => response.json())
  //   .then(json => {
  //     if (json.success) {
  //       username.textContent = json.username;
  //     }
  //   });

  // fetch("http://localhost/hw1/followed.php")
  //   .then(response => response.json())
  //   .then(json => {
  //     if (json.success) {
        // const box = document.createElement('div');
        // box.classList.add('icon-box');
        // username.appendChild(box);
        const image = document.querySelector('.icon-box img.icon');
        image.addEventListener('click', onFollowButton);
      //   image.src = image.dataset.followed === 'true' ? 'figures/followed_dark.png' : 'figures/not_followed_dark.png';
      //   box.appendChild(image)
      // }
    // })
}

function createSummary() {
  const view = document.createElement("div");
  view.setAttribute("class", "view");
  view.setAttribute("data-view-type", "summary");
  document.querySelector(".tab-view").appendChild(view);

  watchedFilms(view);
  // readBooks();
  // listenedMusic();
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
createNav('profile');
// getPics();
// getUsername();
const image = document.querySelector('.icon-box img.icon');
if (image) {
    image.addEventListener('click', onFollowButton);
    loggedProfile = false;
}
createSummary();
createRecently();
