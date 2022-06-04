function displayPost(post, view) {
  const postCurr = document.createElement("div");
  postCurr.setAttribute("id", post.id);
  postCurr.classList.add("post");
  view.appendChild(postCurr);

  const postHeader = document.createElement("div");
  postHeader.setAttribute("class", "post-header");
  const postHeaderLeft = document.createElement("div");
  postHeaderLeft.setAttribute("class", "post-header-left");
  postHeader.appendChild(postHeaderLeft);

  const profilePicBox = document.createElement("div");
  const profilePic = document.createElement("img");
  profilePicBox.appendChild(profilePic);
  postHeaderLeft.appendChild(profilePicBox);
  fetch("http://localhost/hw1/get_pics.php?user_id=" + post.user)
    .then(response => response.json())
    .then(json => {
      if (!json.profile_pic.empty) {
        profilePicBox.classList.add("post-profile-pic-box")
        profilePic.setAttribute("id", "post-" + post.id + "-profile-pic");
        profilePic.setAttribute("class", "post-profile-pic");
        profilePic.src = 'data:image/jpg;charset=utf8;base64,' + json.profile_pic.src;
      }
    });

  const postProfileName = document.createElement("div");
  postHeaderLeft.appendChild(postProfileName);
  fetch("http://localhost/hw1/username.php?user_id=" + post.user)
    .then(response => response.json())
    .then(json => {
      if (json.success) {
        postProfileName.setAttribute("id", "post-" + post.id + "-profile-name");
        postProfileName.setAttribute("class", "post-profile-name");
        postProfileName.textContent = json.username;
      }
    });
  postCurr.appendChild(postHeader);

  const postText = document.createElement("p");
  postText.textContent = post.content;
  postCurr.appendChild(postText);

  const postPicBox = document.createElement("div");
  postPicBox.setAttribute("id", "post-pic-box");
  const postPic = document.createElement("img");
  postPicBox.appendChild(postPic);
  postCurr.appendChild(postPicBox);

  fetch("http://localhost/hw1/get_movie_poster.php?movie_id=" + post.type_id)
    .then(response => response.json())
    .then(json => {
      if (json.success) {
        console.log(json.src);
        postPic.src = json.src;
      }
    });

  const postFoot = document.createElement("div");
  postCurr.appendChild(postFoot);
}

function viewPosts(data, view) {
  for (let post of data) {
    displayPost(post, view);
  }
}
