function sendLike(ev){
    let clickedButton = ev.currentTarget;
    let action = clickedButton.dataset.action;

    if (action === "like"){
        let userId = clickedButton.dataset.userId;

        axios.post("http://localhost/lovenest/public/api/heart/", {
            "sentToId": userId,
        }).
        then(function(response){
            console.log(response);
            if (response.data.status === "ok"){
                clickedButton.dataset.action = "unlike";
                clickedButton.dataset.heartId = response.data.data.id;
                console.log("ok !");
            }
        });
    }
    else if (action === "unlike"){
        let heartId = clickedButton.dataset.heartId;

        axios.delete("http://localhost/lovenest/public/api/heart/" + heartId, {
        }).
        then(function(response){
            console.log(response);
            if (response.data.status === "ok"){
                clickedButton.dataset.action = "like";
                clickedButton.dataset.heartId = null;
                console.log("ok !");
            }
        });
    }
}

let likeButtons = document.querySelectorAll(".like-button");
likeButtons.forEach(function(likeButton){
    likeButton.addEventListener("click", sendLike);
})
