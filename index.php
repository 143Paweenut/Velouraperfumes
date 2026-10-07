            </div>

        </div>

    </div>


    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <script>

        /* =========================
           POPUP
        ========================= */

        const popupOverlay =
            document.getElementById("popupOverlay");

        const popupClose =
            document.getElementById("popupClose");

        const popupSlides =
            document.getElementById("popupSlides");

        const popupDots =
            document.querySelectorAll(".popup-dot");

        let currentSlide = 0;


        function showSlide(index){

            currentSlide = index;

            popupSlides.style.transform =
                "translateX(-" + (index * 100) + "%)";

            popupDots.forEach((dot,i)=>{

                dot.classList.toggle(
                    "active",
                    i === index
                );

            });

        }


        popupDots.forEach(dot => {

            dot.addEventListener("click",function(){

                showSlide(
                    parseInt(this.dataset.slide)
                );

            });

        });


        popupClose.addEventListener("click",function(){

            popupOverlay.classList.remove("show");

            sessionStorage.setItem(
                "veloura_popup_closed",
                "1"
            );

        });


        popupOverlay.addEventListener("click",function(e){

            if(e.target === popupOverlay){

                popupOverlay.classList.remove("show");

                sessionStorage.setItem(
                    "veloura_popup_closed",
                    "1"
                );

            }

        });


        /* แสดง Popup ครั้งแรก */

        window.addEventListener("load",function(){

            const popupClosed =
                sessionStorage.getItem(
                    "veloura_popup_closed"
                );

            if(!popupClosed){

                setTimeout(function(){

                    popupOverlay.classList.add("show");

                },1000);

            }

        });

    </script>

</body>

</html>
