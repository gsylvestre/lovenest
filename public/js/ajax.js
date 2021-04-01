function sendLike(ev){
    let clickedButton = ev.currentTarget;
    let userId = clickedButton.dataset.userId;
    axios.post("http://localhost/lovenest/public/api/heart/", {
        "sentToId": userId
    });
}

let likeButton = document.querySelector(".like-button");
likeButton.addEventListener("click", sendLike);
