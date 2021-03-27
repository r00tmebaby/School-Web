<div class="slider">
    <ul class="bxslider">

        <li class="slide">
            <div class="container">
                <div class="info">
                    <h2>Време е за <br><span>избор на училище?</span></h2>
                    <a href="/specialties">Вижте нашите специалности</a>
                </div>
            </div>
        </li>

        <li class="slide">
            <div class="container">
                <div class="info">
                    <h2>Интересни <br><span>събития и изяви</span></h2>
                    <a href="/events">Вижте повече тук</a>
                </div>
            </div>

        </li>

        <li class="slide">
            <div class="container">
                <div class="info">
                    <h2>Проекти <br><span>у нас и в чужбина</span></h2>
                    <a href="/projects">Вижте повече тук</a>
                </div>
            </div>
        </li>
    </ul>
    <div class="bg-bottom"></div>
</div>


<section class="posts">
    <div class="container">
        <?php include "posts.php"; ?>
    </div>
    <!-- / container -->
</section>

<section class="news">
    <div class="container">
        <h2>Последни Новини</h2>
        <?php showNews(2); ?>
        <div class="btn-holder">
            <a class="btn" href="/news">Виж всички новини</a>
        </div>
    </div>
    <!-- / container -->
</section>

<section class="events">
    <div class="container">
        <h2>Бъдещи събития</h2>
        <?php showEvents(2); ?>
        <div class="btn-holder">
            <a class="btn blue" href="/events">Виж всички бъдещи събития</a>
        </div>
    </div>
    <!-- / container -->
</section>


<script>
    var slideIndex = 0;
    showSlides();

    function showSlides() {
        var i;
        var slides = document.getElementsByClassName("slide");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].className = slides[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        slides[slideIndex - 1].className += " active";
        setTimeout(showSlides, 4000); // Change image every 4 seconds
    }
</script>

