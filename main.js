const navbar = document.getElementById("navbar");

window.addEventListener("scroll", () => {

    if (window.scrollY > 30) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }

});


/* Mobile Menu */

const menuBtn = document.getElementById("menuBtn");
const mobileMenu = document.getElementById("mobileMenu");

menuBtn.addEventListener("click", () => {

    mobileMenu.classList.toggle("active");

});


/* Search */

const searchBtn = document.getElementById("searchBtn");
const searchOverlay = document.getElementById("searchOverlay");
const closeSearch = document.getElementById("closeSearch");
const searchInput = document.getElementById("searchInput");

searchBtn.addEventListener("click", () => {

    searchOverlay.classList.add("active");

    setTimeout(() => {
        searchInput.focus();
    }, 300);

});


closeSearch.addEventListener("click", () => {

    searchOverlay.classList.remove("active");

});


document.addEventListener("keydown", (event) => {

    if (event.key === "Escape") {
        searchOverlay.classList.remove("active");
    }

});


/* Shopping Cart */

let cartCount = 0;

const cartButtons = document.querySelectorAll(".add-cart");
const cartCounter = document.getElementById("cartCount");
const toast = document.getElementById("toast");

cartButtons.forEach(button => {

    button.addEventListener("click", () => {

        const productName = button.dataset.product;
        const price = button.dataset.price;

        cartCount++;

        cartCounter.textContent = cartCount;

        const cart = JSON.parse(
            localStorage.getItem("karenCart")
        ) || [];

        cart.push({
            name: productName,
            price: Number(price)
        });

        localStorage.setItem(
            "karenCart",
            JSON.stringify(cart)
        );

        toast.classList.add("show");

        setTimeout(() => {
            toast.classList.remove("show");
        }, 2500);

    });

});


/* Wishlist */

const wishlistButtons =
    document.querySelectorAll(".wishlist");

wishlistButtons.forEach(button => {

    button.addEventListener("click", () => {

        if (button.textContent.trim() === "♡") {
            button.textContent = "♥";
            button.style.color = "#a83232";
        } else {
            button.textContent = "♡";
            button.style.color = "";
        }

    });

});


/* Newsletter */

const newsletterForm =
    document.getElementById("newsletterForm");

const newsletterMessage =
    document.getElementById("newsletterMessage");

newsletterForm.addEventListener("submit", (event) => {

    event.preventDefault();

    newsletterMessage.textContent =
        "Thank you for joining our community.";

    newsletterForm.reset();

});


/* Hero Animation */

window.addEventListener("load", () => {

    const heroContent =
        document.querySelector(".hero-content");

    heroContent.style.opacity = "0";
    heroContent.style.transform = "translateY(25px)";

    setTimeout(() => {

        heroContent.style.transition =
            "opacity 1s ease, transform 1s ease";

        heroContent.style.opacity = "1";
        heroContent.style.transform =
            "translateY(0)";

    }, 200);

});