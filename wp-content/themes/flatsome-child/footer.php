<?php

/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */
global $flatsome_opt;
?>
</main><!-- #main -->


<?php
global $sitepress;
$current_language = $sitepress->get_current_language();

if ($current_language == 'vi') {
    echo do_shortcode('[block id="chan-trang"]');
}
if ($current_language == 'en') {
    echo do_shortcode('[block id="footer"]');
}



?>



<script>
 jQuery('.slider-for1').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 3000,
        fade: true,
        asNavFor: '.slider-nav1'
    });
    jQuery('.slider-nav1').slick({
        slidesToShow: 7,
        slidesToScroll: 1,
        centerMode: true,
        autoplay: true,
        autoplaySpeed: 3000,
        centerPadding: '20px',
        asNavFor: '.slider-for1',
        dots: false,
        focusOnSelect: true
    });
    // const container = document.getElementById("myPanzoom");
    // const options = { click: "toggleCover" };

    // new Panzoom(container, options);

    jQuery('.navbtnMenu').click(function(e) {
        e.preventDefault();
        let target = jQuery(this).attr('href');
        jQuery(".navbtnMenu").removeClass("active");
        jQuery(this).addClass("active");
        target = target.replace(/.+(?=#)/g, '');
        jQuery('html, body').css('scroll-behavior', 'auto').animate({
            'scrollTop': jQuery(target).offset().top - 120
        }, 800);
    })


    window.onload = function() {
        const sections = document.querySelectorAll(".productBoxCusbox");
        const navA = document.querySelectorAll(".tabBox a");

        function updateActiveSection() {
            let maxVisibleArea = 0;
            let activeSection = null;

            sections.forEach((section) => {
                const visibleArea = getVisibleArea(section);

                if (visibleArea > maxVisibleArea) {
                    maxVisibleArea = visibleArea;
                    activeSection = section.getAttribute("id");
                }
            });

            navA.forEach((a) => {
                a.classList.remove("active");
                if (a.classList.contains(activeSection)) {
                    a.classList.add("active");
                }
            });
        }

        function getVisibleArea(element) {
            const rect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            const windowWidth = window.innerWidth || document.documentElement.clientWidth;
            const visibleHeight = Math.min(rect.bottom, windowHeight) - Math.max(rect.top, 0);
            const visibleWidth = Math.min(rect.right, windowWidth) - Math.max(rect.left, 0);
            return visibleHeight * visibleWidth;
        }

        window.addEventListener("scroll", updateActiveSection);
        window.addEventListener("resize", updateActiveSection);
    };
</script>

<script>
    function stKeyupsmartSearch(event) {

        // Declare variables
        var input, filter, ul, li, a, i, txtValue;
        input = event.value.toUpperCase();
        filter = event.value.toUpperCase();
        parent = event.closest(".form-extra-field");
        ul = parent.getElementsByTagName('ul')[0];
        li = ul.getElementsByTagName('li');

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            //a = li[i].getElementsByTagName("a")[0];
            txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
    
    jQuery('.field-detination').each(function() {
        var parent = jQuery(this);
        var dropdown_menu = jQuery('.dropdown-menu', parent);
        jQuery('li', dropdown_menu).on('click', function() {
       
            var target = jQuery(this).closest('ul.dropdown-menu').attr('aria-labelledby');
            var focus = parent.find('#' + target);

            jQuery('.destination', focus).text(jQuery(this).find('span').text());
            jQuery('input[name="location_name"]', focus).val(jQuery(this).find('span').text());
            jQuery('input.location_name', focus).val(jQuery(this).find('span').text());
            jQuery('input[name="location_id"]', focus).val(jQuery(this).data('value'));
            jQuery('input.location_id', focus).val(jQuery(this).data('value'));
            if (window.matchMedia('(max-width: 767px)').matches) {
                jQuery('label', focus).hide();
                jQuery('.render', focus).show();
            }
            dropdown_menu.slideUp(50);
        });
    });

    jQuery("#st_location_name_tour").click(function(e) {
        jQuery('.form-extra-field .dropdown-menu').show();
    });
    jQuery('.field-durations').each(function() {
        var parent = jQuery(this);
        var dropdown_menu = jQuery('.dropdown-menu', parent);
        jQuery('li', dropdown_menu).on('click', function() {
            var target = jQuery(this).closest('ul.dropdown-menu').attr('aria-labelledby');
            var focus = parent.find('#' + target);
            jQuery('.durations', focus).text(jQuery(this).find('span').text());

            jQuery('input[name="time"]', focus).val(jQuery(this).data('value'));
            if (window.matchMedia('(max-width: 767px)').matches) {
                jQuery('label', focus).hide();
                jQuery('.render', focus).show();
            }
            dropdown_menu.slideUp(50);
        });
    });

    jQuery('.bloghot-home').owlCarousel({
        loop: true,
        margin: 30,
        dots: false,
        nav: false,
        autoplay: 5000,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,

            },
            600: {
                items: 3,

            },
            1000: {
                items: 3,


            }
        }
    })

    jQuery('.tour-detail').owlCarousel({
        loop: true,
        margin: 30,
        dots: false,
        nav: false,
        autoplay: 1000,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,

            },
            600: {
                items: 1,

            },
            1000: {
                items: 1,


            }
        }
    })
    jQuery(".reviews-owl").owlCarousel({
        nav: true,
        margin: 30,
        // navText: ["<img src='/templates/home/images/reviewleft.svg' />", "<img src='/templates/home/images/reviewright.svg' />"],
        autoplay: 5000,
        loop: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
            },
            543: {
                items: 1,
                nav: false,

            },
            768: {
                items: 2
            },
            991: {
                items: 3
            },
            992: {
                items: 3
            },
            1300: {
                items: 3
            },
            1590: {
                items: 3
            }
        }
    });


    jQuery('.home-prohot-owl').owlCarousel({
        loop: true,
        margin: 30,
        dots: true,
        nav: true,
        loop: true,

        autoplay: 5000,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 3,

            },
            1000: {
                items: 4,


            }
        }
    })
    jQuery('.partner-owl').owlCarousel({
        loop: true,
        margin: 30,
        autoplay: 5000,
        dots: false,
        nav: false,
        loop: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2,

            },
            600: {
                items: 3,

            },
            1000: {
                items: 5,


            }
        }
    })
</script>


</div><!-- #wrapper -->
<?php wp_footer(); ?>
</body>

</html>