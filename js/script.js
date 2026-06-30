/*=========================================
    ELECTROSHOP JAVASCRIPT
==========================================*/

document.addEventListener("DOMContentLoaded", function () {

    console.log("ElectroShop Ready!");

});


/*=========================================
    HERO SLIDER AUTO
==========================================*/

const heroImages = [

    "../img/banner/banner1.jpg",

    "../img/banner/banner2.jpg",

    "../img/banner/banner3.jpg",

    "../img/banner/banner4.jpg"

];

let heroIndex = 0;

const heroImage = document.querySelector(".hero-slider img");

if(heroImage){

    setInterval(function(){

        heroIndex++;

        if(heroIndex >= heroImages.length){

            heroIndex = 0;

        }

        heroImage.style.opacity = 0;

        setTimeout(function(){

            heroImage.src = heroImages[heroIndex];

            heroImage.style.opacity = 1;

        },300);

    },4000);

}


/*=========================================
    PRODUCT GALLERY
==========================================*/

const mainImage = document.querySelector(".main-image img");

const thumbs = document.querySelectorAll(".thumb-list img");

thumbs.forEach(function(item){

    item.addEventListener("click",function(){

        mainImage.src = this.src;

    });

});


/*=========================================
    COUNTDOWN FLASH SALE
==========================================*/

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


/*=========================================
    BACK TO TOP BUTTON
==========================================*/

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


/*=========================================
    STICKY HEADER EFFECT
==========================================*/

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


/*=========================================
    BUTTON RIPPLE EFFECT
==========================================*/

const buttons=document.querySelectorAll("button");

buttons.forEach(function(btn){

    btn.addEventListener("click",function(){

        btn.style.transform="scale(.95)";

        setTimeout(function(){

            btn.style.transform="scale(1)";

        },120);

    });

});


/*=========================================
    IMAGE FADE
==========================================*/

const images=document.querySelectorAll("img");

images.forEach(function(img){

    img.style.transition=".4s";

});


/*=========================================
    PRELOADER
==========================================*/

window.onload=function(){

    const loader=document.getElementById("loader");

    if(loader){

        loader.style.opacity="0";

        setTimeout(function(){

            loader.remove();

        },600);

    }

}
/*=========================================================
    SEARCH BOX
=========================================================*/

const searchInput = document.querySelector(".search-box input");

if(searchInput){

    searchInput.addEventListener("focus",function(){

        this.style.boxShadow="0 0 15px rgba(227,0,25,.25)";

    });

    searchInput.addEventListener("blur",function(){

        this.style.boxShadow="none";

    });

}


/*=========================================================
    PRODUCT IMAGE HOVER
=========================================================*/

document.querySelectorAll(".product-card img").forEach(function(img){

    img.addEventListener("mouseenter",function(){

        this.style.transform="scale(1.1) rotate(2deg)";

    });

    img.addEventListener("mouseleave",function(){

        this.style.transform="scale(1)";

    });

});


/*=========================================================
    HEADER HIDE / SHOW
=========================================================*/

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


/*=========================================================
    ACTIVE MENU
=========================================================*/

const menuLinks=document.querySelectorAll(".navbar-menu a");

menuLinks.forEach(function(link){

    link.addEventListener("click",function(){

        menuLinks.forEach(function(item){

            item.classList.remove("active");

        });

        this.classList.add("active");

    });

});


/*=========================================================
    QUANTITY BUTTON
=========================================================*/

const quantityInput=document.querySelector(".quantity input");

if(quantityInput){

    quantityInput.addEventListener("change",function(){

        if(this.value<1){

            this.value=1;

        }

    });

}


/*=========================================================
    FADE UP WHEN SCROLL
=========================================================*/

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


/*=========================================================
    NEWSLETTER
=========================================================*/

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


/*=========================================================
    ADD TO CART DEMO
=========================================================*/

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


/*=========================================================
    BUTTON LOADING
=========================================================*/

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


/*=========================================================
    COPYRIGHT YEAR
=========================================================*/

const year=document.getElementById("year");

if(year){

    year.innerHTML=new Date().getFullYear();

}


/*=========================================================
    CONSOLE
=========================================================*/

console.log("%cElectroShop","font-size:28px;color:red;font-weight:bold;");

console.log("Frontend Version 1.0");

console.log("Developed with PHP + HTML + CSS + JavaScript");