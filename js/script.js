

document.addEventListener("DOMContentLoaded", function () {

    console.log("ElectroShop Ready!");

    const toastElement = document.querySelector("#appToast");
    const cartCountElement = document.querySelector(".cart-count");
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn[data-product-id]");
    const copyTransferButtons = document.querySelectorAll(".copy-transfer-btn[data-copy-text]");
    const transferBox = document.querySelector("[data-transfer-focus='1']");

    function showToast(message, type) {
        if (!toastElement || !message) {
            return;
        }

        toastElement.textContent = message;
        toastElement.classList.remove("is-visible", "is-success", "is-error");
        toastElement.classList.add(type === "error" ? "is-error" : "is-success");

        window.clearTimeout(showToast.hideTimer);
        requestAnimationFrame(function () {
            toastElement.classList.add("is-visible");
        });

        showToast.hideTimer = window.setTimeout(function () {
            toastElement.classList.remove("is-visible");
        }, 2200);
    }

    if (transferBox) {
        setTimeout(function () {
            transferBox.scrollIntoView({ behavior: "smooth", block: "center" });
            transferBox.focus({ preventScroll: true });
        }, 250);
    }

    addToCartButtons.forEach(function (button) {
        button.addEventListener("click", async function (event) {
            event.preventDefault();

            const productId = Number(button.dataset.productId || 0);
            if (productId <= 0 || button.dataset.loading === "1") {
                return;
            }

            const originalContent = button.innerHTML;
            button.dataset.loading = "1";
            button.classList.add("is-loading");
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            try {
                const response = await fetch("cart-api.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: new URLSearchParams({
                        product_id: String(productId),
                        quantity: "1"
                    })
                });

                const result = await response.json();
                if (!response.ok || !result.success) {
                    throw new Error(result.message || "Không thể thêm sản phẩm vào giỏ hàng.");
                }

                if (cartCountElement) {
                    cartCountElement.textContent = String(result.cartCount || 0);
                    cartCountElement.classList.remove("cart-count-bump");
                    void cartCountElement.offsetWidth;
                    cartCountElement.classList.add("cart-count-bump");
                }

                showToast(result.message || "Đã thêm vào giỏ hàng.", "success");

                button.classList.add("is-added");
                button.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(function () {
                    button.classList.remove("is-added");
                    button.innerHTML = originalContent;
                }, 900);
            } catch (error) {
                button.classList.add("is-error");
                button.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                showToast(error.message || "Không thể thêm vào giỏ hàng.", "error");
                setTimeout(function () {
                    button.classList.remove("is-error");
                    button.innerHTML = originalContent;
                }, 1200);
            } finally {
                delete button.dataset.loading;
                button.classList.remove("is-loading");
            }
        });
    });

    copyTransferButtons.forEach(function (button) {
        button.addEventListener("click", async function () {
            const copyText = button.dataset.copyText || "";
            if (!copyText) {
                return;
            }

            const originalLabel = button.textContent;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(copyText);
                } else {
                    const tempInput = document.createElement("textarea");
                    tempInput.value = copyText;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand("copy");
                    tempInput.remove();
                }

                button.textContent = "Đã sao chép";
                showToast("Đã sao chép nội dung chuyển khoản.", "success");
            } catch (error) {
                button.textContent = "Không sao chép được";
                showToast("Không thể sao chép nội dung chuyển khoản.", "error");
            }

            setTimeout(function () {
                button.textContent = originalLabel;
            }, 1400);
        });
    });

});




const heroImages = [

    "/LTWT6/img/uploads/1.webp",

    "/LTWT6/img/uploads/2.webp",

    "/LTWT6/img/uploads/3.webp",

    "/LTWT6/img/uploads/4.webp"

}




const mainImage = document.querySelector(".main-image img");

const thumbs = document.querySelectorAll(".thumb-list img");

thumbs.forEach(function(item){

    item.addEventListener("click",function(){

        mainImage.src = this.src;

    });

});




const countdown = document.querySelector(".countdown");

if(countdown){

    let hour = 6;

    let minute = 30;

    let second = 59;

    setInterval(function(){

        second--;

        if(second < 0){

            second = 59;

            minute--;

        }

        if(minute < 0){

            minute = 59;

            hour--;

        }

        if(hour < 0){

            hour = 6;

        }

        countdown.innerHTML =

        "<span>"+hour+"</span>"

        +" : "

        +"<span>"+minute+"</span>"

        +" : "

        +"<span>"+second+"</span>";

    },1000);

}


/*=========================================
    PRODUCT CARD HOVER
==========================================*/

const cards = document.querySelectorAll(".product-card");

cards.forEach(function(card){

    card.addEventListener("mouseenter",function(){

        this.style.transform="translateY(-12px)";

    });

    card.addEventListener("mouseleave",function(){

        this.style.transform="translateY(0px)";

    });

});




const topButton = document.createElement("button");

topButton.innerHTML = "↑";

topButton.id = "backToTop";

document.body.appendChild(topButton);

topButton.style.position="fixed";

topButton.style.right="25px";

topButton.style.bottom="25px";

topButton.style.width="55px";

topButton.style.height="55px";

topButton.style.borderRadius="50%";

topButton.style.border="none";

topButton.style.background="#E30019";

topButton.style.color="#fff";

topButton.style.cursor="pointer";

topButton.style.fontSize="22px";

topButton.style.display="none";

topButton.style.zIndex="999";


window.addEventListener("scroll",function(){

    if(window.scrollY > 500){

        topButton.style.display="block";

    }

    else{

        topButton.style.display="none";

    }

});


topButton.onclick=function(){

    window.scrollTo({

        top:0,

        behavior:"smooth"

    });

};




const header=document.querySelector(".main-header");

window.addEventListener("scroll",function(){

    if(header){

        if(window.scrollY>80){

            header.style.boxShadow="0 8px 25px rgba(0,0,0,.15)";

        }

        else{

            header.style.boxShadow="0 3px 10px rgba(0,0,0,.05)";

        }

    }

});


 

const buttons=document.querySelectorAll("button");

buttons.forEach(function(btn){

    btn.addEventListener("click",function(){

        btn.style.transform="scale(.95)";

        setTimeout(function(){

            btn.style.transform="scale(1)";

        },120);

    });

});


 

const images=document.querySelectorAll("img");

images.forEach(function(img){

    img.style.transition=".4s";

});


 

window.onload=function(){

    const loader=document.getElementById("loader");

    if(loader){

        loader.style.opacity="0";

        setTimeout(function(){

            loader.remove();

        },600);

    }

}
 

const searchInput = document.querySelector(".search-box input");

if(searchInput){

    searchInput.addEventListener("focus",function(){

        this.style.boxShadow="0 0 15px rgba(227,0,25,.25)";

    });

    searchInput.addEventListener("blur",function(){

        this.style.boxShadow="none";

    });

}


 

document.querySelectorAll(".product-card img").forEach(function(img){

    img.addEventListener("mouseenter",function(){

        this.style.transform="scale(1.1) rotate(2deg)";

    });

    img.addEventListener("mouseleave",function(){

        this.style.transform="scale(1)";

    });

});


 
let lastScroll = 0;

const headerBar = document.querySelector(".main-header");

window.addEventListener("scroll",function(){

    let current = window.pageYOffset;

    if(headerBar){

        if(current > lastScroll && current > 200){

            headerBar.style.transform="translateY(-100%)";

        }else{

            headerBar.style.transform="translateY(0)";

        }

    }

    lastScroll=current;

});


 

const menuLinks=document.querySelectorAll(".navbar-menu a");

menuLinks.forEach(function(link){

    link.addEventListener("click",function(){

        menuLinks.forEach(function(item){

            item.classList.remove("active");

        });

        this.classList.add("active");

    });

});


 

const quantityInput=document.querySelector(".quantity input");

if(quantityInput){

    quantityInput.addEventListener("change",function(){

        if(this.value<1){

            this.value=1;

        }

    });

}


 

const fadeItems=document.querySelectorAll(

".service-item,.product-card,.news-card,.testimonial-card,.category-item,.brand-grid img"

);

const observer=new IntersectionObserver(function(entries){

    entries.forEach(function(entry){

        if(entry.isIntersecting){

            entry.target.style.opacity="1";

            entry.target.style.transform="translateY(0px)";

        }

    });

},{threshold:.2});

fadeItems.forEach(function(item){

    item.style.opacity="0";

    item.style.transform="translateY(40px)";

    item.style.transition=".7s";

    observer.observe(item);

});


 

const newsletter=document.querySelector(".newsletter form");

if(newsletter){

    newsletter.addEventListener("submit",function(e){

        e.preventDefault();

        const email=this.querySelector("input").value.trim();

        if(email===""){

            alert("Vui lòng nhập Email.");

            return;

        }

        alert("Đăng ký nhận khuyến mãi thành công!");

        this.reset();

    });

}


 
let cart=0;

const cartBadge=document.querySelector(".cart-count");

document.querySelectorAll(".product-action button").forEach(function(btn){

    btn.addEventListener("click",function(){

        cart++;

        if(cartBadge){

            cartBadge.innerHTML=cart;

        }

        this.innerHTML='<i class="fa-solid fa-check"></i>';

        setTimeout(()=>{

            this.innerHTML='<i class="fa-solid fa-cart-shopping"></i>';

        },1000);

    });

});


 

document.querySelectorAll("button").forEach(function(btn){

    btn.addEventListener("click",function(){

        if(btn.classList.contains("buy-now")){

            let text=btn.innerHTML;

            btn.innerHTML="Đang xử lý...";

            btn.disabled=true;

            setTimeout(function(){

                btn.innerHTML=text;

                btn.disabled=false;

            },1500);

        }

    });

});


 

const year=document.getElementById("year");

if(year){

    year.innerHTML=new Date().getFullYear();

}


 

console.log("%cElectroShop","font-size:28px;color:red;font-weight:bold;");

console.log("Frontend Version 1.0");

console.log("Developed with PHP + HTML + CSS + JavaScript");