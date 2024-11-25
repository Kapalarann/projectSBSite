

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
