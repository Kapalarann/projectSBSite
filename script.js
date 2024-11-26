

$(document).ready(function () {
    // Smooth scrolling for section
    $(".navbar a").on("click", function (e) {
        if (this.hash !== "") {
            e.preventDefault();
            const hash = this.hash;
            $("html, body").animate(
                {
                    scrollTop: $(hash).offset().top,
                },
                800
            );
        }
    });

    // Handle form submission
    $("#contactForm").on("submit", function (e) {
        e.preventDefault();
        $("#formMessage").text("Form submitted successfully!");
    });
});

let currentSlide = 0;
const radios = document.querySelectorAll("input[type='radio']");
const totalSlides = radios.length;

setInterval(() => {
  radios[currentSlide].checked = false;  // Uncheck the current radio button
  currentSlide = (currentSlide + 1) % totalSlides;  // Move to the next slide, looping back to the start
  radios[currentSlide].checked = true;   // Check the next radio button
}, 3000);  // Change slide every 3 seconds
