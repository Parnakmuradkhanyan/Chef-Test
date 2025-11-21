document.addEventListener("DOMContentLoaded", function() {
    const likeBtn = document.querySelector(".like-btn");
        if (likeBtn) {
                likeBtn.addEventListener("click", function() {
                    const dishId = this.getAttribute("data-dish-id");

                    fetch("toggle_favourite.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "dish_id=" + dishId
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "added") {
                            this.style.backgroundImage = "url(../icons/favourite-recipies-icon-active-on-recipies-page.svg)";
                            this.classList.add("active");
                        } else if (data.status === "removed") {
                            this.style.backgroundImage = "url(../icons/favourite-recipies-icon-not-active-on-recipies-page.svg)";
                            this.classList.remove("active");
                        }
                    });
                });
            }
        });