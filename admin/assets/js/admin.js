/*=====================================
    ELECTROSHOP ADMIN
======================================*/

document.addEventListener("DOMContentLoaded", () => {

    console.log("ElectroShop Admin Ready");

});


/*=====================================
    ACTIVE SIDEBAR
======================================*/

const currentPage = window.location.pathname;

document.querySelectorAll(".sidebar ul li a").forEach(link=>{

    if(currentPage.includes(link.getAttribute("href"))){

        link.parentElement.classList.add("active");

    }

});


/*=====================================
    CARD HOVER
======================================*/

document.querySelectorAll(".card").forEach(card=>{

    card.addEventListener("mouseenter",()=>{

        card.style.transform="translateY(-8px)";

    });

    card.addEventListener("mouseleave",()=>{

        card.style.transform="translateY(0px)";

    });

});


/*=====================================
    TABLE ROW HOVER
======================================*/

document.querySelectorAll("tbody tr").forEach(row=>{

    row.addEventListener("mouseenter",()=>{

        row.style.background="#fff5f5";

    });

    row.addEventListener("mouseleave",()=>{

        row.style.background="white";

    });

});


/*=====================================
    CONFIRM DELETE
======================================*/

document.querySelectorAll(".delete-btn").forEach(btn=>{

    btn.addEventListener("click",(e)=>{

        if(!confirm("Bạn có chắc muốn xóa?")){

            e.preventDefault();

        }

    });

});


/*=====================================
    SEARCH TABLE
======================================*/

const search=document.getElementById("search");

if(search){

search.addEventListener("keyup",function(){

let value=this.value.toLowerCase();

document.querySelectorAll("tbody tr").forEach(row=>{

row.style.display=row.innerText.toLowerCase().includes(value)

?""

:"none";

});

});

}


/*=====================================
    AUTO CLOSE ALERT
======================================*/

const alertBox=document.querySelector(".alert");

if(alertBox){

setTimeout(()=>{

alertBox.style.opacity="0";

setTimeout(()=>{

alertBox.remove();

},500);

},3000);

}


/*=====================================
    SCROLL TOP
======================================*/

const topBtn=document.createElement("button");

topBtn.innerHTML='<i class="fa-solid fa-arrow-up"></i>';

topBtn.className="scrollTop";

document.body.appendChild(topBtn);

window.addEventListener("scroll",()=>{

topBtn.style.display=window.scrollY>300

?"flex"

:"none";

});

topBtn.onclick=()=>{

window.scrollTo({

top:0,

behavior:"smooth"

});

};


/*=====================================
    BUTTON CLICK
======================================*/

document.querySelectorAll("button").forEach(btn=>{

btn.addEventListener("click",()=>{

btn.style.transform="scale(.96)";

setTimeout(()=>{

btn.style.transform="scale(1)";

},120);

});

});


/*=====================================
    LIVE CLOCK
======================================*/

const clock=document.getElementById("clock");

if(clock){

setInterval(()=>{

const d=new Date();

clock.innerHTML=d.toLocaleString("vi-VN");

},1000);

}


/*=====================================
    SIDEBAR COLLAPSE
======================================*/

const toggle=document.getElementById("toggleMenu");

const sidebar=document.querySelector(".sidebar");

if(toggle){

toggle.onclick=function(){

sidebar.classList.toggle("collapse");

};

}


/*=====================================
    COUNTER ANIMATION
======================================*/

document.querySelectorAll(".card h3").forEach(counter=>{

let target=parseInt(counter.innerText.replace(/\D/g,""));

if(isNaN(target)) return;

let current=0;

let speed=Math.ceil(target/60);

let timer=setInterval(()=>{

current+=speed;

if(current>=target){

current=target;

clearInterval(timer);

}

counter.innerText=current;

},20);

});


/*=====================================
    IMAGE PREVIEW
======================================*/

const upload=document.getElementById("image");

const preview=document.getElementById("preview");

if(upload){

upload.onchange=function(){

const reader=new FileReader();

reader.onload=function(e){

preview.src=e.target.result;

}

reader.readAsDataURL(upload.files[0]);

}

}


/*=====================================
    LOG
======================================*/

console.log("%cElectroShop Admin","color:red;font-size:24px;font-weight:bold;");