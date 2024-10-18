console.log("from script file");

//Enable pop Up Window and Text
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
        trigger : 'hover',
        placement : 'top',
        });   
});

$(document).ready(function() {

// Smooth scroll for navigation links
    $('nav ul li a').click(function(e) {
        e.preventDefault();
        const targetSection = $(this).attr('href');

        $('html, body').animate({
            scrollTop: $(targetSection).offset().top - 50}, 800);
    });

    // Show more content when "Learn More" button is clicked
    $('#learnMore').click(function() {
        $('#hiddenContent').slideToggle();
    });

    // Load extra features dynamically
    $('#loadFeatures').click(function() {
        $('#extraFeatures').fadeToggle();
    });

    // Form validation and submission handling
    $('#contactForm').submit(function(e) {
        e.preventDefault();
        const name = $('#name').val();
        const email = $('#email').val();
             
        if (name && email) {
            $('#formMessage').text(`Thank you, ${name}. We will contact you at ${email}.`);
        } else {
            $('#formMessage').text('Please fill out the form completely.');
        }
    });
});

