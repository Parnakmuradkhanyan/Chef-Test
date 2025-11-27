document.addEventListener("DOMContentLoaded", function() {
    const favButtons = document.querySelectorAll(".add-delete-favourite-recipe");

    favButtons.forEach(btn => {
        btn.addEventListener("click", function() {

            const dishId = this.getAttribute("data-dish-id");

            fetch("toggle_favourite.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "dish_id=" + dishId
            })

            .then(res => res.json())
            .then(data => {
                if (data.status === "removed") {
                    this.style.backgroundImage = "url(../icons/favourite-recipies-icon-not-active-on-fav-recipies-page.svg)";
                    this.classList.remove("active");

                    const card = this.closest(".recomended-recipe-container");
                    
                    if (card) {
                        card.remove();
                    }
                    
                } else if (data.status === "added") {
                    this.style.backgroundImage = "url(../icons/favourite-recipies-icon-active-on-fav-recipies-page.svg)";
                    this.classList.add("active");
                }
            });

        });
    });
});